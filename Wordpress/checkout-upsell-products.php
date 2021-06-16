<?php

#
#	Render upsell products in the checkout
#
add_action('woocommerce_after_order_notes', '___woocommerce_after_order_notes');
function ___woocommerce_after_order_notes(){

	$products = get_field('upsells_products', 'option'); # returns product obj list

	?>
		<div class="checkout-upsell-products">
			<?php
				for($x = 0; $x < count($products); $x++){
					$id = $products[$x]->ID;
					$price = get_post_meta($id, '_regular_price', true);
					$price = !empty($price) ? $price : get_post_meta($id, '_price', true); # get either regular price or price
					$sale_price = get_post_meta($id, '_sale_price', true);
					$eval_price = !empty($sale_price) ? $sale_price : $price; # either sale price or regular price
					$title = $products[$x]->post_title;
					$image = get_the_post_thumbnail_url($id);

					?>
						<div class="single-upsell" data-id="<?php echo $id; ?>">
							<div class="contnet">
								<img src="<?php echo $image; ?>" alt="product image <?php echo $id; ?>" />
								<h4 class="title">
									<?php echo $title; ?>
								</h4>
								<p class="price">
									<?php echo get_woocommerce_currency_symbol() . $eval_price; ?>
								</p>
							</div>
							<input class="addor" type="checkbox" />
						</div>
					<?php
				}
			?>
		</div>
		<script>
			jQuery(document).ready(function($){
				$('.single-upsell .addor').change(function(fe){
					$.post("<?php echo admin_url('admin-ajax.php'); ?>", {action: 'add_remove_upsell_checkout', prod_id: $(this).closest('.single-upsell').attr('data-id'), is_add: $(this).is(':checked')}, function(res){
						console.log(res);
						$('body').trigger('update_checkout');
					});
				});
			});
		</script>
	<?php

}


#
#	Ajax endpoint to update the cart
#
add_action('wp_ajax_add_remove_upsell_checkout', 'add_remove_upsell_checkout');
add_action('wp_ajax_nopriv_add_remove_upsell_checkout', 'add_remove_upsell_checkout');
function add_remove_upsell_checkout(){
	@session_start();
	$prod_id = (int)$_POST['prod_id'];
	$is_add = 'true' == $_POST['is_add'];

	if($is_add){ # add product
		$cart_item_key = WC()->cart->add_to_cart( $prod_id, 1);
		$_SESSION['prod_' . $prod_id] = $cart_item_key;
		echo 200;
	}else{ # remove product
		foreach($_SESSION as $name => $cart_key){
			$compare_id = mb_substr($name, 5);
			try{
				$compare_id = (int)$compare_id;
			}catch(Exception $x){}

			#
			#	Found the product to be removed from the cart!
			#
			if('integer' == gettype($compare_id)){ 
				if($compare_id == $prod_id){
					WC()->cart->remove_cart_item($cart_key);
					echo -200;
				}
			}
		}
	}

	die();
}
