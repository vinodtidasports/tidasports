<?php
/**
 * The template for displaying the archive of Showcase custom post type.
 * Template Name: Showcase Archive Template
 */

get_header();

?>

<style>
    /* Add your existing styles here */
</style>

<?php 
    echo do_shortcode("[show_banner_of_page avatar='' title='Tida Sports Tournaments Showcase and Match Highlights' description=\"<p>Catch the latest match highlights of our various academies or schools. Relive thrilling moments with cricket, football, badminton, acting, and more sports highlights. Stay updated with <a href='https://tidasports.com/'>TIDA Sports</a> for all the action. Also, you can download our app from <a href='https://apps.apple.com/in/app/tidasports/id6449814108' target='_blank'>Playstore</a> and <a href='https://play.google.com/store/apps/details?id=org.netgains.tidasports&pli=1' target='_blank'> Appstore </a> to get the latest updates on tournaments.</p>\"]");
?>

<section class="showcase_archive_template">
    <div class="showcase_archive_template__container">
        <?php
        // Custom query for pagination
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'showcase', // Change 'showcase' to your custom post type slug
            'posts_per_page' => 9, // Number of posts per page
            'paged' => $paged
        );
        $custom_query = new WP_Query($args);

        // Start the Loop
        if ($custom_query->have_posts()) {
            while ($custom_query->have_posts()) {
                $custom_query->the_post();
                $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                $title = get_the_title();
                $content = get_the_content();
                $permalink = get_the_permalink();

// Strip any HTML tags and images from the content
    $content = wp_strip_all_tags($content); 

    // Limit content to 50 words
    $words = explode(' ', $content);
    $content = implode(' ', array_slice($words, 0, 15)) . '...';
                ?>
                <div class="showcase_archive_template__container__wrapper">
                    <div class="showcase_archive_template__container__wrapper__img">
                        <img src="<?php echo $thumbnail_url; ?>" alt="image" />
                    </div>
                    <div class="showcase_archive_template__container__wrapper__content">
                        <div class="showcase_archive_template__container__wrapper__content__details">
                            <h1 class="showcase_archive_template__container__wrapper__content__details__title"><?php echo $title; ?></h1>
                            <p class="showcase_archive_template__container__wrapper__content__details__description"><?php echo $content; ?></p>
                        </div>
                        <div class="showcase_archive_template__container__wrapper__content__button">
                            <a href="<?php echo $permalink; ?>">Watch Now</a>
                        </div>
                    </div>
                </div>
            <?php
            }
            // Pagination
            $big = 999999999; // need an unlikely integer
            $pagination_links = paginate_links(array(
                'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                'format' => '?paged=%#%',
                'current' => max(1, get_query_var('paged')),
                'total' => $custom_query->max_num_pages,
                'prev_text' => __('Prev'),
                'next_text' => __('Next'),
            ));
            echo '<div class="pagination showcase_pagination">' . $pagination_links . '</div>';
        } else {
            echo "<h3 class='showcase_archive_template__container__wrapper__content__details__title'>No Showcase Found</h3>";
        }
        // Restore original Post Data
        wp_reset_postdata();
        ?>
    </div>
</section>

<?php 
get_footer();
?>
