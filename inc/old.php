<?php
/*
Plugin Name: Sitespot misc functions and tools
Plugin URI: sitespot.co
Description: Lots of Sitespot functions and tools to help you sell more!
Version: 0.2.0
Author: Faster Forward
Author URI: https://fasterforward.com.au

*/







//clear cache on domain mapping save / update - not sure if it works yet todo CONFIRM
function sp_clear_wpengine_cache(){
	FLBuilderModel::delete_asset_cache_for_all_posts();
	ccfm_clear_cache_for_me( 'ajax' );
}
add_action('dm_echo_updated_msg','sp_clear_wpengine_cache');



//hide clear cache widget
function sp_hide_metabox () {
  remove_meta_box('dashboard_ccfm_widget','dashboard','side'); //Quick Press widget
}

add_action('wp_dashboard_setup', 'sp_hide_metabox');






// scrollable my sites list of websites
function sp_my_sites_scroll_list() {
	if ( is_admin_bar_showing() ) {
   echo '<style type="text/css">
      #wpadminbar #wp-admin-bar-my-sites-list {
			max-height: 500px;
			overflow-y: scroll;
			min-width:500px;
		}
    #wpadminbar #wp-admin-bar-my-sites-list>li {
			width:300px;
		}
		</style>';
	}
}
add_action('admin_footer', 'sp_my_sites_scroll_list');
add_action('wp_footer', 'sp_my_sites_scroll_list');




if( !class_exists('Acf') )
{
  include_once( 'sitespot/advanced-custom-fields/acf.php' );
	//define( 'ACF_LITE', true );
	//hide acf from menu
	//add_filter('advanced-custom-fields/settings/show_admin', '__return_false');
}


//kill featured image for new blogs. this is mess, fix somehow todo
$blog_id = get_current_blog_id();
if( ($blog_id < 121) && ($blog_id !== 102) && ($blog_id !== 1) && ($blog_id !== 116) && ($blog_id !== 110) && ($blog_id !== 119) && ($blog_id !== 114) && ($blog_id !== 115)  && ($blog_id !== 119)   && ($blog_id !== 120)   && ($blog_id !== 55) )
{
	require_once('sitespot/large_featured_image.php');
}
//remove leagacy tools
if( $blog_id < 160 && $blog_id != 1 && $blog_id != 157 && $blog_id != 141 && $blog_id != 143 && $blog_id != 119){
	require_once('inc/template_triggers.php');
	require_once('inc/case_studies.php');
	require_once('inc/logos.php');
	require_once('inc/testimonials.php');
}	 




//include custom admin styles
function sp_scripts_styles(){
	wp_enqueue_style( 'custom_wp_admin_css', plugins_url('sitespot/sp_admin_styles.css', __FILE__ ));
}
add_action( 'admin_enqueue_scripts', 'sp_scripts_styles' );
















//page builder clean up todo fix this up
function sp_page_builder_clean() {
	if ( FLBuilderModel::is_builder_active() ) {
    echo "<script>";
		include "sitespot/bb_cleanup.js";
		echo "</script>";
    //include 'sitespot/uploadmodal.php';
  }
}
add_action( 'wp_footer', 'sp_page_builder_clean');

add_filter('wp_nav_menu_items', 'do_shortcode');


//Customizer modify palette
function sp_customize_palette() {
    wp_enqueue_script("customize-palette" , plugins_url( 'sitespot/customizerPalette.js', __FILE__ ),array( 'jquery' ));
}
add_action( 'customize_controls_enqueue_scripts', 'sp_customize_palette' );



add_action('customize_controls_print_scripts', function(){
	
	$presetColors = get_option('_fl_builder_color_presets'); //get presets
	array_walk($presetColors, function(&$value, $key) { $value = '"#' . $value . '"'; } ); // add a # and quotes around colors
	$colz = "<script> var presetcolors = ["; //setup
	$colz .= implode(",",$presetColors);	 // add colors
	$colz .= "] 
	</script>";

	echo $colz;	
});




//enable shortcodes in gravity forms

add_filter( 'gform_get_form_filter', 'shortcode_unautop', 11 );
add_filter( 'gform_get_form_filter', 'do_shortcode', 11 );


function gravityFormsSlugger(){
  if ( is_main_site() ) {
    wp_enqueue_script("gravity-forms-helper" , plugins_url( 'sitespot/gformshelper.js', __FILE__ ),array( 'jquery' ));
  }
}
add_action( 'wp_enqueue_scripts', 'gravityFormsSlugger' );



