<?php
// Start session if not started
add_action('init', 'register_my_session');
function register_my_session(){
   if( !session_id() ) {
      session_start();
   }
}
// Clear cart after 30 minutes
add_action( 'init', 'woocommerce_clear_cart' );
function woocommerce_clear_cart() 
{
    global $woocommerce;
    $current_time = date( 'Y-m-d H:i:s');
    $expiry_in_seconds = 60*5;//1800; // 30 minutes
    if (!isset($_SESSION['active_time'])) 
    {
        $_SESSION['active_time'] = date('Y-m-d H:i:s');
    }
    else{
        // add 30 minutes
        $cart_expiry_time = strtotime($_SESSION['active_time']) + $expiry_in_seconds;
        // calculate seconds left
        $diff = $cart_expiry_time - strtotime($current_time);
        // round to minutes
        $remaining_minutes = floor($diff/60);
        // if time less than or equal to 1 minutes
        if($remaining_minutes<=1)
        {
            if (isset($_GET['clear-cart']) && $_GET['clear-cart'] == 1) { echo $remaining_minutes;
				// if ($woocommerce->cart) {  //line 7
					$woocommerce->cart->empty_cart();
			//	 }
                //WC()->session->set('cart', array());
            }
        }
    }
}
/* add_filter('wc_session_expiring', 'tida_filter_session_expiring' );
function tida_filter_session_expiring($seconds) {
    return 60;// * 60 * 23; // 23 hours
}
add_filter('wc_session_expiration', 'tida_filter_session_expired' );
function tida_filter_session_expired($seconds) {
    return 60;// * 60 * 24; // 24 hours
} */
function enqueue_child_style() {
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/widget/widget.css', array(), '1.0');
}
add_action('wp_enqueue_scripts', 'enqueue_child_style');
function enqueue_child_script() {
    if ( !is_admin() || !isset( $_GET['action'] ) || 'elementor' !== $_GET['action'] ) {
        wp_enqueue_script('child-script', get_stylesheet_directory_uri() . '/widget/custom.js', array('jquery'), '1.0', true);
    }
    if (is_singular('product')) {
        // Enqueue your custom style for single product pages
        wp_enqueue_style('single-product-style', get_theme_file_uri('/assets/css/single-product.css'));
        // Enqueue Swiper's styles
        wp_enqueue_style('swiper_style', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0');
        // Enqueue Swiper's script
        wp_enqueue_script('swiper_script', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0', true);
        // Optionally, enqueue your custom Swiper initialization script
        wp_enqueue_script('custom-swiper-script', get_theme_file_uri('/assets/js/custom-swiper.js'), array('swiper_script'), '1.0', true);
    }
    // Check if it's a blog archive or a single blog post
    if (is_home() || is_singular('post') || is_category() || is_tag()) {
        wp_enqueue_style(
            'child-theme-posts-css', // Handle for the stylesheet
            get_stylesheet_directory_uri() . '/assets/css/posts.css'
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_child_script');
/**
 * Allows to remove products in checkout page.
 * 
 * @param string $product_name 
 * @param array $cart_item 
 * @param string $cart_item_key 
 * @return string
 */
function slator_woocommerce_checkout_remove_item( $product_name, $cart_item, $cart_item_key ) {
    if ( is_checkout() ) {
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
        $remove_link = apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
            '<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">×</a>',
            esc_url(wc_get_cart_remove_url( $cart_item_key ) ),
            __( 'Remove this item', 'woocommerce' ),
            esc_attr( $product_id ),
            esc_attr( $_product->get_sku() )
        ), $cart_item_key );
        return '<span class="remove_item">' . $remove_link . '</span> <span>' . $product_name . '</span>';
    }
    return $product_name;
}
add_filter( 'woocommerce_cart_item_name', 'slator_woocommerce_checkout_remove_item', 10, 3 );
add_filter( 'woocommerce_add_to_cart_redirect', 'add_to_cart_checkout_redirection', 10, 1 );
function add_to_cart_checkout_redirection( $url ) {
    return wc_get_checkout_url();
}
add_action('template_redirect', 'skip_cart_page_redirection_to_checkout');
function skip_cart_page_redirection_to_checkout() {
    if( is_cart() ){
        if ( WC()->cart->get_cart_contents_count() == 0 ) {
            wp_redirect( site_url('/shop') );
        }else{
            wp_redirect( wc_get_checkout_url() );
        }
    }
}
add_filter('woocommerce_add_to_cart_validation', 'remove_cart_item_before_add_to_cart', 1, 3);
function remove_cart_item_before_add_to_cart($passed, $product_id, $quantity) {
    if (!WC()->cart->is_empty() && @$_GET['subscription_renewal'] == false) {
        WC()->cart->empty_cart();
    }
    return $passed;
}
// ------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------
// ---Saif Code---
// ------ Deactivate Sidebar in woo commerce pages -------
add_action( 'init', 'child_remove_default_sorting_storefront' );
function child_remove_default_sorting_storefront() {
  remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
}
function mb_remove_sidebar() {
    if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
        return false;
    }
}
add_filter( 'is_active_sidebar', 'mb_remove_sidebar', 10, 2 );
// ------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------
// ------ Type Listing Page -------
// Shortcode
function product_type_listing($atts) {
    // Query
    $atts = shortcode_atts(array(
        'type' => 'variable-subscription',
    ), $atts);
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'tax_query'      => array(
        array(
            'taxonomy' => 'product_type',
            'field'    => 'slug',
            'terms'    => $atts['type'],
            ),
        ),
    );
    $products_query = new WP_Query($args);
    if ($products_query->have_posts()) {
        ob_start(); // Start output buffering
        ?>
        <ul class="products columns-3">
            <?php while ($products_query->have_posts()) : $products_query->the_post(); ?>
                <?php wc_get_template_part('content', 'product'); ?>
            <?php endwhile; ?>
        </ul>
        <?php
        wp_reset_postdata(); // Reset post data
        return ob_get_clean(); // Return buffered content
    } else {
        return 'No products found.';
    }
}
add_shortcode('product_type_listing_pass_type', 'product_type_listing');
// ------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------
// ------ Showing header conditionally if header is not in page -------
// Header shortcode showing conditionally
function child_is_header_visible() {
    // Check if the header content exists in the current page's HTML
    $header_content = '[hfe_template id="5488"]';
    $page_content = get_the_content();
    if (strpos($page_content, $header_content) !== false) {
        // Header content is already present on the page
        return true;
    } else {
        // Header content is not present on the page
        return false;
    }
}
function show_header_if_not_header_visible() {
    // Check if the header content is already visible on the page
    if (child_is_header_visible()) {
        // Header is already visible, return empty string
        return '';
    } else {
        $header_shortcode = '[hfe_template id="5488"]';
        return do_shortcode($header_shortcode);
    }
}
add_shortcode('show_header_if_not_header', 'show_header_if_not_header_visible');
// ------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------
// ------ Showing banner of page -------
function child_show_banner_of_page($atts) {
    $user_id = get_current_user_id();
    $atts = shortcode_atts(array(
        'title' => get_the_title(),
        'description' =>"look your nearest destination!",
        'avatar' => get_user_meta($user_id, 'avatar', true),
    ), $atts);
    ob_start(); // Start output buffering
    ?>
    <style>
        .banner_icon{
            height:auto;
        }
    </style>
    <header class="woocommerce-products-header">
        <div class="woocommerce-products-header__wrapper">
            <?php
            $icon = $title_icon = '';
            if (get_field('icon')) {
                $icon = get_field('icon');
                echo $icon;
            }
            if ($title_icon && $title_icon['url']) {
                echo '<img src="' . esc_url($title_icon['url']) . '" alt="icon" />';
            }
            if($atts['avatar']){
                echo '<img src="' . esc_url($atts['avatar']) . '" alt="icon" class="banner_icon" />';
            }
            ?>
            <div class="woocommerce-products-header__content-wrapper">
                <?php if (apply_filters('woocommerce_show_page_title', true) && function_exists('is_woocommerce') && is_woocommerce()) : ?>
                    <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
                <?php else: ?>
                    <h1 class="woocommerce-products-header__title page-title"><?php echo $atts['title']; ?></h1>
                <?php endif; ?>
                <?php
                /**
                 * Hook: woocommerce_archive_description.
                 *
                 * @hooked woocommerce_taxonomy_archive_description - 10
                 * @hooked woocommerce_product_archive_description - 10
                 */
                do_action('woocommerce_archive_description');
                ?>
                <p class="woocommerce-products-header__description"><?php echo $atts['description']; ?></p>
            </div>
        </div>
    </header>
    <?php
    // Get the buffered content and clean the buffer
    $header_content = ob_get_clean();
    return $header_content;
}
add_shortcode('show_banner_of_page', 'child_show_banner_of_page');
// ------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------
// ------ Getting Texonomy Details -------
function getting_tida_store_and_category_function($atts) {
    if(isset($atts['type'])){	
		$sport_terms = array();
		$args = array(
			'taxonomy'   => $atts['taxonomy'],
			'hide_empty' => true, // Only return terms with products
		);
		$terms = get_terms( $args );
		if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) { 
				// Get the products within this taxonomy term
				$args = array(
							'post_type'      => 'product',
							'posts_per_page' => -1, // Adjust as needed
							'tax_query'       => array(
								array(
									'taxonomy' => $atts['taxonomy'],
									'field'    => 'id',
									'terms'    => $term->term_id,
									'operator' => 'IN',
								),
							),
							'meta_query'      => array(
								array(
									'key'     => 'product_type',
									'value'   => $atts['type'], // Check for variable products
									'compare' => 'LIKE',
								),
							),
						);
				$query = new WP_Query( $args ); 
				if ( $query->have_posts() ) { 
					$sport_terms[] = $term;
				}
				wp_reset_postdata();
			}
		}
	}else{
		$atts = shortcode_atts(array(
			'taxonomy' => 'sport',
		), $atts);
		$sport_terms = get_terms(array(
			'taxonomy' => $atts['taxonomy'],
			'hide_empty' => true,
		));
	}
    // var_dump($sport_terms);
    $sport_terms_json = json_encode($sport_terms);
    return $sport_terms_json;
}
// Register shortcode
add_shortcode('getting_tida_store_and_category', 'getting_tida_store_and_category_function');

