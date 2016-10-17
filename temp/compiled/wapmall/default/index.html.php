<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" /> <?php echo $this->_var['page_seo']; ?>
		<link href="<?php echo $this->res_base . "/" . 'css/common.css'; ?>" type="text/css" rel="stylesheet" />
		<link href="<?php echo $this->res_base . "/" . 'css/index.css'; ?>" type="text/css" rel="stylesheet" />
		<link href="<?php echo $this->res_base . "/" . 'css/base.css'; ?>" type="text/css" rel="stylesheet" />
		<link href="newstatic/css/basic.css" type="text/css" rel="stylesheet" />
		<link href="newstatic/css/style.css" type="text/css" rel="stylesheet" />
		<script type="text/javascript" src="index.php?act=jslang"></script>
		<script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/jquery-1.8.0.min.js'; ?>"></script>
		<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
		<script type="text/javascript" src="newstatic/js/yxMobileSlider.js" charset="utf-8"></script>
		<?php echo $this->_var['_head_tags']; ?>
	</head>

	<body>
		<header class="logo-header">
			<img src="newstatic/img/xj-logo.png" alt="新界" />
			<?php echo $this->fetch('header.search.html'); ?>
			<a href="javascript:void(0)" class="menu-btn index-menu-btn"></a>
		</header>
		<div class="mall-slider" style="height: 150px;overflow: hidden;" >
			<div class="slider">
				<ul id="ad_49"></ul>
			</div>
		</div>
		<nav class="mall-nav">
			<div class="mall-nav-item">
				<a class="mall-nav-category" href="<?php echo url('app=category'); ?>">
					分类
				</a>
			</div>
			<div class="mall-nav-item">
				<a class="mall-nav-cart" href="<?php echo url('app=cart'); ?>">
					购物车
				</a>
			</div>
			<div class="mall-nav-item">
				<a class="mall-nav-usercenter" href="http://www.hnsjb.cn/usercenter/menu">
					会员中心
				</a>
			</div>
			<div class="mall-nav-item">
				<a class="mall-nav-fav" href="http://www.hnsjb.cn/content/favorite">
					收藏夹
				</a>
			</div>
		</nav>
		<!--<div class="mall-flash-area">
			<h2 class="mall-flash-list-title">
				秒杀特卖
			</h2>
			<ul class="mall-goods-tri-list" id="flashDeal">
				
			</ul>
		</div>-->
		<div class="mall-goods-area">
			<div class="mall-goods-list-area">
				<h2 class="mall-goods-list-title"><span>新品特惠</span></h2>
				<ul class="mall-goods-list" id="newGoods">
					
				</ul>
			</div>
			<div class="mall-goods-list-area">
				<h2 class="mall-goods-list-title"><span>精品推荐</span></h2>
				<ul class="mall-goods-list" id="bestGoods">
				</ul>
			</div>
		</div>
		<?php echo $this->fetch('footer.html'); ?>
		
		<script type="text/javascript">
			$(function() {
				$.getJSON("/ecmalltest/index.php?app=get_recommend&act=main&recommend_id=13&number=4").done(function(data) {
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
				$.getJSON("/ecmalltest/index.php?app=get_recommend&act=main&recommend_id=14&number=4").done(function(data) {
					$.each(data, function(i) {
						var item = parseItem(data[i]);
						$('#newGoods').append(item);
					});
				});
//				$.getJSON("/index.php?app=get_recommend&act=main&recommend_id=15&number=3").done(function(data) {
//					$.each(data, function(i) {
//						var item = parseflashItem(data[i]);
//						$('#flashDeal').append(item);
//					});
//				});
			})
		</script>
		<script src="http://www.hnsjb.cn/static/js/ads/49.js" type="text/javascript" charset="utf-8"></script>
	</body>

</html>