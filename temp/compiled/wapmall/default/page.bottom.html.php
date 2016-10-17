
	
<?php if ($this->_var['page_info']['page_count'] > 1): ?>	
	
	<div class="goods-list-pager">
		 <?php if ($this->_var['page_info']['prev_link']): ?>
			<a class="pager-navigation pager-prev" href="<?php echo $this->_var['page_info']['prev_link']; ?>">上一页</a>
			<?php endif; ?>
			<div class="pager-number"><span><?php echo $this->_var['page_info']['curr_page']; ?></span>/<span><?php echo $this->_var['page_info']['page_count']; ?></span></div>
			 <?php if ($this->_var['page_info']['next_link']): ?>
			<a class="pager-navigation pager-next" href="<?php echo $this->_var['page_info']['next_link']; ?>">下一页</a>
			<?php endif; ?>
		</div>
<?php endif; ?>