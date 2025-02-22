<?php
add_filter( 'woocommerce_account_menu_items', 'add_tida_menu_items', 99, 1 );
function add_tida_menu_items( $items ) {
    $user_id = get_current_user_id();
    $user = wp_get_current_user();
    $roles =  $user->roles;
    if(in_array('partner',$roles)){
        $my_items = array(
            '' => __( 'Dashboard', 'storefront' ),
            'partner-bookings' => __( 'Bookings', 'storefront' ),
            'partner-subscriptions' => __( 'Subscriptions', 'storefront' ),
            'partner-slots' => __( 'Slots', 'storefront' ),
            'edit-account' => __( 'Account details', 'storefront' ),
            'customer-logout' => __( 'Logout', 'storefront' ),
        );
    }else{
        $my_items = array(
        	'' => __( 'Dashboard', 'storefront' ),
            'orders' => __( 'Orders', 'storefront' ),
            'subscriptions' => __( 'Subscriptions', 'storefront' ),
            'bookings' => __( 'Slots', 'storefront' ),
            'customer-bookings-orders' => __( 'Bookings', 'storefront' ),
            'edit-address' => __( 'Addresses', 'storefront' ),
            'edit-account' => __( 'Account details', 'storefront' ),
            //'wishlist' => __( 'Wishlist', 'storefront' ),
            'payment-methods' => __( 'Payment methods', 'storefront' ),
            'customer-logout' => __( 'Logout', 'storefront' ),
        );
    }
    return $my_items;
}
function tidasports_endpoints() {
    add_rewrite_endpoint( 'partner-bookings', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'partner-subscriptions', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'partner-slots', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'partner-slots-orders', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'partner-subscriptions-orders', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'partner-bookings-orders', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'customer-bookings-orders', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'view-subscription-order', EP_ROOT | EP_PAGES );
    add_rewrite_endpoint( 'view-booking-order', EP_ROOT | EP_PAGES );
}
add_action( 'init', 'tidasports_endpoints' );
function tidasports_query_vars( $vars ) {
    $vars[] = 'partner-bookings';
    $vars[] = 'partner-subscriptions';
    $vars[] = 'partner-slots';
    $vars[] = 'partner-subscriptions-orders';
    $vars[] = 'partner-bookings-orders';
    $vars[] = 'partner-slots-orders';
    $vars[] = 'view-booking-order';
    $vars[] = 'view-subscription-order';
    $vars[] = 'view-slot';
    $vars[] = 'customer-bookings-orders';
	return $vars;
}
add_filter( 'query_vars', 'tidasports_query_vars' );
function tidasports_flush_rewrite_rules() {
    flush_rewrite_rules();
}
add_action( 'wp_loaded', 'tidasports_flush_rewrite_rules' );
function get_customer_order($user_id, $type = '', $orders_per_page = 10) {
    $orders = array();

    $meta_query = array(
        'relation' => 'OR',
        array(
            'key'     => 'tida_order_type',
            'value'   => ', ' . $type,
            'compare' => 'LIKE',
        ),
        array(
            'key'     => 'tida_order_type',
            'value'   => $type,
            'compare' => '=',
        )
    );

    $query_args = array(
        'return'       => 'ids',
        'customer_id'  => $user_id,
        'number'       => -1,
        'meta_query'   => $meta_query
    );

    // Count orders
    $count_query_args = $query_args;
    $count_query_args['number'] = 0; // Count only, don't retrieve orders
    $order_count = count(wc_get_orders($count_query_args));

    // Pagination
    $max_num_pages = ceil($order_count / $orders_per_page);
    $current_page = isset($_GET['page']) ? absint($_GET['page']) : 1;
    $offset = ($current_page - 1) * $orders_per_page;

    $query_args['number'] = $orders_per_page;
    $query_args['offset'] = $offset;

    // Retrieve orders
    $orders['total_orders'] = $order_count;
    $orders['max_pages'] = $max_num_pages;
    $orders['current_page'] = $current_page;
    $orders['orders'] = wc_get_orders($query_args);

    return $orders;
}
function get_partnert_booking_by_date($user_id,$date,$type,$product_id=''){
    $start_date = $date['start_date'];
    $end_date = $date['end_date'];
	global $wpdb;
    $order_status = ['wc-completed', 'wc-cancelled', 'wc-processing', 'wc-on-hold'];
    $sql = "SELECT order_items.order_id, order_item_meta.meta_value AS product
        FROM {$wpdb->prefix}woocommerce_order_items as order_items";
        $sql .= " LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id";
        $sql .= "  LEFT JOIN {$wpdb->prefix}wc_orders_meta AS order_meta ON order_items.order_id = order_meta.order_id";
        if($type){
         $sql .= "  LEFT JOIN {$wpdb->prefix}wc_orders_meta AS order_meta1 ON order_items.order_id = order_meta1.order_id";   
        }
        $sql .= " LEFT JOIN {$wpdb->prefix}wc_orders AS wc_orders ON order_items.order_id = wc_orders.id WHERE 1=1 ";
        $sql .= " AND order_item_meta.meta_key = '_product_id' ";
        if($product_id ){
         $sql .= "   AND order_item_meta.meta_value = '".$product_id."' ";
        }
        if($type == 'subscription'){
            $sql .= " AND ((order_meta1.meta_value LIKE '%,variable-subscription%' OR order_meta1.meta_value LIKE '%, variable-subscription%' OR order_meta1.meta_value = 'variable-subscription'
            OR order_meta1.meta_value LIKE '%,subscription_variation%' OR order_meta1.meta_value LIKE '%, subscription_variation%' OR order_meta1.meta_value = 'subscription_variation') AND order_meta1.meta_key = 'tida_order_type')";
            $sql .= " AND wc_orders.type ='shop_subscription' ";
        }else if($type != ''){
            $sql .= " AND ((order_meta1.meta_value LIKE '%,".$type."%' OR order_meta1.meta_value LIKE '%, ".$type."%' OR order_meta1.meta_value = '".$type."') AND order_meta1.meta_key = 'tida_order_type' )";
        }
        $sql .= " AND wc_orders.date_created_gmt BETWEEN  '$start_date' AND  '$end_date'";
        $sql .= " AND order_items.order_item_type = 'line_item' ";
        $sql .= " AND (order_meta.meta_key = 'partner_id' AND (order_meta.meta_value LIKE '%,".$user_id."%' OR order_meta.meta_value LIKE '%, ".$user_id."%' OR order_meta.meta_value = '".$user_id."'))";
    $sql .= " ORDER BY order_items.order_id DESC";
    $results = $wpdb->get_results($sql);
    return $results;
}
function get_partner_order_by_item($user_id,$product_id='',$type='',$post_per_page=10,$date=array()) {
    global $wpdb;
    $order_status = ['wc-completed', 'wc-cancelled', 'wc-processing', 'wc-on-hold'];
    if($date){}else{
        if(isset($_GET['from'])) {
            $start_date = $_GET['from'];
            $end_date = $_GET['to'];
            $date = array('start_date'=>$start_date,'end_date'=>$end_date);
        }
    }
    $sql = "SELECT DISTINCT(order_items.order_id)
        FROM {$wpdb->prefix}woocommerce_order_items as order_items";
        if($product_id){
        $sql .= " LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id";
        }
        $sql .= "  LEFT JOIN {$wpdb->prefix}wc_orders_meta AS order_meta ON order_items.order_id = order_meta.order_id";
        if($type){
         $sql .= "  LEFT JOIN {$wpdb->prefix}wc_orders_meta AS order_meta1 ON order_items.order_id = order_meta1.order_id";   
        }
        $sql .= " LEFT JOIN {$wpdb->prefix}wc_orders AS wc_orders ON order_items.order_id = wc_orders.id WHERE 1=1 ";
        if($product_id){
         $sql .= " AND order_item_meta.meta_key = '_product_id' AND order_item_meta.meta_value = '".$product_id."' ";
        }
        if($type == 'subscription'){
            $sql .= " AND ((order_meta1.meta_value LIKE '%,variable-subscription%' OR order_meta1.meta_value LIKE '%, variable-subscription%' OR order_meta1.meta_value = 'variable-subscription'
            OR order_meta1.meta_value LIKE '%,subscription_variation%' OR order_meta1.meta_value LIKE '%, subscription_variation%' OR order_meta1.meta_value = 'subscription_variation') AND order_meta1.meta_key = 'tida_order_type')";
            $sql .= " AND wc_orders.type ='shop_subscription' ";
        }else if($type != ''){
            $sql .= " AND ((order_meta1.meta_value LIKE '%,".$type."%' OR order_meta1.meta_value LIKE '%, ".$type."%' OR order_meta1.meta_value = '".$type."') AND order_meta1.meta_key = 'tida_order_type' )";
        }
        if($date){
            $start_date = $date['start_date'];
            $end_date = $date['end_date'];
            $sql .= " AND wc_orders.date_created_gmt BETWEEN  '$start_date' AND  '$end_date'";
        }
        $sql .= " AND order_items.order_item_type = 'line_item' ";
        $sql .= " AND (order_meta.meta_key = 'partner_id' AND (order_meta.meta_value LIKE '%,".$user_id."%' OR order_meta.meta_value LIKE '%, ".$user_id."%' OR order_meta.meta_value = '".$user_id."'))";
     $sql .= " ORDER BY order_items.order_id DESC";
    $results = $wpdb->get_col($sql);
    return $results;
}
function get_partner_order_count(){
    global $wpdb;
    $user_id = get_current_user_id();
    $booking_count_query = wc_get_orders(array(
			'return' => 'ids',
			'number'   => -1,
			'meta_query' => array(
				'relation' => 'AND',
                array('key' => 'tida_order_type',
                      'value' => 'booking', 
                      'compare' => 'LIKE',
				),
				array('key' => 'partner_id',
                      'value' => $user_id, 
                      'compare' => 'IN',
				)
			)
	));
    $academy_count_query = wc_get_orders(array(
			'return' => 'ids',
			'number'   => -1,
			'meta_query' => array(
			   'relation' => 'AND',
			    array('relation' => 'OR',
                array('key' => 'tida_order_type',
                      'value' => ', variation', 
                      'compare' => 'LIKE',
				), array('key' => 'tida_order_type',
						  'value' => 'variation', 
						  'compare' => '=',
				)),
				array('key' => 'partner_id',
					  'value' => $user_id, 
					  'compare' => 'LIKE',
				))
	));
    $subscription_count_query = wc_get_orders(array(
			'return' => 'ids',
			'number'   => -1,
			'meta_query' => array(
				'relation'=>'AND',
				array(
		        'relation' => 'OR',
                array('key' => 'tida_order_type',
                      'value' => 'subscription_variation', 
                      'compare' => 'LIKE',
                ),
                array('key' => 'tida_order_type',
                      'value' => 'variable-subscription', 
                      'compare' => 'LIKE',
                )),
				array('key' => 'partner_id',
                      'value' => $user_id, 
                      'compare' => 'LIKE',
				)
            )
	));
	$order_count = array('total_slots'=>count($booking_count_query),'total_bookings'=>count($academy_count_query),'total_subscriptions'=>count($subscription_count_query));
	return $order_count;
}
function get_url_var($name)
{
    $strURL = $_SERVER['REQUEST_URI'];
    $arrVals = explode("/",$strURL);
    $found = 0;
    foreach ($arrVals as $index => $value) 
    {
        if($value == $name) $found = $index;
    }
    $place = $found + 1;
    return $arrVals[$place];
}
function get_partner_product_by_partner($user_id,$type,$post_per_page=12){	
	$latitude = $longitude = $distance ='';
    if( isset($_GET)) {
        if(isset($_GET['page'])){
           $current_page = $_GET['page'];
        }
    } 
    $current_page = get_url_var('page');
    if(!is_numeric($current_page)){
        $current_page = 1;
    }
    if(!$post_per_page){
        $post_per_page = 12;
    }
	$offset = 0;
	if( ! 0 == $current_page) { 
		$offset = ( $post_per_page * $current_page ) - $post_per_page;
	} 
	$query = new WP_Query( array( 'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => 'product_type',
                'field'    => 'slug',
                'terms'    => $type, 
            ),
        ),
        'meta_query' => array(
            array(
                'key'       => 'partner_manager',
                'value'     => $user_id,
                'compare'   => '='
            )
    )) ); 
    $post_count = $query->found_posts;
    $product = get_posts(array('post_type' => 'product',
        'order'    => 'ASC',
        'orderby'  => 'name',
        'posts_per_page'   => $post_per_page,
        'offset'   => $offset,
        'post_status'    => array('publish', 'draft'),
        'tax_query' => array(
            array(
                'taxonomy' => 'product_type',
                'field'    => 'slug',
                'terms'    => $type, 
            ),
        ),
        'meta_query' => array(
            array(
                'key'       => 'partner_manager',
                'value'     => $user_id,
                'compare'   => '='
            )
    )));
    $data = array();
    $max_num_pages = ceil( $post_count / $post_per_page );
    
    
    
    $data['total_items'] = $post_count;
    $data['max_num_pages'] = $max_num_pages;
    $data['all_items'] = $product;
	return  $data;
}
function tidasports_endpoint_partner_bookings_content() {
	$user = wp_get_current_user();
    $roles =  $user->roles; 
    $user_id = $user->ID;
	$data=get_partner_product_by_partner($user_id,'variable');
    if(in_array('partner',$roles)){
		wc_get_template(
			'myaccount/view-partner-academy.php',
			array(
				'data' => $data,
				'user'=>$user
			)
		);
	}else{
		echo "You have no permission access this page";
	}
}
add_action( 'woocommerce_account_partner-bookings_endpoint', 'tidasports_endpoint_partner_bookings_content' );
function tidasports_endpoint_partner_subscriptions_content() {
	$user = wp_get_current_user();
    $roles =  $user->roles;
    $user_id = $user->ID;
    if(in_array('partner',$roles)){
	    $data=get_partner_product_by_partner($user_id,'variable-subscription'); 
		wc_get_template(
			'myaccount/view-partner-subscriptions.php',
			array(
				'data' => $data,
				'user'=>$user
			)
		);
	}else{
		echo "You have no permission access this page";
	}
}
add_action( 'woocommerce_account_partner-subscriptions_endpoint', 'tidasports_endpoint_partner_subscriptions_content' );
function tidasports_endpoint_partner_slots_content() {
	$user = wp_get_current_user();
    $roles =  $user->roles;
    $user_id = $user->ID;
    $date = '';
    if(isset($_GET['from'])){
        $start_date = $_GET['from'];
        $end_date = $_GET['to'];
        $date = array('start_date'=>$start_date,'end_date'=>$end_date);
    }
    if(in_array('partner',$roles)){
	    $data=get_partner_product_by_partner($user_id,'slots',$date);
		wc_get_template(
			'myaccount/view-partner-slots.php',
			array(
				'data' => $data,
				'user'=>$user
			)
		);
	}else{
		echo "You have no permission access this page";
	}
}
add_action( 'woocommerce_account_partner-slots_endpoint', 'tidasports_endpoint_partner_slots_content' );
function tidasports_partner_endpoint_partner_bookings_orders_content($product_id) {
	$user = wp_get_current_user();
    $roles =  $user->roles; 
    $user_id = $user->ID;
    $date = '';
    if(isset($_GET['from'])){
        $start_date = $_GET['from'];
        $end_date = $_GET['to'];
        $date = array('start_date'=>$start_date,'end_date'=>$end_date);
    }
	$data=get_partner_order_by_item($user_id,$product_id,'',$date);
    if(in_array('partner',$roles)){
		wc_get_template(
			'myaccount/view-partner-academy-orders.php',
			array(
				'product_id' => $product_id,
				'data' => $data,
				'user'=>$user
			)
		);
	}else{
		echo "You have no permission access this page";
	}
}
add_action( 'woocommerce_account_partner-bookings-orders_endpoint', 'tidasports_partner_endpoint_partner_bookings_orders_content' );
function tidasports_partner_endpoint_partner_subscriptions_orders_content($product_id) {
	$user = wp_get_current_user();
    $roles =  $user->roles;
    $user_id = $user->ID;
    $date = array();
    if(in_array('partner',$roles)){
        if(isset($_GET['from'])){
            $start_date = $_GET['from'];
            $end_date = $_GET['to'];
            $date = array('start_date'=>$start_date,'end_date'=>$end_date);
        }
	    $data=get_partner_order_by_item($user_id,$product_id,'subscription',$date); 
		wc_get_template(
			'myaccount/view-partner-subscriptions-orders.php',
			array(
				'product_id' => $product_id,
				'data' => $data,
				'user'=>$user
			)
		);
	}else{
		echo "You have no permission access this page";
	}
}
add_action( 'woocommerce_account_partner-subscriptions-orders_endpoint', 'tidasports_partner_endpoint_partner_subscriptions_orders_content' );
function tidasports_partner_endpoint_partner_slots_orders_content($product_id) {
	$user = wp_get_current_user();
    $roles =  $user->roles;
    $user_id = $user->ID;	
	$date ='';
    if(in_array('partner',$roles)){
        if(isset($_GET['from'])){
            $start_date = $_GET['from'];
            $end_date = $_GET['to'];
            $date = array('start_date'=>$start_date,'end_date'=>$end_date);
        }
		$data=get_partner_order_by_item($user_id,$product_id,'',$date);
		wc_get_template(
			'myaccount/view-partner-slots-orders.php',
			array(
				'product_id' => $product_id,
				'data' => $data,
				'user'=>$user
			)
		);
	}else{
		echo "You have no permission access this page";
	}
}
add_action( 'woocommerce_account_partner-slots-orders_endpoint', 'tidasports_partner_endpoint_partner_slots_orders_content' );
function tidasports_endpoint_customer_bookings_orders_content($product_id) {
	$user = wp_get_current_user();
    $roles =  $user->roles; 
    $user_id = $user->ID;
    $data=get_customer_order($user_id,'variation');
	wc_get_template(
		'myaccount/view-customer-academy-orders.php',
		array(
			'product_id' => $product_id,
			'data' => $data,
			'user'=>$user
		)
	);
}
add_action( 'woocommerce_account_customer-bookings-orders_endpoint', 'tidasports_endpoint_customer_bookings_orders_content' );
function tidasports_endpoint_view_subscription_order_content($subscription) {
	$subscription = new WC_Subscription( $subscription );
	wc_get_template(
		'myaccount/view-subscription-order.php',
		array(
			'subscription' => $subscription
		)
	);
}
add_action( 'woocommerce_account_view-subscription-order_endpoint', 'tidasports_endpoint_view_subscription_order_content' );
function tidasports_endpoint_view_booking_order_content($order_id) {
	wc_get_template(
		'myaccount/view-booking-order.php',
		$order_id
	);
}
add_action( 'woocommerce_account_view-booking-order_endpoint', 'tidasports_endpoint_view_booking_order_content' );
function tidasports_endpoint_view_slot_order_content($order_id) {
	wc_get_template(
		'myaccount/view-slot-order.php',
		$order_id
	);
}
add_action( 'woocommerce_account_view-slot-order_endpoint', 'tidasports_endpoint_view_slot_order_content' );
function toggle_customer_block_callback() {
    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
	$user = new WP_User($user_id); 
    $roles =  $user->roles;
	$user->set_role('inactive');
    if ( in_array( 'inactive', $user->roles, true )) {
		wp_logout();
        wp_send_json_success('Customer blocked successfully');
    } else {
        wp_send_json_error('Failed to block/remove customer account');
    }
}
add_action('wp_ajax_toggle_customer_block', 'toggle_customer_block_callback');


