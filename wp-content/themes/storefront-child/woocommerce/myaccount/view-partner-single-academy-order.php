<?php
/**
 * Template Name: Partner Manager Order Details
 */

// Check if user is logged in and has the partner_manager role
if (is_user_logged_in() && current_user_can('partner_manager')) {

    // Get the order ID from the URL parameter or any other method you're using
    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

    // Get the order object
    $order = wc_get_order($order_id);

    if ($order) {
        ?>

        <h2><?php _e('Order Details', 'textdomain'); ?></h2>

        <p><strong><?php _e('Order Number:', 'textdomain'); ?></strong> <?php echo $order->get_order_number(); ?></p>
        <p><strong><?php _e('Order Date:', 'textdomain'); ?></strong> <?php echo wc_format_datetime($order->get_date_created()); ?></p>
        <p><strong><?php _e('Order Status:', 'textdomain'); ?></strong> <?php echo wc_get_order_status_name($order->get_status()); ?></p>

        <?php
        // Display the order items
        foreach ($order->get_items() as $item_id => $item) {
            echo '<p><strong>' . $item->get_name() . '</strong> - ' . $item->get_quantity() . ' x ' . wc_price($item->get_total()) . '</p>';
        }
        ?>

        <p><strong><?php _e('Order Total:', 'textdomain'); ?></strong> <?php echo wc_price($order->get_total()); ?></p>

        <?php
    } else {
        echo '<p>' . __('Invalid Order ID.', 'textdomain') . '</p>';
    }

} else {
    echo '<p>' . __('Access denied.', 'textdomain') . '</p>';
}