// ------ Getting Texonomy Details -------
function getting_tida_sports_and_category_function($atts) {
    if(isset($atts['type'])){	
		$sport_terms = array();
		$args = array(
			'taxonomy'   => $atts['taxonomy'],
			'hide_empty' => true, // Only return terms with products
		);
		$terms = get_terms( $args );
		if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) { 
				// Get the products within this taxonomy term
				$args = array(
							'post_type'      => 'product',
							'posts_per_page' => -1, // Adjust as needed
							'tax_query'       => array(
								array(
									'taxonomy' => $atts['taxonomy'],
									'field'    => 'id',
									'terms'    => $term->term_id,
									'operator' => 'IN',
								),
							),
							'meta_query'      => array(
								array(
									'key'     => 'product_type',
									'value'   => $atts['type'], // Check for variable products
									'compare' => 'LIKE',
								),
							),
						);
				$query = new WP_Query( $args ); 
				if ( $query->have_posts() ) { 
					$sport_terms[] = $term;
				}
				wp_reset_postdata();
			}
		}
	}else{
		$atts = shortcode_atts(array(
			'taxonomy' => 'sport',
		), $atts);
		$sport_terms = get_terms(array(
			'taxonomy' => $atts['taxonomy'],
			'hide_empty' => true,
		));
	}
    // var_dump($sport_terms);
    $sport_terms_json = json_encode($sport_terms);
    return $sport_terms_json;
}
// Register shortcode
add_shortcode('getting_tida_sports_and_category', 'getting_tida_sports_and_category_function');
// ------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------
// ------ Showing Texonomy Details -------
function showing_tida_store_and_category_function($atts) {
	extract($atts); 
    // print_r($atts);
    ob_start(); // Start output buffering
    $showing_tida_sports_and_category_template = get_stylesheet_directory() . '/widget/store_and_category_listing.php';
    if ( file_exists( $showing_tida_sports_and_category_template ) ) {
        include( $showing_tida_sports_and_category_template );
    }
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('showing_tida_store_and_category', 'showing_tida_store_and_category_function');// ------ Showing Texonomy Details -------
function showing_tida_sports_and_category_function($atts) {
	if(isset($atts['type'])){
	}else{
    $atts = shortcode_atts( array(
        'taxonomy' => 'sport',
     ), $atts );
    // print_r($atts);
	}
    extract($atts);
    // print_r($atts);
    ob_start(); // Start output buffering
    // echo "Hello Saif how are you i am from ".get_stylesheet_directory() . "/widget/sports_and_category_listing.php";
    $showing_tida_sports_and_category_template = get_stylesheet_directory() . '/widget/sports_and_category_listing.php';
    if ( file_exists( $showing_tida_sports_and_category_template ) ) {
        include( $showing_tida_sports_and_category_template );
    }
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('showing_tida_sports_and_category', 'showing_tida_sports_and_category_function');
// ------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------
// ------ Getting Other Posts in Single Details Page -------
function getting_other_post_for_single_details_page_function( $atts ) {
    $atts = shortcode_atts( array(
        'type' => 'post',
        'exclude_id' => '',
        'post_per_page' => 3,
    ), $atts );
    // Sanitize attributes
    $post_type = sanitize_text_field( $atts['type'] );
    $exclude_id = intval( $atts['exclude_id'] );
    $post_per_page = intval( $atts['post_per_page'] );
    // Query arguments
    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => $post_per_page,
        'post__not_in' => array( $exclude_id ),
    );
    // Query posts
    $other_post_query = new WP_Query( $args );
    return json_encode($other_post_query);
}
add_shortcode( 'getting_other_post_for_single_details_page', 'getting_other_post_for_single_details_page_function' );
// ------ Showing Other Posts in Single Details Page -------
function showing_other_post_for_single_details_page_function($atts) {
    $atts = shortcode_atts( array(
        'type' => 'post',
        'exclude_id' => '',
        'post_per_page' => 3,
    ), $atts );
    extract($atts);
    // ob_start(); // Start output buffering
    $showing_other_post_for_single_details_page_template = get_stylesheet_directory() . '/widget/other_post_for_single_details_page.php';
    if ( file_exists( $showing_other_post_for_single_details_page_template ) ) {
        // var_dump($atts);
        include( $showing_other_post_for_single_details_page_template );
    }
    // return ob_get_clean(); // Return the buffered content
}
add_shortcode('showing_other_post_for_single_details_page', 'showing_other_post_for_single_details_page_function');
// ------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------
function wooc_extra_register_fields() {?>
       <p class="form-row form-row-first">
       <label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?><span class="required">*</span></label>
       <input type="text" class="input-text" required name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST[
'billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
       </p>
       <p class="form-row form-row-last">
       <label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?><span class="required">*</span></label>
       <input type="text" class="input-text" required name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST[
'billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
       </p>
       <p class="form-row form-row-wide">
       <label for="reg_phone_number"><?php _e( 'Phone', 'woocommerce' ); ?><span class="required">*</span></label> 
       <input type="number" class="input-text" required name="phone_number" id="reg_phone_number" value="<?php  if ( ! empty( $_POST['phone_number']
 ) ) esc_attr_e( $_POST['phone_number'] ); ?>" />
       </p>
       <div class="clear"></div>
       <?php
 }
 add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );
