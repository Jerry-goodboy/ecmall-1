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
		
		<div class="null" style="display:none;">
			<p><img src="<?php echo $this->res_base . "/" . 'images/cart_null.png'; ?>" /></p>
			<p>你的购物车是空的<br />现在就去购物吧~</p>
			<p><a href="#" class="white_btn">去购物</a></p>
		</div>
		<div class="cart-area">
			<?php $_from = $this->_var['carts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('store_id', 'cart');if (count($_from)):
    foreach ($_from AS $this->_var['store_id'] => $this->_var['cart']):
?>
			<section class="cart-item">
				<div class="cart-shop-name">
					<input type="checkbox" class="cart-checkbox" name="store_id" value="<?php echo $this->_var['store_id']; ?>" /> <?php echo htmlspecialchars($this->_var['cart']['store_name']); ?>
					<a href="<?php echo url('app=store&id=' . $this->_var['store_id']. ''); ?>" class="cart-shop-link">
						进入店铺
					</a>
				</div>
				<ul class="cart-goods-list">
					<?php $_from = $this->_var['cart']['goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
					<li id="cart_item_<?php echo $this->_var['goods']['rec_id']; ?>" class="cart-goods-item">
						<input type="checkbox" class="goods-checkbox" <?php if ($this->_var['goods']['if_checked']): ?>checked="true"<?php endif; ?> name="goods_<?php echo $this->_var['goods']['store_id']; ?>" value="<?php echo $this->_var['goods']['spec_id']; ?>" />
						<div class="cart-goods-info">
							<a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>"><img src="<?php echo $this->_var['goods']['goods_image']; ?>" /></a>
							<div class="cart-goods-detail">
								<h3 class="cart-goods-name"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></h3>
								<p class="cart-goods-type"><?php echo htmlspecialchars($this->_var['goods']['specification']); ?></p>
								<span class="goods-info-num-change">
									<button class="goods-info-num-btn num-minus" onclick="decrease_quantity(<?php echo $this->_var['goods']['rec_id']; ?>);changeTotal()">-</button>
									<input type="number" pattern="[0-9]*" id="input_item_<?php echo $this->_var['goods']['rec_id']; ?>" value="<?php echo $this->_var['goods']['quantity']; ?>" orig="<?php echo $this->_var['goods']['quantity']; ?>" class="goods-info-num-input" onkeyup="change_quantity(<?php echo $this->_var['store_id']; ?>, <?php echo $this->_var['goods']['rec_id']; ?>, <?php echo $this->_var['goods']['spec_id']; ?>, this);"  changed="<?php echo $this->_var['goods']['quantity']; ?>" />
									<button class="goods-info-num-btn num-plus" onclick="add_quantity(<?php echo $this->_var['goods']['rec_id']; ?>);changeTotal()">+</button>
								</span>
								<?php if ($this->_var['goods']['if_credit'] == 1): ?><p class="cart-goods-price">积分<?php echo $this->_var['goods']['credit']; ?></p><?php else: ?><p class="cart-goods-price">&yen; <?php echo $this->_var['goods']['price']; ?></p><?php endif; ?>
							</div>
						</div>
						<div class="cart-goods-del" onclick="drop_cart_item(<?php echo $this->_var['store_id']; ?>, <?php echo $this->_var['goods']['rec_id']; ?>);">删除</div>
					</li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
			</section>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</div>
		<div class="cart-sum-submit">
			<span class="cart-sum">合计：<span class="cart-price cart-money" id='cart_amount' <?php if ($this->_var['amount'] == 0): ?> style="display: none;" <?php endif; ?>>&yen; <?php echo $this->_var['amount']; ?></span><?php if ($this->_var['credit'] != 0 && $this->_var['amount'] != 0): ?>,<?php endif; ?><span class="cart-price cart-credit" id="cart_credit" <?php if ($this->_var['credit'] == 0): ?> style="display: none;"<?php endif; ?>><?php echo $this->_var['credit']; ?></span>(不含运费)</span>
			<a href="<?php echo url('app=order&goods=cart'); ?>" class="cart-submit">结算(<span id="cart-number"></span>)</a>
		</div>
		<div class="slide-menu">
	<a href="http://www.hnsjb.cn/main" class="slide-link home-link"><img src="newstatic/img/side-xj.png" alt="新界" class="xinjie-logo" /></a>
	<a href="http://127.0.0.1/ecmalltest" class="slide-link mall-link">商城首页</a>
	<a href="<?php echo url('app=category'); ?>" class="slide-link cate-link">分类</a>
	<a href="<?php echo url('app=cart'); ?>" class="slide-link cart-link">购物车</a>
	<a href="index.php?app=buyer_order&act=index" class="slide-link order-link">订单</a>
	<a href="http://www.hnsjb.cn/usercenter/menu" class="slide-link fav-link">收藏夹</a>
	<a href="<?php echo url('app=my_address'); ?>" class="slide-link addr-link">收货地址</a>
</div>
<div class="opacity-backdrop"></div>

    <script type="text/javascript">
    	function mysetCookie(c_name, value, expiredays) {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + expiredays);
	document.cookie = c_name + "=" + escape(value) + ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString()) + ";path=/";
};

function mygetCookie(c_name) {
	if (document.cookie.length > 0) {
		c_start = document.cookie.indexOf(c_name + "=");
		if (c_start != -1) {
			c_start = c_start + c_name.length + 1;
			c_end = document.cookie.indexOf(";", c_start);
			if (c_end == -1) c_end = document.cookie.length;
			return unescape(document.cookie.substring(c_start, c_end));
		}
	}
	return "";
};

