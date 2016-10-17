<?php

/**
 *    支付网关通知接口
 *
 *    @author    Garbin
 *    @usage    none
 */
class PaynotifyApp extends MallbaseApp
{
    /**
     *    支付完成后返回的URL，在此只进行提示，不对订单进行任何修改操作,这里不严格验证，不改变订单状态
     *
     *    @author    Garbin
     *    @return    void
     */
    function index()
    {
        //这里是支付宝，财付通等当订单状态改变时的通知地址
        $out_trade_sn   = isset($_GET['out_trade_sn']) ? intval($_GET['out_trade_sn']) : 0; //哪个订单
        if (!$out_trade_sn)
        {
            /* 无效的通知请求 */
            $this->show_warning('forbidden');

            return;
        }

        /* 获取订单信息 */
//        $model_order =& m('order');
//        $order_info  = $model_order->get($order_id);
		$user_id = $this->visitor->get("user_id");
		$db = & db();
    	$order_sql = "select * from ecm_order where out_trade_sn = '$out_trade_sn' and buyer_id = '$user_id'";
	    $order_row = $db->query($order_sql);
	    while($t_result = mysql_fetch_array($order_row))
	    {
	       	$order_info[] = $t_result; 
	    }
        if (empty($order_info))
        {
            /* 没有该订单 */
            $this->show_warning('forbidden');

            return;
        }
		$amount = 0;
		$alipay = false;
         $wechatpay = false;
		foreach($order_info as $v)
		{
			$amount = $amount + $v["order_amount"];
			if($v['payment_code'] == "alipay")
			{
				$alipay = true;
			}
			elseif($v["payment_code"] == "wechatpay") 
			{
				$wechatpay = true;
			}
		}
		
    	if($alipay)
		{
			$payment_code = "alipay";
		}
		elseif($wechatpay)
		{
			$payment_code = "wechatpay";
		}
        $model_payment =& m('payment');
        $payment_info  = $model_payment->get("payment_code='$payment_code'");
        if (empty($payment_info))
        {
            /* 没有指定的支付方式 */
            $this->show_warning('no_such_payment');

            return;
        }

        /* 调用相应的支付方式 */
        $payment = $this->_get_payment($payment_code, $payment_info);
        /* 获取验证结果 */
        $notify_result = $payment->verify_notify($order_info);
        if ($notify_result === false)
        {
            /* 支付失败 */
            $this->show_warning($payment->get_error());

            return;
        }

        $notify_result['target']=ORDER_ACCEPTED;
		
        #TODO 临时在此也改变订单状态为方便调试，实际发布时应把此段去掉，订单状态的改变以notify为准
        //$this->_change_order_status($order_id, $order_info['extension'], $notify_result);
		$out_trade_sn = $order_info[0]["out_trade_sn"];
		$pay_time = date("Y-m-d H:i:s", $order_info[0]['pay_time']);
        /* 只有支付时会使用到return_url，所以这里显示的信息是支付成功的提示信息 */
        $this->_curlocal(LANG::get('pay_successed'));
        $this->assign("out_trade_sn", $out_trade_sn);
        $this->assign("pay_time", $pay_time);
        $this->assign('order', $order_info);
        $this->assign("amount", $amount);
        $this->assign('payment', $payment_info);
        $this->display('paynotify.index.html');
    }