/**
* register fields Validating.
*/
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
	if ( isset( $_POST['phone_number'] ) && empty( $_POST['phone_number'] ) ) {
        	$validation_errors->add( 'phone_number_error', __( '<strong>Error</strong>: Phone Number is required!', 'woocommerce' ) );
    	} elseif ( isset( $_POST['phone_number'] ) && !preg_match( '/^\d{10}$/', $_POST['phone_number'] ) ) {
        	$validation_errors->add( 'phone_number_error', __( '<strong>Error</strong>: Phone Number must be exactly 10 digits!', 'woocommerce' ) );
    	}
	if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
		 $validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
	}
	if ( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
		 $validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
	}
	return $validation_errors;
}
add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );
// giving access to partner manager active or inactive products
function add_woocommerce_capabilities_to_partner_role() {
    // Get the partner role object
    $partner_role = get_role('partner');
    if (!$partner_role) {
        // Add 'partner' role if it doesn't exist
        add_role('partner', 'Partner', array(
            'read' => true, // Give basic read capability
            'edit_products' => true,
            'edit_published_products' => true,
            'edit_others_products' => true,
            'publish_products' => true,
            'download_orders_csv' => true, // Add capability to download orders as CSV
	    'edit_shop_orders' => true,
	    'manage_woocommerce' => true,
        ));
    }
}
add_action('init', 'add_woocommerce_capabilities_to_partner_role');
function add_woocommerce_capabilities_to_customer_role() {
    // Get the partner role object
    $customer_role = get_role('inactive');
    if (!$customer_role) {
        // Add 'partner' role if it doesn't exist
        add_role('inactive', 'Inactive', array(
            'read' => false, // Give basic read capability
            'delete_account' => false,
        ));
    }
}
add_action('init', 'add_woocommerce_capabilities_to_customer_role');
// changing status of products
function toggle_product_status_callback() {
    // Check if the user has permission to edit products
    if (!current_user_can('edit_products')) {
        wp_send_json_error('Permission denied');
    }
    // Get product ID and current status from the AJAX request
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $current_status = get_post_status($product_id);
    // Toggle the product status
    $new_status = ($current_status === 'publish') ? 'draft' : 'publish';
    $updated = wp_update_post(array(
        'ID' => $product_id,
        'post_status' => $new_status,
    ));
    // Check if the update was successful
    if ($updated) {
        wp_send_json_success('Product status toggled successfully');
    } else {
        wp_send_json_error('Failed to toggle product status');
    }
}
add_action('wp_ajax_toggle_product_status', 'toggle_product_status_callback');
// Hook to handle download orders action
add_action('admin_post_download_orders_csv', 'download_orders_csv');
function download_orders_csv() {
    // Set headers to prompt file download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="woocommerce_orders.csv"');
    // Create CSV file
    $output = fopen('php://output', 'w');
    // Add CSV header
    fputcsv($output, array(
        'Order ID',
        'Order Date',
        'Customer Name',
        'Customer Email',
        'Billing First Name',
        'Billing Last Name',
        'Billing Company',
        'Billing Address 1',
        'Billing Address 2',
        'Billing City',
        'Billing State',
        'Billing Postcode',
        'Billing Country',
        'Shipping First Name',
        'Shipping Last Name',
        'Shipping Company',
        'Shipping Address 1',
        'Shipping Address 2',
        'Shipping City',
        'Shipping State',
        'Shipping Postcode',
        'Shipping Country',
        'Order Status',
        'Payment Method',
        'Shipping Method',
        'Item Name',
        'Item Quantity',
        'Item Price',
        'Item Total'
    ));
    // Get order data from POST data
    $data = isset($_POST['data']) ? json_decode(stripslashes($_POST['data']), true) : array();
    // Add orders data to CSV
    foreach ($data as $order_id) {
        $order = wc_get_order($order_id);
        if ($order) {
            // Loop through order items
            foreach ($order->get_items() as $item_id => $item) {
                $product = $item->get_product();
                fputcsv($output, array(
                    $order->get_id(),
                    $order->get_date_created()->date('Y-m-d H:i:s'),
                    $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
                    $order->get_billing_email(),
                    $order->get_billing_first_name(),
                    $order->get_billing_last_name(),
                    $order->get_billing_company(),
                    $order->get_billing_address_1(),
                    $order->get_billing_address_2(),
                    $order->get_billing_city(),
                    $order->get_billing_state(),
                    $order->get_billing_postcode(),
                    $order->get_billing_country(),
                    $order->get_shipping_first_name(),
                    $order->get_shipping_last_name(),
                    $order->get_shipping_company(),
                    $order->get_shipping_address_1(),
                    $order->get_shipping_address_2(),
                    $order->get_shipping_city(),
                    $order->get_shipping_state(),
                    $order->get_shipping_postcode(),
                    $order->get_shipping_country(),
                    $order->get_status(),
                    $order->get_payment_method(),
                    $order->get_shipping_method(),
                    $item->get_name(),
                    $item->get_quantity(),
                    $product ? $product->get_price() : '',
                    $item->get_total()
                ));
            }
        }
    }
    // Close file handle
    fclose($output);
    // Stop WordPress execution
    exit;
}
// Woocommerce Single Page redirection stop
add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );
// function to fetch team members
function get_team_members() {
    $team_query = new WP_Query(array(
        'post_type' => 'team',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'ASC'
    ));
    $team_members = array();
    if ($team_query->have_posts()) {
        while ($team_query->have_posts()) {
            $team_query->the_post();
	    $social_icons = array();
	    if (have_rows('social_links', get_the_ID())) {
 		while (have_rows('social_links', get_the_ID())) : the_row();
		$social_icon = get_sub_field('social_icon');
		$social_link = get_sub_field('social_link');
		if (!empty($social_icon) && !empty($social_link)) {
    			$social_icons[] = '<a href="' . esc_url($social_link) . '" target="_blank"><img src="' . esc_url($social_icon) . '" alt="' . 
get_the_title() . '" /></a>';
		}
		endwhile;
	    }
            $tags_content = array();
            if (have_rows('tags', get_the_ID())) {
 		while (have_rows('tags', get_the_ID())) : the_row();
		$tag = get_sub_field('tag'); // Assuming 'tag' is the sub-field name
		if (!empty($tag)) {
			$tags_content[] = '<p class="tag">' . $tag . '</p>';
        	}
		endwhile;
	    }
            // Get the description from custom field and trim to 50 words
            $description = get_post_meta(get_the_ID(), 'description', true);
            $team_member = array(
                'name' => get_the_title(),
                'designation' => get_post_meta(get_the_ID(), 'designation', true),
                'description' => $description,
                'featuredImage' => get_the_post_thumbnail_url(),
                'url' => get_permalink(),
                'social_icons' => implode(' ', $social_icons),
                'tags' => implode(' ', $tags_content)
            );
            $team_members[] = $team_member;
        }
        wp_reset_postdata();
    }
    return $team_members;
}
// Shortcode function to display team members
function team_shortcode_fun() {
    $team_members = get_team_members();
    $output = '<section class="teams">';
    $output .= '<div class="team-wrapper">';
    foreach ($team_members as $member) {
	//print_r('Tags'. $member['tags']);
        $output .= '<div class="team">';
        $output .= '<div><img src="' . $member['featuredImage'] . '" alt="' . $member['name'] . '"></div>';
        $output .= '<h2>' . $member['name'] . '</h2>';
        $output .= '<p>' . $member['designation'] . '</p>';
	$output .= '<div class="tags">' . $member['tags'] . '</div>';
        $output .= '<p>' . $member['description'] . '</p>';
        $output .= '<div class="social-icons">' . $member['social_icons'] . '</div>';
        $output .= '</div>';
    }
    $output .= '</div>';
    $output .= '</section>';
    return $output;
}
add_shortcode('team', 'team_shortcode_fun');
add_filter('woocommerce_available_payment_gateways', 'conditional_payment_gateways', 10, 1);
function conditional_payment_gateways($available_gateways) {
	$enable_cod = false;
	if (! WC()->cart ) {  
		return false;
	}else{
		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$productId = $cart_item['product_id'];
			$partner_id = get_field('partner_manager',$productId);
			$enable_cod = get_user_meta( $partner_id, 'enable_cod', true );
			if($productId){
				$product = wc_get_product( $productId );
				if( $product->is_type('booking')){
					$enable_cod = 0;
				}
			}
		} 
		if(!$enable_cod){ 
			unset($available_gateways['cod']); 
			unset($available_gateways['cheque']); 
		} 
	}
	return $available_gateways;
}
//add_filter( 'wc_bookings_get_time_slots_html', 'change_some_action1', 1 );
function change_some_action1( $rg )
{
		return apply_filters( 'wc_bookings_get_time_slots_html', $block_html, $available_blocks, $blocks, $this->product );
}
// ------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------
function complete_payment_ajax() {
    // Verify the nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'complete_payment_nonce')) {
        wp_send_json_error('Nonce verification failed');
    }
    // Check if the user has permission
    if (!current_user_can('partner')) {
        wp_send_json_error('Unauthorized access');
    }
    // Check if the order ID is provided
    if (!isset($_POST['order_id'])) {
        wp_send_json_error('Order ID not provided');
    }
    $order_id = absint($_POST['order_id']);
    // Get the order
    $order = wc_get_order($order_id);
    if (!$order) {
        wp_send_json_error('Order not found');
    }
    // Mark the order as completed
    $order->update_status('completed');
    // Respond with success
    wp_send_json_success('Payment completed successfully!');
}
// Hook the function to an AJAX action
add_action('wp_ajax_complete_payment', 'complete_payment_ajax');
// ------------------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------------------
// Add custom body class based on URL parameters
function add_custom_body_class($classes) {
    // Check if 'mode' parameter is present in the URL
    if (isset($_GET['mode']) && $_GET['mode'] === 'mobile') {
        // Add the custom class to the body classes array
        $classes[] = 'mode_mobile';
    }
    return $classes;
}
add_filter('body_class', 'add_custom_body_class');
function add_custom_body_class_js() {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to add class if body already has mode_mobile class
    	    function addClassIfPresent() {
    		if (sessionStorage.getItem('mode_mobile_present')) {
        		document.body.classList.add('mode_mobile');
    		}
	    }
            // Add class to body when the page loads
            addClassIfPresent();
            // Store the class presence in sessionStorage when the user navigates to another page
            window.addEventListener('unload', function() {
                if (document.body.classList.contains('mode_mobile')) {
                    sessionStorage.setItem('mode_mobile_present', true);
                } else {
                    sessionStorage.removeItem('mode_mobile_present');
                }
            });
        });
    </script>
    <?php
}
add_action('wp_footer', 'add_custom_body_class_js');
 add_filter( 'woocommerce_coupon_is_valid', 'tida_woocommerce_coupon_is_valid_filter', 10, 3 );
