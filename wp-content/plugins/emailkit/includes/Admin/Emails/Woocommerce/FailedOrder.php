<?php 

namespace EmailKit\Admin\Emails\Woocommerce;

use WP_Query;
use EmailKit\Admin\Emails\EmailLists;
use EmailKit\Admin\Emails\Helpers\Utils;

defined("ABSPATH") || exit;

class FailedOrder
{

	private $db_query_class = null;

	public function __construct()
	{

		$args = array(
			'post_type'  => 'emailkit',
			'meta_query' => array(
				array(
					'key'   => 'emailkit_template_type',
					'value' => EmailLists::FAILED_ORDER,
				),
				array(
					'key'   => 'emailkit_template_status',
					'value' => 'Active',
				),
			),
		);



		$this->db_query_class = new WP_Query($args);

		if (isset($this->db_query_class->posts[0])) {
			add_action('woocommerce_email', [$this, 'remove_woocommerce_emails']);
		}

		add_filter('woocommerce_order_status_pending_to_failed_notification', [$this, 'failedOrder'], 10, 2);
		add_filter('woocommerce_order_status_on-hold_to_failed_notification', [$this, 'failedOrder'], 10, 2);
	}

	public function remove_woocommerce_emails($email_class)
	{

		remove_action('woocommerce_order_status_pending_to_failed_notification', array($email_class->emails['WC_Email_Failed_Order'], 'trigger'));
		remove_action('woocommerce_order_status_on-hold_to_failed_notification', array($email_class->emails['WC_Email_Failed_Order'], 'trigger'));
	}

	public function failedOrder($order_id, $order)
	{


		$query = $this->db_query_class;
		$email = get_option('admin_email');
		if (isset($query->posts[0])) {
			$html  = get_post_meta($query->posts[0]->ID, 'emailkit_template_content_html', true);

			$replacements = [];
			foreach ($order->get_items() as $item_id => $item) {
			  $product = $item->get_product();
			  $id = $item['product_id'];
			  $product_name = $item['name'];
			  $item_qty = $item['quantity'];
			  $item_total = $item['total'];
			  $product_price = $product->get_price() * $item_qty;
	  
	  
			  // Format the product price with the currency symbol
			  $formatted_product_price = wc_price($product_price);
	  
			  // Format the item total with the currency symbol
			  $formatted_item_total = wc_price($item_total);
	  
			
			  $replacements[] = [$product_name, $item_qty, $formatted_item_total, $formatted_product_price];
	  
			}

			$html = \EmailKit\Admin\Emails\Helpers\Utils::order_items_replace($html, $replacements);

			$order = wc_get_order($order_id);

			// Order details array for email
			$details = $details = Utils::woocommerce_order_email_contents($order);

			$message  = str_replace(array_keys($details), array_values($details), apply_filters('emailkit_shortcode_filter', $html));
			$currency_symbol_position = get_option('woocommerce_currency_pos');

			if($currency_symbol_position == 'left') {

				$message = Utils::adjust_price_structure($message);
				
			} else if($currency_symbol_position == 'left_space') {
		
				$message = Utils::adjust_left_space_price_structure($message);
				
			}
      		$email_settings = get_option('woocommerce_failed_order_settings', []);
      		$to = !empty($email_settings['recipient']) ? explode(',', $email_settings['recipient']) : [get_option('admin_email')];
      		$to = array_map('trim', $to);

			$pre_header_template = get_post_meta($query->posts[0]->ID, 'emailkit_email_preheader', true);
			$pre_header = str_replace(array_keys(Utils::transform_details_keys($details)), array_values(Utils::transform_details_keys($details)), $pre_header_template);
			$pre_header = !empty($pre_header) ? $pre_header : esc_html__("order has been failed. ", "emailkit");
			$subject_template = get_post_meta($query->posts[0]->ID, 'emailkit_email_subject', true);
     		$subject = str_replace(array_keys(Utils::transform_details_keys($details)), array_values(Utils::transform_details_keys($details)), $subject_template);
			$subject =  !empty($subject) ? $subject . ' - ' . $pre_header : esc_attr($order->get_billing_first_name() . " " . $order->get_billing_last_name()) . ", " .esc_html__("your order has been failed. order# ", "emailkit")  . "" . esc_attr($order_id) . ' ' . esc_html__("on", "emailkit") .' '. get_bloginfo('name') . ' - ' . $pre_header;


			$headers = [
				'From: ' . $email . "\r\n",
				'Reply-To: ' . $email . "\r\n",
				'Content-Type: text/html; charset=UTF-8',
			];

			wp_mail($to, $subject, $message, $headers);
		}
	}
}