
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <?php echo $this->_var['page_seo']; ?>
        <link href="<?php echo $this->res_base . "/" . 'css/common.css'; ?>" rel="stylesheet" type="text/css" />
        <link href="<?php echo $this->res_base . "/" . 'css/flexslider.css'; ?>" rel="stylesheet" type="text/css" />
        <link href="newstatic/css/basic.css" type="text/css" rel="stylesheet" />
        <link href="newstatic/css/style.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/jquery-1.8.0.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/jquery.flexslider.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/touchslider.dev.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/sub_menu.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
        <script type="text/javascript">
        	var SITE_URL = '<?php echo $this->_var['site_url']; ?>';
        </script>
    </head>

    <body style="background: #F8F8F8;">
        <header class="list-header">
			<a href="javascript:history.back()" class="back-btn"></a>
			<?php echo $this->fetch('header.search.html'); ?>
			<a href="javascript:void(0)" class="menu-btn"></a>
		</header>
		<div class="shop-page">
			<div class="shop-banner">
				<?php if ($this->_var['store']['store_banner']): ?>
				<img src="<?php echo $this->_var['store']['store_banner']; ?>"/>
				<?php else: ?>
				<?php echo $this->_var['store']['store_name']; ?>
				<?php endif; ?>
				<button class="shop-fav" onclick="collect_store(<?php echo $this->_var['store']['store_id']; ?>);">收藏</button>
			</div>
			<div class="shop-goods-info">
				<a class="shop-goods-info-detail" href="index.php?app=store&act=search&id=<?php echo $this->_var['store']['store_id']; ?>&order=add_time">
					<?php echo $this->_var['new_goods_number']; ?> <br />最新商品
				</a>
				<a class="shop-goods-info-detail" href="index.php?app=store&act=search&id=<?php echo $this->_var['store']['store_id']; ?>&recommended=1">
					<?php echo $this->_var['recommended_goods_number']; ?> <br />推荐商品
				</a>
				<a class="shop-goods-info-detail" href="index.php?app=store&act=search&id=<?php echo $this->_var['store']['store_id']; ?>&order=views">
					<?php echo $this->_var['hot_goods_number']; ?> <br />热门商品
				</a>
			</div>
			<?php if ($this->_var['recommended_goods']): ?>
			<h2 class="mall-flash-list-title shop-col-title">
				橱窗推荐
				<a href="index.php?app=store&act=search&id=<?php echo $this->_var['store']['store_id']; ?>&recommended=1" class="title-more">更多</a>
			</h2>
			<div class="mall-goods-list-area">
				<ul class="mall-goods-list">
					 <?php $_from = $this->_var['recommended_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'rgoods');if (count($_from)):
    foreach ($_from AS $this->_var['rgoods']):
?>
					<li class="mall-goods-item">
						<a href="<?php echo url('app=goods&id=' . $this->_var['rgoods']['goods_id']. ''); ?>">
							<img src="<?php echo $this->_var['rgoods']['default_image']; ?>" alt="<?php echo htmlspecialchars($this->_var['rgoods']['goods_name']); ?>" />
							<h3 class="mall-goods-item-name"><?php echo htmlspecialchars($this->_var['rgoods']['goods_name']); ?></h3>
							<?php if ($this->_var['rgoods']['if_credit']): ?>
							<p class="mall-goods-current-price"><?php echo $this->_var['rgoods']['credit']; ?>积分</p>
							<?php else: ?>
							<p class="mall-goods-current-price"><?php echo price_format($this->_var['rgoods']['price']); ?></p>
							<?php endif; ?>
						</a>
					</li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
			</div>
			<?php endif; ?>
			
			<?php if ($this->_var['new_goods']): ?>
			<h2 class="mall-flash-list-title shop-col-title">
				新品上市
				<a href="index.php?app=store&act=search&id=<?php echo $this->_var['store']['store_id']; ?>&order=add_time" class="title-more">更多</a>
			</h2>
			<div class="mall-goods-list-area">
				<ul class="mall-goods-list">
					 <?php $_from = $this->_var['new_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'ngoods');if (count($_from)):
    foreach ($_from AS $this->_var['ngoods']):
?>
					<li class="mall-goods-item">
						<a href="<?php echo url('app=goods&id=' . $this->_var['ngoods']['goods_id']. ''); ?>">
							<img src="<?php echo $this->_var['ngoods']['default_image']; ?>" alt="<?php echo htmlspecialchars($this->_var['ngoods']['goods_name']); ?>" />
							<h3 class="mall-goods-item-name"><?php echo htmlspecialchars($this->_var['ngoods']['goods_name']); ?></h3>
							<?php if ($this->_var['ngoods']['if_credit']): ?>
							<p class="mall-goods-current-price"><?php echo $this->_var['ngoods']['credit']; ?> 积分</p>
							<?php else: ?>
							<p class="mall-goods-current-price"><?php echo price_format($this->_var['ngoods']['price']); ?></p>
							<?php endif; ?>
						</a>
					</li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>
        
        
        <?php echo $this->fetch('footer.html'); ?>

    </body>
</html>