    /**
     *    支付完成后，外部网关的通知地址，在此会进行订单状态的改变，这里严格验证，改变订单状态
     *
     *    @author    Garbin
     *    @return    void
     */
    function notify()
    {
        //这里是支付宝，财付通等当订单状态改变时的通知地址
        $order_id   = 0;
        if(isset($_POST['out_trade_sn']))
        {
            $out_trade_sn = intval($_POST['out_trade_sn']);
        }
        else
        {
            $out_trade_sn = intval($_GET['out_trade_sn']);
        }
        if (!$out_trade_sn)
        {
            /* 无效的通知请求 */
            $this->show_warning('no_such_order');
            return;
        }

        /* 获取订单信息 */
    		$db = & db();
	       	$order_sql = "select * from ecm_order where out_trade_sn = '$out_trade_sn'";
	       	$order_row = $db->query($order_sql);
	       	while($t_result = mysql_fetch_array($order_row))
	       	{
	       			$order_info[] = $t_result; 
	       	}
	       	
        if (empty($order_info))
        {
            /* 没有该订单 */
            $this->show_warning('no_such_order');
            return;
        }
    	$alipay = false;
         $wechatpay = false;
    	foreach($order_info as $v)
		{
			if($v['payment_code'] == "alipay")
			{
				$alipay = true;
			}
			elseif($v["payment_code"] == "wechatpay") 
			{
				$wechatpay = true;
			}
		}
    	if($alipay)
		{
			$payment_code = "alipay";
		}
		elseif($wechatpay)
		{
			$payment_code = "wechatpay";
		}
        $model_payment =& m('payment');
        $payment_info  = $model_payment->get("payment_code='$payment_code'");
        if (empty($payment_info))
        {
            /* 没有指定的支付方式 */
            $this->show_warning('no_such_payment');
            return;
        }

        /* 调用相应的支付方式 */
        $payment = $this->_get_payment($payment_code, $payment_info);
        /* 获取验证结果 */
        $notify_result = $payment->verify_notify($order_info, true);

        if ($notify_result === false)
        {
            /* 支付失败 */
            $payment->verify_result(false);
            return;
        }
		$credit_amount = 0;//消费积分
        //改变订单状态
        foreach($order_info as $v)
        {
        	$this->_change_order_status($v["order_id"], $v['extension'], $notify_result);
        	$credit_amount = $credit_amount + $v["credit_consume"];
        }
        $user_id = $order_info[0]["buyer_id"];
        $user_sql = "select phpsso_uid from ecm_member where user_id = '$user_id'";
        $user_row = $db->query($user_sql);
        while($t_result = mysql_fetch_array($user_row))
        {
        	$user_result = $t_result;
        }
        $phpsso_uid = $user_result["phpsso_uid"];
//        $con1=mysql_connect("localhost","root","hnsjb123");
//		@mysql_select_db("phpcmstest",$con1) or die("数据库不存在！".mysql_error());
//		mysql_query("SET NAMES UTF8",$con1);
//		$phpcms_sql = "update v9_member, v9_member_detail set v9_member_detail.credit = v9_member_detail.credit - '$credit_amount' where v9_member.phpssouid = '$phpsso_uid' and v9_member.userid = v9_member_detail.userid";
//		$phpcms_row = mysql_query($phpcms_sql);
        $payment->verify_result(true);
        if ($notify_result['target'] == ORDER_ACCEPTED)
        {
            /* 发送邮件给卖家，提醒付款成功 */
            $model_member =& m('member');
            foreach($order_info as $v)
            {
            	$seller_info  = $model_member->get($v['seller_id']);

            	$mail = get_mail('toseller_online_pay_success_notify', array('order' => $v));
            	$this->_mailto($seller_info['email'], addslashes($mail['subject']), addslashes($mail['message']));
            }
            

            /* 同步发送 */
            $this->_sendmail(true);
        }
    }

    /**
     *    改变订单状态
     *
     *    @author    Garbin
     *    @param     int $order_id
     *    @param     string $order_type
     *    @param     array  $notify_result
     *    @return    void
     */
    function _change_order_status($order_id, $order_type, $notify_result)
    {
        /* 将验证结果传递给订单类型处理 */
        $order_type  =& ot($order_type);
        $order_type->respond_notify($order_id, $notify_result);    //响应通知
    }
    
	function logResult($word='') {
		$fp = fopen("/data/web/s.hnsjb.cn/alipay.log","a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
}

?>
