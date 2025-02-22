<?php
include 'inc/api-classes.php';
include 'inc/api-functions.php';
include 'inc/store-functions.php';
include 'inc/woocommerce.php';
include 'inc/woocommerce-account.php';
if(is_admin()){
	include 'inc/woocommerce-admin.php';
}
/*add_filter('auto_update_plugin', '__return_false');
add_filter('auto_update_theme', '__return_false');*/
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
    wp_register_script('jquery-ui', get_stylesheet_directory_uri() . '/assets/js/jquery-ui.js', array('jquery'),'1.3', true);
    wp_enqueue_script('jquery-ui');
    wp_register_script('custom-script', get_stylesheet_directory_uri() . '/assets/js/custom-script.js');
    wp_localize_script('custom-script', 'params', array(
        'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php'

    ) );
    wp_enqueue_script('custom-script');

   if (is_page(25354)) { // Check if the current page is the Career page
        // Enqueue the JavaScript file
        wp_enqueue_script(
            'career-script',
            get_stylesheet_directory_uri() . '/assets/js/career.js',
            array('jquery'), // Include 'jquery' as a dependency
	    // time(),
            filemtime(get_stylesheet_directory() . '/assets/js/career.js'), // Dynamically generate version based on file modification time
            true
        );

        // Enqueue the CSS file
        wp_enqueue_style(
            'career-style',
            get_stylesheet_directory_uri() . '/assets/css/career.css',
            array(), // No dependencies for CSS
            filemtime(get_stylesheet_directory() . '/assets/css/career.css') // Dynamically generate version based on file modification time
        );
    }
    
    wp_enqueue_script('jquery');
	if(is_product() && is_user_logged_in()){
		$user = wp_get_current_user();
		$roles =  $user->roles;
		if(in_array('partner',$roles)){
			wp_register_script('slots-script', get_stylesheet_directory_uri() . '/assets/js/slots.js');
			wp_localize_script('slots-script', 'params', array(
				'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php'

			));
			wp_enqueue_script('slots-script');
		}
	}
    /* wp_enqueue_script('slick-js', '//code.jquery.com/jquery-1.11.0.min.js', array(), null, true);
    wp_enqueue_script('slick-migrate', '//code.jquery.com/jquery-migrate-1.2.1.min.js', array(), null, true);
    wp_enqueue_script('slick-slider', get_template_directory_uri() . '/slick/slick.min.js', array('jquery'), null, true);
    wp_enqueue_style('slick-css', get_template_directory_uri() . '/slick/slick.css');
    wp_enqueue_style('slick-theme-css', get_template_directory_uri() . '/slick/slick-theme.css'); */
}
add_action('wp_ajax_nopriv_autocomplete_search', 'autocomplete_search');
add_action('wp_ajax_autocomplete_search', 'autocomplete_search');
function autocomplete_search(){
    $q=$_POST["q"];
    $category=$_POST["category"];
    $args = array(
        'name__like' => $q,
    );
    $terms = get_terms($category, $args );
    $json = array();
    foreach($terms as $term){
        $json[]=array(
           'value'=> $term->name,
           'label'=> $term->name
        );
    }
    echo json_encode($json);die;
}
// Admin CSS Start
function custom_admin_css() {
    $custom_css_file = get_stylesheet_directory_uri() . '/assets/css/admin.css';

    // Enqueue the custom CSS file in the WordPress admin area.
    wp_enqueue_style('custom-admin-css', $custom_css_file);
}

// Hook the custom_admin_css function to the admin_enqueue_scripts action.
add_action('admin_enqueue_scripts', 'custom_admin_css');

// Admin CSS End

