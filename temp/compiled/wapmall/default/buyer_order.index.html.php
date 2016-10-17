<?php echo $this->fetch('member.header.html'); ?>
<?php echo $this->_var['_head_tags']; ?>
<body>
	<header class="list-header">
			<a href="<?php echo url('app=default'); ?>" class="back-btn"></a>
			订单中心
			<?php echo $this->fetch('header.search.html'); ?>
			<a href="javascript:void(0)" class="menu-btn"></a>
		</header>
	<div class="order-list-area">
		<ul class="ecmall-tab forth-tab">
			<li class="tab-item <?php if ($this->_var['type'] == pending): ?>current-tab<?php endif; ?>"><a href="<?php echo url('app=buyer_order&act=index&type=pending'); ?>">待付款</a></li>
			<li class="tab-item <?php if ($this->_var['type'] == accepted): ?>current-tab<?php endif; ?>"><a href="<?php echo url('app=buyer_order&act=index&type=accepted'); ?>">待发货</a></li>
			<li class="tab-item <?php if ($this->_var['type'] == shipped): ?>current-tab<?php endif; ?>"><a href="<?php echo url('app=buyer_order&act=index&type=shipped'); ?>">待收货</a></li>
			<li class="tab-item <?php if ($this->_var['type'] == finished): ?>current-tab<?php endif; ?>"><a href="<?php echo url('app=buyer_order&act=index&type=finished'); ?>">已完成</a></li>
		</ul>
		<div id="myorder-list">
			<?php $_from = $this->_var['orders']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'order');if (count($_from)):
    foreach ($_from AS $this->_var['order']):
