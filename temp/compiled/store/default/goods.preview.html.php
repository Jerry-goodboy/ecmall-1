
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


        
     	<div style="width: 375px;margin: auto;">
     		<?php echo $this->fetch('goodspreviewinfo.html'); ?>
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
