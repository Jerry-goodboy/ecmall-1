

<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'goodsinfo.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo $this->lib_base . "/" . 'ecmall.js'; ?>" charset="utf-8"></script>
<script type="text/javascript" src="newstatic/js/yxMobileSlider.js"></script>
<script type="text/javascript">
//<!CDATA[
    /* buy */
    function buy()
    {
    	
    	if (mygetCookie('user_info') != '') {
    		if (goodsspec.getSpec() == null)
	        {
	            alert(lang.select_specs);
	            return;
	        }
	        var spec_id = goodsspec.getSpec().id;
	
	        var quantity = $("#quantity").val();
	        if (quantity == '')
	        {
	            alert(lang.input_quantity);
	            return;
	        }
	        if (parseInt(quantity) < 1)
	        {
	            alert(lang.invalid_quantity);
	            return;
	        }
	
	        $.post("<?php echo $this->_var['site_url']; ?>/index.php?app=goods&act=check_goods_limit",
					{
						spec_id: spec_id,
						quantity: quantity
					},
					function(result){
							if(result == 1)
							{
								add_to_cart(spec_id, quantity);
							}
							else if(result == -2)
							{
								alert("请登录");
								return false;
							}
							else
							{
								alert("购买该商品数量超过限制");
								return false;   
							}	 
						}
					)
	    } else {
	    	alert('请登录');
	    	mysetCookie('unlogstatus',true);
	    	mysetCookie('unlogurl',window.location.href);
	    	window.location.href = 'http://www.hnsjb.cn/usercenter/login';
	    }
	             
    }

    /* add cart */
    function add_to_cart(spec_id, quantity)
    {
        var url = '<?php echo $this->_var['site_url']; ?>/index.php?app=cart&act=add';

        $.getJSON(url, {'spec_id': spec_id, 'quantity': quantity}, function(data) {
            if (data.done)
            {
                $('.bold_num').text(data.retval.cart.kinds);
                $('.bold_mly').html(price_format(data.retval.cart.amount));
                $(".msg").slideDown().delay(5000).slideUp();
                // $('.msg').slideDown('slow');
                // setTimeout(slideUp_fn, 5000);
            }
            else
            {
                alert(data.msg);
            }
        });
    }

    function to_shop()
    {
    	if (mygetCookie('user_info') != '') {
    		if (goodsspec.getSpec() == null)
	        {
	            alert(lang.select_specs);
	            return;
	        }
	        var spec_id = goodsspec.getSpec().id;
	
	        var quantity = $("#quantity").val();
	        if (quantity == '')
	        {
	            alert(lang.input_quantity);
	            return;
	        }
	        if (parseInt(quantity) < 1)
	        {
	            alert(lang.invalid_quantity);
	            return;
	        }
			$.post("<?php echo $this->_var['site_url']; ?>/index.php?app=goods&act=check_goods_limit",
					{
						spec_id: spec_id,
						quantity: quantity
					},
					function(result){
							if(result == 1)
							{
								add_to_shop(spec_id, quantity);
							}
							else
							{
								alert("购买该商品数量超过限制");
								return false;   
							}	 
						}
					)
    } else {
    	alert('请登录');
	    mysetCookie('unlogstatus',true);
	    mysetCookie('unlogurl',window.location.href);
	    window.location.href = 'http://www.hnsjb.cn/usercenter/login';
    }
    	
	        

    }
    function add_to_shop(spec_id, quantity)
    {
        var url = '<?php echo $this->_var['site_url']; ?>/index.php?app=cart&act=to_shop';

        $.getJSON(url, {'spec_id': spec_id, 'quantity': quantity}, function(data) {
            if (data.done)
            {
                window.location.href = 'index.php?app=cart';
                // $('.bold_num').text(data.retval.cart.kinds);
                // $('.bold_mly').html(price_format(data.retval.cart.amount));
                // $(".buynow .msg").slideDown().delay(5000).slideUp();
            }
            else
            {
                alert(data.msg);
            }
        });
    }



