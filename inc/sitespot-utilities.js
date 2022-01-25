

jQuery( function($) { 
  // apply class to parent and all childeren links will become un clickable - good for advanced posts logo sliders and things
  $('.kill-links').on('click', 'a', function() {
      return false;  
  });

  //remove draft menu items from menus (also hidden in CSS, but this is a little bonus assurance)
  $('.menu-item.draft, .menu-item.pending').remove();

  //remove sub menu
  $('.kill-sub-menu .sub-menu').remove();

  // pull up the first found link to the block element - useful for rows and columns that we want to make clickable
  if (!$("html").hasClass("fl-builder-edit"))
  {
    $("body:not(.fl-builder-edit) .pull-a").on('click',function(){
      window.location.href = $(this).find('a[href!=""]').attr('href'); //find a link with an existent 'href' [href!=""] href is not blank
    });
  }
 });
