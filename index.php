<?php
// ini_set("display_errors", 1);
define('ROOT_PATH', dirname(__FILE__));

/**
 * 安装判断
 */
if (! file_exists(ROOT_PATH . "/data/install.lock") && is_dir(ROOT_PATH . "/install")) {
    @header("location: install");
    exit();
}

include (ROOT_PATH . '/eccore/ecmall.php');

/* 定义配置信息 */
ecm_define(ROOT_PATH . '/data/config.inc.php');

$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
$uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile|android)/i";

// if((preg_match($uachar, $ua)))
// {
// define('ECMALL_WAP', 1);
// }
// if ($_GET['Debug'] == 'Wap') {
// define('ECMALL_WAP', 1);
// }
define("ECMALL_WAP", 1);
$GLOBALS["member"] = 0;
$GLOBALS["apply"] = 0;
// $GLOBALS["apply"] = 0;
// if(is_numeric(sys_auth($_COOKIE["UgYAk__ssouid"], "DECODE", 'iABbnDY7lElXZehVaFaQwErTyGfSfAzSxDcFvBgNhMjKl1357908642')) && !isset($_COOKIE["user_info"])){
// $rrr = is_numeric(sys_auth($_COOKIE["UgYAk__ssouid"], "DECODE", 'iABbnDY7lElXZehVaFaQwErTyGfSfAzSxDcFvBgNhMjKl1357908642'));
$con = mysql_connect("localhost", "root", "root");
@mysql_select_db("ecmall", $con) or die("数据库不存在！" . mysql_error());
mysql_query("SET NAMES UTF8", $con);
$encrypt_sso_id = $_COOKIE["UgYAk__ssouid"];
$sso_id = sys_auth($encrypt_sso_id, "DECODE", 'iABbnDY7lElXZehVaFaQwErTyGfSfAzSxDcFvBgNhMjKl1357908642');
// 卖家一： 13526691299
// $sso_id = 6937;
// 卖家二：18530971948
// $sso_id = 3863;
// 买家： 13937098833
$sso_id = 7009;
$sql = "SELECT user_id, user_name, reg_time, last_login, last_ip, store_id, sgrade,member.user_id FROM ecm_member member LEFT JOIN ecm_store s ON member.user_id=s.store_id WHERE phpsso_uid = '$sso_id'";
$row = mysql_query($sql);
while ($t_result = mysql_fetch_assoc($row)) {
    $user = $t_result;
}
$user_info = $user;
$user_id = $user_info["user_id"];
setcookie("user_info", serialize($user_info), time() + 3600 * 24 * 365);
$gm_time = time() - date('Z');
$last_ip = real_ip();
$update_login_sql = "update ecm_member set last_login = '$gm_time', last_ip = '$last_ip', logins = logins+1 where user_id = '$user_id'";
mysql_query($update_login_sql);
// $this->visitor->has_login = true;
// }
// define('ECMALL_WAP', 1);
/* 启动ECMall */
ECMall::startup(array(
    'default_app' => 'default',
    'default_act' => 'index',
    'app_root' => ROOT_PATH . '/app',
    'external_libs' => array(
        ROOT_PATH . '/includes/global.lib.php',
        ROOT_PATH . '/includes/libraries/time.lib.php',
        ROOT_PATH . '/includes/ecapp.base.php',
        ROOT_PATH . '/includes/plugin.base.php',
        ROOT_PATH . '/app/frontend.base.php',
        ROOT_PATH . '/includes/subdomain.inc.php'
    )
));

function sys_auth($string, $operation = 'ENCODE', $key = '', $expiry = 0)
{
    $key_length = 4;
    $key = md5($key != '' ? $key : pc_base::load_config('system', 'auth_key'));
    $fixedkey = md5($key);
    $egiskeys = md5(substr($fixedkey, 16, 16));
    $runtokey = $key_length ? ($operation == 'ENCODE' ? substr(md5(microtime(true)), - $key_length) : substr($string, 0, $key_length)) : '';
    $keys = md5(substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
    $string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $egiskeys), 0, 16) . $string : base64_decode(strtr(substr($string, $key_length), '-_', '+/'));
    if ($operation == 'ENCODE') {
        $string .= substr(md5(microtime(true)), - 4);
    }
    if (function_exists('mcrypt_encrypt') == true) {
        $result = sys_auth_ex($string, $operation, $fixedkey);
    } else {
        $i = 0;
        $result = '';
        $string_length = strlen($string);
        for ($i = 0; $i < $string_length; $i ++) {
            $result .= chr(ord($string{$i}) ^ ord($keys{$i % 32}));
        }
    }
    if ($operation == 'DECODE') {
        $result = substr($result, 0, - 4);
    }
    
    if ($operation == 'ENCODE') {
        return $runtokey . rtrim(strtr(base64_encode($result), '+/', '-_'), '=');
    } else {
        if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $egiskeys), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    }
}

/**
 * 字符串加密、解密扩展函数
 *
 *
 * @param string $txt            
 * @param string $operation            
 * @param string $key            
 * @return string
 *
 */
function sys_auth_ex($string, $operation = 'ENCODE', $key)
{
    $encrypted_data = "";
    $td = mcrypt_module_open('rijndael-256', '', 'ecb', '');
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    $key = substr($key, 0, mcrypt_enc_get_key_size($td));
    mcrypt_generic_init($td, $key, $iv);
    if ($operation == 'ENCODE') {
        $encrypted_data = mcrypt_generic($td, $string);
    } else {
        if (! empty($string)) {
            $encrypted_data = rtrim(mdecrypt_generic($td, $string));
        }
    }
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    return $encrypted_data;
}

?>