//get post content by 
function sp_post_content_short($atts) {

    $args = shortcode_atts( array(
        'pagename' => ''
    ), $atts );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post();
            $content = apply_filters('the_content',get_the_content( ));
            ob_start();
            ?>
                <?php echo $content; ?>
            <?php
        endwhile;
    endif;
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('sp-page-content', 'sp_post_content_short');





//register post type (only if its on in the settings page)
function ff_register_post_type($action, $args, $id)
{
	//$titan = TitanFramework::getInstance( 'fasterforward' );

	//if($titan->getOption($id)) // if settings page is enabled
	//{
		register_post_type( $action, $args);//register post type
	//	flush_rewrite_rules(false);
	//}
}



//fix bug(?) with incorrect height in customizer selects
function sp_custom_customize_enqueue() {
	echo "<style>
	.customize-control select {
  	min-height: 40px !important;
	}
</style>";
}
add_action( 'customize_controls_enqueue_scripts', 'sp_custom_customize_enqueue' );


// ###################            TEST ZONE        ##################### 

			
function testFunctions(){

	
}

add_action('init','testFunctions');






function sp_add_media() {
	// first check if data is being sent and that it is the data we want
  if ( isset( $_POST["url"] ) ) {
		// now set our response var equal to that of the POST var (this will need to be sanitized based on what you're doing with with it)
		$response = $_POST["url"];
		// send the response back to the front end
    
    echo addImageFromUrl($response);
      
		die();
	}
}
add_action( 'wp_ajax_sp_add_media', 'sp_add_media' );



function addImageFromUrl($image){
	// only need these if performing outside of admin environment
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');

	$post_id = 0;

	// magic sideload image returns an HTML image, not an ID
	$media = media_sideload_image($image, $post_id);

	// therefore we must find it so we can set it as featured ID
	if(!empty($media) && !is_wp_error($media)){
			$args = array(
					'post_type' => 'attachment',
					'posts_per_page' => -1,
					'post_status' => 'any',
					'post_parent' => $post_id
			);
		
			// reference new image to set as featured
			$attachments = get_posts($args);
		
			if(isset($attachments) && is_array($attachments)){
					foreach($attachments as $attachment){
							// grab source of full size images (so no 300x150 nonsense in path)
							$image = wp_get_attachment_image_src($attachment->ID, 'full');
							// determine if in the $media image we created, the string of the URL exists
							if(strpos($media, $image[0]) !== false){
									// if so, we found our image. set it as thumbnail
									set_post_thumbnail($post_id, $attachment->ID);
									// only want one image
									break;
							}
					}
			}
		return true;
	}
	return false;
}



add_action( 'wp_enqueue_scripts', function(){
	//enqueue utility scripts like kill links etc
	wp_enqueue_script("sp_utilities" , plugins_url( 'sitespot/sp_utilities.js', __FILE__ ),array( 'jquery' ));
	
	//make wp-admin bar more obvious
	if ( is_admin_bar_showing() ) 
	{
		wp_enqueue_script("admin_bar_helper" , plugins_url( 'sitespot/admin_bar_helper.js', __FILE__ ),array( 'jquery' ));
	}
});


function pw_load_scripts() {
	wp_enqueue_script('admin-scripts', plugins_url( 'sitespot/admin_scripts.js', __FILE__ ),array( 'jquery' ));
}
add_action('admin_enqueue_scripts', 'pw_load_scripts');


//remove new css feature of customizer - already in theme
add_action( 'customize_register', 'prefix_remove_css_section', 15 );
/**
 * Remove the additional CSS section, introduced in 4.7, from the Customizer.
 * @param $wp_customize WP_Customize_Manager
 */
function prefix_remove_css_section( $wp_customize ) {
	$wp_customize->remove_section( 'custom_css' );
}











function mailme($message, $title = 'mail me message')
{
	//print_r if array
	if(is_array ($message) || is_object($message))
		$message = print_r($message,true);
	
	if(is_wpe_snapshot())
		$title = "STAGING: " . $title;
	
	mail('tom@fasterforward.com.au',$title,$message);
}


function sp_add_query_vars_filter( $vars ){
	    
  if(isset($_GET['sp_template_post_id']))
  {
    $template_post_id = $_GET['sp_template_post_id'];
    $post_name = get_post_field('post_name',intval($template_post_id));
    wp_redirect(site_url("/fl-builder-template/$post_name/fl_builder"));
    exit;
  }
	return $vars;
}

add_filter( 'query_vars', 'sp_add_query_vars_filter' );


function reset_bb_css(){
	$upload_dir = wp_upload_dir();
	$files = glob($upload_dir['basedir'] . "/bb-theme/*"); // get all file names
	foreach($files as $file){ // iterate files
		//mailme($file);
		if(strpos($file,".css"))
			unlink($file); // delete file
	}
}



function sp_better_user_tracking() {
    wp_enqueue_script("active-campaign-tracker" , plugins_url( 'sitespot/activecampaign.js', __FILE__ ),array( 'jquery' ));
}
add_action( 'admin_enqueue_scripts', 'sp_better_user_tracking' );


/*-------------------------------------------------------------------------------
	Add Body Class on Gforms Submission
-------------------------------------------------------------------------------*/
add_action( 'gform_after_submission', 'add_confirmation_class', 10, 2 );

function add_confirmation_class() {
	add_filter('body_class', 'add_gravity_classes');
	function add_gravity_classes($classes){
		$classes[] = 'gravity-form-submitted';
		return $classes;
	}
}




 
// ###################        end test zone        ##################### 



function add_featured_image_body_class( $classes ) {    
global $post;
    if ( isset ( $post->ID ) && get_the_post_thumbnail($post->ID)) {
          $classes[] = 'has-featured-image';
 }
          return $classes;
}
add_filter( 'body_class', 'add_featured_image_body_class' );









/**
 * This function adds some styles to the WordPress Customizer
 */
function my_customizer_styles() { ?>
	<style>
		/* hide footer option - we aint using it  */
#accordion-panel-fl-footer{
  display:none !important;
}
</style>
	<?php

}
add_action( 'customize_controls_print_styles', 'my_customizer_styles', 999 );



