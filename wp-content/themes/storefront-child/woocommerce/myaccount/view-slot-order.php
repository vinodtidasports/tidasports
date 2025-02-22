<?php
/**
 * View Slot
 *
 * Shows the details of a particular Slot on the account page
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

wc_print_notices();

$order_id = $args;

// Check if order object exists
if($order_id) {
    $order = wc_get_order($order_id);
    $order_number = $order->get_order_number();

    // Get order date
    $order_date = $order->get_date_created()->format('F j, Y');

    // Get order status
    $order_status = $order->get_status();

    // Get billing address
    $billing_address = $order->get_formatted_billing_address();

    // Display customized message
    echo "Order #$order_number was placed on $order_date and is currently $order_status.<br />";
    // Load the order details template
    wc_get_template(
        'order/order-details.php',
        array(
            'order_id' => $order_id,
        )
    );
    wc_get_template('order/order-details-customer.php', array('order' => $order));
    wc_get_template('order/order-details-items.php', array('order' => $order));
} else {
    echo 'Order not found.';
}
?>

<div class="clear"></div>
