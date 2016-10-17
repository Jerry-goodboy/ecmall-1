<?php
error_reporting(0);

class new_wx_islogin
{

    function index()
    {
        session_start();
        // 获取微信openid
        $appId = "wxa874a4ea498e6887";
        $secret = "9b1613d944698035b5ff8d022cd75f66";
        $code = $_GET['code'];
        $weixin = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appId . "&secret=" . $secret . "&code=" . $code . "&grant_type=authorization_code");
        $jsondecode = json_decode($weixin);
        $array = get_object_vars($jsondecode);
        if ($array['openid']) {
            header("LOCATION:http://www.hnsjb.cn/v2_api.php?op=new_wx_islogin&openid=" . $array['openid']);
        }
    }
}
$wx = new new_wx_islogin();
$wx->index();
?>