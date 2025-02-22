<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php
	$title = $product->get_title();
	$permalink = $product->get_permalink();
	$rating = $product->get_average_rating();
	if($rating == 0) $rating = 5;
	$price = $product->get_price();
	$image_id = $product->get_image_id();
	$image_url = wp_get_attachment_image_url( $image_id, 'full' );
    	$address = get_post_meta( $product->get_id(), 'address', true );
	$latitude = get_post_meta( $product->get_id(), 'latitude', true );
	$longitude = get_post_meta( $product->get_id(), 'longitude', true );
	$google_maps_url = "https://www.google.com/maps/?q={$latitude},{$longitude}";
	$encoded_address = urlencode($address);
    ?>
    <style>
        *{
            box-sizing:border-box;
            margin:0;
            padding:0;
        }
        li.product.type-product {
            margin-bottom: 0px !important;
        }
        .products.columns-3{
            max-width:1400px;
            margin:auto;
            display:grid;
            grid-template-columns: repeat(3,1fr);
            gap:20px;
            
        }
        .product.type-product{
            width:100% !important;
        }
        .products.columns-3::before,
        .products.columns-3::after{
            display:none;
        }
        .content-product-template__product{
            width:100%;
            display:flex;
            flex-direction:column;
            flex:1;
        }
        .content-product-template__product__image-wrapper{
            display:flex;
            min-height:320px;
            max-height:320px;
            height:100%;
        }
        .content-product-template__product__image-wrapper__img{
            width:100%;
            border-radius:12px !important;
	    border: 1px solid gainsboro !important;
        }
        .content-product-template__product__content{
            display:flex;
            flex-direction:column;
        }
        .content-product-template__product__content__meta{
            display:flex;
            justify-content:space-between;
            align-items:center;
        }
        .content-product-template__product__content__meta_price {
            font-family: "Nunito";
            font-size: 19px;
            font-weight: 300;
            font-style: normal;
            text-align: right;
            color: #888888;
        }
        .content-product-template__product__content__meta_price span{
            font-size:20px;
            font-family: "Nunito";
            font-weight: 300;
            font-style: normal;
            text-align: right;
            color:#F72E42;
        }
        .content-product-template__product__content__main_title{
            text-align:left;
            font-family: "neutra-display";
            font-size: 24px;
            font-weight:600;
            font-weight: normal;
            font-style: normal;
            text-align: left;
            color: #08224f;
        }
        
        .content-product-template__product__content__main_address{
            font-family: "Nunito";
            font-size:18px;
            font-weight: normal;
            font-style: normal;
            text-align: left;
            color: #888888;
        }
        @media screen and (max-width:1024px){
            .products.columns-3{
                grid-template-columns: repeat(2,1fr);
                gap:20px;
                padding:20px;
            }
            
            .woocommerce-products-header__wrapper{
                padding:20px;
            }
            .woocommerce-products-header__title{
                font-family:"neutra-display", sans-serif;
                font-size: 48px;
                font-weight: normal;
                font-style: normal;
                text-align: left;
                color: #ffffff;
                margin-bottom:0;
            }
            .woocommerce-products-header__description{
                font-family: sans-serif;
                font-size: 20px;
                font-weight: normal;
                font-style: italic;
                text-align: left;
                color: #ffffff;
                text-transform:uppercase;
            }
            
        }
        @media screen and (max-width:767px){
            .products.columns-3{
                grid-template-columns: repeat(1,1fr);
            }
        }
        
    </style>
    <div class="content-product-template__product">
        <a href="<?php echo $permalink ?>">
            <div class="content-product-template__product__image-wrapper">
                <img class="content-product-template__product__image-wrapper__img" src="<?php echo $image_url; ?>" alt="<?php echo $image_id; ?>" />
            </div>
        </a>
        <div class="content-product-template__product__content">
            <div class="content-product-template__product__content__meta">
                <div class="content-product-template__product__content__meta_ratings">
                    <?php
                    for ($i = $rating; $i > 0; $i--) {
                        echo '<svg  style="height:20px; width:20px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">';
                        echo '<path d="M316.9 18C311.6 7 300.4 0 288.1 0s-23.4 7-28.8 18L195 150.3 51.4 171.5c-12 1.8-22 10.2-25.7 21.7s-.7 24.2 7.9 32.7L137.8 329 113.2 474.7c-2 12 3 24.2 12.9 31.3s23 8 33.8 2.3l128.3-68.5 128.3 68.5c10.8 5.7 23.9 4.9 33.8-2.3s14.9-19.3 12.9-31.3L438.5 329 542.7 225.9c8.6-8.5 11.7-21.2 7.9-32.7s-13.7-19.9-25.7-21.7L381.2 150.3 316.9 18z" fill="red" />';
                        echo '</svg>';
                    }
                    ?>
                </div>

                <div class="content-product-template__product__content__meta_price"><span><?php echo "&#x20B9;" . $price; ?></span> onwards</div>
            </div>
            <div class="content-product-template__product__content__main">
                <h1 class="content-product-template__product__content__main_title">
                    <a href="<?php echo $permalink ?>"><?php echo $title ?></a>
                </h1>
                <p class="content-product-template__product__content__main_address">
                    <svg style="height:16px; width:16px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                        <path d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z" fill="red"/>
                    </svg>

                    <?php //echo $address; ?>
			<a href="<?php echo esc_url($google_maps_url); ?>" target="_blank">
        		<?php echo $address; ?>
    			</a>
                </p>
            </div>
            
        </div>
        
    </div>
</li>