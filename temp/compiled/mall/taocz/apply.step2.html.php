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

<script src="<?php echo $this->lib_base . "/" . 'mlselection.js'; ?>" charset="utf-8"></script>
<script src="<?php echo $this->lib_base . "/" . 'jquery.plugins/jquery.validate.js'; ?>" charset="utf-8"></script>
<style type="text/css">
.d_inline{display:inline;}
</style>
<div class="content">
<script type="text/javascript">
//<!CDATA[
var SITE_URL = "<?php echo $this->_var['site_url']; ?>";
var REAL_SITE_URL = "<?php echo $this->_var['real_site_url']; ?>";
$(function(){
    regionInit("region");

    $("#apply_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parents('td').next('td');
            error_td.find('.field_notice').hide();
            error_td.find('.fontColor3').hide();
            error_td.append(error);
        },
        success: function(label){
            label.addClass('validate_right').text('OK!');
        },
        onkeyup: false,
        rules: {
            owner_name: {
                required: true
            },
            store_name: {
                required: true,
                remote : {
                    url  : 'index.php?app=apply&act=check_name&ajax=1',
                    type : 'get',
                    data : {
                        store_name : function(){
                            return $('#store_name').val();
                        },
                        store_id : '<?php echo $this->_var['store']['store_id']; ?>'
                    }
                },
                maxlength: 20
            },
            tel: {
                required: true,
                minlength:6,
                checkTel:true
            },
            image_1: {
                accept: "jpg|jpeg|png|gif"
            },
            image_2: {
                accept: "jpg|jpeg|png|gif"
            },
            image_3: {
                accept: "jpg|jpeg|png|gif"
            },
            notice: {
                required : true
            }
        },
        messages: {
            owner_name: {
                required: '请输入店主姓名'
            },
            store_name: {
                required: '请输入店铺名称',
                remote: '该店铺名称已存在，请您换一个',
                maxlength: '请控制在20个字以内'
            },
            tel: {
                required: '请输入联系电话',
                minlength: '电话号码由数字、加号、减号、空格、括号组成,并不能少于6位',
                checkTel: '电话号码由数字、加号、减号、空格、括号组成,并不能少于6位'
            },
            image_1: {
                accept: '请上传格式为 jpg,jpeg,png,gif 的文件'
            },
            image_2: {
                accept: '请上传格式为 jpg,jpeg,png,gif 的文件'
            },
            image_3: {
                accept: '请上传格式为 jpg,jpeg,png,gif 的文件'
            },
            notice: {
                required: '请阅读并同意开店协议'
            }
        }
    });
});
//]]>
</script>
<div id="main" class="w-full">
<div id="page-apply" class="w mt10 mb20">
   <div class="title border padding5 fs14 strong">
      我要开店
   </div>
   <div class="content border border-t-0 padding10 apply2">
      <form method="post" enctype="multipart/form-data" id="apply_form">
         <table>
           <tr>
              <th>店主姓名：</th>
              <td class="width7"><input type="text" class="input border" name="owner_name" value="<?php echo htmlspecialchars($this->_var['store']['owner_name']); ?>"/></td>
              <td class="padding3"><span class="fontColor3">*</span> <span class="field_notice">请填写真实姓名</span></td>
           </tr>
           <tr>
              <th>身份证号：</th>
              <td><input type="text" class="input border" name="owner_card" value="<?php echo htmlspecialchars($this->_var['store']['owner_card']); ?>" /></td>
              <td class="padding3"> <span class="field_notice">请填写真实准确的身份证号</span></td>
           </tr>
           <tr>
              <th>店铺名称：</th>
              <td><input type="text" class="input border" name="store_name" id="store_name" value="<?php echo htmlspecialchars($this->_var['store']['store_name']); ?>"/></td>
              <td class="padding3"><span class="fontColor3">*</span> <span class="field_notice">请控制在20个字以内</span></td>
           </tr>
           <tr>
              <th>所属分类：</th>
              <td>
                 <div class="select_add">
                    <select name="cate_id">
                       <option value="0">请选择...</option>
                       <?php echo $this->html_options(array('options'=>$this->_var['scategories'],'selected'=>$this->_var['scategory']['cate_id'])); ?>
                    </select>
                 </div>
              </td>
              <td></td>
           </tr>
           <tr>
              <th>所在地区：</th>
              <td>
                  <div class="select_add" id="region" style="width:500px;">
                      <input type="hidden" name="region_id" value="<?php echo $this->_var['store']['region_id']; ?>" class="mls_id" />
                      <input type="hidden" name="region_name" value="<?php echo $this->_var['store']['region_name']; ?>" class="mls_names" />
                      <?php if ($this->_var['store']['region_name']): ?>
                      <span><?php echo htmlspecialchars($this->_var['store']['region_name']); ?></span>
                      <input type="button" value="编辑" class="edit_region" />
                      <?php endif; ?>
                      <select class="d_inline"<?php if ($this->_var['store']['region_name']): ?> style="display:none;"<?php endif; ?>>
                         <option value="0">请选择...</option>
                         <?php echo $this->html_options(array('options'=>$this->_var['regions'])); ?>
                      </select>
                   </div>
               </td>
               <td></td>
            </tr>
            <tr>
                <th>详细地址：</th>
                <td><input type="text" class="input border" name="address" value="<?php echo htmlspecialchars($this->_var['store']['address']); ?>"/></td>
                <td></td>
            </tr>
            <tr>
                <th>邮政编码：</th>
                <td><input type="text" class="input border" name="zipcode" value="<?php echo htmlspecialchars($this->_var['store']['zipcode']); ?>"/></td>
                <td></td>
             </tr>
             <tr>
                 <th>联系电话：</th>
                 <td>
                     <input type="text" class="input border" name="tel"  value="<?php echo htmlspecialchars($this->_var['store']['tel']); ?>"/>
                 </td>
                <td class="padding3"><span class="fontColor3">*</span> <span class="field_notice">请输入联系电话</span></td>
              </tr>
              <tr>
                 <th>上传证件：</th>
                 <td><input type="file" name="image_1" />
                       <?php if ($this->_var['store']['image_1']): ?><p class="d_inline"><a href="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['store']['image_1']; ?>" target="_blank">查看</a></p><?php endif; ?>
                 </td>
                 <td><span class="field_notice">支持格式jpg,jpeg,png,gif，请保证图片清晰且文件大小不超过400KB</span></td>
              </tr>
              <tr>
                 <th>上传执照：</th>
                 <td><input type="file" name="image_2" />
                     <?php if ($this->_var['store']['image_2']): ?><p class="d_inline"><a href="<?php echo $this->_var['site_url']; ?>/<?php echo $this->_var['store']['image_2']; ?>" target="_blank">查看</a></p><?php endif; ?>
                 </td>
                 <td><span class="field_notice">支持格式jpg,jpeg,png,gif，请保证图片清晰且文件大小不超过400KB</span></td>
              </tr>
              <tr>
                 <td colspan="2" class="warning"><p><input type="checkbox"<?php if ($this->_var['store']): ?> checked="checked"<?php endif; ?> name="notice" value="1" id="warning" /> <label for="warning">我已认真阅读并完全同意<a href="index.php?app=article&act=system&code=setup_store" target="_blank">开店协议</a>中的所有条款</label></p></td>
                 <td class="padding3"></td>
              </tr>
              <tr>
                  <td colspan="3"><input class="btn-apply border0 fs14 strong fff pointer" type="submit" value="提交" /></td>
              </tr>
           </table>
       </form>
    </div>
