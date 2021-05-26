<?php

  /**
   * Returns all the special categories ids as an array
   */
  function get_all_special_categories(string $meta_key = '__term_meta_text', string $meta_value = 'yes', string $taxonomy = 'product_cat') : ?array{

    $cats = get_terms([
      'taxonomy' => $taxonomy,
      'hide_empty' => 'false',
      'fields' => 'ids',
      'meta_query' => array(
        'key' => $meta_key,
        'value' => $meta_value,
        'compare' => '='
      )
    ]);

    return $cats;
  }
