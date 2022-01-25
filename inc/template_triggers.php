<?php

/*
 *
 * fancy footer
 *
 */

function sp_bb_footer(){
	if ( !FLTheme::has_footer() ){
		//if there is a beaver builder template called '_footer' then echo it after the footer
		$the_query = new WP_Query( array( 'post_type' => 'fl-builder-template', 'post_status' => 'publish', 'name' => '_footer' ) );
		// The Loop
		if ( $the_query->have_posts() ) {
/*
					// then do shortcode theme id
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$footerid = get_the_ID();
			}
			*/
			
			// Restore original Post Data 
			wp_reset_postdata();

			echo '<footer class="fl-page-footer-wrap" itemscope="itemscope" itemtype="http://schema.org/WPFooter">';
			echo do_shortcode('[fl_builder_insert_layout slug="_footer" type="fl-builder-template"]');
			echo '</footer>';
		}	
	}
}

add_filter('fl_after_content','sp_bb_footer');

/*
 *
 * replace header - disable header and top bar first
 *
 */

function sp_bb_header(){
	if ( (get_theme_mod('fl-topbar-layout') == "none") && (get_theme_mod('fl-header-layout') == "none")  ){
		//if there is a beaver builder template called '_header' then echo it at the top
		$the_query = new WP_Query( array( 'post_type' => 'fl-builder-template', 'post_status' => 'publish', 'name' => '_header' ) );
		// The Loop
		if ( $the_query->have_posts() ) {

					// then do shortcode theme id
			/*
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$headerid = get_the_ID();
			}*/
			// Restore original Post Data 
			wp_reset_postdata();

			echo '<header class="fl-page-header-wrap" itemscope="itemscope" itemtype="http://schema.org/WPHeader">';
			echo do_shortcode('[fl_builder_insert_layout slug="_header" type="fl-builder-template"]');
			echo '</header>';
		}	
	}
}

add_filter('fl_page_open','sp_bb_header');




/*
 *
 * call now on mobile button
 *
 */



function sp_call_now_on_mobile(){
  
  $shortcode =  "_mobile_call_now";
  //if there is a beaver builder template called '$shortcode' then echo it after the footer
  $the_query = new WP_Query( array( 'post_type' => 'fl-builder-template', 'post_status' => 'publish', 'name' => $shortcode ) );
  // The Loop
  if ( $the_query->have_posts() ) {

        // then do shortcode theme id
    while ( $the_query->have_posts() ) {
      $the_query->the_post();
      $elementid = get_the_ID();
    }
    // Restore original Post Data 
    wp_reset_postdata();
    
echo "<style>
.mobile-call-now{
display:none;
}
@media (max-width:768px){
    footer{
        margin-bottom:80px;
    }
    #fl-to-top{
        bottom:95px;
    }
    .mobile-call-now{
      float:left;
			display:block;
      position:fixed;
      left:0;
      bottom:0;
      right:0;
			z-index: 100;
    }
}
</style>";
    
    echo '<div class="mobile-call-now">';
    echo do_shortcode('[fl_builder_insert_layout id="' . $elementid . '" type="fl-builder-template" ]');
    echo '</div>';
  }	

}

add_filter('fl_page_close','sp_call_now_on_mobile',999);


