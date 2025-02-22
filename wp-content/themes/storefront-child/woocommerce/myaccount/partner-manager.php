<?php

//get_header();
$user_id = get_current_user_id();
$user_avatar = get_user_meta($user_id, 'avatar', true);
// echo do_shortcode("[show_banner_of_page title='Hi, $username' description='How are you?' avatar='$user_avatar']");
//------------------------------------------------------------------------------
//------------------------------------------------------------------------------
// QUERIES ARGS
/*$subscription_orders = json_decode(getordersbypartner('subscription'), true);
$slots_orders = json_decode(getordersbypartner('package'), true);
$bookings_orders = json_decode(getordersbypartner('booking'), true);*/
$total_booking = 0;
if(isset($_GET['from'])){
        $start_date = $_GET['from'];
        $end_date = $_GET['to'];
        $date = array('start_date'=>$start_date,'end_date'=>$end_date);
}else{
    $start_date = date('Y-m-d');
    $end_date = date('Y-m-d H:i:s');
    $date = array('start_date'=>date('Y-m-d'),'end_date'=>date('Y-m-d H:i:s'));
}
$query_string = '?from='.$start_date.'&to='.$end_date;
$bookings = get_partnert_booking_by_date($user_id,$date,'variation');
$slots = get_partnert_booking_by_date($user_id,$date,'booking');
$subscriptions = get_partnert_booking_by_date($user_id,$date,'subscription');
?>

<section class="partner-overview-section">
    <div class="partner-wrapper">
        <div class="partner-wrapper_header">
            <h1>No. of bookings</h1>
            <div class="partner-wrapper_header_filters">
                <div><label>From</label><input type="date" id="from_date" /></div>
                <div><label>End</label><input type="date" id="to_date" /></div>
            </div>
        </div>
        <div class="partner-wrapper_body">
            <div class="partner-wrapper_body_products_type type-i">
                <div class="partner-wrapper_body_products_type_img">
                    <img src="https://tidasports.com/wp-content/uploads/2024/04/Group-12459.png" alt="bookings" />
                </div>
                <div class="partner-wrapper_body_products_type_numbers"><?php echo count($bookings); ?></div>
                <div class="partner-wrapper_body_products_type_name">Bookings</div>
            </div>
            <div class="partner-wrapper_body_products_type type-ii">
                <div class="partner-wrapper_body_products_type_img">
                    <img src="https://tidasports.com/wp-content/uploads/2024/04/Group-12460.png" alt="bookings" />
                </div>
                <div class="partner-wrapper_body_products_type_numbers"><?php echo count($subscriptions); ?></div>
                <div class="partner-wrapper_body_products_type_name">Subscriptions</div>
            </div>
            <div class="partner-wrapper_body_products_type type-iii">
                <div class="partner-wrapper_body_products_type_img">
                    <img src="https://tidasports.com/wp-content/uploads/2024/04/Group-12430.png" alt="bookings" />
                </div>
                <div class="partner-wrapper_body_products_type_numbers"><?php echo count($slots); ?></div>
                <div class="partner-wrapper_body_products_type_name">Slots</div>
            </div>
        </div>
    </div>
</section>
    <section class="partner-bookings-section">
        <div class="partner-bookings-wrapper">
            <div class="partner-bookings-wrapper_header">
                <h1>Bookings</h1>
            </div>
