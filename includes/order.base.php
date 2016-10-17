<?php
ini_set("display_errors", 1);
! defined('ROOT_PATH') && exit('Forbidden');

/**
 * 订单类型基类
 *
 * @author Garbin
 *         @usage none
 */
class BaseOrder extends Object
{

    function __construct($params)
    {
        $this->BaseOrder($params);
    }

    function BaseOrder($params)
    {
        if (! empty($params)) {
            foreach ($params as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * 获取订单类型名称
     *
     * @author Garbin
     * @return string
     */
    function get_name()
    {
        return $this->_name;
    }

    /**
     * 获取订单详情
     *
     * @author Garbin
     * @param int $order_id            
     * @param array $order_info            
     * @return array
     */
    function get_order_detail($order_id, $order_info)
    {
        if (! $order_id) {
            return array();
        }
        
        /* 订单基本信息 */
        $data['order_info'] = $order_info;
        
        return array(
            'data' => $data,
            'template' => 'normalorder.view.html'
        );
    }

    /**
     * 获取该商品类型在购物流程中的表单模板及数据
     *
     * @author Garbin
     * @return array
     */
    function get_order_form()
    {
        return array();
    }

    /**
     * 处理表单提交上来后的数据，并插入订单表
     *
     * @author Garbin
     * @param array $data            
     * @return int
     */
    function submit_order($data)
    {
        return 0;
    }

    /**
     * 响应支付通知
     *
     * @author Garbin
     * @param int $order_id            
     * @param array $notify_result            
     * @return bool
     */
    function respond_notify($order_id, $notify_result)
    {
        $model_order = & m('order');
        $where = "order_id = {$order_id}";
        $data = array(
            'status' => $notify_result['target']
        );
        switch ($notify_result['target']) {
            case ORDER_ACCEPTED:
                $where .= ' AND status=' . ORDER_PENDING; // 只有待付款的订单才会被修改为已付款
                $data['pay_time'] = gmtime();
                break;
            case ORDER_SHIPPED:
                $where .= ' AND status=' . ORDER_ACCEPTED; // 只有等待发货的订单才会被修改为已发货
                $data['ship_time'] = gmtime();
                break;
            case ORDER_FINISHED:
                $where .= ' AND status=' . ORDER_SHIPPED; // 只有已发货的订单才会被自动修改为交易完成
                $data['finished_time'] = gmtime();
                break;
            case ORDER_CANCLED: // 任何情况下都可以关闭
                /* 加回商品库存 */
                $model_order->change_stock('+', $order_id);
                break;
        }
        return $model_order->edit($where, $data);
    }

    function logResult($word = '')
    {
        $fp = fopen("/data/web/s.hnsjb.cn/alipay.log", "a");
        flock($fp, LOCK_EX);
        fwrite($fp, "执行日期：" . strftime("%Y%m%d%H%M%S", time()) . "\n" . $word . "\n");
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    /**
     * 获取收货人信息
     *
     * @author Garbin
     * @param int $user_id            
     * @return array
     */
    function _get_my_address($user_id)
    {
        if (! $user_id) {
            return array();
        }
        $address_model = & m('address');
        
        return $address_model->find('user_id=' . $user_id);
    }

    /**
     * 获取配送方式
     *
     * @author Garbin
     * @param int $store_id            
     * @return array
     */
    function _get_shipping_methods($spec_array)
    {
        if (! $spec_array) {
            return array();
        }
        $db = & db();
        // $shipping_model =& m('shipping');
        $user_info = unserialize(stripslashes($_COOKIE['user_info']));
        $user_id = $user_info["user_id"];
        // return $shipping_model->find('enabled=1 AND store_id=' . $store_id);
        foreach ($spec_array as $v) {
            $shipping_fee = 0;
            $spec_id = $v;
            $spec_sql = "select * from ecm_goods_spec where spec_id = '$spec_id'";
            $spec_row = $db->query($spec_sql);
            while ($s_result = mysql_fetch_assoc($spec_row)) {
                $spec_result = $s_result;
            }
            $goods_id = $spec_result["goods_id"];
            $shipping_sql = "select * from ecm_goods_shipping where goods_id = '$goods_id'";
            $shipping_row = $db->query($shipping_sql);
            while ($content = mysql_fetch_assoc($shipping_row)) {
                $param[] = $content;
                $shipping_result[$spec_id][] = $content;
            }
            $shipping = $param;
            unset($param);
            $goods_sql = "select * from ecm_goods where goods_id = '$goods_id'";
            $goods_row = $db->query($goods_sql);
            while ($g_result = mysql_fetch_assoc($goods_row)) {
                $goods_result = $g_result;
            }
            
            $cart_sql = "select * from ecm_cart where user_id = '$user_id' and spec_id = '$spec_id'";
            $cart_row = $db->query($cart_sql);
            while ($c_result = mysql_fetch_assoc($cart_row)) {
                $cart_result = $c_result;
            }
            foreach ($shipping as $c => $a) {
                $shipping_id = $a["shipping_id"];
                $check_sql = "select * from ecm_shipping where shipping_id = '$shipping_id'"; // 查找配送表
                $check_row = $db->query($check_sql);
                while ($a_result = mysql_fetch_assoc($check_row)) {
                    $test = $a_result;
                }
                // var_dump($cart_result["quantity"]);
                if ($goods_result["shipping_caculation"] == 'number') {
                    $shipping_fee = $test["first_price"] + ($cart_result["quantity"] - 1) * $test["step_price"];
                    // var_dump($test["first_price"]);
                    // var_dump($cart_result["quantity"]);
                    // var_dump($test["step_price"]);
                    // var_dump('**********************');
                } else {
                    $step_weight = ($cart_result["quantity"] - 1) * ceil($goods_result["weight"]);
                    if ($step_weight < 0) {
                        $step_weight = 0;
                    }
                    $shipping_fee = $test["first_weight"] + ($step_weight) * $test["step_weight"];
                }
                $shipping_result[$spec_id][$c]["shipping_fee"] = $shipping_fee;
            }
        }
        return $shipping_result;
    }

    /**
     * 获取支付方式
     *
     * @author Garbin
     * @param int $store_id            
     * @return array
     */
    function _get_payments($store_id)
    {
        if (! $store_id) {
            return array();
        }
        $payment_model = & m('payment');
        
        return $payment_model->get_enabled($store_id);
    }

    /**
     * 生成订单号
     *
     * @author Garbin
     * @return string
     */
    function _gen_order_sn()
    {
        /* 选择一个随机的方案 */
        mt_srand((double) microtime() * 1000000);
        $timestamp = gmtime();
        $y = date('y', $timestamp);
        $z = date('z', $timestamp);
        $order_sn = $y . str_pad($z, 3, '0', STR_PAD_LEFT) . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        
        $model_order = & m('order');
        $orders = $model_order->find('order_sn=' . $order_sn);
        if (empty($orders)) {
            /* 否则就使用这个订单号 */
            return $order_sn;
        }
        
        /* 如果有重复的，则重新生成 */
        return $this->_gen_order_sn();
    }

    /**
     * 验证收货人信息是否合法
     *
     * @author Garbin
     * @param array $consignee            
     * @return void
     */
    function _valid_consignee_info($consignee)
    {
        if ($consignee["address_options"]) {
            $db = & db();
            $address_id = $consignee["address_options"];
            $sql = "select * from ecm_address where addr_id='$address_id'";
            $row = $db->query($sql);
            while ($content = mysql_fetch_assoc($row)) {
                $test = $content;
            }
            ;
            $consignee["consignee"] = $test["consignee"];
            $consignee["region_id"] = $test["region_id"];
            $consignee["region_name"] = $test["region_name"];
            $consignee["address"] = $test["address"];
            $consignee["zipcode"] = $test["zipcode"];
            $consignee["phone_tel"] = $test["phone_tel"];
            $consignee["phone_mob"] = $test["phone_mob"];
            return $consignee;
        }
        if (! $consignee['consignee']) {
            $this->_error('consignee_empty');
            
            return false;
        }
        if (! $consignee['region_id']) {
            $this->_error('region_empty');
            
            return false;
        }
        if (! $consignee['address']) {
            $this->_error('address_empty');
            
            return false;
        }
        if (! $consignee['phone_tel'] && ! $consignee['phone_mob']) {
            $this->_error('phone_required');
            
            return false;
        }
        
        // if (!$consignee['shipping_id'])
        // {
        // $this->_error('shipping_required');
        //
        // return false;
        // }
        
        return $consignee;
    }

    /**
     * 获取商品列表
     *
     * @author Garbin
     * @param int $order_id            
     * @return array
     */
    function _get_goods_list($order_id)
    {
        if (! $order_id) {
            return array();
        }
        $ordergoods_model = & m('ordergoods');
        
        return $ordergoods_model->find("order_id={$order_id}");
    }

    /**
     * 获取扩展信息
     *
     * @author Garbin
     * @param int $order_id            
     * @return array
     */
    function _get_order_extm($order_id)
    {
        if (! $order_id) {
            return array();
        }
        
        $orderextm_model = & m('orderextm');
        
        return $orderextm_model->get($order_id);
    }

    /**
     * 获取订单操作日志
     *
     * @author Garbin
     * @param int $order_id            
     * @return array
     */
    function _get_order_logs($order_id)
    {
        if (! $order_id) {
            return array();
        }
        
        $model_orderlog = & m('orderlog');
        
        return $model_orderlog->find("order_id = {$order_id}");
    }

    /**
     * 处理订单基本信息,返回有效的订单信息数组
     *
     * @author Garbin
     * @param array $goods_info            
     * @param array $post            
     * @return array
     */
    function _handle_order_info($goods_info, $post)
    {
        $total = $goods_info["total"]; // 商品总额
        $real_total = $goods_info["real_total"]; // 商品总额扣除积分抵扣
        $total_credit = $goods_info["credit_max_consume"]; // $post['credit_used'];//消费者想要消费的积分额度、
        $credit_max_consume = $goods_info["credit_max_consume"];
        // foreach($goods_info["items"] as $k=>$v)
        // {
        // foreach($v as $a=>$b)
        // {
        // if($if_credit == 1)
        // {
        // $single_credit_max_consume = $b["amount"]*$b["credit_percent"]/CREDIT_RATE;
        // $credit_contrast_rate = $single_credit_max_consume/$credit_max_consume;
        // $single_credit_consume = ceil($total_credit * $credit_contrast_rate);
        // }
        // }
        // }
        /* 默认都是待付款 */
        $order_status = ORDER_PENDING;
        $cod_array = array();
        foreach ($post as $k => $v) {
            if (substr($k, 0, 3) == "cod") {
                if ($v == 1) {
                    $test = explode("+", $k);
                    $cod_array[] = $test[1];
                }
            }
        }
        /* 买家信息 */
        $visitor = & env('visitor');
        $user_id = $visitor->get('user_id');
        $user_name = $visitor->get('user_name');
        // $if_credit = false;
        // foreach($goods_info["items"] as $v)
        // {
        // foreach($v as $c)
        // {
        // if($c["if_credit"] == 1){
        // $if_credit = true;
        // }
        // }
        // }
        // $if_credit = $post["if_credit"];//是否选择了积分消费
        foreach ($goods_info['items'] as $k => $v) {
            foreach ($v as $e => $c) {
                
                $single_credit_consume = 0;
                $amount = 0;
                $cod = 0;
                if (in_array($c["spec_id"], $cod_array)) // 如果为货到付款，则结算时该商品价格不计入总价，如果选用积分消费，则不能选择货到付款方式
{
                    $cod = 1;
                } else {
                    $amount = $amount + $c['amount'];
                }
                
                if ($c["if_credit"] == 1) {
                    $single_credit_max_consume = $c["amount"] * $c["credit_percent"] / CREDIT_RATE;
                    $credit_contrast_rate = $single_credit_max_consume / $credit_max_consume;
                    $single_credit_consume = ceil($total_credit * $credit_contrast_rate);
                }
                
                $result[addslashes($v[$e]['store_id'])][] = array(
                    'order_sn' => $this->_gen_order_sn(),
                    'goods_id' => $c["goods_id"],
                    'type' => $goods_info['type'],
                    'extension' => $this->_name,
                    'seller_id' => $k,
                    'seller_name' => addslashes($v[$e]['store_name']),
                    'buyer_id' => $user_id,
                    'buyer_name' => addslashes($user_name),
                    'buyer_email' => $visitor->get('email'),
                    'status' => $order_status,
                    'add_time' => gmtime(),
                    'goods_amount' => $amount,
                    'discount' => isset($goods_info['discount']) ? $goods_info['discount'] : 0,
                    'anonymous' => intval($post['anonymous']),
                    'postscript' => trim($post['postscript']),
                    'cod' => $cod,
                    'credit_consume' => $single_credit_consume,
                    'if_credit' => $c["if_credit"],
                    'spec_id' => $c["spec_id"]
                );
                // "credit_percent"=> $c["credit_percent"],
            }
            // $result[] = array(
            // 'order_sn' => $this->_gen_order_sn(),
            // 'type' => $goods_info['type'],
            // 'extension' => $this->_name,
            // 'seller_id' => $k,
            // 'seller_name' => addslashes($v[0]['store_name']),
            // 'buyer_id' => $user_id,
            // 'buyer_name' => addslashes($user_name),
            // 'buyer_email' => $visitor->get('email'),
            // 'status' => $order_status,
            // 'add_time' => gmtime(),
            // 'goods_amount' => $amount,
            // 'discount' => isset($goods_info['discount']) ? $goods_info['discount'] : 0,
            // 'anonymous' => intval($post['anonymous']),
            // 'postscript' => trim($post['postscript']),
            // );
        }
        return $result;
        // /* 返回基本信息 */
        // return array(
        // 'order_sn' => $this->_gen_order_sn(),
        // 'type' => $goods_info['type'],
        // 'extension' => $this->_name,
        // 'seller_id' => $goods_info['store_id'],
        // 'seller_name' => addslashes($goods_info['store_name']),
        // 'buyer_id' => $user_id,
        // 'buyer_name' => addslashes($user_name),
        // 'buyer_email' => $visitor->get('email'),
        // 'status' => $order_status,
        // 'add_time' => gmtime(),
        // 'goods_amount' => $goods_info['amount'],
        // 'discount' => isset($goods_info['discount']) ? $goods_info['discount'] : 0,
        // 'anonymous' => intval($post['anonymous']),
        // 'postscript' => trim($post['postscript']),
        // );
    }

    /**
     * 处理收货人信息，返回有效的收货人信息
     *
     * @author Garbin
     * @param array $goods_info            
     * @param array $post            
     * @return array
     */
    function _handle_consignee_info($goods_info, $post)
    {
        $db = & db();
        /* 验证收货人信息填写是否完整 */
        $consignee_info = $this->_valid_consignee_info($post);
        if (! $consignee_info) {
            return false;
        }
        /* 计算配送费用 */
        // $shipping_model =& m('shipping');
        // $shipping_info = $shipping_model->get("shipping_id={$consignee_info['shipping_id']} AND store_id={$goods_info['store_id']} AND enabled=1");
        // if (empty($shipping_info))
        // {
        // $this->_error('no_such_shipping');
        //
        // return false;
        // }
        foreach ($consignee_info as $k => $v) {
            if (is_int($k)) {
                $store_shipping_id = $_POST[$k];
                $store_shipping_array = explode("+", $store_shipping_id);
                $store_id = $store_shipping_array[0];
                $shipping_id = $store_shipping_array[1];
                $spec_id = $store_shipping_array[2];
                $spec_sql = "select * from ecm_goods_spec where spec_id = '$spec_id'";
                $spec_row = $db->query($spec_sql);
                while ($t_spec = mysql_fetch_assoc($spec_row)) {
                    $spec_result = $t_spec;
                }
                $goods_id = $spec_result["goods_id"];
                $postage_sql = "select * from ecm_goods where goods_id = '$goods_id'";
                $postage_row = $db->query($postage_sql);
                while ($t_result = mysql_fetch_assoc($postage_row)) {
                    $postage_goods = $t_result;
                }
                $shipping_caculation = $postage_goods["shipping_caculation"];
                $sql = "select * from ecm_shipping where shipping_id = '$shipping_id'";
                $row = $db->query($sql);
                while ($content = mysql_fetch_assoc($row)) {
                    $test = $content;
                }
                ;
                $shipping_id_array[] = $test["shipping_id"];
                $shipping_name[] = $test["shipping_name"];
                $single_fee[$store_id] = $test['first_price'];
                // $shipping_fee[$store_id] = $shipping_fee[$store_id] + $single_fee[$store_id];
                if ($shipping_caculation == 'number') {
                    $shipping_fee[$spec_id] = $test["first_price"] + ($goods_info["items"][$store_id][$spec_id]["quantity"] - 1) * $test["step_price"];
                } else {
                    $shipping_fee[$spec_id] = $test["first_weight"] + (($goods_info["items"][$store_id][$spec_id]["quantity"] - 1) * ceil($goods_info["items"][$store_id][$spec_id]["weight"])) * $test["step_weight"];
                }
                // $shipping_fee[$goods_id] = $test['first_price'];
                if ($postage_goods["postage_daofu"]) // 如果为邮费到付，则结算时运费为0
{
                    $shipping_fee[$spec_id] = 0;
                }
            }
            
            // $shipping_fee = $shipping_fee + $single_fee;
        }
        /* 配送费用=首件费用＋超出的件数*续件费用 */
        // $shipping_fee = $shipping_info['first_price'] + ($goods_info['quantity'] - 1) * $shipping_info['step_price'];
        
        return array(
            'consignee' => $consignee_info['consignee'],
            'region_id' => $consignee_info['region_id'],
            'region_name' => $consignee_info['region_name'],
            'address' => $consignee_info['address'],
            'zipcode' => $consignee_info['zipcode'],
            'phone_tel' => $consignee_info['phone_tel'],
            'phone_mob' => $consignee_info['phone_mob'],
            
            // 'shipping_id' => $consignee_info['shipping_id'],
            // 'shipping_name' => addslashes($shipping_info['shipping_name']),
            'shipping_id' => $shipping_id_array,
            'shipping_name' => $shipping_name,
            'shipping_fee' => $shipping_fee
        );
    }

    /**
     * 获取一级地区
     *
     * @author Garbin
     * @param
     *            none
     * @return void
     */
    function _get_regions()
    {
        $model_region = & m('region');
        $regions = $model_region->get_list(0);
        if ($regions) {
            $tmp = array();
            foreach ($regions as $key => $value) {
                $tmp[$key] = $value['region_name'];
            }
            $regions = $tmp;
        }
        
        return $regions;
    }
}

?>