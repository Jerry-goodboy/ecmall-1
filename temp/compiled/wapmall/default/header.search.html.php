<div class="header-search">
	<div class="header-search-inner">
		<div class="search-cate-btn">商品</div>
		<div class="search-cate-area">
			<a href="javascript:void(0);" data-stype="index" class="search-link goods-link">商品</a>
			<a href="javascript:void(0);" data-stype="store" class="search-link shop-link">店铺</a>
		</div>
		<form method="GET" action="<?php echo url('app=search'); ?>">
	                            <div class="fakeInput">
	                                <input class="text" name="keyword" style="color:silver"/>
	                                <input type="submit" value="" class="search-button" />
	                                <input type="hidden" name="app" value="search" />
	                                <input type="hidden" name="act" value="index" />
	                            </div> 
	                        </form>
	</div>
	<div class="header-search-cancel">取消</div>
		
</div>
<a href="javascript:void(0)" class="search-btn"></a>

<script type="text/javascript">
	$(function() {
		$('.search-btn').click(function() {
			$('.header-search').show();
		});
		$('.search-cate-btn').click(function() {
			$('.search-cate-area').toggle();
		})
		$('.search-link').click(function() {
			$('input[name="act"]').val($(this).attr('data-stype'));
			$('.search-cate-btn').text($(this).text());
			$('.search-cate-area').hide();
		})
		$('.header-search-cancel').click(function() {
			$('.search-cate-area').hide();
			$('.header-search').hide();
		})
		
	})
</script>