/**
 * Function for `woocommerce_coupon_is_valid` filter-hook.
 * 
 * @param  $true   
 * @param  $coupon 
 * @param  $that   
 *
 * @return 
 */
function tida_woocommerce_coupon_is_valid_filter( $true, $coupon, $that ){
	$type=[]; 
	if(!WC()->cart){ return $true;}else{
		foreach( WC()->cart->get_cart() as $cart_item ){
			$product_id = $cart_item['product_id'];
			$product = wc_get_product( $product_id );
			if($product->is_type('variable') && get_post_meta('_subscription_price',$product_id,true)
				&& (get_post_meta($product_id,'variable-subscription', true)
			|| get_post_meta($product_id,'subscription_variation', true))){
				$type[]='variable-subscription';
			}else if($product->is_type('variable-subscription')){
				$type[]='variable-subscription';
			}else if($product->is_type('variable')){
				$type[]='variable';
			}else if($product->is_type('booking')){
				$type[] ='booking';
			}
		}
		$discount_type = $coupon->get_discount_type();
		if(($discount_type == 'recurring_percent' || $discount_type == 'recurring'  || $discount_type == 'recurring_fee') && in_array(
'variable-subscription',$type)) {
			return $true;
		}else if(!in_array('variable-subscription',$type)) {
			return $true;
		}else {
			die ('<div class="woocommerce-error woocommerce-message" role="alert">Coupon invalid for selected item.</span>');
		}
	}
}
//-----------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------
/*
add_filter('gform_field_validation_4', function($result, $value, $form, $field) {
    if (!preg_match('/^\d{10}$/', $value)) {
        $result['is_valid'] = false;
        $result['message'] = 'Phone number must be exactly 10 digits.';
    }
    return $result;
}, 10, 4);
*/
//-----------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------
function tida_woocommerce_order_status_failed( $order_id, $order ) {
	if($order){
		$order_items = $order->get_items();
		foreach ( $order_items as $item_id => $item ) {
			$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
			if ( $booking_ids ) {
				foreach ( $booking_ids as $booking_id ) {
					$booking = new WC_Booking( $booking_id );
					if( $booking->get_status() == 'in-cart' || $booking->get_status() == 'unpaid' ){
						$booking->update_status( 'cancelled', 'order_note' );
					} 
					return array(
						'status' => true,
						'user_data' => 'update booking status'
					);
				}
			}
		}
	}
}
add_action('woocommerce_order_status_failed', 'tida_woocommerce_order_status_failed', 15, 2);
add_action('woocommerce_order_status_cancelled', 'tida_woocommerce_order_status_failed', 15, 2);
//-----------------------------------------------------------------------------------------------
//-----------------------------------------------------------------------------------------------
// Allow editors to use ACF
function my_acf_settings_capability( $capability ) {
    return 'edit_pages';
}
add_filter('acf/settings/capability', 'my_acf_settings_capability');
// Collaboration Page ShortCode
function display_collaboration($atts) {
    // Extract attributes with default values
    $atts = shortcode_atts(
        array(
            'repeater' => 'collaboration_cities',
            'subfield' => 'city',
        ),
        $atts,
        'tida_collaboration_shortcode'
    );
    // Initialize output
    $output = '';
    // Check if the repeater field has rows of data
    if (have_rows($atts['repeater'])) {
        $output .= '<div class="collaborations-widget"><div class="collaborations-widget-container">';
        // Loop through the rows of data
        while (have_rows($atts['repeater'])) {
            the_row();
            // Get the subfield value
            $link = get_sub_field($atts['subfield']);
            if ($link) {
                $url = esc_url($link['url']);
                $title = esc_html($link['title']);
                $target = $link['target'] ? ' target="' . esc_attr($link['target']) . '"' : '';
                // Generate the HTML output
                $output .= '<a href="' . $url . '"' . $target . '>';
                $output .= '<div class="collaborations-widget-container__content">';
                $output .= '<h3>' . $title . '</h3>';
                $output .= '<svg class="icon-right" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">';
                $output .= '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 
9"/>';
                $output .= '</svg>';
                $output .= '</div></a>';
            }
        }
        $output .= '</div></div>';
    }
    return $output;
}
add_shortcode('tida-collaboration', 'display_collaboration');
// This function shows products in custom pages
function page_related_products_shortcode($atts) {
    // Default attributes
    $atts = shortcode_atts(
        array(
            'product_cat' => '',
            'sport'       => '',
            'orderby'     => 'date',
            'limit'       => 3,
            'ids'         => '',
	    'base_url'    => '',
        ), $atts, 'related_products'
    );
    // Query arguments
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => intval($atts['limit']),
        'orderby'        => $atts['orderby'],
        'order'          => 'ASC',
	'post_status'    => array('publish', 'private'),
        'tax_query'      => array(
            'relation' => 'AND',
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $atts['product_cat'],
            ),
            array(
                'taxonomy' => 'sport',
                'field'    => 'slug',
                'terms'    => $atts['sport'],
            ),
        ),
    );
    // If specific product IDs are provided, override other filters
    if (!empty($atts['ids'])) {
        $args['post__in'] = array_map('intval', explode(',', $atts['ids']));
        unset($args['tax_query']); // Ignore taxonomy if specific IDs are given
    }
    $products = new WP_Query($args);
    if (!$products->have_posts()) {
        return '<p>No products found.</p>';
    }
    // Remove the "Private:" prefix from the product title
    if (!function_exists('remove_private_prefix')) {
        function remove_private_prefix($title, $id) {
            // Only modify the title if it's a private product
            if (get_post_status($id) === 'private') {
                $title = str_replace('Private: ', '', $title);
            }
            return $title;
        }
    }
    add_filter('the_title', 'remove_private_prefix', 10, 2);
    $output = '<ul class="page-realted-products">';
    while ($products->have_posts()) {
        $products->the_post();
        global $product;
	// Product link
        $product_link = !empty($atts['base_url']) 
            ? trailingslashit($atts['base_url']) . $product->get_slug() 
            : get_permalink();
        $output .= '<li class="page-realted-product">
    <a href="' . esc_url($product_link) . '" class="content-product-template__product">
        <div class="content-product-template__product__image-wrapper">
            ' . woocommerce_get_product_thumbnail() . '
        </div>
        <div class="content-product-template__product__content">
            <div class="content-product-template__product__content__meta">
                <div class="content-product-template__product__content__meta_ratings">' .
                    wc_get_rating_html($product->get_average_rating()) .
                '</div>
                <div class="content-product-template__product__content__meta_price">' .
                    $product->get_price_html() .
                '</div>
            </div>
            <div class="content-product-template__product__content__main">
                <h1 class="content-product-template__product__content__main_title">
                    ' . get_the_title() . '
                </h1>
                <p class="content-product-template__product__content__main_address"><img 
src="https://tidasports.com/wp-content/uploads/2024/03/01_align_center.png" width="20" height="20" />' .
                    esc_html(get_post_meta(get_the_ID(), 'address', true)) . 
                '</p>
            </div>
        </div>
    </a>
</li>';
    }
    $output .= '</ul>';
    wp_reset_postdata();
    return $output;
}
add_shortcode('page_related_products', 'page_related_products_shortcode');
add_filter('template_include', 'school_collaboration_product_template', 99);
function school_collaboration_product_template($template) {
    if (is_singular('product') && strpos($_SERVER['REQUEST_URI'], 'school-collaboration') !== false) {
        error_log('Custom School Collaboration Template Loaded');
        $new_template = locate_template('single-product-school-collaboration.php');
        if (!empty($new_template)) {
            return $new_template;
        }
    } else {
        error_log('Default Template Loaded');
    }
    return $template;
}
add_action('init', 'custom_school_collaboration_rewrite');
function custom_school_collaboration_rewrite() {
    add_rewrite_rule(
        '^school-collaboration/([^/]*)/?',
        'index.php?product=$matches[1]',
        'top'
    );
}
// Flush rules on activation
function flush_rewrite_rules_on_activation() {
    custom_school_collaboration_rewrite();
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'flush_rewrite_rules_on_activation');
// ----------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------
// Showcase Short Code
// This function creates a shortcode for the showcase archive template with additional attributes
function showcase_archive_shortcode($atts) {
    // Default attributes
    $atts = shortcode_atts(
        array(
            'post_type'      => 'showcase', // Custom post type slug
            'posts_per_page' => 3,          // Number of posts per page
            'orderby'        => 'date',     // Order by attribute (date, title, etc.)
            'order'          => 'DESC',     // Order direction (ASC or DESC)
            'limit'          => 3,          // Limit the number of posts
            'ids'            => '',         // Specific post IDs
        ), $atts, 'showcase_archive'
    );
    // Prepare query arguments
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
        'post_type'      => $atts['post_type'],
        'posts_per_page' => intval($atts['posts_per_page']),
        'orderby'        => $atts['orderby'],
        'order'          => $atts['order'],
        'paged'          => $paged,
    );
    // If specific post IDs are provided, override other filters
    if (!empty($atts['ids'])) {
        $args['post__in'] = array_map('intval', explode(',', $atts['ids']));
        unset($args['orderby']); // Ignore orderby if specific IDs are given
        unset($args['tax_query']); // Ignore taxonomy if specific IDs are given
    } else {
        $args['posts_per_page'] = intval($atts['limit']); // Limit the number of posts
    }
    $custom_query = new WP_Query($args);
    // Start output buffering
    ob_start();
    // HTML structure
    echo '<section class="showcase_archive_template">';
    echo '<div class="showcase_archive_template__container">';
    // Start the Loop
    if ($custom_query->have_posts()) {
        while ($custom_query->have_posts()) {
            $custom_query->the_post();
            $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
            $title = get_the_title();
            $content = get_the_content();
            $permalink = get_the_permalink();
            // Strip any HTML tags and images from the content
            $content = wp_strip_all_tags($content); 
            // Limit content to 15 words
            $words = explode(' ', $content);
            $content = implode(' ', array_slice($words, 0, 15)) . '...';
            // Output the showcase item
            echo '<div class="showcase_archive_template__container__wrapper">';
            echo '    <div class="showcase_archive_template__container__wrapper__img">';
            echo '        <img src="' . esc_url($thumbnail_url) . '" alt="image" />';
            echo '    </div>';
            echo '    <div class="showcase_archive_template__container__wrapper__content">';
            echo '        <div class="showcase_archive_template__container__wrapper__content__details">';
            echo '            <h1 class="showcase_archive_template__container__wrapper__content__details__title">' . esc_html($title) . '</h1>';
            echo '            <p class="showcase_archive_template__container__wrapper__content__details__description">' . esc_html($content) . 
'</p>';
            echo '        </div>';
            echo '        <div class="showcase_archive_template__container__wrapper__content__button">';
            echo '            <a href="' . esc_url($permalink) . '">Watch Now</a>';
            echo '        </div>';
            echo '    </div>';
            echo '</div>';
        }
	/*
        // Pagination
        $big = 999999999; // need an unlikely integer
        $pagination_links = paginate_links(array(
            'base'      => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format'    => '?paged=%#%',
            'current'   => max(1, get_query_var('paged')),
            'total'     => $custom_query->max_num_pages,
            'prev_text' => __('Prev'),
            'next_text' => __('Next'),
        ));
        echo '<div class="pagination showcase_pagination">' . $pagination_links . '</div>';
	*/
    } else {
        echo "<h3 class='showcase_archive_template__container__wrapper__content__details__title'>No Showcase Found</h3>";
    }
    echo '</div>';
    echo '</section>';
    // Restore original Post Data
    wp_reset_postdata();
    // Return the output
    return ob_get_clean();
}
add_shortcode('showcase_archive', 'showcase_archive_shortcode');
// ----------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------
// Disable COD for Bookings Saif
add_filter('woocommerce_available_payment_gateways', 'tida_sports_unset_gateway_by_product_type');
function tida_sports_unset_gateway_by_product_type($available_gateways) {
    if (is_admin() && !defined('DOING_AJAX')) {
        return $available_gateways; // Exit early if in admin area and not doing AJAX
    }
    $disable_product_type = array('booking'); // Define the product types to disable COD
    foreach (WC()->cart->get_cart() as $cart_item) {
        $product = wc_get_product($cart_item['product_id']); // Get the product object
        $terms = get_the_terms($product->get_id(), 'product_type'); // Get the terms of the product type
        if ($terms && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                if (in_array($term->slug, $disable_product_type)) { // Check if the product type matches 'booking'
                    unset($available_gateways['cod']); // Disable COD payment method
                    return $available_gateways; // Exit the loop and return the modified gateways
                }
            }
        }
    }
    return $available_gateways; // Return the available gateways unchanged if no match
}
/**
 * Get All orders IDs for a given product ID.
 *
 * @param  integer  $product_id (required)
 * @param  array    $order_status (optional) Default is 'wc-completed'
 *
 * @return array
 */
