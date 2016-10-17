<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" /> <?php echo $this->_var['page_seo']; ?>
		<link href="<?php echo $this->res_base . "/" . 'css/common.css'; ?>" type="text/css" rel="stylesheet" />
		<link href="<?php echo $this->res_base . "/" . 'css/comment.css'; ?>" type="text/css" rel="stylesheet" />
		<link href="newstatic/css/basic.css" type="text/css" rel="stylesheet" />
        <link href="newstatic/css/style.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="index.php?act=jslang"></script>
		<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.js'; ?>" charset="utf-8"></script>
		<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
		<script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/nav.js'; ?>" charset="utf-8"></script>
		<script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/jquery-1.8.0.min.js'; ?>" charset="utf-8"></script>
		<script type="text/javascript">
			//<!CDATA[
			var SITE_URL = "<?php echo $this->_var['site_url']; ?>";
			var REAL_SITE_URL = "<?php echo $this->_var['real_site_url']; ?>";
			var PRICE_FORMAT = '<?php echo $this->_var['price_format']; ?>';
			$(function() {
//				var select_list = document.getElementById("select_list");
//				var float_list = document.getElementById("float_list");
//				select_list.onmouseover = function() {
//					float_list.style.display = "block";
//				};
//				select_list.onmouseout = function() {
//					float_list.style.display = "none";
//				};
			});
			//]]>
		</script>

		<?php echo $this->_var['_head_tags']; ?>
		<!--<editmode></editmode>-->
	</head>
	</head>

	<body>
		<header class="list-header">
			<a href="<?php echo url('app=buyer_order&act=index'); ?>" class="back-btn"></a>
			订单提交成功
			<?php echo $this->fetch('header.search.html'); ?>
			<a href="javascript:void(0)" class="menu-btn"></a>
		</header>
		<div class="order-confirm-area">
			<div class="order-infos">
				<form action="index.php?app=cashier&act=goto_pay&out_trade_sn=<?php echo $this->_var['out_trade_sn']; ?>" method="POST" id="goto_pay">
				<input type="hidden" name="payment_id" value="<?php echo $this->_var['payment']['payment_id']; ?>" id="payment_<?php echo $this->_var['payment']['payment_code']; ?>" style="display:none">
				<div class="order-info-item">
					<span class="order-info-name">订单交易号</span>
					<span class="order-info-content"><?php echo $this->_var['out_trade_sn']; ?></span>
				</div>
				
				<div class="order-info-item">
					<span class="order-info-name">配送方式</span>
					<?php $_from = $this->_var['shipping']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('id', 'single_shipping');if (count($_from)):
    foreach ($_from AS $this->_var['id'] => $this->_var['single_shipping']):
?>
					<span class="order-info-content"><?php echo $this->_var['single_shipping']; ?></span>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</div>
				<div class="order-info-item">
					<span class="order-info-name">订单编号</span>
					<?php $_from = $this->_var['order_sn']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('id', 'single_order_sn');if (count($_from)):
    foreach ($_from AS $this->_var['id'] => $this->_var['single_order_sn']):
?>
					<span class="order-info-content"><?php echo $this->_var['single_order_sn']; ?></span>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</div>
				
				<div class="order-info-item">
					<span class="order-info-name">支付方式</span>
					<span class="order-info-content"><?php echo $this->_var['payment']['payment_name']; ?></span>
				</div>
				<div class="order-info-item">
					<span class="order-info-name">应付金额</span>
					<span class="order-info-content order-info-price"><?php echo price_format($this->_var['amount']); ?></span>
				</div>
				<a class="ecmall-btn ecmall-confirm-btn" href="javascript:$('#goto_pay').submit();">确认付款</a>
				</form>
			</div>
		</div>
		<?php echo $this->fetch('member.footer.html'); ?>