// Register Custom Post Type
function create_team_post_type() {
    $labels = array(
        'name'                  => _x( 'Teams', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Team', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Teams', 'text_domain' ),
        'name_admin_bar'        => __( 'Team', 'text_domain' ),
        'archives'              => __( 'Team Archives', 'text_domain' ),
        'attributes'            => __( 'Team Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Team:', 'text_domain' ),
        'all_items'             => __( 'All Teams', 'text_domain' ),
        'add_new_item'          => __( 'Add New Team', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Team', 'text_domain' ),
        'edit_item'             => __( 'Edit Team', 'text_domain' ),
        'update_item'           => __( 'Update Team', 'text_domain' ),
        'view_item'             => __( 'View Team', 'text_domain' ),
        'view_items'            => __( 'View Teams', 'text_domain' ),
        'search_items'          => __( 'Search Team', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into Team', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Team', 'text_domain' ),
        'items_list'            => __( 'Teams list', 'text_domain' ),
        'items_list_navigation' => __( 'Teams list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter teams list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Team', 'text_domain' ),
        'description'           => __( 'Post Type Description', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'taxonomies'            => array( 'category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-groups',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );
    register_post_type( 'team', $args );
    $labels = array(
        'name'                  => _x( 'Showcase', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Showcase', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Showcase', 'text_domain' ),
        'name_admin_bar'        => __( 'Showcase', 'text_domain' ),
        'archives'              => __( 'Showcase Archives', 'text_domain' ),
        'attributes'            => __( 'Showcase Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Showcase:', 'text_domain' ),
        'all_items'             => __( 'All Showcase', 'text_domain' ),
        'add_new_item'          => __( 'Add New Showcase', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Showcase', 'text_domain' ),
        'edit_item'             => __( 'Edit Showcase', 'text_domain' ),
        'update_item'           => __( 'Update Showcase', 'text_domain' ),
        'view_item'             => __( 'View Showcase', 'text_domain' ),
        'view_items'            => __( 'View Showcase', 'text_domain' ),
        'search_items'          => __( 'Search Showcase', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into Showcase', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this Showcase', 'text_domain' ),
        'items_list'            => __( 'Showcase list', 'text_domain' ),
        'items_list_navigation' => __( 'Showcase list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter teams list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Showcase', 'text_domain' ),
        'description'           => __( 'Post Type Description', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'taxonomies'            => array( 'category', 'post_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-groups',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'show_in_rest'=> true,
        'rest_base' => 'showcase',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    );
    register_post_type( 'showcase', $args );
}
add_action( 'init', 'create_team_post_type', 0 );
function custom_head(){
echo '<meta name="google-site-verification" content="HdYW23-wR3MlJmj1eOFSa6Ja5umHdhQg4b-T0ZasOVs" />';
echo "<!-- Google tag (gtag.js) -->
<script async src='https://www.googletagmanager.com/gtag/js?id=G-DR61DHPH0D'></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-DR61DHPH0D');
</script>";

}
add_action('wp_head','custom_head');
//add_action('init','delay_remove');
function delay_remove() {
    remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10 );
}




/* function custom_store_rewrite_rule() {
    // Add a custom rewrite rule for /store/{sport_value}
    add_rewrite_rule(
        '^store/([a-zA-Z0-9_-]+)/?$',
        'index.php?store_sport=$matches[1]',
        'top'
    );
}
add_action('init', 'custom_store_rewrite_rule');

function custom_store_query_vars($vars) {
    // Add a query variable to capture the {sport_value}
    $vars[] = 'store_sport';
    return $vars;
}
add_filter('query_vars', 'custom_store_query_vars');

function custom_store_template_redirect() {
    // Check if the query variable 'store_sport' is set
    $store_sport = get_query_var('store_sport');
    if ($store_sport) {
        // Get the content of the /store page (parent page)
        $store_page = get_page_by_path('store');
        if ($store_page) {
            // Set up global $post to load the content of the parent /store page
            global $post;
            $post = $store_page;
            setup_postdata($post);
            
            // Output the header, content, and footer of the store page
            get_header(); // Load the header
            ?>
            <div class="store-page-content">
                <h1><?php echo get_the_title($post); ?></h1>
                <div><?php echo apply_filters('the_content', get_the_content($post)); ?></div>
                <div>Displaying information for: <strong><?php echo esc_html($store_sport); ?></strong></div>
            </div>
            <?php
            get_footer(); // Load the footer
            wp_reset_postdata();
            exit;
        }
    }
}
add_action('template_redirect', 'custom_store_template_redirect'); */




add_action( 'init', 'tida_vendor_Item' );
function tida_vendor_Item() {
	$labels = array(
		'name'                       => 'Sport', // Replace it with your taxonomy name
		'singular_name'              => 'Sport', // Replace it with your taxonomy name
		'menu_name'                  => 'Sports', // Replace it with your taxonomy name
		'all_items'                  => 'All Sports', // Replace it with your taxonomy name 
		'parent_item'                => 'Parent Sport', // Replace it with your taxonomy name
		'parent_item_colon'          => 'Parent Sport:', // Replace it with your taxonomy name
		'new_item_name'              => 'New Sport Name', // Replace it with your taxonomy name
		'add_new_item'               => 'Add New Sport', // Replace it with your taxonomy name
		'edit_item'                  => 'Edit Sport', // Replace it with your taxonomy name
		'update_item'                => 'Update Sport', // Replace it with your desired text
		'separate_items_with_commas' => 'Separate Sport with commas', // Replace it with your desired text
		'search_items'               => 'Search Sports', // Replace it with your desired text
		'add_or_remove_items'        => 'Add or remove Sports', // Replace it with your desired text
		'choose_from_most_used'      => 'Choose from the most used Sports', // Replace it with your desired text
	);
	$capabilities = array(
        'manage_terms'               => 'manage_woocommerce',
        'edit_terms'                 => 'manage_woocommerce',
        'delete_terms'               => 'manage_woocommerce',
        'assign_terms'               => 'manage_woocommerce',
    ); 
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'capabilities'              => $capabilities,
	);
	register_taxonomy( 'sport', array( 'product' ), $args );
    register_taxonomy_for_object_type( 'sport', 'product' );
	$labels = array(
		'name'                       => 'Store', // Replace it with your taxonomy name
		'singular_name'              => 'Store', // Replace it with your taxonomy name
		'menu_name'                  => 'Store', // Replace it with your taxonomy name
		'all_items'                  => 'All Store', // Replace it with your taxonomy name 
		'parent_item'                => 'Parent Store', // Replace it with your taxonomy name
		'parent_item_colon'          => 'Parent Store:', // Replace it with your taxonomy name
		'new_item_name'              => 'New Store Name', // Replace it with your taxonomy name
		'add_new_item'               => 'Add New Store', // Replace it with your taxonomy name
		'edit_item'                  => 'Edit Store', // Replace it with your taxonomy name
		'update_item'                => 'Update Store', // Replace it with your desired text
		'separate_items_with_commas' => 'Separate Store with commas', // Replace it with your desired text
		'search_items'               => 'Search Sports', // Replace it with your desired text
		'add_or_remove_items'        => 'Add or remove Sports', // Replace it with your desired text
		'choose_from_most_used'      => 'Choose from the most used Sports', // Replace it with your desired text
	);
	$capabilities = array(
        'manage_terms'               => 'manage_woocommerce',
        'edit_terms'                 => 'manage_woocommerce',
        'delete_terms'               => 'manage_woocommerce',
        'assign_terms'               => 'manage_woocommerce',
    ); 
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'capabilities'              => $capabilities,
	);
	register_taxonomy( 'store', array( 'product' ), $args );
    register_taxonomy_for_object_type( 'store', 'product' );
	$labels = array(
		'name'                       => 'Academy', // Replace it with your taxonomy name
		'singular_name'              => 'Academy', // Replace it with your taxonomy name
		'menu_name'                  => 'Academy', // Replace it with your taxonomy name
		'all_items'                  => 'All Academy', // Replace it with your taxonomy name 
		'parent_item'                => 'Parent Academy', // Replace it with your taxonomy name
		'parent_item_colon'          => 'Parent Academy:', // Replace it with your taxonomy name
		'new_item_name'              => 'New Academy Name', // Replace it with your taxonomy name
		'add_new_item'               => 'Add New Academy', // Replace it with your taxonomy name
		'edit_item'                  => 'Edit Academy', // Replace it with your taxonomy name
		'update_item'                => 'Update Academy', // Replace it with your desired text
		'separate_items_with_commas' => 'Separate Academy with commas', // Replace it with your desired text
		'search_items'               => 'Search Academy', // Replace it with your desired text
		'add_or_remove_items'        => 'Add or remove Academy', // Replace it with your desired text
		'choose_from_most_used'      => 'Choose from the most used Academy', // Replace it with your desired text
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
//	register_taxonomy( 'academy', 'product', $args );
}


add_action( 'rest_api_init', 'register_rest_field_for_custom_taxonomy_sport' );
function register_rest_field_for_custom_taxonomy_sport() {
    register_rest_field('product', "sport",
        array("get_callback" => 'product_get_callback_sport',
        'update_callback'    => 'product_update_callback_sport',
        'schema' => null,
        )
    );
}
        //Get Taxonomy record in wc REST API
         function product_get_callback_sport($post, $attr, $request, $object_type)
        {
            $terms = array();

            // Get terms
            foreach (wp_get_post_terms( $post[ 'id' ],'sport') as $term) {
                $terms[] = array(
                    'id'        => $term->term_id,
                    'name'      => $term->name,
                    'slug'      => $term->slug,
                    'sport'  => get_term_meta($term->term_id, 'sport', true)
                );
            }

            return $terms;
        }
        
         //Update Taxonomy record in wc REST API
         function product_update_callback_sport($values, $post, $attr, $request, $object_type)
        {   
            $postId = $post->id;
            $terms = $values;
            $termName = $terms[0]['name'];
            $termSlug = $terms[0]['slug'];
            wp_insert_term( $termName, 'sport', array( 'slug' => $termSlug ) );
             error_log("debug on values");
             error_log(json_encode($values));
             $newTermId = get_term_by('slug',$termSlug,'sport')->term_id;
             array_push($values, array('id' => (int)$newTermId));
             $numarray = [];             
             foreach($values as $value){         
                 if(is_numeric($value['id'])){             
                     $numarray[] = (int)$value['id'];          
                 }       
            }
           wp_set_object_terms( $postId, $numarray , 'sport');
        }

add_filter('request', function( $vars ) {
    global $wpdb;
    if( ! empty( $vars['pagename'] ) || ! empty( $vars['category_name'] ) || ! empty( $vars['name'] ) || ! empty( $vars['attachment'] ) ) {
        $slug = ! empty( $vars['pagename'] ) ? $vars['pagename'] : ( ! empty( $vars['name'] ) ? $vars['name'] : ( !empty( $vars['category_name'] ) ? $vars['category_name'] : $vars['attachment'] ) );
        $exists = $wpdb->get_var( $wpdb->prepare( "SELECT t.term_id FROM $wpdb->terms t LEFT JOIN $wpdb->term_taxonomy tt ON tt.term_id = t.term_id WHERE tt.taxonomy = 'product_cat' AND t.slug = %s" ,array( $slug )));
        if( $exists ){
            $old_vars = $vars;
            $vars = array('product_cat' => $slug );
            if ( !empty( $old_vars['paged'] ) || !empty( $old_vars['page'] ) )
                $vars['paged'] = ! empty( $old_vars['paged'] ) ? $old_vars['paged'] : $old_vars['page'];
            if ( !empty( $old_vars['orderby'] ) )
                    $vars['orderby'] = $old_vars['orderby'];
                if ( !empty( $old_vars['order'] ) )
                    $vars['order'] = $old_vars['order'];    
        }
    }
    return $vars;
});
function add_custom_user_role() {
    add_role(
        'partner',
        'Partner Manager'
    );
}
add_action('init', 'add_custom_user_role');

add_filter('woocommerce_checkout_fields', 'readdonly_country_select_field');
function readdonly_country_select_field( $fields ) {
    unset( $fields['billing']['billing_company'] );
    //unset( $fields['billing']['billing_email'] );
    //unset( $fields['billing']['billing_phone'] );
    unset( $fields['billing']['billing_state'] );
    //unset( $fields['billing']['billing_first_name'] );
    //unset( $fields['billing']['billing_last_name'] );
    unset( $fields['billing']['billing_address_1'] );
    unset( $fields['billing']['billing_address_2'] );
    unset( $fields['billing']['billing_city'] );
    unset( $fields['billing']['billing_postcode'] );
    return $fields;
}

function query_neighbors_count($latitude, $longitude, $post_type = 'product', $distance = 100, $term='',$taxonomy='',$partner='',$type='',$search='' ) {
  global $wpdb;
	$m = 0.621371;
	if($distance){
		$distance = $distance * $m;
	}
  if($post_type == 'showcase' || $type=='showcase'){
		$sql =  "select DISTINCT id from  (SELECT DISTINCT p.ID ";
	}else{
		$sql =  "SELECT DISTINCT p.ID ";
	}
	if($latitude && $longitude){
	$sql .= " , ((ACOS(SIN($latitude * PI() / 180) * SIN(map_lat.meta_value * PI() / 180) + COS($latitude * PI() / 
	180) * COS(map_lat.meta_value * PI() / 180) * COS(($longitude - map_lng.meta_value) * PI() / 180)) * 
	180 / PI()) * 60 * 1.1515) AS distance ";
	}
	$sql .= " FROM $wpdb->posts p";
	if($latitude && $longitude){
	$sql .= " INNER JOIN $wpdb->postmeta map_lat ON p.ID = map_lat.post_id
	INNER JOIN $wpdb->postmeta map_lng ON p.ID = map_lng.post_id";
	}
	if($partner){
	$sql .= " INNER JOIN $wpdb->postmeta partner ON p.ID = partner.post_id";
	}
	if($post_type == 'showcase' || $type=='showcase'){
	$sql .= " INNER JOIN $wpdb->postmeta showcase ON p.ID = showcase.post_id";
	}
	if($post_type == 'showcase' || $type){
	$sql .= " INNER JOIN $wpdb->postmeta type ON p.ID = type.post_id";
	}
	if($search){
		$sql .= " INNER JOIN $wpdb->postmeta address ON p.ID = address.post_id ";
	}
	$sql .= " WHERE ";
    if($taxonomy && $term){
        $sql .= " ID IN (
            SELECT object_id 
            FROM $wpdb->term_relationships AS TR 
                INNER JOIN $wpdb->term_taxonomy AS TT ON TR.term_taxonomy_id = TT.term_taxonomy_id
                INNER JOIN $wpdb->terms AS T ON TT.term_id = T.term_id
            WHERE TT.taxonomy = '$taxonomy' AND T.term_id = $term
        ) AND ";
    } 
    if($search && ($post_type == 'showcase' || $type=='showcase')){ 
        $post_type = 'showcase';
        $sql .= "  p.post_type = '$post_type' AND p.post_status = 'publish' ";
    }else if($search && ($post_type == '' || $type=='')){ 
        $sql .= "  (p.post_type = 'product' || p.post_type = 'showcase') AND p.post_status = 'publish' ";
    }else{ 
        $sql .= "  p.post_type = '$post_type' AND p.post_status = 'publish' ";
    }
    if($partner){
        $sql .= "  AND partner.meta_key = 'partner_manager' AND partner.meta_value = '$partner' ";
    }
    
    
    
    if($type && $type != 'showcase'){
            $sql .=   " AND (type.meta_key = 'product_type'
            AND type.meta_value = '$type') ";
    }
    if($post_type == 'showcase'|| $type=='showcase'){
        $sql .= "  AND showcase.meta_key = 'approved' AND showcase.meta_value = '1' ";
    }
    /* if($search){
            $sql .=   " AND ((p.post_title LIKE '%$search%') OR (p.post_title LIKE '%$search%')) ";
    } */
	if($search){
			$sql .= " AND ((p.post_title LIKE '%$search%') OR (p.post_title LIKE '%$search%') OR (address.meta_key = 'address' AND address.meta_value  LIKE '%$search%' )) ";
	}
    if($latitude && $longitude){
            $sql .= "  AND ((map_lat.meta_key = 'latitude'
            AND map_lng.meta_key = 'longitude' )) 
            HAVING distance < $distance ";
			if($post_type == 'showcase' || $type=='showcase'){}else{
			 $sql .= " ORDER BY distance ASC ";
			}
    }else{
		if($post_type == 'showcase' || $type=='showcase'){}else{
        $sql .= " ORDER BY id DESC ";
		}
    }
	if($post_type == 'showcase' || $type=='showcase'){
		$sql .= " 	union all
		SELECT DISTINCT p1.ID ";		
		if($latitude && $longitude){
		$sql .= " , ((ACOS(SIN($latitude * PI() / 180) * SIN(map_lat.meta_value * PI() / 180) + COS($latitude * PI() / 
		180) * COS(map_lat.meta_value * PI() / 180) * COS(($longitude - map_lng.meta_value) * PI() / 180)) * 
		180 / PI()) * 60 * 1.1515) AS distance";
		}
		$sql .= " FROM $wpdb->posts p1 INNER JOIN $wpdb->postmeta pan_india ON p1.ID = pan_india.post_id";
		if($latitude && $longitude){
		$sql .= " INNER JOIN $wpdb->postmeta map_lat ON p1.ID = map_lat.post_id
		INNER JOIN $wpdb->postmeta map_lng ON p1.ID = map_lng.post_id";
		}
		if($partner){
		$sql .= " INNER JOIN $wpdb->postmeta partner ON p1.ID = partner.post_id";
	}
	if($post_type == 'showcase' || $type=='showcase'){
		$sql .= " INNER JOIN $wpdb->postmeta showcase ON p1.ID = showcase.post_id";
	}
	if($type){
		$sql .= " INNER JOIN $wpdb->postmeta type ON p1.ID = type.post_id";
	}
	if($search){
		$sql .= " INNER JOIN $wpdb->postmeta address ON p.ID = address.post_id ";
	}
		/* if($search){
			$sql .=   " AND ((p1.post_title LIKE '%$search%') OR (p1.post_title LIKE '%$search%')) ";
		} */
	if($search){
			$sql .= " AND ((p.post_title LIKE '%$search%') OR (p.post_title LIKE '%$search%') OR (address.meta_key = 'address' AND address.meta_value  LIKE '%$search%' )) ";
	}
		$sql .= " AND (pan_india.meta_key = 'pan_india' AND pan_india.meta_value = '1')";
		
		
