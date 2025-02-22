<?php
/**
 * View Subscription
 *
 * Shows the details of a particular subscription on the account page
 *
 * @author  Prospress
 * @package WooCommerce_Subscription/Templates
 * @version 1.0.0 - Migrated from WooCommerce Subscriptions v2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_style('partner-my-account-css', get_stylesheet_directory_uri() . '/woocommerce/myaccount/partner-my-account-css.css');

wc_print_notices();


/**
 * Gets subscription details table template
 * @param WC_Subscription $subscription A subscription object
 * @since 1.0.0 - Migrated from WooCommerce Subscriptions v2.2.19
 */
do_action( 'woocommerce_subscription_details_table', $subscription );

/**
 * Gets subscription totals table template
 * @param WC_Subscription $subscription A subscription object
 * @since 1.0.0 - Migrated from WooCommerce Subscriptions v2.2.19
 */
do_action( 'woocommerce_subscription_totals_table', $subscription );

do_action( 'woocommerce_subscription_details_after_subscription_table', $subscription );

wc_get_template( 'order/order-details-customer.php', array( 'order' => $subscription ) );

if (current_user_can('partner')) {

?>

<script>
    let ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

    function completeOrder(order_id, nonce) {
	//console.log("OrderKey",order_id,"nonce",nonce);
        // AJAX request
	if (confirm('Are you sure you want to complete the payment for this order?')) {
	//order_id = order_id - 1;
        fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                //body: 'action=complete_order&nonce=' + nonce + '&order_id=' + order_id,
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
if (current_user_can('partner')) { 
    $parent_order_id = $subscription->get_parent_id() ? $subscription->get_parent_id() : $subscription->get_id();
    $parent_order = wc_get_order($parent_order_id);
    $order_status = $parent_order->get_status(); // Get the order status

    // Show the button only if the order status is pending
    if ($order_status !== 'completed') {
?>
    <form id="complete-order-form">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('complete_payment_nonce'); ?>">
        <input type="hidden" name="parent_order_id" value="<?php echo $parent_order_id; ?>"> <!-- Using parent order ID -->
        <button type="button" class="complete-payment-button" onclick="completeOrder('<?php echo $parent_order_id; ?>', '<?php echo wp_create_nonce('complete_payment_nonce'); ?>')">Confirm Payment</button>
    </form>
<?php 
    } 
}
?>

<?php

}

?>

<div class="clear"></div>