add_action('wp_ajax_nopriv_delete_slot', 'delete_slot');
add_action('wp_ajax_delete_slot', 'delete_slot');
function delete_slot(){	
    $resource_id = $_POST["resource_id"];
    $product_id = $_POST["product_id"];
	$key = $_POST["key"];
	$product = get_wc_product_booking( wc_get_product( $product_id ) );
	if( is_user_logged_in() ) {
		$user = wp_get_current_user();
		$roles =  $user->roles; 
		$user_id = $user->ID;  
		if(in_array('partner',$roles) && $user_id == get_field('partner_manager',$product_id)){
			if($resource_id != 0){
				$book_t_id = $resource_id;
				$slots = get_post_meta($resource_id,'_wc_booking_availability',true);
				$deleted_slot = $slots[$key];
				unset($slots[$key]);		
				$slots = array_values($slots);
				update_post_meta($resource_id,'_wc_booking_availability',$slots);
			}else{
				$book_t_id = $product_id;
				$slots = get_post_meta($product_id,'_wc_booking_availability',true);
				$deleted_slot = $slots[$key];
				unset($slots[$key]);		
				$slots = array_values($slots);
				update_post_meta($product_id,'_wc_booking_availability',$slots);
			}
			
			$from_date = date('Y-m-d',strtotime($deleted_slot['from_date']));
			$to_date = date('Y-m-d',strtotime($deleted_slot['from_date']));
			$from = date('H:i:s',strtotime($deleted_slot['from']));
			$available = get_post_meta($product_id,'_wc_booking_qty',true);
			$disabled_slot = $from_date.' '.$from;
			$disabled_slot_key = strtotime($disabled_slot);	
			if(get_post_meta($book_t_id,'booking_transient_'.$disabled_slot_key,true)){
				$del_book_meta = get_post_meta($book_t_id,'booking_transient_'.$disabled_slot_key,true);
				$restore_slot = array($disabled_slot_key => $del_book_meta);				
			}else{
				$del_book_meta = array(
					'booked'=>0,
					'available' => $available,
					'resources' =>  array(
						 $available
					));
				$restore_slot = array(
					$disabled_slot_key => $del_book_meta
				);
			}
			//	print_r($restore_slot);
			$product = get_wc_product_booking( wc_get_product( $product_id ) );
			$first_block_time = $product->get_first_block_time();	
			$first_from_date = $from_date.' '.$first_block_time.':00';	
			$Item_from_date = $from_date.' 00:00:00';			
			$start_date = $first_block_time ? $first_from_date : $Item_from_date;
			$end_date = $from_date.' 23:59:59';
			$start_from =  strtotime($start_date );
			$start_to = strtotime($end_date);			
			$transient_name = '_transient_book_ts_' . md5( http_build_query( array( $product_id, $resource_id, $start_from, $start_to, false ) ) );
			$available_slots = get_option( $transient_name );
			if(!empty($available_slots)){
				$available_slots = array_filter($available_slots);
				$available_slots[$disabled_slot_key] = $del_book_meta;
				//$restore_slot = array_filter($restore_slot);
				//array_push($available_slots,$restore_slot); 
			}else{
				$available_slots = $restore_slot;
			} 
			update_option($transient_name, $available_slots);
			delete_post_meta( $book_t_id,'booking_transient_'.$disabled_slot_key, $del_book_meta );
			if($resource_id != 0){			
				do_action( 'woocommerce_update_product', $resource_id );
			}
			do_action( 'woocommerce_update_product', $product_id );
			$json[]=array(
			   'success'=> true,
			   'slot'=> 'Slot deleted successfully! '
			);
		}else{
			$json[]=array(
			   'success'=> false,
			   'slot'=> "You don't have permission to update this item!",
			   '1'=>get_field('partner_manager',$product_id),
			   '2'=>$user_id,
			   '3'=>$roles
			);
		}
	}else{
		$json[]=array(
		   'success'=> false,
		   'slot'=> "You don't have permission to update this item!"
		);
	}
	echo json_encode($json);die;
}
add_action('wp_ajax_nopriv_select_slot', 'select_slot');
add_action('wp_ajax_select_slot', 'select_slot');
function select_slot(){
	$data = array();
    $data['year'] = date('Y',strtotime($_POST["date"]));
    $data['month'] = date('m',strtotime($_POST["date"]));
    $data['day'] = date('d',strtotime($_POST["date"]));
    $data['slots_for_days'] = 0;
    $data['id'] = $_POST["product_id"];
    $data['product_id'] =$product_id= $_POST["product_id"];
    $data['resource_id'] =$resource_id= $_POST["resource_id"];
	$slots = getslotsbydate($data);
	$product = get_wc_product_booking( wc_get_product( $product_id ) );
	if(is_array($slots)){
		echo '<option value="">All Slots</option>';
		foreach($slots as $key=>$slot){ 
			if(is_array($slot)){
				foreach($slot as $key=>$val){					  
					if($val['slot_availability']){			
						if($val['slot_time']){
							$slot_availability = $val['slot_availability'].' '.$val['slot_time'];
						}else{
							$slot_availability = $val['slot_availability'];
						}
						echo '<option value="'. $val['slot_availability'] .'">'; echo $slot_availability; echo '</option>';					  
					}else{
						if($val['slot_time']){
							$slot_availability = $val['slot_start_time'].' '.$val['slot_time'];
						}else{
							$slot_availability = $val['slot_start_time'];
						}
						echo '<option value="'. $val['slot_start_time'] .'">'; echo $slot_availability; echo '</option>';
					}
				} 
			}else{
				if($slot['slot_availability']){
					if($val['slot_availability']){	
						if($val['slot_time']){
							$slot_availability = $val['slot_availability'].' '.$val['slot_time'];
						}else{
							$slot_availability = $val['slot_availability'];
						}				
						echo '<option value="'. $val['slot_availability'] .'">'; echo $slot_availability; echo '</option>';					  
					}else{
						if($val['slot_time']){
							$slot_availability = $val['slot_start_time'].' '.$val['slot_time'];
						}else{
							$slot_availability = $val['slot_start_time'];
						}
						echo '<option value="'. $val['slot_start_time'] .'">'; echo $slot_availability; echo '</option>';
					}
				}
			}
		}
	}else{
		echo '<option>'.$slots.'</option>';
	}
	//echo '<pre>'; print_r($slots); echo '</pre>';
	die;
}
add_action('wp_ajax_nopriv_action_new_slot', 'action_new_slot');
add_action('wp_ajax_action_new_slot', 'action_new_slot');
function action_new_slot(){ 
    $from_date = date('Y-m-d',strtotime($_POST["from_date"]));
    $to_date = date('Y-m-d',strtotime($_POST["from_date"]));
	if(isset($_POST["select_slot"])){
		$from = date('H:i',strtotime($_POST["select_slot"]));
	}else{
		$from ="00:00";
	}
    $resource_id = $_POST["resource_id"];
    $slot_type = 'custom:daterange'; 
    $product_id = $_POST["product_id"];
	$product = get_wc_product_booking( wc_get_product( $product_id ) );
	$minutes_to_add = $product->get_duration();
	$date_time = $_POST["from_date"] ." ". $from;
    $time = new DateTime($date_time);
	$time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
	$to = $time->format('H:i'); //date('H:i',$slot_end['date']);
    $priority = 7;
    $bookable = 'no';
    $slot = array(
		'type' => $slot_type,
		'bookable' => $bookable,
		'priority' => $priority,
		//'added_by' => 'partner'
	);
	if(($_POST["select_slot"])){
		if($slot_type == 'time'){
			$slot['from'] = $from;
			$slot['to'] = $to;
		}else {
			$slot['from'] = $from;
			$slot['to'] = $to;
			$slot['from_date'] = $from_date;
			$slot['to_date'] = $to_date;
		} 
	}else{
		$slot['type'] = 'custom';
		$slot['from'] = $from_date;
		$slot['to'] = $to_date;
	}
	$disabled_slot = $from_date.' '.$from.':00';
	$disabled_slot_key = strtotime($disabled_slot);
	if( is_user_logged_in() ) {
		$user = wp_get_current_user();
		$roles = $user->roles; 
		$user_id = $user->ID;
		if(in_array('partner',$roles)  && $user_id == get_field('partner_manager',$product_id)){
			if($resource_id != 0){ 
				$slots = get_post_meta($resource_id,'_wc_booking_availability',true);
			}else{				
				$slots = get_post_meta($product_id,'_wc_booking_availability',true);
			}
			array_push($slots,$slot); //echo '<pre>';  print_r($slots);  print_r($slot);
			
			$first_block_time = $product->get_first_block_time();	
			$first_from_date = $from_date.' '.$first_block_time.':00';	
			$Item_from_date = $from_date.' 00:00:00';			
			$start_date = $first_block_time ? $first_from_date : $Item_from_date;
			$end_date = $from_date.' 23:59:59';
			$start_from =  strtotime($start_date );
			$start_to = strtotime($end_date);			
			$transient_name = '_transient_book_ts_' . md5( http_build_query( array( $product_id, $resource_id, $start_from, $start_to, false ) ) );
			$available_slots = get_option( $transient_name );			
			$disabled_slot = $from_date.' '.$from;
			$disabled_slot_key = strtotime($disabled_slot);
			if($available_slots[$disabled_slot_key]['booked'] == 0){ 
				update_post_meta($product_id,'_wc_booking_availability',$slots);
				if(!empty($available_slots)){ 	
					if($resource_id != 0){ 
					$slots = update_post_meta($resource_id,'booking_transient_'.$disabled_slot_key,$available_slots[$disabled_slot_key]);
				}else{				
					$slots = update_post_meta($product_id,'booking_transient_'.$disabled_slot_key,$available_slots[$disabled_slot_key]);
				} //echo $disabled_slot_key; print_r($available_slots[$disabled_slot_key]);
					unset($available_slots[$disabled_slot_key]);		
					/* $available_slots = array_values($available_slots); echo 2; print_r($available_slots); */
				}
				update_option($transient_name, $available_slots);			
				$json[]=array(
				   'success'=> true,
				   'slot'=> 'Slot updated successfully! '
				);
			}else{
				$json[]= array(
				   'success'=> false,
				   'slot'=> "There are some bookings in this slot. So, you can't disable this slot. Please Contact with admin for more details"
				);
			}
			/* echo '<br/>'.$transient_name;
			echo '<br/>'.$first_from_date;
			echo '<br/>'.$from_date;
			echo '<br/>'.$start_from.' '. date('Y-m-d H:i:s',$start_from);
			echo '<br/>'.$start_to.' '.date('Y-m-d H:i:s',$start_to);
			echo '<pre>'; print_r($available_slots); */
		}else{
			$json[]=array(
			   'success'=> false,
			   'slot'=> "You don't have permission to update this item!"
			);
		}
	}else{
		$json[]=array(
		   'success'=> false,
		   'slot'=> "You don't have permission to update this item!"
		);
	}
	echo json_encode($json);die;
}
add_action('wp_ajax_nopriv_enable_slot', 'enable_slot');
add_action('wp_ajax_enable_slot', 'enable_slot');
function enable_slot(){
    $resource_id = $_POST["resource_id"];
    $product_id = $_POST["product_id"];
	$key = $_POST["key"];
	if( is_user_logged_in() ) {
		$user = wp_get_current_user();
		$roles =  $user->roles; 
		$user_id = $user->ID;  
		if(in_array('partner',$roles)  && $user_id == get_field('partner_manager',$product_id)){
			if($resource_id != 0){ $product_id = $resource_id; }
			$slots = get_post_meta($product_id,'_wc_booking_availability',true);
			$slots[$key]['bookable']='yes';		
			$slots = array_values($slots);
			update_post_meta($product_id,'_wc_booking_availability',$slots);
			$json[]=array(
			   'success'=> true,
			   'slot'=> 'Slot enabled successfully! '
			);
		}else{
			$json[]=array(
			   'success'=> false,
			   'slot'=> "You don't have permission to update this item!",
			   '1'=>get_field('partner_manager',$product_id),
			   '2'=>$user_id,
			   '3'=>$roles
			);
		}
	}else{
		$json[]=array(
		   'success'=> false,
		   'slot'=> "You don't have permission to update this item!"
		);
	}
	echo json_encode($json);die;
}
add_action('wp_ajax_nopriv_disable_slot', 'disable_slot');
add_action('wp_ajax_disable_slot', 'disable_slot');
function disable_slot(){	
    $resource_id = $_POST["resource_id"];
    $product_id = $_POST["product_id"];
	$key = $_POST["key"];
	if( is_user_logged_in() ) {
		$user = wp_get_current_user();
		$roles =  $user->roles; 
		$user_id = $user->ID;  
		if(in_array('partner',$roles)  && $user_id == get_field('partner_manager',$product_id)){
			if($resource_id != 0){ $product_id = $resource_id; }
			$slots = get_post_meta($product_id,'_wc_booking_availability',true);
			$slots[$key]['bookable']='no';		
			$slots = array_values($slots);
			update_post_meta($product_id,'_wc_booking_availability',$slots);
			$json[]=array(
			   'success'=> true,
			   'slot'=> 'Slot disabled successfully! '
			);
		}else{
			$json[]=array(
			   'success'=> false,
			   'slot'=> "You don't have permission to update this item!",
			   '1'=>get_field('partner_manager',$product_id),
			   '2'=>$user_id,
			   '3'=>$roles
			);
		}
	}else{
		$json[]=array(
		   'success'=> false,
		   'slot'=> "You don't have permission to update this item!"
		);
	}
	echo json_encode($json);die;
}