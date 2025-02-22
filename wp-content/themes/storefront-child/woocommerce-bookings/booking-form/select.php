<?php
/**
 * The template used for select fields in the booking form, such as resources
 *
 * This template can be overridden by copying it to yourtheme/woocommerce-bookings/booking-form/select.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/bookings-templates/
 * @author  Automattic
 * @version 1.8.0
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$class   = $field['class'];
$label   = $field['label'];
$name    = $field['name'];
$options = $field['options'];

global $product;

$product_id = $product->get_id();
$pid = $product_id;
$year = date('Y');
$month = date('m');
$day = date('d');
$data = array("id"=>$pid,"year"=>"$year","month"=>"$month","day"=>"$day");
?>

<p class="form-field form-field-wide venue_resource_value_p <?php echo esc_attr( implode( ' ', $class ) ); ?>">
	<!--<label for="<?php //echo esc_attr( $name ); ?>"><?php echo esc_html( $label ); ?>:</label>-->
	<select name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $name ); ?>" class="venue_resource_value">
		<?php foreach ( $options as $key => $value ) : ?>
			<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $value ); ?></option>
		<?php endforeach; ?>
	</select>
</p>
<div class="woocommerce-product-slots">
    <?php foreach ( $options as $key => $value ) : ?>
        <?php 
            $slots = rand(0, 100);
        ?>
        <button class="woocommerce-product-slots__slot woocommerce-product-slots__slot-select" value-data="<?php echo esc_attr( $key ); ?>">
            <div class="woocommerce-product-slots__slot_wrapper">
                <h1 class="woocommerce-product-slots__slot_wrapper__name"><?php echo esc_html( $value ); ?></h1>
                <div class="woocommerce-product-slots__slot_wrapper__details">
                    <h1 class="woocommerce-product-slots__slot_wrapper__details__interval"><?php echo $slots; ?></h1>
                    <div class="woocommerce-product-slots__slot_wrapper__details__time-and-cost">
                        <p><?php echo $slots > 0 ? "SLOTS<br/>AVAILABLE" : "ALL SLOTS<br/>BOOKED" ?></p>
                    </div>
                </div>
            </div>
        </button>
    <?php endforeach; ?>
</div>