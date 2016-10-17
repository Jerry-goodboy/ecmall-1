
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <base href="<?php echo $this->_var['site_url']; ?>/" />
        <?php echo $this->_var['page_seo']; ?>
        <link href="<?php echo $this->res_base . "/" . 'css/common.css'; ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo $this->res_base . "/" . 'css/address.css'; ?>" type="text/css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="newstatic/css/basic.css" />
		<link rel="stylesheet" type="text/css" href="newstatic/css/style.css" />
        <script type="text/javascript" src="index.php?act=jslang"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.js'; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'member.js'; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/jquery-1.8.0.min.js'; ?>"></script>


        <script type="text/javascript">
            //<!CDATA[
            var SITE_URL = "<?php echo $this->_var['site_url']; ?>";
            var REAL_SITE_URL = "<?php echo $this->_var['real_site_url']; ?>";
            var PRICE_FORMAT = '<?php echo $this->_var['price_format']; ?>';
            //]]>
        </script>
        <?php echo $this->_var['_head_tags']; ?>
    </head>
    <body>
    	<header class="list-header">
			<a href="javascript:history.back()" class="back-btn"></a>
			收货地址
			<?php echo $this->fetch('header.search.html'); ?>
			<a href="javascript:void(0)" class="menu-btn"></a>
		</header>
    	<div class="consignee-page">
			<div class="consignee-area">
				<?php $_from = $this->_var['addresses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'address');$this->_foreach['v'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['v']['total'] > 0):
    foreach ($_from AS $this->_var['address']):
        $this->_foreach['v']['iteration']++;
?>
				<div class="consignee-item">
					<div class="consignee-detail">
						<div class="consignee-info">
							<span class="consignee-name"><?php echo htmlspecialchars($this->_var['address']['consignee']); ?></span>
							<span class="consignee-mobile"><?php if ($this->_var['address']['phone_tel']): ?><?php echo $this->_var['address']['phone_tel']; ?> <?php else: ?> <?php echo $this->_var['address']['phone_mob']; ?><?php endif; ?></span>
						</div>
						<div class="consignee-addr">
							收货地址:河南省 <?php echo htmlspecialchars($this->_var['address']['region_name']); ?> <?php echo htmlspecialchars($this->_var['address']['address']); ?>
						</div>
					</div>
					<div class="consignee-function">
						<a href="index.php?app=my_address&act=edit&addr_id=<?php echo $this->_var['address']['addr_id']; ?>">编辑</a>
						<a href="javascript:drop_confirm('您确定要删除它吗？', 'index.php?app=my_address&amp;act=drop&addr_id=<?php echo $this->_var['address']['addr_id']; ?>');">删除</a>
					</div>
				</div>
				 <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?> 
				<div class="consignee-item" style="border-bottom: none;">
					<a class="ecmall-btn ecmall-confirm-btn" href="index.php?app=my_address&act=add">新增收货地址</a>
				</div>
			</div>
		</div>
    	
    	
            
           
            <iframe id='iframe_post' name="iframe_post" frameborder="0" width="0" height="0"></iframe>
                
        <?php echo $this->fetch('member.footer.html'); ?>