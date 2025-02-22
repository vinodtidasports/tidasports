<?php
/*
Template Name: Shirt Collection
*/
// Arguments to get product categories with variable products
$taxonomies = array( 'sport' ); // You can change this to other taxonomies if needed
$args = array(
    'taxonomy'   => $taxonomies,
    'hide_empty' => true, // Only return terms with products
);
$terms = get_terms( $args );
if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
    foreach ( $terms as $term ) { 
        // Get the products within this taxonomy term
        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => -1, // Adjust as needed
            'tax_query'       => array(
                array(
                    'taxonomy' => 'sport',
                    'field'    => 'id',
                    'terms'    => $term->term_id,
                    'operator' => 'IN',
                ),
            ),
            'meta_query'      => array(
                array(
                    'key'     => 'product_type',
                    'value'   => 'Shop', // Check for variable products
                    'compare' => 'LIKE',
                ),
            ),
        );
        $query = new WP_Query( $args ); 
        if ( $query->have_posts() ) { echo '<pre>'; print_r($term);
            echo 'Term: ' . $term->name . '<br>';
        }
        wp_reset_postdata();
    }
} else {
    echo 'No terms found or error in fetching terms.';
}
?>