$sql .= " WHERE ";
	if($taxonomy && $term){
		$sql .= " ID IN (
			SELECT object_id 
			FROM $wpdb->term_relationships AS TR 
				INNER JOIN $wpdb->term_taxonomy AS TT ON TR.term_taxonomy_id = TT.term_taxonomy_id
				INNER JOIN $wpdb->terms AS T ON TT.term_id = T.term_id
			WHERE TT.taxonomy = '$taxonomy' AND T.term_id = $term
		) AND ";
	} 
	if($search && ($post_type == 'showcase' || $type=='showcase')){ 
		$post_type = 'showcase';
		$sql .= "  p1.post_type = '$post_type' AND p1.post_status = 'publish' ";
	}else if($search && $type==''){ 
		$sql .= "  (p1.post_type = 'product' || p1.post_type = 'showcase') AND p1.post_status = 'publish' ";
	}else{ 
		$sql .= "  p1.post_type = '$post_type' AND p1.post_status = 'publish' ";
	}
	if($partner){
		$sql .= "  AND partner.meta_key = 'partner_manager' AND partner.meta_value = '$partner' ";
	}            
	if($type && $type != 'showcase'){
			$sql .=   " AND (type.meta_key = 'product_type'
			AND type.meta_value = '$type') ";
	}
	if($post_type == 'showcase'|| $type=='showcase'){
		$sql .= "  AND showcase.meta_key = 'approved' AND showcase.meta_value = '1' ";
	}
	/* if($search){
			$sql .=   " AND ((p1.post_title LIKE '%$search%') OR (p1.post_title LIKE '%$search%')) ";
	} */
	if($search){
			$sql .= " AND ((p.post_title LIKE '%$search%') OR (p.post_title LIKE '%$search%') OR (address.meta_key = 'address' AND address.meta_value  LIKE '%$search%' )) ";
	}
	/* if($latitude && $longitude){
			$sql .= "  AND ((map_lat.meta_key = 'latitude'
			AND map_lng.meta_key = 'longitude' )) 
			HAVING distance < $distance  ";
		if($post_type == 'showcase' || $type=='showcase'){}else{
			if($search){
				$sql .= " ORDER BY post_title ASC ";
			}else{
				$sql .= "   ORDER BY distance ASC ";
			}
		}
	}else{
		if($post_type == 'showcase' || $type=='showcase'){}else{
			if($search){
				$sql .= " ORDER BY post_title ASC ";
			}else{
				$sql .= "  ORDER BY id DESC ";
			}
		}
	} */
	
		$sql .=") $wpdb->posts ";
	}
    /*$query = $wpdb->prepare($sql);
    $num_rows = mysqli_num_rows($query);*/
    $wpdb->get_results( $sql );  
    return $wpdb->num_rows;
}
function query_neighbors( $latitude, $longitude, $post_type = 'product', $distance = 100, $limit = 10,$OFFSET=0,$term='',$taxonomy='',$partner='',$type='',$search='' ) {
    global $wpdb;
	$m = 0.621371;
	if($distance){
	$distance = $distance * $m;
	}
	if($post_type == 'showcase' || $type=='showcase'){
		$sql =  "select DISTINCT ID from  (SELECT DISTINCT p.ID ";
	}else{
		$sql =  "SELECT DISTINCT p.ID ";
	}
	if($latitude && $longitude){
		$sql .= " , ((ACOS(SIN($latitude * PI() / 180) * SIN(map_lat.meta_value * PI() / 180) + COS($latitude * PI() / 
		180) * COS(map_lat.meta_value * PI() / 180) * COS(($longitude - map_lng.meta_value) * PI() / 180)) * 
		180 / PI()) * 60 * 1.1515) AS distance ";
	}
	$sql .= " FROM $wpdb->posts p";
	if($latitude && $longitude){
		$sql .= " INNER JOIN $wpdb->postmeta map_lat ON p.ID = map_lat.post_id
		INNER JOIN $wpdb->postmeta map_lng ON p.ID = map_lng.post_id";
	}
	if($partner){
		$sql .= " INNER JOIN $wpdb->postmeta partner ON p.ID = partner.post_id";
	}
	if($post_type == 'showcase' || $type=='showcase'){
		$sql .= " INNER JOIN $wpdb->postmeta showcase ON p.ID = showcase.post_id";
	}
	if($type){
		$sql .= " INNER JOIN $wpdb->postmeta type ON p.ID = type.post_id";
	}
	if($search){
		$sql .= " INNER JOIN $wpdb->postmeta address ON p.ID = address.post_id ";
	}
	
		$sql .= " INNER JOIN $wpdb->postmeta pan_india ON p.ID = pan_india.post_id";
	$sql .= " WHERE ";
	if($taxonomy && $term){
		$sql .= " ID IN (
			SELECT object_id 
			FROM $wpdb->term_relationships AS TR 
				INNER JOIN $wpdb->term_taxonomy AS TT ON TR.term_taxonomy_id = TT.term_taxonomy_id
				INNER JOIN $wpdb->terms AS T ON TT.term_id = T.term_id
			WHERE TT.taxonomy = '$taxonomy' AND T.term_id = $term
		) AND ";
	} 
	if($search && ($post_type == 'showcase' || $type=='showcase')){ 
		$post_type = 'showcase';
		$sql .= "  p.post_type = '$post_type' AND p.post_status = 'publish' ";
	}else if($search && $type==''){ 
		$sql .= "  (p.post_type = 'product' || p.post_type = 'showcase') AND p.post_status = 'publish' ";
	}else{ 
		$sql .= "  p.post_type = '$post_type' AND p.post_status = 'publish' ";
	}
	if($partner){
		$sql .= "  AND partner.meta_key = 'partner_manager' AND partner.meta_value = '$partner' ";
	}            
	if($type && $type != 'showcase'){
			$sql .=   " AND (type.meta_key = 'product_type'
			AND type.meta_value = '$type') ";
	}
	if($post_type == 'showcase'|| $type=='showcase'){
		$sql .= "  AND showcase.meta_key = 'approved' AND showcase.meta_value = '1' ";
	}
	/* if($search){
			$sql .=   " AND ((p.post_title LIKE '%$search%') OR (p.post_title LIKE '%$search%')) ";
	} */
	if($search){
			$sql .= " AND ((p.post_title LIKE '%$search%') OR (p.post_title LIKE '%$search%') OR (address.meta_key = 'address' AND address.meta_value  LIKE '%$search%' )) ";
	}
	if($latitude && $longitude){
		$sql .= "  AND ((map_lat.meta_key = 'latitude'
		AND map_lng.meta_key = 'longitude' )) 
		 ";
		if($post_type == 'showcase' || $type=='showcase'){}else{
				$sql .= " HAVING distance < $distance ORDER BY distance ASC ";
		}
		/* if($post_type == 'showcase' || $type=='showcase'){}else{
			if($search){
				$sql .= " ORDER BY post_title ASC ";
			}else{
				$sql .= "   ORDER BY distance ASC ";
			}
		} */
	}else{
		/* if($post_type == 'showcase' || $type=='showcase'){}else{
			if($search){
				$sql .= " ORDER BY post_title ASC ";
			}else{
				$sql .= "  ORDER BY id DESC ";
			}
		} */
	}
	if($post_type == 'showcase' || $type=='showcase'){
		$sql .= " 	union all
		SELECT DISTINCT p1.ID ";		
		if($latitude && $longitude){
		$sql .= " , ((ACOS(SIN($latitude * PI() / 180) * SIN(map_lat.meta_value * PI() / 180) + COS($latitude * PI() / 
		180) * COS(map_lat.meta_value * PI() / 180) * COS(($longitude - map_lng.meta_value) * PI() / 180)) * 
		180 / PI()) * 60 * 1.1515) AS distance";
		}
		$sql .= " FROM $wpdb->posts p1 INNER JOIN $wpdb->postmeta pan_india ON p1.ID = pan_india.post_id";
		if($latitude && $longitude){
		$sql .= " INNER JOIN $wpdb->postmeta map_lat ON p1.ID = map_lat.post_id
		INNER JOIN $wpdb->postmeta map_lng ON p1.ID = map_lng.post_id";
		}
		if($partner){
		$sql .= " INNER JOIN $wpdb->postmeta partner ON p1.ID = partner.post_id";
	}
	if($post_type == 'showcase' || $type=='showcase'){
		$sql .= " INNER JOIN $wpdb->postmeta showcase ON p1.ID = showcase.post_id";
	}
	if($type){
		$sql .= " INNER JOIN $wpdb->postmeta type ON p1.ID = type.post_id";
	}
	if($search){
		$sql .= " INNER JOIN $wpdb->postmeta address ON p.ID = address.post_id ";
	}
		/* if($search){
			$sql .=   " AND ((p1.post_title LIKE '%$search%') OR (p1.post_title LIKE '%$search%')) ";
		} */
	if($search){
			$sql .= " AND ((p.post_title LIKE '%$search%') OR (p.post_title LIKE '%$search%') OR (address.meta_key = 'address' AND address.meta_value  LIKE '%$search%' )) ";
	}
		$sql .= " AND (pan_india.meta_key = 'pan_india' AND pan_india.meta_value = '1')";
		
		