var specs = new Array();
<?php $_from = $this->_var['goods']['_specs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'spec');if (count($_from)):
    foreach ($_from AS $this->_var['spec']):
?>
specs.push(new spec(<?php echo $this->_var['spec']['spec_id']; ?>, '<?php echo htmlspecialchars($this->_var['spec']['spec_1']); ?>', '<?php echo htmlspecialchars($this->_var['spec']['spec_2']); ?>', '<?php if ($this->_var['goods']['if_credit'] == 1): ?><?php echo $this->_var['spec']['credit']; ?><?php else: ?><?php echo $this->_var['spec']['price']; ?><?php endif; ?>', <?php echo $this->_var['spec']['stock']; ?>));
<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
var specQty = <?php echo $this->_var['goods']['spec_qty']; ?>;
var defSpec = <?php echo htmlspecialchars($this->_var['goods']['default_spec']); ?>;
var goodsspec = new goodsspec(specs, specQty, defSpec);
//]]>
</script>
<div class="detail_img goods-slider-area">
    <div id="slider" class="slider" >
    	<div class="main_visual">
			<div class="main_image">
				<ul>
					<?php if ($this->_var['goods']['_images']): ?>
					 <?php $_from = $this->_var['goods']['_images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'goods_image');$this->_foreach['fe_goods_image'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['fe_goods_image']['total'] > 0):
    foreach ($_from AS $this->_var['goods_image']):
        $this->_foreach['fe_goods_image']['iteration']++;
?>
					<li><img src="<?php echo $this->_var['goods_image']['thumbnail']; ?>"   /></li>
					 <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>  
					 <?php else: ?>
					 <li><img src="data/system/default_goods_image.gif"   /></li>
					<?php endif; ?>    
				</ul>
			</div>
		</div>
    	
    </div>
    <script type="text/javascript">
    $(function() {
    	var width = $('body').innerWidth();
    	$(".main_image").yxMobileSlider({width:width,height:width,during:3000});
    	
    })
    function addQuantity(num) {
    	document.getElementById("quantity").value = parseInt(document.getElementById("quantity").value) + num;
    }
    function minus(num) {
    	if (document.getElementById("quantity").value > 1) {
    		document.getElementById("quantity").value = parseInt(document.getElementById("quantity").value) - num;
    	} else {
    		return false;
    	}
    	
    }
    </script>
</div>

<div class="goods-info-area">
	<h1 class="goods-name"><?php echo htmlspecialchars($this->_var['goods']['goods_name']); ?></h1>

    <p class="goods-info-price"><?php if ($this->_var['goods']['if_credit'] == 1): ?><strong ectype="goods_price"><?php echo $this->_var['credit']; ?></strong><span style="color: #DF0507;font-size: 12px;">积分</span><?php else: ?>&yen;<strong ectype="goods_price"><?php echo $this->_var['goods']['_specs']['0']['price']; ?></strong><?php endif; ?><?php if ($this->_var['goods']['_specs']['0']['original_price'] != 0.00): ?><span style="color: #9b9b9b;font-size: 12px;margin-left: 10px;text-decoration: line-through;"><?php echo $this->_var['goods']['_specs']['0']['original_price']; ?></span><?php endif; ?><?php if ($this->_var['goods']['brand']): ?><span class="goods-info-brand"><?php echo $this->_var['goods']['brand']; ?></span><?php endif; ?></p><?php if ($this->_var['goods']['if_credit'] == 1 && $this->_var['goods']['credit_mall']): ?><p class="goods_name" style="color: #DF0507;font-size: 12px;margin-top: 10px;">该商品为积分商城商品</p><?php endif; ?>
   <?php if ($this->_var['shipping']): ?>
   <p class="goods-shipping-fee">物流运费：
        <?php $_from = $this->_var['shipping']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'shippings');if (count($_from)):
    foreach ($_from AS $this->_var['shippings']):
?>
        <span>&yen;<?php echo $this->_var['shippings']['shipping_fee']; ?>(<?php echo $this->_var['shippings']['shipping_name']; ?>)</span>
        <?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
    </p>
    <?php endif; ?>
    <div class="goods-info-num">
		<p class="goods-info-label">购买数量: </p>
		<span class="goods-info-num-change" style="font-size: 12px;color: #9b9b9b;">
			<button class="goods-info-num-btn num-minus" onclick="minus(1)">-</button>
			<input type="number" pattern="[0-9]*" id="quantity" value="1" class="goods-info-num-input" />
			<button class="goods-info-num-btn num-plus" onclick="addQuantity(1)">+</button>
			<span class="goods-left" style="line-height: 30px;">（库存<span class="stock" ectype="goods_stock"><?php echo $this->_var['goods']['_specs']['0']['stock']; ?></span>件）</span>
		</span>
	</div>
	
    <!--<p>销量：<?php echo $this->_var['sales_info']; ?><?php echo $this->_var['comments']; ?></p>-->
    <div class="handle">
            <?php if ($this->_var['goods']['spec_qty'] > 0): ?>
            <ul>
                <li class="handle_title"><?php echo htmlspecialchars($this->_var['goods']['spec_name_1']); ?>: </li>
            </ul>
            <?php endif; ?>
            <?php if ($this->_var['goods']['spec_qty'] > 1): ?>
            <ul>
                <li class="handle_title"><?php echo htmlspecialchars($this->_var['goods']['spec_name_2']); ?>: </li>
            </ul>
            <?php endif; ?>
            <?php if ($this->_var['goods']['spec_qty'] > 0): ?>
            <ul class="selected">
                <li class="handle_title">您已选择: </li>
                <li class="aggregate" ectype="current_spec"></li>
            </ul>
            <?php endif; ?>
        </div>
    
</div>
<div class="goods-info-shop-link">
	<p class="goods-info-shop-info"><?php echo htmlspecialchars($this->_var['store']['store_name']); ?>
		<p class="goods-info-shop-desc"><?php echo htmlspecialchars($this->_var['store']['store_name']); ?></p>
	</p>
	<a href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. ''); ?>" class="shop-enter">进入店铺</a>
</div>

<div class="buy-now-area">
			<div class="buy-item">
				<a href="<?php echo url('app=cart'); ?>" class="buy-now-cart">购物车</a>
				<a href="<?php echo url('app=store&id=' . $this->_var['store']['store_id']. ''); ?>" class="buy-now-shop">店铺</a>
			</div>
			<div class="buy-item">
				<a href="javascript:buy();" class="add">加入购物车</a>
			</div>
			<div class="buy-item">
				<a href="javascript:to_shop();" class="buy"><?php if ($this->_var['goods']['if_credit'] == 1): ?>立即兑换<?php else: ?>立即购买<?php endif; ?></a>
			</div>
			<div class="msg" style="display:none;">
				<div class="content-above">
					<p><b></b>购物车内共有<span class="bold_num"></span>种商品 共计 <span class="bold_mly" style="color:#8D0303;"></span>！</p>
				</div>

	            <a href="<?php echo url('app=cart'); ?>" class="white_btn">查看购物车</a>
	            <a  onclick="$('.msg').css({'display': 'none'});" class="white_btn">继续购物</a>
	        </div>
		</div>