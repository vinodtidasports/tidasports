<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-partner-slots-orders.php.
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
    exit;
}
?>
<style>
.popup {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 99999;
}
.popup-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    /* min-width:500px;*/
    max-height: 90%;
    max-width: 90%;
    overflow: auto;
    min-width:300px;
}
.popup-content-inner {
    /* Add styles for the content inside the popup */
}
.close {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
    border:1px solid black;
    border-radius:50%;
    padding:4px 12px;
}
</style>
<div class='customer-bookings-container'>
<div class="table-responsive">
    <table class='customer-bookings-table'>
        <h3 class="partner_venue_order_name"><strong>Booking for Venue: </strong><a href="<?php echo get_permalink($product_id); ?>" target="_blank"><?php echo get_the_title($product_id); ?></a></h3>
        <thead class="customer-bookings-table_head">
            <tr>
                <th>Order ID</th>
                <th>Booking ID</th>
                <th>Order Status</th>
                <!--<th>Venue</th>-->
                <th>Name</th>
                <th>Phone Number</th>
                <th>Booking Date</th>
                <th>Amount Paid</th>
                <!--<th>Slot Date</th>-->
                <th>Action</th>
            </tr>
        </thead>
        <tbody class="customer-bookings-table_body">
        
        <?php
        foreach ($data as $key => $order_id) {
            $order = wc_get_order($order_id);
            $order_items = $order->get_items();
            $status = $order->get_status();
            $currency = $order->get_currency();
            $created_date = $order->get_date_created();
            $transaction_id = $order->get_transaction_id();
	    if (!empty($transaction_id)) {
		$transaction_type = 'online';
	    } else {
    		$transaction_type = 'offline';
		$transaction_id = 'cash';
	    }
            foreach ($order_items as $item_id => $item) {
                $product = $item->get_product();
                $type = $product->get_type();
                $productId = $product->get_id();
                // $partner_id = get_field('partner_manager', $productId);
                $user = get_user_by('id', $order->get_customer_id());
                $partner_name = $user->display_name;
                $person_name = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
                $billing_phone = $order->get_billing_phone();
                $billing_address = $order->get_billing_address_1() . ', ' . $order->get_billing_address_2() . ', ' . $order->get_billing_city() . ', ' . $order->get_billing_state() . ', ' . $order->get_billing_country() . ', ' . $order->get_billing_postcode();
                if ($type == 'booking') {
                    $item_url = $product->get_permalink();
                    $item_name = $product->get_name();
                    $item_booking_id = $booking_id = $item->get_meta('_booking_id');
                    $booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id($item_id);
                    if ($booking_ids) {
                        foreach ($booking_ids as $booking_id) {
				
                            $booking = new WC_Booking($booking_id);
				
                            $booking_date = date('Y-m-d', $booking->get_start());
			    $booking_start = date('H:i:s', $booking->get_start());
			    $booking_end = date('H:i:s', $booking->get_end());
			    $booked_at = date('Y-m-d H:i:s', $booking->get_data()['date_created']);
                            $email = $user->user_email;
                        /*    
			    if ($hour == 0 && $hour <= 9 && $minute <= 59 && $second <= 59) {
                                $slot_name = "Early Morning Slot";
                            } elseif ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59) {
                                $slot_name = "Morning Slot";
                            } elseif ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59) {
                                $slot_name = "Mid-Day Slot";
                            } elseif ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59) {
                                $slot_name = "Evening Slot";
                            } else {
                                $slot_name = "Night Slot";
                            }
			*/
                            $item_amount = $item->get_total(); // Fetch item amount
                            // Fetch additional details dynamically
                            $resource_id = get_post_meta($booking_id,'_booking_resource_id',true);
							$resource = '';
                            if($resource_id){
                            $resource = get_the_title($resource_id);
                            $item_name .= ' - '.$resource;
                            }

		  	    $order_id = $booking->get_order_id();

		            // Fetch transaction ID and details (You need to replace this with actual logic to get transaction details)
		            //$transaction_id = get_post_meta($order_id, '_transaction_id', true);
            		    //$transaction_type = get_post_meta($order_id, '_transaction_type', true);
			    $address = get_field('address', $product_id);
	  		    
                            $order_details = array(
                                'order_id' => $order_id,
				'booked_at'=>$booked_at,
                                'status' => $status,
                                'venue' => $item_name,
                                'name' => $person_name,
                                'phone_number' => $billing_phone,
                                'transaction_id' => $transaction_id,
                                'transaction_type' => $transaction_type, // You need to add logic for this
                                'booking_date' => $booking_date,
                                'amount_paid' => $currency . ' ' . $item_amount,
                                'email' => $email,
                                // 'academy' => '', // You need to add logic for this
                                'address' => $billing_address,
                                'package' => '', // You need to add logic for this
                                'slot_date' => $booking_date,
                                //'slot_name' => $slot_name,
                                //'slot_time' => $slot_time,
                                //'resource' => $resource,
				'address'=>$address,
				'end_time'=>$booking_end,
				'start_time'=>$booking_start,
                                'next_renewal' => '' // You need to add logic for this
                                // Add more fields as needed
				
                            );
                            echo '<tr>';
                            //echo '<td>' . $order_id. '</td>';
			    echo '<td><button class="customer-bookings-table_body_view_btn" onclick="showPopup(' . htmlspecialchars(json_encode($order_details), ENT_QUOTES, 'UTF-8') . ')">#' . $order_id . '</button></td>';
                            echo '<td>' . $booking_id. '</td>';
                            echo '<td>' . $status . '</td>';
                           // echo '<td><a href="'.$item_url.'" target="_blank">' . $item_name . '</a></td>';
                            echo '<td>' . $person_name . '</td>';
                            echo '<td>' . $billing_phone . '</td>';
                            echo '<td>' . $booking_date . '</td>';
                            echo '<td>' . $currency . ' ' . $item_amount . '</td>';
                            //echo '<td>' . $booking_date . '</td>';
                            echo '<td><button class="customer-bookings-table_body_view_btn" onclick="showPopup(' . htmlspecialchars(json_encode($order_details), ENT_QUOTES, 'UTF-8') . ')">View</button></td>';
                            echo '</tr>';
                        }
                    }
                }
            }
        }
        ?>
        </tbody>
    </table>
    <?php 
    // Add button to download orders as CSV
    echo "<form id='download-orders-form' method='post' action='" . admin_url('admin-post.php') . "'>";
    echo "<input type='hidden' name='action' value='download_orders_csv'>";
    echo "<input type='hidden' name='data' value='" . htmlspecialchars(json_encode($data)) . "'>";
    echo "<button type='submit' id='download-all-orders' class='button'>Download Orders as CSV</button>";
    echo "</form>";
    ?>
