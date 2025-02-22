<?php
/**
 * The template for displaying product slot within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/slot-product.php.
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

<div class="woocommerce-product-slots">
    <style>
    .woocommerce-product-slots{
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:20px;
    }
    .woocommerce-product-slots__slot_wrapper{
        border:2px solid #D6D6D6;
        border-radius:30px;
        padding-top:24px;
        overflow:hidden;
    }
    
    .woocommerce-product-slots__slot_wrapper__name{
        font-family: "Lato";
        font-size: 24px;
        font-weight: normal;
        line-height: 24px;
        font-style: normal;
        text-align: center;
        color: #08224F;
        background:#fff;
        
    }
    .woocommerce-product-slots__slot_wrapper__details{
        display:flex;
        gap:20px;
        background:#04255E;
        padding:24px;
        
    }
    .woocommerce-product-slots__slot_wrapper__details__interval{
        font-family: "Lato";
        font-size: 68px;
        font-weight: 900;
        line-height: 40px;
        font-style: normal;
        text-align: center;
        color: #ffffff;
    }
    .woocommerce-product-slots__slot_wrapper__details__time-and-cost p{
        font-family: "Lato";
        font-size: 22px;
        font-weight: normal;
        line-height: 25px;
        font-style: normal;
        text-align: left;
        color: #ffffff;
        margin:0;
    }
    
    </style>
    <?php 
    if ( $product->is_type( 'booking' ) ) {
        
        $product_id = $product->get_id();
        $pid = $product_id;
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $data = array("id"=>$pid,"year"=>"$year","month"=>"$month","day"=>"$day");
        
        $slots = getslotsbydate($data);
        
        function extractValues($inputString) {
            // Regular expression to match numbers and units
            $pattern = '/(\d+)[\s-]*(\w)/i';
            // Perform the matching
            if (preg_match($pattern, $inputString, $matches)) {
                // Extracted values
                $number = $matches[1];
                $unit = strtoupper($matches[2]);
                // Returning the result
                return array('number' => $number, 'unit' => $unit);
            } else {
                // If no matches found, return null
                return null;
            }
        }
        
        function extractTime($inputString) {
            // Regular expression to match time and AM/PM indicator
            $pattern = '/(\d{1,2}):(\d{2}) (AM|PM)/i';
            // Perform the matching
            if (preg_match($pattern, $inputString, $matches)) {
                // Extracted values
                $hour = $matches[1];
                $minute = $matches[2];
                $am_pm = strtoupper($matches[3]);
                // Returning the result
                return array('hour' => $hour, 'minute' => $minute, 'am_pm' => $am_pm);
            } else {
                // If no matches found, return null
                return null;
            }
        }
        
        // $inputString = "60 minutes";
        // $result = extractValues($inputString);
        
        
        
       /* // Loop through each slot
        // if (is_array($slots)) {
        //     $slot_number = 1; // Initialize slot number
        //     foreach ( $slots as $slot ) {
        //         // Extracting
        //         $start_time = date("Y-m-d H:i A", $slot['start']);
        //         $end_time = date("Y-m-d H:i A", $slot['end']);
        //         $interval = $slot['interval'];
        //         $slot_cost = $slot['slot_cost'];
        //         ?>
                
        <!--//         <a href="?add-to-cart=<?php echo $pid; ?>" class="woocommerce-product-slots__slot">-->
        <!--//             <div class="woocommerce-product-slots__slot_wrapper">-->
        <!--//                 <h1 class="woocommerce-product-slots__slot_wrapper__name">Slot <?php echo $slot_number++ ?></h1>-->
        <!--//                 <div class="woocommerce-product-slots__slot_wrapper__details">-->
        //                     <h1 class="woocommerce-product-slots__slot_wrapper__details__interval"><?php //echo $result['number'].$result['unit']; ?></h1>
        <!--//                     <div class="woocommerce-product-slots__slot_wrapper__details__time-and-cost">-->
        <!--//                         <p><?php echo $start_time ?></p>-->
        <!--//                         <p><?php echo $end_time ?></p>-->
        <!--//                         <p><?php echo $slot_cost ?></p>-->
        <!--//                     </div>-->
        <!--//                 </div>-->
        <!--//             </div>-->
        <!--//         </a>-->
        //         <?php
        //     }
        // }*/
    }
    ?>
</div>
