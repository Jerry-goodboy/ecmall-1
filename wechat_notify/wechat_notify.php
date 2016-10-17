<?php
include_once "log_.php";
include_once "WxPayPubHelper.php";
	
logResult("wechat");
function logResult($word='') {
		$fp = fopen("/data/web/s.hnsjb.cn/wechat_notify/wechat_log.log","a");
		flock($fp, LOCK_EX) ;
		fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}

$notify = new Notify_pub();

$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
$notify->saveData($xml);
if($notify->checkSign() == FALSE){
		$notify->setReturnParameter("return_code","FAIL");//返回状态码
		$notify->setReturnParameter("return_msg","签名失败");//返回信息
	}else{
		$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
	}
	$returnXml = $notify->returnXml();
	echo $returnXml;
	
	$log_ = new Log_();
	$log_name="wechat_log.log";//log文件路径
	$log_->log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");
	
	if($notify->checkSign() == TRUE)
	{
		
		if ($notify->data["return_code"] == "FAIL") {
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
		}
		elseif($notify->data["result_code"] == "FAIL"){
			//此处应该更新一下订单状态，商户自行增删操作
			$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
		}
		else{
			//此处应该更新一下订单状态，商户自行增删操作
			$out_trade_no = $notify->data['out_trade_no'];
			//$log_->log_result($log_name, $out_trade_no);
			$con=mysql_connect("localhost","root","root");
			@mysql_select_db("ecmall",$con) or die("数据库不存在！".mysql_error());
	//var_dump($con);
			mysql_query("SET NAMES UTF8",$con);
			//$sql = "select * from ecm_order where out_trade_sn = $out_trade_no";
			$sql = "update ecm_order set status = 20 where out_trade_sn = $out_trade_no";
			//$log_->log_result($log_name, $sql);
			//$log_->log_result($log_name, $row);
			$row = mysql_query($sql);
//			while($t_result = mysql_fetch_array($row))
//			{
//				$result[] = $t_result;
//			}
			//$log_->log_result($log_name, json_encode($result));
			echo 'success';
			$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
		}
		
		//商户自行增加处理流程,
		//例如：更新订单状态
		//例如：数据库操作
		//例如：推送支付完成信息
	}
//foreach($_GET as $key=>$value)
//{
//	logger("Key:$key; Value:$value");
//}
//$postStr=$GLOBALS["HTTP_RAW_POST_DATA"];
//$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
//$out_trade_no=$postObj->out_trade_no;
//$newstr=substr($out_trade_no,0,13);
//logger($postStr);
//if(isset($_GET)){
//	$sql="update ecs_order_info SET order_status=1, shipping_status=0, pay_status= 2 where order_sn='$newstr'";
//	$result = $db->query($sql);
//	file_put_contents('log.txt',$sql."\r\n", FILE_APPEND);
//	file_put_contents('log.txt',$newstr."\r\n", FILE_APPEND);
//	file_put_contents('log.txt',$result."\r\n\r\n", FILE_APPEND);
//	echo "success";
//}
//function logger($log_content)
//{
//	$max_size =100000;
//	$log_filename="log.xml";
//	file_put_contents($log_filename, date('H:i:s')." ".$log_content."\r\n", FILE_APPEND);
//}
?>