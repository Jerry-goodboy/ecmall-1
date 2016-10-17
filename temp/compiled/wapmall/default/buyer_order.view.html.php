
<?php echo $this->fetch('member.header.html'); ?>


<body>
		<header class="list-header">
			<a href="javascript:history.back()" class="back-btn"></a>
			订单详情
			<?php echo $this->fetch('header.search.html'); ?>
			<a href="javascript:void(0)" class="menu-btn"></a>
		</header>
		<div class="order-detail-area">
			<div class="order-detail-consignee">
				<div class="order-consignee-info">
					<span class="order-consignee-name"><?php echo htmlspecialchars($this->_var['order_extm']['consignee']); ?></span>
					<span class="order-consignee-mobile"><?php echo $this->_var['order_extm']['phone_mob']; ?></span>
				</div>
				<div class="order-consignee-addr">
					收货地址:<?php echo htmlspecialchars($this->_var['order_extm']['region_name']); ?>&nbsp;<?php echo htmlspecialchars($this->_var['order_extm']['address']); ?>
				</div>
			</div>
		</div>
		<div class="order-goods-item">
				<h3 class="order-goods-shopname">
					<?php echo htmlspecialchars($this->_var['order']['store_name']); ?>
				</h3>
				<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
				<ul class="order-goods-list">
					<li>
						<a href="index.php?app=goods&id=<?php echo $this->_var['goods']['goods_id']; ?>" class="order-goods-link">
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
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				<div class="order-goods-extra">
					<div class="order-goods-input-item">
						<label for="payment">订单编号</label>
						<span class="order-goods-extra-content"><?php echo $this->_var['order']['order_sn']; ?></span>
					</div>
					<div class="order-goods-input-item">
						<label for="express">配送方式</label>
						<span class="order-goods-extra-content"><?php echo htmlspecialchars($this->_var['order_extm']['shipping_name']); ?></span>
					</div>
					<div class="order-goods-input-item">
						<label for="postscript">买家留言</label>
						<span class="order-goods-extra-content">给卖家的附言</span>
					</div>
					<?php if ($this->_var['order']['payment_name'] | escape): ?>
					<div class="order-goods-input-item">
						<label for="payment">支付方式</label>
						<span class="order-goods-extra-content"><?php echo htmlspecialchars($this->_var['order']['payment_name']); ?></span>
					</div>
					<?php endif; ?>
					<div class="order-goods-input-item">
						<label for="payment">订单时间</label>
						<span class="order-goods-extra-content"><?php echo local_date("Y-m-d H:i:s",$this->_var['order']['order_add_time']); ?> (创建订单)</span>
						  <?php if ($this->_var['order']['pay_time']): ?>
						<br /><span class="order-goods-extra-content"><?php echo local_date("Y-m-d H:i:s",$this->_var['order']['pay_time']); ?> (支付成功)</span>
						<?php endif; ?>
						 <?php if ($this->_var['order']['ship_time']): ?>
						<br /><span class="order-goods-extra-content"><?php echo local_date("Y-m-d H:i:s",$this->_var['order']['ship_time']); ?> (已发货)</span>
						<?php endif; ?>
						<?php if ($this->_var['order']['finished_time']): ?>
						<br /><span class="order-goods-extra-content"><?php echo local_date("Y-m-d H:i:s",$this->_var['order']['finished_time']); ?> (订单已完成)</span>
						<?php endif; ?>
						
					</div>
					<div class="order-goods-input-item">
						<label for="payment">卖家电话</label>
						<span class="order-goods-extra-content"><?php echo (htmlspecialchars($this->_var['order']['tel']) == '') ? '-' : htmlspecialchars($this->_var['order']['tel']); ?></span>
					</div>
					<div class="myorder-info">
						共1件 合计：<?php if ($this->_var['order']['credit_consume']): ?><span class="myorder-info-price">积分<?php echo $this->_var['order']['credit_consume']; ?></span><span class="myorder-info-express-fee">(含运费<?php echo price_format($this->_var['order_extm']['shipping_fee']); ?>)</span><?php else: ?><span class="myorder-info-price"><?php echo price_format($this->_var['order']['order_amount']); ?></span><span class="myorder-info-express-fee">(含运费<?php echo price_format($this->_var['order_extm']['shipping_fee']); ?>)</span><?php endif; ?>
					</div>
				</div>
				
			</div>
		
    
    <?php echo $this->fetch('member.footer.html'); ?>

