<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<script type="text/javascript">
$(function(){
    $('#confirm_cancel').click(function(){
        DialogManager.close('buyer_order_confirm_order');
    });
});
</script>
<div class="msg">
<div id="warning"></div>
<form action="index.php?app=buyer_order&act=confirm_order&order_id=<?php echo $this->_var['order']['order_id']; ?>&ajax" method="post" target="iframe_post">
	<div class="content-above">
		<p>您是否确已经收到以下订单的货品？</p>
	    <p>订单号:<span><?php echo $this->_var['order']['order_sn']; ?></span></p>
	    <p>注意&nbsp;:&nbsp;如果你尚未收到货品请不要点击“确认”。大部分被骗案件都是由于提前确认付款被骗的，请谨慎操作！ </span></p>
	</div>
	    
        <input type="submit" id="confirm_yes"  value="确认" />
        <input type="button" id="confirm_cancel"  value="取消" />
</form>
</div>

