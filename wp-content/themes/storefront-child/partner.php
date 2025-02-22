<?php
/*
Template Name: Partner Manager Dashboard
*/
if (!is_user_logged_in()) {
    $redirect_url = add_query_arg('redirect_to', urlencode(get_permalink()), wc_get_page_permalink('myaccount'));
    wp_redirect($redirect_url);
    exit;
}

$current_user = wp_get_current_user();

if (!in_array('partner', $current_user->roles)) {
    wp_redirect(wc_get_page_permalink('myaccount'));
    exit;
}

$username = $current_user->user_login;
$user_avatar = get_avatar_url($current_user->ID);
$current_user_id = $current_user->ID;

echo do_shortcode("[show_banner_of_page title='Hi, $username' description='How are you?' avatar='$user_avatar']");

// Fetch booking orders
$booking_orders = json_decode(getordersbypartner('booking'), true);

// Display booking details
if ($booking_orders) {
    echo '<h2>Booking Details</h2>';
    echo '<table>';
    echo '<tr><th>Order ID</th><th>User Name</th><th>Product Name</th><th>Status</th><th>Booking Date</th><th>Booking Time</th></tr>';
    foreach ($booking_orders as $order_id) {
        $order = wc_get_order($order_id);
        $user_id = $order->get_user_id();
        $user = get_userdata($user_id);
        $user_name = $user ? $user->user_login : 'Unknown User';
        $product_name = ''; // Initialize product name variable
        $booking_date = ''; // Initialize booking date variable
        $booking_time = ''; // Initialize booking time variable
        foreach ($order->get_items() as $item_id => $item) {
            $product_name = $item->get_name(); // Get the name of the product
            $booking_meta = wc_get_order_item_meta($item_id, 'Booking Date', true);
            if (!empty($booking_meta['date']) && !empty($booking_meta['time'])) {
                $booking_date = $booking_meta['date'];
                $booking_time = $booking_meta['time'];
            }
            break; // We only need the details of the first booking in the order
        }
        $status = $order->get_status();
        echo "<tr><td>{$order_id}</td><td>{$user_name}</td><td>{$product_name}</td><td>{$status}</td><td>{$booking_date}</td><td>{$booking_time}</td></tr>";
    }
    echo '</table>';
} else {
    echo '<p>No booking orders found.</p>';
}

?>