<?php
if (!empty($bookings)) { ?>
            <div class="partner-bookings-wrapper_body">
                <?php
                $academy = array();
                foreach ($bookings as $booking) { 
                    $id = $booking->product;
                    if(!in_array($id,$academy)){
                        $academy[] = $id;
                        $item = wc_get_product($id);
                        $academy_title = $item->get_name() ? $item->get_name() : "View Academy Details";
                        $academy_address = get_post_meta($id, 'address', true);
                        $academy_link = get_permalink($id);
                        if (has_post_thumbnail( $id ) ): 
                            $image_url = wp_get_attachment_image_src( get_post_thumbnail_id($id ), 'single-post-thumbnail' ); 
                        endif;
                        $product_order_count = array_count_values(array_column($bookings, 'product'))[$id];
                        $total_booking += $product_order_count;
                        ?>
                        <div class="partner-bookings-wrapper_body_products">
                            <div class="partner-bookings-wrapper_body_products_img">
                                <a href="<?php echo $academy_link;?>"><img src="<?php echo $image_url[0]; ?>" alt="bookings" /></a>
                            </div>
                            <div class="partner-bookings-wrapper_body_products_content">
                                <div class="partner-bookings-wrapper_body_products_content_numbers"><?php echo $product_order_count; ?></div>
                                <div class="partner-bookings-wrapper_body_products_content_name"><a href="<?php echo $academy_link;?>"><?php echo $academy_title; ?></a></div>
                                <div class="partner-bookings-wrapper_body_products_content_address"><img src="https://tidasports.com/wp-content/uploads/2024/03/01_align_center.png" alt="image" /><?php echo $academy_address; ?></div>
                                <div class="partner-bookings-wrapper_body_products_content_more"><a href="<?php echo esc_url( home_url( '/my-account/partner-bookings-orders/' . $id.$query_string ) );  ?>">View Orders</a></div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
    <?php }else{ ?>
        <div class="partner-bookings-wrapper">
            <p>No Booking is available for <?php if(isset($_GET['start_date'])){ ?>selected date<?php }else{ ?> Today <?php } ?></p>
        </div>
    <?php } ?>
        </div>
    </section>
    <section class="partner-bookings-section">
        <div class="partner-bookings-wrapper">
            <div class="partner-bookings-wrapper_header">
                <h1>Suscriptions</h1>
            </div>
<?php if (!empty($subscriptions)) { ?>
            <div class="partner-bookings-wrapper_body">
                <?php
                $academy_subsc = array(); 
                foreach ($subscriptions as $subscription) { 
                    $id = $subscription->product;
                    if(!in_array($id,$academy_subsc)){
                        $academy_subsc[] = $id;
                        $item = wc_get_product($id);
                        $academy_title = $item->get_name() ? $item->get_name() : "View Academy Details";
                        $academy_address = get_post_meta($id, 'address', true);
                        $academy_link = get_permalink($id);
                        if (has_post_thumbnail( $id ) ): 
                            $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' ); 
                        endif;
                        $product_order_count = array_count_values(array_column($subscriptions, 'product'))[$id];
                        $total_booking += $product_order_count;
                    ?>
                    <div class="partner-bookings-wrapper_body_products">
                        <div class="partner-bookings-wrapper_body_products_img">
                            <a href="<?php echo $academy_link;?>"><img src="<?php echo $image_url[0]; ?>" alt="bookings" /></a>
                        </div>
                        <div class="partner-bookings-wrapper_body_products_content">
                            <div class="partner-bookings-wrapper_body_products_content_numbers"><?php echo $product_order_count; ?></div>
                            <div class="partner-bookings-wrapper_body_products_content_name"><a href="<?php echo $academy_link;?>"><?php echo $academy_title; ?></a></div>
                            <div class="partner-bookings-wrapper_body_products_content_address"><img src="https://tidasports.com/wp-content/uploads/2024/03/01_align_center.png" alt="image" /><?php echo $academy_address; ?></div>
                            <div class="partner-bookings-wrapper_body_products_content_more"><a href="<?php echo esc_url( home_url( '/my-account/partner-subscriptions-orders/' . $id.$query_string ) ); ?>">View Orders</a></div>
                        </div>
                    </div>
                    <?php
                    }
                }
                ?>
                </div>
    <?php } else{ ?>
        <div class="partner-bookings-wrapper">
            <p>No Booking is available for <?php if(isset($_GET['start_date'])){ ?>selected date<?php }else{ ?> Today <?php } ?></p>
        </div>
    <?php } ?>
        </div>
    </section>
    <section class="partner-bookings-section">
        <div class="partner-bookings-wrapper">
            <div class="partner-bookings-wrapper_header">
                <h1>Slots / Venues</h1>
            </div>
<?php 
if (!empty($slots)) { ?>
            <div class="partner-bookings-wrapper_body">
                <?php
                $venue = array();
                foreach ($slots as $slot) {
                    $id = $slot->product;
                    if(!in_array($id,$venue)){
                        $venue[] = $id;
                        $product = wc_get_product($id);
                        $academy_title = $product->get_name() ? $product->get_name() : "View Academy Details";
                        $academy_address = get_post_meta($id, 'address', true);
                        $academy_link = get_permalink($id);
                        if (has_post_thumbnail( $id ) ): 
                            $image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), 'single-post-thumbnail' ); 
                        endif;
                        $product_order_count = array_count_values(array_column($slots, 'product'))[$id];
                        $total_booking += $product_order_count;
                    ?>
                    <div class="partner-bookings-wrapper_body_products">
                        <div class="partner-bookings-wrapper_body_products_img">
                            <a href="<?php echo $academy_link;?>"><img src="<?php echo $image_url[0]; ?>" alt="bookings" /></a>
                        </div>
                        <div class="partner-bookings-wrapper_body_products_content">
                            <div class="partner-bookings-wrapper_body_products_content_numbers"><?php echo $product_order_count; ?></div>
                            <div class="partner-bookings-wrapper_body_products_content_name"><a href="<?php echo $academy_link;?>"><?php echo $academy_title; ?></a></div>
                            <div class="partner-bookings-wrapper_body_products_content_address"><img src="https://tidasports.com/wp-content/uploads/2024/03/01_align_center.png" alt="image" /><?php echo $academy_address; ?></div>
                            <div class="partner-bookings-wrapper_body_products_content_more"><a href="<?php echo esc_url( home_url( '/my-account/partner-slots-orders/' . $id.$query_string ) ); ?>">View Orders</a></div>
                        </div>
                    </div>
                    <?php
                    }
                }
                ?>
                </div>
    <?php }else{ ?>
        <div class="partner-bookings-wrapper">
            <p>No Booking is available for <?php if(isset($_GET['start_date'])){ ?>selected date<?php }else{ ?> Today <?php } ?></p>
        </div>
    <?php } ?>
        </div>
    </section>
