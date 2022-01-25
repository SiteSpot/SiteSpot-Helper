<?php
/*
Plugin Name: Sitespot Tools
Plugin URI: sitespot.co
Description: Lots of helpful functions.
Version: 0.2.0
Author: SiteSpot
Author URI: https://sitespot.co
*/



add_action('in_admin_header', 'admin_navigator');

function admin_navigator(){
  if(!isset($_GET['welcome'])) // if welcome url param is present, then show the thing (for testing)
    if(get_current_blog_id() !== 1 || is_super_admin() || current_user_can('editor') || current_user_can('administrator'))
      return;
  
  $user_id = get_current_user_id();
  $user_blogs = get_blogs_of_user( $user_id );
  
  echo '<div class="wrap" style="text-align:center; margin:auto;">
  <img style="max-width:100%;" src="https://sitespot.co/wp-content/uploads/2017/07/SiteSpot-email-header.png"><BR><BR><h1 style="color:white">Manage Your Websites:</h1><p style="color:white"><strong>Please update your bookmarks to the below links to login and make changes to your website.</strong><BR><BR><small>Your login details are the same as when you logged in here.</small><BR>';

  foreach ($user_blogs AS $user_blog) {
    if($user_blog->userblog_id != 1 && !$user_blog->deleted && !$user_blog->spam)
      echo "<BR><a class='button' href='$user_blog->siteurl/wp-admin/'>" . $user_blog->blogname . " - $user_blog->siteurl/wp-admin/</a><BR>";
  }
  echo '</p>
  <BR>
  <p style="color:white">Is your website not listed? contact us: support@sitespot.co</p><BR>
  <a style="color:white" href="'.wp_logout_url().'">Logout</a><BR><BR></div>';
  echo "<style>  #wpbody, #adminmenumain, #mw_adminimize_admin_bar{display:none;}  
  #wpwrap, body{ background-color:#1e73be;}
  #wpcontent {
    margin-left: 0px !important;
  }
  </style>";
}


add_action( 'admin_bar_menu', 'toolbar_link_to_mypage', 999 );

function toolbar_link_to_mypage( $wp_admin_bar ) {
	
  //current blog id
  $args = array(
		'id'    => 'site_id',
		'title' => 'Site ID: '.get_current_blog_id(),
		'parent' => 'site-name',
	);
	$wp_admin_bar->add_node( $args );
  
}



 add_action('admin_menu', 'sp_sitespotshop_link');
 function sp_sitespotshop_link() {
	 //add_menu_page( string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', string $icon_url = '', int $position = null )
    add_menu_page('Manage your account', 'Your Account', 'list_users', 'sp-account-link', 'render_account_link_page','',0);
 }

 function render_account_link_page() {
	 	echo "<h1>Manage your account</h1>";
		echo "<p>To manage your billing and subscription, or purchase additional survices, please use the below link.<BR></p>";
		echo "<p><small>You will need to login again for security purposes. Opens in a new tab.</small></p>";
 		echo '<h4><a class="button" href="https://sitespotshop.com/my-account/" target="_blank">Visit the SiteSpot shop to manage your account</a></h4>';
 }




// add tag support to pages
function tags_support_all() {
	register_taxonomy_for_object_type('post_tag', 'page');
}

// ensure all tags are included in queries
function tags_support_query($wp_query) {
	if ($wp_query->get('tag')) $wp_query->set('post_type', 'any');
}

// tag hooks
add_action('init', 'tags_support_all');
add_action('pre_get_posts', 'tags_support_query');

add_action( 'init', 'my_add_excerpts_to_pages' );
function my_add_excerpts_to_pages() {
     add_post_type_support( 'page', 'excerpt' );
}

//add parent class to body tag
add_filter( 'body_class', 'dc_parent_body_class' );
function dc_parent_body_class( $classes ) {
    	if( is_page() ) { 
        	$parents = get_post_ancestors( get_the_ID() );
			$id = ($parents) ? $parents[count($parents)-1]: get_the_ID();
		if ($id) {
			$classes[] = 'top-parent-' . $id;
		} else {
			$classes[] = 'top-parent-' . get_the_ID();
		}
	}
 
	return $classes;
}



require_once('inc/shortcodes.php');


//include global frontend styles
function global_frontend_styles(){
	wp_enqueue_style( 'sp_global_css', plugins_url('inc/global_frontend.css', __FILE__ ));
}
add_action( 'wp_enqueue_scripts', 'global_frontend_styles' );

//hide drafts in nav
function nav_menu_add_post_status_class($classes, $item){
    $post_status = get_post_status($item->object_id);
    $classes[] = $post_status;
    return $classes;
}
add_filter('nav_menu_css_class' , 'nav_menu_add_post_status_class' , 10 , 2);



