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
<body>
<header class="list-header">
	<a href="javascript:history.back();" class="back-btn"></a>
	<?php echo $this->fetch('header.search.html'); ?>
	<a href="javascript:void(0)" class="menu-btn"></a>
</header>
<div class="all-goods-area">
			<ul class="cate-list">
				
				
				<?php $_from = $this->_var['gcategorys']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'gcategory');if (count($_from)):
    foreach ($_from AS $this->_var['gcategory']):
?>
				<li class="cate-item">
					<?php if ($this->_var['gcategory']['children']): ?>
					<?php echo htmlspecialchars($this->_var['gcategory']['value']); ?>
					<?php else: ?>
					 <a href="<?php echo url('app=search&cate_id=' . $this->_var['gcategory']['id']. ''); ?>"><?php echo htmlspecialchars($this->_var['gcategory']['value']); ?></a> 
					<?php endif; ?>
					<div class="cate-content">
						<?php $_from = $this->_var['gcategory']['children']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'child');if (count($_from)):
    foreach ($_from AS $this->_var['child']):
?>
					    <a href="<?php echo url('app=search&cate_id=' . $this->_var['child']['id']. ''); ?>"><?php echo htmlspecialchars($this->_var['child']['value']); ?></a> 
					    <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</div>
				</li>
				<?php endforeach; else: ?>
				<div class="radius">
				    暂无分类
				</div>
				<?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
				
			</ul>
<script type="text/javascript">
	$(function() {
		$('.cate-item').eq(0).addClass('current-cate');
		var contentWidth = $('body').innerWidth - 100;
		$('.cate-content').css({'width':contentWidth + 'px'});
		$('.cate-item').click(function() {
			$('.cate-item').removeClass('current-cate');
			$(this).addClass('current-cate');
		})
	})
</script>
</div>
<?php echo $this->fetch('footer.html'); ?>
</div>
</body>
</html>




