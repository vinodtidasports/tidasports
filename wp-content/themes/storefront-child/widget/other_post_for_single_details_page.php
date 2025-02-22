<style>
    .other-post-for-single-details-page-container{
        max-width:1160px;
        margin:30px auto;
        padding:0 20px;
    }
    .other-post-for-single-details-page-container h1{
        font-size: 36px;
        font-weight: bold;
        font-style: normal;
        text-align: left;
        color: #04255e;
        margin:16px 0;
        text-transform: capitalize;
    }
    .other-post-for-single-details-page-wrapper{
        display:grid;
        gap:20px;
        grid-template-columns:repeat(3,1fr);
        justify-content:space-between;
        background:transparent;
    }
    
    .other-post-for-single-details-page-wrapper a{
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
        border-radius:12px;
        overflow:hidden;
    }
    .other-post-for-single-details-page-wrapper a:hover{
        box-shadow: none;
    }
    .other-post-for-single-details-page__post__image_wrapper img{
        width:100%;
    }
    .other-post-for-single-details-page__post__content{
        padding:0 20px;
    }
    .other-post-for-single-details-page__post__content h3{
        font-size: 24px;
        font-weight: bold;
        font-style: normal;
        text-align: left;
        color: #04255e;
        margin:16px 0;
    }
    .other-post-for-single-details-page__post__content p{
        font-size: 18px;
        font-style: normal;
        text-align: left;
        color: #04255e;
    }
    
    @media screen and (max-width:1024px){
        .other-post-for-single-details-page-wrapper{
            grid-template-columns:repeat(2,1fr);
        }
    }
    @media screen and (max-width:767px){
        .other-post-for-single-details-page-wrapper{
            grid-template-columns:repeat(1,1fr);
        }
    }
</style>

<?php
    $type_value = isset($type) ? $type : 'post';
    $exclude_id_value = isset($exclude_id) ? $exclude_id : '';
    $post_per_page_value = isset($post_per_page) ? $post_per_page : 3;
    // echo $type_value . $exclude_id_value . $post_per_page_value;
    $other_posts = json_decode(do_shortcode("[getting_other_post_for_single_details_page type='$type_value' exclude_id='$exclude_id_value' post_per_page='$post_per_page_value']"));
    // var_dump($other_posts);
?>

<div class="other-post-for-single-details-page">
    <div class="other-post-for-single-details-page-container">
        <h1 class="">Recent <?php echo $type_value; ?>s</h1>
    <div class="other-post-for-single-details-page-wrapper">
        <?php
        if ($other_posts->posts) {
            foreach ($other_posts->posts as $post) {
                setup_postdata($post); // Set up post data
                $permalink = get_permalink();
                $thumbnail = get_the_post_thumbnail($post->ID, 'medium');
                $title = get_the_title($post);
                $content = wp_trim_words(get_the_content($post), 25, '...');
                ?>
                <a href="<?php echo esc_url($permalink); ?>">
                    <div class="other-post-for-single-details-page__post">
                        <?php if (isset($thumbnail)) : ?>
                            <div class="other-post-for-single-details-page__post__image_wrapper">
                                <?php echo $thumbnail; ?>
                            </div>
                        <?php endif; ?>
                        <div class="other-post-for-single-details-page__post__content">
                            <h3><?php echo esc_html($title); ?></h3>
                            <p><?php echo esc_html($content); ?></p>
                        </div>
                    </div>
                </a>
                <?php
            }
            wp_reset_postdata(); // Reset post data
        } else {
            echo '<p>No other posts found.</p>';
        }
        ?>
    </div>
    </div>
</div>

