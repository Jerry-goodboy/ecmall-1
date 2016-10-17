<?php echo $this->fetch('member.header.html'); ?>
<div class="content">
    <div class="particular">
        <div class="particular_wrap">
            <p class="defeated">
                <span></span>
                <b style="float: left; width:380px;">您还不是卖家</b>
                <font style="clear: both; display:block; margin:0 0 0 50px;">
                        <a style="color:#aaa;" href="index.php?app=apply">>> 申请成为卖家</a><br />
                </font>
            </p>
        </div>
    </div>
</div>
<script type="text/javascript">
//<!CDATA[
<?php if ($this->_var['redirect']): ?>
window.setTimeout("<?php echo $this->_var['redirect']; ?>", 1000);
<?php endif; ?>
//]]>
</script>
<?php echo $this->fetch('footer.html'); ?>