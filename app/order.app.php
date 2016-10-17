<?php
ini_set("display_errors", true);

/**
 * 售货员控制器，其扮演实际交易中柜台售货员的角色，你可以这么理解她：你告诉我（售货员）要买什么东西，我会询问你你要的收货地址是什么之类的问题
 * 并根据你的回答来生成一张单子，这张单子就是“订单”
 *
 * @author Garbin
 * @param
 *            none
 * @return void
 */
class OrderApp extends ShoppingbaseApp
{

    /**
     * 填写收货人信息，选择配送，支付方式。
     *
     * @author Garbin
     * @param
     *            none
     * @return void
     */
    function index()
    {
        $goods_info = $this->_get_goods_info();
        if ($goods_info === false) {
            /* 购物车是空的 */
            $this->show_warning('goods_empty');
            
            return;
        }
        $db = & db();
        // $check_goods_info = array_values($goods_info['items']);
        $if_credit = false;
        foreach ($goods_info["items"] as $k => $v) {
            foreach ($v as $a => $c) {
                $spec_id = $c['spec_id'];
                $quantity = $c['quantity'];
                $user_id = $c['user_id'];
                if ($c["if_credit"] == 1) {
                    $if_credit = true;
                    $credit = $c["price"] * 100 / CREDIT_RATE;
                    $goods_info["items"][$k][$a]["credit"] = $credit;
                }
                if ($this->check_goods_limit($spec_id, $quantity, $user_id) == false) {
                    header("Content-type: text/html; charset=utf-8");
                    echo "<script>alert('购买商品数量超过限制');history.go(-1);</script>";
                    exit();
                } else {}
            }
        }
        // $spec_id = $check_goods_info[0]['spec_id'];
        // $quantity = $check_goods_info[0]['quantity'];
        // $user_id = $check_goods_info[0]['user_id'];
        /* 检查库存 */
        $goods_beyond = $this->_check_beyond_stock($goods_info["items"]);
        if ($goods_beyond) {
            $str_tmp = '';
            foreach ($goods_beyond as $goods) {
                $str_tmp .= '<br /><br />' . $goods['goods_name'] . '&nbsp;&nbsp;' . $goods['specification'] . '&nbsp;&nbsp;' . Lang::get('stock') . ':' . $goods['stock'];
            }
            $this->show_warning(sprintf(Lang::get('quantity_beyond_stock'), $str_tmp));
            return;
        }
        $user_info = unserialize(stripslashes($_COOKIE['user_info']));
        $user_id = $this->visitor->get("user_id");
        $sso_sql = "select phpsso_uid from ecm_member where user_id = '$user_id'";
        $sso_row = $db->query($sso_sql);
        while ($t_result = mysql_fetch_array($sso_row)) {
            $sso_result = $t_result;
        }
        $phpsso_uid = $sso_result["phpsso_uid"];
        $con1 = mysql_connect("localhost", "root", "root");
        @mysql_select_db("phpcmstest", $con1) or die("数据库不存在！" . mysql_error());
        mysql_query("SET NAMES UTF8", $con1);
        $phpcms_sql = "select credit from v9_member, v9_member_detail where v9_member.phpssouid = '$phpsso_uid' and v9_member.userid = v9_member_detail.userid"; // 从主站取出用户积分
        $phpcms_row = mysql_query($phpcms_sql);
        while ($p_result = mysql_fetch_array($phpcms_row)) {
            $phpcms_result = $p_result;
        }
        $credit_aval = $phpcms_result["credit"]; // 用户可用积分
        
        if (! IS_POST) {
            /* 根据商品类型获取对应订单类型 */
            $goods_type = &  gt($goods_info['type']);
            $order_type = &  ot($goods_info['otype']);
            /* 显示订单表单 */
            foreach ($goods_info['items'] as $v) {
                foreach ($v as $c) {
                    $spec_array[] = $c['spec_id'];
                }
            }
            $form = $order_type->get_order_form($spec_array);
            if ($form === false) {
                $this->show_warning($order_type->get_error());
                
                return;
            }
            
            foreach ($goods_info['items'] as $k => $v) {
                $store_id = $k;
                $store_model = & m('store');
                $store_info = $store_model->get($store_id);
                $store_name[$k] = $store_info['store_name'];
            }
            $shipping_method = $form['data']['shipping_methods'];
            
            foreach ($shipping_method as $k => $v) {
                $test_array = array();
                foreach ($v as $a => $b) {
                    if (in_array($b["shipping_id"], $test_array)) {
                        unset($shipping_method[$k][$a]); // 避免同一种商品循环出重复的配送方式
                    } else {
                        $test_array[] = $b["shipping_id"];
                    }
                }
            }
            // foreach ($shipping_method as $key => $shipping_value) {
            // foreach ($shipping_value as $k => $shipping_v) {
            // if (! empty($shipping_v['shipping_fee'])) {
            // $shipping_method[$key][0]['shipping_fee'] = $shipping_v['shipping_fee'];
            // unset($shipping_method[$key][$k]);
            // }
            // }
            // }
            $payment_sql = "select payment_id, payment_code, payment_name from ecm_payment where enabled = 1";
            $payment_row = $db->query($payment_sql);
            while ($t_result = mysql_fetch_array($payment_row)) {
                $payment_result[] = $t_result;
            }
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false && ! (isset($_COOKIE["ECS"]["indevice"]))) {
                foreach ($payment_result as $_id => $_info) {
                    if ($_info['payment_code'] == 'wechatpay') {
                        /* 如果不在微信浏览器内，则删除微信支付 */
                        unset($payment_result[$_id]);
                    }
                }
            }
            
            /* 如果是微信浏览器，则不显示支付宝支付 */
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == true) {
                foreach ($payment_result as $_id => $_info) {
                    if ($_info['payment_code'] == 'alipay') {
                        unset($payment_result[$_id]);
                    }
                }
            }
            foreach ($payment_result as $_id => $_info) {
                if ($_info['payment_code'] == 'cod') {
                    unset($payment_result[$_id]);
                }
                if ($_info['payment_code'] == 'huodaofukuan') {
                    unset($payment_result[$_id]);
                }
            }
            $this->_curlocal(LANG::get('create_order'));
            $this->_config_seo('title', Lang::get('confirm_order') . ' - ' . Conf::get('site_title'));
            import('init.lib');
            $this->assign('coupon_list', Init_OrderApp::get_available_coupon($goods_info['items']));
            $this->assign('goods_info', $goods_info["items"]);
            $this->assign("total", $goods_info["total"]);
            $this->assign("real_total", $goods_info['real_total']);
            $this->assign("quantity", $goods_info["quantity"]);
            $this->assign("credit_max", $goods_info["credit_max_money"]);
            $this->assign("store_name", $store_name);
            $this->assign($form['data']);
            $this->assign("shipping_methods", $shipping_method);
            $this->assign("if_credit", $if_credit);
            $this->assign("payments", $payment_result);
            $this->assign("credit_max_consume", $goods_info["credit_max_consume"]);
            $this->assign("credit_aval", $credit_aval);
            $this->display($form['template']);
        } else {
            /* 在此获取生成订单的两个基本要素：用户提交的数据（POST），商品信息（包含商品列表，商品总价，商品总数量，类型），所属店铺 */
            $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
            $credit_used = $goods_info["credit_max_consume"]; // $_POST["credit_used"];//用户欲消费的积分
                                                              // $whether_credit = $_POST["if_credit"];//用户是否选择使用积分消费
            if ($credit_aval < $credit_used) {
                $this->show_warning('可用积分不足');
                
                return;
            }
            if ($goods_info === false) {
                /* 购物车是空的 */
                $this->show_warning('goods_empty');
                
                return;
            }
            /* 优惠券数据处理 */
            if ($goods_info['allow_coupon'] && isset($_POST['coupon_sn']) && ! empty($_POST['coupon_sn'])) {
                $coupon_sn = trim($_POST['coupon_sn']);
                $coupon_mod = & m('couponsn');
                $coupon = $coupon_mod->get(array(
                    'fields' => 'coupon.*,couponsn.remain_times',
                    'conditions' => "coupon_sn.coupon_sn = '{$coupon_sn}' AND coupon.store_id = " . $store_id,
                    'join' => 'belongs_to_coupon'
                ));
                if (empty($coupon)) {
                    $this->show_warning('involid_couponsn');
                    exit();
                }
                if ($coupon['remain_times'] < 1) {
                    $this->show_warning("times_full");
                    exit();
                }
                $time = gmtime();
                if ($coupon['start_time'] > $time) {
                    $this->show_warning("coupon_time");
                    exit();
                }
                
                if ($coupon['end_time'] < $time) {
                    $this->show_warning("coupon_expired");
                    exit();
                }
                if ($coupon['min_amount'] > $goods_info['amount']) {
                    $this->show_warning("amount_short");
                    exit();
                }
                unset($time);
                $goods_info['discount'] = $coupon['coupon_value'];
            }
            /* 根据商品类型获取对应的订单类型 */
            $goods_type = & gt($goods_info['type']);
            $order_type = & ot($goods_info['otype']);
            /* 将这些信息传递给订单类型处理类生成订单(你根据我提供的信息生成一张订单) */
            $order_id = $order_type->submit_order(array(
                'goods_info' => $goods_info, // 商品信息（包括列表，总价，总量，所属店铺，类型）,可靠的!
                'post' => $_POST
            )); // 用户填写的订单信息
            if (! $order_id) {
                $this->show_warning($order_type->get_error());
                
                return;
            }
            /* 检查是否添加收货人地址 */
            if (isset($_POST['save_address']) && (intval(trim($_POST['save_address'])) == 1)) {
                $data = array(
                    'user_id' => $this->visitor->get('user_id'),
                    'consignee' => trim($_POST['consignee']),
                    'region_id' => $_POST['region_id'],
                    'region_name' => $_POST['region_name'],
                    'address' => trim($_POST['address']),
                    'zipcode' => trim($_POST['zipcode']),
                    'phone_tel' => trim($_POST['phone_tel']),
                    'phone_mob' => trim($_POST['phone_mob'])
                );
                $model_address = & m('address');
                $model_address->add($data);
            }
            /* 下单完成后清理商品，如清空购物车，或将团购拍卖的状态转为已下单之类的 */
            // $this->_clear_goods($order_id);
            
            /* 发送邮件 */
            $model_order = & m('order');
            /* 减去商品库存 */
            foreach ($order_id as $v) {
                $model_order->change_stock('-', $v);
                /* 获取订单信息 */
                $order_info[] = $model_order->get($v);
            }
            /* 发送事件 */
            $feed_images = array();
            foreach ($goods_info['items'] as $v) {
                foreach ($v as $k => $_gi) {
                    $feed_images[$k][] = array(
                        'url' => SITE_URL . '/' . $_gi['goods_image'],
                        'link' => SITE_URL . '/' . url('app=goods&id=' . $_gi['goods_id'])
                    );
                }
            }
            
            foreach ($order_info as $k => $v) {
                $this->send_feed('order_created', array(
                    'user_id' => $this->visitor->get('user_id'),
                    'user_name' => addslashes($this->visitor->get('user_name')),
                    'seller_id' => $order_info[$k]['seller_id'],
                    'seller_name' => $order_info[$k]['seller_name'],
                    'store_url' => SITE_URL . '/' . url('app=store&id=' . $order_info[$k]['seller_id']),
                    'images' => $feed_images[$k]
                ));
            }
            $buyer_address = $this->visitor->get('email');
            $model_member = & m('member');
            foreach ($goods_info["items"] as $k => $v) {
                $member_info = $model_member->get($goods_info['store_id']);
                $seller_address[] = $member_info['email'];
            }
            /* 发送给买家下单通知 */
            $buyer_mail = get_mail('tobuyer_new_order_notify', array(
                'order' => $order_info
            ));
            $this->_mailto($buyer_address, addslashes($buyer_mail['subject']), addslashes($buyer_mail['message']));
            /* 发送给卖家新订单通知 */
            foreach ($order_info as $k => $v) {
                $seller_mail = get_mail('toseller_new_order_notify', array(
                    'order' => $v
                ));
                $this->_mailto($seller_address[$k], addslashes($seller_mail['subject']), addslashes($seller_mail['message']));
            }
            /* 更新下单次数 */
            $model_goodsstatistics = & m('goodsstatistics');
            $goods_ids = array();
            foreach ($goods_info['items'] as $v) {
                foreach ($v as $goods) {
                    $goods_ids[] = $goods['goods_id'];
                }
            }
            $model_goodsstatistics->edit($goods_ids, 'orders=orders+1');
            $order_id = json_encode($order_id);
            /* 到收银台付款 */
            header('Location:index.php?app=cashier&order_id=' . $order_id);
        }
    }

    /**
     * 获取外部传递过来的商品
     *
     * @author Garbin
     * @param
     *            none
     * @return void
     */
    function _get_goods_info()
    {
        $return = array(
            'items' => array(), // 商品列表
            'quantity' => 0, // 商品总量
            'amount' => 0, // 商品总价
            'store_id' => 0, // 所属店铺
            'store_name' => '', // 店铺名称
            'type' => null, // 商品类型
            'otype' => 'normal', // 订单类型
            'allow_coupon' => true
        ); // 是否允许使用优惠券
        
        $db = & db();
        switch ($_GET['goods']) {
            case 'groupbuy':
                /* 团购的商品 */
                $group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;
                $user_id = $this->visitor->get('user_id');
                if (! $group_id || ! $user_id) {
                    return false;
                }
                /* 获取团购记录详细信息 */
                $model_groupbuy = & m('groupbuy');
                $groupbuy_info = $model_groupbuy->get(array(
                    'join' => 'be_join, belong_store, belong_goods',
                    'conditions' => $model_groupbuy->getRealFields("groupbuy_log.user_id={$user_id} AND groupbuy_log.group_id={$group_id} AND groupbuy_log.order_id=0 AND this.state=" . GROUP_FINISHED),
                    'fields' => 'store.store_id, store.store_name, goods.goods_id, goods.goods_name, goods.default_image, groupbuy_log.quantity, groupbuy_log.spec_quantity, this.spec_price'
                ));
                
                if (empty($groupbuy_info)) {
                    return false;
                }
                
                /* 库存信息 */
                $model_goodsspec = &m('goodsspec');
                $goodsspec = $model_goodsspec->find('goods_id=' . $groupbuy_info['goods_id']);
                
                /* 获取商品信息 */
                $spec_quantity = unserialize($groupbuy_info['spec_quantity']);
                $spec_price = unserialize($groupbuy_info['spec_price']);
                $amount = 0;
                $groupbuy_items = array();
                $goods_image = empty($groupbuy_info['default_image']) ? Conf::get('default_goods_image') : $groupbuy_info['default_image'];
                foreach ($spec_quantity as $spec_id => $spec_info) {
                    $the_price = $spec_price[$spec_id]['price'];
                    $subtotal = $spec_info['qty'] * $the_price;
                    $groupbuy_items[] = array(
                        'goods_id' => $groupbuy_info['goods_id'],
                        'goods_name' => $groupbuy_info['goods_name'],
                        'spec_id' => $spec_id,
                        'specification' => $spec_info['spec'],
                        'price' => $the_price,
                        'quantity' => $spec_info['qty'],
                        'goods_image' => $goods_image,
                        'subtotal' => $subtotal,
                        'stock' => $goodsspec[$spec_id]['stock']
                    );
                    $amount += $subtotal;
                }
                
                $return['items'] = $groupbuy_items;
                $return['quantity'] = $groupbuy_info['quantity'];
                $return['amount'] = $amount;
                $return['store_id'] = $groupbuy_info['store_id'];
                $return['store_name'] = $groupbuy_info['store_name'];
                $return['type'] = 'material';
                $return['otype'] = 'groupbuy';
                $return['allow_coupon'] = false;
                break;
            default:
                /* 从购物车中取商品 */
//                $_GET['store_id'] = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
//                $store_id = $_GET['store_id'];
//                if (!$store_id)
//                {
//                    return false;
//                }
                $cart_model = & m('cart');
                
                // $cart_items = $cart_model->find(array(
                // 'conditions' => "user_id = " . $this->visitor->get('user_id') . " AND store_id = {$store_id} AND session_id='" . SESS_ID . "'",
                // 'join' => 'belongs_to_goodsspec',
                // ));
                $cart_items = $cart_model->find(array(
                    'conditions' => "user_id = " . $this->visitor->get('user_id') . " AND if_checked = 1",
                    'join' => 'belongs_to_goodsspec'
                ));
                if (empty($cart_items)) {
                    return false;
                }
                
                $store_model = & m('store');
                // $store_info = $store_model->get($store_id);
                //
                // foreach ($cart_items as $rec_id => $goods)
                // {
                // $return['quantity'] += $goods['quantity']; //商品总量
                // $return['amount'] += $goods['quantity'] * $goods['price']; //商品总价
                // $cart_items[$rec_id]['subtotal'] = $goods['quantity'] * $goods['price']; //小计
                // empty($goods['goods_image']) && $cart_items[$rec_id]['goods_image']=Conf::get('default_goods_image');
                // }
                $result["credit_max_money"] = 0; // 积分最多可以抵扣多少钱
                $result["real_total"] = 0;
                foreach ($cart_items as $k => $v) {
                    $goods_id = $v["goods_id"];
                    $spec_id = $v["spec_id"];
                    $goods_sql = "select * from ecm_goods where goods_id = '$goods_id'";
                    $goods_row = $db->query($goods_sql);
                    while ($t_result = mysql_fetch_array($goods_row)) {
                        $goods_result = $t_result;
                    }
                    $spec_id = $v["spec_id"];
                    $spec_sql = "select * from ecm_goods_spec where spec_id = '$spec_id'";
                    $spec_row = $db->query($spec_sql);
                    while ($t_result = mysql_fetch_array($spec_row)) {
                        $spec_result = $t_result;
                    }
                    $v["cod"] = $goods_result["cod"];
                    $v["postage_daofu"] = $goods_result["postage_daofu"];
                    $v["if_credit"] = $goods_result["if_credit"];
                    $v["credit_percent"] = $goods_result["credit_percent"];
                    $v["weight"] = $goods_result["weight"];
                    $store_info = $store_model->get($v['store_id']);
                    $store_id = $v['store_id'];
                    $store_name = $store_info['store_name'];
                    $v['amount'] = $v['quantity'] * $v['price'];
                    if ($v["if_credit"] == 1) {
                        $result["credit_max_money"] = $result["credit_max_money"] + ($v["credit_percent"] / 100) * $v["price"] * $v["quantity"]; // 计算该商品积分
                    }
                    if ($v["if_credit"] != 1) {
                        $result["real_total"] = $result["real_total"] + $v["amount"];
                    }
                    $v['rec_id'] = $k;
                    $v['store_name'] = $store_name;
                    $result["items"][$store_id][$spec_id] = $v;
                    $result["total"] = $result["total"] + $v["amount"];
                    $result["quantity"] = $result["quantity"] + $v["quantity"];
                }
                $return['items'] = $cart_items;
                $return['store_id'] = $store_id;
                $return['store_name'] = $store_info['store_name'];
                $return['store_im_qq'] = $store_info['im_qq']; // tyioocom
                $return['type'] = 'material';
                $return['otype'] = 'normal';
                $result["type"] = "material";
                $result["otype"] = "normal";
                $result["credit_max_consume"] = 0;
                $result["credit_max_consume"] = $result["credit_max_money"] * 100 / CREDIT_RATE;
                break;
        }
        return $result;
    }

    /**
     * 下单完成后清理商品
     *
     * @author Garbin
     * @return void
     */
    function _clear_goods($order_id)
    {
        switch ($_GET['goods']) {
            case 'groupbuy':
                /* 团购的商品 */
                $model_groupbuy = & m('groupbuy');
                $model_groupbuy->updateRelation('be_join', intval($_GET['group_id']), $this->visitor->get('user_id'), array(
                    'order_id' => $order_id
                ));
                break;
            default: // 购物车中的商品
                /* 订单下完后清空指定购物车 */
                // $_GET['store_id'] = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
                // $store_id = $_GET['store_id'];
                // if (!$store_id)
                // {
                // return false;
                // }
                $user_id = $this->visitor->get('user_id');
                $model_cart = & m('cart');
                $model_cart->drop("user_id='$user_id'");
                // 优惠券信息处理
                if (isset($_POST['coupon_sn']) && ! empty($_POST['coupon_sn'])) {
                    $sn = trim($_POST['coupon_sn']);
                    $couponsn_mod = & m('couponsn');
                    $couponsn = $couponsn_mod->get("coupon_sn = '{$sn}'");
                    if ($couponsn['remain_times'] > 0) {
                        $couponsn_mod->edit("coupon_sn = '{$sn}'", "remain_times= remain_times - 1");
                    }
                }
                break;
        }
    }

    /**
     * 检查优惠券有效性
     */
    function check_coupon()
    {
        $coupon_sn = $_GET['coupon_sn'];
        $store_id = is_numeric($_GET['store_id']) ? $_GET['store_id'] : 0;
        if (empty($coupon_sn)) {
            $this->js_result(false);
        }
        $coupon_mod = & m('couponsn');
        $coupon = $coupon_mod->get(array(
            'fields' => 'coupon.*,couponsn.remain_times',
            'conditions' => "coupon_sn.coupon_sn = '{$coupon_sn}' AND coupon.store_id = " . $store_id,
            'join' => 'belongs_to_coupon'
        ));
        if (empty($coupon)) {
            $this->json_result(false);
            exit();
        }
        if ($coupon['remain_times'] < 1) {
            $this->json_result(false);
            exit();
        }
        $time = gmtime();
        if ($coupon['start_time'] > $time) {
            $this->json_result(false);
            exit();
        }
        
        if ($coupon['end_time'] < $time) {
            $this->json_result(false);
            exit();
        }
        
        // 检查商品价格与优惠券要求的价格
        
        $model_cart = & m('cart');
        $item_info = $model_cart->find("store_id={$store_id}");
        $price = 0;
        foreach ($item_info as $val) {
            $price = $price + $val['price'] * $val['quantity'];
        }
        if ($price < $coupon['min_amount']) {
            $this->json_result(false);
            exit();
        }
        $this->json_result(array(
            'res' => true,
            'price' => $coupon['coupon_value']
        ));
        exit();
    }

    function _check_beyond_stock($goods_items)
    {
        $goods_beyond_stock = array();
        // foreach ($goods_items as $rec_id => $goods)
        // {
        // if ($goods['quantity'] > $goods['stock'])
        // {
        // $goods_beyond_stock[$goods['spec_id']] = $goods;
        // }
        // }
        foreach ($goods_items as $k => $v) {
            foreach ($v as $a => $v) {
                if ($v['quantity'] > $v['stock']) {
                    $goods_beyond_stock[$v['spec_id']] = $v;
                }
            }
        }
        return $goods_beyond_stock;
    }

    /**
     *
     * 检查是否超过购买数量限制
     *
     * @param $spec 商品id            
     * @param $num 购买数量            
     * @param $user 用户id            
     */
    function check_goods_limit($spec, $num, $user)
    {
        $spec_id = $spec;
        $quantity = $num;
        if (! $spec_id || ! $quantity) {
            return false;
        }
        $spec_model = & m('goodsspec');
        $spec_info = $spec_model->get(array(
            'fields' => 'g.store_id, g.goods_id, g.goods_name, g.spec_name_1, g.spec_name_2, g.default_image, gs.spec_1, gs.spec_2, gs.stock, gs.price, gs.limit_num',
            'conditions' => $spec_id,
            'join' => 'belongs_to_goods'
        ));
        $user_id = $user;
        if ($spec_info['limit_num'] <= 0) {
            return true;
        }
        $goods_id = $spec_info["goods_id"];
        $db = & db();
        $sql = "select count(g.quantity) from ecm_order as a, ecm_order_goods as g where a.buyer_id = '$user_id' and a.order_id = g.order_id and g.goods_id = '$goods_id'";
        $result = $db->query($sql);
        while ($content = mysql_fetch_array($result)) {
            $test = $content;
        }
        ;
        $total = $test[0] + $quantity;
        if ($total > $spec_info['limit_num']) {
            return false;
        } else {
            return true;
        }
    }
}
?>
