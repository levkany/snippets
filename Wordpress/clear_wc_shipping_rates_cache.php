<?php

  /**
   * Disable woocommerce shipping price cache
   */
  add_filter('woocommerce_checkout_update_order_review', 'clear_wc_shipping_rates_cache');
  function clear_wc_shipping_rates_cache(){
      $packages = WC()->cart->get_shipping_packages();

      foreach ($packages as $key => $value) {
          $shipping_session = "shipping_for_package_$key";

          unset(WC()->session->$shipping_session);
      }
  }
