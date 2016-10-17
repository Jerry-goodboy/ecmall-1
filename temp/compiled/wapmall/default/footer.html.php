<footer>
	<div class="footer-member" id="unlogged">
		<a href="http://www.hnsjb.cn/usercenter/login">登录</a>
		<a href="http://www.hnsjb.cn/usercenter/register">注册</a>
	</div>
	<div class="footer-member" id="logged" style="display: none;">
		<a href="http://www.hnsjb.cn/usercenter/menu" class="center-link">个人中心</a>
	</div>
	<button class="back-to-top">返回顶部</button>
	
	
</footer>
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
	if ($('body').height() - window.innerHeight < 0) {
		$('footer').css({'position':'fixed'});
	}
	
})
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