<section class="partner-total-bookings-section">
    <div class="partner-total-bookings-section-wrapper">
        <div class="partner-total-bookings-section-wrapper-container">
            <h1>Total Bookings</h1><h1><?php echo $total_booking;// count($subscription_orders) + count($slots_orders);  ?></h1>
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
            <iframe width="100%" height="100%" style="min-height:510px; border-radius:10px;" src="https://www.youtube.com/embed/X5AW2TqgIMg" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to format date
    const formatDate = (date) => {
        const d = new Date(date);
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        const year = d.getFullYear();
        return [year, month, day].join('-');
    };
    const getUrlParameter = (name) => {
        name = name.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
        const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        const results = regex.exec(window.location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };

    // Get URL parameters
    let fromParam = getUrlParameter('from');
    let toParam = getUrlParameter('to');
    
    // Set default values for from and to dates
    console.log(fromParam,toParam);
    const today = new Date();
    const todayFormatted = formatDate(today);
    fromDate = fromParam ? fromParam : todayFormatted;
    toDate = toParam ? toParam : todayFormatted;

    // Set date inputs
    const from_date_element = document.getElementById('from_date');
    const to_date_element = document.getElementById('to_date');
    from_date_element.value = fromDate;
    from_date_element.setAttribute('max', toDate);
    to_date_element.value = toDate;
    to_date_element.setAttribute('max', todayFormatted);

    // Function to reload page with updated URL based on selected dates
    const updateURL = () => {
        const from_date = encodeURIComponent(document.getElementById('from_date').value);
        const to_date = encodeURIComponent(document.getElementById('to_date').value);
        const baseUrl = `${window.location.origin}${window.location.pathname}`;
        let newUrl = `${baseUrl}?from=${from_date}&to=${to_date}`;
        newUrl = newUrl.replace(/&#038;/g, '&');
        window.location.href = newUrl;
    };

    // Listen for changes in date inputs
    document.getElementById('from_date').addEventListener('change', updateURL);
    document.getElementById('to_date').addEventListener('change', updateURL);
});
</script>