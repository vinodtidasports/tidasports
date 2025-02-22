<?php
function getuseridbyemail($data){
    if( $data) {
       $email = $data->get_param('email');
       $customer = get_user_by( 'email', $email);
       if(!empty($customer)){
            return array('status'=>'success','data'=> $customer->ID);
       }else{
            return array('status'=>'failure','message'=>'no customer exist with this email.');
       }
    }else{
       return array('status'=>'failure','message'=>'no customer exist with this email.');
    }
}
function forgot_password($data){
	if( $data) {
       $email = $data->get_param('email');
       $customer = get_user_by( 'email', $email);
       if(!empty($customer)){
            $user_id = $customer->ID;
			$user = new WP_User( intval($user_id) );
			$reset_key = get_password_reset_key( $user );
			$wc_emails = WC()->mailer()->get_emails();
			$wc_emails['WC_Email_Customer_Reset_Password']->trigger( $user->user_login, $reset_key );
			return array('status'=>'success','message'=>'Please check your email to reset the password');
       }else{
            return array('status'=>'failure','message'=>'no customer exist with this email.');
       }
    }else{
        return array('status'=>'failure','message'=>'Parameter Missing.');
    }
}
function getalllocations($data){
    $taxonomy     = 'product_cat';
    $current_page = 1;
	$terms_per_page = 10;
    if( $data) {
        if($data->get_param('page')){
           $current_page = $data->get_param('page');
        }
        if($data->get_param('limit_per_page')){
           $terms_per_page = $data->get_param('limit_per_page');
        }
    }
	$term_count = get_terms( array (
		'taxonomy' => $taxonomy,
		'fields'   => 'count',
	) );
	$max_num_pages = ceil( $term_count / $terms_per_page );
	if(isset($_GET['page'])){
		$current_page = $_GET['page'];
	}
	$offset = 0; 
	if( ! 0 == $current_page) {
		$offset = ( $terms_per_page * $current_page ) - $terms_per_page;
	}
    $all_terms = get_terms( array (
		'taxonomy' => $taxonomy,
		'order'    => 'ASC',
		'orderby'  => 'name',
		'number'   => $terms_per_page,
		'offset'   => $offset,
	) );
	$i = 0;
    foreach ($all_terms as $cat) {
    if($cat->category_parent == 0) {
        $category_id = $cat->term_id;       
        $args2 = array(
                'taxonomy'     => $taxonomy,
                'child_of'     => 0,
                'parent'       => $category_id,
        );
        $sub_cats = get_categories( $args2 );
        if($sub_cats) {  
                $cat->sub = $sub_cats;
        }   
        $locations['data'][$i] = $cat;
        $i++;
    }       
    }
	if($locations){
	    $locations['total_item'] = $term_count;
	    $locations['max_num_pages'] = $max_num_pages;
	   return array('status'=>'success','data'=>$locations);
	}else{
	    return array('status'=>'failure','message'=>'No sport is available');
	}
}
function getallsports($data){
	$current_page = 1;
	$terms_per_page = 10;
    if( $data) {
        if($data->get_param('page')){
           $current_page = $data->get_param('page');
        }
        if($data->get_param('limit_per_page')){
           $terms_per_page = $data->get_param('limit_per_page');
        }
    }
    $taxonomy     = 'sport';
	$term_count = get_terms( array (
		'taxonomy' => $taxonomy,
		'fields'   => 'count',
		'hide_empty' => true,
	) );
	   if($terms_per_page > 10){
		   $terms_per_page = $term_count;
	   }
	$max_num_pages = ceil( $term_count / $terms_per_page );
	$offset = 0; 
	if( ! 0 == $current_page) {
		$offset = ( $terms_per_page * $current_page ) - $terms_per_page;
	}
	$terms = get_terms( array (
		'taxonomy' => $taxonomy,
		'order'    => 'ASC',
		'orderby'  => 'name',
		'number'   => $terms_per_page,
		'offset'   => $offset,
		'hide_empty' => true,
	) );
	if($terms){
	    $i = 0;
	    foreach($terms as $term){
	        $all_sports['data'][$i]['term_id'] = $term->term_id;
	        $all_sports['data'][$i]['item_count'] = $term->count;
	        $all_sports['data'][$i]['name'] = html_entity_decode($term->name);
	        $all_sports['data'][$i]['description'] = html_entity_decode(wp_strip_all_tags($term->description));
	        $all_sports['data'][$i]['image'] = get_field('icon',$term);
			$all_sports['data'][$i]['titleicon'] = get_field('title_icon',$term);
	        $i++;
	    }
	    $all_sports['total_item'] = $term_count;
	    $all_sports['max_num_pages'] = $max_num_pages;
	   return array('status'=>'success','data'=>$all_sports);
	}else{
	     return array('status'=>'failure','message'=>'No sport is available');
	}
}
function getproductbycity($data){
	$current_page = 1;
	$terms_per_page = 10;
	$latitude = $longitude = $distance = $type = '';
    if( $data) {
        if($data->get_param('page')){
           $current_page = $data->get_param('page');
        }
        if($data->get_param('term_id')){
           $term_id = $data->get_param('term_id');
        }
        if($data->get_param('limit_per_page')){
           $terms_per_page = $data->get_param('limit_per_page');
        }
        if($data->get_param('latitude')){
           $latitude = $data->get_param('latitude');
        }
        if($data->get_param('longitude')){
           $longitude = $data->get_param('longitude');
        }
        if($data->get_param('distance')){
           $distance = $data->get_param('distance');
        }
        if($data->get_param('type')){
           $type = $data->get_param('type');
        }
    }
	$offset = 0;
	if( ! 0 == $current_page) {
		$offset = ( $terms_per_page * $current_page ) - $terms_per_page;
	}
    $taxonomy     = 'product_cat';
	$all_items = query_neighbors($latitude, $longitude, 'product', $distance, $terms_per_page,$offset,$term_id,$taxonomy,'',$type,'');
	    $products = array();
	    $total_items = query_neighbors_count($latitude, $longitude, 'product', $distance, $term_id,$taxonomy,'',$type,'');
	    $max_num_pages = ceil( $total_items / $terms_per_page );
	    $products['total_items'] = $total_items;
	    $products['max_num_pages'] = $max_num_pages;
	if($all_items && $total_items > 0){
	    $i = 0;
        foreach($all_items as $item){ 
            $img = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID),'full');
            $product = wc_get_product( $item->ID );
            $products['data'][$i]['id'] = $item->ID;
            if (property_exists($item, 'distance')) {
            $products['data'][$i]['distance'] = $item->distance;
			$products['data'][$i]['latitude'] = get_field('latitude',$item->ID);
			$products['data'][$i]['longitude'] = get_field('longitude',$item->ID);
            }
            $products['data'][$i]['title'] = html_entity_decode(get_the_title($item->ID));
            if($img){
                $products['data'][$i]['image'] = $img[0];
            }else{
                $products['data'][$i]['image'] = '';
            }
            $products['data'][$i]['type'] = get_field('product_type',$item->ID);
            $products['data'][$i]['address'] = html_entity_decode(get_field('address',$item->ID));
            if( ! $product->is_type('variable') ){
            $products['data'][$i]['price'] = $product->get_price();
            }else{
            $min_price = $product->get_variation_price( 'min' );
            $max_price = $product->get_variation_price( 'max' );
            $products['data'][$i]['min_price'] = $min_price;
            $products['data'][$i]['max_price'] = $max_price;
            }
			if( $product->is_type('booking') ){
			if ( $product->has_resources()) {
				$resources = $product->get_resources();
				$r = 0;
				foreach ( $resources as $resource ) { 
						$products['data'][$i]['resources'][$r]['id'] = $resource->get_id();
						$products['data'][$i]['resources'][$r]['name'] = $resource->get_name();
					$r++;
				}
			}
			}
            if( $product->is_on_sale() ) {
                $products['data'][$i]['sale_price'] = $product->get_sale_price();
            }
            $i++;
        }
        return array('status'=>'success','data'=>$products);
    }
	else{
	    return array('status'=>'failure','message'=>'No data is found with requested parameters');
	}
}
function getallproducts($data){
	$current_page = 1;
	$terms_per_page = 10;
	$latitude = $longitude = $distance = $type ='';
    if( $data) {
        if($data->get_param('page')){
           $current_page = $data->get_param('page');
        }
        if($data->get_param('limit_per_page')){
           $terms_per_page = $data->get_param('limit_per_page');
        }
        if($data->get_param('latitude')){
           $latitude = $data->get_param('latitude');
        }
        if($data->get_param('longitude')){
           $longitude = $data->get_param('longitude');
        }
        if($data->get_param('distance')){
           $distance = $data->get_param('distance');
        }
        if($data->get_param('type')){
           $type = $data->get_param('type');
        }
    }
	$offset = 0;
	if( ! 0 == $current_page) {
		$offset = ( $terms_per_page * $current_page ) - $terms_per_page;
	} 
    $all_items = query_neighbors($latitude, $longitude, 'product', $distance, $terms_per_page,$offset,'','','',$type,'');
    $products = array();
    $total_items = query_neighbors_count($latitude, $longitude, 'product', $distance,'','','',$type,'');
    $max_num_pages = ceil( $total_items / $terms_per_page );
    $products['total_items'] = $total_items;
    $products['max_num_pages'] = $max_num_pages;
	if(!empty($all_items) && $total_items > 0){
	    $i = 0;
        foreach($all_items as $item){ 
            $img = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID),'full');
            $product = wc_get_product( $item->ID );
            $products['data'][$i]['id'] = $item->ID;
            if (property_exists($item, 'distance')) {
            $products['data'][$i]['distance'] = $item->distance;
			$products['data'][$i]['latitude'] = get_field('latitude',$item->ID);
			$products['data'][$i]['longitude'] = get_field('longitude',$item->ID);
            }
            $products['data'][$i]['title'] = html_entity_decode(get_the_title($item->ID));
            if($img){
            $products['data'][$i]['image'] = $img[0];
            }else{
            $products['data'][$i]['image'] = '';
            }
            $products['data'][$i]['type'] = get_field('product_type',$item->ID);
            $products['data'][$i]['address'] = html_entity_decode(get_field('address',$item->ID));
            if( ! $product->is_type('variable') ){
            $products['data'][$i]['price'] = $product->get_price();
            }else{
            $min_price = $product->get_variation_price( 'min' );
            $max_price = $product->get_variation_price( 'max' );
            $products['data'][$i]['min_price'] = $min_price;
            $products['data'][$i]['max_price'] = $max_price;
            }
			if( $product->is_type('booking') ){
			if ( $product->has_resources()) {
				$resources = $product->get_resources();
				$r = 0;
				foreach ( $resources as $resource ) { 
						$products['data'][$i]['resources'][$r]['id'] = $resource->get_id();
						$products['data'][$i]['resources'][$r]['name'] = $resource->get_name();
					$r++;
				}
			}
			}
            $i++;
        }
        return array('status'=>'success','data'=>$products);
    }
	else{
	    return array('status'=>'failure','message'=>'No data is found with requested parameters');
	}
}
function getproductbypartner($data){
	$current_page = 1;
	$terms_per_page = 10;
	$latitude = $longitude = $distance = $type = $term_id = $partner = '';
    if( $data) {
        if($data->get_param('page')){
           $current_page = $data->get_param('page');
        }
        if($data->get_param('term_id')){
           $term_id = $data->get_param('term_id');
        }
        if($data->get_param('limit_per_page')){
           $terms_per_page = $data->get_param('limit_per_page');
        }
        if($data->get_param('latitude')){
           $latitude = $data->get_param('latitude');
        }
        if($data->get_param('longitude')){
           $longitude = $data->get_param('longitude');
        }
        if($data->get_param('distance')){
           $distance = $data->get_param('distance');
        }
        if($data->get_param('partner')){
           $partner = $data->get_param('partner');
        }
        if($data->get_param('type')){
           $type = $data->get_param('type');
        }
    }
	$offset = 0;
	if( ! 0 == $current_page) {
		$offset = ( $terms_per_page * $current_page ) - $terms_per_page;
	}
    $taxonomy     = 'product_cat';
    $all_items = query_neighbors($latitude, $longitude, 'product', $distance, $terms_per_page,$offset,$term_id,$taxonomy,$partner,$type,'');
    $product_data = array();
    $total_items = query_neighbors_count($latitude, $longitude, 'product', $distance, $term_id,$taxonomy,$partner,$type,'');
    $max_num_pages = ceil( $total_items / $terms_per_page );
    $product_data['total_items'] = $total_items;
    $product_data['max_num_pages'] = $max_num_pages;
	if($all_items && $total_items > 0){
	    $i = 0;
    	$product_data['status'] = 'success';
        foreach($all_items as $product){ 
            $productId = $product->ID;
            $product = wc_get_product($productId);
        	$product_data['data'][$i]['currency_symbol'] = html_entity_decode(get_woocommerce_currency_symbol());
        	$product_data['data'][$i]['currency'] = get_option( 'woocommerce_currency' );
        	$product_data['data'][$i]['id'] = $productId;
        	$product_data['data'][$i]['title'] = wp_strip_all_tags(html_entity_decode($product->get_name()));
        	$product_data['data'][$i]['content'] = wp_strip_all_tags(html_entity_decode($product->get_description()));
        	$product_data['data'][$i]['address']= get_field('address',$productId);
        	$product_data['data'][$i]['latitude']= get_field('latitude',$productId);
        	$product_data['data'][$i]['longitude']= get_field('longitude',$productId);
        	$product_data['data'][$i]['contact_no']= get_field('contact_no',$productId);
        	$product_data['data'][$i]['head_coach']= get_field('head_coach',$productId);
        	$product_data['data'][$i]['session_timings']= get_field('session_timings',$productId);
        	$product_data['data'][$i]['skill_level']= get_field('skill_level',$productId);
        	$product_data['data'][$i]['flood_lights']= get_field('flood_lights',$productId);
        	$product_data['data'][$i]['coach_experience']= get_field('coach_experience',$productId);
        	$product_data['data'][$i]['no_of_assistant_coach']= get_field('no_of_assistant_coach',$productId);
        	$product_data['data'][$i]['assistant_coach_name']= get_field('assistant_coach_name',$productId);
        	$product_data['data'][$i]['partner_id']= get_field('partner_manager',$productId);
        	$amenities = get_field('amenities',$productId);
        	$amenities_with_key = [];
        	$l = 0;
			$site_url = site_url();
        	$array = array(
				"Swimming Pool" => "$site_url/wp-content/uploads/2024/05/Swimming.png",
				"PlayGround" => "$site_url/wp-content/uploads/2024/05/Playground.png",
				"CCTV" => "$site_url/wp-content/uploads/2024/05/CCTV1.png",
				"Transportation" => "$site_url/wp-content/uploads/2024/05/Transportation1.png",
				"Online" => "$site_url/wp-content/uploads/2024/05/online-1.png",
				"First Aid" => "$site_url/wp-content/uploads/2024/03/band-aid.png",
				"Flood Lights" => "$site_url/wp-content/uploads/2024/03/stadium.png",
				"Benches & Seating" => "$site_url/wp-content/uploads/2024/03/waiting-room.png",
				"Restrooms" => "$site_url/wp-content/uploads/2024/03/toilet.png",
				"Equipment" => "$site_url/wp-content/uploads/2024/05/Equipment2.png",
				"Cricket Kits" => "$site_url/wp-content/uploads/2024/05/cricket-1.png",
				"Locker" => "$site_url/wp-content/uploads/2024/03/lockers.png",
				"Parking" => "$site_url/wp-content/uploads/2024/03/parking.png",
				"Wifi" => "$site_url/wp-content/uploads/2024/03/wifi.png",
				"Drinking Water" => "$site_url/wp-content/uploads/2024/03/water.png",
				"Recorded Gameplay" => "$site_url/wp-content/uploads/2024/05/CCTV1.png"
			);
            foreach($amenities as $amenity){
                $key = strtolower(str_replace(' ','-',$amenity));
                $key = strtolower(str_replace(array("@","#","!","$","%","&","*","^","(",")"," "),'',$key));
                $key = strtolower(str_replace('--','-',$key));
                $key = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $key);
                $amenities_with_key[$l]['enum_name']  = $key;
                $amenities_with_key[$l]['display_name'] = html_entity_decode($amenity);
				if (array_key_exists($amenity,$array))
				{
					$amenities_with_key[$l]['image'] = $array[$amenity];
				}else{
					$amenities_with_key[$l]['image'] = "$site_url/wp-content/uploads/2024/03/waiting-room.png";
				}
                $l++;
            }
        	$product_data['data'][$i]['amenities']= $amenities_with_key;
        	$image_links[0] = get_post_thumbnail_id( $product->get_id() );
        	$img = wp_get_attachment_image_src($image_links[0],'full');
        	$product_data['data'][$i]['image'] = $img[0];
        	$product_data['data'][$i]['gallery'][0] = $img[0];
        	$attachment_ids = $product->get_gallery_image_ids();
        	$k =1;
        	foreach( $attachment_ids as $attachment_id ) {
        		$product_data['data'][$i]['gallery'][$k] = wp_get_attachment_url( $attachment_id );
        		$k++;
        	}
        	$product_data['data'][$i]['type'] = $type = $product->get_type(); 
        	if($product->is_type('variable')){ 
            	$variations = $product->get_available_variations();
            	$j = 0;
            	foreach ($variations as $key => $value) { 
					$attributes = $value['attributes'];
					foreach($attributes as $attribute){
                        $attribute_pa_package = $attribute;
                    }
            		if($value['variation_is_visible'] == 1 && $value['variation_is_active'] == 1 && $value['is_purchasable'] == 1){
						$variation_id = $value['variation_id'];
						$item_variation = wc_get_product($variation_id);
						$academy_name = $product->get_name() .' - ';
            			$product_data['data'][$i]['packages'][$j]['type'] = $type;
            			$product_data['data'][$i]['packages'][$j]['id'] = $value['variation_id'];
            			$product_data['data'][$i]['packages'][$j]['content'] = wp_strip_all_tags(html_entity_decode($value['variation_description']));
            			/* $product_data['data'][$i]['packages'][$j]['name'] = wp_strip_all_tags(html_entity_decode(ucfirst(str_replace('-',' ',$attribute_pa_package)))); */
            			$product_data['data'][$i]['packages'][$j]['name'] = str_replace($academy_name, ' ', wp_strip_all_tags(html_entity_decode($item_variation->get_name())));
            			$product_data['data'][$i]['packages'][$j]['regular_price'] = $value['display_regular_price'];
            			$product_data['data'][$i]['packages'][$j]['price'] = $value['display_price'];
						$product_data['data'][$i]['packages'][$j]['trial'] = WC_Subscriptions_Product::get_trial_length( $value['variation_id'] );
            			$product_data['data'][$i]['packages'][$j]['img'] = $value['image']['gallery_thumbnail_src'];
            			$product_data['data'][$i]['packages'][$j]['interval'] = get_post_meta( $value['variation_id'],'_subscription_period_interval', true);
            			$product_data['data'][$i]['packages'][$j]['period'] = get_post_meta( $value['variation_id'],'_subscription_period', true);
            			$product_data['data'][$i]['packages'][$j]['start_date'] = date( 'd-m-Y H:i:s');
            			$j++;
            		}
            	}
        	}else if($product->is_type('booking')){
        	    $data['id'] = $productId;
        	    $data['year'] = date('Y');
        	    $data['month'] = date('m');
        	    $data['day'] = date('d');
        	    if ( $product->has_resources() && $resource_id == '') {
        			$resources = $product->get_resources();
                    $r = 0;
        			foreach ( $resources as $resource ) { 
        			    if($r==0){
        			        $data['resource_id'] = $resource->get_id();
        			    }
    			        $r++;
        		    }
        	    }
        	    if(getpartnerslotsbydate($data)){
                    $product_data['data'][$i]['slots'] = getpartnerslotsbydate($data);
        	    }else{
        	      $product_data['data'][$i]['slots'] =   'No block is available for '. date('Y - m -d');
        	    }
        	}
            $i++;
        }
        return array('status'=>'success','data'=>$product_data);
    }
	else{
	    return array('status'=>'failure','message'=>'No data is found with requested parameters');
	}
}
function getproductbysearch($data){
	$current_page = 1;
	$terms_per_page = 10;
	$latitude = $longitude = $distance = $type = $term_id = $search = '';
    if( $data) {
        if($data->get_param('page')){
           $current_page = $data->get_param('page');
        }
        if($data->get_param('term_id')){
           $term_id = $data->get_param('term_id');
        }
        if($data->get_param('limit_per_page')){
            $terms_per_page = $data->get_param('limit_per_page');
        }
        if($data->get_param('latitude')){
           $latitude = $data->get_param('latitude');
        }
        if($data->get_param('longitude')){
           $longitude = $data->get_param('longitude');
        }
        if($data->get_param('distance')){
           $distance = $data->get_param('distance');
        }else{
			$distance = 30;
		}
        if($data->get_param('type')){
           $type = strtolower($data->get_param('type'));
        }
        if($data->get_param('search')){
           $search = $data->get_param('search');
        }
    }
	$offset = 0;
	if( ! 0 == $current_page) {
		$offset = ( $terms_per_page * $current_page ) - $terms_per_page;
	}
    $taxonomy = 'sport'; 
    $query = '';//query_neighbors_test_query($latitude, $longitude, 'product', $distance, $terms_per_page,$offset,$term_id,$taxonomy,'',$type,$search);
    $all_items = query_neighbors($latitude, $longitude, 'product', $distance, $terms_per_page,$offset,$term_id,$taxonomy,'',$type,$search);
    $total_items = query_neighbors_count($latitude, $longitude, 'product', $distance, $term_id,$taxonomy,'',$type,$search);
    $max_num_pages = ceil( $total_items / $terms_per_page );
    $products = array();
    $products['total_items'] = $total_items;
	    $products['max_num_pages'] = $max_num_pages;
	if($all_items && $total_items > 0){
	    $i = 0;
        foreach($all_items as $item){   
            $img = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID),'full');
            $product = wc_get_product( $item->ID );
            $products['data'][$i]['id'] = $item->ID;
            if($product || get_field('approved',$item->ID)){
            if (property_exists($item, 'distance')) {
                $products['data'][$i]['distance'] = $item->distance;
                $products['data'][$i]['latitude'] = get_field('latitude',$item->ID);
                $products['data'][$i]['longitude'] = get_field('longitude',$item->ID);
            }
            $products['data'][$i]['title'] = html_entity_decode(get_the_title($item->ID));
            $products['data'][$i]['image'] = $img[0];
            if($product){
                $products['data'][$i]['type'] = get_field('product_type',$item->ID);
            }else{
                $products['data'][$i]['type'] = 'Showcase';
                $products['data'][$i]['video'] = get_field('url',$item->ID);
                $products['data'][$i]['no_of_tickets'] = get_field('no_of_tickets',$item->ID);
                $products['data'][$i]['tickets_left'] = get_field('tickets_left',$item->ID);
                $products['data'][$i]['sponsors'] = get_field('sponsors',$item->ID);
                $products['data'][$i]['tournament_type'] = get_field('tournament_type',$item->ID);
                $products['data'][$i]['approved'] = get_field('approved',$item->ID);
				$products['data'][$i]['start_date_time']= get_field('start_date_time',$item->ID);
				$products['data'][$i]['end_date_time']= get_field('end_date_time',$item->ID); 
				$products['data'][$i]['latitude']= get_field('latitude',$item->ID);
				$products['data'][$i]['longitude']= get_field('longitude',$item->ID);
				$pan_india = (get_field('pan_india',$item->ID)) ? get_field('pan_india',$item->ID) : 'false';	
				$products['data'][$i]['pan_india']= $pan_india;				
            }
            $products['data'][$i]['address'] = html_entity_decode(get_field('address',$item->ID));
            if($product){
                if( ! $product->is_type('variable') ){
                $products['data'][$i]['price'] = $product->get_price();
                }else{
                $min_price = $product->get_variation_price( 'min' );
                $max_price = $product->get_variation_price( 'max' );
                $products['data'][$i]['min_price'] = $min_price;
                $products['data'][$i]['max_price'] = $max_price;
                }				
				if( $product->is_type('booking') ){
				if ( $product->has_resources()) {
					$resources = $product->get_resources();
					$r = 0;
					foreach ( $resources as $resource ) { 
							$products['data'][$i]['resources'][$r]['id'] = $resource->get_id();
							$products['data'][$i]['resources'][$r]['name'] = $resource->get_name();							
						$r++;
					}
				}
				}
            }}
            $i++;
        }
	// Sort products by title
	
	/* if($data->get_param('search')){
		usort($products['data'], function($a, $b) {
			return strcmp($a['title'], $b['title']);
		});
	} */
        return array('status'=>'success','data'=>$products,'query'=>$query);
    }
	else{
	    return array('status'=>'failure','message'=>'No data is found with requested parameters');
	}
}
function getproductbysports($data){
	$current_page = 1;
	$terms_per_page = 10;
	$latitude = $longitude = $distance = $type = '';
    if( $data) {
        if($data->get_param('page')){
           $current_page = $data->get_param('page');
        }
        if($data->get_param('term_id')){
           $term_id = $data->get_param('term_id');
        }
        if($data->get_param('limit_per_page')){
           $terms_per_page = $data->get_param('limit_per_page');
        }
        if($data->get_param('latitude')){
           $latitude = $data->get_param('latitude');
        }
        if($data->get_param('longitude')){
           $longitude = $data->get_param('longitude');
        }
        if($data->get_param('distance')){
           $distance = $data->get_param('distance');
        }
        if($data->get_param('type')){
           $type = $data->get_param('type');
        }
    }
	$offset = 0;
	if( ! 0 == $current_page) {
		$offset = ( $terms_per_page * $current_page ) - $terms_per_page;
	}
    $taxonomy = 'sport';
    $all_items = query_neighbors($latitude, $longitude, 'product', $distance, $terms_per_page,$offset,$term_id,$taxonomy,'',$type,'');
    $total_items = query_neighbors_count($latitude, $longitude, 'product', $distance, $term_id,$taxonomy,'',$type,'');
    $max_num_pages = ceil( $total_items / $terms_per_page );
    $products = array();
    $products['total_items'] = $total_items;
	    $products['max_num_pages'] = $max_num_pages;
	if($all_items && $total_items > 0){
	    $i = 0;
        foreach($all_items as $item){ 
            $img = wp_get_attachment_image_src(get_post_thumbnail_id($item->ID),'full');
            $product = wc_get_product( $item->ID );
            $products['data'][$i]['id'] = $item->ID;
            if (property_exists($item, 'distance')) {
                $products['data'][$i]['distance'] = $item->distance;
                $products['data'][$i]['latitude'] = get_field('latitude',$item->ID);
                $products['data'][$i]['longitude'] = get_field('longitude',$item->ID);
            }
            $products['data'][$i]['title'] = html_entity_decode(get_the_title($item->ID));
            if($img){
			$products['data'][$i]['image'] = $img[0];
			}
            $products['data'][$i]['type'] = get_field('product_type',$item->ID);
            $products['data'][$i]['address'] = html_entity_decode(get_field('address',$item->ID));
            if( ! $product->is_type('variable') ){
            $products['data'][$i]['price'] = $product->get_price();
            }else{
            $min_price = $product->get_variation_price( 'min' );
            $max_price = $product->get_variation_price( 'max' );
            $products['data'][$i]['min_price'] = $min_price;
            $products['data'][$i]['max_price'] = $max_price;
            }
			if( $product->is_type('booking') ){
			if ( $product->has_resources()) {
				$resources = $product->get_resources();
				$r = 0;
				foreach ( $resources as $resource ) { 
						$products['data'][$i]['resources'][$r]['id'] = $resource->get_id();
						$products['data'][$i]['resources'][$r]['name'] = $resource->get_name();
						$data['id'] = $item->ID;
						$data['year'] = date('Y');
						$data['month'] = date('m');
						$data['day'] = date('d');
						$data['resource_id'] = $resource->get_id();						
						$data['slots_for_days'] = 0;
						if(getpartnerslotsbydate($data)){
							$products['data'][$i]['resources'][$r]['slots'] = getpartnerslotsbydate($data);
						}else{
							$products['data'][$i]['resources'][$r]['slots'] =   'No block is available for '. date('Y - m -d');
						}
					$r++;
				}
			}
			}
            $i++;
        }
        return array('status'=>'success','data'=>$products);
    }
	else{
	    return array('status'=>'failure','message'=>'No data is found with requested parameters');
	}
}
function update_password($data){
	if( $data) {
		$user_id = 0;
        if($data->get_param('user_id') && $data->get_param('password')){
            $user_id = $data->get_param('user_id');
            $password = $data->get_param('password');
            $customer = new WC_Customer( $user_id ); 
            if($customer){
            $customer->set_password( wp_hash_password($password ));
            $customer->save(); 
            wp_set_password($password , $user_id);
            return array('status'=>'success','message'=>'Password updated succesfully.');
            }else{
            return array('status'=>'failure','message'=>'no user found with this id.');
            }
        }else{
            return array('status'=>'failure','message'=>'Missing Parameters.');
        }
	}else{
        return array('status'=>'failure','message'=>'no user found with this id.');
    }
}
function getallshowcase($data){
	if( $data) {
	    $latitude = $longitude = $distance = $type = $search = '';
        $post_per_page = 10;$current_page=1;
        if(($data->get_param('page'))){
        $current_page = $data->get_param('page');
        }
        if(($data->get_param('pagenum'))){
        $current_page = $data->get_param('pagenum');
        }
        if($data->get_param('latitude')){
           $latitude = $data->get_param('latitude');
        }
        if($data->get_param('longitude')){
           $longitude = $data->get_param('longitude');
        }
        if($data->get_param('limit')){
           $post_per_page = $data->get_param('limit');
        }
        if($data->get_param('distance')){
           $distance = $data->get_param('distance');
        }else{
            $distance=10;
        }
		
        if($data->get_param('search')){
           $search = $data->get_param('search');
        }
        $offset = 0; 
        if( ! 0 == $current_page) {
        $offset = ( $post_per_page * $current_page ) - $post_per_page;
        }
        $showcases = query_neighbors($latitude, $longitude, 'showcase', $distance, $post_per_page,$offset,'','','','',$search);
	    $products = array();
	    $total_items = query_neighbors_count($latitude, $longitude, 'showcase', $distance, '','','','',$search);
	    $max_num_pages = ceil( $total_items / $post_per_page );
        $show_data = array();
	    $pagination['total_items'] = $total_items;
	    $pagination['max_num_pages'] = $max_num_pages;
	    $pagination['current_page'] = $current_page;
	    if($showcases && $total_items > 0){
	    $i = 0;
        foreach($showcases as $showcase){ 
			$showcase_id = $showcase->ID;
            $image = wp_get_attachment_image_src( get_post_thumbnail_id($showcase->ID),'full');
            $show_data[$i]['id']= $showcase->ID;
			$show_data[$i]['video']=$show_data[$i]['end_date_time']=$show_data[$i]['start_date_time']=$show_data[$i]['tickets_left']=$show_data[$i]['tournament_type']=$show_data[$i]['image']=$show_data[$i]['sponsors']=$show_data[$i]['end_date_time']=$show_data[$i]['start_date_time']=$show_data[$i]['title']= $show_data[$i]['title']=$show_data[$i]['address']=$show_data[$i]['latitude']=$show_data[$i]['longitude']=$show_data[$i]['no_of_tickets']='';
            if(get_the_title($showcase->ID)){
            $show_data[$i]['title']= html_entity_decode(get_the_title($showcase->ID));
			}
            $show_data[$i]['description']= html_entity_decode(wp_strip_all_tags(get_post_field('post_content',$showcase->ID)));
			
            if(get_field('address',$showcase->ID)){
            $show_data[$i]['address']= html_entity_decode(get_field('address',$showcase->ID));
			}
            if(get_field('latitude',$showcase->ID)){
				$show_data[$i]['latitude']= get_field('latitude',$showcase->ID);
			}
            if(get_field('longitude',$showcase->ID)){
            $show_data[$i]['longitude']= get_field('longitude',$showcase->ID);
			}
            if(get_field('no_of_tickets',$showcase->ID)){
            $show_data[$i]['no_of_tickets']= get_field('no_of_tickets',$showcase->ID);
			}
            if(get_field('tickets_left',$showcase->ID)){
            $show_data[$i]['tickets_left']= get_field('tickets_left',$showcase->ID);
			}
            if(get_field('sponsors',$showcase->ID)){
            $show_data[$i]['sponsors']= get_field('sponsors',$showcase->ID);
			}
            if(get_field('start_date_time',$showcase->ID)){
            $show_data[$i]['start_date_time']= get_field('start_date_time',$showcase->ID);
			}
            if(get_field('end_date_time',$showcase->ID)){
            $show_data[$i]['end_date_time']= get_field('end_date_time',$showcase->ID);
			}
            if($image[0]){
            $show_data[$i]['image']= $image[0];
			}
            if(get_field('tournament_type',$showcase->ID)){
            $show_data[$i]['tournament_type']= get_field('tournament_type',$showcase->ID);
			}
            if(get_field('url',$showcase->ID)){
            $show_data[$i]['video']= get_field('url',$showcase->ID);
			}
			$pan_india = (get_field('pan_india',$showcase->ID)) ? get_field('pan_india',$showcase->ID) : 'false';
            $show_data[$i]['pan_india']= $pan_india;
            $i++;
        }
        return array('status'=>'success','data'=>$show_data,'pagination'=>$pagination);
	    }else{
	        return array('status'=>'success','data'=>'no data found with these Parameters');
	    }
	}else{
        return array('status'=>'failure','message'=>'no data found with this id.');
    }
}
function getpackages($data){ 
	if($data->get_param('id')) {
	    $product_data = array();
        $productId = $data->get_param('id');
        $product = wc_get_product( $productId );
	    if($product){
        	if($product->is_type('variable') && $product->get_status() == 'publish'){         
        	$product_data['data']['currency_symbol'] = html_entity_decode(get_woocommerce_currency_symbol());
        	$product_data['data']['currency'] = get_option( 'woocommerce_currency' );
        	$product_data['data']['id'] = $productId;
        	$product_data['data']['title'] = $product_name = wp_strip_all_tags(html_entity_decode($product->get_name()));
        	$product_data['data']['content'] = wp_strip_all_tags(html_entity_decode($product->get_description()));
        	$product_data['data']['address']= get_field('address',$productId);
        	$product_data['data']['latitude']= get_field('latitude',$productId);
        	$product_data['data']['longitude']= get_field('longitude',$productId);
        	$product_data['data']['contact_no']= get_field('contact_no',$productId);
        	$product_data['data']['head_coach']= get_field('head_coach',$productId);
        	$product_data['data']['session_timings']= get_field('session_timings',$productId);
        	$product_data['data']['skill_level']= get_field('skill_level',$productId);
        	$product_data['data']['flood_lights']= get_field('flood_lights',$productId);
        	$product_data['data']['coach_experience']= get_field('coach_experience',$productId);
        	$product_data['data']['no_of_assistant_coach']= get_field('no_of_assistant_coach',$productId);
        	$product_data['data']['assistant_coach_name']= get_field('assistant_coach_name',$productId);
        	$product_data['data']['partner_id']= get_field('partner_manager',$productId);
        	$amenities = get_field('amenities',$productId);
        	$amenities_with_key = [];
        	$l = 0;
			$site_url = site_url();
            $array = array(
				"Swimming Pool" => "$site_url/wp-content/uploads/2024/05/Swimming.png",
				"PlayGround" => "$site_url/wp-content/uploads/2024/05/Playground.png",
				"CCTV" => "$site_url/wp-content/uploads/2024/05/CCTV1.png",
				"Transportation" => "$site_url/wp-content/uploads/2024/05/Transportation1.png",
				"Online" => "$site_url/wp-content/uploads/2024/05/online-1.png",
				"First Aid" => "$site_url/wp-content/uploads/2024/03/band-aid.png",
				"Flood Lights" => "$site_url/wp-content/uploads/2024/03/stadium.png",
				"Benches & Seating" => "$site_url/wp-content/uploads/2024/03/waiting-room.png",
				"Restrooms" => "$site_url/wp-content/uploads/2024/03/toilet.png",
				"Equipment" => "$site_url/wp-content/uploads/2024/05/Equipment2.png",
				"Cricket Kits" => "$site_url/wp-content/uploads/2024/05/cricket-1.png",
				"Locker" => "$site_url/wp-content/uploads/2024/03/lockers.png",
				"Parking" => "$site_url/wp-content/uploads/2024/03/parking.png",
				"Wifi" => "$site_url/wp-content/uploads/2024/03/wifi.png",
				"Drinking Water" => "$site_url/wp-content/uploads/2024/03/water.png",
				"Recorded Gameplay" => "$site_url/wp-content/uploads/2024/05/CCTV1.png"
			);
            foreach($amenities as $amenity){
                $key = strtolower(str_replace(' ','-',$amenity));
                $key = strtolower(str_replace(array("@","#","!","$","%","&","*","^","(",")"," "),'',$key));
                $key = strtolower(str_replace('--','-',$key));
                $key = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $key);
                $amenities_with_key[$l]['enum_name']  = $key;
                $amenities_with_key[$l]['display_name'] = html_entity_decode($amenity);
				if (array_key_exists($amenity,$array))
				{
					$amenities_with_key[$l]['image'] = $array[$amenity];
				}else{
					$amenities_with_key[$l]['image'] = "$site_url/wp-content/uploads/2024/03/waiting-room.png";
				}
                $l++;
            }
        	$product_data['data']['amenities']= $amenities_with_key;
        	$image_links[0] = get_post_thumbnail_id( $product->get_id() );
        	$img = wp_get_attachment_image_src($image_links[0],'full');
			if($img){
			$product_data['data']['image'] = $img[0];
        	$product_data['data']['gallery'][0] = $img[0];
			}
        	$attachment_ids = $product->get_gallery_image_ids();
        	$k = 1;
        	foreach( $attachment_ids as $attachment_id ) {
        		$product_data['data']['gallery'][$k] = wp_get_attachment_url( $attachment_id );
        		$k++;
        	}
			// $product_data['data']['type'] ;
			
			$type = $product->get_type(); 
            	$variations = $product->get_available_variations();
            	$j = 0;
            	foreach ($variations as $key => $value) {  
					$attributes = $value['attributes'];
					foreach($attributes as $attribute){
                        $attribute_pa_package = $attribute;
                    }
					$variation_id = $value['variation_id'];
					$renewalDate = WC_Subscriptions_Product::get_first_renewal_payment_date( $variation_id, $start_date );	
					$BillingCycle = WC_Subscriptions_Product::get_interval($variation_id);  // how many times within the period to bill 
					$BillingPeriod = WC_Subscriptions_Product::get_period($variation_id);    // Month/week/Year
					$subscription_length = WC_Subscriptions_Product::get_length($variation_id);    // Month/week/Year
            		if($value['variation_is_visible'] == 1 && $value['variation_is_active'] == 1 && $value['is_purchasable'] == 1){
						if($renewalDate){
            			$product_data['data']['packages'][$j]['type'] = $type;
						}else{
            			$product_data['data']['packages'][$j]['type'] = 'variable';
						}
            			$product_data['data']['packages'][$j]['renewalDate'] = $renewalDate;
            			$product_data['data']['packages'][$j]['id'] = $value['variation_id'];
						$variation_product = new WC_Product_Variation($value['variation_id']);
            			$product_data['data']['packages'][$j]['value'] = wp_strip_all_tags(html_entity_decode((str_replace('-',' ',$attribute_pa_package))));
            			$product_data['data']['packages'][$j]['content'] = wp_strip_all_tags(html_entity_decode($value['variation_description']));
            			$product_data['data']['packages'][$j]['name'] = wp_strip_all_tags(html_entity_decode(str_replace($product_name.' -','',$variation_product->get_name())));
            			$product_data['data']['packages'][$j]['regular_price'] = $value['display_regular_price'];
            			$product_data['data']['packages'][$j]['price'] = $value['display_price'];						
						$product_data['data']['packages'][$j]['trial'] = WC_Subscriptions_Product::get_trial_length( $value['variation_id'] );
						if($value['image']){
							if($value['image']['gallery_thumbnail_src']){
							$product_data['data']['packages'][$j]['img'] = $value['image']['gallery_thumbnail_src'];
							}
						}
            			$product_data['data']['packages'][$j]['interval'] = get_post_meta( $value['variation_id'],'_subscription_period_interval', true);
            			$product_data['data']['packages'][$j]['period'] = get_post_meta( $value['variation_id'],'_subscription_period', true);
            			$product_data['data']['packages'][$j]['start_date'] = date( 'Y-m-d H:i:s');
            			$j++;
            		}
            	}
            return $product_data;
        	}else{
			   return array('status'=>'failure','message'=>'No Academy found with this id.'); 
			}
	    }else{
	       return array('status'=>'failure','message'=>'No item found with this id.'); 
	    }
	}else{
	    return array('status'=>'failure','message'=>'Parameter Missing.');
	}
}
function getslots($data){
	if($data->get_param('id')) { 
	    $product_data = array(); 
        $productId = $data->get_param('id');
        $year = $data->get_param('year');
	    $month = $data->get_param('month');
	    $day = $data->get_param('day');
	    $product = wc_get_product( $productId );
	    if($product){  
			if($product->get_status() != 'publish'){
			   return array('status'=>'failure','message'=>'Item is not available for booking.'); 
			}
            $product_data['status'] = 'success';
            $product_data['data']['currency_symbol'] = html_entity_decode(get_woocommerce_currency_symbol());
            $product_data['data']['currency'] = get_option( 'woocommerce_currency' );
            $product_data['data']['id'] = $productId;
            $product_data['data']['title'] = $product->get_name();
            $product_data['data']['content'] = wp_strip_all_tags($product->get_description());
            $product_data['data']['address']= get_field('address',$productId);
            $product_data['data']['latitude']= get_field('latitude',$productId);
            $product_data['data']['longitude']= get_field('longitude',$productId);
            $product_data['data']['latitude']= get_field('latitude',$productId);
            $product_data['data']['partner_id']= get_field('partner_manager',$productId);
            $product_data['data']['flood_lights']= get_field('flood_lights',$productId);
            $amenities = get_field('amenities',$productId);
            $amenities_with_key = [];
            $l = 0;
			$site_url = site_url();
            $array = array(
				"Swimming Pool" => "$site_url/wp-content/uploads/2024/05/Swimming.png",
				"PlayGround" => "$site_url/wp-content/uploads/2024/05/Playground.png",
				"CCTV" => "$site_url/wp-content/uploads/2024/05/CCTV1.png",
				"Transportation" => "$site_url/wp-content/uploads/2024/05/Transportation1.png",
				"Online" => "$site_url/wp-content/uploads/2024/05/online-1.png",
				"First Aid" => "$site_url/wp-content/uploads/2024/03/band-aid.png",
				"Flood Lights" => "$site_url/wp-content/uploads/2024/03/stadium.png",
				"Benches & Seating" => "$site_url/wp-content/uploads/2024/03/waiting-room.png",
				"Restrooms" => "$site_url/wp-content/uploads/2024/03/toilet.png",
				"Equipment" => "$site_url/wp-content/uploads/2024/05/Equipment2.png",
				"Cricket Kits" => "$site_url/wp-content/uploads/2024/05/cricket-1.png",
				"Locker" => "$site_url/wp-content/uploads/2024/03/lockers.png",
				"Parking" => "$site_url/wp-content/uploads/2024/03/parking.png",
				"Wifi" => "$site_url/wp-content/uploads/2024/03/wifi.png",
				"Drinking Water" => "$site_url/wp-content/uploads/2024/03/water.png",
				"Recorded Gameplay" => "$site_url/wp-content/uploads/2024/05/CCTV1.png"
			);
            foreach($amenities as $amenity){
                $key = strtolower(str_replace(' ','-',$amenity));
                $key = strtolower(str_replace(array("@","#","!","$","%","&","*","^","(",")"," "),'',$key));
                $key = strtolower(str_replace('--','-',$key));
                $key = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $key);
                $amenities_with_key[$l]['enum_name']  = $key;
                $amenities_with_key[$l]['display_name'] = html_entity_decode($amenity);
				if (array_key_exists($amenity,$array))
				{
					$amenities_with_key[$l]['image'] = $array[$amenity];
				}else{
					$amenities_with_key[$l]['image'] = "$site_url/wp-content/uploads/2024/03/waiting-room.png";
				}
                $l++;
            }
            $product_data['data']['amenities']= $amenities_with_key;
            $image_links[0] = get_post_thumbnail_id($productId);
            $img = wp_get_attachment_image_src($image_links[0],'full');
            if(is_array($img)){
                $product_data['data']['image'] = $img[0];
                $product_data['data']['gallery'][0] = $img[0];
            }
            $attachment_ids = $product->get_gallery_image_ids();
            $j = 1;
            foreach( $attachment_ids as $attachment_id ) {
                $product_data['data']['gallery'][$j] = wp_get_attachment_url( $attachment_id );
                $j++;
            }
			$when = date('Y-m-d', strtotime(' +1 day'));
            $product_data['data']['type'] = $type = $product->get_type(); 
           if($type === 'booking'){
                $product_data['data']['date'] = $year.'-'.$month.'-'.$day;
                $product_data['data']['slots'] = getslotsbydate($data);
           }
            return $product_data;
	    }else{
	       return array('status'=>'failure','message'=>'No item found with this id.'); 
	    }
	}else{
	    return array('status'=>'failure','message'=>'Parameter Missing.');
	}
}
function getpartnerslotsbydate($data){
    $dates = array();
        if($data){
            if(isset($data['id'])){
        	    $id = $data['id'];
        	    $year = $data['year'];
        	    $month = $data['month'];
        	    $day = $data['day'];
        	    $resource_id = $data['resource_id'];
        	    if($data['slots_for_days']){
            	    $slots_for_days = $data['slots_for_days'];
            	    $dates[] = $date0 = date( 'Y-m-d', strtotime("{$year}-{$month}-{$day}" ) );
            	    for ($x = 1; $x <= $slots_for_days; $x++) {
                        $dates[] = date( 'Y-m-d', strtotime( $date0 . " +{$x} day" ) );
                    }
        	    }else{
        	        $dates[] = date( 'Y-m-d', strtotime("{$year}-{$month}-{$day}" ) );
        	    }
            }else{
        	    $id = $data->get_param('id');
        	    $year = $data->get_param('year');
        	    $month = $data->get_param('month');
        	    $day = $data->get_param('day');
        	    $resource_id = $data->get_param('resource_id');
        	    if($data->get_param('slots_for_days')){
            	    $slots_for_days = $data->get_param('slots_for_days');
            	    $dates[] = $date0 = date( 'Y-m-d', strtotime("{$year}-{$month}-{$day}" ) );
            	    for ($x = 0; $x <= $slots_for_days; $x++) {
                        $dates[] = date( 'Y-m-d', strtotime( $date0 . " +{$x} day" ) );
                    }
        	    }else{
        	        $dates[] = date( 'Y-m-d', strtotime("{$year}-{$month}-{$day}" ) );
        	    }
            }
        }
        if($year && $month && $day && $id){
            foreach($dates as $date){
        	    $posted['add-to-cart'] = $id;
        	    $year = date( 'Y',strtotime($date));
        	    $month = date( 'm',strtotime($date));
        	    $day = date( 'd',strtotime($date));
        	    $posted['wc_bookings_field_start_date_year'] = $year;
        	    $posted['wc_bookings_field_start_date_month'] = $month;
        	    $posted['wc_bookings_field_start_date_day'] = $day;
        	    $posted['wc_bookings_field_resource'] = $resource_id;
        		$booking_id   = absint( $posted['add-to-cart'] );
        		$product = get_wc_product_booking( wc_get_product( $booking_id ) );
        		if ( ! $product ) {
        			return 'No Booking data available.';
        		}
        		if ( ! empty( $posted['wc_bookings_field_start_date_year'] ) && ! empty( $posted['wc_bookings_field_start_date_month'] ) && ! empty( $posted['wc_bookings_field_start_date_day'] ) ) {
        			$year      = max( date( 'Y' ), absint( $posted['wc_bookings_field_start_date_year'] ) );
        			$month     = absint( $posted['wc_bookings_field_start_date_month'] );
        			$day       = absint( $posted['wc_bookings_field_start_date_day'] );
        			$timestamp = strtotime( "{$year}-{$month}-{$day}" );
        		}
        		if ( empty( $timestamp ) ) {
        			return 'Please enter a valid date.';
        		}
        		if ( ! empty( $posted['wc_bookings_field_duration'] ) ) {
        			$interval = (int) $posted['wc_bookings_field_duration'] * $product->get_duration();
        		} else {
        			$interval = $product->get_duration();
        		}
        		$unit = $product->get_duration_unit().'s';
        		$base_interval = $product->get_duration();
        		if ( 'hour' === $product->get_duration_unit() ) {
					$unit = $product->get_duration_unit();
        			$interval      = $interval * 60;
        			$base_interval = $base_interval * 60;
        		}
        		$first_block_time     = $product->get_first_block_time();
        		$from                 = strtotime( $first_block_time ? $first_block_time : 'midnight', $timestamp );
        		$standard_from        = $from;
                if ( isset( $posted['get_prev_day'] ) ) {
        			$from = strtotime( '- 1 day', $from );
        		}
        		$to = strtotime( '+ 1 day', $standard_from ) + $interval;
        		if ( isset( $posted['get_next_day'] ) ) {
        			$to = strtotime( '+ 1 day', $to );
        		}
        		$to = strtotime( 'midnight', $to ) - 1;
        		$resource_id_to_check = ( ! empty( $posted['wc_bookings_field_resource'] ) ? (int) $posted['wc_bookings_field_resource'] : 0 );
        		$resource             = $product->get_resource( absint( $resource_id_to_check ) );
        		$resources            = $product->get_resources();
        		if ( $resource_id_to_check && $resource ) {
        			$resource_id_to_check = $resource->ID;
        		} elseif ( $product->has_resources() && $resources && count( $resources ) === 1 ) {
        			$resource_id_to_check = current( $resources )->ID;
        		} else {
        			$resource_id_to_check = 0;
        		}
        		$booking_form = new WC_Booking_Form( $product ); 
        		$blocks = $product->get_blocks_in_range( $from, $to, array( $interval, $base_interval ), $resource_id_to_check );
                $available_blocks = wc_bookings_get_time_slots( $product, $blocks, array( $interval, $base_interval ), $resource_id_to_check, $from, $to );
        		$k = 0;  
    			foreach ( $available_blocks as $block => $quantity ) {
    				if ( $quantity['available'] > 0 ) { 
    		    		$product    = wc_get_product( $booking_id );
    				    $posted['wc_bookings_field_start_date_time'] = $start_date_time = date('Y-m-d H:i:s',$block);
    				    $booking_data = wc_bookings_get_posted_data( $posted, $product );
                		$cost = WC_Bookings_Cost_Calculation::calculate_booking_cost( $booking_data, $product );
                		if ( is_wp_error( $cost ) ){
                		}else{
    					if ( $quantity['booked']) { 
                            $date_range_blocks[$date][$k]['interval'] = $interval .' '. $unit;
                            $date_range_blocks[$date][$k]['start_date_time'] = $start_date_time;
                            $date_range_blocks[$date][$k]['end_date_time'] = date('Y-m-d H:i:s', strtotime("$start_date_time +$interval $unit"));
                            $date_range_blocks[$date][$k]['slot_cost'] = $cost;
                            $date_range_blocks[$date][$k]['slot_time'] = '(' . sprintf( _n( '%d left', '%d left', $quantity['available'], 'woocommerce-bookings' ), absint( $quantity['available'] ) ) . ')';
                            $date_range_blocks[$date][$k]['slot_start_time'] = date('g:i a', strtotime("$start_date_time"));
                            $date_range_blocks[$date][$k]['slot_end_time'] = date('g:i a', strtotime("$start_date_time +$interval $unit"));
                            $hour = date ("G", strtotime("$start_date_time"));
                            $minute = date ("i", strtotime("$start_date_time"));
                            $second = date ("s", strtotime("$start_date_time"));
                            if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59){
                                $slot_name = "Early Morning Slot ";
                            }else if ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59){
                                $slot_name = "Morning Slot ";
                            }else if ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59){
                                $slot_name = "Mid-Day Slot ";
                            }else if ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59){
                                $slot_name = "Evening Slot ";
                            }else {
                                $slot_name = "Night Slot ";
                            }
                            $date_range_blocks[$date][$k]['slot_name'] = $slot_name;
                            $date_range_blocks[$date][$k]['start'] = date('YmdHis', strtotime("$start_date_time"));
                            $date_range_blocks[$date][$k]['end'] = date('YmdHis', strtotime("$start_date_time +$interval $unit"));
                            $date_range_blocks[$date][$k]['start_timestamp'] = strtotime("$start_date_time");
                            $date_range_blocks[$date][$k]['end_timestamp'] = strtotime("$start_date_time +$interval $unit");
    				        $k++;
    					} else { 
                            $curDateTime = date("Y-m-d H:i:s");
                            $myDate = date("Y-m-d H:i:s", strtotime("$start_date_time"));
                            if($myDate > $curDateTime ){
                                $date_range_blocks[$date][$k]['interval'] = $interval .' '. $unit;
                                $date_range_blocks[$date][$k]['start_date_time'] = $start_date_time;
                                $date_range_blocks[$date][$k]['end_date_time'] = date('Y-m-d H:i:s', strtotime("$start_date_time +$interval $unit"));
                                $date_range_blocks[$date][$k]['slot_cost'] = $cost;
                                $date_range_blocks[$date][$k]['slot_availability'] = date_i18n( wc_bookings_time_format(), $block );
                                $date_range_blocks[$date][$k]['slot_start_time'] = date('g:i a', strtotime("$start_date_time"));
                                $date_range_blocks[$date][$k]['slot_end_time'] = date('g:i a', strtotime("$start_date_time +$interval $unit"));
                                $hour = date ("G", strtotime("$start_date_time"));
                                $minute = date ("i", strtotime("$start_date_time"));
                                $second = date ("s", strtotime("$start_date_time"));
                                if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59){
                                    $slot_name = "Morning Slot ";
                                }else if ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59){
                                    $slot_name = "Morning Slot ";
                                }else if ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59){
                                    $slot_name = "Afternoon Slot ";
                                }else if ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59){
                                    $slot_name = "Evening Slot ";
                                }else {
                                    $slot_name = "Night Slot ";
                                }
                                $date_range_blocks[$date][$k]['slot_name'] = $slot_name;
                                $date_range_blocks[$date][$k]['start'] = date('YmdHis', strtotime("$start_date_time"));
                                $date_range_blocks[$date][$k]['end'] = date('YmdHis', strtotime("$start_date_time +$interval $unit"));
                                $date_range_blocks[$date][$k]['start_timestamp'] = strtotime("$start_date_time");
                                $date_range_blocks[$date][$k]['end_timestamp'] = strtotime("$start_date_time +$interval $unit");
    				            $k++;
                            }
    					}
    				}
    				}
    			} 
            }
		if ( empty( $date_range_blocks ) ) {
			return 'No blocks available.';
		}
	    return $date_range_blocks;
    }else{
	    return 'Parameter Missing.';
	}
}
function getslotsbydate($data){
	    $dates = array();
        if($data){
            if(isset($data['id'])){
        	    $id = $data['id'];				
				$product = wc_get_product( $id );
				if($product){  
					if($product->get_status() != 'publish'){
					   return array('status'=>'failure','message'=>'Item is not available for booking.'); 
				}}else{
					 return array('status'=>'failure','message'=>'Item is not available for booking.'); 
				}
        	    $year = $data['year'];
        	    $month = $data['month'];
        	    $day = $data['day'];
        	    $resource_id = $data['resource_id'];
        	    if($data['slots_for_days']){
            	    $slots_for_days = $data['slots_for_days'];
            	    $dates[] = $date0 = date( 'Y-m-d', strtotime("{$year}-{$month}-{$day}" ) );
            	    for ($x = 1; $x <= $slots_for_days; $x++) {
                        $dates[] = date( 'Y-m-d', strtotime( $date0 . " +{$x} day" ) );
                    }
        	    }else{
        	        $dates[] = date( 'Y-m-d', strtotime("{$year}-{$month}-{$day}" ) );
        	    }
            }else{
        	    $id = $data->get_param('id');
        	    $year = $data->get_param('year');
        	    $month = $data->get_param('month');
        	    $day = $data->get_param('day');
        	    $resource_id = $data->get_param('resource_id');
        	    if($data->get_param('slots_for_days')){
            	    $slots_for_days = $data->get_param('slots_for_days');
            	    $dates[] = $date0 = date( 'Y-m-d', strtotime("{$year}-{$month}-{$day}" ) );
            	    for ($x = 0; $x <= $slots_for_days; $x++) {
                        $dates[] = date( 'Y-m-d', strtotime( $date0 . " +{$x} day" ) );
                    }
        	    }else{
        	        $dates[] = date( 'Y-m-d', strtotime("{$year}-{$month}-{$day}" ) );
        	    }
            }
        }
        if($year && $month && $day && $id){
            foreach($dates as $date){
        	    $posted['add-to-cart'] = $id;
        	    $year = date( 'Y',strtotime($date));
        	    $month = date( 'm',strtotime($date));
        	    $day = date( 'd',strtotime($date));
        	    $posted['wc_bookings_field_start_date_year'] = $year;
        	    $posted['wc_bookings_field_start_date_month'] = $month;
        	    $posted['wc_bookings_field_start_date_day'] = $day;
        	    $posted['wc_bookings_field_resource'] = $resource_id;
        		$booking_id   = absint( $posted['add-to-cart'] );
        		$product = get_wc_product_booking( wc_get_product( $booking_id ) );
        		if ( ! $product ) {
        			return 'No Booking data available.';
        		}
        		if ( ! empty( $posted['wc_bookings_field_start_date_year'] ) && ! empty( $posted['wc_bookings_field_start_date_month'] ) && ! empty( $posted['wc_bookings_field_start_date_day'] ) ) {
        			$year      = max( date( 'Y' ), absint( $posted['wc_bookings_field_start_date_year'] ) );
        			$month     = absint( $posted['wc_bookings_field_start_date_month'] );
        			$day       = absint( $posted['wc_bookings_field_start_date_day'] );
        			$timestamp = strtotime( "{$year}-{$month}-{$day}" );
        		}
        		if ( empty( $timestamp ) ) {
        			return 'Please enter a valid date.';
        		}
        		if ( ! empty( $posted['wc_bookings_field_duration'] ) ) {
        			$interval = (int) $posted['wc_bookings_field_duration'] * $product->get_duration();
        		} else {
        			$interval = $product->get_duration();
        		}
        		$unit = $product->get_duration_unit().'s';
        		$base_interval = $product->get_duration();
        		if ( 'hour' === $product->get_duration_unit() ) {
					$unit = $product->get_duration_unit();
        			/* $interval      = $interval * 60;
        			$base_interval = $base_interval * 60; */
        		}
        		$first_block_time     = $product->get_first_block_time();
        		$from                 = strtotime( $first_block_time ? $first_block_time : 'midnight', $timestamp );
        		$standard_from        = $from;
                if ( isset( $posted['get_prev_day'] ) ) {
        			$from = strtotime( '- 1 day', $from );
        		}
        		$to = strtotime( '+ 1 day', $standard_from ) + $interval;
        		if ( isset( $posted['get_next_day'] ) ) {
        			$to = strtotime( '+ 1 day', $to );
        		}
        		$to = strtotime( 'midnight', $to ) - 1;
        		$resource_id_to_check = ( ! empty( $posted['wc_bookings_field_resource'] ) ? (int) $posted['wc_bookings_field_resource'] : 0 );
        		$resource             = $product->get_resource( absint( $resource_id_to_check ) );
        		$resources            = $product->get_resources();
        		if ( $resource_id_to_check && $resource ) {
        			$resource_id_to_check = $resource->ID;
        		} elseif ( $product->has_resources() && $resources && count( $resources ) === 1 ) {
        			$resource_id_to_check = current( $resources )->ID;
        		} else {
        			$resource_id_to_check = 0;
        		}
        		$booking_form = new WC_Booking_Form( $product ); 
        		$blocks = $product->get_blocks_in_range( $from, $to, array( $interval, $base_interval ), $resource_id_to_check );
                $available_blocks = wc_bookings_get_time_slots( $product, $blocks, array( $interval, $base_interval ), $resource_id_to_check, $from, $to );
        		$k = 0;  
    			foreach ( $available_blocks as $block => $quantity ) {
    				if ( $quantity['available'] > 0 ) { 
    		    		$product    = wc_get_product( $booking_id );
    				    $posted['wc_bookings_field_start_date_time'] = $start_date_time = date('Y-m-d H:i:s',$block);
    				    $booking_data = wc_bookings_get_posted_data( $posted, $product );
                		$cost = WC_Bookings_Cost_Calculation::calculate_booking_cost( $booking_data, $product );
                		if ( is_wp_error( $cost ) ){
                		}else{
    					if ( $quantity['booked']) { 
                            $date_range_blocks[$date][$k]['interval'] = $interval .' '. $unit;
                            $date_range_blocks[$date][$k]['start_date_time'] = $start_date_time;
                            $date_range_blocks[$date][$k]['end_date_time'] = date('Y-m-d H:i:s', strtotime("$start_date_time +$interval $unit"));
                            $date_range_blocks[$date][$k]['slot_cost'] = $cost;
                            $date_range_blocks[$date][$k]['slot_time'] = '(' . sprintf( _n( '%d left', '%d left', $quantity['available'], 'woocommerce-bookings' ), absint( $quantity['available'] ) ) . ')';
                            $date_range_blocks[$date][$k]['slot_start_time'] = date('g:i a', strtotime("$start_date_time"));
                            $date_range_blocks[$date][$k]['slot_end_time'] = date('g:i a', strtotime("$start_date_time +$interval $unit"));
                            $hour = date ("G", strtotime("$start_date_time"));
                            $minute = date ("i", strtotime("$start_date_time"));
                            $second = date ("s", strtotime("$start_date_time"));
                            if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59){
                                $slot_name = "Early Morning Slot ";
                            }else if ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59){
                                $slot_name = "Morning Slot ";
                            }else if ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59){
                                $slot_name = "Mid-Day Slot ";
                            }else if ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59){
                                $slot_name = "Evening Slot ";
                            }else {
                                $slot_name = "Night Slot ";
                            }
                            $date_range_blocks[$date][$k]['slot_name'] = $slot_name;
                            $date_range_blocks[$date][$k]['start'] = date('YmdHis', strtotime("$start_date_time"));
                            $date_range_blocks[$date][$k]['end'] = date('YmdHis', strtotime("$start_date_time +$interval $unit"));
                            $date_range_blocks[$date][$k]['start_timestamp'] = strtotime("$start_date_time");
                            $date_range_blocks[$date][$k]['end_timestamp'] = strtotime("$start_date_time +$interval $unit");
    				        $k++;
    					} else { 
                            $curDateTime = date("Y-m-d H:i:s");
                            $myDate = date("Y-m-d H:i:s", strtotime("$start_date_time"));
                            if($myDate > $curDateTime ){
                                $date_range_blocks[$date][$k]['interval'] = $interval .' '. $unit;
                                $date_range_blocks[$date][$k]['start_date_time'] = $start_date_time;
                                $date_range_blocks[$date][$k]['end_date_time'] = date('Y-m-d H:i:s', strtotime("$start_date_time +$interval $unit"));
                                $date_range_blocks[$date][$k]['slot_cost'] = $cost;
                                $date_range_blocks[$date][$k]['slot_availability'] = date_i18n( wc_bookings_time_format(), $block );
                                $date_range_blocks[$date][$k]['slot_start_time'] = date('g:i a', strtotime("$start_date_time"));
                                $date_range_blocks[$date][$k]['slot_end_time'] = date('g:i a', strtotime("$start_date_time +$interval $unit"));
                                $hour = date ("G", strtotime("$start_date_time"));
                                $minute = date ("i", strtotime("$start_date_time"));
                                $second = date ("s", strtotime("$start_date_time"));
                                if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59){
                                    $slot_name = "Morning Slot ";
                                }else if ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59){
                                    $slot_name = "Morning Slot ";
                                }else if ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59){
                                    $slot_name = "Afternoon Slot ";
                                }else if ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59){
                                    $slot_name = "Evening Slot ";
                                }else {
                                    $slot_name = "Night Slot ";
                                }
                                $date_range_blocks[$date][$k]['slot_name'] = $slot_name;
                                $date_range_blocks[$date][$k]['start'] = date('YmdHis', strtotime("$start_date_time"));
                                $date_range_blocks[$date][$k]['end'] = date('YmdHis', strtotime("$start_date_time +$interval $unit"));
                                $date_range_blocks[$date][$k]['start_timestamp'] = strtotime("$start_date_time");
                                $date_range_blocks[$date][$k]['end_timestamp'] = strtotime("$start_date_time +$interval $unit");
    				            $k++;
                            }
    					}
    				}
    				}
    			} 
            }
		if ( empty( $date_range_blocks ) ) { 
			return 'No blocks available.';
		}
	    return $date_range_blocks;
    }else{
	    return 'Parameter Missing.';
	}
}
function getBookables( $pid, $when, $type, $bookable = 'yes' ) { 
    $result = array();
    $product = wc_get_product( $pid );
    if ( 'custom' == $type ) {
        $when = DateTime::createFromFormat( 'Y-m-d', $when );
    }
    $availability = get_post_meta( $pid, '_wc_booking_availability' ); 
    $interval = $duration = $base_interval = $product->get_duration();
    $type = $product->get_duration_type();
    $unit = $product->get_duration_unit();
    foreach ( $availability[0] as $a ) {
        if ( $a[ 'bookable' ] == $bookable ) { 
                if ( $when instanceof DateTime ) {
                    $from = DateTime::createFromFormat( 'Y-m-d', $a[ 'from' ] );
                    $to   = DateTime::createFromFormat( 'Y-m-d', $a[ 'to'   ] );
                    if ( $when >= $from && $when >= $to ) {
                        $result[] = $a;
                    }
		            $booking_form = new WC_Booking_Form( $product );
                    $blocks       = $product->get_blocks_in_range( $from, $to, array( $interval, $base_interval ), 0 );
                    $available_blocks = $booking_form->get_time_slots_html( $product, $blocks, $duration, 0, $from, $to );
                } else {  
                    $from = DateTime::createFromFormat( 'Y-m-d', $a[ 'from' ] );
                    $to   = DateTime::createFromFormat( 'Y-m-d', $a[ 'to'   ] );
                    if ( $type == $a[ 'type' ] && ( $when >= $from && $when >= $to ) ) {
                        $result[] = $a;
                    }
            }
        }
    }
    return $result;
}
function getcustomervenueorders($data){
    if($data) {
		$order_count = 0;
        $user = $data->get_param('user');
		$count_query = wc_get_orders(array(
			'return' => 'ids',
			'customer_id' => $user,
			'limit'   => -1,
			'meta_query' =>  array(
		        'relation' => 'AND',
                array('key' => 'tida_order_type',
                      'value' => 'booking', 
                      'compare' => 'LIKE',
                ),
				/* array('key' => '_booking_id',
                      'value' => '', 
                      'compare' => '!=',
                ) */
				)
		));
		$order_count = count( $count_query);
		if($data->get_param('limit_per_page')){
			$order_per_page = $data->get_param('limit_per_page');
		}else{
			$order_per_page = 10;
		}
		$max_num_pages = ceil( $order_count / $order_per_page );
		if($data->get_param('page')){
			$current_page = $data->get_param('page');
		}else{
			$current_page = 1;
		}
		$offset = 0; 
		if( ! 0 == $current_page) {
			$offset = ( $order_per_page * $current_page ) - $order_per_page;
		}
		$query = new WC_Order_Query(array(
			'limit' => $order_per_page,
			'orderby' => 'date',
			'order' => 'DESC',
			'return' => 'ids',
			'customer_id' => $user,
			'number'   => $order_per_page,
			'offset'   => $offset,
			'meta_query' => array(
		        'relation' => 'AND',
                array('key' => 'tida_order_type',
                      'value' => 'booking', 
                      'compare' => 'LIKE',
                ),
				/* array('key' => '_booking_id',
                      'value' => '', 
                      'compare' => '!=',
                ) */
				)
		));
		$orders = array();
		$c_orders = $query->get_orders();
		$orders['total_orders']  = $order_count;
		$orders['max_pages']  = $max_num_pages;
		$orders['current_page'] = $current_page;
		$j=0;
		foreach($c_orders as $order_id){
			$order = wc_get_order( $order_id );
			$order_items = $order->get_items();
			$orders['data'][$j]['id']  = $order_id;
		    $orders['data'][$j]['status'] = $order->get_status();
		    $orders['data'][$j]['currency'] = $order->get_currency();
		    $orders['data'][$j]['created_date'] = $order->get_date_created();
			if($order->get_status() == 'failed'){
				$orders['data'][$j]['transaction_id']= 'Failed';
			}else if($order->get_status() == 'cancelled'){
				$orders['data'][$j]['transaction_id']= 'Cancelled';
			}else{
				if($order->get_transaction_id()){
					$orders['data'][$j]['transaction_id']= $order->get_transaction_id();
				}else if($order->get_meta('transaction_id')){
					$orders['data'][$j]['transaction_id']= $order->get_meta('transaction_id');
				}else{
					$orders['data'][$j]['transaction_id']= '';
				}
			}			
			if($order->get_payment_method() == 'cod' || $order->get_payment_method() == 'cheque'){
				$orders['data'][$j]['transaction_type']= 'offline';
			}else{
				$orders['data'][$j]['transaction_type']= 'online';
			}
			if($order->get_coupon_codes()){
				$orders['data'][$j]['discount_price']= $order->get_discount_total();
				$orders['data'][$j]['total']= $order->get_total() + $order->get_discount_total();
				$orders['data'][$j]['total_discounted_amount']= $order->get_total();
				$co = 0;
				foreach( $order->get_coupon_codes() as $coupon_code ) {
					$coupon = new WC_Coupon($coupon_code);
					$orders['data'][$j]['coupon'][$co]['code']= $coupon_code;
					$orders['data'][$j]['coupon'][$co]['amount']= $coupon->get_amount();
					$orders['data'][$j]['coupon'][$co]['discount_type']= $coupon->get_discount_type();
					$co++;
				}
			}else{
				$orders['data'][$j]['total'] = $order->get_total();
			}
			if(get_field('feedback',$order_id)){
				$feedback = get_field('feedback',$order_id);
			}else if(get_post_meta($order->get_id(), 'feedback', true)){
				$feedback = get_post_meta($order->get_id(), 'feedback', true);
			}else if($order->get_meta('feedback')){
				$feedback = $order->get_meta('feedback');
			}else{
				$feedback = '';
			}
			if($feedback){
				$orders['data'][$j]['feedback_submitted']= true;
				$orders['data'][$j]['feedback']= $feedback;
			}else{
				$orders['data'][$j]['feedback_submitted']= false;
			}
			$i=0;
			$order_price = 0;
        	foreach ( $order_items as $item_id => $item ) {
        		$product = wc_get_product($item['product_id']);
        		$type = $product->get_type();
        		$productId = $product->get_id();
        		$partner_id = get_field('partner_manager',$productId);
        		$user = get_user_by('id', $partner_id);
                $partner_name = $user->display_name;
			if(!($order->get_meta('partner_id') )) { 
				$order->update_meta_data( 'partner_id' , $partner_id ) ;
				$order->save();
			}
			if( $product->get_type() === 'booking' ) {
				$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
				foreach( $booking_ids as $booking_id ) { 
					$booking = new WC_Booking($booking_id);
				if(!($booking->get_meta('partner_id') )) { 
					$booking->update_meta_data( 'partner_id' , $partner_id ) ;
					$booking->save();
				}
				}
			}

                if(get_field('phone_number','user_'.$partner_id)){
                    $partner_phone = get_field('phone_number','user_'.$partner_id);
                }else if(get_field('billing_phone','user_'.$partner_id)){
                    $partner_phone = get_field('billing_phone','user_'.$partner_id);
                }else if(get_user_meta($partner_id,'phone_number')) {
                    $partner_phone = get_user_meta($partner_id,'phone_number',true);
				}else if(get_user_meta($partner_id,'billing_phone')) {
                    $partner_phone = get_user_meta($partner_id,'billing_phone',true);
				}else{
                    $partner_phone = 'No Data';
                }
                if(get_field('person_name',$order_id)){
                    $person_name = get_field('person_name',$order_id);
                }else if(get_post_meta($order->get_id(), 'person_name', true)){
					$person_name = get_post_meta($order->get_id(), 'person_name', true);
				}else if(get_post_meta($order->get_id(), '_wc_order_for_name', true)){
					$person_name = get_post_meta($order->get_id(), '_wc_order_for_name', true);
				}else if($order->get_meta('_wc_order_for_name')){
					$person_name = $order->get_meta('_wc_order_for_name');
				}else if($order->get_meta('person_name')){
					$person_name = $order->get_meta('person_name');
				}else if(get_field('_wc_order_for_name',$order_id)){
                    $person_name = get_field('_wc_order_for_name',$order_id);
                }else{
					$customer = get_userdata($order->customer_user);
					$person_name = $customer->display_name;
                    /* $person_name = 'No Data'; */
                }
        		if($type == 'booking'){
				}
        		    $orders['data'][$j]['items'][$i]['name']= $product->get_title();
        		    $orders['data'][$j]['items'][$i]['address']= get_field('address',$productId);
        		    $orders['data'][$j]['items'][$i]['person_name']=  $person_name;
        		    $orders['data'][$j]['items'][$i]['partner_name']= ($partner_name) ? $partner_name : 'No Data';
        		    $orders['data'][$j]['items'][$i]['partner_phone']= ($partner_phone) ? $partner_phone : 'No Data';
		            $orders['data'][$j]['items'][$i]['booking_id'] = $booking_id = $item->get_meta('_booking_id');
		            $booking = get_wc_booking( $booking_id );
                	$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
                    if ( $booking_ids ) {
                        $k = 0;
                        foreach ( $booking_ids as $booking_id ) {
                            $booking       = new WC_Booking( $booking_id );
                            $booking_start = date( 'Y-m-d', $booking->get_start());
                            $booking_end   = date( 'Y-m-d', $booking->get_end());
                            $hour = date ("G", $booking->get_start());
                            $minute = date ("i", $booking->get_start());
                            $second = date ("s", $booking->get_start());
                            if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59){
                                $slot_name = "Early Morning Slot ";
                            }else if ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59){
                                $slot_name = "Morning Slot ";
                            }else if ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59){
                                $slot_name = "Mid-Day Slot ";
                            }else if ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59){
                                $slot_name = "Evening Slot ";
                            }else {
                                $slot_name = "Night Slot ";
                            }
							if(get_post_meta($booking_id, 'booking_cost', true)){
								$orders['data'][$j]['items']['slots'][$k]['slot_amount'] = intval(get_post_meta($booking_id, 'booking_cost', true));
							}else{
								$orders['data'][$j]['items']['slots'][$k]['slot_amount'] = $order->get_line_subtotal( $item ); 
							}
                		    $orders['data'][$j]['items']['slots'][$k]['slot_start_date']= $booking_start;
                		    $orders['data'][$j]['items']['slots'][$k]['slot_end_date']= $booking_end;
                		    $orders['data'][$j]['items']['slots'][$k]['slot_start_time']= date("g:i a",$booking->get_start());
                		    $orders['data'][$j]['items']['slots'][$k]['slot_end_time']= date("g:i a",$booking->get_end());
        		            $orders['data'][$j]['items']['slot'][$k]['slot_name']= $slot_name;
                        	$k++;
                        }
        		    $i++;
        		}else{
					$j--;
				}
        	}
		    $j++;
		}
		return $orders;
	}else{
		return 'no customer exist with this email.';
	}
}
function getcustomersubscriptionorders($data){
    if($data) {
		$order_count = 0;
        $user = $data->get_param('user');
		$count_query = wc_get_orders(array(
			'return' => 'ids',
			'customer_id' => $user,
			'limit'   => -1,
			'meta_query' => array(
		        'relation' => 'OR',
                array('key' => 'tida_order_type',
                      'value' => 'subscription_variation', 
                      'compare' => 'LIKE',
                ),
                array('key' => 'tida_order_type',
                      'value' => 'variable-subscription', 
                      'compare' => 'LIKE',
                )
            )
		));
		$order_count = count( $count_query);
		$order_per_page = 10;
		$max_num_pages = ceil( $order_count / $order_per_page );
		if($data->get_param('page')){
			$current_page = $data->get_param('page');
		}else{
			$current_page = 1;
		}
		$offset = 0;
		if( ! 0 == $current_page) {
			$offset = ( $order_per_page * $current_page ) - $order_per_page;
		}
		$query = new WC_Order_Query(array(
			'limit' => $order_per_page,
			'orderby' => 'date',
			'order' => 'DESC',
			'return' => 'ids',
			'customer_id' => $user,
			'number'   => $order_per_page,
			'offset'   => $offset,
			'meta_query' => array(
		        'relation' => 'OR',
                array('key' => 'tida_order_type',
                      'value' => 'subscription_variation', 
                      'compare' => 'LIKE',
                ),
                array('key' => 'tida_order_type',
                      'value' => 'variable-subscription', 
                      'compare' => 'LIKE',
                )
            )
		));
		$orders = array();
		$c_orders = $query->get_orders();
		$orders['total_orders']  = $order_count;
		$orders['max_pages']  = $max_num_pages;
		$orders['current_page'] = $current_page;
		$j=0;
		foreach($c_orders as $order_id){
			$order = wc_get_order( $order_id );
			$order_items = $order->get_items();
			$orders['data'][$j]['id']  = $order_id;
		    $orders['data'][$j]['status'] = $order->get_status();
		    $orders['data'][$j]['created_via'] = $order->get_created_via();
		    $orders['data'][$j]['currency'] = $order->get_currency();
		    $orders['data'][$j]['created_date'] = $order->get_date_created();
			if($order->get_status() == 'failed'){
				$orders['data'][$j]['transaction_id']= 'Failed';
			}else if($order->get_status() == 'cancelled'){
				$orders['data'][$j]['transaction_id']= 'Cancelled';
			}else{
				if($order->get_transaction_id()){
					$orders['data'][$j]['transaction_id']= $order->get_transaction_id();
				}else if($order->get_meta('transaction_id')){
					$orders['data'][$j]['transaction_id']= $order->get_meta('transaction_id');
				}else{
					$orders['data'][$j]['transaction_id']= '';
				}
			}			
			if($order->get_payment_method() == 'cod' || $order->get_payment_method() == 'cheque'){
				$orders['data'][$j]['transaction_type']= 'offline';
			}else{
				$orders['data'][$j]['transaction_type']= 'online';
			}
			if($order->get_coupon_codes()){
				$orders['data'][$j]['discount_price']= $order->get_discount_total();
				$orders['data'][$j]['total']= $order->get_total() + $order->get_discount_total();
				$orders['data'][$j]['total_discounted_amount']= $order->get_total();
				$co = 0;
				foreach( $order->get_coupon_codes() as $coupon_code ) {
					$coupon = new WC_Coupon($coupon_code);
					$orders['data'][$j]['coupon'][$co]['code']= $coupon_code;
					$orders['data'][$j]['coupon'][$co]['amount']= $coupon->get_amount();
					$orders['data'][$j]['coupon'][$co]['discount_type']= $coupon->get_discount_type();
					$co++;
				}
			}else{
				$orders['data'][$j]['total'] = $order->get_total();
			}
		    $orders['data'][$j]['subscriptions_ids'] = $subscriptions_ids = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
			$i=0;
            foreach( $subscriptions_ids as $subscription_id => $subscription ){
                if ( WC_Subscriptions_Synchroniser::subscription_contains_synced_product( $subscription_id ) ) {
                    $length_from_timestamp = $subscription->get_time( 'next_payment' );
                }else {
                    $length_from_timestamp = $subscription->get_time( 'start' );
                }
                $subscription_length = wcs_estimate_periods_between( $length_from_timestamp, $subscription->get_time( 'end' ), $subscription->get_billing_period() );
                $orders['data'][$j]['subscriptions'][$i]['id'] = $subscription_id;
                $orders['data'][$j]['subscriptions'][$i]['trial_end'] = $subscription->get_date( 'trial_end' );
                $orders['data'][$j]['subscriptions'][$i]['total'] = $subscription->get_total();
				if($subscription->get_time( 'next_payment' )){
					$orders['data'][$j]['subscriptions'][$i]['next_payment'] = date('d-m-Y',$subscription->get_time( 'next_payment' ));
				}else{
					$orders['data'][$j]['subscriptions'][$i]['next_payment'] = 'No renewal available';
				}
                $orders['data'][$j]['subscriptions'][$i]['start'] = date('d-m-Y',$subscription->get_time( 'start' ));
                $orders['data'][$j]['subscriptions'][$i]['subscription_length'] = $subscription_length;
                $i++;
            }
			if(get_field('feedback',$order_id)){
				$feedback = get_field('feedback',$order_id);
			}else if(get_post_meta($order->get_id(), 'feedback', true)){
				$feedback = get_post_meta($order->get_id(), 'feedback', true);
			}else if($order->get_meta('feedback')){
				$feedback = $order->get_meta('feedback');
			}else{
				$feedback = '';
			}
			if($feedback){
				$orders['data'][$j]['feedback_submitted']= true;
				$orders['data'][$j]['feedback']= $feedback;
			}else{
				$orders['data'][$j]['feedback_submitted']= false;
			}        		    
			$i=0;
        	foreach ( $order_items as $item_id => $item ) {
        		$product = wc_get_product($item['product_id']);
        		$type = $product->get_type();
        		$productId = $product->get_id();
        	    $variation_id = $item['variation_id'];
        		$orders['data'][$j]['items'][$i]['type'] = $type = $product->get_type();
        		$variation = wc_get_product($variation_id);
        		$partner_id = get_field('partner_manager',$productId);
        		$user = get_user_by('id', $partner_id);
                $partner_name = $user->display_name;
                if(get_field('phone_number','user_'.$partner_id)){
                    $partner_phone = get_field('phone_number','user_'.$partner_id);
                }else if(get_field('billing_phone','user_'.$partner_id)){
                    $partner_phone = get_field('billing_phone','user_'.$partner_id);
                }else if(get_user_meta($partner_id,'phone_number')) {
                    $partner_phone = get_user_meta($partner_id,'phone_number',true);
				}else if(get_user_meta($partner_id,'billing_phone')) {
                    $partner_phone = get_user_meta($partner_id,'billing_phone',true);
				}else{
                    $partner_phone = 'No Data';
                }
                if(get_field('person_name',$order_id)){
                    $person_name = get_field('person_name',$order_id);
                }else if(get_post_meta($order->get_id(), 'person_name', true)){
					$person_name = get_post_meta($order->get_id(), 'person_name', true);
				}else if(get_post_meta($order->get_id(), '_wc_order_for_name', true)){
					$person_name = get_post_meta($order->get_id(), '_wc_order_for_name', true);
				}else if($order->get_meta('_wc_order_for_name')){
					$person_name = $order->get_meta('_wc_order_for_name');
				}else if($order->get_meta('person_name')){
					$person_name = $order->get_meta('person_name');
				}else if(get_field('_wc_order_for_name',$order_id)){
                    $person_name = get_field('_wc_order_for_name',$order_id);
                }else{
					$customer = get_userdata($order->customer_user);
					$person_name = $customer->display_name;
                    /* $person_name = 'No Data'; */
                }
        		if($type == 'subscription_variation'){
        		}
        		    $orders['data'][$j]['items'][$i]['academy_id']= $productId;
        		    $orders['data'][$j]['items'][$i]['academy_name']= wp_strip_all_tags(html_entity_decode($product->get_title()));
        		    $orders['data'][$j]['items'][$i]['academy_address']= get_field('address',$productId);
        		    $orders['data'][$j]['items'][$i]['package_id']= $variation_id;
        		    $orders['data'][$j]['items'][$i]['package_name']= wp_strip_all_tags(html_entity_decode($variation->get_name()));
        		    $orders['data'][$j]['items'][$i]['package_amount']= $variation->get_price();
        		    $orders['data'][$j]['items'][$i]['person_name']= $person_name;
        		    $orders['data'][$j]['items'][$i]['partner_name']= ($partner_name) ? $partner_name : 'No Data';
        		    $orders['data'][$j]['items'][$i]['partner_phone']= ($partner_phone) ? $partner_phone : 'No Data';
        		    $i++;
        	} 
        	$j++;
		}
		return $orders;
	}else{
		return 'no customer exist with this email.';
	}
}
function getcustomeracademyorders($data){
    if($data) {
		$order_count = 0;
        $user = $data->get_param('user');
		$count_query = wc_get_orders(array(
			'return' => 'ids',
			'customer_id' => $user,
			'limit'   => -1,
			'meta_query' => array(
			    'relation' => 'OR',
                array('key' => 'tida_order_type',
                      'value' => ', variation', 
                      'compare' => 'LIKE',
            ), array('key' => 'tida_order_type',
                      'value' => 'variation', 
                      'compare' => '=',
            ))
		));
		$order_count = count( $count_query);
		$order_per_page = 10;
		$max_num_pages = ceil( $order_count / $order_per_page );
		if($data->get_param('page')){
			$current_page = $data->get_param('page');
		}else{
			$current_page = 1;
		}
		$offset = 0;
		if( ! 0 == $current_page) {
			$offset = ( $order_per_page * $current_page ) - $order_per_page;
		}
		$query = new WC_Order_Query(array(
			'limit' => $order_per_page,
			'orderby' => 'date',
			'order' => 'DESC',
			'return' => 'ids',
			'customer_id' => $user,
			'number'   => $order_per_page,
			'offset'   => $offset,
			'meta_query' => array(
			    'relation' => 'OR',
                array('key' => 'tida_order_type',
                      'value' => ', variation', 
                      'compare' => 'LIKE',
            ), array('key' => 'tida_order_type',
                      'value' => 'variation', 
                      'compare' => '=',
            ))
		));
		$orders = array();
		$c_orders = $query->get_orders();
		$orders['total_orders']  = $order_count;
		$orders['max_pages']  = $max_num_pages;
		$orders['current_page'] = $current_page;
		$j=0;
		foreach($c_orders as $order_id){
			$order = wc_get_order( $order_id );
			$order_items = $order->get_items();
			$orders['data'][$j]['id']  = $order_id;
		    $orders['data'][$j]['status'] = $order->get_status();
		    $orders['data'][$j]['currency'] = $order->get_currency();
		    $orders['data'][$j]['created_date'] = $order->get_date_created();
		    $orders['data'][$j]['phone_number']= $order->get_billing_phone();
			if($order->get_status() == 'failed'){
				$orders['data'][$j]['transaction_id']= 'Failed';
			}else if($order->get_status() == 'cancelled'){
				$orders['data'][$j]['transaction_id']= 'Cancelled';
			}else{
				if($order->get_transaction_id()){
					$orders['data'][$j]['transaction_id']= $order->get_transaction_id();
				}else if($order->get_meta('transaction_id')){
					$orders['data'][$j]['transaction_id']= $order->get_meta('transaction_id');
				}else{
					$orders['data'][$j]['transaction_id']= '';
				}
			}			
			if($order->get_payment_method() == 'cod' || $order->get_payment_method() == 'cheque'){
				$orders['data'][$j]['transaction_type']= 'offline';
			}else{
				$orders['data'][$j]['transaction_type']= 'online';
			}
			if($order->get_coupon_codes()){
				$orders['data'][$j]['discount_price']= $order->get_discount_total();
				$orders['data'][$j]['total']= $order->get_total() + $order->get_discount_total();
				$orders['data'][$j]['total_discounted_amount']= $order->get_total();
				$co = 0;
				foreach( $order->get_coupon_codes() as $coupon_code ) {
					$coupon = new WC_Coupon($coupon_code);
					$orders['data'][$j]['coupon'][$co]['code']= $coupon_code;
					$orders['data'][$j]['coupon'][$co]['amount']= $coupon->get_amount();
					$orders['data'][$j]['coupon'][$co]['discount_type']= $coupon->get_discount_type();
					$co++;
				}
			}else{
				$orders['data'][$j]['total'] = $order->get_total();
			}
			if(get_field('feedback',$order_id)){
				$feedback = get_field('feedback',$order_id);
			}else if(get_post_meta($order->get_id(), 'feedback', true)){
				$feedback = get_post_meta($order->get_id(), 'feedback', true);
			}else if($order->get_meta('feedback')){
				$feedback = $order->get_meta('feedback');
			}else{
				$feedback = '';
			}
			if($feedback){
				$orders['data'][$j]['feedback_submitted']= true;
				$orders['data'][$j]['feedback']= $feedback;
			}else{
				$orders['data'][$j]['feedback_submitted']= false;
			}
			$i=0;
        	foreach ( $order_items as $item_id => $item ) { 
        	    $variation_id = $item['variation_id'];
        		$product = wc_get_product($item['product_id']);
    		    $orders['data'][$j]['items'][$i]['type'] = $type = $product->get_type();
        		$productId = $product->get_id();
        		$variation = wc_get_product($variation_id);
        		$partner_id = get_field('partner_manager',$productId);
        		$user = get_user_by('id', $partner_id);
                $partner_name = $user->display_name;
                if(get_field('phone_number','user_'.$partner_id)){
                    $partner_phone = get_field('phone_number','user_'.$partner_id);
                }else if(get_field('billing_phone','user_'.$partner_id)){
                    $partner_phone = get_field('billing_phone','user_'.$partner_id);
                }else if(get_user_meta($partner_id,'phone_number')) {
                    $partner_phone = get_user_meta($partner_id,'phone_number',true);
				}else if(get_user_meta($partner_id,'billing_phone')) {
                    $partner_phone = get_user_meta($partner_id,'billing_phone',true);
				}else{
                    $partner_phone = 'No Data';
                }
                if(get_field('person_name',$order_id)){
                    $person_name = get_field('person_name',$order_id);
                }else if(get_post_meta($order->get_id(), 'person_name', true)){
					$person_name = get_post_meta($order->get_id(), 'person_name', true);
				}else if(get_post_meta($order->get_id(), '_wc_order_for_name', true)){
					$person_name = get_post_meta($order->get_id(), '_wc_order_for_name', true);
				}else if($order->get_meta('_wc_order_for_name')){
					$person_name = $order->get_meta('_wc_order_for_name');
				}else if($order->get_meta('person_name')){
					$person_name = $order->get_meta('person_name');
				}else if(get_field('_wc_order_for_name',$order_id)){
                    $person_name = get_field('_wc_order_for_name',$order_id);
                }else{
					$customer = get_userdata($order->customer_user);
					$person_name = $customer->display_name;
                    /* $person_name = 'No Data'; */
                }
        		if($type == 'variation'){
        		}
        		    $orders['data'][$j]['items'][$i]['academy_id']= $productId;
        		    $orders['data'][$j]['items'][$i]['academy_name']= wp_strip_all_tags(html_entity_decode($product->get_title()));
        		    $orders['data'][$j]['items'][$i]['academy_address']= get_field('address',$productId);
        		    $orders['data'][$j]['items'][$i]['package_id']= $variation_id;
        		    $orders['data'][$j]['items'][$i]['package_name']= wp_strip_all_tags(html_entity_decode($variation->get_name()));
        		    $orders['data'][$j]['items'][$i]['package_amount']= $variation->get_price();
        		    $orders['data'][$j]['items'][$i]['person_name']= $person_name;
        		    $orders['data'][$j]['items'][$i]['partner_name']= ($partner_name) ? $partner_name : 'No Data';
        		    $orders['data'][$j]['items'][$i]['partner_phone']=  ($partner_phone) ? $partner_phone : 'No Data';
        		    $i++;
        	} 
        	$j++;
		}
		return $orders;
	}else{
		return 'no customer exist with this email.';
	}
}
function wc_rest_user_endpoint_handler($data){
    if($data) {
      $response = array();
      $username = sanitize_text_field($data->get_param('email'));
      $email = sanitize_text_field($data->get_param('email'));
      $password = sanitize_text_field($data->get_param('password'));
      $phone_number = sanitize_text_field($data->get_param('phone_number'));
	  $first_name = sanitize_text_field($data->get_param('first_name'));
      $address = sanitize_text_field($data->get_param('address'));
      $error = new WP_Error();
  if (empty($email)) {
    $error->add(401, __("Email field 'email' is required.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }
  if (empty($password)) {
    $error->add(404, __("Password field 'password' is required.", 'wp-rest-user'), array('status' => 400));
    return $error;
  }
  $user_id = username_exists($username);
  if (!$user_id && email_exists($email) == false) {
    $user_id = wp_create_user($username, $password, $email);
    if (!is_wp_error($user_id)) {
        add_user_meta( $user_id, 'phone_number', $phone_number);
        add_user_meta( $user_id, 'address', $address);
        add_user_meta( $user_id, 'first_name', $first_name);
        $user = get_user_by('id', $user_id);
        $user->set_role('partner');        
        $response['code'] = 200;
        $response['user_id'] = $user_id;
        $response['message'] = __("User '" . $username . "(".$user_id.")' Registration was Successful", "wp-rest-user");
		$data = array("user_login"=>$username,"user_password"=>$password,"role"=>$role);
		return login($data);
    } else {
        $error->add(406, __("Something is wrong", 'wp-rest-user'), array('status' => 400));
        return $error;
        return $user_id;
    }
  } else {
    $error->add(406, __("Email already exists, please try 'Reset Password'", 'wp-rest-user'), array('status' => 400));
    return $error;
  }
    }else{
        $error->add(406, __("Missing Parameters", 'wp-rest-user'), array('status' => 400));
        return $error;
    }
  return new WP_REST_Response($response, 123);
}
function auto_create_followup_booking( $booking_id ) {
	$prev_booking = get_wc_booking( $booking_id );
	if ( $prev_booking->get_parent_id() <= 0 ) {
		$new_booking_data = array(
			'start_date'  => strtotime( '+1 week', $prev_booking->get_start() ), 
			'end_date'    => strtotime( '+1 week', $prev_booking->get_end() ),   
			'resource_id' => $prev_booking->get_resource_id(),                   
			'parent_id'   => $booking_id,                                        
		);
		$persons = $prev_booking->get_persons();
		if ( is_array( $persons ) && 0 < count( $persons ) ) {
			$new_booking_data['persons'] = $persons;
		}
		if ( $prev_booking->is_all_day() ) {
			$new_booking_data['all_day'] = true;
		}
		create_wc_booking( 
			$prev_booking->get_product_id(), 
			$new_booking_data,               
			$prev_booking->get_status(),     
			false                            
		);
	}
}
function create_booking($request = null){
    $parameters = $request->get_json_params();
    $content = sanitize_text_field($parameters['content']);
    $user = sanitize_text_field($parameters['customer_id']);
    $order_id = $parent = sanitize_text_field($parameters['parent']);
    $start_timestamp = sanitize_text_field(($parameters['start_timestamp']));
    $end_timestamp = sanitize_text_field(($parameters['end_timestamp']));
    $meta_data = ($parameters['meta_data']);
    $year = date('Y',$start_timestamp);
    $month = date('m',$start_timestamp);
    $day = date('d',$start_timestamp);
    $order = wc_get_order( $parent );
	$total = $order->get_total();
	$order_items = $order->get_items();
	foreach ( $order_items as $item_id => $item ) {
		$booking_id = $item['product_id'];
		$product = wc_get_product( $booking_id );
		if($product->get_status() == 'publish'){
			$post_title = sprintf(
				__( 'Booking &ndash; %s', 'woocommerce-bookings' ),
				date( _x( 'M d, Y @ h:i A', 'Booking date format parsed by date', 'woocommerce-bookings' ), time() ) 
			);
			$post_id = $id = wp_insert_post( apply_filters( 'woocommerce_new_booking_data', array(
				'post_type'     => 'wc_booking',
				'post_title'    => $post_title,
				'post_status' => 'unpaid',
				'post_author' => $user,
				'post_parent'=> $parent,
				'ping_status'   => 'closed',
			) ), true );
			$booking = get_wc_product_booking( $product );
			$resource_id = $booking->has_resources() ? $booking->get_resource_id() : 0;
			if ($post_id) {
				if($meta_data){
						foreach($meta_data as $value){
						foreach($value as $key=>$val){ 
							add_post_meta($post_id, $key, $val);
							if($key == '_booking_cost') {
								$cost = $val;
								update_post_meta($post_id, 'booking_cost', $cost);
								if(get_post_meta($order_id, 'booking_cost', true)){
									$total = get_post_meta($order_id, 'booking_cost', true);
									$new_total = intval($total) + intval($cost); 
									update_post_meta($order_id, 'booking_cost', $new_total);
									$item->set_total( $new_total );
									$item->save();
									$order->set_total($new_total);
									$order->calculate_totals();
									$order->save();
								}else{
									$new_total = intval($total); 
									/* $item->set_total( $new_total );
									$item->save();
									$order->set_total($new_total);
									$order->calculate_totals();
									$order->save(); */
									update_post_meta($order_id, 'booking_cost', $new_total);
								}
								
								update_post_meta($order_id, 'booking_'.$post_id.'_price', $cost );
							}	
						}
					}
					}
			} 
			if ( ! $booking->get_date_created( 'edit' ) ) {
				$booking->set_date_created( current_time( 'timestamp' ) );
			}
			if ( $id && ! is_wp_error( $id ) ) {
				$booking->set_id( $id );
				$book_data_source = new Tida_Booking_Data_Store();
				$book_data_source->updatemeta( $booking );
				$booking->save_meta_data();
				$booking->apply_changes();
				WC_Cache_Helper::get_transient_version( 'bookings', true );
			}
			$productId = $booking_id;
			$first_block_time     = $booking->get_first_block_time();
			$timestamp = strtotime( "{$year}-{$month}-{$day}" );
			$from                 = $start_timestamp; strtotime( $first_block_time ? $first_block_time : 'midnight', $timestamp );
			$standard_from        = $from;
			$interval = $booking->get_duration();
			$to = $end_timestamp; strtotime( '+ 1 day', $standard_from ) + $interval;
			$product_id = $productId;
			$min_date   = isset($from) ? strtotime( wc_clean( wp_unslash($from) ) ) : '';
			$max_date   = isset($to) ? strtotime( wc_clean( wp_unslash($to) ) ) : '';
			if ( $product_id && $min_date && $max_date ) {
				$product_or_resource_id = $booking->has_resources() ? $booking->get_resource_id() : $product_id;
				$max_booking_count = apply_filters( 'woocommerce_bookings_max_bookings_to_delete_transient', 1000, (int) $product_or_resource_id, $min_date, $max_date );
				$existing_bookings = WC_Booking_Data_Store::get_bookings_in_date_range( $min_date, $max_date, $product_or_resource_id, true );
				if ( count( $existing_bookings ) < $max_booking_count ) {
					WC_Bookings_Cache::delete_booking_slots_transient();
				} else {
					WC_Bookings_Controller::find_booked_day_blocks( (int) $productId, $min_date, $max_date, 'Y-n-j', 0, array(), 'action-scheduler-helper' );
				}
			} else {
				WC_Bookings_Cache::delete_booking_slots_transient();
			}
			$booking = new WC_Booking($post_id);
			print get_wc_booking($post_id);
		}		
    }
}
function getpartnervenueorders($data){
    if($data) {
		$order_count = 0;
		$order_per_page = 10;
		if($data->get_param('limit_per_page')){
			$order_per_page = $data->get_param('limit_per_page');
		}
		$start_date = $data->get_param('start_date');
		$end_date = $data->get_param('end_date');
		$status = $data->get_param('status');
		$offset = 0; 
		if($data->get_param('page')){
			$current_page = $data->get_param('page');
		}else{
			$current_page = 1;
		}
		if( ! 0 == $current_page) {
			$offset = ( $order_per_page * $current_page ) - $order_per_page;
		}
		if($data->get_param('status')){
			 if($data->get_param('status') == 'all'){
				$status = array("pending","on-hold","completed","processing");
			 }else if($data->get_param('status') == 'pending'){
				$status = array("pending","on-hold","processing");
			 }else{
				$status = array($data->get_param('status'));
			 }
		}else {
			$status = array("pending","on-hold","completed","processing");
		}
        if($product_id = $data->get_param('product_id')){
			$orders = tida_get_orders_by_product( $product_id,$order_per_page,$offset,$start_date,$end_date,$status,'booking' ); 
//			tida_get_orders_by_product( $product_id,$limit,$offset );
			$order_count = $orders['total_orders']; $c_orders = $orders['orders'];
		}else{
			$user = $data->get_param('user');
			$args = array(
				'return' => 'ids',
				'status' => $status,
				'limit'   => -1,
				'date_query'     => array(
					'after'     => $start_date,
					'before'    => $end_date,
					'inclusive' => true, 
				),
				'meta_query' => array(
					'relation' => 'AND',
					array('key' => 'tida_order_type',
						  'value' => 'booking', 
						  'compare' => 'LIKE',
					),
					array(
					'relation' => 'OR',
					array('key' => 'partner_id',
						  'value' => ','.$user, 
						  'compare' => 'LIKE',
					),
					array('key' => 'partner_id',
						  'value' => ', '.$user, 
						  'compare' => 'LIKE',
					),
					array('key' => 'partner_id',
						  'value' => $user, 
						  'compare' => '=',
					)
					)
				)
			);
			if($data->get_param('search_term')){
				$search_term = $data->get_param('search_term');
				$args['s'] = $search_term;
			}
			$count_query = wc_get_orders($args); 
			$order_count = count( $count_query);
			$args['limit'] = $order_per_page;
			$args['number'] = $order_per_page;
			$args['offset'] = $offset;
			$query = new WC_Order_Query($args);
			$c_orders = $query->get_orders();
		}
		$max_num_pages = ceil( $order_count / $order_per_page );
		$orders = array();
		$orders['total_orders']  = $order_count;
		$orders['max_pages']  = $max_num_pages;
		$orders['current_page'] = $current_page;
		$j=0;
		foreach($c_orders as $order_id){
			$order = wc_get_order( $order_id );
			$order_items = $order->get_items();
			$orders['data'][$j]['id']  = $order_id;
		    $orders['data'][$j]['status'] = $order->get_status();
		    $orders['data'][$j]['currency'] = $order->get_currency();
		    $orders['data'][$j]['created_date'] = $order->get_date_created();
		    if($order->get_status() == 'failed'){
				$orders['data'][$j]['transaction_id']= 'Failed';
			}else if($order->get_status() == 'cancelled'){
				$orders['data'][$j]['transaction_id']= 'Cancelled';
			}else{
				if($order->get_transaction_id()){
					$orders['data'][$j]['transaction_id']= $order->get_transaction_id();
				}else if($order->get_meta('transaction_id')){
					$orders['data'][$j]['transaction_id']= $order->get_meta('transaction_id');
				}else{
					$orders['data'][$j]['transaction_id']= '';
				}
			}						
			if($order->get_payment_method() == 'cod' || $order->get_payment_method() == 'cheque'){
				$orders['data'][$j]['transaction_type']= 'offline';
			}else{
				$orders['data'][$j]['transaction_type']= 'online';
			}
			if($order->get_coupon_codes()){
				$orders['data'][$j]['discount_price']= $order->get_discount_total();
				$orders['data'][$j]['total']= $order->get_total() + $order->get_discount_total();
				$orders['data'][$j]['total_discounted_amount']= $order->get_total();
				$co = 0;
				foreach( $order->get_coupon_codes() as $coupon_code ) {
					$coupon = new WC_Coupon($coupon_code);
					$orders['data'][$j]['coupon'][$co]['code']= $coupon_code;
					$orders['data'][$j]['coupon'][$co]['amount']= $coupon->get_amount();
					$orders['data'][$j]['coupon'][$co]['discount_type']= $coupon->get_discount_type();
					$co++;
				}
			}else{
				$orders['data'][$j]['total'] = $order->get_total();
			}
			$i=0;
			$order_price = 0;
        	foreach ( $order_items as $item_id => $item ) {
        		$product = wc_get_product($item['product_id']);
        		$type = $product->get_type();
        		$productId = $product->get_id();
        		$partner_id = get_field('partner_manager',$productId);
        		$user = get_user_by('id', $partner_id);
                $partner_name = $user->display_name;
				$order_items = $order->get_items();
		if(!($order->get_meta('partner_id') )) { 
			$order->update_meta_data( 'partner_id' , $partner_id ) ;
			$order->save();
		}
		if( $product->get_type() === 'booking' ) {
			$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
			foreach( $booking_ids as $booking_id ) { 
				$booking = new WC_Booking($booking_id);
			if(!($booking->get_meta('partner_id') )) { 
				$booking->update_meta_data( 'partner_id' , $partner_id ) ;
				$booking->save();
			}
			}
		}

                if(get_field('phone_number','user_'.$partner_id)){
                    $partner_phone = get_field('phone_number','user_'.$partner_id);
                }else if(get_field('billing_phone','user_'.$partner_id)){
                    $partner_phone = get_field('billing_phone','user_'.$partner_id);
                }else if(get_user_meta($partner_id,'phone_number')) {
                    $partner_phone = get_user_meta($partner_id,'phone_number',true);
				}else if(get_user_meta($partner_id,'billing_phone')) {
                    $partner_phone = get_user_meta($partner_id,'billing_phone',true);
				}else{
                    $partner_phone = 'No Data';
                }
				
                if(get_field('person_name',$order_id)){
                    $person_name = get_field('person_name',$order_id);
                }else if(get_post_meta($order->get_id(), 'person_name', true)){
					$person_name = get_post_meta($order->get_id(), 'person_name', true);
				}else if(get_post_meta($order->get_id(), '_wc_order_for_name', true)){
					$person_name = get_post_meta($order->get_id(), '_wc_order_for_name', true);
				}else if($order->get_meta('_wc_order_for_name')){
					$person_name = $order->get_meta('_wc_order_for_name');
				}else if($order->get_meta('person_name')){
					$person_name = $order->get_meta('person_name');
				}else if(get_field('_wc_order_for_name',$order_id)){
                    $person_name = get_field('_wc_order_for_name',$order_id);
                }else{
					$customer = get_userdata($order->customer_user);
					$person_name = $customer->display_name;
                    /* $person_name = 'No Data'; */
                }
		    $customer = $order->get_user();
		    $customer_id = $customer->ID;
		    $orders['data'][$j]['customer']['id']= $customer_id;
		    $orders['data'][$j]['customer']['email']= $customer->user_email;
		    $orders['data'][$j]['customer']['name']= $customer->display_name;
		    $orders['data'][$j]['customer']['first_name']= $customer->first_name;
		    $orders['data'][$j]['customer']['last_name']= $customer->last_name;
		    $orders['data'][$j]['customer']['phone']= get_user_meta( $customer_id, 'billing_phone', true ) ;
			if(get_field('feedback',$order_id)){
				$feedback = get_field('feedback',$order_id);
			}else if(get_post_meta($order->get_id(), 'feedback', true)){
				$feedback = get_post_meta($order->get_id(), 'feedback', true);
			}else if($order->get_meta('feedback')){
				$feedback = $order->get_meta('feedback');
			}else{
				$feedback = '';
			}
			if($feedback){
				$orders['data'][$j]['feedback_submitted']= true;
				$orders['data'][$j]['feedback']= $feedback;
			}else{
				$orders['data'][$j]['feedback_submitted']= false;
			}
        		    $orders['data'][$j]['items'][$i]['name']= $product->get_title();
        		    $orders['data'][$j]['items'][$i]['address']= get_field('address',$productId);
        		    $orders['data'][$j]['items'][$i]['person_name']= $person_name;
                    $user = $order->get_user();
        		    $orders['data'][$j]['items'][$i]['user']= $user;					
					if($img = wp_get_attachment_image_src(get_post_thumbnail_id($productId),'full')){
						$orders['data'][$j]['items'][$i]['image']= $img[0];	
					}
        		    $orders['data'][$j]['items'][$i]['partner_name']=  ($partner_name) ? $partner_name : 'No Data';
        		    $orders['data'][$j]['items'][$i]['partner_phone']=  ($partner_phone) ? $partner_phone : 'No Data';
					$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
                    if ( $booking_ids ) {
                        $k = 0;
                        foreach ( $booking_ids as $booking_id ) {
                            $booking       = new WC_Booking( $booking_id );
                            $booking_start = date( 'Y-m-d', $booking->get_start());
                            $booking_end   = date( 'Y-m-d', $booking->get_end());
                            $hour = date ("G", $booking->get_start());
                            $minute = date ("i", $booking->get_start());
                            $second = date ("s", $booking->get_start());
                            if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59){
                                $slot_name = "Early Morning Slot ";
                            }else if ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59){
                                $slot_name = "Morning Slot ";
                            }else if ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59){
                                $slot_name = "Mid-Day Slot ";
                            }else if ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59){
                                $slot_name = "Evening Slot ";
                            }else {
                                $slot_name = "Night Slot ";
                            }
            		        $orders['data'][$j]['items']['slots'][$k]['day_time'] = $hour.':'.$minute.':'.$second;
            		        $orders['data'][$j]['items']['slots'][$k]['booking_id']= $booking_id;
							if(get_post_meta($booking_id, 'booking_cost', true)){
								$orders['data'][$j]['items']['slots'][$k]['slot_amount'] = intval(get_post_meta($booking_id, 'booking_cost', true));
							}else{
								$orders['data'][$j]['items']['slots'][$k]['slot_amount'] = $order->get_line_subtotal( $item ); 
							}
                		    $orders['data'][$j]['items']['slots'][$k]['slot_start_date']= $booking_start;
                		    $orders['data'][$j]['items']['slots'][$k]['slot_end_date']= $booking_end;
                		    $orders['data'][$j]['items']['slots'][$k]['slot_start_time']= date("g:i a",$booking->get_start());
                		    $orders['data'][$j]['items']['slots'][$k]['slot_end_time']= date("g:i a",$booking->get_end());
        		            $orders['data'][$j]['items']['slots'][$k]['slot_name']= $slot_name;
                        	$k++;
                        }
                    }
        		    $i++;
        	}
		    $j++;
		}
		return $orders;
	}else{
		return 'no customer exist with this email.';
	}
}
function get_order_detail( $request) {
	$order_id = '';	//print_r($request);
	if($request['order_id']){
		$order_id = $request['order_id'];
	}else if($request->get_param('order_id')){
		$order_id =  $request->get_param('order_id');
	}
	if($order_id){
		$order = wc_get_order($order_id);
		$orders['data']['order_type']=$order->get_meta('tida_order_type');
		$order_items = $order->get_items();
			$orders['data']['id']  = $order_id;
		    $orders['data']['status'] = $order->get_status();
		    $orders['data']['currency'] = $order->get_currency();
		    $orders['data']['created_date'] = $order->get_date_created();
			if($order->get_status() == 'failed'){
				$orders['data']['transaction_id']= 'Failed';
			}else if($order->get_status() == 'cancelled'){
				$orders['data']['transaction_id']= 'Cancelled';
			}else{
				if($order->get_transaction_id()){
					$orders['data']['transaction_id']= $order->get_transaction_id();
				}else if($order->get_meta('transaction_id')){
					$orders['data']['transaction_id']= $order->get_meta('transaction_id');
				}else{
					$orders['data']['transaction_id']= '';
				}
			}						
			if($order->get_payment_method() == 'cod' || $order->get_payment_method() == 'cheque' || $order->get_payment_method() == 'bank'){
				$orders['data']['transaction_type']= 'offline';
			}else{
				$orders['data']['transaction_type']= 'online';
			}
			if($order->get_coupon_codes()){
				$orders['data']['discount_price']= $order->get_discount_total();
				$orders['data']['total']= $order->get_total() + $order->get_discount_total();
				$orders['data']['total_discounted_amount']= $order->get_total();
				$co = 0;
				foreach( $order->get_coupon_codes() as $coupon_code ) {
					$coupon = new WC_Coupon($coupon_code);
					$orders['data']['coupon'][$co]['code']= $coupon_code;
					$orders['data']['coupon'][$co]['amount']= $coupon->get_amount();
					$orders['data']['coupon'][$co]['discount_type']= $coupon->get_discount_type();
					$co++;
				}
			}else{
				$orders['data']['total'] = $order->get_total();
			}
		    $customer = $order->get_user();
		    $customer_id = $customer->ID;
		    $orders['data']['customer']['id']= $customer_id;
		    $orders['data']['customer']['email']= $customer->user_email;
		    $orders['data']['customer']['name']= $customer->display_name;
		    $orders['data']['customer']['first_name']= $customer->first_name;
		    $orders['data']['customer']['last_name']= $customer->last_name;
		    $orders['data']['customer']['phone']= get_user_meta( $customer_id, 'billing_phone', true ) ;
			if(get_field('feedback',$order_id)){
				$feedback = get_field('feedback',$order_id);
			}else if(get_post_meta($order->get_id(), 'feedback', true)){
				$feedback = get_post_meta($order->get_id(), 'feedback', true);
			}else if($order->get_meta('feedback')){
				$feedback = $order->get_meta('feedback');
			}else{
				$feedback = '';
			}
			if($feedback){
				$orders['data'][$j]['feedback_submitted']= true;
				$orders['data'][$j]['feedback']= $feedback;
			}else{
				$orders['data'][$j]['feedback_submitted']= false;
			}
			$i=0;
        	foreach ( $order_items as $item_id => $item ) {				
        		$product = wc_get_product($item['product_id']);
        		$type = $product->get_type();
        		$productId = $product->get_id();
				$partner_id = get_field('partner_manager',$productId);
        		$user = get_user_by('id', $partner_id);
                $partner_name = $user->display_name;
                if(get_field('phone_number','user_'.$partner_id)){
                    $partner_phone = get_field('phone_number','user_'.$partner_id);
                }else if(get_field('billing_phone','user_'.$partner_id)){
                    $partner_phone = get_field('billing_phone','user_'.$partner_id);
                }else if(get_user_meta($partner_id,'phone_number')) {
                    $partner_phone = get_user_meta($partner_id,'phone_number',true);
				}else if(get_user_meta($partner_id,'billing_phone')) {
                    $partner_phone = get_user_meta($partner_id,'billing_phone',true);
				}else{
                    $partner_phone = 'No Data';
                }
				if(get_field('person_name',$order_id)){
                    $person_name = get_field('person_name',$order_id);
                }else if(get_post_meta($order->get_id(), 'person_name', true)){
					$person_name = get_post_meta($order->get_id(), 'person_name', true);
				}else if(get_post_meta($order->get_id(), '_wc_order_for_name', true)){
					$person_name = get_post_meta($order->get_id(), '_wc_order_for_name', true);
				}else if($order->get_meta('_wc_order_for_name')){
					$person_name = $order->get_meta('_wc_order_for_name');
				}else if($order->get_meta('person_name')){
					$person_name = $order->get_meta('person_name');
				}else if(get_field('_wc_order_for_name',$order_id)){
                    $person_name = get_field('_wc_order_for_name',$order_id);
                }else{
					$customer = get_userdata($order->customer_user);
					$person_name = $customer->display_name;
                    /* $person_name = 'No Data'; */
                }
				if($type == 'booking'){
        		    $orders['data']['items'][$i]['name']= $product->get_title();
        		    $orders['data']['items'][$i]['address']= get_field('address',$productId);
        		    $orders['data']['items'][$i]['person_name']= $person_name;
                    $user = $order->get_user();
        		    $orders['data']['items'][$i]['user']= $user;					
					if($img = wp_get_attachment_image_src(get_post_thumbnail_id($productId),'full')){
						$orders['data']['items'][$i]['image']= $img[0];	
					}					
				$orders['data']['items'][$i]['partner_name']= ($partner_name) ? $partner_name : 'No Data';
				$orders['data']['items'][$i]['partner_phone']= ($partner_phone) ? $partner_phone : 'No Data';
					$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
                    if ( $booking_ids ) {
                        $k = 0;
                        foreach ( $booking_ids as $booking_id ) {
							 $booking       = new WC_Booking( $booking_id );
                            $booking_start = date( 'Y-m-d', $booking->get_start());
                            $booking_end   = date( 'Y-m-d', $booking->get_end());
                            $hour = date ("G", $booking->get_start());
                            $minute = date ("i", $booking->get_start());
                            $second = date ("s", $booking->get_start());
                            if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59){
                                $slot_name = "Early Morning Slot ";
                            }else if ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59){
                                $slot_name = "Morning Slot ";
                            }else if ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59){
                                $slot_name = "Mid-Day Slot ";
                            }else if ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59){
                                $slot_name = "Evening Slot ";
                            }else {
                                $slot_name = "Night Slot ";
                            }
            		        $orders['data']['items']['slots'][$k]['day_time'] = $hour.':'.$minute.':'.$second;
            		        $orders['data']['items']['slots'][$k]['booking_id']= $booking_id;
							if(get_post_meta($booking_id, 'booking_cost', true)){
								$orders['data']['items']['slots'][$k]['slot_amount'] = intval(get_post_meta($booking_id, 'booking_cost', true));
							}else{
								$orders['data']['items']['slots'][$k]['slot_amount'] = $order->get_line_subtotal( $item ); 
							}
                		    $orders['data']['items']['slots'][$k]['slot_start_date']= $booking_start;
                		    $orders['data']['items']['slots'][$k]['slot_end_date']= $booking_end;
                		    $orders['data']['items']['slots'][$k]['slot_start_time']= date("g:i a",$booking->get_start());
                		    $orders['data']['items']['slots'][$k]['slot_end_time']= date("g:i a",$booking->get_end());
        		            $orders['data']['items']['slots'][$k]['slot_name']= $slot_name;
                        	$k++;
                        }
                    }
        		    $i++;
        	 }elseif(wcs_get_subscriptions_for_order($order_id)){				 
				$orders['data']['subscriptions_ids'] = $subscriptions_ids = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
				$ii = 0;
			    foreach( $subscriptions_ids as $subscription_id => $subscription ){
					if ( WC_Subscriptions_Synchroniser::subscription_contains_synced_product( $subscription_id ) ) {
						$length_from_timestamp = $subscription->get_time( 'next_payment' );
					}  else {
						$length_from_timestamp = $subscription->get_time( 'start' );
					}
					$subscription_length = wcs_estimate_periods_between( $length_from_timestamp, $subscription->get_time( 'end' ), $subscription->get_billing_period() );
					$orders['data']['subscriptions'][$ii]['id'] = $subscription_id;
					$orders['data']['subscriptions'][$ii]['trial_end'] = $subscription->get_date( 'trial_end' );
					$orders['data']['subscriptions'][$ii]['total'] = $subscription->get_total();
					if($subscription->get_time( 'next_payment' )){
						$orders['data']['subscriptions'][$ii]['next_payment'] = date('d-m-Y',$subscription->get_time( 'next_payment' ));
					}else{
						$orders['data']['subscriptions'][$ii]['next_payment'] = 'No renewal available';
					}
					$orders['data']['subscriptions'][$ii]['start'] = date('d-m-Y',$subscription->get_time( 'start' ));
					if($subscription->get_time( 'end' )){
					$orders['data']['subscriptions'][$ii]['end'] = date('d-m-Y',$subscription->get_time( 'end' ));
					}
					$orders['data']['subscriptions'][$ii]['subscription_length'] = $subscription_length;
					$ii++;
				}
				}
        		
                if(get_field('person_name',$order_id)){
                    $person_name = get_field('person_name',$order_id);
                }else if(get_post_meta($order->get_id(), 'person_name', true)){
					$person_name = get_post_meta($order->get_id(), 'person_name', true);
				}else if(get_post_meta($order->get_id(), '_wc_order_for_name', true)){
					$person_name = get_post_meta($order->get_id(), '_wc_order_for_name', true);
				}else if($order->get_meta('_wc_order_for_name')){
					$person_name = $order->get_meta('_wc_order_for_name');
				}else if($order->get_meta('person_name')){
					$person_name = $order->get_meta('person_name');
				}else if(get_field('_wc_order_for_name',$order_id)){
                    $person_name = get_field('_wc_order_for_name',$order_id);
                }else{
					$customer = get_userdata($order->customer_user);
					$person_name = $customer->display_name;
                    /* $person_name = 'No Data'; */
                }
				if($type == 'variable' || $type == 'variable-subscription'){
					$variation_id = $item['variation_id'];
					$variation = wc_get_product($variation_id);
					$orders['data']['items'][$i]['academy_id']= $productId;
        		    $orders['data']['items'][$i]['academy_name']= wp_strip_all_tags(html_entity_decode($product->get_title()));
        		    $orders['data']['items'][$i]['academy_address']= get_field('address',$productId);
        		    $orders['data']['items'][$i]['package_id']= $variation_id;
        		    $orders['data']['items'][$i]['package_name']= wp_strip_all_tags(html_entity_decode($variation->get_name()));
        		    $orders['data']['items'][$i]['package_amount']= $variation->get_price();
					$orders['data']['items'][$i]['person_name']= $person_name;					
					if($img = wp_get_attachment_image_src(get_post_thumbnail_id($productId),'full')){
						$orders['data']['items'][$i]['image']= $img[0];	
					}
					$orders['data']['items'][$i]['partner_name']= ($partner_name) ? $partner_name : 'No Data';
					$orders['data']['items'][$i]['partner_phone']= ($partner_phone) ? $partner_phone : 'No Data'; 
					$orders['data']['items'][$i]['partner_id']= $partner_id;       		    
				}
		}
		return array(
			'success' => true,
			'message' => $orders
		);
		}else{
		return array(
			'success' => false,
			'message' => 'User ID missing',
		);
	}
}
function getpartnersubscriptionorders($data){
    if($data) {
		$order_count = 0;
        $user = $data->get_param('user');
        $start_date = $data->get_param('start_date');
        $end_date = $data->get_param('end_date');		
        $status = $data->get_param('status');
		$order_per_page = 10;
		if($data->get_param('limit_per_page')){
			$order_per_page = $data->get_param('limit_per_page');
		}		
		if($data->get_param('page')){
			$current_page = $data->get_param('page');
		}else{
			$current_page = 1;
		}
        if($data->get_param('status')){
			 if($data->get_param('status') == 'all'){
			$status = array("pending","on-hold","completed","processing");
			 }else if($data->get_param('status') == 'pending'){
			$status = array("pending","on-hold","processing");
			 }else{
				$status = array($data->get_param('status'));
			 }
		}else {
			$status = array("pending","on-hold","completed","processing");
		}
		
		$offset = 0; 
		if( ! 0 == $current_page) {
			$offset = ( $order_per_page * $current_page ) - $order_per_page;
		}
        if($product_id = $data->get_param('product_id')){
			$orders = tida_get_orders_by_product( $product_id,$order_per_page,$offset,$start_date,$end_date,$status,'subscription_variation' ); 
			$order_count = $orders['total_orders']; $c_orders = $orders['orders'];
		}else{
		$args = array(
			'return' => 'ids',
			'status' => $status,
			'limit'   => -1,
			'meta_query' => array(
				'relation'=>'AND',
                array('key' => 'tida_order_type',
                      'value' => 'subscription_variation', 
                      'compare' => 'LIKE',
                ),
				/* array(
		        'relation' => 'OR',
                array('key' => 'tida_order_type',
                      'value' => 'variable-subscription', 
                      'compare' => 'LIKE',
                )), */
				array(
		        'relation' => 'OR',
				array('key' => 'partner_id',
                      'value' => ','.$user, 
                      'compare' => 'LIKE',
				),
				array('key' => 'partner_id',
                      'value' => ', '.$user, 
                      'compare' => 'LIKE',
				),
				array('key' => 'partner_id',
                      'value' => $user, 
                      'compare' => '=',
				)
				)
            )
		);
		if($data->get_param('start_date') && $data->get_param('end_date') ){
			$args['date_query'] = array(
                'after'     => $start_date,
                'before'    => $end_date,
                'inclusive' => true,
            );
		}
		if($data->get_param('search_term')){
			$search_term = $data->get_param('search_term');
			$args['s'] = $search_term;
		}
		$count_query = wc_get_orders($args);
		$order_count = count( $count_query);
		global $wpdb;
		$max_num_pages = ceil( $order_count / $order_per_page );
		$args['limit'] = $order_per_page;
		$args['number'] = $order_per_page;
		$args['offset'] = $offset;
		$query = new WC_Order_Query($args);
		$c_orders = $query->get_orders();
		}
		$max_num_pages = ceil( $order_count / $order_per_page );
		if($data->get_param('search_term')){
			unset($args['s']);
			$args['meta_query'] = array(
				'relation'=>'AND',
                array('key' => 'tida_order_type',
                      'value' => 'subscription_variation', 
                      'compare' => 'LIKE',
                ),
				array(
		        'relation' => 'OR',
                array('key' => 'person_name',
                      'value' => $search_term, 
                      'compare' => 'LIKE',
                ),
				array('key' => '_wc_order_for_name',
                      'value' => $search_term, 
                      'compare' => 'LIKE',
                ),
				),
				array(
		        'relation' => 'OR',
				array('key' => 'partner_id',
                      'value' => ','.$user, 
                      'compare' => 'LIKE',
				),
				array('key' => 'partner_id',
                      'value' => ', '.$user, 
                      'compare' => 'LIKE',
				),
				array('key' => 'partner_id',
                      'value' => $user, 
                      'compare' => '=',
				)
				)
            );
			$sub_count_query = wc_get_orders($args);
			$order_count = count( $sub_count_query) +  $order_count;
			$offset = 0; 
			if( ! 0 == $current_page) {
				$offset = ( $order_per_page * $current_page ) - $order_per_page;
			}
			$max_num_pages = ceil( $order_count / $order_per_page );
			$args['limit'] = $order_per_page;
			$args['number'] = $order_per_page;
			$args['offset'] = $offset;
			global $wpdb;
			$query = new WC_Order_Query($args);
			$orders = array();
			$c_orders = array_unique (array_merge($query->get_orders(),$c_orders));
		}
		/* $orders['args']  = $args;
		$orders['$c_orders']  = $c_orders;$wpdb->last_query; */
		$orders['total_orders']  = $order_count;
		$orders['max_pages']  = $max_num_pages;
		$orders['current_page'] = $current_page;
		$j=0;
		foreach($c_orders as $order_id){
			$order = wc_get_order( $order_id );
			$order_items = $order->get_items();
			$orders['data'][$j]['id']  = $order_id;
		    $orders['data'][$j]['status'] = $order->get_status();
		    $orders['data'][$j]['currency'] = $order->get_currency();
		    $orders['data'][$j]['created_date'] = $order->get_date_created();
			if($order->get_status() == 'failed'){
				$orders['data'][$j]['transaction_id']= 'Failed';
			}else if($order->get_status() == 'cancelled'){
				$orders['data'][$j]['transaction_id']= 'Cancelled';
			}else{
				if($order->get_transaction_id()){
					$orders['data'][$j]['transaction_id']= $order->get_transaction_id();
				}else if($order->get_meta('transaction_id')){
					$orders['data'][$j]['transaction_id']= $order->get_meta('transaction_id');
				}else{
					$orders['data'][$j]['transaction_id']= '';
				}
			}			
			if($order->get_payment_method() == 'cod' || $order->get_payment_method() == 'cheque' || $order->get_payment_method() == 'bank'){
				$orders['data'][$j]['transaction_type']= 'offline';
			}else{
				$orders['data'][$j]['transaction_type']= 'online';
			}
				$orders['data'][$j]['get_payment_method']= $order->get_payment_method();
			if($order->get_coupon_codes()){
				$co = 0;
				$orders['data'][$j]['discount_price']= $order->get_discount_total();
				$orders['data'][$j]['total']= $order->get_total() + $order->get_discount_total();
				$orders['data'][$j]['total_discounted_amount']= $order->get_total();
				foreach( $order->get_coupon_codes() as $coupon_code ) {
					$coupon = new WC_Coupon($coupon_code);
					$orders['data'][$j]['coupon'][$co]['code']= $coupon_code;
					$orders['data'][$j]['coupon'][$co]['amount']= $coupon->get_amount();
					$orders['data'][$j]['coupon'][$co]['discount_type']= $coupon->get_discount_type();
					$co++;
				}
			}else{
				$orders['data'][$j]['total'] = $order->get_total();
			}
		    $subscriptions_ids = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
		    $customer = $order->get_user();
		    $customer_id = $customer->ID;
			$display_name = $customer->first_name;
			$display_name .= ($customer->last_name) ? ' '.$customer->last_name : '';
		    $orders['data'][$j]['customer']['id']= $customer_id;
		    $orders['data'][$j]['customer']['email']= $customer->user_email;
		    $orders['data'][$j]['customer']['name']= $customer->display_name;
		    $orders['data'][$j]['customer']['first_name']= $customer->first_name;
		    $orders['data'][$j]['customer']['last_name']= $customer->last_name;
		    $orders['data'][$j]['customer']['phone']= get_user_meta( $customer_id, 'billing_phone', true ) ;
			if(get_field('feedback',$order_id)){
				$feedback = get_field('feedback',$order_id);
			}else if(get_post_meta($order->get_id(), 'feedback', true)){
				$feedback = get_post_meta($order->get_id(), 'feedback', true);
			}else if($order->get_meta('feedback')){
				$feedback = $order->get_meta('feedback');
			}else{
				$feedback = '';
			}
			if($feedback){
				$orders['data'][$j]['feedback_submitted']= true;
				$orders['data'][$j]['feedback']= $feedback;
			}else{
				$orders['data'][$j]['feedback_submitted']= false;
			}
		    $i=0;
            foreach( $subscriptions_ids as $subscription_id => $subscription ){
                if ( WC_Subscriptions_Synchroniser::subscription_contains_synced_product( $subscription_id ) ) {
                    $length_from_timestamp = $subscription->get_time( 'next_payment' );
                }  else {
                    $length_from_timestamp = $subscription->get_time( 'start' );
                }
                $subscription_length = wcs_estimate_periods_between( $length_from_timestamp, $subscription->get_time( 'end' ), $subscription->get_billing_period() );
                $orders['data'][$j]['subscriptions'][$i]['id'] = $subscription_id;
                $orders['data'][$j]['subscriptions'][$i]['trial_end'] = $subscription->get_date( 'trial_end' );
                $orders['data'][$j]['subscriptions'][$i]['total'] = $subscription->get_total();
                if($subscription->get_time( 'next_payment' )){
					$orders['data'][$j]['subscriptions'][$i]['next_payment'] = date('d-m-Y',$subscription->get_time( 'next_payment' ));
				}else{
					$orders['data'][$j]['subscriptions'][$i]['next_payment'] = 'No renewal available';
				}
				$orders['data'][$j]['subscriptions'][$i]['start'] = date('d-m-Y',$subscription->get_time( 'start' ));
				if($subscription->get_time( 'end' )){
                $orders['data'][$j]['subscriptions'][$i]['end'] = date('d-m-Y',$subscription->get_time( 'end' ));
				}
                $orders['data'][$j]['subscriptions'][$i]['subscription_length'] = $subscription_length;
                $i++;
            }
			$i=0;
        	foreach ( $order_items as $item_id => $item ) {
        		$product = wc_get_product($item['product_id']);
        		$type = $product->get_type();
        		$productId = $product->get_id();
        	    $variation_id = $item['variation_id'];
        		$orders['data'][$j]['items'][$i]['type'] = $type = $product->get_type();
        		$variation = wc_get_product($variation_id);
        		$partner_id = get_field('partner_manager',$productId);
        		$user = get_user_by('id', $partner_id);
                $partner_name = $user->display_name;
                if(get_field('phone_number','user_'.$partner_id)){
                    $partner_phone = get_field('phone_number','user_'.$partner_id);
                }else if(get_field('billing_phone','user_'.$partner_id)){
                    $partner_phone = get_field('billing_phone','user_'.$partner_id);
                }else if(get_user_meta($partner_id,'phone_number')) {
                    $partner_phone = get_user_meta($partner_id,'phone_number',true);
				}else if(get_user_meta($partner_id,'billing_phone')) {
                    $partner_phone = get_user_meta($partner_id,'billing_phone',true);
				}else{
                    $partner_phone = 'No Data';
                }
                if(get_field('person_name',$order_id)){
                    $person_name = get_field('person_name',$order_id);
                }else if(get_post_meta($order->get_id(), 'person_name', true)){
					$person_name = get_post_meta($order->get_id(), 'person_name', true);
				}else if(get_post_meta($order->get_id(), '_wc_order_for_name', true)){
					$person_name = get_post_meta($order->get_id(), '_wc_order_for_name', true);
				}else if($order->get_meta('_wc_order_for_name')){
					$person_name = $order->get_meta('_wc_order_for_name');
				}else if($order->get_meta('person_name')){
					$person_name = $order->get_meta('person_name');
				}else if(get_field('_wc_order_for_name',$order_id)){
                    $person_name = get_field('_wc_order_for_name',$order_id);
                }else{
					$customer = get_userdata($order->customer_user);
					$person_name = $customer->display_name;
                    /* $person_name = 'No Data'; */
                }
        		if($type == 'subscription_variation' || $type == 'variable-subscription'){
        		}
        		    $orders['data'][$j]['items'][$i]['academy_name']= wp_strip_all_tags(html_entity_decode($product->get_title()));
        		    $orders['data'][$j]['items'][$i]['academy_address']= get_field('address',$productId);
        		    $orders['data'][$j]['items'][$i]['package_name']= wp_strip_all_tags(html_entity_decode($variation->get_name()));
        		    $orders['data'][$j]['items'][$i]['package_amount']= $variation->get_price();
        		    $orders['data'][$j]['items'][$i]['person_name']= $person_name;					
					if($img = wp_get_attachment_image_src(get_post_thumbnail_id($productId),'full')){
						$orders['data'][$j]['items'][$i]['image']= $img[0];	
					}
        		    $orders['data'][$j]['items'][$i]['partner_name']= ($partner_name) ? $partner_name : 'No Data';
        		    $orders['data'][$j]['items'][$i]['partner_phone']= ($partner_phone) ? $partner_phone : 'No Data';
        		    $i++;
        	} 
        	$j++;
		}
		return $orders;
	}else{
		return 'no customer exist with this email.';
	}
}
function getpartneracademyorders($data){
    if($data) {
		$order_count = 0;
		if($parameters = $data->get_json_params()){
		    $user = $parameters['user'];
            $start_date = $parameters['start_date'];
            $end_date = $parameters['end_date'];
			$order_per_page = 10;
			if($data->get_param('limit_per_page')){
				$order_per_page = $data->get_param('limit_per_page');
			}
			if(isset($parameters['status'])){
				if($parameters['status'] == 'pending'){
					$status = array("pending","on-hold","processing");
				}else{
					$status = $parameters['status'];
				}
			}else {
				$status = array("pending","on-hold","completed","processing");
			}	
		}else{ 
            $user = $data->get_param('user');
            $start_date = $data->get_param('start_date');
            $end_date = $data->get_param('end_date');	
			$status = $data->get_param('status');
			if($data->get_param('status')){
				 if($data->get_param('status') == 'all'){
				$status = array("pending","on-hold","completed","processing","failed","cancelled");
				 }else if($data->get_param('status') == 'pending'){
					$status = array("pending","on-hold","processing");
				}else{
					$status = array($data->get_param('status'));
				 }
			}else {
				$status = array("pending","on-hold","completed","processing");
			}
		}
		
		if($data->get_param('page')){
			$current_page = $data->get_param('page');
		}else{
			$current_page = 1;
		}
		$offset = 0; 
		if( ! 0 == $current_page) {
			$offset = ( $order_per_page * $current_page ) - $order_per_page;
		}
        if($product_id = $data->get_param('product_id')){
			$orders = tida_get_orders_by_product( $product_id,$order_per_page,$offset,$start_date,$end_date,$status,'variation' ); 
			$order_count = $orders['total_orders']; $c_orders = $orders['orders'];
		}else{
		$args = array(
			'return' => 'ids',
			'status' => $status,
			'date_query'     => array(
                'after'     => $start_date,
                'before'    => $end_date,
                'inclusive' => true,
            ),
			'limit'   => -1,
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
				array(
		        'relation' => 'OR',
				array('key' => 'partner_id',
                      'value' => ','.$user, 
                      'compare' => 'LIKE',
				),
				array('key' => 'partner_id',
                      'value' => ', '.$user, 
                      'compare' => 'LIKE',
				),
				array('key' => 'partner_id',
                      'value' => $user, 
                      'compare' => '=',
				)
				))
		);
		if($data->get_param('search_term')){
			$search_term = $data->get_param('search_term');
			$args['s'] = $search_term;
		}
		$count_query = wc_get_orders($args);
		$order_count = count( $count_query);
		$max_num_pages = ceil( $order_count / $order_per_page );
		}
		$args['limit'] = $order_per_page;
		$args['number'] = $order_per_page;
		$args['offset'] = $offset;
		$query = new WC_Order_Query($args);
		$orders = array(); 
		$c_orders = $query->get_orders();
		$orders['total_orders']  = $order_count;
		$orders['max_pages']  = $max_num_pages;
		$orders['current_page'] = $current_page;
		$j=0;
		foreach($c_orders as $order_id){
			$order = wc_get_order( $order_id );
			$order_items = $order->get_items();
			$orders['data'][$j]['id']  = $order_id;
		    $orders['data'][$j]['status'] = $order->get_status();
		    $orders['data'][$j]['currency'] = $order->get_currency();
		    $orders['data'][$j]['created_date'] = $order->get_date_created();
		    $orders['data'][$j]['phone_number']= $order->get_billing_phone();
			
			
			if($order->get_status() == 'failed'){
				$orders['data'][$j]['transaction_id']= 'Failed';
			}else if($order->get_status() == 'cancelled'){
				$orders['data'][$j]['transaction_id']= 'Cancelled';
			}else{
				if($order->get_transaction_id()){
					$orders['data'][$j]['transaction_id']= $order->get_transaction_id();
				}else if($order->get_meta('transaction_id')){
					$orders['data'][$j]['transaction_id']= $order->get_meta('transaction_id');
				}else{
					$orders['data'][$j]['transaction_id']= '';
				}
			}			
			if($order->get_payment_method() == 'cod' || $order->get_payment_method() == 'cheque' || $order->get_payment_method() == 'bank'){
				$orders['data'][$j]['transaction_type']= 'offline';
			}else{
				$orders['data'][$j]['transaction_type']= 'online';
			}
			if($order->get_coupon_codes()){
				$co = 0;
				$orders['data'][$j]['discount_price']= $order->get_discount_total();
				$orders['data'][$j]['total']= $order->get_total() + $order->get_discount_total();
				$orders['data'][$j]['total_discounted_amount']= $order->get_total();
				foreach( $order->get_coupon_codes() as $coupon_code ) {
					$coupon = new WC_Coupon($coupon_code);
					$orders['data'][$j]['coupon'][$co]['code']= $coupon_code;
					$orders['data'][$j]['coupon'][$co]['amount']= $coupon->get_amount();
					$orders['data'][$j]['coupon'][$co]['discount_type']= $coupon->get_discount_type();
					$co++;
				}
			}else{
				$orders['data'][$j]['total'] = $order->get_total();
			}
		    $customer = $order->get_user();
		    $customer_id = $customer->ID;
		    $orders['data'][$j]['customer']['id']= $customer_id;
		    $orders['data'][$j]['customer']['email']= $customer->user_email;
		    $orders['data'][$j]['customer']['name']= $customer->display_name;
		    $orders['data'][$j]['customer']['first_name']= $customer->first_name;
		    $orders['data'][$j]['customer']['last_name']= $customer->last_name;
		    $orders['data'][$j]['customer']['phone']= get_user_meta( $customer_id, 'billing_phone', true ) ;
		    $orders['data'][$j]['customer']['phone']= get_user_meta( $customer_id, 'billing_phone', true ) ;
			if(get_field('feedback',$order_id)){
				$feedback = get_field('feedback',$order_id);
			}else if(get_post_meta($order->get_id(), 'feedback', true)){
				$feedback = get_post_meta($order->get_id(), 'feedback', true);
			}else if($order->get_meta('feedback')){
				$feedback = $order->get_meta('feedback');
			}else{
				$feedback = '';
			}
			if($feedback){
				$orders['data'][$j]['feedback_submitted']= true;
				$orders['data'][$j]['feedback']= $feedback;
			}else{
				$orders['data'][$j]['feedback_submitted']= false;
			}
			$i=0;
        	foreach ( $order_items as $item_id => $item ) { 
        	    $variation_id = $item['variation_id'];
        		$product = wc_get_product($item['product_id']);
    		    $orders['data'][$j]['items'][$i]['type'] = $type = $product->get_type();
        		$productId = $product->get_id();
        		$variation = wc_get_product($variation_id);
        		$partner_id = get_field('partner_manager',$productId);
        		$user = get_user_by('id', $partner_id);
                $partner_name = $user->display_name;
                if(get_field('phone_number','user_'.$partner_id)){
                    $partner_phone = get_field('phone_number','user_'.$partner_id);
                }else if(get_field('billing_phone','user_'.$partner_id)){
                    $partner_phone = get_field('billing_phone','user_'.$partner_id);
                }else if(get_user_meta($partner_id,'phone_number')) {
                    $partner_phone = get_user_meta($partner_id,'phone_number',true);
				}else if(get_user_meta($partner_id,'billing_phone')) {
                    $partner_phone = get_user_meta($partner_id,'billing_phone',true);
				}else{
                    $partner_phone = 'No Data';
                }
                if(get_field('person_name',$order_id)){
                    $person_name = get_field('person_name',$order_id);
                }else if(get_post_meta($order->get_id(), 'person_name', true)){
					$person_name = get_post_meta($order->get_id(), 'person_name', true);
				}else if(get_post_meta($order->get_id(), '_wc_order_for_name', true)){
					$person_name = get_post_meta($order->get_id(), '_wc_order_for_name', true);
				}else if($order->get_meta('_wc_order_for_name')){
					$person_name = $order->get_meta('_wc_order_for_name');
				}else if($order->get_meta('person_name')){
					$person_name = $order->get_meta('person_name');
				}else if(get_field('_wc_order_for_name',$order_id)){
                    $person_name = get_field('_wc_order_for_name',$order_id);
                }else{
					$customer = get_userdata($order->customer_user);
					$person_name = $customer->display_name;
                    /* $person_name = 'No Data'; */
                }
        		if($type == 'variation'){
        		}
        		    $orders['data'][$j]['items'][$i]['academy_name']= $product->get_title();
        		    $orders['data'][$j]['items'][$i]['academy_address']= get_field('address',$productId);
        		    $orders['data'][$j]['items'][$i]['package_name']= $variation->get_name();
        		    $orders['data'][$j]['items'][$i]['package_amount']= $variation->get_price();
        		    $orders['data'][$j]['items'][$i]['person_name']= $person_name;					
					if($img = wp_get_attachment_image_src(get_post_thumbnail_id($productId),'full')){
						$orders['data'][$j]['items'][$i]['image']= $img[0];	
					}
        		    $orders['data'][$j]['items'][$i]['partner_id']= $partner_id;
        		    $orders['data'][$j]['items'][$i]['partner_name']= ($partner_name) ? $partner_name : 'No Data';
        		    $i++;
        	} 
        	$j++;
		}
		return $orders;
	}else{
		return 'no customer exist with this email.';
	}
}
function upload_profile_image(){
    $upload_media = new Tida_Upload_media();
	$response = $upload_media->uploadFile();
}
// Written by Saif
/*
function upload_profile_image(WP_REST_Request $request)
{
    $user_id = $request->get_param('user_id');
    $new_profile_url = $request->get_param('profile_url');

    if (!$user_id || !$new_profile_url) {
        return wp_send_json([
            'success' => false,
            'message' => 'User ID or Profile URL is missing',
        ]);
    }

    $upload_media = new Tida_Upload_media();
    $response = $upload_media->updateProfileImage($user_id, $new_profile_url);

    return $response;
}
*/
function get_profile_image(WP_REST_Request $request){
    $user_id = $request->get_param('user_id');
	if($user_id){
	if($profile_url = get_user_meta($user_id, 'avatar', true)){
		return array(
			'success' => true,
			'data' => $profile_url,
			'message' => 'success',
		);
	}else {
		return array(
			'success' => true,
			'data' => 'https://secure.gravatar.com/avatar/08370a47192e85795f680753329262eb?s=96&d=mm&r=g',
			'message' => 'success',
		);
		/* return array(
			'success' => false,
			'message' => 'No Profile image is existing for this user',
		); */
	}
	}else {
		return array(
			'success' => false,
			'message' => 'User ID missing',
		);
	}
	exit;
}
function tida_apply_coupon1(WP_REST_Request $request){
	$coupon_code = $request->get_param('coupon');
    $order_id = $request->get_param('order_id');
    $coupon = new WC_Coupon( $coupon_code ); 
	$coupon_data = $coupon->get_data();
	if($coupon_data){
		$coupon_code = $coupon_data['code'];
	}
	$order = wc_get_order( $order_id ); 
	$discount_total = $coupon->get_amount();
	$discount_type = $coupon->get_discount_type();
	$order_items = $order->get_items();
	$type=[]; 
		foreach ( $order_items as $item_id => $item ) { 
			$product_id = $item['product_id'];
			$product = wc_get_product( $product_id );
			if($product->is_type('variable') && get_post_meta('_subscription_price',$product_id,true) && (get_post_meta($product_id,'variable-subscription', true) || get_post_meta($product_id,'subscription_variation', true))){
				$type[]='variable-subscription';
			} if($product->is_type('variable-subscription') ){
				$type[]='variable-subscription';
			}else if($product->is_type('variable') ){
				$type[]='variable';
			}else if($product->is_type('booking')){
				$type[] ='booking';
			} 
		} 
		if(($discount_type == 'recurring_percent' || $discount_type == 'recurring' || $discount_type == 'recurring_fee') && 
		in_array('variable-subscription',$type)){
			$subscriptions_ids = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
			$i=0;
            foreach( $subscriptions_ids as $subscription_id => $subscription ){
				$subscription->apply_coupon( $coupon_code );		
				$subscription->calculate_totals();
				$subscription->save();
            } 
			if ($coupon = $order->apply_coupon( $coupon_code )) {		
				$order->calculate_totals();
				$order->save();
				$order_items = $order->get_items();
				$results = array(
				'status' => 'true',
				'message' => 'done and apply coupon to order',
				'coupon_code'=>$coupon_code,
				'discount_total'=>$discount_total,
				'discount_type'=>$discount_type,
				'order'=>$order->get_data()
				);
				$applied_coupon = $order->get_coupon_codes();				
				if(in_array($coupon_code,$applied_coupon)){ 
					return $results; 
				} else{
					$customer = $order->get_user();
					$user_id = $customer->ID;
					$order_count = tida_getOrderbyCouponCode($coupon_code, $user_id);
					$coupon_limit = isset($coupon_data['usage_limit_per_user']) ? $coupon_data['usage_limit_per_user'] : '';
					if($order_count < $coupon_limit){
						$post_id = $coupon_data['id'];
						$meta_key = '_used_by';
						$meta_value = $user_id;
						delete_post_meta($post_id, $meta_key, $meta_value);
						if(in_array($coupon_code,$applied_coupon)){ 
							return array(
								'status' => 'success',
								'order_count'=>$order_count,
								'coupon_limit'=>$coupon_limit,
								'results'=>$results); 
						}else{
							$message = 'error to complete coupon function'; 
							$error = $coupon->errors; 
							if(!empty($error)){
								$message = $error['invalid_coupon'][0];  
							}
							return array(
								'status' => 'error1',
								'coupon'=>$coupon,
								'message' => $message,
								'order_count' => $order_count
							);
						}
					}else{
						$message = 'error to complete coupon function'; 
						$error = $coupon->errors; 
						if(!empty($error)){
							$message = $error['invalid_coupon'][0];  
						}
						return array(
							'status' => 'error1',
							'coupon'=>$coupon,
							'message' => $message,
							'order_count' => $order_count,
						);
					}
				}
			} else{
				return array(
					'status' => 'error2',
					'message' => 'error to complete coupon function'
				);
			}
		}else if(!in_array('variable-subscription',$type)){
			if ($order->apply_coupon( $coupon_code )) {		
				$order->calculate_totals();
				$order->save();
				$results = array(
				'status' => 'true',
				'message' => 'done and apply coupon to order',
				'coupon_code'=>$coupon_code,
				'discount_total'=>$discount_total,
				'discount_type'=>$discount_type,
				'order'=>$order->get_data()
				);  
				$applied_coupon = $order->get_coupon_codes();				
				if(in_array($coupon_code,$applied_coupon)){ 
					return $results; 
				} else{
					return array(
						'status' => 'error3',
						'message' => 'error to complete coupon function'
					);
				}
			} else{
				return array(
					'status' => 'error4',
					'message' => 'error to complete coupon function'
				);
			}
		}else{
			return array(
				'status' => 'error5',
				'message' => 'error to complete coupon function'
			);
		}
/*     $coupon_code = $request->get_param('coupon');
    $order_id = $request->get_param('order_id');
    $coupon = new WC_Coupon( $coupon_code );
	$order = wc_get_order( $order_id ); 
	$discount_total = $coupon->get_amount();
	$discount_type = $coupon->get_discount_type();
    if ($order->apply_coupon( $coupon_code )) {		
		$order->calculate_totals();
		$order->save();
        $results = array(
        'status' => 'true',
        'message' => 'done and apply coupon to order',
		'coupon_code'=>$coupon_code,
		'discount_total'=>$discount_total,
		'discount_type'=>$discount_type,
		'order'=>$order->get_data()
        );  
        return $results; 
    } else{
        return array(
            'status' => 'error',
            'message' => 'error to complete coupon function'
		);
    } */
}
function tida_remove_coupon($request){
    $coupon_code = $request->get_param('coupon');
    $order_id = $request->get_param('order_id');
	$order = wc_get_order( $order_id ); 
	$get_previous_coupon = $order->get_used_coupons();
	if (count($get_previous_coupon) > 0 && is_array($get_previous_coupon)) {
		foreach( $order->get_used_coupons() as $applied_coupon_code ){
			if($coupon_code == $applied_coupon_code){
				$applied_coupon = $applied_coupon_code;
			}
		}
		$order->remove_coupon( $applied_coupon );
		$code = 1;
		$message = 'Coupon successfully removed';
	}else{
		$code = 0;
		$message = 'error'; 
	}
	return $message;
}
function tida_apply_coupon($request){
    $coupon_code = $request->get_param('coupon');
    $order_id = $request->get_param('order_id');
    $coupon = new WC_Coupon( $coupon_code ); 
	$coupon_data = $coupon->get_data();
	if($coupon_data){
		$coupon_code = $coupon_data['code'];
	}
	$order = wc_get_order( $order_id ); 
	$discount_total = $coupon->get_amount();
	$discount_type = $coupon->get_discount_type();
	$order_items = $order->get_items();
	$type=[]; 
		foreach ( $order_items as $item_id => $item ) { 
			$product_id = $item['product_id'];
			$product = wc_get_product( $product_id );
			if($product->is_type('variable') && get_post_meta('_subscription_price',$product_id,true) && (get_post_meta($product_id,'variable-subscription', true) || get_post_meta($product_id,'subscription_variation', true))){
				$type[]='variable-subscription';
			} if($product->is_type('variable-subscription') ){
				$type[]='variable-subscription';
			}else if($product->is_type('variable') ){
				$type[]='variable';
			}else if($product->is_type('booking')){
				$type[] ='booking';
			} 
		} 
		if(($discount_type == 'recurring_percent' || $discount_type == 'recurring' || $discount_type == 'recurring_fee') && 
		in_array('variable-subscription',$type)){
			$subscriptions_ids = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
			$i=0;
            foreach( $subscriptions_ids as $subscription_id => $subscription ){
				$subscription->apply_coupon( $coupon_code );		
				$subscription->calculate_totals();
				$subscription->save();
            } 
			$apply_coupon = $order->apply_coupon( $coupon_code );
			if ($apply_coupon) {		
				$order->calculate_totals();
				$order->save();
				$order_items = $order->get_items();
				/* foreach ( $order_items as $item_id => $item ) { 
					$product = wc_get_product($item['product_id']);
					if($order->get_created_via() == 'rest-api' && ($product->get_type() == 'subscription_variation' || $product->get_type() == 'variable-subscription')){
						$subscriptions = wcs_get_subscriptions_for_order($order_id);
						if(empty($subscriptions)){
							$rz_subscription = new Tida_WC_Razorpay_Subscription(); 
							$rz_subsc = $rz_subscription->getPaymentrzParams($order,$order_id,$item);
						}
					}
				} */ 
				$results = array(
				'status' => 'true',
				'message' => 'done and apply coupon to order',
				'coupon_code' => $coupon_code,
				'discount_total' => $discount_total,
				'discount_type' => $discount_type,
				'order' => $order->get_data()
				); 
				$applied_coupons = $order->get_coupon_codes();				
				if(in_array($coupon_code,$applied_coupons)){ 
					if ( count( $applied_coupons ) > 1 ) {
						foreach ( $applied_coupons as $applied_coupon ) {
							if ( $applied_coupon !== $coupon_code ) {
								$order->remove_coupon($applied_coupon);
								$order->calculate_totals();
								$order->save();
								$results['error_message'] = 'Coupon "' . $applied_coupon . '" has been removed from the order.';
							}
						}
					}
					return $results; 
				} else{
					$customer = $order->get_user();
					$user_id = $customer->ID;
					$order_count = tida_getOrderbyCouponCode($coupon_code, $user_id);
					$coupon_limit = isset($coupon_data['usage_limit_per_user']) ? $coupon_data['usage_limit_per_user'] : '';
					$message = 'Error to complete coupon function'; 
					$error = $apply_coupon->errors;  
					if(!empty($error)){
						$message = $error['invalid_coupon'][0];  
					}else if(isset($error['invalid_coupon'][0])){
						$message = $error['invalid_coupon'][0];  
					}
					if($order_count < $coupon_limit && $error == 'Coupon usage limit has been reached.'){
						$post_id = $coupon_data['id'];
						$meta_key = '_used_by';
						$meta_value = $user_id;
						delete_post_meta($post_id, $meta_key, $meta_value);
						/* $request['coupon'] = $coupon_code;
						$request['order_id'] = $order_id; */
						tida_apply_coupon($request);
					}else{
						return array(
							'status' => 'error',
							'coupon'=>$apply_coupon,
							'message' => wp_strip_all_tags(html_entity_decode($message)),
							'order_count' => $order_count,
						);
					}
					/* $message = 'error to complete coupon function'; 
					$error = $apply_coupon->errors; 
					if(!empty($error)){
						$message = strip_tags($error['invalid_coupon'][0]); 
					}
					return array(
						'status' => 'error',
						'message' => $message,
					); */
				}
			} else{
				$message = 'Error to complete coupon function'; 
					$error = $apply_coupon->errors; 
					if(!empty($error)){
						$message = $error['invalid_coupon'][0];  
					}else if(isset($error['invalid_coupon'][0])){
						$message = $error['invalid_coupon'][0];  
					}
				return array(
					'status' => 'error',
					'coupon'=>$apply_coupon,
					'message' => wp_strip_all_tags(html_entity_decode($message))
				);
			}
		}else if(!in_array('variable-subscription',$type)){
			$apply_coupon = $order->apply_coupon( $coupon_code );
			if ($apply_coupon) {	
				$order->calculate_totals();
				$order->save();
				$results = array(
				'status' => 'true',
				'message' => 'done and apply coupon to order',
				'coupon_code'=>$coupon_code,
				'discount_total'=>$discount_total,
				'discount_type'=>$discount_type,
				'order'=>$order->get_data()
				);  
				/* $applied_coupon = $order->get_coupon_codes();				
				if(in_array($coupon_code,$applied_coupon)){ } */
				$applied_coupons = $order->get_coupon_codes();				
				if(in_array($coupon_code,$applied_coupons)){ 
					if ( count( $applied_coupons ) > 1 ) {
						foreach ( $applied_coupons as $applied_coupon ) {
							if ( $applied_coupon !== $coupon_code ) {
								$order->remove_coupon($applied_coupon);
								$order->calculate_totals();
								$order->save();
								$results['error_message'] = 'Coupon "' . $applied_coupon . '" has been removed from the order.';
							}
						}
					}
					return $results; 
				} else{
					$message = 'Error to complete coupon function'; 
					$error = $apply_coupon->errors; 
					if(!empty($error)){
						$message = $error['invalid_coupon'][0];  
					}else if(isset($error['invalid_coupon'][0])){
						$message = $error['invalid_coupon'][0];  
					}
					return array(
						'status' => 'error',
						'coupon'=>$apply_coupon,
						'message' => wp_strip_all_tags(html_entity_decode($message))
					);
				}
			} else{
				$error = $apply_coupon->errors; 
				if(!empty($error)){
					$message = $error['invalid_coupon'][0];  
				}else if(isset($error['invalid_coupon'][0])){
					$message = $error['invalid_coupon'][0];  
				}
				return array(
					'status' => 'error',
					'coupon'=>$apply_coupon,
					'message' => wp_strip_all_tags(html_entity_decode($message))
				);
			}
		}else{
			return array(
				'status' => 'error',
				'coupon'=>$apply_coupon,
				'message' => 'Wrong Coupon Code for Selected Item.'
			);
		}
}
function notification_find_fcm_token($data = null){ 
    if($data) {
		$order_id = $userid = 0;
		if($parameters = $data->get_json_params()){
		    $userid = $parameters['userid'];
		}else{ 
            $userid = $data->get_param('userid');
		}
	}else{
		$userid = '';
	}
    if ($userid) { 
        if ($fcm_token = get_user_meta( $userid, 'fcm_token', true )) {
			$fcmTokenList[] = $fcm_token;
            return array(
                'status' => TRUE,
                'message' => 'FCM Token retrieved successfully',
                'data' => ['fcm_token' => $fcmTokenList]
            );
        }else {
           return array(
                'status' => FALSE,
                'data' => null,
                'message' => 'User token not found'
            );
        }
    } else {
        return array(
            'status' => FALSE,
            'data' => null,
            'message' => 'Parameter missing (userid)'
        );
    }
}

