<?php
/**
 * Template Name: School Collaborations Product
 * Template Post Type: product
 */
get_header(); 

if (have_posts()) : while (have_posts()) : the_post();
    global $product;
    $title = $product->get_title();
    $description = $product->get_description();
    $address = get_post_meta( $product->get_id(), 'address', true );
    $encoded_address = urlencode($address);
    $gallery_ids = $product->get_gallery_image_ids();
    $product_id = $product->get_id();
    $sports_terms = wp_get_post_terms($product_id, 'sport');
?>
<style>
.woocommerce_single_product_sports{
    display: flex;
    gap:20px;
    overflow-x: scroll;
    width: 100%;
    margin: 0;
    border-radius: 8px;
    justify-content: start;
}
/* Custom scrollbar styles */
.woocommerce_single_product_sports::-webkit-scrollbar {
    height: 6px; /* Adjust the height of the scrollbar */
}

.woocommerce_single_product_sports::-webkit-scrollbar-track {
    background: #f1f1f1; /* Track color */
    border-radius: 8px;
}

.woocommerce_single_product_sports::-webkit-scrollbar-thumb {
    background-color: #888; /* Scrollbar thumb color */
    border-radius: 8px;
    border: 1px solid #f1f1f1; /* Adds padding around the thumb */
}

.woocommerce_single_product_sports::-webkit-scrollbar-thumb:hover {
    background-color: #555; /* Thumb color on hover */
}

.woocommerce_single_product_sport{
    margin: 10px;
    padding: 10px;
    font-size: 10px;
    width: 100%;
    min-width: 120px;
    box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    list-style: none;
    border-radius: 10px;
    transform: scale(1);
    transition: transform 0.3s ease-in-out;
}

.woocommerce_single_product_sport:hover {
    transform: scale(1.1);
}

.woocommerce_single_product_sport img{
    min-height: 80px;
    max-height: 80px;
    width: auto;
    margin: auto;
}
.woocommerce_single_product_sport h3{
    text-align: center;
    font-size: 16px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: #08224f;
    font-weight: 600;
}

/* Swiper CSS Start */

.swiper-container {
    max-width: 1400px;
    margin: auto;
    margin-bottom: 20px;
    padding: 20px;
    display: flex;
    overflow: hidden;
    position: relative;
}

.swiper-wrapper {
    display: flex;
    align-items: center;
    width: 100%;
    max-width: 100%;
}

.swiper-slide {
/*     border: 1px solid red !important; */
    width: 100% !important;
    max-width: 100% !important;
    display: flex;
    justify-content: center;
    align-items: center;
}
.swiper-slide.swiper-slide-active img{
    border: 1px solid gainsboro;
    box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;
}

.swiper-pagination {
    bottom: 20px;
    left: 0;
    width: 100%;
    text-align: center;
    display:none;
}
/*
.swiper.swiper-initialized.swiper-horizontal.swiper-backface-hidden{
    max-width: 1200px !important
}
*/
.swiper-button-next:after, .swiper-rtl .swiper-button-prev:after {
    content: 'next';
    font-size: 16px !important;
    color: #fff;
    background-color: #04244C;
    padding: 20px;
    border-radius: 50%;
    height: 8px;
    width: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}
.swiper-button-prev:after, .swiper-rtl .swiper-button-next:after {
    content: 'prev';
    font-size: 16px !important;
    color: #fff;
    background-color: #04244C;
    padding: 20px;
    border-radius: 50%;
    height: 8px;
    width: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
}
.swiper-slide img{
    max-height: 510px !important;
}

.swiper-button-next {
    top: 50%;
}
.swiper-button-prev {
    left: 20px;
}

/* Swiper CSS End */

.woocommerce_single_product_showcases_section__wrapper_left{
    max-width: 1400px;
    margin: auto;
    padding: 20px 20px 0 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap:wrap;
}

