
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $this->_var['page_seo']; ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />


<link href="<?php echo $this->res_base . "/" . 'css/common.css'; ?>" type="text/css" rel="stylesheet" />
<link href="<?php echo $this->res_base . "/" . 'css/sp_cart.css'; ?>" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="newstatic/css/basic.css" />
<link rel="stylesheet" type="text/css" href="newstatic/css/style.css" />
<script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/jquery-1.8.0.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'cart.js'; ?>" charset="utf-8"></script>
</head>
<body>
	<header class="list-header">
			<a href="<?php echo url('app=default'); ?>" class="back-btn"></a>
			购物车
			<?php echo $this->fetch('header.search.html'); ?>
			<a href="javascript:void(0)" class="menu-btn"></a>
		</header>
		<div class="cart-area">
			<div class="empty-cart">
				<p>您的购物车空空如也，快去装满它</p>
				<a href="<?php echo url('app=category'); ?>" class="empty-cart-link">去购物</a>
			</div>
		</div>
		<div class="mall-goods-area">
			<div class="mall-goods-list-area">
				<h2 class="mall-goods-list-title"><span>您可能感兴趣的商品</span></h2>
				<ul class="mall-goods-list" id="bestGoods">
					
				</ul>
			</div>
		</div>
    	<script type="text/javascript">
			$(function() {
				$.getJSON("/index.php?app=get_recommend&act=main&recommend_id=13&number=6").done(function(data) {
					$.each(data, function(i) {
						var item = parseItem(data[i]);
						$('#bestGoods').append(item);
					});
					if ($('body').height() - window.innerHeight < 0) {
						$('footer').css({'position':'fixed'});
					} else {
						$('footer').css({'position':'relative'});
					}
				});
			})
		</script>
    
    
    <?php echo $this->fetch('footer.html'); ?>
    
</body>
</html>

    