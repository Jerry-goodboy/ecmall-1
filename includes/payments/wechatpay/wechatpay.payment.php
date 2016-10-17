<?php
class WechatpayPayment extends BasePayment
{
	function get_payform($order_info)
	{
//		$appid = $this->_config['wechatpay_appid'];
//		$appsecret = $this->_config['wechatpay_appsecret'];
//		$params = array(
//			"out_trade_no" => $this->_get_trade_sn($order_info),
//			"body" => $this->_get_subject($order_info),
//			"total_fee" => $order_info['order_amount'],
//			"notify_url" => $this->_create_notify_url($order_id),
//			"spbill_create_ip" => real_ip(),
//			"nonce_str" => $this->create_noncestr(32),
//		);
//		if(isset($_COOKIE["ECS"]["indevice"]))
//		{
//			$params["trade_type"] = "APP";
//			$params["appid"] = "wx18dbed3a77ba78a2";
//			$params["mch_id"] = "1308942101";
//		}
//		else
//		{
//			$params["trade_type"] = "JSAPI";
//			$params["appid"] = "wxa874a4ea498e6887";
//			$params["mch_id"] = "1234234102";
//		}
//		$jsonParameters = json_encode($params);
		foreach($order_info as $v)
		{
			$order_id[] = $v["order_id"];
		}
		//$order_id = $order_info['order_id'];
		$url = $this->getUser($order_id);
		header("Location: ".$url);
		exit();
	}
	
	function getUser($order_id)
	{
		$order_id = json_encode($order_id);
		$callBackUrl = "http://s.hnsjb.cn/ecmall.php";
		$appid = $this->_config['wechatpay_appid'];
		$appsecret = $this->_config['wechatpay_appsecret'];
		$url = urlencode($callBackUrl);
		$result = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$url?order_id=$order_id&response_type=code&scope=snsapi_base#wechat_redirect";
		return $result;
	}
	
	//生成随机数
	function create_noncestr( $length = 16 ) 
	{  
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";  
		$str ="";  
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
			//$str .= $chars[ mt_rand(0, strlen($chars) - 1) ];  
		}  
		return $str;  
	}
	
	function logResult($word='') {
		$fp = fopen("wechatpay.log","a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	
	function verify_notify($order_info, $strict = false)
    {
    	$this->logResult("test");
    }
}
?>