function update_order_status($data){
    if($data) {
		$order_count = 0;
		$fcm_token = [];
		if($parameters = $data->get_json_params()){
		    $order_id = $parameters['order_id'];
		    $userid = $parameters['userid'];
		    $customerUserId = $parameters['customerUserId'];
		    $fcmToken = $parameters['fcmToken'];
		}else{ 
            $order_id = $data->get_param('order_id');
            $userid = $data->get_param('userid');
            $customerUserId = $data->get_param('customerUserId');
            $fcmToken = $data->get_param('fcmToken');
		}
		if($order_id){
			if ($userid) {
				$fcm_token[] = get_user_meta( $userid, 'fcm_token', true );
			}
			if ($userid){
				$fcm_token[] = get_user_meta( $customerUserId, 'fcm_token', true );
			}
			$order = wc_get_order( $order_id );
			$status = $order->get_status();
			return array(
				'status' => TRUE,
				'message' => 'Order status updated successfully',
				'data' => ['order_status' => $status,'order_type' => $order->get_meta('tida_order_type'),"fcm_token"=>$fcm_token]
			);
		}else{
			return array(
				'status' => FALSE,
				'data' => null,
				'message' => 'Parameters missing (order_id)'
			);
		}
	}else{
		return array(
            'status' => FALSE,
            'data' => null,
            'message' => 'Parameters missing (order_id)'
        );
	}
}
function notification_update_fcm_token($data){
    if($data) {
		$order_count = 0;
		if($parameters = $data->get_json_params()){
		    $userid = $parameters['userid'];
		    $fcm_token = $parameters['fcm_token'];
		}else{ 
            $userid = $data->get_param('userid');
            $fcm_token = $data->get_param('fcm_token');
		}
	}else{
		$userid = $fcm_token = $gcm_token = $token = '';
	}
    if ($userid && $fcm_token) {
        if($fcm_token) {
            update_user_meta( $userid, 'fcm_token', $fcm_token );
        }else {
            return array(
                'status' => FALSE,
                'data' => null,
                'message' => 'Failed to update FCM Token'
            );
        }
        return array(
            'status' => TRUE,
            'message' => 'FCM Token updated successfully',
            'data' => ['fcm_token' => $fcm_token]
        );
    } else {
        return array(
            'status' => FALSE,
            'data' => null,
            'message' => 'Parameters missing (userid or fcm_token)'
        );
    }
}
function get_booking_details($data) {
    if($data) {
        $user = $data->get_param('user');
		$query = new WC_Order_Query(array(
			/* 'limit' => $order_per_page, */
			'orderby' => 'date',
			'order' => 'DESC',
			'return' => 'ids',
			'status' => 'wc-completed',
			'date_query'     => array(
                'after'     => date('Y-m-d'),
                'before'    => date('Y-m-d'),
                'inclusive' => true,
            ),
			'meta_query' => array(
                array(
					'key' => 'tida_order_type',
					'value' => 'booking', 
					'compare' => 'LIKE',
				)
			),			
		));
		$bookings = array(); 
		$orders = $query->get_orders();
		if($orders){
		foreach ($orders as $order_id) {
			$order = wc_get_order( $order_id );
			$order_items = $order->get_items();
			$order_price = 0;
        	foreach ( $order_items as $item_id => $item ) {
                	$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
                    if ( $booking_ids ) {
                        $k = 0;
                        foreach ( $booking_ids as $booking_id ) {
                            $booking       = new WC_Booking( $booking_id );
                            $booking_start = date( 'Y-m-d', $booking->get_start());
                            $booking_end   = date( 'Y-m-d', $booking->get_end());
							if($booking_start == date('Y-m-d')){
								$customer = $order->get_user();
								$customer_id = $customer->ID;
								array_push($bookings,(object)[
									'order_id' => $order_id,
									'user_id' => $customer_id,
									'booking_id' => $booking_id,
									'date' => $booking_start,
									'slot_end_date' => $booking_end,
									'slot_start_time' => date("g:i a",$booking->get_start()),
									'slot_end_time' => date("g:i a",$booking->get_end()),
								]);
							}
                        }
					}
        	}
		}
			if(!empty($bookings)){
				return array(
					'status' => TRUE,
					'message' => 'Booking details retrieved successfully',
					'data' => $bookings
				);
			}else{
				return array(
					'status' => FALSE,
					'data' => null,
					'message' => 'No booking details found for today'
				);
			}
	}else{
			return array(
				'status' => FALSE,
				'data' => null,
				'message' => 'No booking details found for today'
			);
		}
		}else{
			return array(
				'status' => FALSE,
				'data' => null,
				'message' => 'No booking details found for today with booking_status = 1'
			);
		}
}
function available_payment($data){
    if($data) {
		$order_count = 0;
		if($parameters = $data->get_json_params()){
		    $partnerid = $parameters['partnerid'];	
		    
			$enable_cod = get_user_meta( $partnerid, 'enable_cod', true );
			if(isset($parameters['productId'])){
				$productId = $parameters['productId'];	
				if($productId){
					$product = wc_get_product( $productId );
					if($product->is_type('booking')) {
						$enable_cod = 0;
					}
				}	
			}
			if($enable_cod){
				return array(
					'status' => true,
					'enable_cod' => $enable_cod,
					'payment_method'=>'cod',
					'message' => 'COD is available for this partner'
				);
			}else{
				return array(
					'status' => true,
					'enable_cod' => $enable_cod,
					'payment_method'=>'cod',
					'message' => 'COD is un-available for this partner'
				);
			}
		}else{
			return array(
				'status' => FALSE,
				'data' => null,
				'message' => 'Partner id is missing'
			);
		}
	}
}
function cod_payment_toggle($data){
    if($data) {
		$order_count = 0;
		if($parameters = $data->get_json_params()){
		    $userid = $parameters['userid'];
		    $enable_cod = $parameters['enable_cod'];		
			$enable_cod = update_user_meta( $userid, 'enable_cod', $enable_cod );
			return array(
				'status' => true,
				'enable_cod' => $enable_cod,
				'payment_method'=>'cod',
				'message' => 'COD is updated for this user'
			);
		}else{
			return array(
				'status' => FALSE,
				'data' => null,
				'message' => 'Partner id is missing'
			);
		}
	}
}
function get_customer_detail($data){
    if($data) {
		$order_count = 0; 
		if($parameters = $data->get_json_params()){
		    $userid = $parameters['userid'];
		    $user_data = get_user_by( 'id', $userid );
			if($profile_url = get_user_meta($userid, 'avatar', true)){
				$user_data->avatar_url = $profile_url;
			}else {
				$user_data->avatar_url = 'https://secure.gravatar.com/avatar/08370a47192e85795f680753329262eb?s=96&d=mm&r=g';
			}
			$user_data->meta_data = get_user_meta( $userid);
			return array(
				'status' => true,
				'user_data' => $user_data
			);
		}else{
			return array(
				'status' => FALSE,
				'data' => null,
				'message' => 'Partner id is missing'
			);
		}
	}
}
function cancel_booking_for_failed_order($data){
    if($data) {
		$order_count = 0; 
		if($parameters = $data->get_json_params()){
		    $order_id = $parameters['order_id'];
			$order = wc_get_order( $order_id );
		   if($order){
			$order_items = $order->get_items();
        	foreach ( $order_items as $item_id => $item ) {
                	$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
                    if ( $booking_ids ) {
                        $k = 0;
                        foreach ( $booking_ids as $booking_id ) {
                            $booking = new WC_Booking( $booking_id );
							if( $booking->get_status() == 'in-cart' || $booking->get_status() == 'unpaid' ){
								$booking->update_status( 'cancelled', 'order_note' );
							} 
							return array(
								'status' => true,
								'user_data' => 'update booking status'
							);
						}
					}
			}
		}}else{
			return array(
				'status' => FALSE,
				'data' => null,
				'message' => 'Partner id is missing'
			);
		}
	}
}
    function login($data){
    if($data) {
		$order_count = 0; 
		if(is_array($data)){
			$user_login = $data['user_login'];
		    $user_password = $data['user_password'];
			$role = $data['role'];
		}else if($parameters = $data->get_json_params()){
		    $user_login = $parameters['user_login'];
		    $user_password = $parameters['user_password'];
			$role = $parameters['role'];
		}
		if($user_login && $user_password){
			$user = wp_signon( array(
				'user_login'    => $user_login,
				'user_password' => $user_password
			), false );
			if ( is_wp_error( $user ) ) {
				$msg = $user->get_error_message();
				return array('status' => FALSE,'message'=> 'Please check the user login details and try again.');
			}else {
				if($role == 'partner'){
					$role_check = in_array($role,$user->roles);
				}else{
					if(in_array('customer',$user->roles)){
						$role_check = in_array('customer',$user->roles);
					}else{
						$role_check = in_array('subscriber',$user->roles);
					}
				}
				if($role_check){
					if($profile_url = get_user_meta($user->ID, 'avatar', true)){
						$image = $user->image = $profile_url;
					}else{
						$image = $user->image = 'https://secure.gravatar.com/avatar/08370a47192e85795f680753329262eb?s=96&d=mm&r=g';
					}
					/* $user->meta = get_user_meta($user->ID); */
					if(get_field('phone_number','user_'.$user->ID)){
						$phone = get_field('phone_number','user_'.$user->ID);
					}else if(get_field('billing_phone','user_'.$user->ID)){
						$phone = get_field('billing_phone','user_'.$user->ID);
					}else if(get_user_meta($user->ID,'phone_number')) {
						$phone = get_user_meta($user->ID,'phone_number',true);
					}else if(get_user_meta($user->ID,'billing_phone')) {
						$phone = get_user_meta($user->ID,'billing_phone',true);
					}else{
						$phone = '';
					}
					$user_data = array('ID'=>$user->ID,'phone_number'=>$phone,'display_name'=>get_user_meta($user->ID,'first_name',true),'email'=>$user_login,'image'=>$image,'token'=>getToken($user_login,$user_password));
					return array('status' => True,'data'=>$user_data);
				}else{
					if($role == 'Partner' || $role == 'partner'){
						$message = 'Your account is in Customer app. So, please try to login to Customer app';
					}else{
						$message = 'Your account is in Partner app. So, please try to login to Partner app';
					}
					foreach( $user->roles as $role){
						$user_role = $role;
					}
					return array('status' => FALSE,'role' => $user_role,'message'=> $message);
				}
			}
		}
	}
    }
	function getToken($username,$password) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,site_url().'/wp-json/jwt-auth/v1/token');
		curl_setopt($ch, CURLOPT_POST, 1);

		# Admin credentials here
		curl_setopt($ch, CURLOPT_POSTFIELDS, "username=$username&password=$password"); 

		// receive server response ...
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);
		if ($server_output === false) {
			return get_token_data($username,$password);//die('Error getting JWT token on WordPress for API integration.');
		}
		$server_output = json_decode($server_output);

		if ($server_output === null && json_last_error() !== JSON_ERROR_NONE) {
			return get_token_data($username,$password);//die('Invalid response getting JWT token on WordPress for API integration.');
		}

		if (!empty($server_output->token)) {
			$token = $server_output->token; # Token is here
			curl_close ($ch);
			return $token;
		} else {
			return get_token_data($username,$password);//die('Invalid response getting JWT token on WordPress for API integration.');
		}
		return false;
	}
