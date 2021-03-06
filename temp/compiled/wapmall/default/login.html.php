<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />
        <?php echo $this->_var['page_seo']; ?>
        <link href="<?php echo $this->res_base . "/" . 'css/common.css'; ?>" type="text/css" rel="stylesheet" />
        <link href="<?php echo $this->res_base . "/" . 'css/wxlog-reg.css'; ?>" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="index.php?act=jslang"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.js'; ?>" charset="utf-8"></script>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
        <?php echo $this->_var['_head_tags']; ?>
    </head>

    <script type="text/javascript">
        $(function() {
            $('#login_form').validate({
                errorPlacement: function(error, element) {
                    $(element).parent('td').append(error);
                },
                success: function(label) {
                    label.addClass('validate_right').text('OK!');
                },
                onkeyup: false,
                rules: {
                    user_name: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    captcha: {
                        required: true,
                        remote: {
                            url: 'index.php?app=captcha&act=check_captcha',
                            type: 'get',
                            data: {
                                captcha: function() {
                                    return $('#captcha1').val();
                                }
                            }
                        }
                    }
                },
                messages: {
                    user_name: {
                        required: 'user_name_required'
                    },
                    password: {
                        required: 'password_required'
                    },
                    captcha: {
                        required: 'captcha_required',
                        remote: 'captcha_error'
                    }
                }
            });
        });
    </script>
    <body id="log-reg" class="gray">
<div class="w320">
        <div class="fixed">
            <div class="header header2">
                <a href="javascript:history.back(-1);" class="back2_pre"></a>
                用户登入
            </div>  
        </div>

        
        <div class="login_panel">
            <form class="login_box"  method="post" id="login_form">
                <h2>登录微信商城</h2>
                <table width="100%">
                    <tr>
                        <td>
                            <input placeholder="用户名" type="text" name="user_name" class="text"/>
                        </td>
                    </tr>
                    <tr> <td><label class="field_message"><span class="field_notice"></span></label></td></tr>
                    <tr><td><input placeholder="密 码" type="password" name="password"  class="text"/> </td> </tr>
                    <tr> <td> <input  value="登录" name="Submit"  type="submit" class=" red_btn sub_btn"/></td></tr>
                    <input type="hidden" name="ret_url" value="<?php echo $this->_var['ret_url']; ?>" />
                </table>
                <p>还不是会员？<a href="<?php echo url('app=member&act=register&ret_url=' . $this->_var['ret_url']. ''); ?>" >立即注册</a></p>
            </form>
        </div>
</div>


    </body>
</html>