$sql .= " WHERE ";
	if($taxonomy && $term){
		$sql .= " ID IN (
			SELECT object_id 
			FROM $wpdb->term_relationships AS TR 
				INNER JOIN $wpdb->term_taxonomy AS TT ON TR.term_taxonomy_id = TT.term_taxonomy_id
				INNER JOIN $wpdb->terms AS T ON TT.term_id = T.term_id
			WHERE TT.taxonomy = '$taxonomy' AND T.term_id = $term
		) AND ";
	} 
	if($search && ($post_type == 'showcase' || $type=='showcase')){ 
		$post_type = 'showcase';
		$sql .= "  p1.post_type = '$post_type' AND p1.post_status = 'publish' ";
	}else if($search && $type==''){ 
		$sql .= "  (p1.post_type = 'product' || p1.post_type = 'showcase') AND p1.post_status = 'publish' ";
	}else{ 
		$sql .= "  p1.post_type = '$post_type' AND p1.post_status = 'publish' ";
	}
	if($partner){
		$sql .= "  AND partner.meta_key = 'partner_manager' AND partner.meta_value = '$partner' ";
	}            
	if($type && $type != 'showcase'){
			$sql .=   " AND (type.meta_key = 'product_type'
			AND type.meta_value = '$type') ";
	}
	if($post_type == 'showcase'|| $type=='showcase'){
		$sql .= "  AND showcase.meta_key = 'approved' AND showcase.meta_value = '1' ";
	}
	/* if($search){
			$sql .=   " AND ((p1.post_title LIKE '%$search%') OR (p1.post_title LIKE '%$search%')) ";
	} */
	if($search){
			$sql .= " AND ((p.post_title LIKE '%$search%') OR (p.post_title LIKE '%$search%') OR (address.meta_key = 'address' AND address.meta_value  LIKE '%$search%' )) ";
	}
	if($latitude && $longitude){
		$sql .= "  AND ((map_lat.meta_key = 'latitude'
		AND map_lng.meta_key = 'longitude' )) 
		HAVING distance < $distance  ORDER BY distance ASC ";
		/* if($post_type == 'showcase' || $type=='showcase'){}else{
			if($search){
				$sql .= " ORDER BY post_title ASC ";
			}else{
				$sql .= " ORDER BY distance ASC ";
			}
		} */
	} else{
		if($post_type == 'showcase' || $type=='showcase'){}else{
			if($search){
				$sql .= " ORDER BY post_title ASC ";
			}else{
				$sql .= "  ORDER BY id DESC ";
			}
		}
	} 
	
		$sql .=") $wpdb->posts ";
		
		$sql .= " LIMIT $limit OFFSET $OFFSET";
	}else{
		$sql .= " LIMIT $limit OFFSET $OFFSET";
	} 
	$neighbors = $wpdb->get_results( $sql );
	foreach($neighbors as $neighbor){
		if(isset($neighbor->distance)){		
				$neighbor->distance = $neighbor->distance / $m;
		}
	}
	return $neighbors;
}

