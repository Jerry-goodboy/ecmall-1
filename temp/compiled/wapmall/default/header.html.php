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
        <div class="tmall-h5-v2">
            <header class="region"> 
                <div class="content"> 
                    <div class="top"> <h1> <a href="<?php echo url('app=default'); ?>"><img alt="<?php echo $this->_var['site_title']; ?>" src="<?php echo $this->_var['site_logo']; ?>"  width="144px"/></a></h1> </div> 
                    <div id="fake-search"> 
                        <form method="GET" action="<?php echo url('app=search'); ?>">
                            <div class="fakeInput">
                                <input class="text" name="keyword" style="color:silver"/>
                                <input type="submit" value="" class="search-button" />
                                <input type="hidden" name="app" value="search" />
                                <input type="hidden" name="act" value="index" />
                            </div> 
                        </form>
                    </div> 
                </div> 
            </header>
            <div class="region entry-list"> 
                <a href="<?php echo url('app=category'); ?>"><img alt="商品类目" height="29px" src="<?php echo $this->res_base . "/" . 'images/ico1.png'; ?>" width="29px"/><br/><span>商品类目</span></a><span class="entry-decollator"></span>
                <a href="<?php echo url('app=category&act=store'); ?>"><img alt="店铺类目" height="30px" src="<?php echo $this->res_base . "/" . 'images/ico2.png'; ?>" width="30px"/><br/> <span>店铺类目</span> </a><span class="entry-decollator"></span>   
                <a href="<?php echo url('app=member'); ?>"><img alt="个人中心" height="31px" src="<?php echo $this->res_base . "/" . 'images/ico3.png'; ?>" width="31px"/><br/> <span>个人中心</span> </a><span class="entry-decollator"></span>
                <a href="<?php echo url('app=cart'); ?>"><img alt="购物车" height="31px" src="<?php echo $this->res_base . "/" . 'images/ico4.png'; ?>" width="37px"/><br/><span>购物车</span></a>

                     
                <br/>
                <a href="<?php echo url('app=buyer_order'); ?>"><img alt="订单管理" height="32px" src="<?php echo $this->res_base . "/" . 'images/ico5.png'; ?>" width="22px"/><br/><span>订单管理</span></a><span class="entry-decollator"></span>
                <a href="#"><img alt="活动促销" height="19px" src="<?php echo $this->res_base . "/" . 'images/ico6.png'; ?>" style="margin-top: 8px;margin-bottom: 8px;" width="44px"/><br/><span>活动促销</span></a><span class="entry-decollator"></span>     
                <a href="<?php echo url('app=my_favorite'); ?>"><img alt="收藏夹" height="30px" src="<?php echo $this->res_base . "/" . 'images/ico7.png'; ?>" width="34px"/><br/><span>收藏夹</span></a><span class="entry-decollator"></span>
                <a href="http://m.kuaidi100.com/uc/index.html"><img alt="查物流" height="28px" src="<?php echo $this->res_base . "/" . 'images/ico8.png'; ?>"  width="34px"/><br/><span>查物流</span></a><span class="entry-decollator"></span>
            </div>