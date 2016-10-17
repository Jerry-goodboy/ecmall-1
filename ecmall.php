<?php
ini_set("display_errors", 1);
require_once "jssdk.php";
require_once "mobile/includes/cls_mysql.php";
$jssdk = new JSSDK("wxa874a4ea498e6887", "9b1613d944698035b5ff8d022cd75f66");
$signPackage = $jssdk->GetSignPackage();
$db = new cls_mysql("localhost", "root", "root", "ecmall");
$order_id = $_GET['order_id'];
$code = $_GET["code"];
$order_id = json_decode($order_id);
$appid = "wxa874a4ea498e6887";
$appsecret = "9b1613d944698035b5ff8d022cd75f66";
foreach ($order_id as $v) {
    
    $sql = "select * from ecm_order where order_id = '$v'";
    $row = $db->query($sql);
    while ($t_result = mysql_fetch_array($row)) {
        $order_info[] = $t_result;
    }
}
foreach ($order_info as $v) {
    if ($v["status"] == 20) {
        $order_status = 1; // 支付成功
        header("Location: http://s.hnsjb.cn/index.php?app=buyer_order");
        exit();
    } elseif ($v["status"] == 11) {
        $order_status = 2; // 待付款
    } elseif ($v["status"] == 40) {
        $order_status = 3; // 交易完成
        header("Location: http://s.hnsjb.cn/index.php?app=buyer_order");
        exit();
    } elseif ($v["status"] == 0) {
        $order_status = 0; // 交易取消
        header("Location: http://s.hnsjb.cn/index.php?app=buyer_order");
        exit();
    }
}
$params["out_trade_no"] = $order_info[0]["out_trade_sn"];
$params["body"] = "绿公社订单：" . $order_info[0]["out_trade_sn"];
$order_amount = 0;
foreach ($order_info as $v) {
    $order_amount = $order_amount + $v["order_amount"];
}
$params["total_fee"] = intval($order_amount * 100);
$params["notify_url"] = "http://s.hnsjb.cn/wechat_notify/wechat_notify.php";
$params['trade_type'] = "JSAPI";
$params['appid'] = "wxa874a4ea498e6887";
$params['mch_id'] = "1234234102";
$params['spbill_create_ip'] = real_ip();
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$noncestr = "";
for ($i = 0; $i < 16; $i ++) {
    $noncestr .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
}
$params['nonce_str'] = $noncestr;
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
if (! $openid) {
    $order_id = json_encode($order_id);
    $callBackUrl = "http://s.hnsjb.cn/ecmall.php";
    $url = urlencode($callBackUrl);
    $result = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$url?order_id=$order_id&response_type=code&scope=snsapi_base#wechat_redirect";
    header("Location: " . $result);
    exit();
}
$params['openid'] = $openid;
$params["sign"] = getSign($params);
$xml = arrayToXml($params);
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
$jsApiObj["nonceStr"] = createNoncestr();
$jsApiObj["package"] = "prepay_id=$prepay_id";
$jsApiObj["signType"] = "MD5";
$jsApiObj["paySign"] = getSign($jsApiObj);
$jsonData = json_encode($jsApiObj);

function getSign($Obj) // 获取签名
{
    foreach ($Obj as $k => $v) {
        $Parameters[$k] = $v;
    }
    // 签名步骤一：按字典序排序参数
    ksort($Parameters);
    $String = formatBizQueryParaMap($Parameters, false);
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

function createNoncestr($length = 32)
{
    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i ++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
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

function createXml()
{
    return arrayToXml($params);
}

function getPrepayId()
{
    postXml();
    $result = xmlToArray($response);
    $prepay_id = $result["prepay_id"];
    return $prepay_id;
}

function postXml()
{
    $xml = createXml();
    $response = postXmlCurl($xml, $url, $curl_timeout);
    return $response;
}

function postXmlCurl($xml, $url, $second = 30)
{
    // 初始化curl
    $ch = curl_init();
    // 设置超时
    curl_setopt($ch, CURLOP_TIMEOUT, $second);
    // 这里设置代理，如果有的话
    // curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
    // curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    // 设置header
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    // 要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    // post提交方式
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    // 运行curl
    $data = curl_exec($ch);
    curl_close($ch);
    // 返回结果
    if ($data) {
        curl_close($ch);
        return $data;
    } else {
        $error = curl_errno($ch);
        echo "curl出错，错误码:$error" . "<br>";
        echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
        curl_close($ch);
        return false;
    }
}

/**
 * 获得用户的真实IP地址
 *
 * @return string
 */
function real_ip()
{
    static $realip = NULL;
    
    if ($realip !== NULL) {
        return $realip;
    }
    
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            
            /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
            foreach ($arr as $ip) {
                $ip = trim($ip);
                
                if ($ip != 'unknown') {
                    $realip = $ip;
                    
                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $realip = $_SERVER['REMOTE_ADDR'];
            } else {
                $realip = '0.0.0.0';
            }
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $realip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $realip = getenv('HTTP_CLIENT_IP');
        } else {
            $realip = getenv('REMOTE_ADDR');
        }
    }
    
    preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
    $realip = ! empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
    
    return $realip;
}

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>微信安全支付</title>
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<LINK rel=apple-touch-icon-precomposed href="mobile/themes/default/images/touch-icon.png">
<LINK rel="shortcut icon" type=image/x-icon href="mobile/themes/default/images/favicon2.ico">
<LINK rel=stylesheet type=text/css href="mobile/themes/default/css/com_cart.css">
<LINK rel=stylesheet type=text/css href="mobile/themes/default/css/com_style.css">
<link href="mobile/themes/default/css/c_lvgs.css" rel="stylesheet" type="text/css" />
<link href="mobile/themes/default/css/c_lvgs_lx.css" rel="stylesheet" type="text/css" />
<script src="mobile/jquery-1.4.4.min.js"></script>
<script type="text/javascript">
  function jsApiCall()
        {
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                <?php echo $jsonData; ?>, //此处是json数据
                function(res){
                     if(res.err_msg == "get_brand_wcpay_request:ok")
                     {			
						  window.location.href="http://s.hnsjb.cn/index.php?app=buyer_order";
					 }
					 else if(res.err_msg == "get_brand_wcpay_request:fail")
					{
						 window.location.href="http://s.hnsjb.cn/index.php?app=buyer_order";
					 }
					 else if(res.err_msg == "get_brand_wcpay_request:cancel")
						 {
						 	window.location.href="http://s.hnsjb.cn/index.php?app=buyer_order";
						 }
                    //alert(res.err_msg + ",ok");
                }
            );
        }
 
        function callpay()
        {
             
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
                }
            }else{
                jsApiCall();
            }
        }
</script>
</head>
<body <?php if($order_status == 2){echo "onload='callpay()'";}?>}>
	<div align="center"></div>
</body>
</html>