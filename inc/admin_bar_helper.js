jQuery(document).ready(function($){
  
  replace_admin_text("wp-admin-bar-edit","Edit Page","Edit Page Settings");
  
  // remove https from site links so it doesnt go to the "not secure warning"
  jQuery("#wp-admin-bar-my-sites-list li.menupop a").each(function(){
    siteref = jQuery(this).attr('href');
    siteref = siteref.replace("https", "http");
    jQuery(this).attr("href", siteref);
  });
  
 
  
  
});


function replace_admin_text(id,old_text,new_text){

  var pb = jQuery("#"+id+" a").html();
  if(pb){
    pb = pb.replace(old_text,new_text);
    jQuery("#"+id+" a").html(pb);
  }
}