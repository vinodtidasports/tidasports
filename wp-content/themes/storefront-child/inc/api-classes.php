<?php
class Tida_Booking_Data_Store extends WC_Booking_Data_Store
{
    public function updatemeta($booking) // public instead of private so you can call it from the class instance
    {
        $this->update_post_meta( $booking );
    }
}
if (class_exists('RZP_Subscriptions')) {
class Tida_WC_Razorpay_Subscription extends RZP_Subscriptions
{
    function __construct() {
            $wcRazorpay = new WC_Razorpay(false);
            parent::__construct( 'RZP_Subscriptions', $wcRazorpay->getSetting('key_id'), $wcRazorpay->getSetting('key_secret') );
	}
    public function create_wc_subParams($order,$orderId,$product) 
    {  
		$WC_REST_Subscriptions_Controller = new WC_REST_Subscriptions_Controller();
        $request = new WP_REST_Request( 'POST', site_url().'/orders/'.$orderId );
		$request->set_param( "id", $orderId );
		$request->set_query_params( '?id='.$orderId );
        return $WC_REST_Subscriptions_Controller->create_subscriptions_from_order($request);
        /* $productId = $product['product_id'];
        $metadata = get_post_meta($productId); 
        $product = $this->getProductFromOrder($order); */
        //$this->createOrGetPlanId($metadata, $product, $order);
        /* $subscriptionData = $this->getSubscriptionCreateData($orderId); */
	}
    public function getPaymentrzParams1($order,$orderId,$product) 
    {  
        $WC_REST_Subscriptions_Controller = new WC_REST_Subscriptions_Controller();
        $subscriptionData = $this->getSubscriptionCreateData($orderId);	
		if($product['variation_id']){
			$product_id = $product['variation_id'];
		}else{
			$product_id = $product['product_id'];
		} $product_id;
		$trial_length = WC_Subscriptions_Product::get_trial_length( $product_id ); 
		$renewalDate = WC_Subscriptions_Product::get_first_renewal_payment_date( $product_id, $start_date );	
		$BillingCycle = WC_Subscriptions_Product::get_interval($product_id);  // how many times within the period to bill 
		$BillingPeriod = WC_Subscriptions_Product::get_period($product_id);    // Month/week/Year
		$subscription_length = WC_Subscriptions_Product::get_length($product_id);    // Month/week/Year
		if ($trial_length > 0)
        {
			$renewalDate = WC_Subscriptions_Product::get_first_renewal_payment_time($product_id);
            $subscriptionData['start_at'] = $renewalDate;
        }else{ 
			$productId = $product['product_id'];
			$variation_id = $product['variation_id'];
			$subscriptionData['quantity'] = 1;
			$subscriptionData['customer_notify'] = 1;
			$subscriptionData['total_count'] = $subscription_length;
		/* 	$subscriptionData['BillingPeriod'] = $BillingPeriod;
			$subscriptionData['subscription_length'] = $subscription_length; */
			$subscriptionData['expire_by'] = strtotime(WC_Subscriptions_Product::get_expiration_date($product_id));
			$subscriptionData['notes']['woocommerce_order_id'] =  "$orderId";
			$subscriptionData['notes']['woocommerce_product_id'] = "$productId";
			$subscriptionData['notes']['woocommerce_variation_id'] = "$variation_id";
		}
		$options = array("plan_id" => $subscriptionData['plan_id'],
		"quantity" => 1,
		"remaining_count" => $subscription_length - 1,
		"start_at" => strtotime($renewalDate),
		"schedule_change_at" => "now",
		"customer_notify" => 1);
		 echo 'subscription Request Data: <pre>'; print_r($subscriptionData);
        $order->update_meta_data( 'subscriptionData' , $subscriptionData ) ; 
		$order->save();
        try
        {
            $subscription = $this->api->subscription->create($subscriptionData);
			$subscription_id = $subscription['id'];
			$remaining_count = $subscription['remaining_count'];
			echo 'Response subscriptionData: <pre>'; print_r($subscription);
			$this->api->subscription->fetch($subscription_id)->update($options);
        }catch (Exception $e) {
            $message = $e->getMessage();
			echo "Woocommerce orderId: $orderId Subscription creation failed with the following message: $message";
            rzpSubscriptionErrorLog("Woocommerce orderId: $orderId Subscription creation failed with the following message: $message");
            throw new Errors\Error(
                $message,
                WooErrors\SubscriptionErrorCode::API_SUBSCRIPTION_CREATION_FAILED,
                400
            );
        }
       /*  $order->update_meta_data( 'razorpay_subscription_id' , $subscription_id ) ;
		$order->save(); */
   }
    public function getPaymentrzParams($order,$orderId,$product) 
    {  
        //$wcRazorpay = new WC_Razorpay(false);
        //$RZP_Subscriptions = new RZP_Subscriptions($wcRazorpay->getSetting('key_id'), $wcRazorpay->getSetting('key_secret'));
        $WC_REST_Subscriptions_Controller = new WC_REST_Subscriptions_Controller();
        /* $request = new WP_REST_Request( 'POST', site_url().'/orders/'.$orderId );
		$request->set_param( "id", $orderId );
		$request->set_query_params( '?id='.$orderId );
        $WC_REST_Subscriptions_Controller->create_subscriptions_from_order($request);
        $productId = $product['product_id'];
        $metadata = get_post_meta($productId); 
        $product = $this->getProductFromOrder($order); */
        //$this->createOrGetPlanId($metadata, $product, $order);
        $subscriptionData = $this->getSubscriptionCreateData($orderId);
		if($product['variation_id']){
			$product_id = $product['variation_id'];
		}else{
			$product_id = $product['product_id'];
		}
		$trial_length = WC_Subscriptions_Product::get_trial_length( $product_id ); 
		$subscription_length = WC_Subscriptions_Product::get_length($product_id); 
		if ($trial_length > 0) { }else{
			$productId = $product['product_id'];
			$variation_id = $product['variation_id'];
			$subscription_length = WC_Subscriptions_Product::get_length($product_id); 
			$subscriptionData['quantity'] = 1;
			$subscriptionData['customer_notify'] = 1;
			$subscriptionData['total_count'] = $subscription_length;
			$subscriptionData['expire_by'] = strtotime(WC_Subscriptions_Product::get_expiration_date($variation_id));
			$subscriptionData['notes']['woocommerce_order_id'] =  "$orderId";
			$subscriptionData['notes']['woocommerce_product_id'] = "$productId";
			$subscriptionData['notes']['woocommerce_variation_id'] = "$variation_id";
		}
        try
        {
            $subscription = $this->api->subscription->create($subscriptionData);
        }catch (Exception $e) {
            $message = $e->getMessage();
            rzpSubscriptionErrorLog("Woocommerce orderId: $orderId Subscription creation failed with the following message: $message");
            throw new Errors\Error(
                $message,
                WooErrors\SubscriptionErrorCode::API_SUBSCRIPTION_CREATION_FAILED,
                400
            );
        }
        $subscription_id = $subscription['id'];
        $order->update_meta_data( 'razorpay_subscription_id' , $subscription_id ) ;
		$order->save();
   }
}}
class Tida_Upload_media
{
function uploadFile()
{
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    //upload only images and files with the following extensions
    $file_extension_type = array('jpg', 'jpeg', 'jpe', 'gif', 'png', 'bmp', 'tiff', 'tif', 'ico', 'zip', 'pdf', 'docx');
	if(isset($_FILES)){
    $file_extension = strtolower(pathinfo($_FILES['async-upload']['name'], PATHINFO_EXTENSION));
    if (!in_array($file_extension, $file_extension_type)) {
        return wp_send_json(
            array(
                'success' => false,
                'data'    => array(
                    'message'  => __('The uploaded file is not a valid file. Please try again.'),
                    'filename' => esc_html($_FILES['async-upload']['name']),
                ),
            )
        );
    }
    $attachment_id = media_handle_upload('async-upload', null, []);

    if (is_wp_error($attachment_id)) {
        return wp_send_json(
            array(
                'success' => false,
                'data'    => array(
                    'message'  => $attachment_id->get_error_message(),
                    'filename' => esc_html($_FILES['async-upload']['name']),
                ),
            )
        );
    }

    if (isset($post_data['context']) && isset($post_data['theme'])) {
        if ('custom-background' === $post_data['context']) {
            update_post_meta($attachment_id, '_wp_attachment_is_custom_background', $post_data['theme']);
        }

        if ('custom-header' === $post_data['context']) {
            update_post_meta($attachment_id, '_wp_attachment_is_custom_header', $post_data['theme']);
        }
    }

    $attachment = wp_prepare_attachment_for_js($attachment_id);
    if (!$attachment) {
        return wp_send_json(
            array(
                'success' => false,
                'data'    => array(
                    'message'  => __('Image cannot be uploaded.'),
                    'filename' => esc_html($_FILES['async-upload']['name']),
                ),
            )
        );
    }
    if(isset($_POST['user_id'])) {
        $profile_url = wp_get_attachment_image_url( $attachment_id, 'thumbnail',true );
        $user_id = $_POST['user_id'];
    	  if(get_user_meta($user_id, 'avatar', true)){
    	      update_user_meta($user_id, 'avatar', $profile_url); 
    	  }else{
    	      add_user_meta($user_id, 'avatar', $profile_url);
    	  }
        $response['code'] = 200;
        $response['status'] = 'success';
        $response['message'] = 'Profile image updated';
        $response['user_id'] = $_POST['user_id'];
        $response['profile_url'] = $profile_url;
        $response['media_id'] = $attachment_id;
        return wp_send_json(
            array(
                'success' => true,
                'data'    => $response
            )
        );
    }
    return wp_send_json(
        array(
            'success' => true,
            'data'    => $attachment,
        )
    );
	}else{
		return wp_send_json(
        array(
            'success' => false,
            'data'    => 'Empty Data object',
        )
		);
	}
}
}


