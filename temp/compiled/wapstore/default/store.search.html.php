
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
        <script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/sub_menu.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>

    </head>

    <body>
		 <header class="list-header">
			<a href="javascript:history.back()" class="back-btn"></a>
			<?php echo $this->fetch('header.search.html'); ?>
			<a href="javascript:void(0)" class="menu-btn"></a>
		</header>
		<div class="shop-page">
			<ul class="ecmall-tab forth-tab">
				<li class="tab-item <?php if ($this->_var['order'] == 'sales'): ?>current-tab<?php endif; ?>"><a href="index.php?app=store&act=search&id=<?php echo $this->_var['store']['store_id']; ?>&keyword=<?php echo $this->_var['keyword']; ?>&cate_id=<?php echo $this->_var['cate_id']; ?>&order=sales">销量</a></li>
				<li class="tab-item <?php if ($this->_var['order'] == 'add_time'): ?>current-tab<?php endif; ?>"><a href="index.php?app=store&act=search&id=<?php echo $this->_var['store']['store_id']; ?>&keyword=<?php echo $this->_var['keyword']; ?>&cate_id=<?php echo $this->_var['cate_id']; ?>&order=add_time">新品</a></li>
				<li class="tab-item <?php if ($this->_var['order'] == 'price'): ?>current-tab<?php endif; ?>"><a href="index.php?app=store&act=search&id=<?php echo $this->_var['store']['store_id']; ?>&keyword=<?php echo $this->_var['keyword']; ?>&cate_id=<?php echo $this->_var['cate_id']; ?>&order=price">价格</a></li>
				<li class="tab-item <?php if ($this->_var['order'] == 'views'): ?>current-tab<?php endif; ?>"><a href="index.php?app=store&act=search&id=<?php echo $this->_var['store']['store_id']; ?>&keyword=<?php echo $this->_var['keyword']; ?>&cate_id=<?php echo $this->_var['cate_id']; ?>&order=views">人气</a></li>
			</ul>
			
			<div class="mall-goods-list-area">
				<ul class="mall-goods-list">
					<?php if ($this->_var['searched_goods']): ?>
                	<?php $_from = $this->_var['searched_goods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sgoods');if (count($_from)):
    foreach ($_from AS $this->_var['sgoods']):
?>
					<li class="mall-goods-item">
						<a href="<?php echo url('app=goods&id=' . $this->_var['sgoods']['goods_id']. ''); ?>">
							<img src="<?php echo $this->_var['sgoods']['default_image']; ?>" alt="<?php echo htmlspecialchars($this->_var['sgoods']['goods_name']); ?>" />
							<h3 class="mall-goods-item-name"><?php echo htmlspecialchars($this->_var['sgoods']['goods_name']); ?></h3>
							<p class="mall-goods-current-price"><?php echo price_format($this->_var['sgoods']['price']); ?></p>
						</a>
					</li>
					<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	                <?php else: ?>
	                <div style="padding:50px 60px; text-align:center;background:#fff;margin:5px 5px 0;">很抱歉! 没有找到相关商品</div>
	                <?php endif; ?>
				</ul>
			</div>
		</div>
		<script type="text/javascript">
			$(function() {
				var templink = window.location.href;
				if (templink.indexOf('asc') == -1) {
					$('.current-tab a').attr('href',templink + '&asc=0');
				} else if (templink.indexOf('asc=0') > -1) {
					$('.current-tab a').attr('href',templink.replace('asc=0','asc=1'));
				} else if (templink.indexOf('asc=1') > -1) {
					$('.current-tab a').attr('href',templink.replace('asc=1','asc=0'));
				}
			})
		</script>

        
        <div class="page">
            <?php echo $this->fetch('page.bottom.html'); ?>
        </div>
        <?php echo $this->fetch('footer.html'); ?>
    </body>
</html>