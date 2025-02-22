<?php
/* add_action( 'woocommerce_coupon_options_save', 'trigger_coupon_schedule_single_event', 10, 2 );
function trigger_coupon_schedule_single_event( $post_id, $coupon ) {
    // Check that some usage limit has been activated for the current coupon
    if ( $coupon->get_usage_limit() || $coupon->get_usage_limit_per_user() ) {
        // Create a shedule event on 'coupon_schedule_reset_restrictions' custom hook
        wp_schedule_single_event( time() + 60, 'coupon_schedule_reset_restrictions', array( $coupon ) );
    }
}

add_action( 'coupon_schedule_reset_restrictions', 'coupon_reset_restrictions' );
function coupon_reset_restrictions( $coupon ){
    $coupon->set_usage_limit(null);
    $coupon->set_usage_limit_per_user(null);
    $coupon->save();
} */
/* add_action('woocommerce_coupon_used', 'increase_coupon_usage_limit');

function increase_coupon_usage_limit($coupon_code) {
    // Get the coupon object
    $coupon = new WC_Coupon($coupon_code);
    
    // Get the current usage limit
    $usage_limit = $coupon->get_usage_limit();
    
    // Increase the usage limit by 1
    if ($usage_limit) {
        $new_usage_limit = $usage_limit + 1;
        $coupon->set_usage_limit($new_usage_limit);
        $coupon->save();
    }
} */
function coupon_error_message_change($err, $err_code, $WC_Coupon) {
    if ( $err == 'Wrong Coupon Code for Selected Item' ) {
		$err = 'Invalid Coupon Code for Selected Item';
    }
    return $err;
}

add_filter( 'woocommerce_coupon_error','coupon_error_message_change',10,3 );
function tida_getOrderbyCouponCode($coupon_code, $user_id) {
    global $wpdb;
    $return_array = [];
    $total_discount = 0;
    $query = "SELECT o.ID AS order_id FROM {$wpdb->prefix}posts AS p
	INNER JOIN {$wpdb->prefix}wc_orders AS o ON p.post_parent = o.ID
	INNER JOIN {$wpdb->prefix}woocommerce_order_items AS woi ON o.ID = woi.order_id
	WHERE o.customer_id = '$user_id' AND
	woi.order_item_type = 'coupon' AND
	woi.order_item_name = '" . $coupon_code . "'";
	$orders = $wpdb->get_results($query);
	return count($orders);
}
function incart_booking_remove($booking_id){
	if($booking_id){
		
		/* if($booking = get_wc_booking( $booking_id )){
		$order = $booking->get_order();
		if ($order){			
			return false;
		}else{
			$booking->update_status( 'cancelled' );
			return true;
		} */
	}else{			
	}
}
/**
* Add custom field to the checkout page
*/
add_action('woocommerce_after_order_notes', 'custom_checkout_field');
function custom_checkout_field($checkout)
{
echo '<div id="custom_checkout_field">';
woocommerce_form_field('person_name', array(
		'type' => 'text',
		'class' => array(
		'person_name form-row-wide'
		) ,
		'required' => true,
		'label'   => __( 'Name of the Person to whom the order is booked', 'storefront' )
) ,
$checkout->get_value('person_name'));
echo '</div>';
}
/**
* Checkout Process
*/
add_action('woocommerce_checkout_process', 'customised_checkout_field_process');
function customised_checkout_field_process()
{
// Show an error message if the field is not set.
if (!$_POST['person_name']) wc_add_notice(__('Please enter the name of person to whom the order is booked!') , 'error');
}
/**
* Update the value given in custom field
*/
add_action('woocommerce_checkout_update_order_meta', 'custom_checkout_field_update_order_meta');
function custom_checkout_field_update_order_meta($order_id)
{
    if (!empty($_POST['person_name'])) {
        update_post_meta($order_id, 'person_name',sanitize_text_field($_POST['person_name']));
    }
}
/**
 * Display field value on the order edit page
 */
