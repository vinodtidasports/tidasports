<div class="tida-venue-widget">
    <div class="venue-sliders-container">
        <div class="venue-sliders-wrapper">
            <?php
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 15, // Number of products to display
		'post_status'    => 'publish',
                'meta_query' => array(
                    array(
                        'key' => 'product_type', // Replace with your custom field key
                        'value' => 'venue',
                        'compare' => '=', // Use '=' for exact match
                    ),
                ),
            );
            $loop = new WP_Query($args);
            if ($loop->have_posts()) :
                while ($loop->have_posts()) : $loop->the_post(); ?>
                    <div class="venue-slider">
                        <div class="venue-sliders-content">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="venue-sliders-image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', array('class' => 'venue-thumbnail', 'loading' => 'lazy')); ?>
                                    </a>
                                </div>
                                  <div class="main-starss">
                                    <!-- <span class="starts">★★★★★</span> -->
                                    </div>
                            <?php endif; ?>
                            <div class="contens">
                                <span class="venue-text-contents">
                                    <a href="<?php the_permalink(); ?>"><?php echo wp_trim_words(get_the_title(), 4); ?></a>
                                </span>
                            </div>
                            <div class="taddd">
                                <img loading="lazy" class="add--img" src="https://tidasports.com/wp-content/uploads/2024/03/01_align_center.png">
                                <span class="address_field">
                                    <?php echo wp_trim_words(get_post_meta(get_the_ID(), 'address', true), 6); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata(); // Reset the post data to restore the global $post variable
            endif; ?>
        </div>
    </div>
     <button class="venue-prev-btn">&#10094;</button>
        <button class="venue-next-btn">&#10095;</button>
</div>