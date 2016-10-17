<?php
ini_set("display_errors", 1);
class WechatmodifyorderApp extends StoreadminbaseApp
{
	function index()
	{
		$order_id = $_POST['order_id'];
		$order_model =& m('order');
		//var_dump($order_model);
		$sql = "update ecm_order set status = 0 where order_id = '$order_id'";
		$order_model->edit(intval($order_id), array('status' => 0));
	}
	
	function queryorder()
	{
		$order_id = $_POST['order_id'];
		$order_model = & m('order');
		$order_info  = $order_model->get("order_id={$order_id} AND buyer_id=" . $this->visitor->get('user_id'));
		echo $order_info['status'];
	}
}