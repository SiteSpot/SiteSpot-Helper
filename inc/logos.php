<?php

if ( ! function_exists('logo_post_type') ) {

// Register Custom Post Type
function logo_post_type() {

  // hide if none present
  $logos = get_posts( 
    [
        'post_type' => 'logo', 
        'posts_per_page' => 1,
        'fields' => 'ids'
    ] 
);

if (!$logos)
  return;
  
  
  
	$labels = array(
		'name'                  => _x( 'Logos', 'Post Type General Name', 'sp_logo' ),
		'singular_name'         => _x( 'Logo', 'Post Type Singular Name', 'sp_logo' ),
		'menu_name'             => __( 'Logos', 'sp_logo' ),
		'name_admin_bar'        => __( 'Logos', 'sp_logo' ),
		'archives'              => __( 'Logo Archives', 'sp_logo' ),
		'attributes'            => __( 'Logo Attributes', 'sp_logo' ),
		'parent_item_colon'     => __( 'Parent Logo :', 'sp_logo' ),
		'all_items'             => __( 'All Logos', 'sp_logo' ),
		'add_new_item'          => __( 'Add New Logo', 'sp_logo' ),
		'add_new'               => __( 'Add New Logo', 'sp_logo' ),
		'new_item'              => __( 'New Logo', 'sp_logo' ),
		'edit_item'             => __( 'Edit Logo', 'sp_logo' ),
		'update_item'           => __( 'Update Logo', 'sp_logo' ),
		'view_item'             => __( 'View Logo', 'sp_logo' ),
		'view_items'            => __( 'View Logos', 'sp_logo' ),
		'search_items'          => __( 'Search Logo', 'sp_logo' ),
		'not_found'             => __( 'Logo Not found', 'sp_logo' ),
		'not_found_in_trash'    => __( 'Logo Not found in Trash', 'sp_logo' ),
		'featured_image'        => __( 'Logo', 'sp_logo' ),
		'set_featured_image'    => __( 'Set Logo', 'sp_logo' ),
		'remove_featured_image' => __( 'Remove Logos', 'sp_logo' ),
		'use_featured_image'    => __( 'Use as logo', 'sp_logo' ),
		'insert_into_item'      => __( 'Insert into logos', 'sp_logo' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'sp_logo' ),
		'items_list'            => __( 'Logo list', 'sp_logo' ),
		'items_list_navigation' => __( 'Items list navigation', 'sp_logo' ),
		'filter_items_list'     => __( 'Filter items list', 'sp_logo' ),
	);
	
	$args = array(
		'label'                 => __( 'Logo', 'sp_logo' ),
		'description'           => __( 'Customer or Vendor Logos', 'sp_logo' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', ),
		'taxonomies'            => array( 'category' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 21,
		'menu_icon'             => 'dashicons-store',
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'logo', $args );

}
add_action( 'init', 'logo_post_type', 0 );

}