// Written by Saif
/*
require_once __DIR__ . '/vendor/autoload.php';

class Tida_Cloudinary_Upload_media
{
    private $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary(
            [
                'cloud' => [
                    'cloud_name' => CLOUDINARY_CLOUD_NAME,
                    'api_key'    => CLOUDINARY_API_KEY,
                    'api_secret' => CLOUDINARY_API_SECRET,
                ],
            ]
        );
    }

    public function updateProfileImage($user_id, $new_profile_url)
    {
        // Get the current profile image URL
        $current_profile_url = get_user_meta($user_id, 'avatar', true);

        // If there is an existing profile image, delete it from Cloudinary
        if ($current_profile_url) {
            $public_id = pathinfo($current_profile_url, PATHINFO_FILENAME);
            try {
                $this->cloudinary->uploadApi()->destroy($public_id);
            } catch (Exception $e) {
                return wp_send_json([
                    'success' => false,
                    'message' => 'Failed to delete old image from Cloudinary: ' . $e->getMessage(),
                ]);
            }
        }

        // Update or add the new profile image URL in user metadata
        if ($current_profile_url) {
            update_user_meta($user_id, 'avatar', $new_profile_url);
        } else {
            add_user_meta($user_id, 'avatar', $new_profile_url);
        }

        // Return a success response
        return wp_send_json([
            'success' => true,
            'message' => 'Profile image updated successfully',
            'profile_url' => $new_profile_url,
        ]);
    }
}
*/
class Tida_Upload_Media_Cloud {
    private $cloud_name;
    private $api_key;
    private $api_secret;

