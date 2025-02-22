<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

if ( is_shop() ) {
    ?>
    <title>Book Sports Courts & Venues Near Me | Badminton, Tennis, Basketball, Cricket</title>
    <meta name="title" content="Book Sports Courts & Venues Near Me | Badminton, Tennis, Basketball, Cricket">
    <meta name="description" content="Easily book courts and venues for various sports with our sports booking platform (Tida Sports). Find and reserve badminton courts, tennis courts, and basketball courts near you for a seamless booking experience.">
    <?php
}

get_header( 'shop' );
// echo do_shortcode("[hfe_template id='5488']");

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );

?>


<style>
img.bunty-icon {
    max-width: 100px;
    width:100%;
}
</style>
<?php
            $sport_terms = get_terms(array(
                'taxonomy' => 'sport',
                'hide_empty' => false,
                'post_per_page' => -1,
            ));
?>
<header class="woocommerce-products-header">
    <div class="woocommerce-products-header__wrapper">
        <div class="conta">
            <?php 
	    /*
            // Retrieve the current term
            $current_term = get_queried_object();
            
            // Check if it's a term and if it has the ACF field 'title_icon' set
            if ($current_term && is_a($current_term, 'WP_Term')) {
                $title_icon = get_field('title_icon', $current_term);
                if ($title_icon) {
                    // If 'title_icon' is set, display it
                    echo '<img class="bunty-icon" src="' . esc_url($title_icon) . '"/>';
                } else {
                    // If 'title_icon' is not set, display a default icon
                    // echo '<img class="bunty-icon" src="https://tidasports.com/wp-content/uploads/2024/04/medal-image.png"/>';
                }
            }
	    */
            ?>
        </div>
        <div class="woocommerce-products-header__content-wrapper">
	   <?php 
        	if ( is_tax() || is_post_type_archive( 'product' ) ) {
            	// Get the current taxonomy term
            	$term = get_queried_object();

            	// Check if the term is an instance of WP_Term
            	if ( $term instanceof WP_Term ) {
                	// Get the custom field value
                	$taxonomy_body_title = get_field('taxonomy_body_title', $term);

                	// Check if the custom field value is not empty
                	if ( ! empty( $taxonomy_body_title ) ) {
                    	// Output the custom field value
                    	echo '<h1 class="woocommerce-products-header__title page-title taxonomy_body_title">' . esc_html( $taxonomy_body_title ) . '</h1>';
                	} else {
				echo '<h1 class="woocommerce-products-header__title page-title">Our Facilities</h1>';

			}
            	} else if ( is_shop() ) {
                	// Shop Page title
                	echo '<h1 class="woocommerce-products-header__title page-title">Tida Sports Academies & Venues Booking Packages</h1>';
            	} else {
                	// Fallback to default title
                	if ( apply_filters( 'woocommerce_show_page_title', true ) ) {
                    	echo '<h1 class="woocommerce-products-header__title page-title">Our Facilities</h1>';
                	}
            	}
        	} else {
            	// Fallback to default title if not on a taxonomy archive or shop page
            	if ( apply_filters( 'woocommerce_show_page_title', true ) ) {
                	echo '<h1 class="woocommerce-products-header__title page-title">Our Facilities</h1>';
            	}
        	}
    	    ?>
            <?php // if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <!-- <h1 class="woocommerce-products-header__title page-title">Our Facilities<?php //woocommerce_page_title(); ?></h1> -->
            <?php // endif; ?>
            <?php
            /**
             * Hook: woocommerce_archive_description.
             *
             * @hooked woocommerce_taxonomy_archive_description - 10
             * @hooked woocommerce_product_archive_description - 10
             */
            // do_action('woocommerce_archive_description');
            ?>
	    <?php 
		$description = '';

		if ( is_tax() || is_post_type_archive( 'product' ) ) {
    			$description = term_description(); // Get the taxonomy description
    			if ( empty( $description ) ) {
        			$description = get_post_meta( wc_get_page_id( 'shop' ), 'woocommerce_shop_page_description', true ); // Get the shop page description
    			}
		} 
		if ( ! empty( $description ) ) {
    			// Description exists, display it
    			echo '<div class="custom-class">';
    			echo wp_kses_post( $description );
    			echo '</div>';
		} else if(is_shop()) {
    			// No description, display fallback paragraph
    			echo '<p class="woocommerce-products-header__description"><p>Unlock a top-class sports experience with Tida Sports! Whether you are booking badminton, tennis, basketball or other <a href="https://tidasports.com/academy/">Sports Academies</a>, our flexible packages are designed to suit your needs. Download the Tida Sports mobile app from the <a href="https://apps.apple.com/in/app/tidasports/id6449814108" target="_blank">App Store</a> and <a href="https://play.google.com/store/apps/details?id=org.netgains.tidasports&pli=1" target="_blank">Play Store</a> to easily book a venue and view exclusive packages today!</p></p>';
		} else {
    			// No description, display fallback paragraph
    			echo '<p class="woocommerce-products-header__description">LOOK FOR YOUR NEAREST DESTINATIONS!</p>';
		}   	    
	    ?>
            <!-- <p class="woocommerce-products-header__description">LOOK FOR YOUR NEAREST DESTINATIONS!</p> -->
        </div>
    </div>
