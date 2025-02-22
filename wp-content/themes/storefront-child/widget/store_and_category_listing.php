<style>
    .tida-sports_and_category-widget-container{
        max-width:1140px;
        margin:auto;
        display:grid;
        grid-template-columns: repeat(4,1fr);
        justify-content:space-between;
        gap:20px;
        margin-bottom:50px;
        /*border:1px solid red;*/
    }
    .tida-sports_and_category-widget-container a{
        /*box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;*/
        border-radius:10px;
        overflow:hidden;
    }
    .tida-sports_and_category-widget-container a:hover{
        /*box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;*/
    }
    .tida-sports_and_category-widget-container__taxonomy{
        display:flex;
        flex-direction:column;
        align-items:center;
        height:100%;
        max-width:400px;
        margin:auto;
    }
    .product_cat a{
        box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
        padding:10px 20px;
    }
    
    .tida-sports_and_category-widget-container__taxonomy__image_wrapper{
        flex:9;
    }
    .tida-sports_and_category-widget-container__taxonomy__content{
        flex:1;
        display:flex;
        gap:10px;
        width:100%;
    }
    .tida-sports_and_category-widget-container__taxonomy__content__icon{
        height:30px;
        width:30px;
    }
    .tida-sports_and_category-widget-container__taxonomy__content__icon img{
        height:100%;
        width:100%;
    }
    .tida-sports_and_category-widget-container__taxonomy__content h3{
        text-transform: capitalize;
        font-size:20px;
        font-weight: 900;
        color: #04244c;
        margin:0;
    }
    
    .product_cat a:hover{
        box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
        color:#fff !important;
    }
    
    @media screen and (max-width:1020px){
        .tida-sports_and_category-widget-container{
            grid-template-columns: repeat(3,1fr);
        }
    }
    @media screen and (max-width:767px){
        .tida-sports_and_category-widget-container{
            grid-template-columns: repeat(2,1fr);
        }
    }
</style>

<?php
    $taxonomy_value = isset($taxonomy) ? $taxonomy : 'sport';
    $type = isset($type) ? $type : '';
    $sport_terms = json_decode(do_shortcode("[getting_tida_store_and_category taxonomy='$taxonomy_value' type='$type']"));
?>
<div class="tida-sports_and_category-widget <?php echo $taxonomy_value; ?>">
    <div class="tida-sports_and_category-widget-container">
        <?php
        foreach ($sport_terms as $term) :
            // var_dump($term);
            ?>
            <a href="<?php echo  get_term_link($term); ?>">
                <div class="tida-sports_and_category-widget-container__taxonomy">
                    <?php if ($image = get_field('icon', $term)) : ?>
                        <div class="tida-sports_and_category-widget-container__taxonomy__image_wrapper">
                            <img class="tida-sports_and_category-widget-container__taxonomy__image_wrapper_img" src="<?php echo $image; ?>" loading="lazy">
                        </div>
                    <?php endif; ?>
                    <div class="tida-sports_and_category-widget-container__taxonomy__content">
                        <?php if ($image = get_field('title_icon', $term)) : ?>
                        <div class="tida-sports_and_category-widget-container__taxonomy__content__icon">
                            <img class="head-icon" src="<?php echo $image; ?>" loading="lazy">
                        </div>
                        <?php endif; ?>
                        <h3><?php echo esc_html($term->name); ?></h3>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
