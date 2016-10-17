<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <?php echo $this->_var['page_seo']; ?>
        <link href="<?php echo $this->res_base . "/" . 'css/common.css'; ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo $this->res_base . "/" . 'css/index.css'; ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo $this->res_base . "/" . 'css/base.css'; ?>" type="text/css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="newstatic/css/basic.css" />
		<link rel="stylesheet" type="text/css" href="newstatic/css/style.css" />
        <script type="text/javascript" src="index.php?act=jslang"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.js'; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
        <?php echo $this->_var['_head_tags']; ?>
        <script type="text/javascript">
            //<!CDATA[
            var SITE_URL = "<?php echo $this->_var['site_url']; ?>";
            var REAL_SITE_URL = "<?php echo $this->_var['real_site_url']; ?>";
            var PRICE_FORMAT = '<?php echo $this->_var['price_format']; ?>';
            //]]>
        </script>
    </head>
    <body id="log-reg" class="gray">
<header class="list-header">
			<a href="javascript:history.back()" class="back-btn"></a>
			产品列表
			<?php echo $this->fetch('header.search.html'); ?>
			<a href="javascript:void(0)" class="menu-btn"></a>
		</header>



<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'search_goods.js'; ?>" charset="utf-8"></script>

<div class="mall-goods-area">
			<div class="mall-goods-list-area">
				<ul class="mall-goods-list">
					<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');$this->_foreach['fe_goods'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods']['total'] > 0):
    foreach ($_from AS $this->_var['goods']):
        $this->_foreach['fe_goods']['iteration']++;
?>
					<li class="mall-goods-item">
						<a href="<?php echo url('app=goods&id=' . $this->_var['goods']['goods_id']. ''); ?>">
							<img src="<?php echo $this->_var['goods']['default_image']; ?>" alt="<?php echo $this->_var['goods']['name']; ?>" />
							<h3 class="mall-goods-item-name"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></h3>
							<?php if ($this->_var['goods']['if_credit'] == 1): ?>
							<p class="mall-goods-current-price">积分:<?php echo $this->_var['goods']['credit']; ?></p>
							<?php else: ?>
							<p class="mall-goods-current-price"><?php echo price_format($this->_var['goods']['price']); ?></p>
							<?php endif; ?>
						</a>
					</li>
					 <?php endforeach; else: ?>
        暂无此类商品
        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
				</div>
			</div>

<?php echo $this->fetch('page.bottom.html'); ?>


<?php echo $this->fetch('footer.html'); ?>

</div>
</body>
</html>