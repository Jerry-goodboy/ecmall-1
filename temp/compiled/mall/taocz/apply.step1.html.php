<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo $this->_var['site_url']; ?>/" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_var['charset']; ?>" />
<?php echo $this->_var['page_seo']; ?>

<meta name="author" content="ecmall.shopex.cn" />
<meta name="generator" content="ECMall <?php echo $this->_var['ecmall_version']; ?>" />
<meta name="copyright" content="ShopEx Inc. All Rights Reserved" />

<link type="text/css" href="<?php echo $this->res_base . "/" . 'css/header.css'; ?>" rel="stylesheet" />
<link type="text/css" href="<?php echo $this->res_base . "/" . 'css/main.css'; ?>" rel="stylesheet"  />
<link type="text/css" href="<?php echo $this->res_base . "/" . 'css/footer.css'; ?>" rel="stylesheet" />

<script type="text/javascript" src="index.php?act=jslang"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'cart.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->res_base . "/" . 'js/main.js'; ?>" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'kissy/build/kissy.js'; ?>"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'kissy/build/switchable/switchable-pkg.js'; ?>"></script>

<!--[if lte IE 6]>
<script type="text/javascript" language="Javascript" src="<?php echo $this->res_base . "/" . 'js/hoverForIE6.js'; ?>"></script>
<![endif]-->

<script type="text/javascript">
//<!CDATA[
var SITE_URL = "<?php echo $this->_var['site_url']; ?>";
var REAL_SITE_URL = "<?php echo $this->_var['real_site_url']; ?>";
var PRICE_FORMAT = '<?php echo $this->_var['price_format']; ?>';
//]]>
</script>

<?php echo $this->_var['_head_tags']; ?>
<!--<editmode></editmode>-->
</head>
<body>
<div id="site-nav" class="w-full">
<div class="shoptop w clearfix">
      <div class="login_info">
         您好,
         <?php if (! $this->_var['visitor']['user_id']): ?>
         <?php echo htmlspecialchars($this->_var['visitor']['user_name']); ?>
         <a href="<?php echo url('app=member&act=login&ret_url=' . $this->_var['ret_url']. ''); ?>">登录</a>
         <a href="<?php echo url('app=member&act=register&ret_url=' . $this->_var['ret_url']. ''); ?>">注册</a>
         <?php else: ?>
         <a href="<?php echo url('app=member'); ?>"><span><?php echo htmlspecialchars($this->_var['visitor']['user_name']); ?></span></a>
         <a href="http://www.hnsjb.cn/web/usercenter.html">用户中心</a>
         <a href="<?php echo url('app=message&act=newpm'); ?>">站内消息<?php if ($this->_var['new_message']): ?>(<span><?php echo $this->_var['new_message']; ?></span>)<?php endif; ?></a>
         <?php endif; ?>
      </div>
</div>
<div id="header" class="w-full">
	<div class="shop-t w clearfix pb10 mb5 mt5">
      <div class="logo mt10">
         <img alt="<?php echo $this->_var['site_title']; ?>" src="<?php echo $this->_var['site_logo']; ?>" />
      </div>
      <div class="top-search">
      </div>
   </div>
    <div class="w-full mall-nav">
    	<ul class="w clearfix">
    	</ul>
    </div>
</div>

<div id="main" class="w-full">
<div id="page-apply" class="w mt10 mb20">
   <div class="title padding5 strong fs14">
      我要开店
   </div>
   <div class="content border padding10 border-t-0 linehei20">
      <?php $_from = $this->_var['sgrades']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'sgrade');$this->_foreach['fe_sgrade'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_sgrade']['total'] > 0):
    foreach ($_from AS $this->_var['sgrade']):
        $this->_foreach['fe_sgrade']['iteration']++;
?>
      <dl class="clearfix mb10" <?php if (($this->_foreach['fe_sgrade']['iteration'] == $this->_foreach['fe_sgrade']['total'])): ?> style="border-bottom:0;"<?php endif; ?>>
         <dt class="float-left strong"><?php echo $this->_var['sgrade']['grade_name']; ?></dt>
         <dd class="float-left">
            <p>商品数：<span><?php echo $this->_var['sgrade']['goods_limit']; ?></span></p>
            <p>上传空间(MB)：<span><?php echo $this->_var['sgrade']['space_limit']; ?></span></p>
            <p>模板数：<span><?php echo $this->_var['sgrade']['skin_limit']; ?></span></p>
            <p>收费标准：<span><?php echo $this->_var['sgrade']['charge']; ?></span></p>
            <p>需要审核：<span><?php if ($this->_var['sgrade']['need_confirm']): ?>是<?php else: ?>否<?php endif; ?></span></p>
         </dd>
         <dd class="float-left">
            <p>附加功能：</p>
            <p>
               <?php $_from = $this->_var['sgrade']['functions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('k', 'functions');$this->_foreach['v'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['v']['total'] > 0):
    foreach ($_from AS $this->_var['k'] => $this->_var['functions']):
        $this->_foreach['v']['iteration']++;
?>
               <?php if ($this->_var['domain'] && $this->_var['k'] == 'subdomain'): ?>
               <span>二级域名</span>
               <?php else: ?>
               <span><?php echo $this->_var['lang'][$this->_var['k']]; ?></span>
               <?php endif; ?>
               <?php if (! ($this->_foreach['v']['iteration'] == $this->_foreach['v']['total'])): ?>
               <?php endif; ?>
               <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </p>
         </dd>
         <dd class="float-left"><?php echo $this->_var['sgrade']['description']; ?></dd>
         <dd class="float-left"><a href="<?php echo url('app=apply&step=2&id=' . $this->_var['sgrade']['grade_id']. ''); ?>" class="btn-apply fs14 strong fff center">立即开店</a></dd>
      </dl>
      <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
   </div>
</div>
</div>
<div id="footer" class="w-full">
   <div class="foot-copyright">Copyright &copy; 2011-2014 河南手机报 版权所有 <?php if ($this->_var['icp_number']): ?><?php echo $this->_var['icp_number']; ?><?php endif; ?> <?php echo $this->_var['statistics_code']; ?></div>
</div>
    
    
</div>
</body>
</html>
