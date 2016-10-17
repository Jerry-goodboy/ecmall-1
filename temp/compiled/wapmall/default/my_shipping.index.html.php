<?php echo $this->fetch('member.header.html'); ?>
<?php echo $this->_var['_head_tags']; ?>

<style>
    .my_shipping_index{border-radius: 5px;position: relative;border: #aaa solid 1px;background: #fff;overflow: hidden;color: #6b6b6b;margin: 0px 10px 0;font-size: 14px;margin-top:60px;}
    .my_shipping_index td{text-align: center}
    .my_shipping_index a{margin-right: 10px;}
    .add_shipping{margin: 0 auto;width: 43%;}
    .add_shipping a {padding: 5px 2%;margin: 10px 2% 0 0;width: 100%;text-align: center;display: inline-block;cursor: pointer;text-align: center;}
</style>
<body class="gray" style="overflow-x:hidden;min-height:300px">
    <div class="w320">
        <div class="fixed">
            
            <div class="header header2">
                <a href="<?php echo url('app=default'); ?>"
                   class="back2_index"></a>
                配送方式管理
                <a href="<?php echo url('app=my_favorite'); ?>" class="bookmark"></a>
            </div>    
        </div>
        <script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'jquery.plugins/jquery.validate.js'; ?>" charset="<?php echo $this->_var['charset']; ?>"></script>
        <div class="my_shipping_index" >
            <table style="width:100%;">
                <?php if ($this->_var['shippings']): ?>
                <tr class="line_bold">
                    <th class="" colspan="6">
                    </th>
                </tr>

                <tr class="gray">
                    <th style="width:20%;text-align: center">名称</th>
                    <th style="width:20%">首件邮费</th>
                    <th style="width:20%">附加邮费</th>
                    <th style="width:10%">启用</th>
                    <th style="width:30%">操作</th>
                </tr>
                <?php endif; ?>
                <?php $_from = $this->_var['shippings']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'shipping');$this->_foreach['v'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['v']['total'] > 0):
    foreach ($_from AS $this->_var['shipping']):
        $this->_foreach['v']['iteration']++;
?>
                <tr <?php if (($this->_foreach['v']['iteration'] == $this->_foreach['v']['total'])): ?>class="line_bold"<?php else: ?>class="line"<?php endif; ?>>
                    <td><span><?php echo htmlspecialchars($this->_var['shipping']['shipping_name']); ?></span></td>
                    <td><?php echo $this->_var['shipping']['first_price']; ?></td>
                    <td><?php echo $this->_var['shipping']['step_price']; ?></td>
                    <td><?php if ($this->_var['shipping']['enabled']): ?>是<?php else: ?>否<?php endif; ?></td>
                    <td>
                        <div>
                            <a href="javascript:void(0);" uri="index.php?app=my_shipping&amp;act=edit&shipping_id=<?php echo $this->_var['shipping']['shipping_id']; ?>" ectype="dialog" dialog_id="my_shipping_edit" dialog_width="100%" dialog_title="编辑" class="edit1">编辑</a><a href="javascript:drop_confirm('您确定要删除它吗？', 'index.php?app=my_shipping&amp;act=drop&shipping_id=<?php echo $this->_var['shipping']['shipping_id']; ?>');" class="delete">删除</a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="6" class="member_no_records padding6">您没有添加配送方式</td>
                </tr>
                <?php endif; unset($_from); ?><?php $this->pop_vars();; ?>
                <iframe name="my_shipping" style="display:none" ></iframe>
            </table>
        </div>
        <div class="add_shipping">
            <a class="white_btn" ectype="dialog" uri="index.php?app=my_shipping&amp;act=add" ectype="dialog" dialog_id="my_shipping_add" dialog_width="100%" dialog_title="新增配送方式">新增配送方式</a>
        </div>
    </div>
</body>

<?php echo $this->fetch('member.footer.html'); ?>