</div>
</div>
<div id="footer" class="w-full">
   <div class="foot-copyright">Copyright &copy; 2011-2014 河南手机报 版权所有 <?php if ($this->_var['icp_number']): ?><?php echo $this->_var['icp_number']; ?><?php endif; ?> <?php echo $this->_var['statistics_code']; ?></div>
</div>
    
    
</div>
<script>
function seller_logout() {
	if (confirm('您确定要退出登录么？')) {
		/*$.post('http://www.hnsjb.cn/index.php?m=v2_member&c=index&a=logout').success(function(obj) {
			obj = JSON.parse(obj);
			
		});*/
		$.post('http://www.hnsjb.cn/index.php?m=v2_member&c=index&a=logout', 
				{},
				function(obj){
					obj = JSON.parse(obj);
					if (obj.status == 1) {
						var oBody = document.getElementsByTagName('body').item(0);
					var oScript = document.createElement("script");
					var oScript2 = document.createElement("script");
					oScript.type = "text/javascript";
					oScript2.type = "text/javascript";
					var src1 = obj.script.match(/<script[^>]*?>[\s\S]*?<\/script>/i)[0];
					var src2 = obj.script.replace(src1,'');
					oScript.src = $(src1).attr("src");
					oScript2.src = $(src2).attr("src");
					oBody.appendChild(oScript2);
					oBody.appendChild(oScript);
				    	oBody.appendChild( oScript);
						oScript.onload = function() {
							window.location.href = 'http://www.hnsjb.cn/web/sellerapply.html';
						}
					}
				});
	} else {
		return false;
	}
}
</script>
</body>
</html>

