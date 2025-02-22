<div class="showcase-sliders-container">
    <div class="showcase-sliders-wrapper">
        <?php
        $showcase_query = new WP_Query(array(
            'post_type' => 'showcase',
            'posts_per_page' => 3,
        ));

        while ($showcase_query->have_posts()) : $showcase_query->the_post();
            ?>
            <div class="showcase-slider">
                <div class="showcase-slider-content">
                    <div class="showcase-slider-image">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium', array('class' => 'slickers-images radius-15', 'loading' => 'lazy')); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="addd">
                        <h3 class="additional-heading">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h3>
                        <div class="infos">
                            <img class="add--img" src="https://tidasports.com/wp-content/uploads/2024/03/01_align_center.png" loading="lazy">        
                            <span class="address_field">
                                <?php echo wp_trim_words(get_post_meta(get_the_ID(), 'address', true), 8); ?>
                            </span>
                        </div>    
                    </div> 
                </div>
            </div>
        <?php endwhile;
        wp_reset_postdata(); ?>
    </div>

</div>