add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_script( 'jquery-match-height', '//cdnjs.cloudflare.com/ajax/libs/jquery.matchHeight/0.7.2/jquery.matchHeight-min.js', [ 'jquery' ], null, true );
} );

add_filter( 'fl_builder_register_settings_form', function( $form, $slug ) {
	if ( 'module_advanced' === $slug ) {
		$form[ 'sections' ][ 'css_selectors' ][ 'fields' ][ 'match_height_group' ] = [
			'type' => 'text',
			'label' => __( 'Match height group', 'wpd' )
		];
	}
	
	return $form;
}, 10, 2 );

add_filter( 'fl_builder_module_attributes', function( $attrs, $module ) {
	if ( isset( $module->settings->match_height_group ) && ! empty( $module->settings->match_height_group ) ) {
		$attrs[ 'data-mh' ] = $module->settings->match_height_group;
	}

	return $attrs;
}, 10, 2 );

add_filter('wp_nav_menu_items', 'do_shortcode');


function wpse_enqueue_datepicker() {
    // Load the datepicker script (pre-registered in WordPress).
    wp_enqueue_script( 'jquery-ui-datepicker' );

    // You need styling for the datepicker. For simplicity I've linked to the jQuery UI CSS on a CDN.
    wp_register_style( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css' );
    wp_enqueue_style( 'jquery-ui' );  
}
add_action( 'wp_enqueue_scripts', 'wpse_enqueue_datepicker' );


//Customizer add business details that we will need to make global before we add more themes TODO
function sp_customize_register( $wp_customize ) {
    /* Just use the $wp_customize object and create a section or use a built-in section. */
    $wp_customize->add_section(
        'sp_business_details',
        array(
            'title'       => 'Business Details',
            'priority'    => 1,
        )
    );

$wp_customize->add_setting(
        'sp_business_details[business_name]',
        array(
            'default'    => '',
            'type'       => 'option',
            'capability' => 'edit_theme_options',
        ) 
    );
    /* ... and link controls to the settings. */
    $wp_customize->add_control(
        'sp_business_name',
        array(
            'label'      => 'Business Name',
            'section'    => 'sp_business_details',
            'settings'   => 'sp_business_details[business_name]',
        )
    );
    $wp_customize->add_setting(
        'sp_business_details[primary_phone]',
        array(
            'default'    => '',
            'type'       => 'option',
            'capability' => 'edit_theme_options',
        )
    );
    $wp_customize->add_control(
        'sp_business_phone',
        array(
            'label'      => 'Primary phone number',
            'section'    => 'sp_business_details',
            'settings'   => 'sp_business_details[primary_phone]',
        )
    );
    $wp_customize->add_setting(
        'sp_business_details[primary_service_area]',
        array(
            'default'    => '',
            'type'       => 'option',
            'capability' => 'edit_theme_options',
        )
    );
    $wp_customize->add_control(
        'sp_primary_service_area',
        array(
            'label'      => 'Primary Service Area',
            'section'    => 'sp_business_details',
            'settings'   => 'sp_business_details[primary_service_area]',
        )
    );
    $wp_customize->add_setting(
        'sp_business_details[primary_email]',
        array(
            'default'    => '',
            'type'       => 'option',
            'capability' => 'edit_theme_options',
        )
    );
    $wp_customize->add_control(
        'sp_primary_email',
        array(
            'label'      => 'Primary email address',
            'section'    => 'sp_business_details',
            'settings'   => 'sp_business_details[primary_email]',
            'type'       => 'email',
        )
    );
    $wp_customize->add_setting(
        'sp_business_details[support_phone]',
        array(
            'default'    => '',
            'type'       => 'option',
            'capability' => 'edit_theme_options',
        )
    );
    $wp_customize->add_control(
        'sp_support_phone',
        array(
            'label'      => 'Support phone number (optional)',
            'section'    => 'sp_business_details',
            'settings'   => 'sp_business_details[support_phone]',
        )
    );
    $wp_customize->add_setting(
        'sp_business_details[support_email]',
        array(
            'default'    => '',
            'type'       => 'option',
            'capability' => 'edit_theme_options',
        )
    );
    $wp_customize->add_control(
        'sp_support_email',
        array(
            'label'      => 'Support email address (optional)',
            'section'    => 'sp_business_details',
            'settings'   => 'sp_business_details[support_email]',
            'type'       => 'email',
        )
    );  
    $wp_customize->add_setting(
        'sp_business_details[primary_address]',
        array(
            'default'    => '',
            'type'       => 'option',
            'capability' => 'edit_theme_options',
        )
    );
    $wp_customize->add_control(
        'sp_primary_address',
        array(
            'label'      => 'Primary address',
            'section'    => 'sp_business_details',
            'settings'   => 'sp_business_details[primary_address]',
            'type' => 'textarea',
        )
    );
}
add_action( 'customize_register' , 'sp_customize_register' );


/*
 *
 * Synch customizer and beaver builder presets
 *
 */

function wt_get_ID_by_page_name($page_name){
	global $wpdb;
	$page_name_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."'");
	return $page_name_id;
}




