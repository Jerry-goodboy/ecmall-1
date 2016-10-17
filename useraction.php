<?php
/**
 * ecmall用户行为记录，存放到redis里
 */
error_reporting(0);

class useraction
{
    // 安全过滤
    function safe_replace($string)
    {
        $string = str_replace("SELECT", "", $string);
        $string = str_replace("JOIN", "", $string);
        $string = str_replace("UNION", "", $string);
        $string = str_replace("WHERE", "", $string);
        $string = str_replace("INSERT", "", $string);
        $string = str_replace("DELETE", "", $string);
        $string = str_replace("UPDATE", "", $string);
        $string = str_replace("LIKE", "", $string);
        $string = str_replace("DROP", "", $string);
        $string = str_replace("CREATE", "", $string);
        $string = str_replace("MODIFY", "", $string);
        $string = str_replace("RENAME", "", $string);
        $string = str_replace("IFRAME", "", $string);
        $string = str_replace("SCRIPT", "", $string);
        $string = str_replace("ALTER", "", $string);
        $string = str_replace("CAS", "", $string);
        $string = str_replace('%20', '', $string);
        $string = str_replace('%27', '', $string);
        $string = str_replace('%2527', '', $string);
        $string = str_replace('*', '', $string);
        $string = str_replace('"', '&quot;', $string);
        $string = str_replace("'", '', $string);
        $string = str_replace('"', '', $string);
        $string = str_replace(';', '', $string);
        $string = str_replace('<', '&lt;', $string);
        $string = str_replace('>', '&gt;', $string);
        $string = str_replace("{", '', $string);
        $string = str_replace('}', '', $string);
        $string = str_replace('\\', '', $string);
        // $string = htmlspecialchars(strtolower($string));
        // $string = str_replace( '/', "", $string);
        $string = str_replace("&gt", "", $string);
        $string = str_replace("&lt", "", $string);
        $string = str_replace("<SCRIPT>", "", $string);
        $string = str_replace("</SCRIPT>", "", $string);
        $string = str_replace("<script>", "", $string);
        $string = str_replace("</script>", "", $string);
        $string = str_replace("select", "", $string);
        $string = str_replace("join", "", $string);
        $string = str_replace("union", "", $string);
        $string = str_replace("where", "", $string);
        $string = str_replace("insert", "", $string);
        $string = str_replace("delete", "", $string);
        $string = str_replace("update", "", $string);
        $string = str_replace("like", "", $string);
        $string = str_replace("drop", "", $string);
        $string = str_replace("create", "", $string);
        $string = str_replace("modify", "", $string);
        $string = str_replace("rename", "", $string);
        $string = str_replace("iframe", "", $string);
        $string = str_replace("script", "", $string);
        $string = str_replace("alter", "", $string);
        $string = str_replace("cas", "", $string);
        $string = str_replace("&", "", $string);
        $string = str_replace(" ", chr(32), $string);
        $string = str_replace(" ", chr(9), $string);
        $string = str_replace("    ", chr(9), $string);
        $string = str_replace("&", chr(34), $string);
        $string = str_replace("'", chr(39), $string);
        $string = str_replace("<br />", chr(13), $string);
        $string = str_replace("''", "", $string);
        $string = str_replace("css", "", $string);
        $string = str_replace("CSS", "", $string);
        $string = str_replace("&&", "", $string);
        $string = str_replace("||", "", $string);
        $string = str_replace("!", "", $string);
        $string = str_replace("=", "", $string);
        $string = str_replace("and", "", $string);
        $string = str_replace("or", "", $string);
        return $string;
    }
    // 获取IP
    function get_iP()
    {
        global $ip;
        if (getenv("HTTP_CLIENT_IP"))
            $ip = getenv("HTTP_CLIENT_IP");
        elseif (getenv("HTTP_X_FORWARDED_FOR"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        elseif (getenv("REMOTE_ADDR"))
            $ip = getenv("REMOTE_ADDR");
        else
            $ip = "Unknow";
        return $ip;
    }

    /**
     * 字符串加密、解密函数
     *
     *
     * @param string $txt            
     * @param string $operation            
     * @param string $key            
     * @param string $expiry            
     * @return string
     */
    function sys_auth($string, $operation = 'ENCODE', $key = '', $expiry = 0)
    {
        $key_length = 4;
        $key = md5($key != '' ? $key : 'iABbnDY7lElXZehVaFaQwErTyGfSfAzSxDcFvBgNhMjKl1357908642');
        $fixedkey = md5($key);
        $egiskeys = md5(substr($fixedkey, 16, 16));
        $runtokey = $key_length ? ($operation == 'ENCODE' ? substr(md5(microtime(true)), - $key_length) : substr($string, 0, $key_length)) : '';
        $keys = md5(substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
        $string = $operation == 'ENCODE' ? sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $egiskeys), 0, 16) . $string : base64_decode(strtr(substr($string, $key_length), '-_', '+/'));
        
        if ($operation == 'ENCODE') {
            $string .= substr(md5(microtime(true)), - 4);
        }
        
        if (function_exists('mcrypt_encrypt') == true) {
            $result = $this->sys_auth_ex($string, $operation, $fixedkey);
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
    // 主方法
    function index()
    {
        foreach ($_POST['params'] as $k => $v) {
            $v = $this->safe_replace($v);
        }
        $rDb = new Redis();
        $rDb->connect('192.168.3.91', '6379');
        $rDb->auth('Hnsjb&hnsjb&redis&91');
        // post处理
        $_POST['params']['uv'] = $_COOKIE['xinjie_uv'];
        $_POST['params']['ip'] = $this->get_iP();
        $_POST['params']['time'] = time();
        $_POST['params']['platform'] = strtolower($_POST['params']['platform']);
        if (($_POST['params']['indevice'] == 'true') && (strstr($_POST['params']['platform'], 'linux'))) {
            $_POST['params']['android'] = '1';
        } else {
            $_POST['params']['indevice'] = 'false';
            $_POST['params']['android'] = '0';
        }
        $_POST['params']['userid'] = $_COOKIE['UgYAk__userid'];
        if (! $_POST['params']['userid']) {
            $_POST['params']['userid'] = 0;
        } else {
            $_POST['params']['userid'] = $this->sys_auth($_POST['params']['userid'], 'DECODE');
        }
        if (! $_POST['params']['visitor']) {
            $_POST['params']['visitor'] = $_COOKIE['PHPSESSID'];
        }
        $params = serialize($_POST['params']);
        $result = $rDb->rPush("jl_user", $params);
        echo '1';
    }
}
$user = new useraction();
$user->index();
?>