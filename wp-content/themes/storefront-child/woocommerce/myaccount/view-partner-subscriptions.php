<?php 
$user_id = get_current_user_id();
$user_avatar = get_user_meta($user_id, 'avatar', true);
//  subscription products
$products_subscription_data = get_partner_product_by_partner($user_id,'variable-subscription');
$products_subscription = $products_subscription_data['all_items'];
$max_num_pages = $products_subscription_data['max_num_pages'];
/*$type = 'subscription';
$total_order = get_partner_order_by_item($user_id,'',$type);
$total_count = count($total_order);
$subscription_orders = json_decode(getordersbypartner($type), true);
*/

$total_order = get_partner_order_by_item($user_id,'','subscription');
$total_count = count($total_order);
if (count($products_subscription) > 0) { 
    ?>
    <section class="partner-bookings-section">
        <div class="partner-bookings-wrapper">
            <div class="partner-bookings-wrapper_header">
                <h1>Suscriptions</h1>
            </div>
            <div class="partner-bookings-wrapper_body">
                <?php
                foreach ($products_subscription as $item) {
                    $item_id = $item->ID;
                    $academy_title = $item->post_title;
                    $academy_address = get_post_meta($item_id, 'address', true);
                    $academy_link = $item->guid;
                    if (has_post_thumbnail($item->ID)) {
                        $image_url = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID), 'single-post-thumbnail');
                    }
                    $orders = get_partner_order_by_item($user_id, $item_id, 'subscription');
                    $product_order_count = count($orders);
                    $product_status = get_post_status($item_id);
		    $product_link = esc_url($item->guid);
                    ?>
                    <div class="partner-bookings-wrapper_body_products">
                        <p class="partner-bookings-wrapper_body_products_tag" id="<?php echo 'product-tag-'.$item_id ?>"><?php echo $product_status ?></p>
                        <div class="partner-bookings-wrapper_body_products_img">
                            <a href="<?php echo esc_url(home_url('/my-account/partner-subscriptions-orders/' . $item_id)); ?>"><img src="<?php echo $image_url[0]; ?>" alt="bookings" /></a>
                        </div>
                        <div class="partner-bookings-wrapper_body_products_content">
                            <div class="partner-bookings-wrapper_body_products_content_numbers"><?php echo $product_order_count; ?></div>
                            <div class="partner-bookings-wrapper_body_products_content_name"><a href="<?php echo $product_link; ?>"><?php echo $academy_title; ?></a></div>
                            <div class="partner-bookings-wrapper_body_products_content_address"><img src="https://tidasports.com/wp-content/uploads/2024/03/01_align_center.png" alt="image" /><?php echo $academy_address; ?></div>
                            
                            <div class="partner-bookings-wrapper_body_products_content_more">
                                <a href="<?php echo esc_url(home_url('/my-account/partner-subscriptions-orders/' . $item_id)); ?>">View Orders</a>
                                <button class="toggle-product-status" data-product-id="<?php echo $item_id; ?>" data-current-status="<?php echo $product_status; ?>" data-nonce="<?php echo wp_create_nonce('toggle_product_status_nonce'); ?>"><?php echo $product_status === "draft" ? "Make Active" : "Make Inactive"; ?></button>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                wp_reset_postdata();
                ?>
            </div>
            <?php if ($max_num_pages > 1) { ?>
<?php $current_page = get_url_var('page');
    if(!is_numeric($current_page)){
        $current_page = 1;
    } ?>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php
                    /* When we're not on the first page, we'll have a paginator back to the beginning
                    if ($current_page > 1) { ?>
                        <li class="page-item"><a class="page-link" href="<?php echo '/page=1' ; 
?>">First</a></li>
                        <?php
                    }*/
                    // Loop through page numbers
                    for ($page_in_loop = 1; $page_in_loop <= $max_num_pages; $page_in_loop++) {
                        
                        // if the page in the loop is more between 
                        if ($max_num_pages > 3) {
                            if (($page_in_loop >= $current_page - 5 && $page_in_loop <= $current_page)  || ($page_in_loop <= $current_page 
+ 5 && $page_in_loop >= $current_page)) {  ?>
                                <li class="page-item <?php echo $page_in_loop == $current_page ? 'active disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo site_url() . '/my-account/partner-subscriptions/page/' . $page_in_loop ; ?> "><?php 
echo $page_in_loop; ?></a>
                                </li>
                            <?php }
                        }
                        // if the total pages doesn't look ugly, we can display all of them
                        else { ?>
                            <li class="page-item <?php echo $page_in_loop == $current_page ? 'active disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo site_url() . '/my-account/partner-subscriptions/page/' . $page_in_loop ; ?> "><?php echo 
$page_in_loop; ?></a>
                            </li>
                        <?php } // End if   
                        ?>
                    <?php } // end for loop
                    /* and the last page
                    if ($current_page < $max_num_pages) { ?>
                        <li class="page-item"><a class="page-link" href="<?php echo '/page=' . $max_num_pages ; 
?>">Last</a></li>
                    <?php }*/ ?>
                </ul>
            </nav>
        <?php }  ?>
        </div>
    </section>
<?php
}
?>

<script>
    let ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    document.addEventListener('DOMContentLoaded', function() {
        var toggleButtons = document.querySelectorAll('.toggle-product-status');
        toggleButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var productId = this.getAttribute('data-product-id');
                var currentStatus = this.getAttribute('data-current-status');
                // console.log("currentStatus",currentStatus);
                // Confirm with the user before toggling the status
                if (confirm('Are you sure you want to change the status?')) {
                    // Send AJAX request
                    var formData = new FormData();
                    formData.append('action', 'toggle_product_status');
                    formData.append('product_id', productId);
                    formData.append('nonce', this.getAttribute('data-nonce'));
                    this.innerText = "Updating"
                    fetch(ajaxurl, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // console.log("Data",data);
                        if (data.success) {
                            // Update the button text and data attributes
                            button.innerText = currentStatus === "publish" ? "Make Active" : "Make Inactive";
                            button.setAttribute('data-current-status', currentStatus === "publish" ? 'draft':'publish');
                            let productsTag = document.getElementById(`product-tag-${productId}`);
                            productsTag.innerText = currentStatus === "publish" ? 'draft':'publish';
                            alert("Updated Successfully");
                            // Update the product status in the UI
                            // (Replace this with actual UI update logic)
                        } else {
                            // console.error('Failed to toggle product status:', data.message);
                            alert("Something went wrong");
                            
                        }
                    })
                    .catch(error => {
                        alert("Something went wrong");
                        // console.error('Error toggling product status:', error);
                    });
                }
            });
        });
    });
</script>