function get_orders_ids_by_product_id( $product_id ) {
    global $wpdb;
    $order_status = ['wc-completed', 'wc-processing', 'wc-on-hold', 'wc-cancelled', 'wc-pending', 'wc-failed', 'wc-payment-creation'];
    $results = $wpdb->get_col("SELECT order_items.order_id FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->prefix}wc_orders AS orders ON order_items.order_id = orders.ID
        WHERE orders.type = 'shop_order' AND orders.status IN ( '" . implode( "','", $order_status ) . "' ) AND order_items.order_item_type = 
'line_item'
        AND order_item_meta.meta_key = '_product_id' AND order_item_meta.meta_value = '".$product_id."' ORDER BY order_items.order_id DESC");
    return $results;
}
add_action( 'pre_get_posts', 'custom_product_visibility_for_school_pages' );
function custom_product_visibility_for_school_pages( $query ) {
    // Make sure we're not in the admin area and it's the main query
    if ( ! is_admin() && $query->is_main_query() ) {
        // Check if the URL contains exactly 'school-collaboration'
        if ( preg_match( '/^\/school-collaboration/', $_SERVER['REQUEST_URI'] ) ) {
            // Allow both published and private products to be displayed
            $query->set( 'post_status', array( 'publish', 'private' ) );
        }
    }
}
function tida_get_orders_by_product( $product_id,$limit,$offset,$start_date,$end_date,$order_status,$order_type ) {
    global $wpdb;
	$raw = "
	SELECT
	`items`.`order_id`,
	MAX(CASE WHEN `itemmeta`.`meta_key` = '_product_id' THEN `itemmeta`.`meta_value` END) AS `product_id`
	FROM
	`{$wpdb->prefix}woocommerce_order_items` AS `items`
	INNER JOIN
	`{$wpdb->prefix}woocommerce_order_itemmeta` AS `itemmeta`
	ON
	`items`.`order_item_id` = `itemmeta`.`order_item_id`";
	$raw .= " LEFT JOIN {$wpdb->prefix}wc_orders AS wc_orders ON items.order_id = wc_orders.id  "; 
	$raw .= "  LEFT JOIN {$wpdb->prefix}wc_orders_meta AS order_meta ON items.order_id = order_meta.order_id";       
	$raw .= "WHERE
	`items`.`order_item_type` IN('line_item')
	AND
	`itemmeta`.`meta_key` IN('_product_id')
	";
	$raw .= " AND ((order_meta.meta_value LIKE '%".$order_type."%'  AND order_meta.meta_key = 'tida_order_type' ) ";
	if($order_status){
		$raw .= " AND wc_orders.status IN ( '" . implode( "','", $order_status ) . "' ) ";
	}
	if($start_date && $end_date){
		//$raw .= " AND wc_orders.date_created_gmt >= '$start_date' AND wc_orders.date_created_gmt <= '$end_date' ";
		$raw .= " AND wc_orders.date_created_gmt BETWEEN  '$start_date' AND  '$end_date' ";
	}
	$raw .= " GROUP BY
	`items`.`order_item_id`
	HAVING
	`product_id` = %d";
     $sql = $wpdb->prepare( $raw, $product_id );
	$wpdb->get_results( $sql );  
	$orders['total_orders'] = $wpdb->num_rows;
	$raw .= " ORDER BY items.order_id DESC LIMIT $limit OFFSET $offset ";
    $sql = $wpdb->prepare( $raw, $product_id );
    $orders['orders'] =  array_map(function ( $data ) {
        return wc_get_order( $data->order_id );
    }, $wpdb->get_results( $sql ) ); 
	return $orders;
}

// ----------------------------------------------------------------------------------------------
// ----------------------------------------------------------------------------------------------
function add_custom_upload_mimes( $mimes_types ) {
    $mimes_types['webp'] = 'image/webp'; // webp files
    return $mimes_types;
}
add_filter( 'upload_mimes', 'add_custom_upload_mimes' );

function add_allow_upload_extension_exception( $types, $file, $filename, $mimes ) {
    // Do basic extension validation and MIME mapping
      $wp_filetype = wp_check_filetype( $filename, $mimes );
      $ext         = $wp_filetype['ext'];
      $type        = $wp_filetype['type'];
    if( in_array( $ext, array( 'webp' ) ) ) { // if follows webp files have
      $types['ext'] = $ext;
      $types['type'] = $type;
    }
    return $types;
}
add_filter( 'wp_check_filetype_and_ext', 'add_allow_upload_extension_exception', 99, 4 );
  


function displayable_image_webp( $result, $path ) {
    if ($result === false) {
        $displayable_image_types = array( IMAGETYPE_WEBP );
        $info = @getimagesize( $path );

        if (empty($info)) {
            $result = false;
        } elseif (!in_array($info[2], $displayable_image_types)) {
            $result = false;
        } else {
            $result = true;
        }
    }

    return $result;
}
add_filter( 'file_is_displayable_image', 'displayable_image_webp', 10, 2 );


/*
* New Products
*/
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );

add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 60 );


?>