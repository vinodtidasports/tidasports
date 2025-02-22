<?php
/**
 * View Booking
 *
 * Shows the details of a particular booking on the account page
 *
 * @author  Prospress
 * @package WooCommerce_Bookings/Templates
 * @version 1.0.0 - Customized for Booking
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

wc_print_notices();

$order_id = $args;

wp_enqueue_style('partner-my-account-css', get_stylesheet_directory_uri() . '/woocommerce/myaccount/partner-my-account-css.css');

// Check if order object exists
if($order_id) {
    $order = wc_get_order($order_id);
	//echo "<pre>";
	//print_r($order);
	//echo "</pre>";
    $order_number = $order->get_order_number();

    // Get order date
    $order_date = $order->get_date_created()->format('F j, Y');

    // Get order status
    $order_status = $order->get_status();

    // Get billing address
    $billing_address = $order->get_formatted_billing_address();

    $transaction_id = $order->get_transaction_id();

    if (!empty($transaction_id)) {
        // If the transaction ID exists, it's an online transaction
        $transaction_type = 'online';
    } else {
      // If the transaction ID doesn't exist, it's an offline transaction
      $transaction_type = 'offline';
    
      // Since it's an offline transaction, set transaction ID to "cash"
      $transaction_id = 'cash';
    }
    $person_name = $order->get_meta('_wc_order_for_name') ?: ($order->get_meta('person_name') ?: "No Data");

    // Check if person name exists, if not, set it to 'No Data'
    $person_name = $person_name ? $person_name : 'No Data';

    // Display customized message
    // echo "Order #$order_number was placed on $order_date and is currently $order_status.<br />";
    echo "<div class='booking_order_details_table_wrapper'><table><tbody>";
    echo "<tr><td>Booking ID</td><td>" . esc_html($order_number) . "</td></tr>";
    echo "<tr><td>Status</td><td>" . esc_html($order_status) . "</td></tr>";
    echo "<tr><td>Booking Date</td><td>" . esc_html($order_date) . "</td></tr>";
    echo "<tr><td>Payment Method</td><td>" . esc_html($transaction_type) . "</td></tr>";
    echo "<tr><td>Transaction ID</td><td>" . esc_html($transaction_id) . "</td></tr>";
    echo "<tr><td>Person Name</td><td>" . esc_html($person_name) . "</td></tr>";
    echo "</tbody></table></div>";

    // Load the order details template
    wc_get_template(
        'order/order-details.php',
        array(
            'order_id' => $order_id,
	    'show_downloads'=> false,
        )
    );
    wc_get_template('order/order-details-customer.php', array('order' => $order));
    wc_get_template('order/order-details-items.php', array('order' => $order));
if ($order_status !== 'completed') {
?>
    <form id="complete-order-form">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('complete_payment_nonce'); ?>">
        <input type="hidden" name="parent_order_id" value="<?php echo $order_id; ?>"> <!-- Using parent order ID -->
        <button type="button" class="complete-payment-button" onclick="completeOrder('<?php echo $order_id; ?>', '<?php echo wp_create_nonce('complete_payment_nonce'); ?>')">Confirm Payment</button>
    </form>

    <script>
        let ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

        function completeOrder(order_id, nonce) {
		console.log("order_id",order_id);
            // AJAX request
            if (confirm('Are you sure you want to complete the payment for this order?')) {
                fetch(ajaxurl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'action=complete_payment&nonce=' + nonce + '&order_id=' + order_id,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI accordingly, e.g., display success message
                            alert(data.data);
                            location.reload(); // Reload the page after successful completion
                        } else {
                            // Handle error
                            alert(data.data);
                        }
                    })
                    .catch(error => {
                        console.error('Error completing order:', error);
                        alert('An error occurred while completing the order');
                    });
            }
        }
    </script>
<?php
    }
} else {
    echo 'Order not found.';
}
?>

<div class="clear"></div>
