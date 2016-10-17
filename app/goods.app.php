<?php
/* 商品 */
class GoodsApp extends StorebaseApp
{
    var $_goods_mod;
    function __construct()
    {
        $this->GoodsApp();
    }
    function GoodsApp()
    {
        parent::__construct();
        $this->_goods_mod =& m('goods');
    }

    function index()
    {
    	$db = & db();
        /* 参数 id */
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!$id)
        {
            $this->show_warning('Hacking Attempt');
            return;
        }

        /* 可缓存数据 */
        $data = $this->_get_common_info($id);
        if ($data === false)
        {
            return;
        }
        else
        {
            $this->_assign_common_info($data);
        }
        $store_id = $data["store_data"]['store_id'];
        if(ECMALL_WAP == 1){
            $data = $this->_get_goods_comment($id, 10);
            $this->_assign_goods_comment($data);
        }
//		$goods_sql = "select * from ecm_goods where goods_id = '$id'";
//		$goods_row = $db->query($goods_sql);
//   		while($t_result = mysql_fetch_assoc($goods_row)){
//			$shipping_id[]=$t_result;
//			}
//		$shipping_id = json_decode($shipping_id[0]['shipping']);
//		if($shipping_id)
//		{
//		foreach($shipping_id as $v)
//		{
//			$shipping_sql = "select * from ecm_shipping where shipping_id = '$v'";
//			$shipping_row = $db->query($shipping_sql);
//			while($t_result = mysql_fetch_assoc($shipping_row)){
//			$shipping=$t_result;
//			}
//			$shipping_result[] = $shipping;
//		}
//		$this->assign('shipping', $shipping_result);
//		}
		$goods_shipping_sql = "select * from ecm_goods_shipping where goods_id = '$id'";//循环快递信息
		$goods_shipping_row = $db->query($goods_shipping_sql);
		while($t_result = mysql_fetch_assoc($goods_shipping_row))
		{
			$goods_shipping_result[] = $t_result;
		}
		if($goods_shipping_result)
		{
			$this->assign("shipping", $goods_shipping_result);
		}
        /* 更新浏览次数 */
        $this->_update_views($id);

        //是否开启验证码
        if (Conf::get('captcha_status.goodsqa'))
        {
            $this->assign('captcha', 1);
        }
		
		// sku tyioocom 
		$goods_pvs_mod = &m('goods_pvs');
		$props_mod = &m('props');
		$prop_value_mod = &m('prop_value');
		$goods_pvs = $goods_pvs_mod->get($id);// 取出该商品的属性字符串
		$goods_pvs_str = $goods_pvs['pvs'];
		$props = array();
		if(!empty($goods_pvs_str))
		{
			$goods_pvs_arr = explode(';',$goods_pvs_str);//  分割成数组
			foreach($goods_pvs_arr as $pv)
			{
				if($pv)
				{
					$pv_arr = explode(':',$pv);
					$prop = $props_mod->get(array('conditions'=>'pid='.$pv_arr[0].' AND status=1'));
					if($prop)
					{
						$prop_value = $prop_value_mod->get(array('conditions'=>'pid='.$pv_arr[0].' AND vid='.$pv_arr[1].' AND status=1'));
						if($prop_value){
							$props[] = array('name'=>$prop['name'],'value'=>$prop_value['prop_value']);
						}
					}
				}
			}
		}
		$this->assign('props',$props);
		// end sku
		
