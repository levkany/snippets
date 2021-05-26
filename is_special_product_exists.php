<?php
  /**
   * Returns whether a special product exists in the cart or not
   */
  function is_special_product_exists(array $cats = array(), string $taxonomy = 'product_cat') : bool{

    // Loop over $cart items
    $allowed_cats = $cats ?? get_all_special_categories();
    $flag = false;
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
      if($flag) break;
      $product_id = $cart_item['product_id'];
      $category = get_the_terms($product_id, $taxonomy);
      foreach($category as $cat){
        if(in_array($cat->term_id, $allowed_cats)) $flag = true; break;
      }
    }

    return $flag;
  }