function signup( $request = null ) {
  $response = array();
  $parameters = $request->get_json_params();
  $username = sanitize_user( $parameters['email'] );
  $email = sanitize_email( $parameters['email'] );
  $password = sanitize_text_field( $parameters['password'] );
  $first_name = sanitize_text_field( $parameters['name'] );
  $phone = sanitize_text_field( $parameters['phone'] );
  if(isset($parameters['role'])){
	$role = sanitize_text_field( $parameters['role']);
  }else{
	  $role = 'customer';
  }
  $error = new WP_Error();
  if ( empty( $username ) ) {
    $error->add( 400, __( "Username field 'username' is required.", 'wp-rest-user' ), array( 'status' => 400 ) );
    return $error;
  }
  if ( empty( $email ) ) {
    $error->add(401, __( "Email field 'email' is required.", 'wp-rest-user' ), array('status' => 400 ) );
    return $error;
  }
  if ( empty( $password ) ) {
    $error->add( 404, __( "Password field 'password' is required.", 'wp-rest-user' ), array( 'status' => 400 ) );
    return $error;
  }
   if (empty($role)) {
    $role = 'customer';
   } else {
       if ( $GLOBALS['wp_roles']->is_role( $role ) ) {
        // Silence is gold
       } else {
		$error->add( 405, __("Role field 'role' is not a valid. Check your User Roles from Dashboard.", 'wp_rest_user' ), array('status' => 400 ) );
      return $error;
      }
	}
  $user_id = username_exists( $username );
  if ( ! $user_id && email_exists( $email ) == false ) {
    $user_id = wp_create_user( $username, $password, $email );
    if ( ! is_wp_error( $user_id ) ) {
		update_user_meta($user_id, 'billing_phone', $phone);
		update_user_meta($user_id, 'phone_number', $phone);
		update_user_meta($user_id, 'billing_first_name', $first_name);
		update_user_meta($user_id, 'first_name', $first_name);
		$user = get_user_by('id', $user_id);
		$user->set_role( $role );
		$response['code'] = 200;
        $response['user_id'] = $user_id;
		$response['message'] = sprintf( __( "User '%s' Registration was Successful", 'wp-rest-user' ), $username );
		$data = array("user_login"=>$username,"user_password"=>$password,"role"=>$role);
		return login($data);
    } else {
      return $user_id;
    }
  } else {
    $error->add( 406, __( "Email already exists, please try 'Reset Password'", 'wp-rest-user' ), array( 'status' => 400 ));
    return $error;
  }
  return new WP_REST_Response( $response, 123 );
}

  
  function get_items_permissions_check(\WP_REST_Request $req ) {
	$consumer_key = get_user_meta(1, 'woocommerce_api_consumer_key', true);
	$consumer_secret = get_user_meta(1, 'woocommerce_api_consumer_secret', true);
	 $auth = apache_request_headers();
    //Get only Authorization header
    $valid = $auth['Authorization'];

    // Validate
    if ($valid == "$consumer_key $consumer_secret") {
        //Do what the function should do
    } else {
        $response = 'Please use a valid authentication';
    }

    return json_encode($response);
  }
  function notification_domain(){
	  return 
	  $option = get_field('notification_domain', 'options');
	  return  array('status' => FALSE,'data'=>$option);
  }
  function update_order_payment($request = null ) {
		$response = array();
		$parameters = $request->get_json_params();
		$order_id = $parameters['order_id'];
		$status = $parameters['status'];
		$note = $parameters['note'];
		$order = wc_get_order( $order_id ); 
		if($status ){
			if(($note == "Cash on Delivery Payment Selected.") || (strpos(strtolower($note), "cash") !== false) ){
				$order->set_payment_method('cod');
				$order->set_payment_method_title( 'Pay Cash' );
			} else if($note){
				$order->add_order_note( $note );
			}
			$order->update_status( $status, 'Order Payment Status updated via api');
		}
		if(isset($parameters['meta_data'])){
			$meta_data = $parameters['meta_data'];
			if(is_array($meta_data)){
				foreach($meta_data as $meta){ 
					if($meta['key'] == 'transaction_id'){
						$order->set_transaction_id( $meta['value'] );
					}
					$order->update_meta_data( $meta['key'] , $meta['value'] ) ;
					$order->save();
				}
			}
		}
		send_notification($request);
  }
  function send_notification($request = null ) {
		$response = array();
		$parameters = $request->get_json_params();
		$userid = sanitize_user( $parameters['userid'] );
		$fcmToken = sanitize_user( $parameters['fcmToken'] );
		$order_id = sanitize_user( $parameters['order_id'] );
		$customerUserId = sanitize_user( $parameters['customerUserId'] );
		$curl = curl_init();
		$curl_url = get_field('notification_domain', 'options');
		$post_fields=json_encode(array("userid"=>$userid,"fcmToken"=>$fcmToken,"order_id"=>$order_id,"customerUserId"=>$customerUserId));
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $curl_url.'/partner_notification',
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $post_fields,
		 CURLOPT_HTTPHEADER => array(
			'Content-Type: application/json',
		 ),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$response;
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			  echo "cURL Error #:" . $err;
		} else {
			echo $response;
		}
  }  
  function wp_insert_custom_user_meta($request = null ) {
  $response = array();
  $parameters = $request->get_json_params();
	$customer_id = sanitize_user( $parameters['id'] );
  if(get_field('phone_number','user_'.$customer_id)){
		$phone = get_field('phone_number','user_'.$customer_id);
	}else if(get_field('billing_phone','user_'.$customer_id)){
		$phone = get_field('billing_phone','user_'.$customer_id);
	}else if(get_user_meta($customer_id,'phone_number')) {
		$phone = get_user_meta($customer_id,'phone_number',true);
	}else if(get_user_meta($customer_id,'billing_phone')) {
		$phone = get_user_meta($customer_id,'billing_phone',true);
	}
	if($phone && !get_user_meta($customer_id,'billing_phone',true)){
		update_user_meta($customer_id, 'billing_phone', $phone);
		update_user_meta($customer_id, 'shipping_phone', $phone);			
	}
	if($customer && !get_user_meta($customer_id,'billing_first_name',true)){
		 update_user_meta($customer_id, 'billing_first_name', $customer->first_name);	
		 update_user_meta($customer_id, 'shipping_first_name', $customer->first_name);	
	} 
	return get_user_by('id', $customer_id);
}

