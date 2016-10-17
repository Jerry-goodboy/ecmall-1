
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
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'mlselection.js'; ?>" charset="utf-8"></script>
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
<script type="text/javascript">
//<!CDATA[
$(function(){
    regionInit("region");
    $('#address_form').validate({
        /*errorPlacement: function(error, element){
            var _message_box = $(element).parent().find('.field_message');
            _message_box.find('.field_notice').hide();
            _message_box.append(error);
        },
        success       : function(label){
            label.addClass('validate_right').text('OK!');
        },*/
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
           var errors = validator.numberOfInvalids();
           if(errors)
           {
               $('#warning').show();
           }
           else
           {
               $('#warning').hide();
           }
        },
        onkeyup : false,
        rules : {
            consignee : {
                required : true
            },
            region_id : {
                required : true,
                min   : 1
            },
            address   : {
                required : true
            },
            phone_tel : {
                required : check_phone,
                minlength:6,
                checkTel:true
            },
            phone_mob : {
                required : check_phone,
                minlength:6,
                digits : true
            }
        },
        messages : {
            consignee : {
                required : '请填写收货人姓名. '
            },
            region_id : {
                required : '请选择所在地区. ',
                min  : '请选择所在地区. '
            },
            address   : {
                required : '请填写详细地址. '
            },
            phone_tel : {
                required : '固定电话和手机请至少填写一项. ',
                minlength: '电话号码由数字、加号、减号、空格、括号组成,并不能少于6位. ',
                checkTel: '电话号码由数字、加号、减号、空格、括号组成,并不能少于6位. '
            },
            phone_mob : {
                required : '固定电话和手机请至少填写一项. ',
                minlength: '错误的手机号码,只能是数字,并且不能少于6位. ',
                digits : '错误的手机号码,只能是数字,并且不能少于6位. '
            }
        },
        groups:{
            phone:'phone_tel phone_mob'
        }
    });
});
function check_phone(){
    return ($('[name="phone_tel"]').val() == '' && $('[name="phone_mob"]').val() == '');
}
function hide_error(){
	
    $('#region').find('.error').hide();
}
//]]>
</script>
<div class="consignee-page">
	<div class="consignee-edit-area">
 <form method="post" action="index.php?app=my_address&act=<?php echo $this->_var['act']; ?>&addr_id=<?php echo $this->_var['address']['addr_id']; ?>" id="address_form">
    	
    	<div class="consignee-input-item" style="padding-top: 20px;">
			<input  name="consignee" value="<?php echo htmlspecialchars($this->_var['address']['consignee']); ?>" type="text" placeholder="请填写你的真实姓名"/>
		</div>
		<div class="consignee-input-item">
			<input type="number" pattern="[0-9]*" name="phone_tel" value="<?php echo $this->_var['address']['phone_tel']; ?>" placeholder="请填写你的手机号码" />
		</div>
		<div class="consignee-input-item">
			<input type="text" disabled="disabled" name="province" value="河南省" />
		</div>
		<div class="consignee-input-item" id="region" style="padding-top: 0;">
			<input type="hidden" class="mls_id" name="region_id" id="region_id" value="<?php echo $this->_var['address']['region_id']; ?>"/>
			<input type="hidden" name="region_name" class="mls_names" id="region_name" value="<?php echo htmlspecialchars($this->_var['address']['region_name']); ?>"/>
			<?php if ($this->_var['address']['region_id']): ?>
                        <span><?php echo htmlspecialchars($this->_var['address']['region_name']); ?></span>
                        <input type="button" value="编辑" class="edit_region" style="position: absolute;color: #DF0507;width: 2em;right: 16px;" />
                        <select style="display:none;" onchange="hide_error();">
                          <option>请选择...</option>
                          <?php echo $this->html_options(array('options'=>$this->_var['regions'])); ?>
                        </select>
                        <?php else: ?>
                        <select onchange="hide_error();">
                          <option>--请选择地区--</option>
                          <?php echo $this->html_options(array('options'=>$this->_var['regions'])); ?>
                        </select>
                        <?php endif; ?>
		</div>
        <div class="consignee-input-item">
			<input type="text" name="address" value="<?php echo htmlspecialchars($this->_var['address']['address']); ?>" placeholder="请填写详细地址" />
		</div>
        <p>
        <input type="submit" class="ecmall-btn ecmall-confirm-btn" style="margin-bottom: 60px;" value="<?php if ($this->_var['address']['addr_id']): ?>编辑地址<?php else: ?>新增地址<?php endif; ?>" />
        </p>
 </form>
  </div>
 </div>
 <?php echo $this->fetch('member.footer.html'); ?>