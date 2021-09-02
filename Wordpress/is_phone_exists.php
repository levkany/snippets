<?php

function is_phone_exists(string $phone = ''){
	global $wpdb;
	$results = $wpdb->query(
		'SELECT ID, wp_usermeta.meta_value AS "phone" FROM ' . $wpdb->prefix . 'users
		INNER JOIN ' . $wpdb->prefix . 'usermeta ON ' . $wpdb->prefix . 'users.ID = ' . $wpdb->prefix . 'usermeta.user_id
		WHERE ' . $wpdb->prefix . 'usermeta.meta_key = "billing_phone" AND ' . $wpdb->prefix . 'usermeta.meta_value = "'. $phone .'"'
	);
	return (int)$results > 0;
}