function update_orderstatus($request = null ) {
		$response = array();
		$parameters = $request->get_json_params();
		$orderid = sanitize_user( $parameters['orderid'] );
		$order = wc_get_order( $orderid );
		/* if($subscriptions_ids = wcs_get_subscriptions_for_order($orderid, array('order_type' => 'any'))){
			foreach( $subscriptions_ids as $subscription_id => $subscription ){
				$url = site_url();
				$username = 'ck_57b3bde95118c7b9cb1a8d38959c52eec132e844';
				$pwd = 'cs_24722f47f9f76230d1b0f41062050eabb74cacbb';
				$response = wp_remote_request(
					"{$url}/wp-json/wc/v3/orders/{$orderid}",
					array(
					'method' => 'PUT',
					'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( "$username:$pwd" )
					),
					'body' => array(
					'status' => 'cancelled', // pending, processing, on-hold, cancelled, refunded, failed
					)
					)
				);
				if( 'OK' === wp_remote_retrieve_response_message( $response ) || 'Internal Server Error' === wp_remote_retrieve_response_message( $response )) {
					return array('status' => TRUE,'message'=>'Order cancelled succesfully'); 
				}else{
					return array('status' => FALSE,'message'=>'Something went wrong');
				}
			}
		}else{} */
		if($order->update_status( 'cancelled', 'Order cancelled via API' )){
			return array('status' => TRUE,'message'=>'Order cancelled succesfully'); 
		}else{
			return array('status' => FALSE,'message'=>'Something went wrong');
		}
}
function get_booking_detail($request = null ) {
		$response = array();
		$parameters = $request->get_json_params();
		$booking_id = $parameters['id'];
		$booking = get_wc_booking( $booking_id );
		$order = $booking->get_order();
		if ($order) {
			return array('status' => TRUE,'data' => $order); 
		}else{
			$booking->update_status( 'cancelled' );
			return array('status' => FALSE,'message'=>'Something went wrong');
		}
}
function get_upcoming_ordersbypartner($request = null ) {
		$response = array();
		$parameters = $request->get_json_params();
		$order_per_page = 10;
		if(isset($parameters['limit_per_page'])){
			$order_per_page = $parameters['limit_per_page'];
		}
		if($parameters['type'] == 'past'){
			$type = '<';
		}else{
			$type = '>=';
		}
		if($parameters['sort_by'] == 'old'){
			$order = 'ASC';
		}else{
			$order = 'DESC'; 
		}
		$partner_id = $parameters['partner_id']; 
		$today = date('YmdHis');
		$args = array(
			'post_type' => 'wc_booking',
			'posts_per_page' => '-1',
			'post_parent__not_in' => array('0'),
			'return' => 'ids',
			'meta_key' => '_booking_start', 
			'post_status' => array('publish','complete','paid','confirmed','unpaid'),
			'meta_query' => array(
				'relation'=>'AND',
				array('key' => 'partner_id',
                      'value' => $partner_id, 
                      'compare' => '=',
				), 
				array('key' => '_booking_start',
                      'value' => $today, 
                      'compare' => $type,
				)
            ), 
			'orderby' => 'meta_value_num',
			'order' => $order
		);
		$count_query = new WP_Query($args);
		$order_count =  $count_query->post_count;
		$max_num_pages = ceil( $order_count / $order_per_page );
		if(isset($parameters['page'])){
			$current_page = $parameters['page'];
		}else{
			$current_page = 1;
		}
		$offset = 0; 
		if( ! 0 == $current_page) { 
			$offset = ( $order_per_page * $current_page ) - $order_per_page;
		}
		$args['limit'] = $order_per_page;
		$args['posts_per_page'] = $order_per_page;
		$args['paged'] = $current_page;
		$args['offset'] = $offset; 
		//print_r($args);
		//$post_query = new WP_Query($args);
		 $c_orders = get_posts($args); 
		$orders = array();
		$orders['total_bookings']  = $order_count;
		$orders['max_pages']  = $max_num_pages;
		$orders['current_page'] = $current_page;
		$j=0;
		$order_ids = [];
		if($count_query->have_posts()){
		$p = 1;
		foreach($c_orders as $get_upcoming_booking){
			if($get_upcoming_booking){
				$booking = new WC_Booking($get_upcoming_booking->ID);
				$booking_title = $get_upcoming_booking->post_title;
				$order_id = $get_upcoming_booking->post_parent;
				$booking_id = $get_upcoming_booking->ID;
				if($booking){		
						$order = wc_get_order( $order_id );
						$orders['data'][$j]['order_id'] = $order_id;	
						$orders['data'][$j]['booking_id'] = $booking_id;	
						if($order){
						$orders['data'][$j]['status'] = $order->get_status();
						$orders['data'][$j]['currency'] = $order->get_currency();
						$orders['data'][$j]['created_date'] = $order->get_date_created();
						if($order->get_status() == 'failed'){
							$orders['data'][$j]['transaction_id']= 'Failed';
						}else if($order->get_status() == 'cancelled'){
							$orders['data'][$j]['transaction_id']= 'Cancelled';
						}else{
							if($order->get_transaction_id()){
								$orders['data'][$j]['transaction_id']= $order->get_transaction_id();
							}else if($order->get_meta('transaction_id')){
								$orders['data'][$j]['transaction_id']= $order->get_meta('transaction_id');
							}else{
								$orders['data'][$j]['transaction_id']= '';
							}
						}
						if($order->get_payment_method() == 'cod' || $order->get_payment_method() == 'cheque'){
							$orders['data'][$j]['transaction_type']= 'offline';
						}else{
							$orders['data'][$j]['transaction_type']= 'online';
						}
						if(get_post_meta($booking_id, 'booking_cost', true)){
							$orders['data'][$j]['booking_amount'] = number_format(floatval(get_post_meta($booking_id, 'booking_cost', true)),2);
						}
						if($order->get_coupon_codes()){
							$orders['data'][$j]['discount_price']= $order->get_discount_total();
							$orders['data'][$j]['order_total']= $order->get_total() + $order->get_discount_total();
							$orders['data'][$j]['order_total_discounted_amount']= $order->get_total();
							$co = 0;
							foreach( $order->get_coupon_codes() as $coupon_code ) {
								$coupon = new WC_Coupon($coupon_code);
								$orders['data'][$j]['coupon'][$co]['code']= $coupon_code;
								$orders['data'][$j]['coupon'][$co]['amount']= $coupon->get_amount();
								$orders['data'][$j]['coupon'][$co]['discount_type']= $coupon->get_discount_type();
								$co++;
							}
						}else{
							$orders['data'][$j]['order_total'] = $order->get_total();
						}
						$i=0;
						$order_price = 0;
						$order_items = $order->get_items();
						foreach ( $order_items as $item_id => $item ) {							
							$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
							$orders['data'][$j]['order_items'] = count($booking_ids);
							/* $orders['data'][$j]['order_item_position'] = $p;
							if($order_id == $prev_order_id){
							$p++;
							} */
							$prev_order_id = $order_id;
							$product = wc_get_product($item['product_id']);
							$type = $product->get_type();
							$productId = $product->get_id();
							$partner_id = get_field('partner_manager',$productId);
							
							$user = get_user_by('id', $partner_id);
							$partner_name = $user->display_name;
							if(get_field('phone_number','user_'.$partner_id)){
								$partner_phone = get_field('phone_number','user_'.$partner_id);
							}else if(get_field('billing_phone','user_'.$partner_id)){
								$partner_phone = get_field('billing_phone','user_'.$partner_id);
							}else if(get_user_meta($partner_id,'phone_number')) {
								$partner_phone = get_user_meta($partner_id,'phone_number',true);
							}else if(get_user_meta($partner_id,'billing_phone')) {
								$partner_phone = get_user_meta($partner_id,'billing_phone',true);
							}else{
								$partner_phone = 'No Data';
							} 
							if(get_field('person_name',$order_id)){
								$person_name = get_field('person_name',$order_id);
							}else if(get_post_meta($order->get_id(), 'person_name', true)){
								$person_name = get_post_meta($order->get_id(), 'person_name', true);
							}else if(get_post_meta($order->get_id(), '_wc_order_for_name', true)){
								$person_name = get_post_meta($order->get_id(), '_wc_order_for_name', true);
							}else if($order->get_meta('_wc_order_for_name')){
								$person_name = $order->get_meta('_wc_order_for_name');
							}else if($order->get_meta('person_name')){
								$person_name = $order->get_meta('person_name');
							}else if(get_field('_wc_order_for_name',$order_id)){
								$person_name = get_field('_wc_order_for_name',$order_id);
							}else{
								$customer = get_userdata($order->customer_user);
								$person_name = $customer->display_name;
								/* $person_name = 'No Data'; */
							}
						$customer = $order->get_user();
						$customer_id = $customer->ID;
						$orders['data'][$j]['customer']['id']= $customer_id;
						$orders['data'][$j]['customer']['email']= $customer->user_email;
						$orders['data'][$j]['customer']['name']= $customer->display_name;
						$orders['data'][$j]['customer']['first_name']= $customer->first_name;
						$orders['data'][$j]['customer']['last_name']= $customer->last_name;
						$orders['data'][$j]['customer']['phone']= get_user_meta( $customer_id, 'billing_phone', true ) ;
						if(get_field('feedback',$order_id)){
							$feedback = get_field('feedback',$order_id);
						}else if(get_post_meta($order->get_id(), 'feedback', true)){
							$feedback = get_post_meta($order->get_id(), 'feedback', true);
						}else if($order->get_meta('feedback')){
							$feedback = $order->get_meta('feedback');
						}else{
							$feedback = '';
						}						
						if($feedback = get_field('feedback',$order_id)){
							$orders['data'][$j]['feedback_submitted']= true;
							$orders['data'][$j]['feedback']= $feedback;
						}else{
							$orders['data'][$j]['feedback_submitted']= false;
						}						
								$orders['data'][$j]['slot']['item']['name']= $product->get_title();
								$orders['data'][$j]['slot']['item']['address']= get_field('address',$productId);
								$orders['data'][$j]['slot']['item']['person_name']= $person_name;
								if($img = wp_get_attachment_image_src(get_post_thumbnail_id($productId),'full')){
									$orders['data'][$j]['slot']['item']['image']= $img[0];	
								}
								$orders['data'][$j]['slot']['item']['partner_name']= ($partner_name) ? $partner_name : 'No Data';
								$orders['data'][$j]['slot']['item']['partner_phone']= ($partner_phone) ? $partner_phone : 'No Data';
						
						}
									$orders['data'][$j]['slot']['slot_amount'] = $order->get_line_subtotal( $item );
						}
								$booking_start = date( 'Y-m-d', $booking->get_start());
								$booking_end   = date( 'Y-m-d', $booking->get_end());
								$hour = date ("G", $booking->get_start());
								$minute = date ("i", $booking->get_start());
								$second = date ("s", $booking->get_start());
								if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59){
									$slot_name = "Early Morning Slot ";
								}else if ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59){
									$slot_name = "Morning Slot ";
								}else if ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59){
									$slot_name = "Mid-Day Slot ";
								}else if ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59){
									$slot_name = "Evening Slot ";
								}else {
									$slot_name = "Night Slot ";
								}		
								$orders['data'][$j]['slot']['day_time'] = $hour.':'.$minute.':'.$second;
								$orders['data'][$j]['slot']['booking_id']= $booking_id;
								if(get_post_meta($booking_id, 'booking_cost', true)){
									$orders['data'][$j]['slot']['slot_amount'] = intval(get_post_meta($booking_id, 'booking_cost', true));
								}
								$orders['data'][$j]['slot']['slot_start_date']= $booking_start;
								$orders['data'][$j]['slot']['slot_end_date']= $booking_end;
								$orders['data'][$j]['slot']['slot_start_time']= date("g:i a",$booking->get_start());
								$orders['data'][$j]['slot']['slot_end_time']= date("g:i a",$booking->get_end());
								$orders['data'][$j]['slot']['slot_name']= $slot_name;		
				}
			$j++;
			} 
		}
		return $orders;
		}else{
			return array('status' => FALSE,'message'=>'No record found');
		}
}
function get_subscription_detail($order_id,$order,$j){
	$orders['id']  = $order_id;
		    $orders['status'] = $order->get_status();
		    $orders['currency'] = $order->get_currency();
		    $orders['created_date'] = $order->get_date_created();
			if($order->get_status() == 'failed'){
				$orders['transaction_id']= 'Failed';
			}else if($order->get_status() == 'cancelled'){
				$orders['transaction_id']= 'Cancelled';
			}else{
				if($order->get_transaction_id()){
					$orders['transaction_id']= $order->get_transaction_id();
				}else if($order->get_meta('transaction_id')){
					$orders['transaction_id']= $order->get_meta('transaction_id');
				}else{
					$orders['transaction_id']= '';
				}
			}			
			if($order->get_payment_method() == 'cod' || $order->get_payment_method() == 'cheque'){
				$orders['transaction_type']= 'offline';
			}else{
				$orders['transaction_type']= 'online';
			}
			if($order->get_coupon_codes()){
				$co = 0;
				$orders['discount_price']= $order->get_discount_total();
				$orders['total']= $order->get_total() + $order->get_discount_total();
				$orders['total_discounted_amount']= $order->get_total();
				foreach( $order->get_coupon_codes() as $coupon_code ) {
					$coupon = new WC_Coupon($coupon_code);
					$orders['coupon'][$co]['code']= $coupon_code;
					$orders['coupon'][$co]['amount']= $coupon->get_amount();
					$orders['coupon'][$co]['discount_type']= $coupon->get_discount_type();
					$co++;
				}
			}else{
				$orders['total'] = $order->get_total();
			}
		    $subscriptions_ids = wcs_get_subscriptions_for_order($order_id, array('order_type' => 'any'));
		    $customer = $order->get_user();
		    $customer_id = $customer->ID;
		    $orders['customer']['id']= $customer_id;
		    $orders['customer']['email']= $customer->user_email;
		    $orders['customer']['name']= $customer->display_name;
		    $orders['customer']['first_name']= $customer->first_name;
		    $orders['customer']['last_name']= $customer->last_name;
		    $orders['customer']['phone']= get_user_meta( $customer_id, 'billing_phone', true ) ;
			if(get_field('feedback',$order_id)){
				$feedback = get_field('feedback',$order_id);
			}else if(get_post_meta($order->get_id(), 'feedback', true)){
				$feedback = get_post_meta($order->get_id(), 'feedback', true);
			}else if($order->get_meta('feedback')){
				$feedback = $order->get_meta('feedback');
			}else{
				$feedback = '';
			}
			if($feedback){
				$orders['data'][$j]['feedback_submitted']= true;
				$orders['data'][$j]['feedback']= $feedback;
			}else{
				$orders['data'][$j]['feedback_submitted']= false;
			}
		    $i=0;
			$orders['order_id'] = $order_id;
			$orders['subscriptions_ids'] = $subscriptions_ids;
            foreach( $subscriptions_ids as $subscription_id => $subscription ){
                if ( WC_Subscriptions_Synchroniser::subscription_contains_synced_product( $subscription_id ) ) {
                    $length_from_timestamp = $subscription->get_time( 'next_payment' );
                }  else {
                    $length_from_timestamp = $subscription->get_time( 'start' );
                }
                $subscription_length = wcs_estimate_periods_between( $length_from_timestamp, $subscription->get_time( 'end' ), $subscription->get_billing_period() );
                $orders['subscriptions'][$i]['id'] = $subscription_id;
				$orders['subscriptions'][$i]['status'] = $subscription->get_status();
                $orders['subscriptions'][$i]['trial_end'] = $subscription->get_date( 'trial_end' );
                $orders['subscriptions'][$i]['total'] = $subscription->get_total();
                if($subscription->get_status() == 'on-hold'){
					$orders['subscriptions'][$i]['next_payment'] = 'Payment not completed';
				}else if($subscription->get_time( 'next_payment' )){
					$orders['subscriptions'][$i]['next_payment'] = date('d-m-Y',$subscription->get_time( 'next_payment' ));
				}else{
					$orders['subscriptions'][$i]['next_payment'] = 'No renewal available';
				}
				$orders['subscriptions'][$i]['start'] = date('d-m-Y',$subscription->get_time( 'start' ));
				if($subscription->get_time( 'end' )){
                $orders['subscriptions'][$i]['end'] = date('d-m-Y',$subscription->get_time( 'end' ));
				}
                $orders['subscriptions'][$i]['subscription_length'] = $subscription_length;
                $i++;
            }
			$i=0;
			$order_price = 0;
			$order_items = $order->get_items();
			$orders['order_items'] = count($order_items);
        	foreach ( $order_items as $item_id => $item ) {
        		$product = wc_get_product($item['product_id']);
        		$type = $product->get_type();
        		$productId = $product->get_id();
        	    $variation_id = $item['variation_id'];
        		$orders['items'][$i]['type'] = $type = $product->get_type();
        		$variation = wc_get_product($variation_id);
        		$partner_id = get_field('partner_manager',$productId);
        		$user = get_user_by('id', $partner_id);
                $partner_name = $user->display_name;
                if(get_field('phone_number','user_'.$partner_id)){
                    $partner_phone = get_field('phone_number','user_'.$partner_id);
                }else if(get_field('billing_phone','user_'.$partner_id)){
                    $partner_phone = get_field('billing_phone','user_'.$partner_id);
                }else if(get_user_meta($partner_id,'phone_number')) {
                    $partner_phone = get_user_meta($partner_id,'phone_number',true);
				}else if(get_user_meta($partner_id,'billing_phone')) {
                    $partner_phone = get_user_meta($partner_id,'billing_phone',true);
				}else{
                    $partner_phone = 'No Data';
                }
                if(get_field('person_name',$order_id)){
                    $person_name = get_field('person_name',$order_id);
                }else if(get_post_meta($order->get_id(), 'person_name', true)){
					$person_name = get_post_meta($order->get_id(), 'person_name', true);
				}else if(get_post_meta($order->get_id(), '_wc_order_for_name', true)){
					$person_name = get_post_meta($order->get_id(), '_wc_order_for_name', true);
				}else if($order->get_meta('_wc_order_for_name')){
					$person_name = $order->get_meta('_wc_order_for_name');
				}else if($order->get_meta('person_name')){
					$person_name = $order->get_meta('person_name');
				}else if(get_field('_wc_order_for_name',$order_id)){
                    $person_name = get_field('_wc_order_for_name',$order_id);
                }else{
					$customer = get_userdata($order->customer_user);
					$person_name = $customer->display_name;
                    /* $person_name = 'No Data'; */
                }
        		if(($type == 'subscription_variation' || $type == 'variable-subscription') && $variation){
        		    $orders['items'][$i]['academy_name']= wp_strip_all_tags(html_entity_decode($product->get_title()));
        		    $orders['items'][$i]['academy_address']= get_field('address',$productId);
        		    $orders['items'][$i]['package_name']= wp_strip_all_tags(html_entity_decode($variation->get_name()));
        		    $orders['items'][$i]['package_amount']= $variation->get_price();
        		    $orders['items'][$i]['person_name']= $person_name;					
					if($img = wp_get_attachment_image_src(get_post_thumbnail_id($productId),'full')){
						$orders['items'][$i]['image']= $img[0];	
					}
        		    $orders['items'][$i]['partner_name']= ($partner_name) ? $partner_name : 'No Data';
        		    $orders['items'][$i]['partner_phone']= ($partner_phone) ? $partner_phone : 'No Data';
        		    $i++;
        		}
        	}
			return $orders;
}
function get_upcoming_subscriptionsbypartner($request = null ) {
		$response = array();
		$parameters = $request->get_json_params();
		$partner_id = $parameters['partner_id']; 
		$today = date('Ymd');date('YmdHis');
		$order_per_page = 10;
		if(isset($parameters['limit_per_page'])){
			$order_per_page = $parameters['limit_per_page'];
		}				
		if($parameters['type'] == 'past'){
			$type = '<';
		}else{
			$type = '>=';
		}
		if($parameters['sort_by'] == 'old'){
			$order = 'ASC';
		}else{
			$order = 'DESC'; 
		}
		if($parameters['type'] == 'past'){
			$args = array(
				'limit' => -1,
				'return' => 'ids',
					 'meta_query' => array(
						'relation'=>'AND',
						array('key' => 'tida_order_type',
						  'value' => 'subscription_variation', 
						  'compare' => 'LIKE',
						),
						array('key' => 'partner_id',
							  'value' => $partner_id, 
							  'compare' => '=',
						),
						array(
						'relation'=>'OR',
						 array('key' => 'next_payment',
							  'value' => $today, 
							  'compare' => $type,
						) ,
						array(
							'key' => 'next_payment',
							'compare' => 'NOT EXISTS'
						)
					)
				),   
				'orderby' => 'date',
				'order' => $order
			); 
		}else{
			$args = array(
				'limit' => -1,
				'return' => 'ids',
				 'status' => array('wc-completed','wc-pending','wc-processing','wc-on-hold') ,
				 'meta_query' => array(
					'relation'=>'AND',
					array('key' => 'partner_id',
						  'value' => $partner_id, 
						  'compare' => '=',
					),
					 array('key' => 'next_payment',
						  'value' => $today, 
						  'compare' => $type,
					) 
				),

				'order'     => $order,
				//'meta_key' => 'next_payment',
				'orderby'   => 'date'
			); 
		}
		$query =  wc_get_orders($args); 
		$order_count =  count($query);
		$max_num_pages = ceil( $order_count / $order_per_page );
		if(isset($parameters['page'])){
			$current_page = $parameters['page'];
		}else{
			$current_page = 1;
		}
		$offset = 0; 
		if( ! 0 == $current_page) {
			$offset = ( $order_per_page * $current_page ) - $order_per_page;
		}
		$args['orderby'] = 'date';
		$args['order'] = $order;
		$args['limit'] = $order_per_page;
		$args['offset'] = $offset;
		$query_order = new WC_Order_Query($args);
		$orders = array();
		$c_orders = $query_order->get_orders();
		$orders['total_orders']  = $order_count;
		$orders['max_pages']  = $max_num_pages;
		$orders['current_page'] = $current_page;
		$j=0;
		if($c_orders > 0){
		foreach($c_orders as $order_id){
			$order = wc_get_order( $order_id );
			$orders['data'][$j] = get_subscription_detail($order_id,$order,$j);
        	$j++;
		}
		return $orders;
		}else{
			return array('status' => FALSE,'message'=>'No record found');
		}
}
function rz_subscription_data($request = null ) {
	$response = array();
	$parameters = $request->get_json_params();
	$order_id = $parameters['order_id'];
	$order = wc_get_order( $order_id );
	$order_items = $order->get_items();
	foreach ( $order_items as $item_id => $item ) { 
		$product = wc_get_product($item['product_id']);
    	if($order->get_created_via() == 'rest-api' && ($product->get_type() == 'subscription_variation' || $product->get_type() == 'variable-subscription')){
			$subscriptions = wcs_get_subscriptions_for_order($order_id, array( 'order_type' => 'any' ));
			if(empty($subscriptions)){
				$rz_subscription = new Tida_WC_Razorpay_Subscription(); 
				$rz_subsc = $rz_subscription->create_wc_subParams($order,$order_id,$item);
				$next_payment = date('YmdHis');
				$order->update_meta_data( 'created_subscription' , $next_payment ) ;
				$order->save();
			}
    	}
	}
	return array('status' => True,'message'=>$parameters);
}
function rest_create_permissions_check($data) {
	$auth = apache_request_headers();
    //Get only Authorization header
    $valid = isset($auth['authorization']) ? $auth['authorization'] : '';
	if(!$valid){
		$valid = $data->get_header('authorization');
	}
	$auth = 'Basic Y2tfNTdiM2JkZTk1MTE4YzdiOWNiMWE4ZDM4OTU5YzUyZWVjMTMyZTg0NDpjc18yNDcyMmY0N2Y5Zjc2MjMwZDFiMGY0MTA2MjA1MGVhYmI3NGNhY2Ji';
    if ($valid == $auth) {
       return $response = $auth;
    } else {
       return false;
    }
}
function add_order_feedback($request = null ) {
	$response = array();
	$parameters = $request->get_json_params();
	$order_id = $parameters['order_id'];
	$feedback = $parameters['feedback'];
	$order = wc_get_order( $order_id );
	$order->update_meta_data('feedback' , $feedback) ;
	if($order->save()){
		return array('status' => true,'feedback_submitted'=>'true');
	}else{
		return false;
	}
}
function razorpay_order_create($request = null ) {
	$response = array();
	$parameters = $request->get_json_params();
	$order_id = $parameters['order_id'];
	$order = wc_get_order( $order_id );
	$order_items = $order->get_items();
	foreach ( $order_items as $item_id => $item ) { 
		$product = wc_get_product($item['product_id']);
    	if($order->get_created_via() == 'rest-api' && ($product->get_type() == 'subscription_variation' || $product->get_type()
 == 'variable-subscription')){
    	    $subscriptions = wcs_get_subscriptions_for_order($order_id);
        	    $rz_subscription = new Tida_WC_Razorpay_Subscription(); 
        	    if(!$rz_subsc = $rz_subscription->getPaymentrzParams1($order,$order_id,$item)){
					return array(
						'status' => TRUE,
						'data' => $order,
						'rz_subsc' => $rz_subsc,
						'message' => 'subscription Created on razorpay'
					); 
				}
           if(!$order->get_meta('razorpay_subscription_id')){
           }else{
			   return array(
					'status' => FALSE,
					'data' => null,
					'message' => 'Something went wrong'
				); 
		   }
    	}
	}
}
function update_profile_data($request = null ) {
	$response = array();
	$parameters = $request->get_json_params();
	$user_id = $parameters['user_id'];
	$email_id = $parameters['email_id'];
	$signature = $parameters['signature'];
	if($phone_number = $parameters['phone_number']){
		update_user_meta($user_id, 'billing_phone', $phone_number);
		update_user_meta($user_id, 'phone_number', $phone_number);
	}
	if($name = $parameters['name']){
		update_user_meta($user_id, 'billing_first_name', $name);
		update_user_meta($user_id, 'first_name', $name);
		$response1 = wp_update_user(array( 'ID' => $user_id, 'display_name' => $name ));
	}
	if($image_url = $parameters['image_url']){		
		if(get_user_meta($user_id, 'avatar', true)){
			$timestamp = strtotime(date('Y-m-d H:i:s'));
			$public_id = pathinfo($image_url, PATHINFO_FILENAME);
			/* print_r(array('public_id'=>$public_id,'api_key'=>CLOUDINARY_API_KEY,'timestamp'=>$timestamp)); */
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => 'https://api.cloudinary.com/v1_1/'.CLOUDINARY_CLOUD_NAME.'/image/destroy',
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => '',
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_FOLLOWLOCATION => true,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => 'POST',
			  CURLOPT_POSTFIELDS => array('public_id'=>$public_id,'signature' => $signature,'api_key'=>CLOUDINARY_API_KEY,'timestamp'=>$timestamp),
			  CURLOPT_HTTPHEADER => array(
				'Authorization: Basic ' . base64_encode(CLOUDINARY_API_KEY . ':' . CLOUDINARY_API_SECRET)
			  ),
			));

			$response2 = curl_exec($curl);
			curl_close($curl);
			/* $upload_media = new Tida_Upload_Media_Cloud(CLOUDINARY_CLOUD_NAME,CLOUDINARY_API_KEY,CLOUDINARY_API_SECRET);
			$response2 = $upload_media->delete_image_from_cloudinary($public_id); */
			update_user_meta($user_id, 'avatar', $image_url);
			update_user_meta($user_id, 'avatar_signature', $signature);
		}else{
		  add_user_meta($user_id, 'avatar', $image_url);
		}
	}
	if($response1 || $response2){
			return array(
				'status' => TRUE,
				'name' => $name,
				'response1' => $response1,
				'response2' => $response2,
				'phone_number' => $phone_number,
				'image_url' => $image_url
			); 
	}else{
	   return array(
			'status' => FALSE,
			'data' => null,
			'message' => 'Something went wrong'
		); 
	}
}
function get_token_data($username,$password){
	/* $response = array();
	$parameters = $request->get_json_params();
	$username = $parameters['username'];
	$password = $parameters['password']; */
	$data = [
		  'username' => $username,
		  'password' => $password,
		];
	$jsonData = json_encode($data);
	$curl = curl_init();
	curl_setopt_array($curl, array(
	  CURLOPT_URL => 'https://tidasports.com/wp-json/jwt-auth/v1/token',
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  CURLOPT_POSTFIELDS => $jsonData,
	  CURLOPT_HTTPHEADER => array(
		'Content-Type: application/json'
	  ),
	));
	$response = curl_exec($curl);
	curl_close($curl);
	$data = json_decode($response, true); 
	return $data['token'];

}
function get_order_by_item($request = null ) {
	$response = array();
	$parameters = $request->get_json_params();
	$product_id = $parameters['product_id'];
	$orders = get_orders_ids_by_product_id( $product_id ); 
	$partner = get_field('partner_manager',$product_id);
	foreach($orders as $order_id){
		$order = wc_get_order( $order_id );
		$order->update_meta_data( 'partner_id' , $partner ) ;
		$order->save();
		$order_items = $order->get_items();
		foreach ( $order_items as $item_id => $item ) { 
			$product = wc_get_product($item['product_id']);
			if( $product->get_type() === 'booking' ) {
				$booking_ids = WC_Booking_Data_Store::get_booking_ids_from_order_item_id( $item_id );
				foreach( $booking_ids as $booking_id ) { 
					$booking = new WC_Booking($booking_id);
					$booking->update_meta_data( 'partner_id' , $partner ) ;
					$booking->save();
				}
			}elseif($product->get_type() == 'subscription_variation' || $product->get_type() == 'variable-subscription'){
					$subscriptions_ids = wcs_get_subscriptions_for_order( $order_id, array( 'order_type' => 'any' ) );
					if(!empty($subscriptions_ids)){
						foreach( $subscriptions_ids as $subscription_id => $subscription ){
							update_post_meta( $subscription_id,'partner_id' , $partner ) ;
					   }
					}
					
			}
		}
	}
	return $orders;
}
function get_simple_products($request = null ) {
	$response = array();
	$parameters = $request->get_json_params();
	$posts_per_page = (isset($parameters['limit']) ? $parameters['limit'] : 10 );
	$paged = (isset($parameters['page']) ? $parameters['page'] : 1 );
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => $posts_per_page,
		'paged'          => $paged,
		'post_status'    => 'publish',
		'meta_query'     => array(
			array(
				'key'     => 'product_type',
				'value'   => 'Shop',
				'compare' => '='
			),
		),
	);
	$query = new WP_Query( $args );
	$data=array();
	if ( $query->have_posts() ) {
		$i = 0;
		while ( $query->have_posts() ) {
			$query->the_post();
			$productId = get_the_ID();
			$product  = wc_get_product( get_the_ID() );
			$data[$i]['id'] = get_the_ID();
			$data[$i]['name'] = get_the_title();
			$data[$i]['price'] = $product->get_price();
			$data[$i]['currency_symbol'] = html_entity_decode(get_woocommerce_currency_symbol());
            $data[$i]['currency'] = get_option( 'woocommerce_currency' );
            $data[$i]['id'] = $productId;
            $data[$i]['title'] = $product->get_name();
            $data[$i]['content'] = html_entity_decode(wp_strip_all_tags($product->get_description()));
            $data[$i]['address']= get_field('address',$productId);
            $data[$i]['latitude']= get_field('latitude',$productId);
            $data[$i]['longitude']= get_field('longitude',$productId);
            $data[$i]['latitude']= get_field('latitude',$productId);
            $data[$i]['partner_id']= get_field('partner_manager',$productId);
			$sports = get_the_terms( $productId, 'sport' );
			if ( ! empty( $sports ) && ! is_wp_error( $sports ) ) {
				foreach ( $sports as $sport ) {
					$data[$i]['sport']['id'] = $sport->term_id;
					$data[$i]['sport']['name'] = html_entity_decode(wp_strip_all_tags( $sport->name ));
				}
			}
			$product_cats = get_the_terms( $productId, 'product_cat' );
			if ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ) {
				foreach ( $product_cats as $cat ) {
					$data[$i]['location']['id'] = $cat->term_id;
					$data[$i]['location']['name'] = html_entity_decode(wp_strip_all_tags( $cat->name ));
				}
			}
			$i++;
		}
		return array('status' => TRUE,'total_products'=>$query->found_posts,'total_pages','data'=>$data);
	} else {
		return array('status' => FALSE,'message'=>'No simple products found.');
	}

	// Reset post data
	wp_reset_postdata();

}

