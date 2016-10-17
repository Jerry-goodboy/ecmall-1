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
    <body id="log-reg" >
<header class="list-header">
			<a href="javascript:history.back()" class="back-btn"></a>
			店铺列表
			<?php echo $this->fetch('header.search.html'); ?>
			<a href="javascript:void(0)" class="menu-btn"></a>
		</header>

<div class="shop-search-area">
    <ul class="shop-list">
        <?php $_from = $this->_var['stores']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'store');if (count($_from)):
    foreach ($_from AS $this->_var['store']):
?>
        <li>
            <a href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. ''); ?>" class="shop-item-link">
                   <img src="<?php echo $this->_var['store']['store_logo']; ?>" alt="<?php echo htmlspecialchars($this->_var['store']['store_name']); ?>" width="100" height="100"/>
                <span><?php echo htmlspecialchars($this->_var['store']['store_name']); ?></span>
                <span class="shop-owner-name"><?php echo htmlspecialchars($this->_var['store']['user_name']); ?></span>
                <!--<span><?php echo htmlspecialchars($this->_var['store']['region_name']); ?><img src="<?php echo $this->_var['store']['credit_image']; ?>"/></span>-->
            </a>
        </li>
        <?php endforeach; else: ?>
        未找到此类店铺
        <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </ul>
</div>

<?php echo $this->fetch('page.bottom.html'); ?>

<?php echo $this->fetch('footer.html'); ?>


</div>
</body>
</html>