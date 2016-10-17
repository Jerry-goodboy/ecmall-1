
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <?php echo $this->_var['page_seo']; ?>
        <link href="<?php echo $this->res_base . "/" . 'css/common.css'; ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo $this->res_base . "/" . 'css/comment.css'; ?>" type="text/css" rel="stylesheet" />
        <link href="newstatic/css/basic.css" type="text/css" rel="stylesheet" />
		<link href="newstatic/css/style.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/jquery-1.8.0.min.js'; ?>"></script>
        <script type="text/javascript">
      //<!CDATA[
            var SITE_URL = "<?php echo $this->_var['site_url']; ?>";
            var REAL_SITE_URL = "<?php echo $this->_var['real_site_url']; ?>";
            var PRICE_FORMAT = '<?php echo $this->_var['price_format']; ?>';
      //]]>
        </script>
        <script type="text/javascript" src="index.php?act=jslang"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.js'; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'member.js'; ?>" charset="utf-8"></script>
    </head>
    <body>
    	<header class="list-header">
			<a href="<?php echo url('app=member'); ?>" class="back-btn"></a>
			发表评价
			<?php echo $this->fetch('header.search.html'); ?>
			<a href="javascript:void(0)" class="menu-btn"></a>
		</header>
		<div class="order-comment-page">
			<div class="order-goods-item">
				<h3 class="order-goods-shopname">
					<?php echo htmlspecialchars($this->_var['order']['seller_name']); ?>
				</h3>
				<?php $_from = $this->_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS $this->_var['goods']):
?>
				<ul class="order-goods-list">
					<li class="order-goods-thumb">
						<a href="" class="order-goods-link">
							<img src="<?php echo $this->_var['goods']['goods_image']; ?>" alt="<?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?>" />
						</a>
					</li>
				</ul>
				<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
			</div>
			<div class="order-comment-area">
				<form method="POST">
				<textarea name="evaluations[<?php echo $this->_var['goods']['rec_id']; ?>][comment]" rows="2" placeholder="开始畅所欲言，写出对产品的感受吧，感谢留言！" class="order-comment-input">
					
				</textarea>
				<div class="order-comment-rate">
					<input type="radio" class="ecmall-check" id="g<?php echo $this->_var['goods']['rec_id']; ?>_op1" name="evaluations[<?php echo $this->_var['goods']['rec_id']; ?>][evaluation]" value="3" />
					<label for="g<?php echo $this->_var['goods']['rec_id']; ?>_op1" class="rate-star five-star">好评</label>
				</div>
				<div class="order-comment-rate">
					<input type="radio" class="ecmall-check" id="g<?php echo $this->_var['goods']['rec_id']; ?>_op2" name="evaluations[<?php echo $this->_var['goods']['rec_id']; ?>][evaluation]" value="2" />
					<label for="g<?php echo $this->_var['goods']['rec_id']; ?>_op2" class="rate-star three-star">中评</label>
				</div>
				<div class="order-comment-rate">
					<input type="radio" class="ecmall-check" id="g<?php echo $this->_var['goods']['rec_id']; ?>_op3" name="evaluations[<?php echo $this->_var['goods']['rec_id']; ?>][evaluation]" value="1" />
					<label for="g<?php echo $this->_var['goods']['rec_id']; ?>_op3" class="rate-star one-star">差评</label>
				</div>
				<button class="ecmall-btn ecmall-confirm-btn">提交评价</button>
				</form>
			</div>
		</div>
        
        
        <?php echo $this->fetch('member.footer.html'); ?>