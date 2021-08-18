<?php

#
# Main frontend & logic
#
add_action('woocommerce_after_add_to_cart_quantity', '___woocommerce_after_add_to_cart_quantity');
function ___woocommerce_after_add_to_cart_quantity(){

    $bundle = get_post_meta( get_the_id(), '_crosssell_ids' );
    $product = wc_get_product(get_the_id());
    $bundle_filtered = [];
    $total = $product->get_price();

    # abort if no crosssell ids was found..
    if(!count($bundle)) return false;

    $current_prod = [
        'name' => $product->get_name(),
        'id' => get_the_id(),
        'thumb' => get_the_post_thumbnail_url(get_the_id()) ? get_the_post_thumbnail_url(get_the_id()) : 'https://cdn.shopify.com/s/files/1/0533/2089/files/placeholder-images-image_large.png?format=jpg&quality=90&v=1530129081',
        'price' => $product->get_price(),
        'on_sale' => $product->get_sale_price() ? true:false
    ];

    foreach($bundle[0] as $item){
        $product = wc_get_product($item);
        $bundle_filtered[$item] = [
            'name' => $product->get_name(),
            'id' => $item,
            'thumb' => get_the_post_thumbnail_url($item) ? get_the_post_thumbnail_url($item) : 'https://cdn.shopify.com/s/files/1/0533/2089/files/placeholder-images-image_large.png?format=jpg&quality=90&v=1530129081',
            'price' => $product->get_price(),
            'on_sale' => $product->get_sale_price() ? true:false
        ];
    };

    ob_start();
    ?>  
        <div class="upsell-bundle-products">
            <div class="upsell-bundle">
                <div class="title">
                    <?php echo __('מחיר חבילת שידרוג: ', 'lkd') . '<span class="price">'. (string)$total . '</span>' . ' ₪' ?>
                </div>
                <div class="thumbs">

                    <div class="thumb-wrapper">
                        <img src="<?php echo $current_prod['thumb']; ?>" />
                    </div>

                    <?php
                        
                        foreach($bundle_filtered as $item){
                            ?>
                                <div class="thumb-wrapper">
                                    <img src="<?php echo $item['thumb']; ?>" />
                                </div>
                            <?php
                        }

                    ?>
                </div>
                <div class="checkboxes">
                    <div class="upsell-item-wrapper">
                        <label>
                            <input type="checkbox" checked disabled name="bundled[]" data-price="<?php echo $current_prod['price']; ?>" value="<?php echo $current_prod['id']; ?>" /> <br/>
                            <p>
                                <?php echo $current_prod['name']; ?>
                                <span class="price">
                                    - מחיר <?php echo $current_prod['price'] . ' ₪'; ?> 
                                </span>
                            </p>
                        </label>
                    </div>
                    <?php
                        foreach($bundle_filtered as $item){
                            ?>
                                <div class="upsell-item-wrapper">
                                    <label>
                                        <input type="checkbox" name="bundled[]" data-price="<?php echo $item['price']; ?>" value="<?php echo $item['id'] ?>" /> <br/>
                                        <p>
                                            <?php echo $item['name']; ?>
                                            <span class="price">
                                                 - מחיר <?php echo $item['price'] . ' ₪'; ?> 
                                            </span>
                                        </p>
                                    </label>
                                </div>
                            <?php
                        }
                    ?>
                </div>
                <button type="button" class="btn btn-order-now">
                    <?php _e('הזמן עכשיו עם חבילת השידרוג!'); ?>
                </button>
            </div>
        </div>
        <style>
            
        .upsell-bundle-products img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        }
        .upsell-bundle-products .thumb-wrapper {
        width: 60px;
        height: 120px;
        position: relative;
        }
        .thumbs {
        display: flex;
        justify-content: space-between;
        border: 1px solid rgba(25,25,25,.08);
        padding: 13px;
        }
        .thumb-wrapper:not(:last-of-type)::after {
        content: ;
        content: '+';
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        font-size: 22px;
        font-weight: bold;
        margin-right: 60px;
        }
        .upsell-bundle-products {
        width: 100%;
        padding: 0 10px;
        }
        .upsell-bundle {
        border: 1px solid lightgray;
        padding: 6px 20px;
        }
        .upsell-bundle .title {
        background: rgba(25,25,25,.04);
        padding: 4px;
        margin: 10px 0;
        font-weight: bold;
        border: 1px solid rgba(25,25,25,.08);
        padding-right: 10px;
        }
        .upsell-item-wrapper label {
        display: flex;
        align-items: center;
        }
        .upsell-item-wrapper label * {
        margin: 0;
        }
        .upsell-item-wrapper label input {
        margin-left: 10px;
        }
        .checkboxes {
        border: 1px solid rgba(25,25,25,.08);
        margin: 10px 0;
        padding: 10px;
        }
        .checkboxes input[disabled] {
        background: lightgray;
        filter: blur(1px);
        border: 1px solid #252525;
        }
        .btn.btn-order-now {
        color: white;
        font-size: 18px !important;
        width: 100%;
        text-shadow: 0 0 8px #252525;
        margin: 10px 0;
        }
        form.cart button.single_add_to_cart_button{
        display: none !important;
        pointer-events: none !important;
        }

        form.cart .quantity{
            display: none !important;
        }
        </style>
        <script>
            jQuery(document).ready(function($){

                //
                //  Build the uri and refresh to add to cart
                //
                $('.btn-order-now').click(function(fe){
                    var prefix = '?add-bundle=';
                    var query = '';
                    var total_checked = 0;
                    $('.upsell-bundle-products input').each((i, e)=>{
                        if($(e).prop('checked')){
                            total_checked++;
                            query += $(e).val() + ',';
                        }
                    });

                    if(1 == total_checked) prefix = '?add-to-cart=';
                    query = prefix + query;

                    query = query.substring(0, query.length -1);
                    window.location.href = window.location.href.split('?')[0] + query;
                });


                //
                //  Calculate frontend
                //
                $('.upsell-item-wrapper input').change(function(fe){
                    var total_price = 0;
                    $('.upsell-bundle-products input').each((i, e)=>{
                        if($(e).prop('checked')){
                            total_price += parseInt($(e).attr('data-price'));
                        }
                    });

                    $('.upsell-bundle-products .title .price').text(total_price);
                });
            });
        </script>
    <?php
    echo ob_get_clean();
}



#
# Multi product add to cart support
#
add_action( 'init', 'woocommerce_maybe_add_multiple_products_to_cart', 999 );
function woocommerce_maybe_add_multiple_products_to_cart() {
    if(is_admin()) return;
    
    $product_ids = explode(',',  $_REQUEST['add-bundle']);
    $count       = count( $product_ids );
    sort($product_ids);

    if($_REQUEST['add-bundle']){
        wc_add_notice( apply_filters( 'wc_add_to_cart_message', 'חבילת המוצרים המשודרגת הוספה לעגלה בהצלחה!', 0 ) );
        if($count){
            foreach($product_ids as $__id){
                WC()->cart->add_to_cart($__id);
            }
        }
        $cart_updated = apply_filters('woocommerce_update_cart_action_cart_updated', true);
    }
}
