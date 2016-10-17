<?php
/*
 * 客户端被动接收phpsso服务端通知
 * 服务端通知内容:同步登陆、退出，同步积分设置、对换比率，同步添加、删除用户、修改用户密码，测试通信状态
 * 
 */

	ini_set('log_errors',TRUE);
	define('APP_LOG',__DIR__."/error.log");
	ini_set('error_log',APP_LOG);
	ini_set('display_errors',false);
	require_once __DIR__.'/Logger.php';
	
	require_once __DIR__.'/phpsso/system.php';
	require_once __DIR__.'/phpsso/client.class.php';
	require_once __DIR__. '/init.php';
	define('APPID', $system['phpsso_appid']);
	$ps_api_url = $system['phpsso_api_url'];	//接口地址
	$ps_auth_key = $system['phpsso_auth_key'];	//加密密钥
	$ps_version = $system['phpsso_version'];

	$client = new client($ps_api_url, $ps_auth_key);
	if(isset($_GET["check_app"])){
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		$platform = $_GET['platform'];
		setcookie("ECS[indevice]",  $platform,   $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
		$appPath = $_GET['appPath'];
		setcookie("appPath",  $appPath,   $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
		exit();
	}
	$code = $_REQUEST['code'];
	
	parse_str($client->sys_auth($code, 'DECODE'), $arr);
Logger::debug($arr);
	if(isset($arr['action'])) {
		$action = $arr['action'];
	} else {
		exit('0');
	}
	/**
	 * 测试通信状态
	 */
	
	if ($action == 'check_status') exit('1');
	
	/*
	 *  设置cookie
	 */
	function set_session ($user_id = '', $user_name = '', $email = '')
	{
		if (empty($user_id))
		{
			$GLOBALS['sess']->destroy_session();
		}
		else
		{
			$_SESSION['user_id']   = $user_id;
			$_SESSION['user_name'] = $user_name;
			$_SESSION['email']     = $email;
		}
	}
	
	function set_cookie($user_id='', $user_name = '', $email = '',$password="")
	{
		
		if (empty($user_id))
		{
			/* 摧毁cookie */
			$time = time() - 3600;
			setcookie('ECS[user_id]',  '', $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
			setcookie('ECS[username]', '', $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
			setcookie('ECS[email]',    '', $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
			setcookie('ECS[password]',    '', $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
		}
		else
		{
			/* 设置cookie */
			$time = time() + 31536000;
			setcookie("ECS[user_id]",  $user_id,   $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
			setcookie("ECS[username]", $user_name, $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
			setcookie("ECS[email]",    $email,     $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
			setcookie("ECS[password]",    $password,     $time, $GLOBALS['cookie_path'], $GLOBALS['cookie_domain']);
		}
	}
	/*
	 *  设置登陆
	 */
	function set_login($user_id = '', $user_name = '')
	{
		
		if (empty($user_id))
		{
			exit("0");
		}
		else
		{
			
			$sql = "SELECT user_id,user_name, email,password FROM " . $GLOBALS['ecs']->table('users') . " WHERE phpssouid='$user_id' LIMIT 1";
			$row = $GLOBALS['db']->getRow($sql);
			if ($row)
			{
				set_cookie($row['user_id'], $row['user_name'], $row['email'],$row['password']);
				set_session($row['user_id'], $row['user_name'], $row['email']);
				include_once(ROOT_PATH . 'includes/lib_main.php');
				update_user_info();
			}
			else exit("0");
		}
	}
	
	/**
	 * 同步登陆
	 */
	if ($action == 'synlogin') {
		$uid = intval($arr['uid']);
		if(!uid) exit(0);
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		set_login($uid);
		exit("1");
	}
	
	/**
	 * 同步退出
	 */
	if ($action == 'synlogout') {
        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        set_cookie();
        set_session();
		exit("1");
		//执行本系统退出操作
	}
	
	if($action == 'member_add')
	{

		$phpssouid = isset($arr['uid']) ? $arr['uid'] : exit('0');
		$ec_salt = isset($arr['random']) ? $arr['random'] : exit('0');
		$user_name = isset($arr['username']) ? $arr['username'] : exit('0');
		$password = isset($arr['password']) ? $arr['password'] : exit('0');
		$email = isset($arr['email']) ? $arr['email'] : '';
		$last_ip = isset($arr['regip']) ? $arr['regip'] : '';
		$reg_time = time();
		$sql = "INSERT ignore INTO " . $GLOBALS['ecs']->table('users').
		" (`phpssouid`,`ec_salt`,`user_name`,`password`,`email`,`last_ip`,`reg_time`)".
		" VALUES ('$phpssouid','$ec_salt','$user_name','$password','$email','$last_ip','$reg_time')";
		$status = $GLOBALS['db']->query($sql);
		if($status)exit("1");
		else exit("0");
	}
	

	
	
	
	
?>
