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
    exit; // Exit if accessed directly.
}
// print_r($data);
// echo "<br/>";

$total_orders = $data['total_orders'];
$orders = $data['orders'];
?>

<?php
if (!empty($orders)) {
    echo "<div class='customer-bookings-container'>";
    echo "<table class='customer-bookings-table'>";
    echo "<thead class='customer-bookings-table_head'>";
    echo "<tr><th>Order</th><th>Date</th><th>Product</th><th>Status</th><th>Price</th><th>Action</th></tr>";
    echo "</thead>";
    
    echo "<tbody class='customer-bookings-table_body'>";
    foreach ($orders as $order_id) {
        $order = wc_get_order($order_id); // Fetching order details
        
        // Checking if the order exists
        if ($order) {
            // Loop through each item in the order
            foreach ($order->get_items() as $item_id => $item) {
                $product_name = $item->get_name();
                $product_name = strlen($product_name) > 40 ? substr($product_name, 0, 40) . '...' : $product_name;
                $quantity = $item->get_quantity();
                $price = $item->get_total();
                
                echo "<tr>";
                if ($item_id === key($order->get_items())) {
                    echo "<td rowspan='" . count($order->get_items()) . "'>#$order_id</td>";
                    echo "<td rowspan='" . count($order->get_items()) . "'>" . $order->get_date_created()->format('Y-m-d') . "</td>";
                }
                echo "<td>$product_name</td>";
                // echo "<td>$quantity</td>";
                echo "<td>".$order->get_status()."</td>";
                echo "<td>$price</td>";
                // echo "<td><a class='customer-bookings-table_body_view_btn' orderId='". $order_id . "'>View</a></td>";
                echo "<td><a class='customer-bookings-table_body_view_btn' href='".get_permalink( wc_get_page_id( 'myaccount' ) )."/view-order/" . $order_id . "'>View</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Order ID $order_id not found.</td></tr>";
        }
    }
    echo "</tbody>";
    echo "</table>";
    echo "</div>";
} else {
    echo "No orders found.";
}
