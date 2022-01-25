<?php

if ( ! function_exists('sp_testimonials') ) {

// Register Custom Post Type
function sp_testimonials() {

  // hide if none present
  $testimonials = get_posts( 
    [
        'post_type' => 'testimonials', 
        'posts_per_page' => 1,
        'fields' => 'ids'
    ] 
  );

  if (!$testimonials)
    return;

	$labels = array(
		'name'                  => _x( 'Testimonials', 'Post Type General Name', 'sp_testimonials' ),
		'singular_name'         => _x( 'Testimonial', 'Post Type Singular Name', 'sp_testimonials' ),
		'menu_name'             => __( 'Testimonials', 'sp_testimonials' ),
		'name_admin_bar'        => __( 'Post Type', 'sp_testimonials' ),
		'archives'              => __( 'Testimonial Archives', 'sp_testimonials' ),
		'attributes'            => __( 'Testimonial Attributes', 'sp_testimonials' ),
		'parent_item_colon'     => __( 'Parent Testimonial:', 'sp_testimonials' ),
		'all_items'             => __( 'All Testimonials', 'sp_testimonials' ),
		'add_new_item'          => __( 'Add New Testimonial', 'sp_testimonials' ),
		'add_new'               => __( 'Add New Testimonial', 'sp_testimonials' ),
		'new_item'              => __( 'New Testimonial', 'sp_testimonials' ),
		'edit_item'             => __( 'Edit Testimonial', 'sp_testimonials' ),
		'update_item'           => __( 'Update Testimonial', 'sp_testimonials' ),
		'view_item'             => __( 'View Testimonial', 'sp_testimonials' ),
		'view_items'            => __( 'View Testimonials', 'sp_testimonials' ),
		'search_items'          => __( 'Search Testimonial', 'sp_testimonials' ),
		'not_found'             => __( 'Testimonial Not found', 'sp_testimonials' ),
		'not_found_in_trash'    => __( 'Testimonial Not found in Trash', 'sp_testimonials' ),
		'featured_image'        => __( 'Featured Image', 'sp_testimonials' ),
		'set_featured_image'    => __( 'Set featured image', 'sp_testimonials' ),
		'remove_featured_image' => __( 'Remove featured image', 'sp_testimonials' ),
		'use_featured_image'    => __( 'Use as featured image', 'sp_testimonials' ),
		'insert_into_item'      => __( 'Insert into testimonial', 'sp_testimonials' ),
		'uploaded_to_this_item' => __( 'Uploaded to this testimonial', 'sp_testimonials' ),
		'items_list'            => __( 'Testimonials list', 'sp_testimonials' ),
		'items_list_navigation' => __( 'Testimonials list navigation', 'sp_testimonials' ),
		'filter_items_list'     => __( 'Filter testimonials list', 'sp_testimonials' ),
	);
	$args = array(
		'label'                 => __( 'Testimonial', 'sp_testimonials' ),
		'description'           => __( 'Customer Testimonials', 'sp_testimonials' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', ),
		'taxonomies'            => array( 'category' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-format-status',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,		
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'rewrite'               => false,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'testimonials', $args );

}
add_action( 'init', 'sp_testimonials', 0 );

}