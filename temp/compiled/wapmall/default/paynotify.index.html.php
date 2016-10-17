<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <base href="<?php echo $this->_var['site_url']; ?>/" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7 charset=<?php echo $this->_var['charset']; ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_var['charset']; ?>" />
		<?php echo $this->_var['page_seo']; ?>
        <link href="<?php echo $this->res_base . "/" . 'css/common.css'; ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo $this->res_base . "/" . 'css/bookmark.css'; ?>" type="text/css" rel="stylesheet" />
        <link href="newstatic/css/basic.css" type="text/css" rel="stylesheet" />
        <link href="newstatic/css/style.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.js'; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'member.js'; ?>" charset="utf-8"></script>

    </head>

    <body>
    	
    	<header class="list-header">
			<a href="<?php echo url('app=buyer_order'); ?>" class="back-btn"></a>
			交易成功
			<?php echo $this->fetch('header.search.html'); ?>
			<a href="javascript:void(0)" class="menu-btn"></a>
		</header>
		<div class="order-confirm-area">
			<div class="trade-success">
				交易成功
			</div>
			<div class="success-infos">
				<div class="order-info-item">
					<span class="order-info-name">订单编号</span>
					<span class="order-info-content"><?php echo $this->_var['out_trade_sn']; ?></span>
				</div>
				<div class="order-info-item">
					
					<span class="order-info-name">交易时间</span>
					<span class="order-info-content"><?php echo $this->_var['pay_time']; ?></span>
					
				</div>
				<?php if ($this->_var['amount']): ?>
				<div class="order-info-item">
					<span class="order-info-name">支付方式</span>
					<span class="order-info-content"><?php echo $this->_var['payment']['payment_name']; ?></span>
				</div>
							<?php endif; ?>
			</div>

			<div class="more-link">
				<a href='<?php echo url('app=default'); ?>'>继续逛逛</a>&nbsp;&nbsp;<a href="<?php echo url('app=buyer_order&act=index'); ?>">查看订单</a>
			</div>
			
		</div>
</body>
</html>