        $this->assign('guest_comment_enable', Conf::get('guest_comment'));
        $this->display('goods.index.html');
    }
    
    function preview()//商户、管理员预览商品
    {
    	$db = & db();
        /* 参数 id */
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!$id)
        {
            $this->show_warning('Hacking Attempt');
            return;
        }

        /* 可缓存数据 */
        $data = $this->_get_preview_info($id);
        if ($data === false)
        {
            return;
        }
        else
        {
            $this->_assign_common_info($data);
        }
        $store_id = $data["store_data"]['store_id'];
        if(ECMALL_WAP == 1){
            $data = $this->_get_goods_comment($id, 10);
            $this->_assign_goods_comment($data);
        }
		$goods_sql = "select * from ecm_goods where goods_id = '$id'";
		$goods_row = $db->query($goods_sql);
   		while($t_result = mysql_fetch_assoc($goods_row)){
			$shipping_id[]=$t_result;
			}
		$shipping_id = json_decode($shipping_id[0]['shipping']);
		if($shipping_id)
		{
		foreach($shipping_id as $v)
		{
			$shipping_sql = "select * from ecm_shipping where shipping_id = '$v'";
			$shipping_row = $db->query($shipping_sql);
			while($t_result = mysql_fetch_assoc($shipping_row)){
			$shipping=$t_result;
			}
			$shipping_result[] = $shipping;
		}
		$this->assign('shipping', $shipping_result);
		}
        /* 更新浏览次数 */
        $this->_update_views($id);

        //是否开启验证码
        if (Conf::get('captcha_status.goodsqa'))
        {
            $this->assign('captcha', 1);
        }
		
		// sku tyioocom 
		$goods_pvs_mod = &m('goods_pvs');
		$props_mod = &m('props');
		$prop_value_mod = &m('prop_value');
		$goods_pvs = $goods_pvs_mod->get($id);// 取出该商品的属性字符串
		$goods_pvs_str = $goods_pvs['pvs'];
		$props = array();
		if(!empty($goods_pvs_str))
		{
			$goods_pvs_arr = explode(';',$goods_pvs_str);//  分割成数组
			foreach($goods_pvs_arr as $pv)
			{
				if($pv)
				{
					$pv_arr = explode(':',$pv);
					$prop = $props_mod->get(array('conditions'=>'pid='.$pv_arr[0].' AND status=1'));
					if($prop)
					{
						$prop_value = $prop_value_mod->get(array('conditions'=>'pid='.$pv_arr[0].' AND vid='.$pv_arr[1].' AND status=1'));
						if($prop_value){
							$props[] = array('name'=>$prop['name'],'value'=>$prop_value['prop_value']);
						}
					}
				}
			}
		}
		$this->assign('props',$props);
		// end sku
		
        $this->assign('guest_comment_enable', Conf::get('guest_comment'));
        $this->display('goods.preview.html');
    }
    
    /**
     * 
     * 检查购买商品数量是否超过限制
     */
	function check_goods_limit()
    {
    	$spec_id   = isset($_POST['spec_id']) ? intval($_POST['spec_id']) : 0;
        $quantity   = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
        if (!$spec_id || !$quantity)
        {
            return;
        }
        $spec_model =& m('goodsspec');
        $spec_info  =  $spec_model->get(array(
            'fields'        => 'g.store_id, g.goods_id, g.goods_name, g.spec_name_1, g.spec_name_2, g.default_image, gs.spec_1, gs.spec_2, gs.stock, gs.price, gs.limit_num',
            'conditions'    => $spec_id,
            'join'          => 'belongs_to_goods',
        ));
        //$user_id = $_COOKIE['user_info']['user_id'];
        $user_info = unserialize(stripslashes($_COOKIE["user_info"]));
        $user_id = $user_info["user_id"];
        if(!$user_id)
        {
        	echo -2;
        	return false;
        }
        
        if($spec_info['limit_num'] <= 0)
        {
        	echo 1;
        	exit();
        }
        $goods_id = $spec_info["goods_id"];
        $db = & db();
        $sql = "select count(g.quantity) from ecm_order as a, ecm_order_goods as g where a.buyer_id = '$user_id' and a.order_id = g.order_id and g.goods_id = '$goods_id'";
        $result = $db->query($sql);
        while($content=mysql_fetch_array($result)){
			$test=$content;
		};
		$total = $test[0] + $quantity;
		if($total > $spec_info['limit_num'])
		{
			echo -1;
			exit();
		}
		else
		{
			echo 1;
			exit();	
		}
    }

    /* 商品评论 */
    function comments()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!$id)
        {
            $this->show_warning('Hacking Attempt');
            return;
        }

        $data = $this->_get_common_info($id);
        if ($data === false)
        {
            return;
        }
        else
        {
            $this->_assign_common_info($data);
        }

        /* 赋值商品评论 */
        $data = $this->_get_goods_comment($id, 10);
        $this->_assign_goods_comment($data);

        $this->display('goods.comments.html');
    }

    /* 销售记录 */
    function saleslog()
    {
        $id = empty($_GET['id']) ? 0 : intval($_GET['id']);
        if (!$id)
        {
            $this->show_warning('Hacking Attempt');
            return;
        }

        $data = $this->_get_common_info($id);
        if ($data === false)
        {
            return;
        }
        else
        {
            $this->_assign_common_info($data);
        }

        /* 赋值销售记录 */
        $data = $this->_get_sales_log($id, 10);
        $this->_assign_sales_log($data);

        $this->display('goods.saleslog.html');
    }
    function qa()
    {
        $goods_qa =& m('goodsqa');
         $id = intval($_GET['id']);
         if (!$id)
         {
            $this->show_warning('Hacking Attempt');
            return;
         }
        if(!IS_POST)
        {
            $data = $this->_get_common_info($id);
            if ($data === false)
            {
                return;
            }
            else
            {
                $this->_assign_common_info($data);
            }
            $data = $this->_get_goods_qa($id, 10);
            $this->_assign_goods_qa($data);

            //是否开启验证码
            if (Conf::get('captcha_status.goodsqa'))
            {
                $this->assign('captcha', 1);
            }
            $this->assign('guest_comment_enable', Conf::get('guest_comment'));
            /*赋值产品咨询*/
            $this->display('goods.qa.html');
        }
        else
        {
            /* 不允许游客评论 */
            if (!Conf::get('guest_comment') && !$this->visitor->has_login)
            {
                $this->show_warning('guest_comment_disabled');

                return;
            }
            $content = (isset($_POST['content'])) ? trim($_POST['content']) : '';
            //$type = (isset($_POST['type'])) ? trim($_POST['type']) : '';
            $email = (isset($_POST['email'])) ? trim($_POST['email']) : '';
            $hide_name = (isset($_POST['hide_name'])) ? trim($_POST['hide_name']) : '';
            if (empty($content))
            {
                $this->show_warning('content_not_null');
                return;
            }
            //对验证码和邮件进行判断

            if (Conf::get('captcha_status.goodsqa'))
            {
                if (base64_decode($_COOKIE['captcha']) != strtolower($_POST['captcha']))
                {
                    $this->show_warning('captcha_failed');
                    return;
                }
            }
            if (!empty($email) && !is_email($email))
            {
                $this->show_warning('email_not_correct');
                return;
            }
            $user_info = unserialize(stripslashes($_COOKIE["user_info"]));
        	$user_id = $user_info["user_id"];
            $user_id = empty($hide_name) ? $user_id : 0;
            $conditions = 'g.goods_id ='.$id;
            $goods_mod = & m('goods');
            $ids = $goods_mod->get(array(
                'fields' => 'store_id,goods_name',
                'conditions' => $conditions
            ));
            extract($ids);
            $data = array(
                'question_content' => $content,
                'type' => 'goods',
                'item_id' => $id,
                'item_name' => addslashes($goods_name),
                'store_id' => $store_id,
                'email' => $email,
                'user_id' => $user_id,
                'time_post' => gmtime(),
            );
            if ($goods_qa->add($data))
            {
                header("Location: index.php?app=goods&act=qa&id={$id}#module\n");
                exit;
            }
            else
            {
                $this->show_warning('post_fail');
                exit;
            }
        }
    }

    /**
     * 取得公共信息
     *
     * @param   int     $id
     * @return  false   失败
     *          array   成功
     */
    function _get_common_info($id)
    {
        $cache_server =& cache_server();
        $key = 'page_of_goods_' . $id;
        $data = $cache_server->get($key);
        $data = false;
        $cached = true;
        if ($data === false)
        {
            $cached = false;
            $data = array('id' => $id);

            /* 商品信息 */
            $goods = $this->_goods_mod->get_info($id);
            if (!$goods || $goods['if_show'] == 0 || $goods['closed'] == 1 || $goods['state'] != 1)
            {
                $this->show_warning('goods_not_exist');
                return false;
            }
            $goods['tags'] = $goods['tags'] ? explode(',', trim($goods['tags'], ',')) : array();

            $data['goods'] = $goods;

            /* 店铺信息 */
            if (!$goods['store_id'])
            {
                $this->show_warning('store of goods is empty');
                return false;
            }
            $this->set_store($goods['store_id']);
            $data['store_data'] = $this->get_store_data();

            /* 当前位置 */
            $data['cur_local'] = $this->_get_curlocal($goods['cate_id']);
            $data['goods']['related_info'] = $this->_get_related_objects($data['goods']['tags']);
            /* 分享链接 */
            $data['share'] = $this->_get_share($goods);

            $cache_server->set($key, $data, 1800);
        }
        if ($cached)
        {
            $this->set_store($data['goods']['store_id']);
        }

        return $data;
    }
    
