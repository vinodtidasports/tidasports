<?php

$user_id = get_current_user_id();
$user_avatar = get_user_meta($user_id, 'avatar', true);

// QUERIES ARGS

// venue queries
$products_venue_data = get_partner_product_by_partner($user_id, 'booking');
$products_venue = $products_venue_data['all_items'];
$max_num_pages = $products_venue_data['max_num_pages'];
$total_order = get_partner_order_by_item($user_id, '', 'booking');
$total_count = count($total_order);

//print_r($max_num_pages);

?>
<!--
<section class="partner-overview-section">
    <div class="partner-wrapper">
        <div class="partner-wrapper_header">
            <h1>No. of bookings</h1>
        </div>
        <div class="partner-wrapper_body">
            <div class="partner-wrapper_body_products_type type-iii">
                <div class="partner-wrapper_body_products_type_img">
                    <img src="https://tidasports.com/wp-content/uploads/2024/04/Group-12430.png" alt="bookings" />
                </div>
                <div class="partner-wrapper_body_products_type_numbers"><?php //echo $total_count; ?></div>
                <div class="partner-wrapper_body_products_type_name">Slots</div>
            </div>
        </div>
    </div>
</section>
-->
<?php if ($total_count > 0) { ?>
    <section class="partner-bookings-section">
        <div class="partner-bookings-wrapper">
            <div class="partner-bookings-wrapper_header">
                <h1>Slots / Venues</h1>
            </div>
            <div class="partner-bookings-wrapper_body">
                <?php
                $total_booking = 0;
                foreach ($products_venue as $item) {
                    $item_id = $item->ID;
                    $product = wc_get_product($item_id); // Change here
                    $academy_title = $item->post_title;
                    $academy_address = get_post_meta($item_id, 'address', true);
                    $academy_link = esc_url($item->guid);
                    if (has_post_thumbnail($item->ID)) {
                        $image_url = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID), 'single-post-thumbnail');
                        $image_url = $image_url[0];
                    } else {
                        $image_url = ''; // If no thumbnail found
                    }
                    $orders = get_partner_order_by_item($user_id, $item_id);
                    if (is_array($orders) && count($orders) > 0 && $product->get_type() == 'booking') {
                        $product_order_count = count($orders);
                        $total_booking += $product_order_count;
                    } else {
                        $product_order_count = 0;
                    }
                    $product_status = get_post_status($item_id);
                ?>
                    <div class="partner-bookings-wrapper_body_products">
                        <p class="partner-bookings-wrapper_body_products_tag" id="<?php echo 'product-tag-' . $item_id ?>"><?php echo $product_status ?></p>
                        <div class="partner-bookings-wrapper_body_products_img">
                            <a href="<?php echo $academy_link; ?>"><img src="<?php echo $image_url; ?>" alt="bookings" /></a>
                        </div>
                        <div class="partner-bookings-wrapper_body_products_content">
                            <div class="partner-bookings-wrapper_body_products_content_numbers"><?php echo $product_order_count; ?></div>
                            <div class="partner-bookings-wrapper_body_products_content_name"><a href="<?php echo $academy_link; ?>"><?php echo $academy_title; ?></a></div>
                            <div class="partner-bookings-wrapper_body_products_content_address"><img src="https://tidasports.com/wp-content/uploads/2024/03/01_align_center.png" alt="image" /><?php echo $academy_address; ?></div>
                            <div class="partner-bookings-wrapper_body_products_content_more">
                                <a href="<?php echo esc_url(home_url('/my-account/partner-slots-orders/' . $item_id)); ?>">View Orders</a>
                                <button class="toggle-product-status" data-product-id="<?php echo $item_id; ?>" data-current-status="<?php echo $product_status; ?>" data-nonce="<?php echo wp_create_nonce('toggle_product_status_nonce'); ?>"><?php echo $product_status === "draft" ? "Make Active" : "Make Inactive"; ?></button>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php if ($max_num_pages > 1) { ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php
                        $current_page = get_url_var('page');
                        if (!is_numeric($current_page)) {
                            $current_page = 1;
                        }
                        for ($page_in_loop = 1; $page_in_loop <= $max_num_pages; $page_in_loop++) {
                            if ($max_num_pages > 3) {
                                if (($page_in_loop >= $current_page - 5 && $page_in_loop <= $current_page) || ($page_in_loop <= $current_page + 5 && $page_in_loop >= $current_page)) { ?>
                                    <li class="page-item <?php echo $page_in_loop == $current_page ? 'active disabled' : ''; ?>">
                                        <a class="page-link" href="<?php echo site_url() . '/my-account/partner-slots/page/' . $page_in_loop; ?> "><?php echo $page_in_loop; ?></a>
                                    </li>
                                <?php }
                            } else { ?>
                                <li class="page-item <?php echo $page_in_loop == $current_page ? 'active disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo site_url() . '/my-account/partner-slots/page/' . $page_in_loop; ?> "><?php echo $page_in_loop; ?></a>
                                </li>
                        <?php }
                        } ?>
                    </ul>
                </nav>
            <?php } ?>
        </div>
    </section>
<?php } ?>

<script>
    let ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    document.addEventListener('DOMContentLoaded', function() {
        var toggleButtons = document.querySelectorAll('.toggle-product-status');
        toggleButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var productId = this.getAttribute('data-product-id');
                var currentStatus = this.getAttribute('data-current-status');
                if (confirm('Are you sure you want to change the status?')) {
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
                            if (data.success) {
                                button.innerText = currentStatus === "publish" ? "Make Active" : "Make Inactive";
                                button.setAttribute('data-current-status', currentStatus === "publish" ? 'draft' : 'publish');
                                let productsTag = document.getElementById(`product-tag-${productId}`);
                                productsTag.innerText = currentStatus === "publish" ? 'draft' : 'publish';
                                alert("Updated Successfully");
                            } else {
                                alert("Something went wrong");
                            }
                        })
                        .catch(error => {
                            alert("Something went wrong");
                        });
                }
            });
        });
    });
</script>
