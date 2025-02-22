<?php
add_filter( 'woocommerce_admin_reports', 'woocommerce_admin_reports' );
function woocommerce_admin_reports( $reports ) {

    $reports['customers']['reports']['customer_list']['callback'] = 'customer_list_get_report';

    return $reports;
}
function customer_list_get_report( $name ) {

    $class = 'My_WC_Report_Customer_List';

    do_action('class_wc_report_customer_list');

    if ( ! class_exists( $class ) )
        return;

    $report = new $class();
    $report->output_report();
}
add_action( 'class_wc_report_customer_list', 'class_wc_report_customer_list' );
function class_wc_report_customer_list() {

    if ( ! class_exists( 'WC_Report_Customer_List' ) ) {
        include_once( WC_ABSPATH . 'includes/admin/reports/class-wc-report-customer-list.php' );
    }
    class My_WC_Report_Customer_List extends WC_Report_Customer_List {

        /**
         * Get column value.
         *
         * @param WP_User $user
         * @param string $column_name
         * @return string
         */
        public function column_default( $user, $column_name ) {
            global $wpdb;

            switch ( $column_name ) {

                case 'phone' :
                    return get_user_meta( $user->ID, 'billing_phone', true );
            }
            return parent::column_default( $user, $column_name );
        }

        /**
         * Get columns.
         *
         * @return array
         */
        public function get_columns() {

            /* default columns.
            $columns = array(
                'customer_name'   => __( 'Name (Last, First)', 'woocommerce' ),
                'username'        => __( 'Username', 'woocommerce' ),
                'email'           => __( 'Email', 'woocommerce' ),
                'location'        => __( 'Location', 'woocommerce' ),
                'orders'          => __( 'Orders', 'woocommerce' ),
                'spent'           => __( 'Money spent', 'woocommerce' ),
                'last_order'      => __( 'Last order', 'woocommerce' ),
                'user_actions'    => __( 'Actions', 'woocommerce' ),
            ); */

            // sample adding City next to Location.
            $columns = array(
                'customer_name'   => __( 'Name (Last, First)', 'woocommerce' ),
                'username'        => __( 'Username', 'woocommerce' ),
                'email'           => __( 'Email', 'woocommerce' ),
                'location'        => __( 'Location', 'woocommerce' ),
                'phone'            => __( 'Phone', 'woocommerce' ),
            );
            return array_merge( $columns, parent::get_columns() );
        }
    }
}


/* add_action('save_post', 'tida_partner_post_save'); */
function tida_partner_post_save($post_id) {
    // Check if this is an autosave; if so, exit
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if this is the right post type
    if (get_post_type($post_id) !== 'product') {
        return;
    }
	$orders = get_orders_ids_by_product_id( $post_id ); 
	$partner = get_field('partner_manager',$post_id);
	if(!empty($orders)){
		foreach($orders as $order_id){
			$order = wc_get_order( $order_id );
			$order->update_meta_data( 'partner_id' , $partner ) ;
			$order->save();
			$order_items = $order->get_items();
			foreach ( $order_items as $item_id => $item ) { 
				$product = wc_get_product($item['product_id']);
				if( $product->get_type() === 'booking' ) {
					$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
					foreach( $booking_ids as $booking_id ) { 
						$booking = new WC_Booking($booking_id);
						$booking->update_meta_data( 'partner_id' , $partner ) ;
						$booking->save();
					}
				}elseif($product->get_type() == 'subscription_variation' || $product->get_type() == 'variable-subscription'){
						$subscriptions_ids = wcs_get_subscriptions_for_order( $order_id, array( 'order_type' => 'any' ) );
						if(!empty($subscriptions_ids)){
							foreach( $subscriptions_ids as $subscription_id => $subscription ){
								update_post_meta( $subscription_id,'partner_id' , $partner ) ;
						   }
						}
						
				}
			}
		}
	}
    // Add more custom fields as needed
}