</header>

<?php
    // do_shortcode("tida_search_widget");
   /* if(isset($_POST) && !empty($_POST)){ 
    	$tax_query  = array('relation' => 'OR',);
        if(isset($_POST['sport'])){
            $sport = $_POST['sport'];
            	array_push($tax_query, array(
                    'taxonomy' => 'sport',
                    'field' => 'term_id',
                    'terms' => $cat_val
				));
        }
        if(isset($_POST['location'])){
            $location = $_POST['location'];
        	array_push($tax_query, array(
        	    'taxonomy' => 'product_cat',
               'field' => 'term_id',
			   'terms' => $location
			));
        }
		$args = array(
			'suppress_filters' => true,
			'post_type' => 'product',
			'post_status' => 'publish',
			'posts_per_page' => 10,
			'order'=> 'DESC', 
			'tax_query' => array(
			'relation' => 'OR',
			 $tax_query)
		);
        if(isset($_POST['item_name'])){
            $search_term = $_POST['item_name'];
            $args['s'] = $search_term;
        }
        $query=new WP_Query($args);
        if( $query->have_posts()): 
            while( $query->have_posts()): $query->the_post();
            {
                echo $post->post_title;
                echo $post->post_content;
            }
        endwhile; 
        else:
        endif;
    }else{*/
	if($term->taxonomy == 'product_cat'){
    ?>
    <div class="product-filters-section">
        <div class="product-filters-section-wrapper">
            <form action="<?php echo site_url(); ?>/shop" name="search_widget" id="search_widget" method="">
                <!--<input type="text" id="location" name="location">-->
                <div class="product-filters-filter filter-by-product_cat">
                    <label for="product_cat">Location</label>
                    <select id="product_cat" name="product_cat">
                        <option value="" disabled selected>Select Location</option>
                        <?php 
                            $taxonomy_value = 'product_cat';
    			    $sport_terms_json = do_shortcode("[getting_tida_sports_and_category taxonomy='$taxonomy_value']");
    			    $sport_terms = json_decode($sport_terms_json);
    
			    $parent_terms = array();
			    $child_terms = array();

			    if ($sport_terms) {
			        // Separate parent and child terms
			        foreach ($sport_terms as $term) {
			            if ($term->parent == 0) {
			                $parent_terms[] = $term;
			            } else {
			                $child_terms[] = $term;
			            }
			        }

			        // Display parent terms
			        foreach ($parent_terms as $parent) {
			            //$selected = isset($_GET['product_cat']) && $_GET['product_cat'] == $parent->slug ? 'selected' : '';
			            $selected = '';
				    echo '<option value="' . esc_attr($parent->slug) . '" ' . $selected . '>' . esc_html($parent->name) . '</option>';

			            // Display child terms for this parent
			            foreach ($child_terms as $child) {
			                if ($child->parent == $parent->term_id) {
			                    //$selected = isset($_GET['product_cat']) && $_GET['product_cat'] == $child->slug ? 'selected' : '';
					    $selected = '';
			                    echo '<option value="' . esc_attr($child->slug) . '" ' . $selected . '>— ' . esc_html($child->name) . '</option>';
			                }
			            }
			        }
			    }
                        ?>
                    </select>
                </div>
                <div class="product-filters-filter filter-by-game">
                    <label for="sport">Sport</label>
                    <select id="sport" name="sport">
                        <option value="" disabled selected>Select Sport</option>
                        <?php 
                            $taxonomy_value = 'sport';
                            $sport_terms_json = do_shortcode("[getting_tida_sports_and_category taxonomy='$taxonomy_value']");
                            $sport_terms = json_decode($sport_terms_json);
                            if ($sport_terms) {
                                foreach ($sport_terms as $term) {
                                    $selected = '';
                                    /*if (isset($_GET['sport']) && $_GET['sport'] == $term->slug) {
                                        $selected = 'selected';
                                    }*/
                                    echo '<option value="' . esc_attr($term->slug) . '" ' . $selected . '>' . esc_html($term->name) . '</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
                <div class="product-filters-filter filter-by-price">
                    <label for="sport">Price</label>
                    <select id="orderby" name="orderby">
                        <option value="" disabled selected>Sort</option>
                        <?php 
                        //$selected_price = isset($_GET['orderby']) ? $_GET['orderby'] : '';
			$selected_price = '';
                        ?>
                        <option value="price" <?php selected($selected_price, 'price'); ?>>Low To High</option>
                        <option value="price-desc" <?php selected($selected_price, 'price-desc'); ?>>High To Low</option>
                    </select>
                </div>
                <div class="product-filters-filter filter-by-name">
                    <label for="s">Name</label>
                    <input id="s" name="s" placeholder="Search by name" 
                           value="<?php // echo isset($_GET['s']) ? esc_attr($_GET['s']) : ''; ?>"
                           oninput="this.disabled = !this.value.trim()">
                </div>
                <!--<input type="text" id="sport" name="sport">-->
                <!--<input id="s" name="s" placeholder="Enter text to search">-->
                <input id="btn" type="submit" value="Search" />
                <!--<div class="push-down"></div>-->
            </form>
        </div>
    </div>
    <?php
	}
    if (woocommerce_product_loop()) {
    	/**
    	 * Hook: woocommerce_before_shop_loop.
    	 *
    	 * @hooked woocommerce_output_all_notices - 10
    	 * @hooked woocommerce_result_count - 20
    	 * @hooked woocommerce_catalog_ordering - 30
    	 */
	if($term->taxonomy == 'product_cat'){
    	do_action( 'woocommerce_before_shop_loop' );
	}
    	woocommerce_product_loop_start();
    	if ( wc_get_loop_prop( 'total' ) ) {
    		while ( have_posts() ) {
    			the_post();
    			/**
    			 * Hook: woocommerce_shop_loop.
    			 */
    			do_action( 'woocommerce_shop_loop' );
    			wc_get_template_part( 'content', 'product' );
    		}
    	}
    	woocommerce_product_loop_end();
    	/**
    	 * Hook: woocommerce_after_shop_loop.
    	 *
    	 * @hooked woocommerce_pagination - 10
    	 */
    	do_action( 'woocommerce_after_shop_loop' );
    } else {
    	/**
    	 * Hook: woocommerce_no_products_found.
    	 *
    	 * @hooked wc_no_products_found - 10
    	 */
    	do_action( 'woocommerce_no_products_found' );
    }/*}*/
    /**
     * Hook: woocommerce_after_main_content.
     *
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action( 'woocommerce_after_main_content' );
    /**
     * Hook: woocommerce_sidebar.
     *
     * @hooked woocommerce_get_sidebar - 10
     */
    //do_action( 'woocommerce_sidebar' );
    get_footer( 'shop' );