function query_neighbors_test_query( $latitude, $longitude, $post_type = 'product', $distance = 100, $limit = 10,$OFFSET=0,$term='',$taxonomy='',$partner='',$type='',$search='' ) {
    global $wpdb;
	$m = 0.621371;	
	if($distance){
	$distance = $distance * $m;
	}
	if($post_type == 'showcase' || $type=='showcase'){
		$sql =  "select DISTINCT ID from  (SELECT DISTINCT p.ID ";
	}else{
		$sql =  "SELECT DISTINCT p.ID ";
	}
	if($latitude && $longitude){
		$sql .= " , ((ACOS(SIN($latitude * PI() / 180) * SIN(map_lat.meta_value * PI() / 180) + COS($latitude * PI() / 
		180) * COS(map_lat.meta_value * PI() / 180) * COS(($longitude - map_lng.meta_value) * PI() / 180)) * 
		180 / PI()) * 60 * 1.1515) AS distance ";
	}
	$sql .= " FROM $wpdb->posts p";
	if($latitude && $longitude){
		$sql .= " INNER JOIN $wpdb->postmeta map_lat ON p.ID = map_lat.post_id
		INNER JOIN $wpdb->postmeta map_lng ON p.ID = map_lng.post_id";
	}
	if($partner){
		$sql .= " INNER JOIN $wpdb->postmeta partner ON p.ID = partner.post_id";
	}
	if($post_type == 'showcase' || $type=='showcase'){
		$sql .= " INNER JOIN $wpdb->postmeta showcase ON p.ID = showcase.post_id";
	}
	if($type){
		$sql .= " INNER JOIN $wpdb->postmeta type ON p.ID = type.post_id";
	}
	if($search){
		$sql .= " INNER JOIN $wpdb->postmeta address ON p.ID = address.post_id";
	}
	
		$sql .= " INNER JOIN $wpdb->postmeta pan_india ON p.ID = pan_india.post_id";
	$sql .= " WHERE ";
	if($taxonomy && $term){
		$sql .= " ID IN (
			SELECT object_id 
			FROM $wpdb->term_relationships AS TR 
				INNER JOIN $wpdb->term_taxonomy AS TT ON TR.term_taxonomy_id = TT.term_taxonomy_id
				INNER JOIN $wpdb->terms AS T ON TT.term_id = T.term_id
			WHERE TT.taxonomy = '$taxonomy' AND T.term_id = $term
		) AND ";
	} 
	if($search && ($post_type == 'showcase' || $type=='showcase')){ 
		$post_type = 'showcase';
		$sql .= "  p.post_type = '$post_type' AND p.post_status = 'publish' ";
	}else if($search && $type==''){ 
		$sql .= "  (p.post_type = 'product' || p.post_type = 'showcase') AND p.post_status = 'publish' ";
	}else{ 
		$sql .= "  p.post_type = '$post_type' AND p.post_status = 'publish' ";
	}
	if($partner){
		$sql .= "  AND partner.meta_key = 'partner_manager' AND partner.meta_value = '$partner' ";
	}            
	if($type && $type != 'showcase'){
			$sql .=   " AND (type.meta_key = 'product_type'
			AND type.meta_value = '$type') ";
	}
	if($post_type == 'showcase'|| $type=='showcase'){
		$sql .= "  AND showcase.meta_key = 'approved' AND showcase.meta_value = '1' ";
	}
	if($search){
			$sql .= " AND ((p.post_title LIKE '%$search%') OR (p.post_title LIKE '%$search%') OR (address.meta_key = 'address' AND address.meta_value  LIKE '%$search%' )) ";
	}
	if($latitude && $longitude){
		$sql .= "  AND ((map_lat.meta_key = 'latitude'
		AND map_lng.meta_key = 'longitude' )) 
		 ";
		if($post_type == 'showcase' || $type=='showcase'){}else{
				$sql .= " HAVING distance < $distance ORDER BY distance ASC ";
		}
		/* if($post_type == 'showcase' || $type=='showcase'){}else{
			if($search){
				$sql .= " ORDER BY post_title ASC ";
			}else{
				$sql .= "   ORDER BY distance ASC ";
			}
		} */
	}else{
		/* if($post_type == 'showcase' || $type=='showcase'){}else{
			if($search){
				$sql .= " ORDER BY post_title ASC ";
			}else{
				$sql .= "  ORDER BY id DESC ";
			}
		} */
	}
	if($post_type == 'showcase' || $type=='showcase'){
		$sql .= " 	union all
		SELECT DISTINCT p1.ID ";		
		if($latitude && $longitude){
		$sql .= " , ((ACOS(SIN($latitude * PI() / 180) * SIN(map_lat.meta_value * PI() / 180) + COS($latitude * PI() / 
		180) * COS(map_lat.meta_value * PI() / 180) * COS(($longitude - map_lng.meta_value) * PI() / 180)) * 
		180 / PI()) * 60 * 1.1515) AS distance";
		}
		$sql .= " FROM $wpdb->posts p1 INNER JOIN $wpdb->postmeta pan_india ON p1.ID = pan_india.post_id";
		if($latitude && $longitude){
		$sql .= " INNER JOIN $wpdb->postmeta map_lat ON p1.ID = map_lat.post_id
		INNER JOIN $wpdb->postmeta map_lng ON p1.ID = map_lng.post_id";
		}
		if($partner){
		$sql .= " INNER JOIN $wpdb->postmeta partner ON p1.ID = partner.post_id";
	}
	if($post_type == 'showcase' || $type=='showcase'){
		$sql .= " INNER JOIN $wpdb->postmeta showcase ON p1.ID = showcase.post_id";
	}
	if($type){
		$sql .= " INNER JOIN $wpdb->postmeta type ON p1.ID = type.post_id";
	}
		if($search){
			$sql .= " INNER JOIN $wpdb->postmeta address ON p1.ID = address.post_id";
			$sql .=   " AND ((p1.post_title LIKE '%$search%') OR (p1.post_title LIKE '%$search%')) ";
		}
		$sql .= " AND (pan_india.meta_key = 'pan_india' AND pan_india.meta_value = '1')";
		
		
