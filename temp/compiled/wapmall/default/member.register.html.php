
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
    //注册表单验证
        $(function() {
            $('#register_form').validate({
                errorPlacement: function(error, element) {
                    var error_td = element.parent('td');
                    error_td.find('.field_notice').hide();
                    error_td.append(error);
                },
                success: function(label) {
                    label.addClass('validate_right').text('OK!');
                },
                onkeyup: false,
                rules: {
                    user_name: {
                        required: true,
                        byteRange: [3, 15, '<?php echo $this->_var['charset']; ?>'],
                        remote: {
                            url: 'index.php?app=member&act=check_user&ajax=1',
                            type: 'get',
                            data: {
                                user_name: function() {
                                    return $('#user_name').val();
                                }
                            },
                            beforeSend: function() {
                                var _checking = $('#checking_user');
                                _checking.prev('.field_notice').hide();
                                _checking.next('label').hide();
                                $(_checking).show();
                            },
                            complete: function() {
                                $('#checking_user').hide();
                            }
                        }
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    password_confirm: {
                        required: true,
                        equalTo: '#password'
                    },
                    email: {
                        required: true,
                        email: true
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
                    },
                    agree: {
                        required: true
                    }
                },
                messages: {
                    user_name: {
                        required: '您必须提供一个用户名',
                        byteRange: '用户名必须在3-15个字符之间',
                        remote: '您提供的用户名已存在'
                    },
                    password: {
                        required: '您必须提供一个密码',
                        minlength: '密码长度应在6-20个字符之间'
                    },
                    password_confirm: {
                        required: '您必须再次确认您的密码',
                        equalTo: '两次输入的密码不一致'
                    },
                    email: {
                        required: '您必须提供您的电子邮箱',
                        email: '这不是一个有效的电子邮箱'
                    },
                    captcha: {
                        required: '请输入右侧图片中的文字',
                        remote: '验证码错误'
                    },
                    agree: {
                        required: '您必须阅读并同意该协议,否则无法注册'
                    }
                }
            });
        });
    </script>

    <body id="log-reg" class="gray">
        <div class="fixed">
            <div class="header header2">
                <a href="javascript:history.back(-1);" class="back2_pre"></a>
                用户注册
            </div>  
        </div>

        
        <div class="login_panel" >
            <form class="login_box" id="register_form" method="post">
                <h2>填写注册信息</h2>
                <table  width="100%">
                    <tr>
                        <td> <input placeholder="用户名" type="text" name="user_name" class="text">
                                <label class="field_notice"></label></td>
                    </tr>
                    <tr>  
                        <td> <input placeholder="密 码"  id="password" name="password" type="password"  class="text">  
                                <label class="field_notice"></label></td>
                    </tr>
                    <tr> 
                        <td>  
                            <input placeholder="确认密码"   name="password_confirm" type="password"  class="text">  
                                <label class="field_notice"></label></td>
                    </tr>
                    <tr>
                        <td>电子邮箱:<input type="text" name="email" class="text width10" /><label class="field_notice">请输入一个有效的电子邮箱地址</label></td>
                    </tr>
                    <?php if ($this->_var['captcha']): ?>
                    <tr>
                        <td>验证码:<input type="text" name="captcha" class="text" id="captcha1" />
                            <a href="javascript:change_captcha($('#captcha'));" class="renewedly"><img id="captcha" src="index.php?app=captcha&amp;<?php echo $this->_var['random_number']; ?>" /></a><label class="field_notice">请输入图片中的文字,点击图片以更换</label></td>
                    </tr>
                    <?php endif; ?>

                    <tr>
                        <td>
                            <input  value="立即注册"  type="submit" class=" red_btn sub_btn">
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="ret_url" value="<?php echo $this->_var['ret_url']; ?>" />
                <p>已有账号？<a href="<?php echo url('app=member&act=login&ret_url=' . $this->_var['ret_url']. ''); ?>" >直接登录</a></p>
                <input type="hidden"  checked name="agree" value="1" value="1" > 
            </form>
        </div>



    </body>
</html>
