<?php
// ini_set("display_errors", 1);

/**
 * 收银台控制器，其扮演的是收银员的角色，你只需要将你的订单交给收银员，收银员按订单来收银，她专注于这个过程
 *
 * @author Garbin
 */
class CashierApp extends ShoppingbaseApp
{

    /**
     * 根据提供的订单信息进行支付
     *
     * @author Garbin
     * @param
     *            none
     * @return void
     */
    function index()
    {
        $db = & db();
        /* 外部提供订单号 */
        $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;
        if (! $order_id) {
            $this->show_warning('no_such_order');
            
            return;
        }
        // 支付单个订单时
        if (intval($order_id)) {
            $order_id = array(
                $order_id
            );
            $order_model = & m('order');
            foreach ($order_id as $v) {
                $order_info[] = $order_model->get("order_id={$v} AND buyer_id=" . $this->visitor->get('user_id'));
            }
            if (empty($order_info)) {
                $this->show_warning('no_such_order');
                
                return;
            }
            /* 订单有效性判断 */
            foreach ($order_info as $v) {
                if ($v["payment_code"] != 'cod' && $v["status"] != ORDER_PENDING) {
                    $this->show_warning("no_such_order");
                    return;
                }
            }
            $payment_model = & m('payment');
            foreach ($order_info as $v) {
                $amount = $amount + $v["order_amount"]; // 需要支付的总价
            }
            $payment["payment_id"] = $order_info[0]["payment_id"];
            $payment["payment_name"] = $order_info[0]["payment_name"];
            $payment["payment_code"] = $order_info[0]["payment_code"];
            $new_trade_sn = $this->_get_trade_sn(); // 生成新的支付交易号
            
            $order_id = $order_info[0]["order_id"];
            $sql = "update ecm_order set out_trade_sn = '$new_trade_sn' where order_id = '$order_id'"; // 更新订单的支付交易号
            $db->query($sql);
            $order_info[0]["out_trade_sn"] = $new_trade_sn;
            $shipping_sql = "select * from ecm_order_extm where order_id = '$order_id'";
            $shipping_row = $db->query($shipping_sql);
            while ($t_result = mysql_fetch_array($shipping_row)) {
                $shipping_result = $t_result;
            }
            $shipping_name = $shipping_result["shipping_name"];
            $shipping[] = $shipping_name;
            $order_sn[] = $order_info[0]["order_sn"];
            // 同时支付多个订单
        } else {
            $order_id = json_decode($order_id);
            
            /* 内部根据订单号收银,获取收多少钱，使用哪个支付接口 */
            $order_model = & m('order');
            foreach ($order_id as $v) {
                $order_info[] = $order_model->get("order_id={$v} AND buyer_id=" . $this->visitor->get('user_id'));
            }
            // $order_info = $order_model->get("order_id={$order_id} AND buyer_id=" . $this->visitor->get('user_id'));
            if (empty($order_info)) {
                $this->show_warning('no_such_order');
                
                return;
            }
            
            /* 订单有效性判断 */
            foreach ($order_info as $v) {
                if ($v["payment_code"] != 'cod' && $v["status"] != ORDER_PENDING && $v["order_amount"] != 0.00) {
                    $this->show_warning("no_such_order");
                    return;
                }
            }
            // if ($order_info['payment_code'] != 'cod' && $order_info['status'] != ORDER_PENDING)
            // {
            // $this->show_warning('no_such_order');
            // return;
            // }
            $payment_model = & m('payment');
            foreach ($order_info as $k => $v) {
                $order_id = $v["order_id"];
                $shipping_sql = "select * from ecm_order_extm where order_id = '$order_id'";
                $shipping_row = $db->query($shipping_sql);
                while ($t_result = mysql_fetch_array($shipping_row)) {
                    $shipping_result = $t_result;
                }
                $shipping_name = $shipping_result["shipping_name"];
                $shipping[] = $shipping_name;
                $order_sn[] = $v["order_sn"];
                // 不在微信浏览器内
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false && ! (isset($_COOKIE["ECS"]["indevice"]))) {
                    $order_info[$k]["payment_id"] = 6;
                    $order_info[$k]["payment_code"] = "alipay";
                    $order_info[$k]["payment_name"] = "支付宝";
                }
                
                /* 如果是微信浏览器，则不显示支付宝支付 */
                if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == true) {
                    $order_info[$k]["payment_id"] = 7;
                    $order_info[$k]["payment_code"] = "wechatpay";
                    $order_info[$k]["payment_name"] = "微信支付";
                }
                $amount = $amount + $v["order_amount"]; // - floor(($v["credit_consume"] * CREDIT_RATE)/100);
            }
            $payment["payment_id"] = $order_info[0]["payment_id"];
            $payment["payment_name"] = $order_info[0]["payment_name"];
            $payment["payment_code"] = $order_info[0]["payment_code"];
            // if (!$order_info['payment_id'])
            // {
            // /* 若还没有选择支付方式，则让其选择支付方式 */
            // $payments = $payment_model->get_enabled($order_info['seller_id']);
            // if (empty($payments))
            // {
            // $this->show_warning('store_no_payment');
            //
            // return;
            // }
            // /* 找出配送方式，判断是否可以使用货到付款 */
            // $model_extm =& m('orderextm');
            // $consignee_info = $model_extm->get($order_id);
            // if (!empty($consignee_info))
            // {
            // /* 需要配送方式 */
            // $model_shipping =& m('shipping');
            // $shipping_info = $model_shipping->get($consignee_info['shipping_id']);
            // $cod_regions = unserialize($shipping_info['cod_regions']);
            // $cod_usable = true;//默认可用
            // if (is_array($cod_regions) && !empty($cod_regions))
            // {
            // /* 取得支持货到付款地区的所有下级地区 */
            // $all_regions = array();
            // $model_region =& m('region');
            // foreach ($cod_regions as $region_id => $region_name)
            // {
            // $all_regions = array_merge($all_regions, $model_region->get_descendant($region_id));
            // }
            //
            // /* 查看订单中指定的地区是否在可货到付款的地区列表中，如果不在，则不显示货到付款的付款方式 */
            // if (!in_array($consignee_info['region_id'], $all_regions))
            // {
            // $cod_usable = false;
            // }
            // }
            // else
            // {
            // $cod_usable = false;
            // }
            // if (!$cod_usable)
            // {
            // /* 从列表中去除货到付款的方式 */
            // foreach ($payments as $_id => $_info)
            // {
            // if ($_info['payment_code'] == 'cod')
            // {
            // /* 如果安装并启用了货到付款，则将其从可选列表中去除 */
            // unset($payments[$_id]);
            // }
            // }
            // }
            //
            // /*判断是否为微信浏览器 */
            // if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false && !(isset($_COOKIE["ECS"]["indevice"])))
            // {
            // foreach($payments as $_id => $_info)
            // {
            // if ($_info['payment_code'] == 'wechatpay')
            // {
            // /* 如果不在微信浏览器内，则删除微信支付*/
            // unset($payments[$_id]);
            // }
            // }
            // }
            //
            // /*如果是微信浏览器，则不显示支付宝支付 */
            // if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == true){
            // foreach($payments as $_id => $_info)
            // {
            // if($_info['payment_code'] == 'alipay')
            // {
            // unset($payments[$_id]);
            // }
            // }
            // }
            //
            // }
            // $all_payments = array('online' => array(), 'offline' => array());
            // foreach ($payments as $key => $payment)
            // {
            // if ($payment['is_online'])
            // {
            // $all_payments['online'][] = $payment;
            // }
            // // else
            // // {
            // // $all_payments['offline'][] = $payment;
            // // }
            // }
        }
        $this->assign('order', $order_info);
        $this->assign("out_trade_sn", $order_info[0]["out_trade_sn"]);
        $this->assign("shipping", $shipping);
        $this->assign("order_sn", $order_sn);
        if ($amount < 0)
            $amount = 0;
        $this->assign("amount", $amount);
        // 如果需要用户支付，则跳转到支付页面
        if ($amount) {
            $this->assign("payment", $payment);
            // $this->assign('payments', $all_payments);
            // $this->_curlocal(
            // LANG::get('cashier')
            // );
            //
            // $this->_config_seo('title', Lang::get('confirm_payment') . ' - ' . Conf::get('site_title'));
            $this->display('cashier.payment.html');
            // 订单价格为0，则不跳转到支付页面
        } else {
            $pay_time = $order_info[0]["pay_time"];
            $this->assign("pay_time", date("Y-m-d H:i:s", $pay_time));
            $this->display('paynotify.index.html');
        }
    }
    
    // }
    // else
    // {
    // /* 否则直接到网关支付 */
    // /* 验证支付方式是否可用，若不在白名单中，则不允许使用 */
    // if (!$payment_model->in_white_list($order_info['payment_code']))
    // {
    // $this->show_warning('payment_disabled_by_system');
    //
    // return;
    // }
    //
    // $payment_info = $payment_model->get("payment_code = '{$order_info['payment_code']}'");
    // /* 若卖家没有启用，则不允许使用 */
    // if (!$payment_info['enabled'])
    // {
    // $this->show_warning('payment_disabled');
    //
    // return;
    // }
    //
    // /* 生成支付URL或表单 */
    // $payment = $this->_get_payment($order_info['payment_code'], $payment_info);
    // $payment_form = $payment->get_payform($order_info);
    //
    // /* 货到付款，则显示提示页面 */
    // if ($payment_info['payment_code'] == 'cod')
    // {
    // $this->show_message('cod_order_notice',
    // 'view_order', 'index.php?app=buyer_order',
    // 'close_window', 'javascript:window.close();'
    // );
    //
    // return;
    // }
    //
    // /* 线下付款的 */
    // if (!$payment_info['online'])
    // {
    // $this->_curlocal(
    // Lang::get('post_pay_message')
    // );
    // }
    //
    // /* 跳转到真实收银台 */
    // $this->_config_seo('title', Lang::get('cashier'));
    // $this->assign('payform', $payment_form);
    // $this->assign('payment', $payment_info);
    // $this->assign('order', $order_info);
    // header('Content-Type:text/html;charset=' . CHARSET);
    // $this->display('cashier.payform.html');
    // }
    function merge_payment()
    { // 订单页面合并支付
        $db = & db();
        $order_id = isset($_GET['order_id']) ? $_GET['order_id'] : 0;
        if (! $order_id) {
            $this->show_warning('no_such_order');
            
            return;
        }
        $order_id = json_decode(stripcslashes($order_id));
        /* 内部根据订单号收银,获取收多少钱，使用哪个支付接口 */
        $order_model = & m('order');
        foreach ($order_id as $v) {
            $order_info[] = $order_model->get("order_id={$v} AND buyer_id=" . $this->visitor->get('user_id'));
        }
        if (empty($order_info)) {
            $this->show_warning('no_such_order');
            
            return;
        }
        
        /* 订单有效性判断 */
        foreach ($order_info as $v) {
            if ($v["payment_code"] != 'cod' && $v["status"] != ORDER_PENDING && $v["order_amount"] != 0.00) {
                $this->show_warning("no_such_order");
                return;
            }
        }
        $payment_model = & m('payment');
        $new_trade_sn = $this->_get_trade_sn();
        foreach ($order_info as $k => $v) {
            $single_order_id = $v["order_id"];
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == false && ! (isset($_COOKIE["ECS"]["indevice"]))) {
                $order_info[$k]["payment_id"] = 6;
                $order_info[$k]["payment_code"] = "alipay";
                $order_info[$k]["payment_name"] = "支付宝";
                $payment_sql = "update ecm_order set payment_id = 6, payment_code='alipay', payment_name='支付宝' where order_id = '$single_order_id'";
                $db->query($payment_sql);
            }
            
            /* 如果是微信浏览器，则不显示支付宝支付 */
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') == true) {
                $order_info[$k]["payment_id"] = 7;
                $order_info[$k]["payment_code"] = "wechatpay";
                $order_info[$k]["payment_name"] = "微信支付";
                $payment_sql = "update ecm_order set payment_id = 7, payment_code='wechatpay', payment_name='微信支付' where order_id = '$single_order_id'";
                $db->query($payment_sql);
            }
            $amount = $amount + $v["order_amount"]; // - floor(($v["credit_consume"] * CREDIT_RATE)/100);
            $sql = "update ecm_order set out_trade_sn = '$new_trade_sn' where order_id = '$single_order_id'";
            $db->query($sql);
        }
        $payment["payment_id"] = $order_info[0]["payment_id"];
        $payment["payment_name"] = $order_info[0]["payment_name"];
        $payment["payment_code"] = $order_info[0]["payment_code"];
        // $order_id = $order_info[0]["order_id"];
        // $sql = "update ecm_order set out_trade_sn = '$new_trade_sn' where order_id = '$order_id'";
        // $db->query($sql);
        $this->assign('order', $order_info);
        $this->assign("out_trade_sn", $new_trade_sn);
        if ($amount < 0)
            $amount = 0;
        $this->assign("amount", $amount);
        if ($amount) {
            $this->assign("payment", $payment);
            // $this->assign('payments', $all_payments);
            // $this->_curlocal(
            // LANG::get('cashier')
            // );
            //
            // $this->_config_seo('title', Lang::get('confirm_payment') . ' - ' . Conf::get('site_title'));
            $this->display('cashier.payment.html');
        } else {
            $this->display('paynotify.index.html');
        }
    }

    /**
     * 确认支付
     *
     * @author Garbin
     * @return void
     */
    function goto_pay()
    {
        // $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $out_trade_sn = isset($_GET['out_trade_sn']) ? intval($_GET['out_trade_sn']) : 0;
        $payment_id = isset($_POST['payment_id']) ? intval($_POST['payment_id']) : 0;
        if (! $out_trade_sn) {
            $this->show_warning('no_such_order');
            
            return;
        }
        if (! $payment_id) {
            $this->show_warning('no_such_payment');
            
            return;
        }
        // $order_model =& m('order');
        // $order_info[] = $order_model->get("out_trade_sn={$out_trade_sn} AND buyer_id=" . $this->visitor->get('user_id'));
        $user_id = $this->visitor->get("user_id");
        $db = & db();
        $order_sql = "select * from ecm_order where out_trade_sn = '$out_trade_sn' and buyer_id = '$user_id'";
        $order_row = $db->query($order_sql);
        while ($t_result = mysql_fetch_array($order_row)) {
            $order_info[] = $t_result;
        }
        if (empty($order_info)) {
            $this->show_warning('no_such_order');
            
            return;
        }
        // 可能不合适
        if ($order_info[0]['payment_id']) {
            $this->_goto_pay($out_trade_sn);
            return;
        }
        
        /* 验证支付方式 */
        //
        // $payment_info = $payment_model->get($payment_id);
        // if (!$payment_info)
        // {
        // $this->show_warning('no_such_payment');
        //
        // return;
        // }
        //
        // /* 保存支付方式 */
        // $edit_data = array(
        // 'payment_id' => $payment_info['payment_id'],
        // 'payment_code' => $payment_info['payment_code'],
        // 'payment_name' => $payment_info['payment_name'],
        // );
        //
        // /* 如果是货到付款，则改变订单状态 */
        // if ($payment_info['payment_code'] == 'cod')
        // {
        // $edit_data['status'] = ORDER_SUBMITTED;
        // }
        //
        // $order_model->edit($order_id, $edit_data);
        //
        // /* 开始支付 */
        // $this->_goto_pay($order_id);
    }

    /**
     * 线下支付消息
     *
     * @author Garbin
     * @return void
     */
    function offline_pay()
    {
        if (! IS_POST) {
            return;
        }
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $pay_message = isset($_POST['pay_message']) ? trim($_POST['pay_message']) : '';
        if (! $order_id) {
            $this->show_warning('no_such_order');
            return;
        }
        if (! $pay_message) {
            $this->show_warning('no_pay_message');
            
            return;
        }
        $order_model = & m('order');
        $order_info = $order_model->get("order_id={$order_id} AND buyer_id=" . $this->visitor->get('user_id'));
        if (empty($order_info)) {
            $this->show_warning('no_such_order');
            
            return;
        }
        $edit_data = array(
            'pay_message' => $pay_message
        );
        
        $order_model->edit($order_id, $edit_data);
        
        /* 线下支付完成并留下pay_message,发送给卖家付款完成提示邮件 */
        $model_member = & m('member');
        $seller_info = $model_member->get($order_info['seller_id']);
        $mail = get_mail('toseller_offline_pay_notify', array(
            'order' => $order_info,
            'pay_message' => $pay_message
        ));
        $this->_mailto($seller_info['email'], addslashes($mail['subject']), addslashes($mail['message']));
        
        $this->show_message('pay_message_successed', 'view_order', 'index.php?app=buyer_order', 'close_window', 'javascript:window.close();');
    }

    function _goto_pay($out_trade_sn)
    {
        $user_id = $this->visitor->get("user_id");
        $db = & db();
        $order_sql = "select * from ecm_order where out_trade_sn = '$out_trade_sn' and buyer_id = '$user_id'";
        $order_row = $db->query($order_sql);
        while ($t_result = mysql_fetch_array($order_row)) {
            $order_info[] = $t_result;
        }
        $alipay = false;
        $wechatpay = false;
        foreach ($order_info as $v) {
            if ($v["payment_code"] == "alipay") {
                $alipay = true;
            } elseif ($v["payment_code"] == "wechatpay") {
                $wechatpay = true;
            }
        }
        if ($alipay) {
            $payment_code = "alipay";
        } elseif ($wechatpay) {
            $payment_code = "wechatpay";
        }
        $payment_model = & m('payment');
        // $order_info = $order_model->get("out_trade_sn={$out_trade_sn} AND buyer_id=" . $this->visitor->get('user_id'));
        if (! $payment_model->in_white_list($payment_code)) {
            $this->show_warning('payment_disabled_by_system');
            
            return;
        }
        $payment_info = $payment_model->get("payment_code = '$payment_code'");
        /* 若卖家没有启用，则不允许使用 */
        if (! $payment_info['enabled']) {
            $this->show_warning('payment_disabled');
            
            return;
        }
        
        /* 生成支付URL或表单 */
        $payment = $this->_get_payment($payment_code, $payment_info);
        $payment_form = $payment->get_payform($order_info);
        /* 货到付款，则显示提示页面 */
        if ($payment_info['payment_code'] == 'cod') {
            $this->show_message('cod_order_notice', 'view_order', 'index.php?app=buyer_order', 'close_window', 'javascript:window.close();');
            
            return;
        }
        
        /* 线下付款的 */
        if (! $payment_info['online']) {
            $this->_curlocal(Lang::get('post_pay_message'));
        }
        
        /* 跳转到真实收银台 */
        $this->_config_seo('title', Lang::get('cashier'));
        $this->assign('payform', $payment_form);
        $this->assign('payment', $payment_info);
        $this->assign('order', $order_info);
        header('Content-Type:text/html;charset=' . CHARSET);
        $this->display('cashier.payform.html');
    }

    function wechat_callback()
    {
        $appid = "wxa874a4ea498e6887";
        $appsecret = "9b1613d944698035b5ff8d022cd75f66";
        $order_id = $_GET['order_id'];
        $order_id = json_decode($order_id);
        $order_model = & m('order');
        foreach ($order_id as $v) {
            $order_info[] = $order_model->get("order_id={$v['order_id']} AND buyer_id=" . $this->visitor->get('user_id'));
        }
        // $order_info = $order_model->get("order_id={$order_id[0]} AND buyer_id=" . $this->visitor->get('user_id'));
        $out_trade_sn = $order_info[0]['out_trade_sn']; // 商品订单号
                                                        // if (!$out_trade_sn)
                                                        // {
                                                        // $out_trade_sn = $this->_config['pcode'] . $order_info[]['order_sn'];
                                                        //
                                                        // /* 将此数据写入订单中 */
                                                        // $model_order =& m('order');
                                                        // $model_order->edit(intval($order_info['order_id']), array('out_trade_sn' => $out_trade_sn));
                                                        // }
        $body = '绿公社订单:' . $out_trade_sn; // 商品简介
        $params['out_trade_no'] = $order_info[0]['order_trade_sn'];
        $params['body'] = $body;
        foreach ($order_info as $v) {
            $order_amount = $order_amount + $v["order_amount"];
        }
        $params['total_fee'] = $order_amount; // $order_info['order_amount'] * 100;
        $params['notify_url'] = "http://s.hnsjb.cn/wechat.php";
        if (isset($_COOKIE["ECS"]["indevice"])) {
            $params['trade_type'] = "APP";
            $params['appid'] = "wx18dbed3a77ba78a2";
            $params['mch_id'] = "1308942101";
        } else {
            $params['trade_type'] = "JSAPI";
            $params['appid'] = "wxa874a4ea498e6887";
            $params['mch_id'] = "1234234102";
        }
        $params['spbill_create_ip'] = real_ip();
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $noncestr = "";
        for ($i = 0; $i < 16; $i ++) {
            $noncestr .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        $params['nonce_str'] = $noncestr;
        $code = $_GET['code'];
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res);
        $openid = $res->openid;
        $params['openid'] = $openid;
        $params['sign'] = $this->getSign($params);
        $xml = $this->arrayToXml($params);
        $ch1 = curl_init();
        // 设置超时
        curl_setopt($ch1, CURLOPT_TIMEOUT, 30);
        // 这里设置代理，如果有的话
        // curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        // curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch1, CURLOPT_URL, "https://api.mch.weixin.qq.com/pay/unifiedorder");
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, FALSE);
        // 设置header
        curl_setopt($ch1, CURLOPT_HEADER, FALSE);
        // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE);
        // post提交方式
        curl_setopt($ch1, CURLOPT_POST, TRUE);
        curl_setopt($ch1, CURLOPT_POSTFIELDS, $xml);
        // 运行curl
        $data = curl_exec($ch1);
        $testResult = json_decode(json_encode(simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        // 返回结果
        if ($data) {} else {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error" . "<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch1);
            return false;
        }
        
        $prepay_id = $testResult['prepay_id'];
        $jsApiObj["appId"] = "wxa874a4ea498e6887";
        $timeStamp = time();
        $jsApiObj["timeStamp"] = "$timeStamp";
        $jsApiObj["nonceStr"] = $this->createNoncestr();
        $jsApiObj["package"] = "prepay_id=$prepay_id";
        $jsApiObj["signType"] = "MD5";
        $jsApiObj["paySign"] = $this->getSign($jsApiObj);
        $jsonData = json_encode($jsApiObj);
        $this->assign('jsonData', $jsonData);
        $this->assign('order_id', $order_id);
        header('Content-Type:text/html;charset=' . CHARSET);
        // $this->display('wechatpay.index.html');
    }

    public function getSign($Obj) // 获取签名
    {
        foreach ($Obj as $k => $v) {
            $Parameters[$k] = $v;
        }
        // 签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatBizQueryParaMap($Parameters, false);
        // echo '【string1】'.$String.'</br>';
        // 签名步骤二：在string后加入KEY
        $String = $String . "&key=KQTJnaBu7rcIOLANWur2m6cSNUjnhyTI";
        // echo "【string2】".$String."</br>";
        // 签名步骤三：MD5加密
        $String = md5($String);
        // echo "【string3】 ".$String."</br>";
        // 签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        // echo "【result】 ".$result_."</br>";
        return $result_;
    }

    function formatBizQueryParaMap($paraMap, $urlencode)
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            // $buff .= strtolower($k) . "=" . $v . "&";
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    function createXml()
    {
        return $this->arrayToXml($this->parameters);
    }

    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
        }
        $xml .= "</xml>";
        return $xml;
    }

    function getPrepayId()
    {
        $this->postXml();
        $this->result = $this->xmlToArray($this->response);
        $prepay_id = $this->result["prepay_id"];
        return $prepay_id;
    }

    function postXml()
    {
        $xml = $this->createXml();
        $this->response = $this->postXmlCurl($xml, $this->url, $this->curl_timeout);
        return $this->response;
    }

    public function createNoncestr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i ++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
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