function _get_preview_info($id)
    {
        $cache_server =& cache_server();
        $key = 'page_of_goods_' . $id;
        $data = $cache_server->get($key);
        $data = false;
        $cached = true;
        if ($data === false)
        {
            $cached = false;
            $data = array('id' => $id);

            /* 商品信息 */
            $goods = $this->_goods_mod->get_info($id);
            if (!$goods || $goods['state'] != 1)
            {
                $this->show_warning('goods_not_exist');
                return false;
            }
            $goods['tags'] = $goods['tags'] ? explode(',', trim($goods['tags'], ',')) : array();

            $data['goods'] = $goods;

            /* 店铺信息 */
            if (!$goods['store_id'])
            {
                $this->show_warning('store of goods is empty');
                return false;
            }
            $this->set_store($goods['store_id']);
            $data['store_data'] = $this->get_store_data();

            /* 当前位置 */
            $data['cur_local'] = $this->_get_curlocal($goods['cate_id']);
            $data['goods']['related_info'] = $this->_get_related_objects($data['goods']['tags']);
            /* 分享链接 */
            $data['share'] = $this->_get_share($goods);

            $cache_server->set($key, $data, 1800);
        }
        if ($cached)
        {
            $this->set_store($data['goods']['store_id']);
        }

        return $data;
    }

    function _get_related_objects($tags)
    {
        if (empty($tags))
        {
            return array();
        }
        $tag = $tags[array_rand($tags)];
        $ms =& ms();

        return $ms->tag_get($tag);
    }

    function generateQRfromGoogle($goods_id, $widhtHeight = '100', $EC_level = 'L', $margin = '0') {
        $url = SITE_URL.'/index.php?app=goods&id='.$goods_id;
        $url = urlencode($url);
        return '<img src="http://chart.apis.google.com/chart?chs=' . $widhtHeight . 'x' . $widhtHeight . '&cht=qr&chld=' . $EC_level . '|' . $margin . '&chl=' . $url . '" alt="QR code" widhtHeight="' . $widhtHeight . '" widhtHeight="' . $widhtHeight . '"/>';
    }
    
    /* 赋值公共信息 */
    function _assign_common_info($data)
    {
        /* 商品信息 */
        $goods = $data["goods"];
        $if_credit = $goods["if_credit"];
        if($if_credit == 1)
        {
        	foreach($goods["_specs"] as $k=>$v)
        	{
        		$goods["_specs"][$k]["credit"] = $v["price"]*100/CREDIT_RATE;
        	}
        }
        else 
        {
        	foreach($goods["_specs"] as $k=>$v)
        	{
        		$goods["_specs"][$k]["credit"] = 0;
        	}
        }
        $price = $goods["price"];
        $credit = $price*100/CREDIT_RATE;
        $goods['scan_code'] = $this->generateQRfromGoogle($goods['goods_id']);
        $this->assign('goods', $goods);
        $this->assign('sales_info', sprintf(LANG::get('sales'), $goods['sales'] ? $goods['sales'] : 0));
        $this->assign('comments', sprintf(LANG::get('comments'), $goods['comments'] ? $goods['comments'] : 0));
		$this->assign("credit", $credit);
        /* 店铺信息 */
        $this->assign('store', $data['store_data']);

        /* 浏览历史 */
        $this->assign('goods_history', $this->_get_goods_history($data['id']));

        /* 默认图片 */
        $this->assign('default_image', Conf::get('default_goods_image'));

        /* 当前位置 */
        $this->_curlocal($data['cur_local']);

        /* 配置seo信息 */
        $this->_config_seo($this->_get_seo_info($data['goods']));

        /* 商品分享 */
        $this->assign('share', $data['share']);

//      $this->import_resource(array(
//          'script' => 'jquery.jqzoom.js',
//          'style' => 'res:jqzoom.css'
//      ));
    }

    /* 取得浏览历史 */
    function _get_goods_history($id, $num = 9)
    {
        $goods_list = array();
        $goods_ids  = ecm_getcookie('goodsBrowseHistory');
        $goods_ids  = $goods_ids ? explode(',', $goods_ids) : array();
        if ($goods_ids)
        {
            $rows = $this->_goods_mod->find(array(
                'conditions' => $goods_ids,
                'fields'     => 'goods_name,default_image',
            ));
            foreach ($goods_ids as $goods_id)
            {
                if (isset($rows[$goods_id]))
                {
                    empty($rows[$goods_id]['default_image']) && $rows[$goods_id]['default_image'] = Conf::get('default_goods_image');
                    $goods_list[] = $rows[$goods_id];
                }
            }
        }
        $goods_ids[] = $id;
        if (count($goods_ids) > $num)
        {
            unset($goods_ids[0]);
        }
        ecm_setcookie('goodsBrowseHistory', join(',', array_unique($goods_ids)));

        return $goods_list;
    }

    /* 取得销售记录 */
    function _get_sales_log($goods_id, $num_per_page)
    {
        $data = array();

        $page = $this->_get_page($num_per_page);
        $order_goods_mod =& m('ordergoods');
        $sales_list = $order_goods_mod->find(array(
            'conditions' => "goods_id = '$goods_id' AND status = '" . ORDER_FINISHED . "'",
            'join'  => 'belongs_to_order',
            'fields'=> 'buyer_id, buyer_name, add_time, anonymous, goods_id, specification, price, quantity, evaluation',
            'count' => true,
            'order' => 'add_time desc',
            'limit' => $page['limit'],
        ));
        $data['sales_list'] = $sales_list;

        $page['item_count'] = $order_goods_mod->getCount();
        $this->_format_page($page);
        $data['page_info'] = $page;
        $data['more_sales'] = $page['item_count'] > $num_per_page;

        return $data;
    }

    /* 赋值销售记录 */
    function _assign_sales_log($data)
    {
        $this->assign('sales_list', $data['sales_list']);
        $this->assign('page_info',  $data['page_info']);
        $this->assign('more_sales', $data['more_sales']);
    }

    /* 取得商品评论 */
    function _get_goods_comment($goods_id, $num_per_page)
    {
        $data = array();

        $page = $this->_get_page($num_per_page);
        $order_goods_mod =& m('ordergoods');
        $comments = $order_goods_mod->find(array(
            'conditions' => "order_goods.goods_id = '$goods_id' AND evaluation_status = '1'",
            'join'  => 'belongs_to_order',
            'fields'=> 'buyer_id, buyer_name, anonymous, evaluation_time, comment, evaluation',
            'count' => true,
            'order' => 'evaluation_time desc',
            'limit' => $page['limit'],
        ));
        $data['comments'] = $comments;

        $page['item_count'] = $order_goods_mod->getCount();
        $this->_format_page($page);
        $data['page_info'] = $page;
        $data['more_comments'] = $page['item_count'] > $num_per_page;

        return $data;
    }

    /* 赋值商品评论 */
    function _assign_goods_comment($data)
    {
        $this->assign('goods_comments', $data['comments']);
        $this->assign('page_info',      $data['page_info']);
        $this->assign('more_comments',  $data['more_comments']);
    }

    /* 取得商品咨询 */
    function _get_goods_qa($goods_id,$num_per_page)
    {
        $page = $this->_get_page($num_per_page);
        $goods_qa = & m('goodsqa');
        $qa_info = $goods_qa->find(array(
            'join' => 'belongs_to_user',
            'fields' => 'member.user_name,question_content,reply_content,time_post,time_reply',
            'conditions' => '1 = 1 AND item_id = '.$goods_id . " AND type = 'goods'",
            'limit' => $page['limit'],
            'order' =>'time_post desc',
            'count' => true
        ));
        $page['item_count'] = $goods_qa->getCount();
        $this->_format_page($page);

        //如果登陆，则查出email
        if (!empty($_COOKIE['user_info']))
        {
        	$user_info = unserialize(stripslashes($_COOKIE["user_info"]));
        	$user_id = $user_info["user_id"];
            $user_mod = & m('member');
            $user_info = $user_mod->get(array(
                'fields' => 'email',
                'conditions' => '1=1 AND user_id = '.$user_id
            ));
            extract($user_info);
        }

        return array(
            'email' => $email,
            'page_info' => $page,
            'qa_info' => $qa_info,
        );
    }

    /* 赋值商品咨询 */
    function _assign_goods_qa($data)
    {
        $this->assign('email',      $data['email']);
        $this->assign('page_info',  $data['page_info']);
        $this->assign('qa_info',    $data['qa_info']);
    }

    /* 更新浏览次数 */
    function _update_views($id)
    {
        $goodsstat_mod =& m('goodsstatistics');
        $goodsstat_mod->edit($id, "views = views + 1");
    }

    /**
     * 取得当前位置
     *
     * @param int $cate_id 分类id
     */
    function _get_curlocal($cate_id)
    {
        $parents = array();
        if ($cate_id)
        {
            $gcategory_mod =& bm('gcategory');
            $parents = $gcategory_mod->get_ancestor($cate_id, true);
        }

        $curlocal = array(
            array('text' => LANG::get('all_categories'), 'url' => url('app=category')),
        );
        foreach ($parents as $category)
        {
            $curlocal[] = array('text' => $category['cate_name'], 'url' => url('app=search&cate_id=' . $category['cate_id']));
        }
        $curlocal[] = array('text' => LANG::get('goods_detail'));

        return $curlocal;
    }

    function _get_share($goods)
    {
        $m_share = &af('share');
        $shares = $m_share->getAll();
        $shares = array_msort($shares, array('sort_order' => SORT_ASC));
        $goods_name = ecm_iconv(CHARSET, 'utf-8', $goods['goods_name']);
        $goods_url = urlencode(SITE_URL . '/' . str_replace('&amp;', '&', url('app=goods&id=' . $goods['goods_id'])));
        $site_title = ecm_iconv(CHARSET, 'utf-8', Conf::get('site_title'));
        $share_title = urlencode($goods_name . '-' . $site_title);
        foreach ($shares as $share_id => $share)
        {
            $shares[$share_id]['link'] = str_replace(
                array('{$link}', '{$title}'),
                array($goods_url, $share_title),
                $share['link']);
        }
        return $shares;
    }
    
    function _get_seo_info($data)
    {
        $seo_info = $keywords = array();
        $seo_info['title'] = $data['goods_name'] . ' - ' . Conf::get('site_title');        
        $keywords = array(
            $data['brand'],
            $data['goods_name'],
            $data['cate_name']
        );
        $seo_info['keywords'] = implode(',', array_merge($keywords, $data['tags']));        
        $seo_info['description'] = sub_str(strip_tags($data['description']), 10, true);
        return $seo_info;
    }
    
    
}

?>