add_action( 'wp_enqueue_scripts', function(){
	//enqueue utility scripts like kill links etc
	wp_enqueue_script("sp_utilities" , plugins_url( 'inc/sitespot-utilities.js', __FILE__ ),array( 'jquery' ));
	
});


 
// ###################        end test zone        ##################### 



function add_featured_image_body_class( $classes ) {    
global $post;
    if ( isset ( $post->ID ) && get_the_post_thumbnail($post->ID)) {
          $classes[] = 'has-featured-image';
 }
          return $classes;
}
add_filter( 'body_class', 'add_featured_image_body_class' );



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
  include_once( 'inc/advanced-custom-fields/acf.php' );
	//define( 'ACF_LITE', true );
	//hide acf from menu
	//add_filter('advanced-custom-fields/settings/show_admin', '__return_false');
}


//kill featured image for new blogs. this is mess, fix somehow todo
//remove leagacy tools
if( $blog_id < 160 && $blog_id != 1 && $blog_id != 157 && $blog_id != 141 && $blog_id != 143 && $blog_id != 119){
	require_once('inc/template_triggers.php');
	require_once('inc/case_studies.php');
	require_once('inc/logos.php');
	require_once('inc/testimonials.php');
}	 




//include custom admin styles
function sp_scripts_styles(){
	wp_enqueue_style( 'custom_wp_admin_css', plugins_url('inc/sp_admin_styles.css', __FILE__ ));
}
add_action( 'admin_enqueue_scripts', 'sp_scripts_styles' );
















//page builder clean up todo fix this up
function sp_page_builder_clean() {
	if ( FLBuilderModel::is_builder_active() ) {
    echo "<script>";
		include "inc/bb_cleanup.js";
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

//	reset_bb_css();
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
	
	//make wp-admin bar more obvious
	if ( is_admin_bar_showing() ) 
	{
		wp_enqueue_script("admin_bar_helper" , plugins_url( 'inc/admin_bar_helper.js', __FILE__ ),array( 'jquery' ));
	}
});


function pw_load_scripts() {
//	wp_enqueue_script('admin-scripts', plugins_url( 'sitespot/admin_scripts.js', __FILE__ ),array( 'jquery' ));
}
add_action('admin_enqueue_scripts', 'pw_load_scripts');


//remove new css feature of customizer - already in theme
add_action( 'customize_register', 'prefix_remove_css_section', 15 );
/**
 * Remove the additional CSS section, introduced in 4.7, from the Customizer.
 * @param $wp_customize WP_Customize_Manager
 */
function prefix_remove_css_section( $wp_customize ) {
//	$wp_customize->remove_section( 'custom_css' );
}











function mailme($message, $title = 'mail me message')
{
	//print_r if array
	if(is_array ($message) || is_object($message))
		$message = print_r($message,true);
	
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



//remove snap from conflicting og stuffs
//the seo framework doesnt like SNAP... but we do
function allow_og_tags_seo_framework($data){
	
	unset($data['open_graph']['NextScripts SNAP']);
		
	return $data;
}
add_filter('the_seo_framework_conflicting_plugins', 'allow_og_tags_seo_framework');



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



add_filter('bb_bt_ab_licence_key',function($string){
  return '0209328033840';
});



function sp_custom_excerpt_length( $length ) {
  
  $custom = get_option('custom_excerpt_length');
  
  if(!$custom){
    $custom = '55';
    update_option('custom_excerpt_length', $custom); 
  }
  return intval($custom);
   
}
add_filter( 'excerpt_length', 'sp_custom_excerpt_length', 999 );



function sp_login_logo() { ?>
<style type=”text/css”>
#login h1 a, .login h1 a {
background-image: url(https://sitespot.co/wp-content/uploads/2020/07/SiteSPot_Logo_Blue.svg);
height:65px;
width:320px;
background-size: 320px 65px;
background-repeat: no-repeat;
padding-bottom: 30px;
}
</style>
<?php }
add_action( 'login_enqueue_scripts', 'sp_login_logo' );



add_filter( 'hiwnaa_user_cap', 'change_the_hiwnaa_user_cap' );

function change_the_hiwnaa_user_cap( $cap ) {
	return 'create_sites';
}

