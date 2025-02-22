<div class="tida-sports-widget">
    <div class="sliders-container">
        <div class="sliders-wrapper">
            <?php
            $sport_terms = get_terms(array(
                'taxonomy' => 'sport',
                'hide_empty' => false,
                'post_per_page' => -1,
            ));
            foreach ($sport_terms as $term) :
                ?>
                <div class="slider">
                    <div class="sliders-content">
                        <?php if ($image = get_field('icon', $term)) : ?>
                            <div class="sliders-image">
                                <a href="<?php echo get_term_link($term); ?>">
                                    <img class="slickers-images" src="<?php echo $image; ?>" loading="lazy">
                                </a>
                            </div>
                        <?php endif; ?>
                        <span class="text-contents">
                            <?php if ($image = get_field('title_icon', $term)) : ?>
                                <a href="<?php echo get_term_link($term); ?>">
                                    <img class="head-icon" src="<?php echo $image; ?>" loading="lazy">
                                </a>
                            <?php endif; ?>
                            <a href="<?php echo get_term_link($term); ?>"><?php echo esc_html($term->name); ?></a>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
     
    </div>
       <button class="prev-btn">&#10094;</button>
        <button class="next-btn">&#10095;</button>
</div>