function myclearCookie(c_name) {
	mysetCookie(c_name, "", -1);
}




			$(function() {
				
				
				
				var ua = navigator.userAgent.toLowerCase();
				if (mygetCookie('user_info') !='') {
					$('#unlogged').hide();
					$('#logged').show()
				}
				$('.back-to-top').click(function() {
					$("html,body").animate({scrollTop: 0}, 1000);
				})
				$('.menu-btn').click(function() {
					$('.slide-menu').slideDown();
					$('.opacity-backdrop').show();
				})
				$('.opacity-backdrop').click(function() {
					$('.slide-menu').fadeOut();
					$('.opacity-backdrop').hide();
				})
				var params = {};
				if (document) {
					params.domain = document.domain || '';
					params.url = document.URL || '';
					params.title = document.title || '';
				};
				//Window对象数据
				if (window && window.screen) {
					params.sh = window.screen.height || 0;
					params.sw = window.screen.width || 0;
					params.cd = window.screen.colorDepth || 0;
				};
				//navigator对象数据
				if (navigator) {
					params.lang = navigator.language || '';
					params.appVersion = navigator.appVersion || '';
					params.platform = navigator.platform || '';
				};
				if (ua.match(/micromessenger/) == "micromessenger") {
					params.wechat = 1;
				} else {
					params.wechat = 0;
				}
				params.referrer = mygetCookie('referer') || document.referrer || '';
			
				$.post('useraction.php', {
					params: params
				});
				
				
				$('input[name="store_id"]').change(function() {
					var tempid = $(this).val();
					var tempboxes = 'goods_' + tempid;
					var ids = [];
					var statuses = [];
					var list = $('input[name="' + tempboxes + '"]');
					
					if ($(this).prop('checked')) {
						for (var i= 0;i<list.length;i++) {
							ids.push($(list[i]).val());
							statuses.push(1);
						}
						$('input[name="' + tempboxes + '"]').prop('checked',true);
						checkStore(ids,statuses);
					} else {
						for (var i= 0;i<list.length;i++) {
							ids.push($(list[i]).val());
							statuses.push(0);
						}
						$('input[name="' + tempboxes + '"]').prop('checked',false);
						checkStore(ids,statuses);
					}
					var checkedgoods = $('input.goods-checkbox:checked').parent().find('input[type="number"]');
						var totalamount = 0;
						for (var i= 0; i<checkedgoods.length;i++) {
							totalamount += parseInt($(checkedgoods[i]).val());
						}
						$('#cart-number').text(totalamount);
					
				})
				$('.cart-goods-item input[type="checkbox"]').change(function() {
					if ($(this).prop('checked')) {
						var temparray = [$(this).val()];
						var tempstatus = [1];
						checkGoods(temparray,tempstatus);
						if ($(this).parent().parent().find('.goods-checkbox').length == $(this).parent().parent().find('.goods-checkbox:checked').length) {
							$(this).parent().parent().parent().find('.cart-checkbox').attr('checked',true);
						} else {
							$(this).parent().parent().parent().find('.cart-checkbox').attr('checked',false);
						}
					} else {
						var temparray = [$(this).val()];
						var tempstatus = [0];
						checkGoods(temparray,tempstatus);
						$(this).parent().parent().parent().find('.cart-checkbox').attr('checked',false);
					}
					var checkedgoods = $('input.goods-checkbox:checked').parent().find('input[type="number"]');
					var totalamount = 0;
					for (var i= 0; i<checkedgoods.length;i++) {
						totalamount += parseInt($(checkedgoods[i]).val());
					}
					$('#cart-number').text(totalamount);
						
				})
				var stores = $('.cart-checkbox');
				for (var i=0;i<stores.length;i++) {
					if ($(stores[i]).parent().parent().find('.goods-checkbox').length ==$(stores[i]).parent().parent().find('.goods-checkbox:checked').length) {
						$(stores[i]).attr('checked',true);
					} else {
						$(stores[i]).attr('checked',false);
					}
				}
			
			})
			var checkedgoods = $('input.goods-checkbox:checked').parent().find('input[type="number"]');
			var totalamount = 0;
			for (var i= 0; i<checkedgoods.length;i++) {
				totalamount += parseInt($(checkedgoods[i]).val());
			}
			$('#cart-number').text(totalamount);
			function checkStore(ids,status) {
				$.post('http://127.0.0.1/ecmalltest/index.php?app=cart&act=if_checked',{spec_id:ids,status:status},function(res) {
					res = JSON.parse(res);
					$('#cart_amount').html(price_format(res.price));
					$('#cart_credit').html(res.credit);
				})
			}
			function checkGoods(ids,status) {
				$.post('http://127.0.0.1/ecmalltest/index.php?app=cart&act=if_checked',{spec_id:ids,status:status},function(res) {
					res = JSON.parse(res);
					$('#cart_amount').html(price_format(res.price));
					$('#cart_credit').html(res.credit);
				})
			}
			function changeTotal() {
				var checkedgoods = $('input.goods-checkbox:checked').parent().find('input[type="number"]');
				var totalamount = 0;
				for (var i= 0; i<checkedgoods.length;i++) {
					totalamount += parseInt($(checkedgoods[i]).val());
				}
				$('#cart-number').text(totalamount);
			}
		</script>
		<script>
		var _hmt = _hmt || [];
		(function() {
		  var hm = document.createElement("script");
		  hm.src = "//hm.baidu.com/hm.js?428818587a22359510fbcc475869ae96";
		  var s = document.getElementsByTagName("script")[0]; 
		  s.parentNode.insertBefore(hm, s);
		})();
		</script>
	</body>
</html>