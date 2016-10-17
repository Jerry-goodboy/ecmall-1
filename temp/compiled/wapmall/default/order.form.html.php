
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <base href="<?php echo $this->_var['site_url']; ?>/" />
        
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7 charset=<?php echo $this->_var['charset']; ?>" />
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_var['charset']; ?>" />
        <?php echo $this->_var['page_seo']; ?>

        <meta name="author" content="" />
        <meta name="generator" content="" />
        <meta name="copyright" content="" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />

        <script type="text/javascript" src="index.php?act=jslang"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.js'; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/nav.js'; ?>" charset="utf-8"></script>
        <link href="<?php echo $this->res_base . "/" . 'css/order.css'; ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo $this->res_base . "/" . 'css/common.css'; ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo $this->res_base . "/" . 'css/address.css'; ?>" type="text/css" rel="stylesheet" />
        <link href="newstatic/css/basic.css" type="text/css" rel="stylesheet" />
        <link href="newstatic/css/style.css" type="text/css" rel="stylesheet" />
        
        	
        <!--<script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/select.js'; ?>" charset="utf-8"></script>-->
        <script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/jquery-1.8.0.min.js'; ?>" charset="utf-8"></script>

        <script type="text/javascript">
            //<!CDATA[
            var SITE_URL = "<?php echo $this->_var['site_url']; ?>";
            var REAL_SITE_URL = "<?php echo $this->_var['real_site_url']; ?>";
            var PRICE_FORMAT = '<?php echo $this->_var['price_format']; ?>';

            //]]>
        </script>


        <body style="padding-bottom: 100px;">
                
                    <header class="list-header">
						<a href="javascript:history.back(-1);" class="back-btn"></a>
						订单确认
						<?php echo $this->fetch('header.search.html'); ?>
						<a href="javascript:void(0)" class="menu-btn"></a>
					</header>
					<form method="post" id="order_form" onsubmit="return checkForm()">
                    <?php echo $this->fetch('order.shipping.html'); ?>


                    <script type="text/javascript">
                        function postscript_activation(tt) {
                            if (!tt.name)
                            {
                                tt.value = '';
                                tt.name = 'postscript';
                            }

                        }
                    </script>
                    <!--<div class="orderlist">
                        <ul>
                            <li>给卖家的附言</li>
                            <li>  <textarea  class="com_text" id="postscript" placeholder="附注：选填，可以告诉卖家您对商品的特殊需求，如颜色、尺码等" onclick="postscript_activation(this);"></textarea></li>
                        </ul>
                    </div>-->

                         <?php $_from = $this->_var['goods_info']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('store_id', 'items');if (count($_from)):
    foreach ($_from AS $this->_var['store_id'] => $this->_var['items']):
