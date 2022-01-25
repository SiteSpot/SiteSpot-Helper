<?php
/*
Plugin Name: Sitespot Tools
Plugin URI: sitespot.co
Description: Lots of helpful functions.
Version: 0.2.1
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






// ###################            TEST ZONE        ##################### 

			
function testFunctions(){

//	reset_bb_css();
}

add_action('init','testFunctions');




add_action( 'wp_enqueue_scripts', function(){
	//enqueue utility scripts like kill links etc
	
	//make wp-admin bar more obvious
	if ( is_admin_bar_showing() ) 
	{
		wp_enqueue_script("admin_bar_helper" , plugins_url( 'inc/admin_bar_helper.js', __FILE__ ),array( 'jquery' ));
	}
});

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



//disable gutenberg stuffs
remove_action('try_gutenberg_panel', 'wp_try_gutenberg_panel');
add_filter( 'gutenberg_can_edit_post_type', '__return_false' );





function custom_login_logo() {
echo '<style type="text/css">
h1 a { background-image: url(https://sitespot.co/wp-content/uploads/2019/04/SiteSpot-Logo-White.svg) !important; 
       background-size: 80% 100% !important;
       background-position: center center !important;
       height: 82px !important;
       width: 359px !important;
       margin-left: -14px !important;
     }
        body {
          background: linear-gradient(75deg, #1E73BE, #2ABDD4, #478CC8);
          background-size: 600% 600%;
          animation: GradientBackground 15s ease infinite;
        }

        .login #login #backtoblog>a ,.login #login #nav>a {
            color: white !important;
        }

        @keyframes GradientBackground {
          0% {
            background-position: 0% 50%;
          }

          50% {
            background-position: 100% 50%;
          }

          100% {
            background-position: 0% 50%;
          }
        };
</style>

<script>
    jQuery(function(){
        jQuery("#login h1 a").attr("href","https://sitespot.co").text("Powered by SiteSpot");
    });
</script>
';
}
add_action('login_head', 'custom_login_logo');