$sql .= " WHERE ";
	if($taxonomy && $term){
		$sql .= " ID IN (
			SELECT object_id 
			FROM $wpdb->term_relationships AS TR 
				INNER JOIN $wpdb->term_taxonomy AS TT ON TR.term_taxonomy_id = TT.term_taxonomy_id
				INNER JOIN $wpdb->terms AS T ON TT.term_id = T.term_id
			WHERE TT.taxonomy = '$taxonomy' AND T.term_id = $term
		) AND ";
	} 
	if($search && ($post_type == 'showcase' || $type=='showcase')){ 
		$post_type = 'showcase';
		$sql .= "  p1.post_type = '$post_type' AND p1.post_status = 'publish' ";
	}else if($search && $type==''){ 
		$sql .= "  (p1.post_type = 'product' || p1.post_type = 'showcase') AND p1.post_status = 'publish' ";
	}else{ 
		$sql .= "  p1.post_type = '$post_type' AND p1.post_status = 'publish' ";
	}
	if($partner){
		$sql .= "  AND partner.meta_key = 'partner_manager' AND partner.meta_value = '$partner' ";
	}            
	if($type && $type != 'showcase'){
			$sql .=   " AND (type.meta_key = 'product_type'
			AND type.meta_value = '$type') ";
	}
	if($post_type == 'showcase'|| $type=='showcase'){
		$sql .= "  AND showcase.meta_key = 'approved' AND showcase.meta_value = '1' ";
	}
	if($search){
			$sql .=   " AND ((p1.post_title LIKE '%$search%') OR (p1.post_title LIKE '%$search%') OR (address.meta_key = 'address' AND address.meta_value LIKE '%$search%')) ";
	}
	if($latitude && $longitude){
		$sql .= "  AND ((map_lat.meta_key = 'latitude'
		AND map_lng.meta_key = 'longitude' )) 
		HAVING distance < $distance  ORDER BY distance ASC ";
		/* if($post_type == 'showcase' || $type=='showcase'){}else{
			if($search){
				$sql .= " ORDER BY post_title ASC ";
			}else{
				$sql .= " ORDER BY distance ASC ";
			}
		} */
	} else{
		if($post_type == 'showcase' || $type=='showcase'){}else{
			if($search){
				$sql .= " ORDER BY post_title ASC ";
			}else{
				$sql .= "  ORDER BY id DESC ";
			}
		}
	} 
	
		$sql .=") $wpdb->posts ";
		
		$sql .= " LIMIT $limit OFFSET $OFFSET";
	}else{
		$sql .= " LIMIT $limit OFFSET $OFFSET";
	} 
	$neighbors['sql'] = $sql;
	$neighbors['result'] = $wpdb->get_results( $sql );
	return $neighbors;
}
function tida_search_widget() { 
    include 'widget/tida_search_widget.php';
}
add_shortcode('tida_search_widget', 'tida_search_widget');

function tida_sports_list_widget() {
    $taxonomy     = 'sport';
	$terms = get_terms( array (
		'taxonomy' => $taxonomy,
		//'order'    => 'ASC',
		//'orderby'  => 'name',
		//'number'   => 8,
		'hide_empty' => false,
	) );
    include 'widget/tida_sports_list_widget.php';
}
add_shortcode('tida_sports_list_widget', 'tida_sports_list_widget');

function tida_showcase_list_widget() {
    $taxonomy     = 'sport';
	$showcases = get_posts(array('post_type' => 'showcase',
        'order'    => 'ASC',
        'orderby'  => 'name',
        'number'   => 10,
        'post_status'   => 'publish',
        'meta_query' => array(
        array(
        'key'       => 'approved',
        'value'     => true,
        'compare'   => '='
        )
    )));
    include 'widget/tida_showcase_list_widget.php';
}
add_shortcode('tida_showcase_list_widget', 'tida_showcase_list_widget');

function tida_academy_list_widget() {
    $args = array(
			'post_type'      => 'product',
			'post_status' => 'publish',
			'meta_query' => array(
                array('key' => 'product_type',
                      'value' => 'Academy', 
                      'compare' => '=',
                )
            ),  
            'posts_per_page' => 10
	);
    $loop = new WP_Query( $args );
    include 'widget/tida_academy_list_widget.php';
}
add_shortcode('tida_academy_list_widget', 'tida_academy_list_widget');

function tida_venue_list_widget() {
    $args = array(
			'post_type'      => 'product',
			'post_status' => 'publish',
			'meta_query' => array(
                array('key' => 'product_type',
                      'value' => 'Venue', 
                      'compare' => '=',
                )
            ),  
            'posts_per_page' => 10
	);
    $loop = new WP_Query( $args );
    include 'widget/tida_venue_list_widget.php';
}
add_shortcode('tida_venue_list_widget', 'tida_venue_list_widget');


// Anuj's code here
function register_testimonial_post_type() {
    $args = array(
        'public' => true,
        'label'  => 'Testimonials',
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-testimonial',
        'rewrite' => array('slug' => 'testimonials'),
    );
    register_post_type('testimonial', $args);
}
add_action('init', 'register_testimonial_post_type');