    public function __construct($cloud_name, $api_key, $api_secret) {
        $this->cloud_name = $cloud_name;
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
    }

    function get_auth_header() {
        return 'Basic ' . base64_encode($this->api_key . ':' . $this->api_secret);
    }

    function delete_image_from_cloudinary($public_id) {
        $cloudinary_url = "https://api.cloudinary.com/v1_1/{$this->cloud_name}/image/upload/{$public_id}";
        $auth_header = $this->get_auth_header();
        $response = wp_remote_request($cloudinary_url, array(
            'method'    => 'DELETE',
            'headers'   => array(
                'Authorization' => $auth_header
            )
        )); print_r($response);
        if (is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200) {
            return false;
        }
        return true;
    }
    public function update_profile_image($user_id, $new_profile_url) {
        $user_id = intval($user_id);
        $new_profile_url = esc_url_raw($new_profile_url);

        // Get the current profile image URL
        $current_profile_url = get_user_meta($user_id, 'avatar', true);

        // If there is an existing profile image, delete it from Cloudinary
        if ($current_profile_url) {
            $public_id = pathinfo($current_profile_url, PATHINFO_FILENAME);
            if (!$this->delete_image_from_cloudinary($public_id)) {
                return [
                    'success' => false,
                    'message' => 'Failed to delete old image from Cloudinary',
                ];
            }
        }

        // Update or add the new profile image URL in user metadata
        if ($current_profile_url) {
            update_user_meta($user_id, 'avatar', $new_profile_url);
        } else {
            add_user_meta($user_id, 'avatar', $new_profile_url);
        }

        return [
            'success' => true,
            'message' => 'Profile image updated successfully',
            'profile_url' => $new_profile_url,
        ];
    }
}