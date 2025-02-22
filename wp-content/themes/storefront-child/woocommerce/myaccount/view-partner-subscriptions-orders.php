<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-partner-academy-orders.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Assuming $data contains the partner subscription IDs

// print_r($data);

if (!empty($data)) {
    echo "<div class='customer-bookings-container partner-subscription-container'>";
    echo "<table class='customer-bookings-table partner-subscription-table'>";
    echo "<thead class='customer-bookings-table_head partner-subscription-table_head'>";
    echo "<tr><th>Order Id</th><th>Name</th><th>Email</th><th>Status</th><th>Price</th><th>Action</th></tr>";
    echo "</thead>";
    
    echo "<tbody class='customer-bookings-table_body partner-subscription-table_body'>";
    foreach ($data as $order_id) {
        $order = wc_get_order($order_id); // Fetching order details
        
        // Check if order object is valid
        if ($order) {
            $items = $order->get_items();
            foreach ($items as $item) {
                $product_name = $item->get_name(); // Get product name
                $product_price = $item->get_total(); // Get product price
                $product_status = $order->get_status(); // Get order status
                
                // Get user details
                $user_id = $order->get_user_id();
                $user = get_userdata($user_id);
                
                // Check if user object is valid
                if ($user) {
                    $user_name = $user->data->display_name;
                    $user_email = $user->data->user_email;
                    
                    echo "<tr>";
                    // echo "<td>#$order_id</td>";
		    echo "<td><a class='customer-bookings-table_body_view_btn' href='../../view-subscription-order/$order_id'>#$order_id</a></td>";
                    echo "<td>$product_name</td>";
                    echo "<td>$user_email</td>"; // Display user email
                    echo "<td>$product_status</td>";
                    echo "<td>$product_price</td>";
                    echo "<td><a class='customer-bookings-table_body_view_btn' href='../../view-subscription-order/$order_id'>View</a></td>";
                    echo "</tr>";
                } else {
                    echo "<tr><td colspan='6'>User not found for order #$order_id</td></tr>";
                }
            }
        } else {
            echo "<tr><td colspan='6'>Order details not found for order #$order_id</td></tr>";
        }
    }
    echo "</tbody>";
    echo "</table>";
    
    // Add button to download orders as CSV
    echo "<form id='download-orders-form' method='post' action='" . admin_url('admin-post.php') . "'>";
    echo "<input type='hidden' name='action' value='download_orders_csv'>";
    echo "<input type='hidden' name='data' value='" . htmlspecialchars(json_encode($data)) . "'>";
    echo "<button type='submit' id='download-all-orders' class='button'>Download Orders as CSV</button>";
    echo "</form>";
    echo "</div>";
} else {
    echo "No orders found.";
}
?>

<script>
    
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('download-orders-form').addEventListener('submit', function (event) {
        // Prevent default form submission
        event.preventDefault();

        // Submit the form
        this.submit();
    });
});
</script>
