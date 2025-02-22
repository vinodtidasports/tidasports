<?php
/*
Template Name: Partner Subscription Details
*/
get_header();
// Check if the user is logged in
if (!is_user_logged_in()) {
    $redirect_url = add_query_arg('redirect_to', urlencode(get_permalink()), wc_get_page_permalink('myaccount'));
    wp_redirect($redirect_url);
    exit;
}

// Check if the logged-in user is a partner manager
$current_user = wp_get_current_user();
if (in_array('partner', $current_user->roles)) {
    // User is a partner manager
    // Proceed with displaying the partner manager dashboard
    $username = $current_user->user_login;
    $user_avatar = get_avatar_url($user_id);
    $current_user_id = $current_user->ID;

    // Display banner
    echo do_shortcode("[show_banner_of_page title='Hi, $username' description='How are you?' avatar='$user_avatar']");

    // Fetch and display subscription details
    // Add your subscription details fetching and display code here
} else {
    // User is not a partner manager
    echo '<p>Access denied. You must be a partner manager to view this page.</p>';
    get_footer();
    exit;
}
// Fetch subscription details
$type = 'subscription';
$subscription_orders = json_decode(getordersbypartner($type), true);

// Display subscription details if available
if ($subscription_orders) {
    echo '<div class="containered partner-wrapper_header">';
    echo '<h1>Subscription Details</h1>';
    echo '<table class="tables">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Order ID</th>';
    echo '<th>Name</th>';
    echo '<th>Phone</th>';
    echo '<th>Email</th>';
    echo '<th>Academy Name</th>';
    echo '<th>Booking Date</th>';
    echo '<th>Address</th>';
    echo '<th>Paid</th>';
    echo '<th>Status</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($subscription_orders as $order_id) {
        $order = wc_get_order($order_id);
        // Get username from order
        $username = $order->get_user()->user_login;
        $email = $order->get_billing_email();
        // Get phone number from order
        $phone_number = $order->get_billing_phone();
        // Get partner ID from user meta
        $partner_id = get_user_meta($order->get_user_id(), 'partner_id', true);
        // Get product name(s) from the order items
        $product_names = array();
        foreach ($order->get_items() as $item_id => $item) {
            $product_name = $item->get_name();
            $product_names[] = $product_name;
        }
        $product_name_str = implode(', ', $product_names);
        // Get academy name from the booking
        $booking_items = $order->get_items('booking');
        $academy_names = array();
        foreach ($booking_items as $item_id => $booking_item) {
            $product_id = $booking_item->get_product_id();
            $product = wc_get_product($product_id);
            if ($product && $product->is_type('booking')) {
                $booking_data = $product->get_data();
                $academy_name = $booking_data['name']; // Assuming the academy name is stored in the 'name' field
                $academy_names[] = $academy_name;
            }
        }
        $academy_name_str = implode(', ', $academy_names);
        // Get transaction type
        $transaction_type = $order->get_payment_method();
        // Get booking date
        $booking_date = $order->get_date_created()->date('Y-m-d H:i:s');
        // Get address
        $address = $order->get_formatted_billing_address();
        // Get package name
        // Assuming the package name is stored in the order meta
        $package_name = $order->get_meta('package_name', true);
        // Get amount paid
        $amount_paid = $order->get_total();
        // Get order status
        $order_status = $order->get_status();
        // Output table row
        echo '<tr>';
        echo '<td>' . $order_id . '</td>';
        echo '<td>' . $username . '</td>';
        echo '<td>' . $phone_number . '</td>';
        echo '<td>' . $email . '</td>';
        echo '<td>' . $product_name_str . '</td>';
        echo '<td>' . $booking_date . '</td>';
        echo '<td>' . $address . '</td>';
        echo '<td>' . $amount_paid . '</td>';
        echo '<td>' . $order_status . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo '<p>No subscriptions found.</p>';
}
// Continue with the rest of your code here

get_footer();
?>
