<?php

//get_header();
$user_id = get_current_user_id();
$user_avatar = get_user_meta($user_id, 'avatar', true);
// echo do_shortcode("[show_banner_of_page title='Hi, $username' description='How are you?' avatar='$user_avatar']");
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// QUERIES ARGS
// academy queries
$products_academy_data = get_partner_product_by_partner($user_id, 'variable');
$products_academy = $products_academy_data['all_items'];
// print_r($products_academy);
$max_num_pages = $products_academy_data['max_num_pages'];
//print_r($max_num_pages);
// $type = 'variable';
$type = 'variation';
$total_order = get_partner_order_by_item($user_id, '', $type);
// print_r($total_order);
$total_count = count($total_order);
/*$bookings_orders = json_decode(getordersbypartner('booking'), true);
$total_bookings = count($bookings_orders);
$bookings_orders = json_decode(getordersbypartner('booking'), true);*/
// print_r($max_num_pages);

$total_booking = 0; // Initialize total bookings counter

?>
<!--
<section class="partner-overview-section">
    <div class="partner-wrapper">
        <div class="partner-wrapper_header">
            <h1>No. of bookings</h1>
        </div>
        <div class="partner-wrapper_body">
            <div class="partner-wrapper_body_products_type type-i">
                <div class="partner-wrapper_body_products_type_img">
                    <img src="https://tidasports.com/wp-content/uploads/2024/04/Group-12459.png" alt="bookings" />
                </div>
                <div class="partner-wrapper_body_products_type_numbers"><?php //echo $total_count; ?></div>
                <div class="partner-wrapper_body_products_type_name">Bookings</div>
            </div>
        </div>
    </div>
</section>
-->
<?php if (!empty($products_academy)) { ?>
    <section class="partner-bookings-section">
        <div class="partner-bookings-wrapper">
            <div class="partner-bookings-wrapper_header">
                <h1>Bookings</h1>
            </div>
            <div class="partner-bookings-wrapper_body">
                <?php
                foreach ($products_academy as $item) { //echo print_r($item); die;
                    $item_id = $item->ID;
                    $product = wc_get_product($item_id); // Corrected function name
                    $academy_title = $item->post_title;
                    $academy_address = get_post_meta($item_id, 'address', true);
                    $academy_link = get_permalink($item_id); // Corrected function name
                    if (has_post_thumbnail($item->ID)):
                        $image_url = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID), 'single-post-thumbnail');
                    endif;
                    $orders = get_partner_order_by_item($user_id, $item_id, 'variation');
                    if (!empty($orders)) {
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
                            <a href="<?php echo $academy_link; ?>"><img src="<?php echo $image_url[0]; ?>" alt="bookings" /></a>
                        </div>
                        <div class="partner-bookings-wrapper_body_products_content">
                            <div class="partner-bookings-wrapper_body_products_content_numbers"><?php echo $product_order_count; ?></div>
                            <div class="partner-bookings-wrapper_body_products_content_name"><a href="<?php echo $academy_link; ?>"><?php echo $academy_title; ?></a></div>
                            <div class="partner-bookings-wrapper_body_products_content_address"><img src="https://tidasports.com/wp-content/uploads/2024/03/01_align_center.png" alt="image" /><?php echo $academy_address; ?></div>
                            <div class="partner-bookings-wrapper_body_products_content_more">
                                <a href="<?php echo esc_url(home_url('/my-account/partner-bookings-orders/' . $item->ID)); ?>">View Orders</a>
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
                <?php
                $current_page = get_query_var('paged') ? get_query_var('paged') : 1; // Corrected function name
                ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php
                        // Loop through page numbers
                        for ($page_in_loop = 1; $page_in_loop <= $max_num_pages; $page_in_loop++) {

                            // if the page in the loop is more between 
                            if ($max_num_pages > 3) {
                                if (
                                    ($page_in_loop >= $current_page - 5 && $page_in_loop <= $current_page) || ($page_in_loop <= $current_page
                                        + 5 && $page_in_loop >= $current_page)
                                ) { ?>
                                    <li class="page-item <?php echo $page_in_loop == $current_page ? 'active disabled' : ''; ?>">
                                        <a class="page-link" href="<?php echo get_pagenum_link($page_in_loop); ?>"><?php
                                                                                                                        echo $page_in_loop; ?></a>
                                    </li>
                                <?php }
                            }
                            // if the total pages doesn't look ugly, we can display all of them
                            else { ?>
                                <li class="page-item <?php echo $page_in_loop == $current_page ? 'active disabled' : ''; ?>">
                                    <a class="page-link" href="<?php echo get_pagenum_link($page_in_loop); ?>"><?php echo
                                                                                                                $page_in_loop; ?></a>
                                </li>
                        <?php }
                        } // end for loop
                        ?>
                    </ul>
                </nav>
            <?php } ?>
        </div>
    </section>
<?php
}
?>

<section class="partner-total-bookings-section">
    <div class="partner-total-bookings-section-wrapper">
        <div class="partner-total-bookings-section-wrapper-container">
            <h1>Total Bookings</h1><h1><?php echo $total_booking; ?></h1>
        </div>
    </div>
</section>

<section class="woocommerce_single_product_game_intro_section">
    <div class="woocommerce_single_product_game_intro_section_wrapper">
        <div class="woocommerce_single_product_game_intro_section_wrapper__text_wrapper">
            <h1 class="woocommerce_single_product_game_intro_section_wrapper__text_wrapper__title">Introduce your child to a new world</h1>
            <p class="woocommerce_single_product_game_intro_section_wrapper__text_wrapper__desc">Celebrate the milestones, and nurture the passion</p>
        </div>
        <div class="woocommerce_single_product_game_intro_section_wrapper__video_wrapper">
            <iframe width="100%" height="100%" style="min-height:480px; border-radius:10px;" src="https://www.youtube.com/embed/X5AW2TqgIMg" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</section>

<script>
    let ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    document.addEventListener('DOMContentLoaded', function() {
        var toggleButtons = document.querySelectorAll('.toggle-product-status');
        toggleButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                var productId = this.getAttribute('data-product-id');
                var currentStatus = this.getAttribute('data-current-status');
                // Confirm with the user before toggling the status
                if (confirm('Are you sure you want to change the status?')) {
                    // Send AJAX request
                    var formData = new FormData();
                    formData.append('action', 'toggle_product_status');
                    formData.append('product_id', productId);
                    formData.append('nonce', this.getAttribute('data-nonce'));
                    this.innerText = "Updating";
                    fetch(ajaxurl, {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Update the button text and data attributes
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
