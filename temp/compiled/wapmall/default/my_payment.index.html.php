<?php echo $this->fetch('member.header.html'); ?>
<?php echo $this->_var['_head_tags']; ?>

<style>
    .my_payment_index{border-radius: 5px;position: relative;border: #aaa solid 1px;background: #fff;overflow: hidden;color: #6b6b6b;margin: 0px 10px 0;font-size: 14px;margin-top:60px;}
</style>
<body class="gray" style="overflow-x:hidden;min-height:300px">
    <div class="w320">
        <div class="fixed">
            
            <div class="header header2">
                <a href="<?php echo url('app=default'); ?>"
                   class="back2_index"></a>
                支付方式管理
                <a href="<?php echo url('app=my_favorite'); ?>" class="bookmark"></a>
            </div>    
        </div>
        <div class="my_payment_index">
            <table style="width:100%;">
                <tr class="line_bold">
                    <th class="" colspan="6">
                    </th>
                </tr>
                <?php if ($this->_var['payments']): ?>
                <tr class="gray gray_new">
                    <th style="width:30%">名称</td>
                    <th style="width:40%">启用</th>
                    <th style="width:30%">操作</th>
                </tr>
                <?php endif; ?>
                <?php $_from = $this->_var['payments']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'payment');$this->_foreach['v'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['v']['total'] > 0):
    foreach ($_from AS $this->_var['payment']):
        $this->_foreach['v']['iteration']++;
?>
                <?php if (($this->_foreach['v']['iteration'] == $this->_foreach['v']['total'])): ?><tr class="line_bold"><?php else: ?><tr class="line"><?php endif; ?>
                    <td><span class="padding1"><?php echo htmlspecialchars($this->_var['payment']['name']); ?></span></td>
                    <td class="align2"><?php if ($this->_var['payment']['enabled']): ?>是<?php else: ?>否<?php endif; ?></td>
                    <td>
                        <div class="padding1">
                            <?php if ($this->_var['payment']['installed']): ?>
                            <a href="javascript:void(0);" ectype="dialog" uri="index.php?app=my_payment&amp;act=config&payment_id=<?php echo $this->_var['payment']['payment_id']; ?>&amp;code=<?php echo $this->_var['payment']['code']; ?>" dialog_id="my_payment_config" dialog_title="配置" dialog_width="100%" class="add2_ico">配置</a> <a href="javascript:drop_confirm('卸载后所有使用该支付方式的订单将无法支付，若您只是不希望让用户可以选择该支付方式，可以使用“配置”将该支付方式禁用，您确定要卸载它吗？', 'index.php?app=my_payment&amp;act=uninstall&payment_id=<?php echo $this->_var['payment']['payment_id']; ?>');" class="delete">卸载</a>
                            <?php else: ?>
                            <a href="javascript:void(0);" ectype="dialog" dialog_id="my_payment_install" dialog_title="安装" uri="index.php?app=my_payment&amp;act=install&code=<?php echo $this->_var['payment']['code']; ?>" dialog_width="100%" class="add1_ico">安装</a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="4" class="member_no_records">没有可用的支付方式，请联系管理员解决此问题</td>
                </tr>
                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
            </table>
            <iframe name="my_payment" style="display:none"></iframe>
        </div>
    </div>
</body>








<?php echo $this->fetch('member.footer.html'); ?>
