<?php


// ###################     SHORTCODE UTILITIES   #####################

// Content Views - Do shortcode on excerpt
add_filter( 'the_excerpt', 'cv_do_shortcode_excerpt', 100, 1 );
function cv_do_shortcode_excerpt( $args ) {
	$args = do_shortcode( $args );
	return $args;
}

//enable shortcodes in widgets - derr WP
add_filter('widget_text','do_shortcode');


// ###################     SHORTCODES   #####################

// business-name
function sitespot_business_name() {
  
  $optionData =  get_option("sp_business_details");
  
  //check support phone exists
  if(!$optionData['business_name'])
    $optionData['business_name'] = get_bloginfo('name');
  
  return $optionData['business_name'];
}
add_shortcode( 'business-name', 'sitespot_business_name' );
add_shortcode( 'business', 'sitespot_business_name' );


function sitespot_primary_address() {
  return get_option( "sp_business_details")['primary_address'];
}
add_shortcode( 'primary-address', 'sitespot_primary_address' );
add_shortcode( 'address', 'sitespot_primary_address' );


function sitespot_primary_service_area() {
  return get_option( "sp_business_details")['primary_service_area'];
}
add_shortcode( 'primary-service-area', 'sitespot_primary_service_area' );
add_shortcode( 'service-area', 'sitespot_primary_service_area' );
add_shortcode( 'business-area', 'sitespot_primary_service_area' );



function sitespot_greater_service_area() {
  $optionData =  get_option( "sp_business_details");
  if(!isset($optionData['greater-service-area']))
    return get_option( "sp_business_details")['primary_service_area'];
  return get_option( "sp_business_details")['greater_service_area'];
}
add_shortcode( 'greater-service-area', 'sitespot_greater_service_area' );


function sitespot_primary_email() {

  $optionData =  get_option( "sp_business_details");
  if(!$optionData['primary_email'])
    return get_bloginfo('admin_email');
  return $optionData['primary_email'];
}
add_shortcode( 'primary-email', 'sitespot_primary_email' );
add_shortcode( 'email', 'sitespot_primary_email' );


function sitespot_primary_phone() {
  return get_option( "sp_business_details")['primary_phone'];
}
add_shortcode( 'primary-phone', 'sitespot_primary_phone' );
add_shortcode( 'primary_phone', 'sitespot_primary_phone' );
add_shortcode( 'phone', 'sitespot_primary_phone' );


function sitespot_support_email() {

  $optionData =  get_option( "sp_business_details");
  if(!isset($optionData['support_email']))
    $optionData['support_email'] = $optionData['primary_email'];
  
  if(!$optionData['support_email'])
    return get_bloginfo('admin-email');
  
  return $optionData['support_email'];

}
add_shortcode( 'support-email', 'sitespot_support_email' );


function sitespot_support_phone() {
  
  $optionData =  get_option( "sp_business_details");
  
  //check support phone exists
  if(!isset($optionData['support_phone']))
    $optionData['support_phone'] = $optionData['primary_phone'];
  
  return $optionData['support-phone'];
}
add_shortcode( 'support-phone', 'sitespot_support_phone' );


function sitespot_current_year() {
    
  return date("Y");
}
add_shortcode( 'current-year', 'sitespot_current_year' );
add_shortcode( 'year', 'sitespot_current_year' ); //alias

function sitespot_website_address() {
  return get_site_url();
}
add_shortcode( 'website-address', 'sitespot_website_address' );

function sitespot_getvalue($atts) {
  return $_GET[$atts['value']];
}
add_shortcode( 'sitespot_getvalue', 'sitespot_getvalue' );


function sitespot_post_name() {
  return get_the_title();
}
add_shortcode( 'post-name', 'sitespot_post_name' );


function sitespot_site_url() {
  return get_site_url();
}
add_shortcode( 'site-url', 'sitespot_site_url' );

function sitespot_redirect_url() {
  return $_GET['redirect-url'];
}
add_shortcode( 'redirect-url', 'sitespot_redirect_url' );

function sitespot_redirect_name() {
  return $_GET['redirect-name'];
}
add_shortcode( 'redirect-name', 'sitespot_redirect_name' );


//admin notification shortcode
function sp_editor_notice($atts = [], $content = null){

	// do something to $content
	$adminExplanation = "<p class=\"text-right text-muted\" style=\"font-size:12px;\">This message is only visible to you as a logged in admin. Visitors to your site will not see this message</p>";
	// always return

	return "<div class=\"alert alert-info alert-dismissible admin-only\" style=\"margin-top:30px; margin-bottom:10px;\" role=\"alert\">".$adminExplanation ."<p>" . $content . "</p></div>";
}
add_shortcode('editor-message', 'sp_editor_notice');

function myshortcode_title( ){
   return get_the_title();
}
add_shortcode( 'page_title', 'myshortcode_title' );

function page_url( ){
   return get_permalink();
}
add_shortcode( 'page_url', 'page_url' );
add_shortcode( 'permalink', 'page_url' );

function current_url( ){
	$url = $domain . $_SERVER['REQUEST_URI'];
	return $url;
}
add_shortcode( 'current_url', 'current_url' );

// Font Awesome Shortcodes
function addscFontAwesome($atts) {
    extract(shortcode_atts(array(
    'type'  => '',
    'size' => '',
    'rotate' => '',
    'flip' => '',
    'pull' => '',
    'animated' => '',
    'class' => '',
    'fw' => '',
  
    ), $atts));
 
    $classes  = ($type) ? 'fa-'.$type. '' : 'fa-star';
    $classes .= ($size) ? ' fa-'.$size.'' : '';
    $classes .= ($rotate) ? ' fa-rotate-'.$rotate.'' : '';
    $classes .= ($flip) ? ' fa-flip-'.$flip.'' : '';
    $classes .= ($pull) ? ' pull-'.$pull.'' : '';
    $classes .= ($animated) ? ' fa-spin' : '';
    $classes .= ($class) ? ' '.$class : '';
    $classes .= ($fw) ?  ' fa-fw' : '';
 
    $theAwesomeFont = '<i class="fa '.esc_html($classes).'"></i>';
      
    return $theAwesomeFont;
}
add_shortcode('icon', 'addscFontAwesome');



function sp_searchforms_shortcode( $form ) {

    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
    <input style="display:none" type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
    </div>
    </form>';

    return $form;
}

add_shortcode('search', 'sp_searchforms_shortcode');

// Add Shortcode
function cmg_generate_team_member( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'name' => '',
		),
		$atts
	);
  
  
	  if( empty($atts['name']) ) return;
	
    return get_post_meta( get_the_ID(), $atts['name'], true );

}
add_shortcode( 'field', 'cmg_generate_team_member' );


function years_since_shortcode($atts) {
	
	$foundedDate = $atts['date'];

	if(!$foundedDate)
		return false;
	
	$datetime1 = new DateTime($foundedDate);
	$datetime2 = new DateTime('now');
	$interval = $datetime1->diff($datetime2);
	return $interval->y;

}
add_shortcode('years_since', 'years_since_shortcode');





