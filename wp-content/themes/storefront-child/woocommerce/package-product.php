<?php
/**
 * The template for displaying product package within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/package-product.php.
 *
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

// Ensure visibility
if ( ! defined( 'WPINC' ) ) {
    die;
}

global $product;
?>

<div class="woocommerce-product-packages">
    <?php 
    if ( $product->is_type( 'variable' ) ) {
        // Get the variation data
        $variations = $product->get_available_variations();
        // Loop through each variation
        if (is_array($variations)) {
            foreach ( $variations as $variation ) {
                // Extracting data from the variation array
                // print_r($variation);
                $variation_id = $variation['variation_id'];
                $attributes = $variation['attributes'];
                /*if (isset($variation['attributes']['attribute_pa_package'])) {
                    $attribute_pa_package = $variation['attributes']['attribute_pa_package'];
                } elseif (isset($variation['attributes']['attribute_packages'])) {
                    $attribute_pa_package = $variation['attributes']['attribute_packages'];
                }else{
                }*/
				foreach($attributes as $attribute){
					$attribute_pa_package = $attribute;
				}
        		$item_variation = wc_get_product($variation_id);
				$academy_name = get_the_title() . ' - ';
                $variation_name = str_replace($academy_name, ' ', wp_strip_all_tags(html_entity_decode($item_variation->get_name())));
				str_replace('-', ' ', $attribute_pa_package);
                $price_html = $variation['price_html'];
				if(($price_html)){
					$price_array = explode(".00", $price_html);
					$price_value = $price_array[0];
					$price_details = $price_array[1];
				}else if($variation['display_price']){
					$_product = new WC_Product_Variation( $variation_id );
					$_currency = get_woocommerce_currency_symbol();
					$price_value =  $_currency . number_format($_product->get_price(), 2);
					$price_details = '';
				}else{
					$price_value = '';
				}
                // $price_parts = explode(".00", $price);
    
                // Extracting the price value and details
                // $price_value = trim($price_parts[0]);
                // $details = trim($price_parts[1]);
    
                $variation_description = $variation['variation_description'];
                $coupon = isset($variation['coupon']) ? $variation['coupon'] : ""; 
                $ratings = isset($variation['ratings']) ? $variation['ratings'] : $product->get_average_rating();
                if ($ratings <= 0) {
                    $ratings = 5;
                }
                // Outputting the retrieved information
                // echo "Name: $name <br>";
                // echo "Description: $description <br>";
                // echo "Price: $price <br>";
                // echo "Coupon: $coupon <br>";
                // echo "Ratings: $ratings <br>";
                // // Linking to the product variation page with a "Book Now" button
                // echo "<a href='" . get_permalink( $product->get_id() ) . "?attribute_pa_package=" . 
$variation['attributes']['attribute_pa_package'] . "'><button>Book Now</button></a> <br>";
                // var_dump($variation);
                ?>
                <a href="?add-to-cart=<?php echo $variation_id; ?>" class="woocommerce-product-packages__package">
                    <!--<div class="woocommerce-product-packages__package" >-->
                        <!-- <div class="woocommerce-product-packages__package__tags">
                            <p class="woocommerce-product-packages__package__tags__ratings">
                                <?php
                                    for ($i = $ratings; $i > 0; $i--) {
                                        echo '★';
                                    }
                                ?>
                            </p>
                            <p class="woocommerce-product-packages__package__tags__coupen">30%</p>
                            <?php 
                                if($coupon){
                                    echo "<p>$coupon</p>";
                                }
                            ?>
                            
                        </div> -->
                        <div class="woocommerce-product-packages__package__content">
                            <div class="woocommerce-product-packages__package__content__title-and-des">
                                <h1 class="woocommerce-product-packages__package__content__title-and-des__title"><?php echo 
$variation_name; ?></h1>
                                <p class="woocommerce-product-packages__package__content__title-and-des__description"><?php 
echo $variation_description; ?></p>
                            </div>
                            <div class="woocommerce-product-packages__package__content__price">
							<?php if($price_value){ ?>
                                <p>
                                    <?php echo $price_value; ?>
                                </p>
                                <p>
                                    <?php // echo $price_details; ?>
                                </p>
							<?php } ?>
                            </div>
                        </div>
                    <!--</div>-->
                </a>
                <?php
            }
        }
    }
    ?>
</div>