?>
				<section class="myorder-item">
					<div class="order-goods-item">
						<h3 class="order-goods-shopname" <?php if ($this->_var['order']['status'] == ORDER_PENDING): ?> style="padding-left: 57px;background-position-x: 36px;"<?php endif; ?>>
							<?php if ($this->_var['order']['status'] == ORDER_PENDING): ?>
								<input type="checkbox" name="pending" id="<?php echo $this->_var['order']['order_id']; ?>" value="<?php echo $this->_var['order']['order_id']; ?>" class="ecmall-check" />
							<?php endif; ?>
							<label for="<?php echo $this->_var['order']['order_id']; ?>"><?php echo htmlspecialchars($this->_var['order']['seller_name']); ?></label>	
							<span class="order-status"><?php echo call_user_func("order_status",$this->_var['order']['status']); ?><?php if ($this->_var['order']['evaluation_status']): ?>,&nbsp;已评价<?php endif; ?></span>
						</h3>
						<?php $_from = $this->_var['order']['order_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
						<ul class="order-goods-list">
							<li>
								<a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>" class="order-goods-link">
									<img src="<?php echo $this->_var['goods']['goods_image']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>" />
									<div class="order-goods-info">
										<p class="order-goods-name"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></p>
										<p class="order-goods-amount">数量：<?php echo $this->_var['goods']['quantity']; ?></p>
										<?php if ($this->_var['order']['credit_consume']): ?>
										<p class="order-goods-price">积分：<?php echo $this->_var['order']['credit_consume']; ?></p>
										<?php else: ?>
										<p class="order-goods-price"><?php echo price_format($this->_var['goods']['price']); ?></p>
										<?php endif; ?>
									</div>
								</a>
							</li>
						</ul>
						
						<?php if ($this->_var['order']['payment_name']): ?>

                		<?php endif; ?>
                		<div class="myorder-option">
                			<div class="myorder-info">
								共<?php echo $this->_var['goods']['quantity']; ?>件 合计：<span class="myorder-info-price"><?php if ($this->_var['order']['credit_consume']): ?>积分<?php echo $this->_var['order']['credit_consume']; ?><?php else: ?>￥<em><?php echo $this->_var['goods']['price']; ?></em><?php endif; ?></span><span class="myorder-info-express-fee">(含运费￥<?php echo $this->_var['order']['shipping_fee']; ?>)</span>
							</div>
							<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
							<div class="myorder-function">
								<?php if ($this->_var['order']['status'] == ORDER_FINISHED && $this->_var['order']['evaluation_status'] == 0): ?> <a class="myorder-btn" href="<?php echo url('app=buyer_order&act=evaluate&order_id=' . $this->_var['order']['order_id']. ''); ?>" id="order<?php echo $this->_var['order']['order_id']; ?>_evaluate" >我要评价</a><?php endif; ?>
	                    		<?php if ($this->_var['order']['status'] == ORDER_PENDING && $this->_var['order']['order_amount'] != 0.00): ?><a href="<?php echo url('app=cashier&order_id=' . $this->_var['order']['order_id']. ''); ?>" class="myorder-btn" id="order<?php echo $this->_var['order']['order_id']; ?>_action_pay" class="white_btn">付款</a><?php endif; ?> 
	                    		<?php if ($this->_var['order']['status'] == ORDER_SHIPPED && $this->_var['order']['payment_code'] != 'cod'): ?>  <button type="button" class="myorder-btn" ectype="dialog" dialog_id="buyer_order_confirm_order" dialog_width="100%" dialog_title="确认收货" uri="index.php?app=buyer_order&amp;act=confirm_order&order_id=<?php echo $this->_var['order']['order_id']; ?>&ajax"  id="order<?php echo $this->_var['order']['order_id']; ?>_action_confirm"/>确认收货</button><?php endif; ?>
	                            <a href="<?php echo url('app=buyer_order&act=view&order_id=' . $this->_var['order']['order_id']. ''); ?>"  class="myorder-btn">查看订单</a>
							</div>
                		</div>
							
					</div>
				</section>
				<?php endforeach; else: ?>
				<section class="myorder-item" style="height: 100px;line-height: 100px;width: 100%;text-align: center;">
					你没有订单信息~
				</section>
				 <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
		</div>
	</div>
	
    



        <div class="page">
            <?php echo $this->fetch('member.page.bottom.html'); ?>
        </div>
<?php if ($this->_var['type'] == pending): ?>

<div class="cart-sum-submit">
	<span class="cart-sum">合计：<span class="cart-price">&yen;<em id="order-number"></em></span>(不含运费)</span>
	<a href="#" id="totalpay" class="cart-submit">合并付款</a>
</div>

<script type="text/javascript">

$(function() {
	var order_id = [];
	$('input[name="pending"]').change(function() {
		if ($(this).prop('checked')) {
			if (order_id.length > 0) {
				order_id = JSON.parse(order_id);
			}
			order_id.push($(this).val());
			order_id = JSON.stringify(order_id);
			$('#totalpay').attr('href','index.php?app=cashier&act=merge_payment&order_id='+order_id);
		} else {
			order_id = [];
			var checkedbox = $('input[name="pending"]:checked');
			for (var i = 0;i<checkedbox.length;i++) {
				order_id.push($(checkedbox[i]).val());
			}
			order_id = JSON.stringify(order_id);
			$('#totalpay').attr('href','index.php?app=cashier&act=merge_payment&order_id='+order_id);
		}
		var selectedGoods = $('input[name="pending"]:checked').parent().parent().parent().find('em');
	var totalamount = 0;
	for (var i = 0;i < selectedGoods.length;i ++) {
		
				totalamount += parseFloat($(selectedGoods[i]).text());
			
	}
	$('#order-number').text(totalamount);
	})
	var selectedGoods = $('input[name="pending"]:checked').parent().parent().parent().find('em');
	var totalamount = 0;
	for (var i = 0;i < selectedGoods.length;i ++) {
		totalamount += parseFloat($(selectedGoods[i]).text());
			
	}
	$('#order-number').text(totalamount);
})
	
</script>
<?php endif; ?>
        <iframe id='iframe_post' name="iframe_post" src="about:blank" frameborder="0" width="0" height="0"></iframe>
    <?php echo $this->fetch('member.footer.html'); ?>