.woocommerce_single_product_game_intro_section_wrapper__text_wrapper__title,
.woocommerce_single_product_showcases_section__wrapper_left__title {
    font-family: "neutra-display", Sans-serif !important;
    font-size: 32px;
    font-weight: normal;
    font-style: normal;
    text-align: left;
    color: #08224f;
    margin: 0;
}
section.product-gallery,
section.woocommerce_single_product_title_section,
section.woocommerce_single_product_description_section,
section.woocommerce_single_product_showcases_section,
section.showcase_archive_template {
    padding: 0 90px;
}

@media screen and (max-width: 1024px) {
    section.product-gallery,
    section.woocommerce_single_product_title_section,
    section.woocommerce_single_product_description_section,
    section.woocommerce_single_product_showcases_section,
    section.showcase_archive_template {
        padding: 0 60px;
    }
}

@media screen and (max-width: 667px) {
    section.product-gallery,
    section.woocommerce_single_product_title_section,
    section.woocommerce_single_product_description_section,
    section.woocommerce_single_product_showcases_section,
    section.showcase_archive_template {
        padding: 0px;
    }
}

</style>
    <section class="product-gallery">
    <div class="swiper-container" >
        <div class="swiper-wrapper">
            <?php foreach ($gallery_ids as $attachment_id) : ?>
                <?php
                $image_url = wp_get_attachment_image_url($attachment_id, 'full');
                ?>
                <div class="swiper-slide">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)); ?>" />
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Navigation -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
    </section>
    <section class="woocommerce_single_product_title_section">
        <div class="woocommerce_single_product_title_section__wrapper">
            <div class="woocommerce_single_product_title_section__wrapper_left">
                <h1 class="woocommerce_single_product_title_section__wrapper_left__title"><?php echo $title ?></h1>
                <p class="woocommerce_single_product_title_section__wrapper_left__address">
                    <img src="<?php echo site_url(); ?>/wp-content/uploads/2024/03/01_align_center.png" alt="location_icon" />
                    <?php //echo $address; ?>
		    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $encoded_address; ?>" target="_blank">
        		<?php echo $address; ?>
    		    </a>
                </p>
            </div>
        </div>
    </section>
    <section class="woocommerce_single_product_description_section">
        <div class="woocommerce_single_product_description_section__wrapper">
            <p class="woocommerce_single_product_description_section__wrapper__description"><?php echo $description ?></p>
	    <?php if (!empty($sports_terms) && !is_wp_error($sports_terms)) : ?>
                <div class="woocommerce_single_product_sports_wrapper">
                    <h2 class="woocommerce_single_product_game_intro_section_wrapper__text_wrapper__title">Related Sports:</h2>
                    <ul class="woocommerce_single_product_sports">
                        <?php foreach ($sports_terms as $term) : ?>
                            <?php 
                            // Get the ACF field 'title_icon' from the sport term
                            $title_icon_url = get_field('title_icon', 'sport_' . $term->term_id);
                            ?>
                            <li class="woocommerce_single_product_sport">
                                <div>
                                    <div>
                                        <?php if ($title_icon_url) : ?>
                                            <img src="<?php echo esc_url($title_icon_url); ?>" alt="<?php echo esc_attr($term->name); ?>" />
                                        <?php endif; ?>
                                    </div>
                                    <h3><?php echo esc_html($term->name); ?></h3>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </section>

   <section class="woocommerce_single_product_showcases_section">
        <div class="woocommerce_single_product_showcases_section__wrapper">
            <div class="woocommerce_single_product_showcases_section__wrapper_left">
                <h1 class="woocommerce_single_product_showcases_section__wrapper_left__title">Showcases</h1>
                <div>
                    <div class="button-wrapper"><button class="button-wrapper__button"><a style="color: darkblue;" href="/showcase/">
			View All Showcase</a>
		    </button></div>
                </div>
            </div>
        </div>
    </section>

<?php
    echo do_shortcode('[showcase_archive post_type="showcase" posts_per_page="6" orderby="title" order="ASC" limit="3"]');
    endwhile; endif; // End of the loop.
    get_footer(); 
?>