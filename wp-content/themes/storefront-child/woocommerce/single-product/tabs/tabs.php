<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters('woocommerce_product_tabs', array());
global $product;
if (!empty($tabs)) : 
?>
    <div class="woocommerce-tabs accordion">
        <?php 
        $first = true;
        foreach ($tabs as $key => $tab) : 
            $active_class = $first ? ' active' : '';
            $show_class = $first ? ' show' : '';
            $first = false;
        ?>
            <div class="saif accordion-item<?php echo $active_class; ?>">
                <div class="accordion-header" id="heading-<?php echo esc_attr($key); ?>" data-toggle="collapse" data-target="#collapse-<?php echo esc_attr($key); ?>" aria-expanded="true" aria-controls="collapse-<?php echo esc_attr($key); ?>">
                    <?php 
                    echo apply_filters('woocommerce_product_' . $key . '_tab_title', esc_html($tab['title']), $key);
                    if (esc_attr($key) == 'reviews') {
                        echo '(' . $product->get_review_count() . ')';
                    }
                    ?>
                </div>
                <div id="collapse-<?php echo esc_attr($key); ?>" class="accordion-collapse collapse<?php echo $show_class; ?>" aria-labelledby="heading-<?php echo esc_attr($key); ?>" data-parent=".woocommerce-tabs">
                    <div class="accordion-body">
                        <?php 
                        if (isset($tab['callback'])) {
                            call_user_func($tab['callback'], $key, $tab);
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

