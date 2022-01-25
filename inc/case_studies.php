<?php

if ( ! function_exists('case_studies') ) {

// Register Custom Post Type
function case_studies() {
	  // hide if none present
  $casestudy = get_posts( 
    [
        'post_type' => 'case_study', 
        'posts_per_page' => 1,
        'fields' => 'ids'
    ] 
  );

  if (!$casestudy)
    return;
  
		$labels = array(
			'name'                  => _x( 'Case Studies', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Case Study', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Case Studies', 'text_domain' ),
			'name_admin_bar'        => __( 'Case Study', 'text_domain' ),
			'archives'              => __( 'Case Study Archives', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All Case Studies', 'text_domain' ),
			'add_new_item'          => __( 'Case Study', 'text_domain' ),
			'add_new'               => __( 'Add Case Study', 'text_domain' ),
			'new_item'              => __( 'New Case Study', 'text_domain' ),
			'edit_item'             => __( 'Edit Case Study', 'text_domain' ),
			'update_item'           => __( 'Update Case Study', 'text_domain' ),
			'view_item'             => __( 'View Case Studies', 'text_domain' ),
			'search_items'          => __( 'Search Case Studies', 'text_domain' ),
			'not_found'             => __( 'Case Study Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
			'items_list'            => __( 'Items list', 'text_domain' ),
			'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
		);
		$args = array(
			'label'                 => __( 'Case Study', 'text_domain' ),
			'description'           => __( 'Customer Case Studies', 'text_domain' ),
			'labels'                => $labels,
			'supports'							=> array( 'title', 'editor', 'excerpt', 'author', 'thumbnail',  'revisions' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-chart-area',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,		
			'exclude_from_search'   => false,
			'taxonomies' => array('category'),
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
	
			//register_post_type( 'case_study', $args );
		ff_register_post_type( 'case_study', $args, 'enable_case_studies' );

}
add_action( 'init', 'case_studies' );
}





if ( ! function_exists('datasheets') ) {

// Register Custom Post Type
function datasheets() {
	
	  // hide if none present
  $casestudy = get_posts( 
    [
        'post_type' => 'datasheet', 
        'posts_per_page' => 1,
        'fields' => 'ids'
    ] 
  );

  if (!$casestudy)
    return;

  $labels = array(
			'name'                  => _x( 'Datasheets', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Datasheet', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Datasheets', 'text_domain' ),
			'name_admin_bar'        => __( 'Datasheet', 'text_domain' ),
			'archives'              => __( 'Datasheet Archives', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All Datasheets', 'text_domain' ),
			'add_new_item'          => __( 'Datasheet', 'text_domain' ),
			'add_new'               => __( 'Add Datasheet', 'text_domain' ),
			'new_item'              => __( 'New Datasheet', 'text_domain' ),
			'edit_item'             => __( 'Edit Datasheet', 'text_domain' ),
			'update_item'           => __( 'Update Datasheet', 'text_domain' ),
			'view_item'             => __( 'View Datasheets', 'text_domain' ),
			'search_items'          => __( 'Search Datasheets', 'text_domain' ),
			'not_found'             => __( 'Datasheet Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
			'items_list'            => __( 'Items list', 'text_domain' ),
			'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
		);
		$args = array(
			'label'                 => __( 'Datasheet', 'text_domain' ),
			'description'           => __( 'Customer Datasheets', 'text_domain' ),
			'labels'                => $labels,
			'supports'							=> array( 'title', 'editor', 'excerpt', 'author', 'thumbnail',  'revisions' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-media-document',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,		
			'exclude_from_search'   => false,
			'taxonomies' => array('category'),
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
	
		ff_register_post_type( 'datasheet', $args, 'enable_datasheets' );


}
add_action( 'init', 'datasheets' );

}


if ( ! function_exists('whitepapers') ) {

// Register Custom Post Type
function whitepapers() {
  
  
	  // hide if none present
  $casestudy = get_posts( 
    [
        'post_type' => 'whitepaper', 
        'posts_per_page' => 1,
        'fields' => 'ids'
    ] 
  );

  if (!$casestudy)
    return;
	
		$labels = array(
			'name'                  => _x( 'Whitepapers', 'Post Type General Name', 'text_domain' ),
			'singular_name'         => _x( 'Whitepaper', 'Post Type Singular Name', 'text_domain' ),
			'menu_name'             => __( 'Whitepapers', 'text_domain' ),
			'name_admin_bar'        => __( 'Whitepaper', 'text_domain' ),
			'archives'              => __( 'Whitepaper Archives', 'text_domain' ),
			'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
			'all_items'             => __( 'All Whitepapers', 'text_domain' ),
			'add_new_item'          => __( 'Whitepaper', 'text_domain' ),
			'add_new'               => __( 'Add Whitepaper', 'text_domain' ),
			'new_item'              => __( 'New Whitepaper', 'text_domain' ),
			'edit_item'             => __( 'Edit Whitepaper', 'text_domain' ),
			'update_item'           => __( 'Update Whitepaper', 'text_domain' ),
			'view_item'             => __( 'View Whitepapers', 'text_domain' ),
			'search_items'          => __( 'Search Whitepapers', 'text_domain' ),
			'not_found'             => __( 'Whitepaper Not found', 'text_domain' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
			'featured_image'        => __( 'Featured Image', 'text_domain' ),
			'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
			'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
			'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
			'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
			'items_list'            => __( 'Items list', 'text_domain' ),
			'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
			'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
		);
		$args = array(
			'label'                 => __( 'Whitepaper', 'text_domain' ),
			'description'           => __( 'Customer Whitepapers', 'text_domain' ),
			'labels'                => $labels,
			'supports'							=> array( 'title', 'editor', 'excerpt', 'author', 'thumbnail',  'revisions' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 20,
			'menu_icon'             => 'dashicons-media-spreadsheet',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,		
			'exclude_from_search'   => false,
			'taxonomies' => array('category'),
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
	
		ff_register_post_type( 'whitepaper', $args, 'enable_whitepapers' );


}
add_action( 'init', 'whitepapers' );

}