function testimonial_slider_shortcode_handler() {
    ob_start();
    ?>
    <section class="mains">
        <div class="slider">
            <div class="buttons">
                <div class="previous">←</div>
                <div class="next">→</div>
            </div>
            <?php
            $args = array(
                'post_type' => 'testimonial',
                'posts_per_page' => -1, // Retrieve all testimonials
            );
            $testimonials = new WP_Query($args);
            
            if ($testimonials->have_posts()) {
                while ($testimonials->have_posts()) : $testimonials->the_post();
                    ?>
                    <div class="slide">
                        <div class="testimonial">
                            <h3 class="heads"><?php the_field('header'); ?></h3>
                            <span class="descriptions"><?php the_content(); ?></span>
                        </div>
                        <div class="slider-img">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="image">
                                    <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>">
                                </div>
                                <div class="names">
                                    <span class="stars"><?php the_field('ratings'); ?></span>
                                    <span class="designation"><?php the_field('name'); ?></span>
                                    <span class="location"><?php the_field('location'); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                endwhile;
            } else {
                echo '<p>No testimonials found.</p>';
            }
            wp_reset_postdata();
            ?>
        </div>
    </section>
    <script>
        const next = document.querySelector(".next");
        const prev = document.querySelector(".previous");
        const slides = document.querySelectorAll(".slide");

        let index = 0;
        display(index);

        function display(index) {
            slides.forEach((slide) => {
                slide.style.display = "none";
            });
            slides[index].style.display = "flex";
        }

        function nextSlide() {
            index++;
            if (index > slides.length - 1) {
                index = 0;
            }
            display(index);
        }

        function prevSlide() {
            index--;
            if (index < 0) {
                index = slides.length - 1;
            }
            display(index);
        }

        next.addEventListener("click", nextSlide);
        prev.addEventListener("click", prevSlide);
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('testimonial_slider', 'testimonial_slider_shortcode_handler');
function custom_login_logo() {
    echo '<style type="text/css">
        #login h1 a {
            background-image: url(https://tidasports.com/wp-content/uploads/2022/11/Logo.png);
            background-size: cover;
            width: 100%; /* Adjust width as needed */
            height: 150px; /* Adjust height as needed */
        }
    </style>';
}
add_action('login_enqueue_scripts', 'custom_login_logo');
add_action('woocommerce_checkout_create_order', 'save_partner_id_on_order_creation');
function save_partner_id_on_order_creation($order) {
    // Loop through order items
    foreach ($order->get_items() as $item_id => $item) {
        // Get the product
        $product = wc_get_product($item['product_id']);
        // Get the partner ID from product custom field 'partner_manager'
        $partner_id = get_post_meta($product->get_id(), 'partner_manager', true);
        // Save the partner ID as order item meta
        if (!empty($partner_id)) {
			$user =  get_user_by('id', $partner_id);
            $item->update_meta_data('partner_id', $partner_id);
            update_post_meta($order->get_id(), 'partner_email', $user->user_email);
        }
    }
}
function custom_process_registration( $customer_id ) {
    if ( isset( $_POST['user_role'] ) ) {
        $user_role = sanitize_text_field( $_POST['user_role'] );
        // Assign the selected role to the user
        if ( $user_role === 'partner' ) {
            $user = new WP_User( $customer_id );
            $user->set_role( 'partner' );
        }
    }
}
add_action( 'woocommerce_created_customer', 'custom_process_registration' );

function custom_validate_registration( $errors, $username, $email ) {
    $existing_user = get_user_by( 'email', $email );
    if ( $existing_user && in_array( 'partner', $existing_user->roles, true ) ) {
        // If the email already exists and belongs to a partner, prevent registration
        $errors->add( 'email_exists', __( 'Email address is already registered as a partner. Please log in.', 'woocommerce' ) );
    }
    return $errors;
}
add_filter( 'woocommerce_registration_errors', 'custom_validate_registration', 10, 3 );
function custom_check_login_role( $user, $username, $password ) {
    if ( is_a( $user, 'WP_User' ) && is_wp_error( $user ) ) {
        return $user; // Return if there's an error
    }
    // Check if the user is trying to log in as a partner or user
    if ( isset( $_POST['log'], $_POST['pwd'] ) && ! empty( $_POST['log'] ) && ! empty( $_POST['pwd'] ) ) {
        $login_user = get_user_by( 'login', $_POST['log'] );
        if ( $login_user && in_array( 'partner', $login_user->roles, true ) && ! current_user_can( 'partner' ) ) {
            return new WP_Error( 'login_failed', __( 'You are trying to login as a partner. Please login using partner credentials.', 'woocommerce' ) );
        } elseif ( $login_user && in_array( 'customer', $login_user->roles, true ) && ! current_user_can( 'customer' ) ) {
            return new WP_Error( 'login_failed', __( 'You are trying to login as a user. Please login using user credentials.', 'woocommerce' ) );
        }
    }
    return $user;
}
add_filter( 'authenticate', 'custom_check_login_role', 10, 3 );
// ----------------------
// Define a custom function to retrieve partner subscription orders
function getordersbypartner($type = 'booking'){
    $user = get_current_user_id();
    if($type == 'booking'){
        $args= array(
			'limit' => 10,
			'orderby' => 'date',
			'order' => 'DESC',
			'number'   => $order_per_page ?? 10,
			'offset'   => $offset ?? 0,
			'return' => 'ids',
			'meta_query' => array(
				'relation' => 'AND',
                array('key' => 'tida_order_type',
                      'value' => 'booking', 
                      'compare' => 'LIKE',
				),
				array('key' => 'partner_id',
                      'value' => $user, 
                      'compare' => 'IN',
				)
			)
		);
    }else if($type == 'subscription'){
		$args= array(
			'limit' => 10,
			'orderby' => 'date',
			'order' => 'DESC',
			'number'   => $order_per_page ?? 10,
			'offset'   => $offset ?? 0,
			'return' => 'ids',
			'meta_query' => array(
				'relation'=>'AND',
				array(
		        'relation' => 'OR',
                array('key' => 'tida_order_type',
                      'value' => 'subscription_variation', 
                      'compare' => 'LIKE',
                ),
                array('key' => 'tida_order_type',
                      'value' => 'variable-subscription', 
                      'compare' => 'LIKE',
                )),
				array('key' => 'partner_id',
                      'value' => $user, 
                      'compare' => 'LIKE',
				)
            )
		);
    }else if($type == 'package'){
		$args=array(
			'limit' => 10,
			'orderby' => 'date',
			'order' => 'DESC',
			'number'   => $order_per_page ?? 10,
			'offset'   => $offset ?? 0,
			'return' => 'ids',
			'meta_query' => array(
				'relation' => 'AND',
			    array('relation' => 'OR',
                array('key' => 'tida_order_type',
                      'value' => ', variation', 
                      'compare' => 'LIKE',
				), array('key' => 'tida_order_type',
						  'value' => 'variation', 
						  'compare' => '=',
				)),
				array('key' => 'partner_id',
					  'value' => $user, 
					  'compare' => 'LIKE',
				)
			)
		);
    }
    // print_r(" $args before wc_get_orders query' ".$args);
    $query = wc_get_orders($args);
    $orders_json_encoded = json_encode($query);
    // print_r($orders_json_encoded);
    return $orders_json_encoded;
// 	$c_orders = $query->get_orders();
// 	return $c_orders;
}
add_action('woocommerce_edit_account_form_tag',function(){
	echo ' enctype="multipart/form-data"';
});
add_action('woocommerce_edit_account_form_start',function(){
	?>
	<p><label for="photo">Photo</label><input type="file" id="photo" name="photo" /></p>
	<?php 
	$user = wp_get_current_user();
	$roles =  $user->roles;
	if(in_array('partner',$roles)){
	?>
	<p class="enable_cod_wrapper"><label for="enable_cod">Enable Cheque/COD Payment option for customers</label><input type="checkbox"  id="enable_cod" value="1" name="enable_cod" <?php if(get_user_meta( $user->ID, 'enable_cod', true )){ echo 'checked="checked"'; } ?> /><label for="enable_cod">Toggle</label></p>
	<style>
		input#enable_cod{
		  height: 0;
		  width: 0;
		  visibility: hidden;
		}
		input#enable_cod + label {
			cursor: pointer;
			text-indent: -9999px;
			width: 50px;
			height: 25px;
			background: grey;
			display: inline-block;
			border-radius: 100px;
			position: relative;
		}
		input#enable_cod + label:after {
			content: '';
			position: absolute;
			top: 0px;
			left: 1px;
			width: 25px;
			height: 25px;
			background: #fff;
			border-radius: 90px;
			transition: 0.3s;
		}
		input#enable_cod:checked + label {
		  background: #bada55;
		}
		input#enable_cod:checked + label:after {
		  left: calc(100% - 1px);
		  transform: translateX(-100%);
		}
		input#enable_cod + label:active:after {
		  width: 130px;
		}
	</style>
	<?php
	}
});
add_action('woocommerce_save_account_details',function( $user_id ){
	if(isset($_POST['enable_cod'])){
		echo $enable_cod = $_POST['enable_cod'];
		update_user_meta($user_id, 'enable_cod', $enable_cod);
	}else{
		update_user_meta($user_id, 'enable_cod', 0);	
	}
	if ( ! function_exists( 'wp_handle_upload' ) ) {
	    require_once( ABSPATH . 'wp-admin/includes/file.php' );
	} 
	$uploadedfile = $_FILES['photo'];
	$movefile = wp_handle_upload( $uploadedfile, array('test_form' => FALSE) );
	if ( $movefile && !isset( $movefile['error'] ) ) {
	  if(get_user_meta($user_id, 'avatar', $movefile['url'])){
	      update_user_meta($user_id, 'avatar', $movefile['url']); 
	  }else{
	      add_user_meta($user_id, 'avatar', $movefile['url']);
	  }
	} else {
	    echo $movefile['error'];
	}
});
add_filter ('get_avatar', function($avatar_html, $id_or_email, $size, $default, $alt) {
	$avatar = get_user_meta($id_or_email,'avatar',true);
	if( $avatar ) {
		return '<img src="'.$avatar.'" width="96" height="96" alt="Avatar" class="avatar avatar-96 wp-user-avatar wp-user-avatar-96 photo avatar-default" />';
	} else {
		return $avatar_html;
	}
}, 10, 5);
// custom sign
function custom_sign_in_button_shortcode() {

    ob_start();
    $my_account_url = is_user_logged_in() ? esc_url(wc_get_page_permalink('myaccount')) : esc_url(home_url('/my-account'));

    ?>
    <div class="button-wrapper header_button">
    <a href="<?php echo $my_account_url; ?>" class="header_button_a">
	<button class="button-wrapper__button">
        <?php
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $user_display_name = $current_user->display_name;
            echo 'Hi, ' . esc_html($user_display_name);
        } else {
            echo 'Login | Register';
        }
        ?>
	</button>
    </a>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('custom_sign_in_button', 'custom_sign_in_button_shortcode');