?>
                        <div class="order-goods-item">
							<h3 class="order-goods-shopname">
								<a href="<?php echo url('app=store&id=' . $this->_var['store_id']. ''); ?>" ><?php echo htmlspecialchars($this->_var['store_name'][$this->_var['store_id']]); ?></a>
							</h3>
							<?php $_from = $this->_var['items']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
							<div class="order-goods-container">
								<ul class="order-goods-list">
									<li>
										<a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>" class="order-goods-link">
											<?php if ($this->_var['goods']['goods_image']): ?>
											<img src="<?php echo $this->_var['goods']['goods_image']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>" />
											<?php else: ?>
											<img src="data/system/default_goods_image.gif" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>" />
											<?php endif; ?>
											<div class="order-goods-info">
												<p class="order-goods-name"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></p>
												<p class="order-goods-amount">数量：<span class="amount-caculate"><?php echo $this->_var['goods']['quantity']; ?></span>件</p>
												<?php if ($this->_var['goods']['if_credit'] == 1): ?>
												<p class="order-goods-price"><span class="price-caculate">积分<?php echo $this->_var['goods']['credit']; ?></span></p>
												<?php else: ?>
												<p class="order-goods-price">&yen;<span class="price-caculate"><?php echo $this->_var['goods']['price']; ?></span></p>
												<?php endif; ?>
												<?php if ($this->_var['goods']['if_credit'] != 1): ?>
												<input type="hidden" name="order-goods-subtotal" value="<?php echo $this->_var['goods']['amount']; ?>" />
												<?php endif; ?>
											</div>
										</a>
									</li>
								</ul>
								<div class="order-goods-extra">
									<div class="order-goods-input-item">
										<label for="express">配送方式</label>
										<?php if ($this->_var['goods']['postage_daofu']): ?>
										<?php $_from = $this->_var['shipping_methods'][$this->_var['goods']['spec_id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'shipping_method');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['shipping_method']):
        $this->_foreach['foo']['iteration']++;
?>
										
			                                 <div shipping_id="<?php echo $this->_var['shipping_method']['shipping_id']; ?>" >
			                                <input class="shipping-method" type="radio" name="<?php echo $this->_var['goods']['spec_id']; ?>" id="ship_<?php echo $this->_var['shipping_method']['shipping_id']; ?>_<?php echo $this->_var['goods']['spec_id']; ?>" value="<?php echo $this->_var['store_id']; ?>+<?php echo $this->_var['shipping_method']['shipping_id']; ?>+<?php echo $this->_var['goods']['spec_id']; ?>" <?php if (($this->_foreach['foo']['iteration'] <= 1)): ?>checked="true"<?php endif; ?>/>
			                                <label for="ship_<?php echo $this->_var['shipping_method']['shipping_id']; ?>_<?php echo $this->_var['goods']['spec_id']; ?>" style="position: initial;"><span class="money" ectype="shipping_fee"><?php echo htmlspecialchars($this->_var['shipping_method']['shipping_name']); ?> &yen;<?php echo $this->_var['shipping_method']['shipping_fee']; ?></span></label>
			                            	</div>
	                                 <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	                                 
	                                 <?php else: ?>
	                                 <?php $_from = $this->_var['shipping_methods'][$this->_var['goods']['spec_id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'shipping_method');$this->_foreach['foo'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['foo']['total'] > 0):
    foreach ($_from AS $this->_var['shipping_method']):
        $this->_foreach['foo']['iteration']++;
?>
			                                 <div shipping_id="<?php echo $this->_var['shipping_method']['shipping_id']; ?>" >
			                                <input class="shipping-method shipping-method1" type="radio" name="<?php echo $this->_var['goods']['spec_id']; ?>" data-fee="<?php echo $this->_var['shipping_method']['shipping_fee']; ?>" onclick="changeAmount()" id="ship_<?php echo $this->_var['shipping_method']['shipping_id']; ?>_<?php echo $this->_var['goods']['spec_id']; ?>" value="<?php echo $this->_var['store_id']; ?>+<?php echo $this->_var['shipping_method']['shipping_id']; ?>+<?php echo $this->_var['goods']['spec_id']; ?>" <?php if (($this->_foreach['foo']['iteration'] <= 1)): ?>checked="true"<?php endif; ?>/>
			                                <label for="ship_<?php echo $this->_var['shipping_method']['shipping_id']; ?>_<?php echo $this->_var['goods']['spec_id']; ?>" style="position: initial;"><span class="money" ectype="shipping_fee"><?php echo htmlspecialchars($this->_var['shipping_method']['shipping_name']); ?> &yen;<?php echo $this->_var['shipping_method']['shipping_fee']; ?></span></label>
			                            	</div>
	                                 <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	                                  
	                                 <?php endif; ?>
									</div>
									<?php if ($this->_var['goods']['cod']): ?>
									<div class="order-goods-input-item" style="padding-left: 7em;">
										<label for="payment">是否货到付款</label>
										<input type="radio" name="cod+<?php echo $this->_var['goods']['spec_id']; ?>" class="cod-y" id="cod_<?php echo $this->_var['goods']['spec_id']; ?>_y" value="1"/> <label style="position: relative;top: 0;" for="cod_<?php echo $this->_var['goods']['spec_id']; ?>_y">是</label>
	                                 	<input type="radio" name="cod+<?php echo $this->_var['goods']['spec_id']; ?>" class="cod-n" id="cod_<?php echo $this->_var['goods']['spec_id']; ?>_n" value="0"/> <label style="position: relative;top: 0;" for="cod_<?php echo $this->_var['goods']['spec_id']; ?>_n">否</label>
									</div>
									 <?php endif; ?>
									<?php if ($this->_var['goods']['postage_daofu']): ?>
									<input type="hidden" name="postage_daofu" value="1 " />
									<div class="order-goods-input-item">
										请注意该商品为邮费到付
									</div>
									 <?php endif; ?>
								</div>
							</div>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
						</div>
                        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    <?php if ($this->_var['if_credit']): ?>
                    <!-- <p>是否使用积分消费</p>
                    <input type="radio" name="if_credit" value="1" />是
                    <input type="radio" name="if_credit" value="0" checked/>否 -->
                    <div class="credit-area">
                    	<p>积分抵扣金额为<?php echo $this->_var['credit_max']; ?></p>
	                    <p>消费积分为<?php echo $this->_var['credit_max_consume']; ?></p>
	                    <p>您的可用积分为<?php echo $this->_var['credit_aval']; ?></p>
	                    <input type="hidden" id="userCredit" value="<?php echo $this->_var['credit_aval']; ?>" />
                    </div>
	                    
                    <!-- <p>请输入您想消费的积分数量：<input type="number" name="credit_used"></p> -->
                    <?php else: ?>
                    <input type="hidden" name="if_credit" value="0" checked/>
                    <?php endif; ?>
                    <div class="payment-area">
                    <p>支付方式：</p>
                    <?php $_from = $this->_var['payments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'payment');if (count($_from)):
    foreach ($_from AS $this->_var['payment']):
?>
                    <div class="payment-select">
	                    <input type="radio" name="payment" id="pay_<?php echo $this->_var['payment']['payment_code']; ?>" value="<?php echo $this->_var['payment']['payment_code']; ?>" />
	                    <label for="pay_<?php echo $this->_var['payment']['payment_code']; ?>"><?php echo $this->_var['payment']['payment_name']; ?></label>
                    </div>
	                    
                    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
                    </div>
                    <div class="cart-sum-submit">
						<span class="cart-sum">合计：<span class="cart-price"><strong id="order_amount"><?php echo $this->_var['real_total']; ?></strong></span></span>
						<a href="javascript:void($('#order_form').submit());" class="cart-submit">提交订单</a>
					</div>
                </form >
            </div>
            <script type="text/javascript">
            var re = /^1[3|4|5|7|8][0-9]\d{4,8}$/; //手机正则
            	function checkForm() {
            		if ($('#use_new_address').attr('checked')) {
            			if ($('#consignee').val() == '') {
            				alert('请填写收货人姓名');
            				return false;
            			} else if ($('#phone_tel').val() == '') {
            				alert('请填写收货人手机');
            				return false;
            			} else if (!re.test($('#phone_tel').val())) {
            				alert('请正确的手机号');
            				return false;
            			} else if ($('#region_id').val() == '') {
            				alert('请选择所在地区');
            				return false;
            			}  else if ($('#address').val() == '') {
            				alert('请填写详细地址');
            				return false;
            			} 
            		} else if ($('.shipping-method:checked').length == 0) {
            			alert('请选择配送方式');
            			return false;
            		} else if ($('input[name="payment"]:checked').length == 0) {
            			alert('请选择支付方式');
            			return false;
            		}
            	}
            </script>
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
function changeAmount() {
	var amountList = $('input[name="order-goods-subtotal"]:not(.coded)');
		var totalAmount = 0;
		for (var i=0;i<amountList.length;i++) {
			totalAmount += parseFloat($(amountList[i]).val());
		}
		var shippingList = $('.shipping-method1:checked');
		for (var i = 0;i<shippingList.length;i++) {
			totalAmount += parseFloat($(shippingList[i]).data('fee'));
		}
//		totalAmount = totalAmount.toFixed(2);
		$('#order_amount').text(totalAmount);
		if (totalAmount <=0) {
			$('.payment-area').hide();
			var paymentitem = '<input type="radio" checked="checked" class="hide-elem" style="width:1px;height:1px" name="payment" id="pay_alipay" value="alipay" />';
			$('.cart-sum-submit').prepend(paymentitem);
		} else {
			$('.payment-area').show();
			$('.hide-elem').remove();
		}
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
	var amountList = $('input[name="order-goods-subtotal"]:not(.coded)');
		var totalAmount = 0;
		for (var i=0;i<amountList.length;i++) {
			totalAmount += parseFloat($(amountList[i]).val());
		}
		var shippingList = $('.shipping-method1:checked');
		for (var i = 0;i<shippingList.length;i++) {
			totalAmount += parseFloat($(shippingList[i]).data('fee'));
		}
//		totalAmount = totalAmount.toFixed(2);
		$('#order_amount').text(totalAmount);
		if (totalAmount <=0) {
			$('.payment-area').hide();
			var paymentitem = '<input type="radio" checked="checked" class="hide-elem" style="width:1px;height:1px" name="payment" id="pay_alipay" value="alipay" />';
			$('.cart-sum-submit').prepend(paymentitem);
		} else {
			$('.payment-area').show();
			$('.hide-elem').remove();
		}
	$('.cod-y').click(function() {
		$(this).parent().parent().parent().find('input[name="order-goods-subtotal"]').addClass('coded');
		var amountList = $('input[name="order-goods-subtotal"]:not(.coded)');
		var totalAmount = 0;
		for (var i=0;i<amountList.length;i++) {
			totalAmount += parseFloat($(amountList[i]).val());
		}
		var shippingList = $('.shipping-method1:checked');
		for (var i = 0;i<shippingList.length;i++) {
			totalAmount += parseFloat($(shippingList[i]).data('fee'));
		}
//		totalAmount = totalAmount.toFixed(2);
		$('#order_amount').text(totalAmount);
		if (totalAmount <=0) {
			$('.payment-area').hide();
			var paymentitem = '<input type="radio" checked="checked" class="hide-elem" style="width:1px;height:1px" name="payment" id="pay_alipay" value="alipay" />';
			$('.cart-sum-submit').prepend(paymentitem);
		} else {
			$('.payment-area').show();
			$('.hide-elem').remove();
		}
	})
	$('.cod-n').click(function() {
		$(this).parent().parent().parent().find('input[name="order-goods-subtotal"]').removeClass('coded');
		var amountList = $('input[name="order-goods-subtotal"]:not(.coded)');
		var totalAmount = 0;
		for (var i=0;i<amountList.length;i++) {
			totalAmount += parseFloat($(amountList[i]).val());
		}
		var shippingList = $('.shipping-method1:checked');
		for (var i = 0;i<shippingList.length;i++) {
			totalAmount += parseFloat($(shippingList[i]).data('fee'));
		}
		
//		totalAmount = totalAmount.toFixed(2);
		$('#order_amount').text(totalAmount);
		if (totalAmount <=0) {
			$('.payment-area').hide();
			var paymentitem = '<input type="radio" checked="checked" class="hide-elem" style="width:1px;height:1px" name="payment" id="pay_alipay" value="alipay" />';
			$('.cart-sum-submit').prepend(paymentitem);
		} else {
			$('.payment-area').show();
			$('.hide-elem').remove();
		}
	})
	
	
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

           </body>
</html>
