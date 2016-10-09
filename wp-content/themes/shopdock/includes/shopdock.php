<?php
/**
 * Template for cart
 */
global $woocommerce;
?>
<?php themify_shopdock_before(); //hook ?>
<div id="shopdock" class="shopdock_cart">

	<div class="shopdock-inner pagewidth">
    	<?php themify_shopdock_start(); //hook ?>
    	<?php if (sizeof($woocommerce->cart->get_cart())>0) { ?>
    		<div id="cart-slider">
				<ul class="cart-slides">
					<?php get_template_part('includes/loop-product', 'cart'); ?>
				</ul>
			</div>
		<?php } //end if sizeof cart
			do_action( 'woocommerce_cart_contents' );
		?>
		
		<div class="checkout-wrap clearfix">
        	<?php themify_checkout_start(); //hook ?>
			<p class="checkout-button">
				<button type="submit" class="button checkout" onclick="location.href='<?php echo esc_url($woocommerce -> cart -> get_checkout_url()); ?>'"><?php _e('Checkout', 'themify')?></button>
			</p>
			<p class="cart-total">
				<span id="cart-loader" class="hide"></span>
				<span class="total-item"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'themify'), $woocommerce->cart->cart_contents_count); ?></span>
        		<?php if(sizeof($woocommerce->cart->get_cart()) > 0){ ?>
					<span class="amount">( <?php echo strip_tags($woocommerce -> cart -> get_cart_total()); ?> &sdot; <span class="view-cart-link">
							<a href="<?php echo esc_url($woocommerce->cart->get_cart_url()) ?>"><?php _e('View Cart', 'themify') ?></a>
						</span>)
					</span>
				<?php } ?>
			</p>
            <?php themify_checkout_end(); //hook ?>
		</div>
		<!-- /.cart-checkout -->
		<?php themify_shopdock_end(); //hook ?>
	</div>
	<!-- /.pagewidth -->
    
</div>
<!-- /#shopdock -->
<?php themify_shopdock_after(); //hook ?>