if( function_exists('acf_add_options_page')) {
	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
}

/**
 * Add a new options page named "Notification Server".
 */
function myprefix_register_options_page() {
    add_menu_page(
        'Notification Server',
        'Notification Server',
        'manage_options',
        'notification_server',
        'notification_server_page_html'
    );
}
add_action( 'admin_menu', 'myprefix_register_options_page' );

/**
 * The "My Options" page html.
 */
function notification_server_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_GET['settings-updated'] ) ) {
        add_settings_error(
            'notification_server_mesages',
            'notification_server_message',
            esc_html__( 'Settings Saved', 'text_domain' ),
            'updated'
        );
    }

    settings_errors( 'notification_server_mesages' );

    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
                settings_fields( 'notification_server_group' );
                do_settings_sections( 'notification_server' );
                submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}
/**
 * Register our settings.
 */
function myprefix_register_settings() {
    register_setting( 'Notification Server', 'notification_server' );

    add_settings_section(
        'notification_server_sections',
        false,
        false,
        'notification_server'
    );

    add_settings_field(
        'notification_domain',
        esc_html__( 'Notification Server', 'text_domain' ),
        'render_notification_domain_field',
        'notification_server',
        'notification_server_sections',
        [
            'label_for' => 'notification_domain',
        ]
    );
}
add_action( 'admin_init', 'myprefix_register_settings' );
/**
 * Render the "notification_domain" field.
 */
function render_notification_domain_field( $args ) {
    $value = get_option( 'notification_server' )[$args['label_for']] ?? '';
    ?>
    <input
        type="text"
        id="<?php echo esc_attr( $args['label_for'] ); ?>"
        name="notification_server[<?php echo esc_attr( $args['label_for'] ); ?>]"
        value="<?php echo esc_attr( $value ); ?>">
    <?php
}
register_setting( 'notification_server_group', 'notification_server', [
    'sanitize_callback' => 'notification_server_sanitize_fields',
    'default'           => []
] );
/**
 * Sanitize fields before adding to the database.
 */
function notification_server_sanitize_fields( $value ) {
    $value = (array) $value;
    if ( ! empty( $value['notification_domain'] ) ) {
        $value['notification_domain'] = sanitize_text_field( $value['notification_domain'] );
    }
    return $value;
}

/**
 * Allowing Webp
 */
function webp_upload_mimes( $existing_mimes ) {
    // add webp to the list of mime types
    $existing_mimes['webp'] = 'image/webp';
    // return the array back to the function with our added mime type
    return $existing_mimes;
}
add_filter( 'mime_types', 'webp_upload_mimes' );
//** * Enable preview / thumbnail for webp image files.*/
function webp_is_displayable($result, $path) {
    if ($result === false) {
        $displayable_image_types = array( IMAGETYPE_WEBP );
        $info = @getimagesize( $path );
        if (empty($info)) {
            $result = false;
        } elseif (!in_array($info[2], $displayable_image_types)) {
            $result = false;
        } else {
            $result = true;
        }
    }
    return $result;
}
add_filter('file_is_displayable_image', 'webp_is_displayable', 10, 2);

//---------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------
/* Popup Image for home page */

/*
function enqueue_popup_script() {
    if (is_front_page()) { // Only load on the homepage
        wp_enqueue_script('popup-script', get_stylesheet_directory_uri() . '/assets/js/popup.js', array('jquery'), null, true);
	wp_enqueue_style('popup-css', get_stylesheet_directory_uri() . '/assets/css/popup.css', array(), null, 'all');

        // Get ACF fields
        $popup_image = get_field('popup_image', 'option');
        $popup_url = get_field('popup_url', 'option'); // Get full link array

        // Localize the fields for JavaScript with conditional logic for URL
        if ($popup_image) {
            wp_localize_script('popup-script', 'popupData', array(
                'image'  => $popup_image['url'] ?? '',      // Image URL
                'url'    => $popup_url['url'] ?? '',        // Link URL or empty if not provided
                'title'  => $popup_url['title'] ?? '',      // Link title
                'target' => $popup_url['target'] ?? '_self' // Link target
            ));
        }
    }
}
add_action('wp_enqueue_scripts', 'enqueue_popup_script');
*/
//---------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------
function custom_blog_posts_shortcode($atts) {
    // Set default values for the attributes
    $atts = shortcode_atts( array(
        'ids' => '',            // Comma-separated list of post IDs
        'posts_per_page' => 3,  // Default posts per page
        'is_pagination' => 'false',  // Default is no pagination
    ), $atts, 'custom_blog_posts' );

    // Extract post IDs and convert them to an array if provided
    $ids = !empty($atts['ids']) ? explode(',', $atts['ids']) : array();

    // Prepare the query arguments
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $atts['posts_per_page'],
        'post__in' => $ids,  // If IDs are passed, filter by these IDs
        'orderby' => 'post__in', // Maintain order of IDs passed
    );

    // Execute the query
    $query = new WP_Query($args);

    // Start output buffer to capture the content
    ob_start();

    // Check if posts exist
    if ($query->have_posts()) :
        echo '<div class="blog-posts-container">';
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
        <?php endwhile;
        
        // Check if pagination is needed
        if ($atts['is_pagination'] === 'true') {
            echo '<div class="load-more">';
            echo paginate_links([
                'total' => $query->max_num_pages,
            ]);
            echo '</div>';
        }
        
        echo '</div>';
    else :
        echo '<p>No posts found!</p>';
    endif;

    wp_reset_postdata();

    // Return the buffered content
    return ob_get_clean();
}
add_shortcode('related_blog_posts', 'custom_blog_posts_shortcode');

add_filter('the_content', 'add_related_blog_posts_to_single');

function add_related_blog_posts_to_single($content) {
    if (is_single() && get_post_type() === 'post') {
        // Add the related blog posts shortcode after the content
        $related_posts = do_shortcode('[related_blog_posts]');
        $content .= $related_posts; // Append to the content
    }
    return $content;
}

//---------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------

// Create the shortcode for the dialog with Gravity Form
function gravity_form_dialog_shortcode($atts) {
    // Default attributes for the shortcode
    $atts = shortcode_atts(array(
        'form_id' => 4, // Default to form ID 4
    ), $atts, 'gravity_form_dialog');

    // Generate the dialog HTML
    ob_start(); ?>

    <dialog id="gravityFormDialog">
        <div>
            <button onclick="closeDialog()" >X</button>
            <h2>Job Application Form</h2>
            <?php
                // Render the Gravity Form with the provided form ID
                echo do_shortcode('[gravityform id="' . $atts['form_id'] . '" title="true" ajax="true"]');
            ?>
        </div>
    </dialog>

    <?php
    return ob_get_clean();
}

// Register the shortcode
add_shortcode('gravity_form_dialog', 'gravity_form_dialog_shortcode');

//---------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------

add_filter( 'auto_update_plugin', '__return_false' );
add_filter( 'auto_update_theme', '__return_false' );

function remove_plugin_menu() {
    remove_menu_page('plugins.php');  // Removes the Plugins menu
}
add_action('admin_menu', 'remove_plugin_menu', 999);


function remove_acf_menu(){
  global $current_user;
  if ($current_user->user_login!='admin'){
    remove_menu_page( 'edit.php?post_type=acf-field-group' );
  }
}
add_action( 'admin_menu', 'remove_acf_menu', 100 );



?>