<?php
/*
Template Name: My Blog Template
*/
get_header(); ?>

<style>
.blog-posts-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    max-width: 1360px;
    margin: 60px auto;
    padding: 0 60px;
}
.post-card {
    padding: 12px;
    background: #F5F5F5;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    transform: translateY(0);
    transition: transform 0.3s ease-in-out;
}
.post-card:hover {
    transform: translateY(-20px);
}
.post-card img {
    width: 100%;
    height: auto;
    min-height: 240px;
    object-fit: cover;
}
.post-card h2 {
    font-size: 20px;
    margin-top: 10px;
    font-size: 22px;
    font-weight: 700;
    color: #000;
    margin: 0;
}
.post-card p {
    font-size: 18px;
    color: #6A6A6A;
    margin: 0;
}
.read-more {
    display: inline-block;
    margin-top: 10px;
    color: #F82C44;
    font-weight: 700;
    font-size: 20px;
    text-decoration: none;
}

/* Pagination container */
.load-more {
    grid-column: span 3;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    padding: 15px;
    margin-top: 20px;
    font-family: Arial, sans-serif;
    flex-wrap: wrap;
}

/* Links and buttons */
.page-numbers {
    display: inline-block;
    padding: 10px 15px;
    font-size: 14px;
    color: #04244C;
    text-decoration: none;
    border: 1px solid #ddd;
    border-radius: 5px;
    transition: all 0.3s ease;
    background-color: #fff;
}

/* Current page number */
.page-numbers.current {
    background-color: #04244C;
    color: #fff;
    border-color: #04244C;
    font-weight: bold;
    cursor: default;
}

/* Dots for skipped pages */
.page-numbers.dots {
    color: #04244C;
    cursor: default;
    pointer-events: none;
}

/* Hover effect */
.page-numbers:hover {
    background-color: #04244C;
    border-color: #04244C;
    color: #fff;
}

/* Previous and Next buttons */
.prev, .next {
    font-weight: bold;
}


@media screen and (max-width: 1024px){
.blog-posts-container {
    margin: 30px auto;
    padding: 0 40px;
}
.load-more {
    grid-column: span 2;
}
}

@media screen and (max-width: 667px){
.blog-posts-container {
    margin: 30px auto;
    padding: 0 20px;
}
.page-numbers {
        padding: 8px 12px;
        font-size: 12px;
    }
.load-more {
    grid-column: span 1;
}

}

</style>
<?php 
    echo do_shortcode("[show_banner_of_page avatar='' title='BLOG' description=\"<p>Discover TIDA Sports blogs: app updates, insights, and more on the benefits of sports for students' health and growth.</p>\"]");
?>
<div class="blog-posts-container">
    <?php
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $query = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => 9, // Show 9 posts per page
        'paged' => $paged,
    ]);

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            <div class="post-card">
                <a href="<?php the_permalink(); ?>">
                    <?php if (has_post_thumbnail()) : ?>
                        <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>">
                    <?php endif; ?>
                </a>
                <h2><?php the_title(); ?></h2>
                <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                <a class="read-more" href="<?php the_permalink(); ?>">Read More</a>
            </div>
        <?php endwhile; ?>

        <div class="load-more">
            <?php
            echo paginate_links([
                'total' => $query->max_num_pages,
            ]);
            ?>
        </div>
    <?php else : ?>
        <p>No posts found!</p>
    <?php endif;

    wp_reset_postdata(); ?>
</div>

<?php get_footer(); ?>