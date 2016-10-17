<?php

/**
 *    普通订单类型
 *
 *    @author    Garbin
 *    @usage    none
 */
class NormalOrder extends BaseOrder
{

    var $_name = 'normal';

    /**
     * 查看订单
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
        
        /* 获取商品列表 */
        $data['goods_list'] = $this->_get_goods_list($order_id);
        
        /* 配关信息 */
        $data['order_extm'] = $this->_get_order_extm($order_id);
        
        /* 支付方式信息 */
        if ($order_info['payment_id']) {
            $payment_model = & m('payment');
            $payment_info = $payment_model->get("payment_id={$order_info['payment_id']}");
            $data['payment_info'] = $payment_info;
        }
        
        /* 订单操作日志 */
        $data['order_logs'] = $this->_get_order_logs($order_id);
        
        return array(
            'data' => $data
        );
    }
    
    /* 显示订单表单 */
    function get_order_form($spec_array)
    {
        $data = array();
        $template = 'order.form.html';
        
        $visitor = & env('visitor');
        
        /* 获取我的收货地址 */
        $data['my_address'] = $this->_get_my_address($visitor->get('user_id'));
        $data['addresses'] = ecm_json_encode($data['my_address']);
        $data['regions'] = $this->_get_regions();
        
        /* 配送方式 */
        $data['shipping_methods'] = $this->_get_shipping_methods($spec_array);
        if (empty($data['shipping_methods'])) {
            $this->_error('no_shipping_methods');
            
            return false;
        }
        $data['shippings'] = ecm_json_encode($data['shipping_methods']);
        // foreach ($data['shipping_methods'] as $shipping)
        // {
        // $data['shipping_options'][$shipping['shipping_id']] = $shipping['shipping_name'];
        // }
        return array(
            'data' => $data,
            'template' => $template
        );
    }

    /**
     * 提交生成订单，外部告诉我要下的单的商品类型及用户填写的表单数据以及商品数据，我生成好订单后返回订单ID
     *
     * @author Garbin
     * @param array $data            
     * @return int
     */
    function submit_order($data)
    {
        /* 释放goods_info和post两个变量 */
        extract($data);
        /* 处理订单基本信息 */
        $base_info = $this->_handle_order_info($goods_info, $post);
        if (! $base_info) {
            /* 基本信息验证不通过 */
            
            return 0;
        }
        /* 处理订单收货人信息 */
        $consignee_info = $this->_handle_consignee_info($goods_info, $post);
        if (! $consignee_info) {
            /* 收货人信息验证不通过 */
            return 0;
        }
        /* 至此说明订单的信息都是可靠的，可以开始入库了 */
        
        /* 插入订单基本信息 */
        // 订单总实际总金额，可能还会在此减去折扣等费用
        // $base_info['order_amount'] = $base_info['goods_amount'] + $consignee_info['shipping_fee'] - $base_info['discount'];
        /* 如果优惠金额大于商品总额和运费的总和 */
        // if ($base_info['order_amount'] < 0)
        // {
        // $base_info['order_amount'] = 0;
        // $base_info['discount'] = $base_info['goods_amount'] + $consignee_info['shipping_fee'];
        // }
        
        $order_model = & m('order');
        $payment_code = $post["payment"];
        $db = & db();
        $payment_sql = "select payment_id, payment_name, payment_code from ecm_payment where payment_code = '$payment_code'";
        $payment_row = $db->query($payment_sql);
        while ($payment = mysql_fetch_array($payment_row)) {
            $payment_result = $payment;
        }
        $out_trade_sn = $this->_get_trade_sn();
        foreach ($base_info as $k => $v) {
            foreach ($v as $a => $b) {
                $b["payment_code"] = $payment_code;
                $b["payment_name"] = $payment_result["payment_name"];
                $b["payment_id"] = $payment_result["payment_id"];
                $b["order_amount"] = $b["goods_amount"] + $consignee_info["shipping_fee"][$b['spec_id']] - $b['discount'] - ($b["credit_consume"] / 100) * CREDIT_RATE;
                $b["out_trade_sn"] = $out_trade_sn;
                if ($b["order_amount"] < 0) {
                    $b["order_amount"] = 0;
                    $b["discount"] = $b["goods_amount"] + $consignee_info["shipping_fee"][$b["spec_id"]] - ($b["credit_consume"] / 100) * CREDIT_RATE;
                }
                if ($b["order_amount"] == 0) {
                    if ($b["if_credit"] == 1) {
                        $b["status"] = ORDER_ACCEPTED;
                        $b["payment_code"] = "credit";
                        $b["payment_name"] = "积分支付";
                        $b["payment_id"] = "9";
                        $b["pay_time"] = time();
                    } else {
                        $b["status"] = COD_ORDER;
                        $b["payment_code"] = "huodaofukuan";
                        $b["payment_name"] = "货到付款";
                        $b["payment_id"] = "8";
                        $b["pay_time"] = time();
                    }
                }
                unset($b["if_credit"]);
                unset($b["spec_id"]);
                $order_id[$k][] = $order_model->add($b);
            }
            // $order_id[$k] = $order_model->add($v);
        }
        // $con1=mysql_connect("localhost","root","hnsjb123");
        // @mysql_select_db("phpcmstest",$con1) or die("数据库不存在！".mysql_error());
        // mysql_query("SET NAMES UTF8",$con1);
        // $phpcms_sql = "update v9_member, v9_member_detail set v9_member_detail.credit = v9_member_detail.credit - '$credit_amount' where v9_member.phpssouid = '$phpsso_uid' and v9_member.userid = v9_member_detail.userid";
        // $phpcms_row = mysql_query($phpcms_sql);
        foreach ($base_info as $k => $v) {
            foreach ($v as $a => $b) {
                if ($b["if_credit"] == 1) {
                    $credit_amount = 0;
                    $credit_amount = $b["credit_consume"];
                    $user_id = $b["buyer_id"];
                    $user_sql = "select phpsso_uid from ecm_member where user_id = '$user_id'";
                    $user_row = $db->query($user_sql);
                    while ($t_result = mysql_fetch_array($user_row)) {
                        $user_result = $t_result;
                    }
                    $phpsso_uid = $user_result["phpsso_uid"];
                    $con1 = mysql_connect("localhost", "root", "root");
                    @mysql_select_db("phpcmstest", $con1) or die("数据库不存在！" . mysql_error());
                    mysql_query("SET NAMES UTF8", $con1);
                    $phpcms_sql = "update v9_member, v9_member_detail set v9_member_detail.credit = v9_member_detail.credit - '$credit_amount' where v9_member.phpssouid = '$phpsso_uid' and v9_member.userid = v9_member_detail.userid";
                    $phpcms_row = mysql_query($phpcms_sql);
                }
            }
        }
        
        if (! sizeof($order_id)) {
            /* 插入基本信息失败 */
            $this->_error('create_order_failed');
            
            return 0;
        }
        
        /* 插入收货人信息 */
        
        // $consignee_info['order_id'] = $order_id;
        /* 插入商品信息 */
        $goods_items = array();
        $goods_info["items"] = array_values($goods_info["items"]);
        $order_id = array_values($order_id);
        foreach ($goods_info["items"] as $k => $v) {
            $v = array_values($v);
            foreach ($v as $a => $b) {
                $goods_items[] = array(
                    'order_id' => $order_id[$k][$a],
                    'goods_id' => $b['goods_id'],
                    'goods_name' => $b['goods_name'],
                    'spec_id' => $b['spec_id'],
                    'specification' => $b['specification'],
                    'price' => $b['price'],
                    'quantity' => $b['quantity'],
                    'goods_image' => $b['goods_image']
                );
            }
        }
        // foreach ($goods_info['items'] as $key => $value)
        // {
        // $goods_items[] = array(
        // 'order_id' => $order_id,
        // 'goods_id' => $value['goods_id'],
        // 'goods_name' => $value['goods_name'],
        // 'spec_id' => $value['spec_id'],
        // 'specification' => $value['specification'],
        // 'price' => $value['price'],
        // 'quantity' => $value['quantity'],
        // 'goods_image' => $value['goods_image'],
        // );
        // }
        $order_goods_model = & m('ordergoods');
        $order_goods_model->add(addslashes_deep($goods_items)); // 防止二次注入
        foreach ($order_id as $v) {
            foreach ($v as $a) {
                $order_array[] = $a;
            }
        }
        $order_extm_model = & m('orderextm');
        $shipping_fee_test = array_values($consignee_info["shipping_fee"]);
        $consignee_result = $consignee_info;
        foreach ($order_array as $k => $v) {
            $consignee_result["order_id"] = $v;
            $consignee_result["shipping_fee"] = $shipping_fee_test[$k];
            $consignee_result["shipping_id"] = $consignee_info["shipping_id"][$k];
            $consignee_result["shipping_name"] = $consignee_info["shipping_name"][$k];
            // $consignee_info["order_id"] = $v;
            // $consignee_info["shipping_fee"] = $shipping_fee_test[$k];
            // $consignee_info["shipping_id"] = $consignee_info["shipping_id"][$k];
            // $consignee_info["shipping_name"] = $consignee_info["shipping_name"][$k];
            $order_extm_model->add($consignee_result);
        }
        return $order_array;
    }
    
    /* 生成支付序号 */
    function _get_trade_sn()
    {
        $db = & db();
        $index = true;
        while ($index == true) {
            $out_trade_sn = rand(1000000000, 9999999999);
            $check_sql = "select * from ecm_order where out_trade_sn = '$out_trade_sn'";
            $check_row = $db->query($check_sql);
            while ($result = mysql_fetch_array($check_row)) {
                $check_result = $result;
            }
            if ($check_result) {} else {
                $index = false;
            }
        }
        return $out_trade_sn;
    }
}

?>