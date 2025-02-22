<?php
/**
 * The template for displaying single Showcase custom post type.
 * Template Name: Single Showcase Template
 */

get_header();

if (have_posts()) {
    the_post();
    $id = get_the_ID();
    $thumbnail_url = get_the_post_thumbnail_url($id, 'full');
    $partner_manager = get_post_meta($id,"partner_manager",true);
    $address = get_post_meta($id,"address",true);
    // $latitude = get_post_meta($id,"latitude",true);
    // $longitude = get_post_meta($id,"longitude",true);
    $no_of_tickets = get_post_meta($id,"no_of_tickets",true);
    $tickets_left = get_post_meta($id,"tickets_left",true);
    $sponsors = get_post_meta($id,"sponsors",true);
    $tournament_type = get_post_meta($id,"tournament_type",true);
    $start_date_time = get_post_meta($id,"start_date_time",true);
    $end_date_time = get_post_meta($id,"end_date_time",true);
    $url = get_post_meta($id,"url",true);
    $approved = get_post_meta($id,"approved",true);
	//print_r($url);

	function getYoutubeVideoId($url) {
    		$parts = parse_url($url);
    		if (isset($parts['query'])) {
        		parse_str($parts['query'], $query);
        		if (isset($query['v'])) {
            		return $query['v'];
        		}
    		}
    		return false;
	}
	$video_id = getYoutubeVideoId($url);

    ?>
    <?php 
      $showcase_header_title = get_post_meta($id, "showcase_header_title", true);
      $showcase_header_description = get_post_meta($id, "showcase_header_description", true);
    
      // Set default values if title or description doesn't exist
      $title = !empty($showcase_header_title) ? $showcase_header_title : get_the_title();
      $description = !empty($showcase_header_description) ? '<p>'.$showcase_header_description.'</p>' : 'Spotlighting Showcase Event';
    
      // Call the shortcode with dynamic values
      echo do_shortcode("[show_banner_of_page avatar='' title='" . esc_attr($title) . "' description='" . wp_kses_post($description) . "']");
    ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class('single-showcase'); ?>>
        <style>
            
            .single-showcase__header,
            .single-showcase__content,
            .single-showcase__meta-details{
                max-width:1160px;
                margin:30px auto;
                padding:0 20px;
            }
            .single-showcase__header_video{
                border-radius:16px;
                overflow:hidden;
            }
	    .single-showcase__header_video iframe{
                min-height:630px;
            }
            .single-showcase__header_content h1,
            .single-showcase__meta-details__wrapper__heading {
                font-size: 36px;
                font-weight: bold;
                font-style: normal;
                text-align: left;
                color: #04255e;
                margin:16px 0;
            }
            .single-showcase__meta-details__wrapper__table{
                border-radius:16px;
                overflow:hidden;
            }
            .single-showcase__meta-details__wrapper__table__thead tr{
                background: #04255E !important;
                color:#fff;
                font-weight:600;
                font-size:20px;
            }
            .single-showcase__meta-details__wrapper__table__thead tr th{
                background:none !important;
            }
	    @media screen and (max-width: 667px) {
        	.single-showcase__header_video iframe {
            		width: 100%;
            		min-height:380px;
    		}
	    }

            
        </style>
        <header class="single-showcase__header">
            <div class="single-showcase__header__wrapper">
		<?php
			if ($video_id !== false) {
     			   // Embed the video using the video ID
        			echo '<div class="single-showcase__header_video">';
				echo '<iframe width="100%" height="100%" src="https://www.youtube.com/embed/' . $video_id . '?rel=0&showinfo=0" frameborder="0" allowfullscreen></iframe>';
        			echo '</div>';
    			} 
		?>
                <div class="single-showcase__header_content">
                    <h1><?php the_title(); ?></h1>
                    <?php
                        $categories = get_the_category();
                        $count = count($categories);
                         if (!empty($categories)) {
                             echo '<div class="showcase-categories">';
                             echo '<span>' . __('Categories: ', 'storefront-child') . '</span>';
                                foreach ($categories as $index => $category) {
                                    echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                                    if ($index < $count - 1) {
                                        echo ', ';
                                    }
                                }
                             echo '</div>';
                         }
                     ?>
                </div>
            </div>
        </header>

        <div class="single-showcase__content">
            <?php the_content(); ?>
        </div>
        
        <div class="single-showcase__meta-details">
            <div class="single-showcase__meta-details__wrapper">
                <h1 class="single-showcase__meta-details__wrapper__heading">Important Information</h1>
                <table class="single-showcase__meta-details__wrapper__table">
                    <thead class="single-showcase__meta-details__wrapper__table__thead">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                Title
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Details
                            </th>
                        </tr>
                    </thead>
                    <tbody class="single-showcase__meta-details__wrapper__table__tbody">
                    <!--$approved = get_post_meta($id,"approved",true);-->
                        <?php
                            if($partner_manager){
                                echo "<tr>
                                <th>
                                    Partner Manager
                                </th>
                                <td>
                                    $partner_manager
                                </td>
                            </tr>";
                            }
                            if($address){
                                echo "<tr>
                                <th>
                                    Address
                                </th>
                                <td>
                                    $address
                                </td>
                            </tr>";
                            }
                            if($no_of_tickets){
                                echo "<tr>
                                <th>
                                    No Of Tickets
                                </th>
                                <td>
                                    $no_of_tickets
                                </td>
                            </tr>";
                            }
                            if($tickets_left){
                                echo "<tr>
                                <th>
                                    Tickets Left
                                </th>
                                <td>
                                    $tickets_left
                                </td>
                            </tr>";
                            }
                            if($sponsors){
                                echo "<tr>
                                <th>
                                    Sponsors
                                </th>
                                <td>
                                    $sponsors
                                </td>
                            </tr>";
                            }
                            if($tournament_type){
                                echo "<tr>
                                <th>
                                    Tournament Type
                                </th>
                                <td>
                                    $tournament_type
                                </td>
                            </tr>";
                            }
                            if($start_date_time){
                                echo "<tr>
                                <th>
                                    Start Date Time
                                </th>
                                <td>
                                    $start_date_time
                                </td>
                            </tr>";
                            }
                            if($end_date_time){
                                echo "<tr>
                                <th>
                                    End Date Time
                                </th>
                                <td>
                                    $end_date_time
                                </td>
                            </tr>";
                            }
                            if ($approved) {
                                echo "<tr>
                                    <th>
                                        Approved
                                    </th>
                                    <td>
                                        " . ($approved ? "Yes" : "No") . "
                                    </td>
                                </tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="single-showcase__others">
            <?php
                do_shortcode("[showing_other_post_for_single_details_page type='showcase' exclude_id='$id' post_per_page='3']");
            ?>
        </footer>

    </article>
    <?php
} else {
    // If no content, display a message.
    echo "No posts found.";
}

get_footer();