add_action('woocommerce_admin_order_data_after_billing_address', function($order) {
	$my_value = get_post_meta($order->get_id(), 'person_name', true);
	if (!empty($my_value)) {
		echo '<p><strong>' . __('Name of the Person to whom the order is booked', 'woocommerce') . ':</strong> ' . $my_value . 
'</p>';
	}
});
function woocommerce_edit_my_account_page() {
    return apply_filters( 'woocommerce_forms_field', array(
        'woocommerce_my_account_page' => array(
           'type'    => 'text',
		   'name'    => 'billing_first_name',
		   'id'    => 'reg_billing_first_name',
           'required' => true,
           'label'   => __( 'First name', 'storefront' )
    ),
'woocommerce_my_account_page' => array(
           'type'    => 'text',
		   'name'    => 'billing_last_name',
		   'id'    => 'reg_billing_last_name',
           'required' => true,
           'label'   => __( 'Last name', 'storefront' )
    ),
'woocommerce_my_account_page' => array(
           'type'    => 'text',
		   'name'    => 'phone_number',
		   'id'    => 'reg_phone_number',
           'required' => true,
           'label'   => __( 'Phone', 'storefront' )
    ),
'woocommerce_my_account_page' => array(
           'type'    => 'text',
           'required' => true,
           'label'   => __( 'Person For', 'storefront' )
    )	));
}
function edit_my_account_page_woocommerce() {
    $fields = woocommerce_edit_my_account_page();
    foreach ( $fields as $key => $field_args ) {
        woocommerce_form_field( $key, $field_args );
    }
}
add_action( 'woocommerce_register_form', 'edit_my_account_page_woocommerce', 15 );
add_filter( 'woocommerce_is_purchasable', 'tidasports_per_role_purchase', 10, 2 );
function tidasports_per_role_purchase( $is_purchasable, $product ) {
    $user           = wp_get_current_user();
    $catalog_roles  = array( 'partner', 'partner_manager' ); // add your user roles here.
    $roles          = (array) $user->roles;
    if ( 0 < count( array_intersect( $catalog_roles, $roles ) ) ) {
        $is_purchasable = false;
    }
    return $is_purchasable;
}
add_filter( 'woocommerce_rest_prepare_product', 'custom_products_api_data', 90, 2 );
function custom_products_api_data( $response, $post ) {
    $response->data['sport'] = wp_get_post_terms( $post->ID, 'sport', [] );
    return $response;
}
function action_woocommerce_created_customer( $customer_id, $new_customer_data, $password_generated ) { 
	$customer = get_user_by( 'id', $customer_id); 
	if(get_field('phone_number','user_'.$customer_id)){
		$phone = get_field('phone_number','user_'.$customer_id);
	}else if(get_field('billing_phone','user_'.$customer_id)){
		$phone = get_field('billing_phone','user_'.$customer_id);
	}else if(get_user_meta($customer_id,'phone_number')) {
		$phone = get_user_meta($customer_id,'phone_number',true);
	}else if(get_user_meta($customer_id,'billing_phone')) {
		$phone = get_user_meta($customer_id,'billing_phone',true);
	}else{
		$phone = '';
	}
	if($phone && !get_user_meta($customer_id,'billing_phone',true)){
		update_user_meta($customer_id, 'billing_phone', $phone);
		update_user_meta($customer_id, 'shipping_phone', $phone);			
	}
	if($customer && !get_user_meta($customer_id,'billing_first_name',true)){
		 update_user_meta($customer_id, 'billing_first_name', $customer->first_name);	
		 update_user_meta($customer_id, 'shipping_first_name', $customer->first_name);	
	}
}; 
add_action( 'woocommerce_created_customer', 'action_woocommerce_created_customer', 10, 3 ); 
add_action('woocommerce_new_order', 'tida_new_order', 10,2);
function tida_new_order($order_id, $order )
{ 
	$order_items = $order->get_items();
    $type = '';
    $partner = ''; 
	foreach ( $order_items as $item_id => $item ) { 
		$product = wc_get_product($item['product_id']);
		if($product->get_type() == 'subscription_variation' || $product->get_type() == 'variable-subscription'){
			$type = ', subscription_variation';
		}else if($product->get_type() == 'booking' ){
			$type = ', booking';
		}elseif($product->get_type() == 'variation' ){
			$type = ', variation';
		}else{
			$type = ', '.$product->get_type();
		}
		if($item['product_id']){
		    $productId = $item['product_id'];
		}else {
		    $productId = $product->get_id();
		}
		if($partner == ''){
			$partner .= get_field('partner_manager',$productId);
		}else{
			$partner .= ','.get_field('partner_manager',$productId);
		}
	}  
	$meta_order_type = $order->get_meta('tida_order_type');
	if (!($order->get_meta('tida_order_type') )) { 
		$order->update_meta_data( 'tida_order_type' , $type ) ;
		$order->update_meta_data( 'partner_id' , $partner ) ;
		$order->save();
	}elseif(!($order->get_meta('partner_id') )) { 
		$order->update_meta_data( 'partner_id' , $partner ) ;
		$order->save();
	}elseif($partner) { 
		$order->update_meta_data( 'partner_id' , $partner ) ;
		$order->save();
	}	
     $order_items = $order->get_items();
	foreach ( $order_items as $item_id => $item ) { 
		$product = wc_get_product($item['product_id']);
    	if($order->get_created_via() == 'rest-api' && ($product->get_type() == 'subscription_variation' || $product->get_type()
 == 'variable-subscription')){
			$subscriptions = wcs_get_subscriptions_for_order($order_id, array( 'order_type' => 'any' ));
			if(empty($subscriptions)){
				$rz_subscription = new Tida_WC_Razorpay_Subscription(); 
				$rz_subsc = $rz_subscription->create_wc_subParams($order,$order_id,$item);
				$next_payment = date('YmdHis');
				$order->update_meta_data( 'created_subscription' , $next_payment ) ;
				$order->save();
			}
    	}
		if( $product->get_type() === 'booking' ) {
			$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
			foreach( $booking_ids as $booking_id ) { 
				$booking = new WC_Booking($booking_id);
				$order->update_meta_data( '_booking_id' , $booking_id ) ;
				$order->save();
				$booking->update_meta_data( 'partner_id' , $partner ) ;
				$booking->save();
			}
		}elseif($product->get_type() == 'subscription_variation' || $product->get_type()
		== 'variable-subscription'){
				$subscriptions_ids = wcs_get_subscriptions_for_order( $order_id, array( 'order_type' => 'any' ) );
				if(!empty($subscriptions_ids)){
					foreach( $subscriptions_ids as $subscription_id => $subscription ){
						update_post_meta( $subscription_id,'partner_id' , $partner ) ;
						if($subscription->get_time( 'next_payment' )){
							$next_payment = date('YmdHis',$subscription->get_time( 'next_payment' ));
							$order->update_meta_data( 'next_payment' , $next_payment ) ;
							$order->save();
						}
				   }
				}
				
		}
	}
	$customer_id = $order->get_customer_id();
	if(get_field('phone_number','user_'.$customer_id)){
		$phone = get_field('phone_number','user_'.$customer_id);
	}else if(get_field('billing_phone','user_'.$customer_id)){
		$phone = get_field('billing_phone','user_'.$customer_id);
	}else if(get_user_meta($customer_id,'phone_number')) {
		$phone = get_user_meta($customer_id,'phone_number',true);
	}else if(get_user_meta($customer_id,'billing_phone')) {
		$phone = get_user_meta($customer_id,'billing_phone',true);
	}else{
		$phone = '';
	}
	if($phone && !get_user_meta($customer_id,'billing_phone',true)){
		update_user_meta($customer_id, 'billing_phone', $phone);
		update_user_meta($customer_id, 'shipping_phone', $phone);			
	}
	$customer = get_user_by( 'id', $customer_id); 
	if($customer && !get_user_meta($customer_id,'billing_first_name',true)){
		 update_user_meta($customer_id, 'billing_first_name', $customer->first_name);	
		 update_user_meta($customer_id, 'shipping_first_name', $customer->first_name);	
	} 
	if($order->get_created_via() == 'rest-api' && $order->get_meta('tida_order_type') != ', subscription_variation'){
	  /* $order->update_status('checkout-draft');
	  $order->update_status('pending'); */
	}
}
function update_next_payment_date_insubscription($subscription){
	if($subscription->get_time( 'next_payment' )){
		    $order_id = method_exists( $subscription, 'get_parent_id' ) ? $subscription->get_parent_id() : $subscription->order->id;
			if($subscription->get_time( 'next_payment' )){	
				$order = wc_get_order( $order_id );		
				$next_payment = date('YmdHis',$subscription->get_time( 'next_payment' ));
				$order->update_meta_data( 'next_payment' , $next_payment ) ;
				$order->save();
			}
	}
}
add_action( 'woocommerce_subscription_status_updated','update_next_payment_date_insubscription' );
add_action( 'woocommerce_subscription_payment_complete','update_next_payment_date_insubscription' );
add_action( 'woocommerce_subscription_renewal_payment_complete','update_next_payment_date_insubscription' );
function register_payment_creation_order_status() {
   register_post_status( 'wc-payment-creation', array(
       'label'                     => 'Order Payment Processing',
       'public'                    => true,
       'show_in_admin_status_list' => true,
       'show_in_admin_all_list'    => true,
       'exclude_from_search'       => false,
       'label_count'               => _n_noop( 'Order  <span class="count">(%s)</span>', 'Payment Processing <span 
class="count">(%s)</span>' )
   ) );
}
add_action( 'init', 'register_payment_creation_order_status','' );
function add_payment_creation_to_order_statuses( $order_statuses ) {
   $new_order_statuses = array();
   foreach ( $order_statuses as $key => $status ) {
       $new_order_statuses[ $key ] = $status;
	   $new_order_statuses['wc-payment-creation'] = 'Order Payment Processing';
   }
   return $new_order_statuses;
}
function create_razorpay_order($order_id){
	$order = wc_get_order($order_id);
	$order->update_status('pending');
	$currency = $order->get_currency();
	$total = $order->get_total(); 
	 $array = ["amount"=> (float)$total * 100,
		"currency"=> "$currency",
		"receipt"=> "$order_id",
		"notes"=>  ["woocommerce_order_number"=> "$order_id"]
		];
	$post_fields = json_encode($array); 
	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://api.razorpay.com/v1/orders',
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'POST',
	CURLOPT_POSTFIELDS =>$post_fields,
	CURLOPT_HTTPHEADER => array(
	'Content-Type: application/json',
	'Authorization: Basic '. base64_encode("rzp_live_S0jgYnlkOhur9i:GqkJFzxrv6qqZ5dYPTAvklQn")
	),
	));
	 $response = curl_exec($curl);
	$data = json_decode($response, true);
	$err = curl_error($curl);
	curl_close($curl);		
	if ($err) {
		 "cURL Error #:" . $err;
	} else {
		$data = json_decode($response, true); 
		if (isset($data['id'])) {
			$order->update_meta_data( 'razorpay_order_id' , $data['id'] ) ;
			$order->save();
		}else{
			$order->update_meta_data( 'razorpay_order_id' , $data ) ;
			$order->save();
		}
	}
}
add_action('woocommerce_order_status_changed', 'so_status_completed', 10, 3);
function so_status_completed($order_id, $old_status, $new_status)
{
	$order = wc_get_order($order_id);
    if(($old_status == 'pending' || $old_status == 'wc-pending') && ($new_status == 'payment-creation' || $new_status == 'wc-payment-creation')){
		/*  $order_items = $order->get_items();
			foreach ( $order_items as $item_id => $item ) { 
				$product = wc_get_product($item['product_id']);
				if($order->get_created_via() == 'rest-api' && ($product->get_type() == 'subscription_variation' || $product->get_type()
		 == 'variable-subscription')){
					$subscriptions = wcs_get_subscriptions_for_order($order_id, array( 'order_type' => 'any' ));
					if(empty($subscriptions)){
						$rz_subscription = new Tida_WC_Razorpay_Subscription(); 
						$rz_subsc = $rz_subscription->create_wc_subParams($order,$order_id,$item);
						$next_payment = date('YmdHis');
						$order->update_meta_data( 'created_subscription' , $next_payment ) ;
						$order->save();
					}
				}
			} */
		$order->update_status('pending');
		//create_razorpay_order($order_id);
		create_rz_sub_id( $order_id, $order);
	}else if(($old_status == 'pending' || $old_status == 'wc-pending') && ( $new_status == 'checkout-draft' || $new_status == 'wc-checkout-draft')){
		create_razorpay_order($order_id);
	}else if(($new_status == 'processing') || ($new_status == 'wc-processing')){
		if($order->get_transaction_id() || $order->get_meta('transaction_id') || $order->get_total() == 0){
			$order->update_status('completed');
		}else if($order->get_payment_method() == 'cheque' || $order->get_payment_method() == 'cod'){
			$order->update_status('on-hold');
			$order->set_payment_method('cod');
			$order->set_payment_method_title( 'Pay Cash' );
		}
	}else if(($new_status == 'completed') || ($new_status == 'on-hold')){
		if($order->get_payment_method() == 'cheque'){
			$order->set_payment_method('cod');
			$order->set_payment_method_title( 'Pay Cash' );
		}
	}
}
add_filter( 'wc_order_statuses', 'add_payment_creation_to_order_statuses' );
/* add_action( 'woocommerce_order_status_on-hold', 'create_rz_sub_id', 10, 2 ); */
add_action( 'woocommerce_order_status_processing', 'check_payment_metohd', 10, 2 );
add_action( 'woocommerce_order_status_payment_creation', 'create_rz_sub_id', 10, 2 );
add_action( 'woocommerce_order_status_checkout_draft', 'create_rz_sub_id', 10, 2 );
function check_payment_metohd( $order_id, $order) {
	
}
function create_rz_sub_id( $order_id, $order) {
	$order_items = $order->get_items();
	foreach ( $order_items as $item_id => $item ) { 
		if($variation_id = $item['variation_id']){
			$product = wc_get_product($item['variation_id']);
		}else{
			$product = wc_get_product($item['product_id']);
		}
    	if($order->get_created_via() == 'rest-api' && ($product->get_type() == 'subscription_variation' || $product->get_type()
 == 'variable-subscription')){
    	    $subscriptions = wcs_get_subscriptions_for_order($order_id);
           if(!$order->get_meta('razorpay_subscription_id')){
        	    $rz_subscription = new Tida_WC_Razorpay_Subscription(); 
        	    if(!$rz_subsc = $rz_subscription->getPaymentrzParams($order,$order_id,$item)){
					return array(
						'status' => TRUE,
						'data' => $order,
						'rz_subsc' => $rz_subsc,
						'message' => 'subscription Created on razorpay'
					); 
				}
           }else{
			   return array(
					'status' => FALSE,
					'data' => null,
					'message' => 'Something went wrong'
				); 
		   }
    	}
	}
}
add_action('woocommerce_order_status_pool-payment-rec', 'auto_change_booking_status_to_paid', 20, 2 );
function auto_change_booking_status_to_paid( $order_id, $order ) {
    if( $order->get_status() === 'wc-pending' || $order->get_status() === 'pending' ) {
        foreach( $order->get_items() as $item_id => $item ) {
            $product = wc_get_product($item['product_id']);
            if( $product->get_type() === 'booking' ) {
                $booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
                foreach( $booking_ids as $booking_id ) {
                    $booking = new WC_Booking($booking_id);
                    if( $booking->get_status() != 'unpaid' )
                        $booking->update_status( 'unpaid', 'order_note' );
                }
            }            
        }
    }else if( $order->get_status() === 'wc-completed' || $order->get_status() === 'completed' ) {
        foreach( $order->get_items() as $item_id => $item ) {
            $product = wc_get_product($item['product_id']);
            if( $product->get_type() === 'booking' ) {
                $booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
                foreach( $booking_ids as $booking_id ) {
                    $booking = new WC_Booking($booking_id);
                    if( $booking->get_status() != 'paid' )
                        $booking->update_status( 'paid', 'order_note' );
                }
            }
        } 
    }
}
add_action( 'woocommerce_admin_order_data_after_order_details', 'admin_order_display_delivery_order_id', 60, 1 );
function admin_order_display_delivery_order_id( $order ){
	$order_id = $order->get_id();
	if(get_field('person_name',$order_id)){
		$person_name = get_field('person_name',$order_id);
	}else if(get_post_meta($order->get_id(), 'person_name', true)){
		$person_name = get_post_meta($order->get_id(), 'person_name', true);
	}else if(get_post_meta($order->get_id(), '_wc_order_for_name', true)){
		$person_name = get_post_meta($order->get_id(), '_wc_order_for_name', true);
	}else if($order->get_meta('_wc_order_for_name')){
		$person_name = $order->get_meta('_wc_order_for_name');
	}else if($order->get_meta('person_name')){
		$person_name = $order->get_meta('person_name');
	}else if(get_field('_wc_order_for_name',$order_id)){
		$person_name = get_field('_wc_order_for_name',$order_id);
	}else{
		$customer = get_userdata($order->get_customer_id());
		$person_name = $customer->display_name;
		/* $person_name = 'No Data'; */
	}
    echo '<br clear="all"><p><strong>'.__('Booked For').':</strong> ' . $person_name . '</p>';
}