function getslotsbydate1($data){
	    $dates = array();
        if($data){
            if(isset($data['id'])){
        	    $id = $data['id'];				
				$product = wc_get_product( $id );
				if($product){  
					if($product->get_status() != 'publish'){
					   return array('status'=>'failure','message'=>'Item is not available for booking.'); 
				}}else{
					 return array('status'=>'failure','message'=>'Item is not available for booking.'); 
				}
        	    $year = $data['year'];
        	    $month = $data['month'];
        	    $day = $data['day'];
        	    $resource_id = $data['resource_id'];
        	    if($data['slots_for_days']){
            	    $slots_for_days = $data['slots_for_days'];
            	    $dates[] = $date0 = date( 'Y-m-d', strtotime("{$year}-{$month}-{$day}" ) );
            	    for ($x = 1; $x <= $slots_for_days; $x++) {
                        $dates[] = date( 'Y-m-d', strtotime( $date0 . " +{$x} day" ) );
                    }
        	    }else{
        	        $dates[] = date( 'Y-m-d', strtotime("{$year}-{$month}-{$day}" ) );
        	    }
            }else{
        	    $id = $data->get_param('id');
        	    $year = $data->get_param('year');
        	    $month = $data->get_param('month');
        	    $day = $data->get_param('day');
        	    $resource_id = $data->get_param('resource_id');
        	    if($data->get_param('slots_for_days')){
            	    $slots_for_days = $data->get_param('slots_for_days');
            	    $dates[] = $date0 = date( 'Y-m-d', strtotime("{$year}-{$month}-{$day}" ) );
            	    for ($x = 0; $x <= $slots_for_days; $x++) {
                        $dates[] = date( 'Y-m-d', strtotime( $date0 . " +{$x} day" ) );
                    }
        	    }else{
        	        $dates[] = date( 'Y-m-d', strtotime("{$year}-{$month}-{$day}" ) );
        	    }
            }
        }
        if($year && $month && $day && $id){
            foreach($dates as $date){
        	    $posted['add-to-cart'] = $id;
        	    $year = date( 'Y',strtotime($date));
        	    $month = date( 'm',strtotime($date));
        	    $day = date( 'd',strtotime($date));
        	    $posted['wc_bookings_field_start_date_year'] = $year;
        	    $posted['wc_bookings_field_start_date_month'] = $month;
        	    $posted['wc_bookings_field_start_date_day'] = $day;
        	    $posted['wc_bookings_field_resource'] = $resource_id;
        		$booking_id   = absint( $posted['add-to-cart'] );
        		$product = get_wc_product_booking( wc_get_product( $booking_id ) );
        		if ( ! $product ) {
        			return 'No Booking data available.';
        		}
        		if ( ! empty( $posted['wc_bookings_field_start_date_year'] ) && ! empty( $posted['wc_bookings_field_start_date_month'] ) && ! empty( $posted['wc_bookings_field_start_date_day'] ) ) {
        			$year      = max( date( 'Y' ), absint( $posted['wc_bookings_field_start_date_year'] ) );
        			$month     = absint( $posted['wc_bookings_field_start_date_month'] );
        			$day       = absint( $posted['wc_bookings_field_start_date_day'] );
        			$timestamp = strtotime( "{$year}-{$month}-{$day}" );
        		}
        		if ( empty( $timestamp ) ) {
        			return 'Please enter a valid date.';
        		}
        		if ( ! empty( $posted['wc_bookings_field_duration'] ) ) {
        			$interval = (int) $posted['wc_bookings_field_duration'] * $product->get_duration();
        		} else {
        			$interval = $product->get_duration();
        		}
        		$unit = $product->get_duration_unit().'s';
        		$base_interval = $product->get_duration();
        		if ( 'hour' === $product->get_duration_unit() ) {
					$unit = $product->get_duration_unit();
        			/* $interval      = $interval * 60;
        			$base_interval = $base_interval * 60; */
        		}
        		$first_block_time     = $product->get_first_block_time();
        		$from                 = strtotime( $first_block_time ? $first_block_time : 'midnight', $timestamp );
        		$standard_from        = $from;
                if ( isset( $posted['get_prev_day'] ) ) {
        			$from = strtotime( '- 1 day', $from );
        		}
        		$to = strtotime( '+ 1 day', $standard_from ) + $interval;
        		if ( isset( $posted['get_next_day'] ) ) {
        			$to = strtotime( '+ 1 day', $to );
        		}
        		$to = strtotime( 'midnight', $to ) - 1;
        		$resource_id_to_check = ( ! empty( $posted['wc_bookings_field_resource'] ) ? (int) $posted['wc_bookings_field_resource'] : 0 );
        		$resource             = $product->get_resource( absint( $resource_id_to_check ) );
        		$resources            = $product->get_resources();
        		if ( $resource_id_to_check && $resource ) {
        			$resource_id_to_check = $resource->ID;
        		} elseif ( $product->has_resources() && $resources && count( $resources ) === 1 ) {
        			$resource_id_to_check = current( $resources )->ID;
        		} else {
        			$resource_id_to_check = 0;
        		}
        		$booking_form = new WC_Booking_Form( $product ); 
        		$blocks = $product->get_blocks_in_range( $from, $to, array( $interval, $base_interval ), $resource_id_to_check );
				//echo '<br/>interval: '.$interval.' base_interval: '.$base_interval.' resource_id_to_check: '.$resource_id_to_check.' from: '.$from.' to: '.$to;
                $available_blocks = wc_bookings_get_time_slots( $product, $blocks, array( $interval, $base_interval ), $resource_id_to_check, $from, $to );
        		$k = 0;  
    			foreach ( $available_blocks as $block => $quantity ) {
    				if ( $quantity['available'] > 0 ) { 
    		    		$product    = wc_get_product( $booking_id );
    				    $posted['wc_bookings_field_start_date_time'] = $start_date_time = date('Y-m-d H:i:s',$block);
    				    $booking_data = wc_bookings_get_posted_data( $posted, $product );
                		$cost = WC_Bookings_Cost_Calculation::calculate_booking_cost( $booking_data, $product );
                		if ( is_wp_error( $cost ) ){
                		}else{
    					if ( $quantity['booked']) { 
                            $date_range_blocks[$date][$k]['interval'] = $interval .' '. $unit;
                            $date_range_blocks[$date][$k]['start_date_time'] = $start_date_time;
                            $date_range_blocks[$date][$k]['end_date_time'] = date('Y-m-d H:i:s', strtotime("$start_date_time +$interval $unit"));
                            $date_range_blocks[$date][$k]['slot_cost'] = $cost;
                            $date_range_blocks[$date][$k]['slot_time'] = '(' . sprintf( _n( '%d left', '%d left', $quantity['available'], 'woocommerce-bookings' ), absint( $quantity['available'] ) ) . ')';
                            $date_range_blocks[$date][$k]['slot_start_time'] = date('g:i a', strtotime("$start_date_time"));
                            $date_range_blocks[$date][$k]['slot_end_time'] = date('g:i a', strtotime("$start_date_time +$interval $unit"));
                            $hour = date ("G", strtotime("$start_date_time"));
                            $minute = date ("i", strtotime("$start_date_time"));
                            $second = date ("s", strtotime("$start_date_time"));
                            if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59){
                                $slot_name = "Early Morning Slot ";
                            }else if ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59){
                                $slot_name = "Morning Slot ";
                            }else if ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59){
                                $slot_name = "Mid-Day Slot ";
                            }else if ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59){
                                $slot_name = "Evening Slot ";
                            }else {
                                $slot_name = "Night Slot ";
                            }
                            $date_range_blocks[$date][$k]['slot_name'] = $slot_name;
                            $date_range_blocks[$date][$k]['start'] = date('YmdHis', strtotime("$start_date_time"));
                            $date_range_blocks[$date][$k]['end'] = date('YmdHis', strtotime("$start_date_time +$interval $unit"));
                            $date_range_blocks[$date][$k]['start_timestamp'] = strtotime("$start_date_time");
                            $date_range_blocks[$date][$k]['end_timestamp'] = strtotime("$start_date_time +$interval $unit");
    				        $k++;
    					} else { 
                            $curDateTime = date("Y-m-d H:i:s");
                            $myDate = date("Y-m-d H:i:s", strtotime("$start_date_time"));
                            if($myDate > $curDateTime ){
                                $date_range_blocks[$date][$k]['interval'] = $interval .' '. $unit;
                                $date_range_blocks[$date][$k]['start_date_time'] = $start_date_time;
                                $date_range_blocks[$date][$k]['end_date_time'] = date('Y-m-d H:i:s', strtotime("$start_date_time +$interval $unit"));
                                $date_range_blocks[$date][$k]['slot_cost'] = $cost;
                                $date_range_blocks[$date][$k]['slot_availability'] = date_i18n( wc_bookings_time_format(), $block );
                                $date_range_blocks[$date][$k]['slot_start_time'] = date('g:i a', strtotime("$start_date_time"));
                                $date_range_blocks[$date][$k]['slot_end_time'] = date('g:i a', strtotime("$start_date_time +$interval $unit"));
                                $hour = date ("G", strtotime("$start_date_time"));
                                $minute = date ("i", strtotime("$start_date_time"));
                                $second = date ("s", strtotime("$start_date_time"));
                                if ($hour == 00 && $hour <= 9 && $minute <= 59 && $second <= 59){
                                    $slot_name = "Morning Slot ";
                                }else if ($hour >= 10 && $hour <= 11 && $minute <= 59 && $second <= 59){
                                    $slot_name = "Morning Slot ";
                                }else if ($hour >= 12 && $hour <= 15 && $minute <= 59 && $second <= 59){
                                    $slot_name = "Afternoon Slot ";
                                }else if ($hour >= 16 && $hour <= 23 && $minute <= 59 && $second <= 59){
                                    $slot_name = "Evening Slot ";
                                }else {
                                    $slot_name = "Night Slot ";
                                }
                                $date_range_blocks[$date][$k]['slot_name'] = $slot_name;
                                $date_range_blocks[$date][$k]['start'] = date('YmdHis', strtotime("$start_date_time"));
                                $date_range_blocks[$date][$k]['end'] = date('YmdHis', strtotime("$start_date_time +$interval $unit"));
                                $date_range_blocks[$date][$k]['start_timestamp'] = strtotime("$start_date_time");
                                $date_range_blocks[$date][$k]['end_timestamp'] = strtotime("$start_date_time +$interval $unit");
    				            $k++;
                            }
    					}
    				}
    				}
    			} 
            }
		if ( empty( $date_range_blocks ) ) { 
			return 'No blocks available.';
		}
	    return $date_range_blocks;
    }else{
	    return 'Parameter Missing.';
	}
}
function getslots_availability($data){
	if($data->get_param('id')) { 
	    $product_data = array(); 
        $productId = $data->get_param('id');
        $year = $data->get_param('year');
	    $month = $data->get_param('month');
	    $day = $data->get_param('day');
	    $product = wc_get_product( $productId );
	    if($product){  
			if($product->get_status() != 'publish'){
			   return array('status'=>'failure','message'=>'Item is not available for booking.'); 
			}
            $product_data['status'] = 'success';
            $product_data['data']['currency_symbol'] = html_entity_decode(get_woocommerce_currency_symbol());
            $product_data['data']['currency'] = get_option( 'woocommerce_currency' );
            $product_data['data']['id'] = $productId;
            $product_data['data']['title'] = $product->get_name();
            $product_data['data']['content'] = wp_strip_all_tags($product->get_description());
            $product_data['data']['address']= get_field('address',$productId);
            $product_data['data']['latitude']= get_field('latitude',$productId);
            $product_data['data']['longitude']= get_field('longitude',$productId);
            $product_data['data']['latitude']= get_field('latitude',$productId);
            $product_data['data']['partner_id']= get_field('partner_manager',$productId);
            $product_data['data']['flood_lights']= get_field('flood_lights',$productId);
            $amenities = get_field('amenities',$productId);
            $amenities_with_key = [];
            $l = 0;
			$site_url = site_url();
            $array = array(
				"Swimming Pool" => "$site_url/wp-content/uploads/2024/05/Swimming.png",
				"PlayGround" => "$site_url/wp-content/uploads/2024/05/Playground.png",
				"CCTV" => "$site_url/wp-content/uploads/2024/05/CCTV1.png",
				"Transportation" => "$site_url/wp-content/uploads/2024/05/Transportation1.png",
				"Online" => "$site_url/wp-content/uploads/2024/05/online-1.png",
				"First Aid" => "$site_url/wp-content/uploads/2024/03/band-aid.png",
				"Flood Lights" => "$site_url/wp-content/uploads/2024/03/stadium.png",
				"Benches & Seating" => "$site_url/wp-content/uploads/2024/03/waiting-room.png",
				"Restrooms" => "$site_url/wp-content/uploads/2024/03/toilet.png",
				"Equipment" => "$site_url/wp-content/uploads/2024/05/Equipment2.png",
				"Cricket Kits" => "$site_url/wp-content/uploads/2024/05/cricket-1.png",
				"Locker" => "$site_url/wp-content/uploads/2024/03/lockers.png",
				"Parking" => "$site_url/wp-content/uploads/2024/03/parking.png",
				"Wifi" => "$site_url/wp-content/uploads/2024/03/wifi.png",
				"Drinking Water" => "$site_url/wp-content/uploads/2024/03/water.png",
				"Recorded Gameplay" => "$site_url/wp-content/uploads/2024/05/CCTV1.png"
			);
            foreach($amenities as $amenity){
                $key = strtolower(str_replace(' ','-',$amenity));
                $key = strtolower(str_replace(array("@","#","!","$","%","&","*","^","(",")"," "),'',$key));
                $key = strtolower(str_replace('--','-',$key));
                $key = preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', '', $key);
                $amenities_with_key[$l]['enum_name']  = $key;
                $amenities_with_key[$l]['display_name'] = html_entity_decode($amenity);
				if (array_key_exists($amenity,$array))
				{
					$amenities_with_key[$l]['image'] = $array[$amenity];
				}else{
					$amenities_with_key[$l]['image'] = "$site_url/wp-content/uploads/2024/03/waiting-room.png";
				}
                $l++;
            }
            $product_data['data']['amenities']= $amenities_with_key;
            $image_links[0] = get_post_thumbnail_id($productId);
            $img = wp_get_attachment_image_src($image_links[0],'full');
            if(is_array($img)){
                $product_data['data']['image'] = $img[0];
                $product_data['data']['gallery'][0] = $img[0];
            }
            $attachment_ids = $product->get_gallery_image_ids();
            $j = 1;
            foreach( $attachment_ids as $attachment_id ) {
                $product_data['data']['gallery'][$j] = wp_get_attachment_url( $attachment_id );
                $j++;
            }
			$when = date('Y-m-d', strtotime(' +1 day'));
            $product_data['data']['type'] = $type = $product->get_type(); 
           if($type === 'booking'){
                $product_data['data']['date'] = $year.'-'.$month.'-'.$day;
                $product_data['data']['slots'] = getslotsbydate1($data);
           }
            return $product_data;
	    }else{
	       return array('status'=>'failure','message'=>'No item found with this id.'); 
	    }
	}else{
	    return array('status'=>'failure','message'=>'Parameter Missing.');
	}
}
add_action( 'rest_api_init', function () {	
    register_rest_route('tida/v1', '/getslots_availability/',
        array(
            'methods'  => 'POST',
            'callback' => 'getslots_availability',
			'permission_callback' => '__return_true'
        )
    );
    register_rest_route('tida/v1', '/get_simple_products/',
        array(
            'methods'  => 'POST',
            'callback' => 'get_simple_products',
			'permission_callback' => '__return_true'
        )
    );
    register_rest_route('tida/v1', '/get_order_by_item/',
        array(
            'methods'  => 'POST',
            'callback' => 'get_order_by_item',
			'permission_callback' => '__return_true'
        )
    );
    register_rest_route('tida/v1', '/update_profile_data/',
        array(
            'methods'  => 'POST',
            'callback' => 'update_profile_data',
			'permission_callback' => 'rest_create_permissions_check'
        )
    );
    register_rest_route('tida/v1', '/razorpay_order_create/',
        array(
            'methods'  => 'POST',
            'callback' => 'razorpay_order_create',
			'permission_callback' => 'rest_create_permissions_check'
        )
    );
    register_rest_route('tida/v1', '/add_order_feedback/',
        array(
            'methods'  => 'POST',
            'callback' => 'add_order_feedback',
			'permission_callback' => 'rest_create_permissions_check'
        )
    );
    register_rest_route('tida/v1', '/rz_subscription_data/',
        array(
            'methods'  => 'POST',
            'callback' => 'rz_subscription_data',
			'permission_callback' => 'rest_create_permissions_check'
        )
    );
    register_rest_route('tida/v1', '/get_upcoming_subscriptionsbypartner/',
        array(
            'methods'  => 'POST',
            'callback' => 'get_upcoming_subscriptionsbypartner',
			'permission_callback' => 'rest_create_permissions_check'
        )
    );
    register_rest_route('tida/v1', '/get_upcoming_ordersbypartner/',
        array(
            'methods'  => 'POST',
            'callback' => 'get_upcoming_ordersbypartner',
			'permission_callback' => 'rest_create_permissions_check'
        )
    );
    register_rest_route('tida/v1', '/get_booking_detail/',
        array(
            'methods'  => 'POST',
            'callback' => 'get_booking_detail',
			'permission_callback' => 'rest_create_permissions_check'
        )
    );
    register_rest_route('tida/v1', '/update_orderstatus/',
        array(
            'methods'  => 'POST',
            'callback' => 'update_orderstatus',
			'permission_callback' => 'rest_create_permissions_check'
        )
    );
    register_rest_route('tida/v1', '/wp_insert_custom_user_meta/',
        array(
            'methods'  => 'POST',
            'callback' => 'wp_insert_custom_user_meta',
			'permission_callback' => 'rest_create_permissions_check'
        )
    );
	register_rest_route('tida/v1', '/notification_domain/',
        array(
            'methods'  => 'POST',
            'callback' => 'notification_domain',
			'permission_callback' => 'rest_create_permissions_check'
        )
    );
	register_rest_route('tida/v1/notification_domain', '/partner_notification/',
        array(
            'methods'  => 'POST',
            'callback' => 'send_notification',
			'permission_callback' => 'rest_create_permissions_check'
        )
    );
	register_rest_route('tida/v1/notification_domain', '/update_order_payment/',
        array(
            'methods'  => 'POST',
            'callback' => 'update_order_payment',
			'permission_callback' => 'rest_create_permissions_check'
        )
    );
    register_rest_route('tida/v1', '/login/',
        array(
            'methods'  => 'POST',
            'callback' => 'login',
			'permission_callback' => '__return_true'
        )
    );
    register_rest_route('tida/v1', '/signup/',
        array(
            'methods'  => 'POST',
            'callback' => 'signup',
			'permission_callback' => '__return_true'
        )
    );
	 /* register_rest_route( 'wp/v2', 'users/register', array(
		'methods' => 'POST',
		'callback' => 'wc_rest_user_endpoint_handler',
        'permission_callback' => '__return_true'
	  ) ); */
    register_rest_route('tida/v1', 'cancel_booking_for_failed_order', array(
        'methods' => 'POST',
        'callback' => 'cancel_booking_for_failed_order',
		'permission_callback' => 'rest_create_permissions_check'
    ));  
    register_rest_route('tida/v1', 'get_customer_detail', array(
        'methods' => 'POST',
        'callback' => 'get_customer_detail',
		'permission_callback' => 'rest_create_permissions_check'
    ));  
    register_rest_route('tida/v1', 'cod_payment_toggle', array(
        'methods' => 'POST',
        'callback' => 'cod_payment_toggle',
		'permission_callback' => 'rest_create_permissions_check'
    ));  
    register_rest_route('tida/v1', 'get_available_payment', array(
        'methods' => 'POST',
        'callback' => 'available_payment',
		'permission_callback' => 'rest_create_permissions_check'
    ));  
    register_rest_route('tida/v1/notification', 'get_booking_details', array(
        'methods' => 'POST',
        'callback' => 'get_booking_details',
		'permission_callback' => 'rest_create_permissions_check'
    ));  
    register_rest_route('tida/v1/notification', 'find_fcm_token', array(
        'methods' => 'POST',
        'callback' => 'notification_find_fcm_token',
		'permission_callback' => 'rest_create_permissions_check'
    ));  
    register_rest_route('tida/v1/notification', 'update_order_status', array(
        'methods' => 'POST',
        'callback' => 'update_order_status',
		'permission_callback' => 'rest_create_permissions_check'
    )); 
    register_rest_route('tida/v1/notification', 'update_fcm_token', array(
        'methods' => 'POST',
        'callback' => 'notification_update_fcm_token',
		'permission_callback' => 'rest_create_permissions_check'
    ));  
    register_rest_route('tida/v1', 'remove_coupon', array(
        'methods' => 'POST',
        'callback' => 'tida_remove_coupon',
		'permission_callback' => 'rest_create_permissions_check'
    ));   
    register_rest_route('tida/v1', 'apply_coupon', array(
        'methods' => 'POST',
        'callback' => 'tida_apply_coupon',
		'permission_callback' => 'rest_create_permissions_check'
    ));     
    register_rest_route('tida/v1', 'apply_coupon1', array(
        'methods' => 'POST',
        'callback' => 'tida_apply_coupon1',
		'permission_callback' => 'rest_create_permissions_check'
    ));     
    register_rest_route('tida/v1', 'get_profile_image', array(
        'methods' => 'POST',
        'callback' => 'get_profile_image',
		'permission_callback' => 'rest_create_permissions_check'
    ));    
    register_rest_route('tida/v1', 'upload_profile_image', array(
        'methods' => 'POST',
        'callback' => 'upload_profile_image',
		'permission_callback' => 'rest_create_permissions_check'
    ));   
    register_rest_route('tida/v1', 'create_booking', array(
        'methods' => 'POST',
        'callback' => 'create_booking',
		'permission_callback' => 'rest_create_permissions_check'
      ));
    register_rest_route('partner/v1', 'register', array(
        'methods' => 'POST',
        'callback' => 'wc_rest_user_endpoint_handler',
		'permission_callback' => 'rest_create_permissions_check'
     ));
    register_rest_route( 'tidasports/v1', 'update_password', array(
        'methods' => 'POST',
        'callback' => 'update_password',
		'permission_callback' => 'rest_create_permissions_check'
    ) );
    register_rest_route( 'tidasports/v1', 'getpackages', array(
        'methods' => 'POST',
        'callback' => 'getpackages',
		'permission_callback' => 'rest_create_permissions_check'
    ) );
 register_rest_route( 'tidasports/v1', 'getslotsbyid', array(
    'methods' => 'POST',
    'callback' => 'getslotsbydate',
	'permission_callback' => 'rest_create_permissions_check'
  ) );
 register_rest_route( 'tidasports/v1', 'getslots', array(
    'methods' => 'POST',
    'callback' => 'getslots',
	'permission_callback' => 'rest_create_permissions_check'
  ) );
 register_rest_route( 'tidasports/v1', 'getallshowcase', array(
    'methods' => 'POST',
    'callback' => 'getallshowcase',
	'permission_callback' => 'rest_create_permissions_check'
  ) );
 register_rest_route( 'tidasports/v1', 'getuseridbyemail', array(
    'methods' => 'POST',
    'callback' => 'getuseridbyemail',
	'permission_callback' => 'rest_create_permissions_check'
  ) );
 register_rest_route( 'tidasports/v1', 'getproductbycity', array(
    'methods' => 'POST',
    'callback' => 'getproductbycity',
	'permission_callback' => 'rest_create_permissions_check'
  ) );
 register_rest_route( 'tidasports/v1', 'getproductbysports', array(
    'methods' => 'POST',
    'callback' => 'getproductbysports',
	'permission_callback' => 'rest_create_permissions_check'
  ));
 register_rest_route( 'tidasports/v1', 'getproductbysearch', array(
    'methods' => 'POST',
    'callback' => 'getproductbysearch',
	'permission_callback' => 'rest_create_permissions_check'
  ));
 register_rest_route( 'tidasports/v1', 'getproductbypartner', array(
    'methods' => 'POST',
    'callback' => 'getproductbypartner',
	'permission_callback' => 'rest_create_permissions_check'
  ));
 register_rest_route( 'tidasports/v1', 'getallsports', array(
    'methods' => 'POST',
    'callback' => 'getallsports',
	'permission_callback' => 'rest_create_permissions_check'
  ));
 register_rest_route( 'tidasports/v1', 'getalllocations', array(
    'methods' => 'POST',
    'callback' => 'getalllocations',
	'permission_callback' => 'rest_create_permissions_check'
  ));
 register_rest_route( 'tidasports/v1', 'getallproducts', array(
    'methods' => 'POST',
    'callback' => 'getallproducts',
	'permission_callback' => 'rest_create_permissions_check'
  ));
 register_rest_route( 'tidasports/v1', 'getcustomeracademyorders', array(
    'methods' => 'POST',
    'callback' => 'getcustomeracademyorders',
	'permission_callback' => 'rest_create_permissions_check'
  ));
 register_rest_route( 'tidasports/v1', 'getcustomervenueorders', array(
    'methods' => 'POST',
    'callback' => 'getcustomervenueorders',
	'permission_callback' => 'rest_create_permissions_check'
  ));
 register_rest_route( 'tidasports/v1', 'getcustomersubscriptionorders', array(
    'methods' => 'POST',
    'callback' => 'getcustomersubscriptionorders',
	'permission_callback' => 'rest_create_permissions_check'
  ));
 register_rest_route( 'tidasports/v1', 'forgot_password', array(
    'methods' => 'POST',
    'callback' => 'forgot_password',
	'permission_callback' => 'rest_create_permissions_check'
  ));
 register_rest_route( 'tidasports/v1', 'getallorders', array(
    'methods' => 'POST',
    'callback' => 'getallorders',
	'permission_callback' => 'rest_create_permissions_check'
  ));  
 register_rest_route( 'tidasports/v1', 'getpartneracademyorders', array(
    'methods' => 'POST',
    'callback' => 'getpartneracademyorders',
	'permission_callback' => 'rest_create_permissions_check'
  ));
 register_rest_route( 'tidasports/v1', 'getpartnervenueorders', array(
    'methods' => 'POST',
    'callback' => 'getpartnervenueorders',
	'permission_callback' => 'rest_create_permissions_check'
  ));
 register_rest_route( 'tidasports/v1', 'getpartnersubscriptionorders', array(
    'methods' => 'POST',
    'callback' => 'getpartnersubscriptionorders',
	'permission_callback' => 'rest_create_permissions_check'
  ));
 register_rest_route( 'tidasports/v1', 'get_order_detail', array(
    'methods' => 'POST',
    'callback' => 'get_order_detail',
	'permission_callback' => 'rest_create_permissions_check'
  ));
});
?>