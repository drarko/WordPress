<?php 
/**
 * Template for cart loop
 */
global $woocommerce;

$carts = array_reverse($woocommerce->cart->get_cart());
foreach ($carts as $cart_item_key => $values) {
	$_product = $values['data'];
	if ($_product->exists() && $values['quantity']>0) {
		?>
		<li>
			<div class="product">
			
				<div class="product-imagewrap">
					<?php if($values['quantity'] > 1): ?>
						<div class="quantity-tip"><?php echo $values['quantity']; ?></div>
					<?php endif; ?>
					<a href="<?php echo esc_url( $woocommerce->cart->get_remove_url($cart_item_key) ); ?>" data-product-key="<?php echo $cart_item_key; ?>" class="remove-item remove-item-js"><?php _e('Remove', 'themify'); ?></a>
					<figure class="product-image">
						<?php themify_product_cart_image_start(); //hook ?>
						<a href="<?php echo get_permalink($values['product_id']); ?>">
							<?php
								$product_thumbnail = $_product->get_image('cart_thumbnail');
								if ( ! empty( $product_thumbnail ) ) {
									echo $product_thumbnail;
								} else {
									?>
									<img src="http://placehold.it/65x65">
									<?php
								}
							?>
						</a>
						<?php themify_product_cart_image_end(); //hook ?>
					</figure>
				</div>
				
			</div>
			<!--/product -->
		</li>
		<?php
	}
}
?>