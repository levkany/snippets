<?php

#
# This snippet is mainly used to return whether a customer already made purchase (returny customer) or if it is his first purchase (new customer) 
# Be-aware: (Snippet based on customer phone)
#
# "$check_after_published" param is used if the check is done after a new order is submitted (Ex: on new order meta save hook)
#



#
#	Retrieve all the order's phones
#
function get_customer_phone_numbers() :array {
	$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	if(!mysqli_connect_errno()){
		$result = mysqli_query(
			$db,
			'SELECT wp_postmeta.meta_value
			from wp_posts
			inner join wp_postmeta on
				wp_posts.id = wp_postmeta.post_id AND
				wp_posts.post_type = "shop_order" AND
				wp_postmeta.meta_key = "_billing_phone"'
		);
		return mysqli_fetch_all($result);
	}

	return 0x0; # failed to fetched
}



#
#	Returns whether the provided phone exists in any order
#
function is_customer_phone_exists($myphone, $check_after_published = false){
	$phones = get_customer_phone_numbers();	
	$timesMatched = 0;
	for($x = 0; $x < count($phones); $x++){
		var_dump($phones[$x][0]);
		if($myphone == $phones[$x][0]){
			if(!$check_after_published) return 0x1;
			$timesMatched++;
		}

		#
		#	Since the shop_order post is being created before our check, we have to make check for at least 2 same numbers to determine if the phone exists or not
		#
		if($timesMatched >= 0x2 && $check_after_published) return 0x1;
	}
	return 0x0; # no match
}