function sp_ac_tracking(){
	$current_user = wp_get_current_user();
	if ( !($current_user) ){
		return;
	}
	echo "<script>trackcmp_email='$current_user->user_email'</script>";
}

add_action('wp_head', 'sp_ac_tracking');



//update CC form
add_filter( 'gform_stripe_customer_id', function ( $customer_id, $feed, $entry, $form ) {
    
	$feed_name  = rgars( $feed, 'meta/feedName' );
    // The name associated with the Stripe feed.
    if ( $feed_name == 'Update Billing' ) { 
        return rgar( $entry, '2' );
    }
 
    return $customer_id;
}, 10, 4 );




add_filter( 'gform_stripe_charge_authorization_only', function ( $authorization_only, $feed ) {
    $feed_name  = rgars( $feed, 'meta/feedName' );
    // The name associated with the Stripe feed.
    if ( $feed_name == 'Update Billing' ) {
        return true;
    }
 
    return $authorization_only;
}, 10, 2 );





//* Noindex on the post type pages
add_filter( 'the_seo_framework_robots_meta_array', function( $meta = array() ) {

	if ( 'fl-builder-template' === get_post_type() ) {
		$meta['noindex'] = 'noindex';
	}

	return $meta;
}, 10, 1 );

//* Remove SEO metabox, SEO Bar and sitemap support.
add_filter( 'the_seo_framework_supported_post_type', function( $post_type, $evaluated_post_type = '' ) {
	
	if ( 'fl-builder-template' === $evaluated_post_type ) {
		return false;
	}

	return $post_type;
}, 10, 2 );


//disable bb inline editing
add_filter ('fl_inline_editing_enabled', '__return_false' );

//disable gutenberg stuffs
remove_action('try_gutenberg_panel', 'wp_try_gutenberg_panel');
add_filter( 'gutenberg_can_edit_post_type', '__return_false' );


// new blog http(s) fix

function sitespot_wpmu_new_blog_https( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
  
  $domain = 'https://'.$domain;

  wl(" UPDATE NEW BLOG SITEURL $domain  
  $blog_id");
  
	update_blog_option ($blog_id, 'siteurl', $domain);
  update_blog_option( $blog_id, 'home', $domain );
  
}
add_action( 'wpmu_new_blog', 'sitespot_wpmu_new_blog_https', 10, 6 );







add_action( 'admin_bar_menu',        'mss_admin_bar_menu' );
/**
 * Add search field menu item
 *
 * @param WP_Admin_Bar $wp_admin_bar
 * @return void
 */
function mss_admin_bar_menu( $wp_admin_bar ) {
	$total_users_sites = count( $wp_admin_bar->user->blogs );
	$show_if_gt        = apply_filters( 'mms_show_search_minimum_sites', 10 );
	if ( ! is_user_logged_in() || ( $total_users_sites < $show_if_gt ) ) {
		return;
	}
	$wp_admin_bar->add_menu( array(
		'parent' => 'my-sites-list',
		'id'     => 'my-sites-search',
		'title'  => sprintf(
			'<label for="my-sites-search-text">%s</label><input type="text" id="my-sites-search-text" placeholder="%s" />',
			esc_html__( 'Filter My Sites', 'mss' ),
			esc_attr__( 'Search Sites', 'mss' )
		),
		'meta'   => array(
			'class' => 'hide-if-no-js'
		)
	) );
}





function sp_custom_excerpt_length( $length ) {
  
  $custom = get_option('custom_excerpt_length');
  
  if(!$custom){
    $custom = '55';
    update_option('custom_excerpt_length', $custom); 
  }
  return intval($custom);
   
}
add_filter( 'excerpt_length', 'sp_custom_excerpt_length', 999 );

