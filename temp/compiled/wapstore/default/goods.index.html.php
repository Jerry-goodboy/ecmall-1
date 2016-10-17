
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <meta content="telephone=no" name="format-detection" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <?php echo $this->_var['page_seo']; ?>
        <link href="<?php echo $this->res_base . "/" . 'css/common.css'; ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo $this->res_base . "/" . 'css/detail.css'; ?>" type="text/css" rel="stylesheet" />
        <link href="newstatic/css/basic.css" type="text/css" rel="stylesheet" />
        <link href="newstatic/css/style.css" type="text/css" rel="stylesheet" />
        <script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/jquery-1.8.0.min.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/sub_menu.js'; ?>"></script>
        <script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/touchslider.dev.js'; ?>"></script>
        <script type="text/javascript" src="index.php?act=jslang"></script>


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

        <?php echo $this->fetch('header.html'); ?>


        
        <?php echo $this->fetch('goodsinfo.html'); ?>
        
        <div class="goods-info-details detail_con">
			<ul class="ecmall-tab db-tab">
				<li class="tab-item current-tab">商品详情</li>
				<li class="tab-item">精彩评论</li>
			</ul>
			<div class="goods-info-detail-content" style="padding-left: 16px;padding-right: 16px;">
				 <?php echo html_filter($this->_var['goods']['description']); ?>
			</div>
			<div class="goods-info-detail-content" style="display:none;">
				<ul class="goods-comment-list">
					<?php $_from = $this->_var['goods_comments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'comment');if (count($_from)):
    foreach ($_from AS $this->_var['comment']):
?>
					<li class="goods-comment-item">
						<div class="goods-comment-user"><?php if ($this->_var['comment']['anonymous']): ?>***<?php else: ?><?php echo htmlspecialchars($this->_var['comment']['buyer_name']); ?><?php endif; ?></div>
						<p class="goods-comment-content">
							<?php echo nl2br(htmlspecialchars($this->_var['comment']['comment'])); ?>
						</p>
						<div class="goods-comment-info">
							<span class="goods-comment-time"><?php echo local_date("Y-m-d H:i:s",$this->_var['comment']['evaluation_time']); ?></span>
							<span class="goods-comment-rank">评分：
								<?php if ($this->_var['comment']['evaluation'] > 1): ?><img style="width:11px;height:11px;" src="<?php echo $this->res_base . "/" . 'images/bit.png'; ?>" /><?php endif; ?>
                                <?php if ($this->_var['comment']['evaluation'] > 2): ?><img style="width:11px;height:11px;" src="<?php echo $this->res_base . "/" . 'images/bit.png'; ?>" /><?php endif; ?>
                                <?php if ($this->_var['comment']['evaluation'] < 3): ?><img style="width:11px;height:11px;" src="<?php echo $this->res_base . "/" . 'images/bit2.png'; ?>" /><?php endif; ?>
                                <?php if ($this->_var['comment']['evaluation'] < 2): ?><img style="width:11px;height:11px;" src="<?php echo $this->res_base . "/" . 'images/bit2.png'; ?>" /><?php endif; ?>
                                <?php if ($this->_var['comment']['evaluation'] < 1): ?><img style="width:11px;height:11px;" src="<?php echo $this->res_base . "/" . 'images/bit2.png'; ?>" /><?php endif; ?>
							</span>
						</div>
						<?php if ($this->_var['comment']['reply']): ?>
						<div class="goods-comment-reply">
							[店长回复:]<?php echo $this->_var['comment']['reply']; ?>
						</div>
						<?php endif; ?>
					</li>
					<?php endforeach; else: ?>
					<p>该商品还没有评论!</p>
                    <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
				</ul>
			</div>
		</div>
        
        <script type="text/javascript">
            jQuery(function(jq) {
                function changeTab(lis, divs) {
                    lis.each(function(i) {
                        var els = jq(this);
                        els.click(function() {
                            lis.removeClass('current-tab');
                            divs.stop().hide().animate({'opacity': 0}, 0);
                            jq(this).addClass("current-tab");
                            divs.eq(i).show().animate({'opacity': 1}, 300);
                        });
                    });
                }
                var rrE = jq(".detail_con");
                changeTab(rrE.find(".ecmall-tab li"), rrE.find(".goods-info-detail-content"));

            });
        </script>
        
        <?php echo $this->fetch('footer.html'); ?>
    </body>
</html>