</div>
</div>
<div id="popup" class="popup">
    <div class="popup-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <div class="popup-content-inner" id="popup-content-inner">
            <!-- Dynamic content will be loaded here -->
        </div>
    </div>
</div>
<?php
?>
<script>
function showPopup(orderDetails) {
    var popupContent = document.getElementById("popup-content-inner");
    if (popupContent) {
        popupContent.innerHTML = `
            <p>Order ID: ${orderDetails.order_id}</p>
	    <p>Order Status: ${orderDetails.status}</p>

            <!-- <p>Booking ID: ${orderDetails.booking_id}</p> -->
            <!-- <p>Slot Name: ${orderDetails.slot_name}</p> -->
            <p>Booking At: ${orderDetails.booked_at}</p>
            <p>Venue Name: ${orderDetails.venue}</p>
            <p>Venue Name: ${orderDetails.address}</p>
            
            <!--<p>Partner ID: ${orderDetails.partner_id}</p>-->
            <p>Customer Name: ${orderDetails.name}</p>
            <p>Customer Email: ${orderDetails.email}</p>
            <p>Phone Number: ${orderDetails.phone_number}</p>
            <p>Transaction ID: ${orderDetails.transaction_id}</p> 
            <p>Transaction Type: ${orderDetails.transaction_type}</p> 
            <p>Slot Date: ${orderDetails.booking_date}</p>
            <p>Slot Amount: ${orderDetails.amount_paid}</p>
            <p>Slot Start Time: ${orderDetails.start_time}</p>
            <p>Slot End Time: ${orderDetails.end_time}</p>
	    <!-- <button class="complete-payment-button" data-order-id="${orderDetails.order_id}" data-nonce="<?php echo wp_create_nonce('complete_payment_nonce'); ?>">Confirm Payment</button> -->
            <!-- Add more data fields as needed -->
        `;
        
        // Show the popup
        var popup = document.getElementById("popup");
        if (popup) {
            popup.style.display = "block";
        }
	if (orderDetails.status != 'completed') {
            var completePaymentButton = document.createElement('button');
            completePaymentButton.classList.add('complete-payment-button');
            completePaymentButton.dataset.orderId = orderDetails.order_id;
            completePaymentButton.dataset.nonce = '<?php echo wp_create_nonce('complete_payment_nonce'); ?>';
            completePaymentButton.textContent = 'Confirm Payment';

            completePaymentButton.addEventListener('click', function() {
                var orderId = this.dataset.orderId;
                var nonce = this.dataset.nonce;

                if (confirm('Are you sure you want to complete the payment for this order?')) {
                    completePayment(orderId, nonce);
                }
            });

            popupContent.appendChild(completePaymentButton);
        }
    }
}
function closePopup() {
    // Hide the popup
    var popup = document.getElementById("popup");
    if (popup) {
        popup.style.display = "none";
    }
}
document.addEventListener("DOMContentLoaded", function() {
    // Your other JavaScript code here
});
</script>
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

<script>

    let ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    function completePayment(orderId, nonce) {

	console.log("completePayment","orderId",orderId,"nonce",nonce);
        // AJAX request
        fetch(ajaxurl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=complete_payment&nonce=' + nonce + '&order_id=' + orderId,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI accordingly, e.g., change order status to completed
                    alert(data.data);
		    location.reload();
                } else {
                    alert(data.data);
                }
            })
            .catch(error => {
                console.error('Error completing payment:', error);
                alert('An error occurred while completing the payment');
            });
    }
</script>