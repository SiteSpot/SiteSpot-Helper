jQuery(document).ready(function($) {
    
  jQuery('#fl-builder-blocks-advanced .fl-builder-block-module, #fl-builder-blocks-SitespotBuilderExtensionModules .fl-builder-block-module').each(function() {
    jQuery(this).clone(true).appendTo('#fl-builder-blocks-basic>.fl-builder-blocks-section-content');
  });

  jQuery('#fl-builder-blocks-advanced, #fl-builder-blocks-SitespotBuilderExtensionModules, .fl-builder-modules-cta, .fl-builder-global-settings-button, .fl-builder-uabb-global-settings-button').remove();
  jQuery("#fl-builder-blocks-basic .fl-builder-blocks-section-title").html("Modules<i class='fa fa-chevron-down'></i>");

  

  //refreshes the media modal when you swap the dropdown to 'all'
  jQuery(document).on('change','#media-attachment-date-filters',function(){
    if(jQuery('#media-attachment-date-filters').val() == 'all')
    {
      console.log('SiteSpot: Refreshing media list');
      if (wp.media.frame.content.get() !== null) 
      {          
          // this forces a refresh of the content
          wp.media.frame.content.get().collection._requery(true);
          // optional: reset selection
          wp.media.frame.content.get().options.selection.reset();
      }
    }
  });
  
  
  
  // pre select the home pages template section

  
  /*function Sp_preselect_template_dropdown() {
    var OldHtml = window.jQuery.fn.html;
    window.jQuery.fn.html = function() {
      var EnhancedHtml = OldHtml.apply(this, arguments);
      if (arguments.length && EnhancedHtml.find('.fl-template-selector').length) {
        var ssinter = setInterval(function() {
          if (jQuery('.fl-template-category-select').is(":hidden")) {
          } else {
            jQuery('.fl-template-category-select').val("fl-builder-settings-tab-landing").change();
            clearInterval(ssinter);
          }
        }, 200)
      }
      return EnhancedHtml;
    }
  }
  jQuery(Sp_preselect_template_dropdown);
*/
});