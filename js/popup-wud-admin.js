/**
=== PopUp WUD ===
Contributors: wistudat.be
Plugin Name: PopUp WUD
Author: Danny WUD
Author URI: https://wud-plugins.com
 */
 
(function($) { 
//START DOCUMENT LOAD
	$(document).ready(function(e)
	{
	  
// Changed value (if shortcode then show #showsh, else hide)
	  $("select").change(function(){
		  if ($("select[name=popup_pages] option:selected").val() == 'shortcode') {
				 $("#showsh").show();
		   }
		   else{
				 $("#showsh").hide();   
		   }
		}).change();	
		
// Onload value (if shortcode then show #showsh (hidden is default) )
	  if ($("select[name=popup_pages] option:selected").val() == 'shortcode') {
			 $("#showsh").show();
			 $("#showsh").show();
	   }
	   
//Change the background color with wpColorPicker   
	$("#popup_bgcolor").wpColorPicker(
	  'option',
	  'change',
	  function(event, ui) {
		var element = event.target;
		var bcolor = ui.color.toString();
		$(tinymce.activeEditor.getBody()).css("background-color", bcolor);	
	  }
	)

//Change the content color with wpColorPicker
	$("#popup_color").wpColorPicker(
	  'option',
	  'change',
	  function(event, ui) {
		var element = event.target;
		var bcolor = ui.color.toString();
		$(tinymce.activeEditor.getBody()).css("color", bcolor);
	  }
	)
	   
	});
//END DOCUMENT LOAD

//Set the defined colors on page load
    $( window ).on( "load", function() {
		//Load only when visual editor is loaded
		if($('.wp-editor-area').css('display') == 'none')
		{
			ChangeTinyMceColor();
		}  
    });

//Set the defined width on change
$(document).on('input change', '#popup_width', function() {
		$(tinymce.activeEditor.getBody()).css("width", $(this).val());
		$(tinymce.activeEditor.getBody().parentNode).css("width", $(this).val());
});

//Set the defined font size on change
$(document).on('input change', '#popup_text_fs', function() {
		$(tinymce.activeEditor.getBody()).css("font-size", $(this).val()+"px");
		$(tinymce.activeEditor.getBody().parentNode).css("font-size", $(this).val()+"px");
});	
   
//Set the defined colors on button click
  $('#content-tmce').click(function(e) {
	  //Wait untill the TinyMce is loaded
		jQuery( document ).on( 'tinymce-editor-init', function( event, editor ) {
			ChangeTinyMceColor();
		});	  	
  })
  	
//Change the TinyMce text and background Color
	function ChangeTinyMceColor(){
		var bcolor = $("#popup_bgcolor").attr('value').toString();
		var fcolor = $("#popup_color").attr('value').toString();
		var mwidth = $("#popup_width").attr('value').toString();
		var fsize = $("#popup_text_fs").attr('value').toString();
		//BC
		
		$(tinymce.activeEditor.getBody('content_ifr')).css("cssText", "font-size: 13px !important; background-color: #fff;  padding: 4% !important; -webkit-box-shadow: 2px 2px 2px 0px rgba(0,0,0,0.75); -moz-box-shadow: 2px 2px 2px 0px rgba(0,0,0,0.75); box-shadow: 2px 2px 2px 0px rgba(0,0,0,0.75); -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px;");

		
		$(tinymce.activeEditor.getBody()).css("background-color", bcolor);
		$(tinymce.activeEditor.getBody().parentNode).css("background-color", "white");
		//FC
		$(tinymce.activeEditor.getBody()).css("color", fcolor);
		$(tinymce.activeEditor.getBody().parentNode).css("color", fcolor);
		//Width
		$(tinymce.activeEditor.getBody()).css("width", mwidth);
		$(tinymce.activeEditor.getBody().parentNode).css("width", mwidth);	
		//Font-Size
		$(tinymce.activeEditor.getBody()).css("font-size", fsize+"px");
		$(tinymce.activeEditor.getBody().parentNode).css("font-size", fsize+"px");		
	}

})(jQuery);