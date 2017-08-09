(function($) {
	var popup_wud;
	function popupWUD() {
		if ( typeof popup_wud_data !== 'undefined' && $.isArray( popup_wud_data ) ) { 
			for ( var i=0; i<popup_wud_data.length; i++) { 
				/* Get the allowed pages */
				if ( 	popup_wud_data[i].pages=='all'
					||  (popup_wud_data[i].pages=='shortcode'&& popup_wud_data[i].page_type=='all')				
					|| (popup_wud_data[i].pages=='front' && popup_wud_data[i].page_type=='index') 
					|| (popup_wud_data[i].pages=='interior' && (popup_wud_data[i].page_type=='page' || popup_wud_data[i].page_type=='post' || popup_wud_data[i].page_type=='archive')) 
					|| (popup_wud_data[i].pages=='post' && popup_wud_data[i].page_type=='post')
					|| (popup_wud_data[i].pages=='page' && popup_wud_data[i].page_type=='page')
					|| (popup_wud_data[i].pages=='archive' && popup_wud_data[i].page_type=='archive')
					|| (popup_wud_data[i].pages=='postpage' && (popup_wud_data[i].page_type=='post' || popup_wud_data[i].page_type=='page'))
					|| (popup_wud_data[i].pages=='postarchive' && (popup_wud_data[i].page_type=='post' || popup_wud_data[i].page_type=='archive'))
					|| (popup_wud_data[i].pages=='pagearchive' && (popup_wud_data[i].page_type=='page' || popup_wud_data[i].page_type=='archive'))
					){ 

					
					var cookieName = 'popup-wud-' + popup_wud_data[i].id; 
					if (!GetPopupWudCookie(cookieName) && popup_wud_data[i].viewed!=true) { 
						popup_wud_data[i].viewed = true; 
				/* START CSS values */
						//Add Classes ID and Slug
						$('#cssWUD').addClass('popup-id-' + popup_wud_data[i].id + ' popup-slug-' + popup_wud_data[i].slug );
						//Buttons Left
						if (popup_wud_data[i].buttons=='left'){
							$('#cssWUD').addClass('wud-buttons-left');
							$('#cssWUD .wud-close-button').css('display','inline-block');
						} 
						//Buttons Center
						else if (popup_wud_data[i].buttons=='center'){
							$('#cssWUD').addClass('wud-buttons-center');
							$('#cssWUD .wud-close-button').css('display','inline-block');
						} 
						//Buttons Right
						else if (popup_wud_data[i].buttons=='right'){
							$('#cssWUD').addClass('wud-buttons-right');
							$('#cssWUD .wud-close-button').css('display','inline-block');
						} 	
						//Remove Class Allow Scrolling
						$('#cssWUD .wud-dialog').removeClass('allow-scrolling');					
						//Clear used CSS values
							PopUpClearCSS();
						//Top Center - Middle Center - Bottom Center
						if (popup_wud_data[i].location=='tc' || popup_wud_data[i].location=='mc' || popup_wud_data[i].location=='bc'){	
								$('#cssWUD .wud-content').css('margin','0 auto');
								$('#cssWUD .wud-content').css('left','0');
								$('#cssWUD .wud-content').css('right','0');
						}
						//Middle Left
						if (popup_wud_data[i].location=='tl' || popup_wud_data[i].location=='ml' || popup_wud_data[i].location=='bl'){	
								$('#cssWUD .wud-content').css('float','left');
								$('#cssWUD .wud-content').css('margin-left','0.6%');
								$('#cssWUD .wud-content').css('left','0');
						}
						//Middle Right
						if (popup_wud_data[i].location=='tr' || popup_wud_data[i].location=='mr' || popup_wud_data[i].location=='br'){	
								$('#cssWUD .wud-content').css('float','right');
								$('#cssWUD .wud-content').css('margin-right','0.6%');
								$('#cssWUD .wud-content').css('right','0');
						}	
						
						//Top Center - Top Left - Top Right
						if (popup_wud_data[i].location=='tc' || popup_wud_data[i].location=='tl' || popup_wud_data[i].location=='tr'){									
							$('#cssWUD .wud-content').css('position','absolute');
							$('#cssWUD .wud-content').css('top','1%');
							$('#cssWUD .wud-content').css('-webkit-transform','translateY(-1%)');
							$('#cssWUD .wud-content').css('-ms-transform','translateY(-1%)');
							$('#cssWUD .wud-content').css('transform','translateY(-1%)');
						} 
						// Middle Center - Middle Left - Middle Right
						if (popup_wud_data[i].location=='mc' || popup_wud_data[i].location=='ml' || popup_wud_data[i].location=='mr'){
							$('#cssWUD .wud-content').css('position','absolute');
							$('#cssWUD .wud-content').css('top','50%');
							$('#cssWUD .wud-content').css('-webkit-transform','translateY(-50%)');
							$('#cssWUD .wud-content').css('-ms-transform','translateY(-50%)');
							$('#cssWUD .wud-content').css('transform','translateY(-50%)');
						} 
						//Bottom Center - Bottom Left - Bottom Right
						if (popup_wud_data[i].location=='bc' || popup_wud_data[i].location=='bl' || popup_wud_data[i].location=='br'){
							$('#cssWUD .wud-content').css('position','absolute');
							$('#cssWUD .wud-content').css('bottom','1%');
						} 

							
						//PopUp Print button
						if(popup_wud_data[i].click=='print'){
							if (popup_wud_data[i].buttons=='no'){
								$('#cssWUD .wud-print-button').css('display','inline-block');
								$('#cssWUD .wud-buttons').css('text-align','right');
							}
							else{
								$('#cssWUD .wud-print-button').css('display',(popup_wud_data[i].click=='print'?'inline-block':'none'));
							}			
						}
						
						//Other CSS values :-)
						$('#cssWUD .wud-content').css('width',popup_wud_data[i].width)+'px';						
						$('#cssWUD .wud-body').css('color',popup_wud_data[i].color);
						$('#cssWUD .wud-body').css('background-color',popup_wud_data[i].bgcolor);
						$('#cssWUD .wud-body-content').html(popup_wud_data[i].body); 
						$('#cssWUD .wud-title').html(popup_wud_data[i].poptitle);
						$('#cssWUD .wud-title').css('color',popup_wud_data[i].title_clr);
						$('#cssWUD .wud-title').css('font-size',popup_wud_data[i].title_fs+'px');
						$('#cssWUD .wud-content').css('font-size',popup_wud_data[i].text_fs+'px');
						$('#cssWUD .wud-close-button').css('background-color',popup_wud_data[i].button_bg);
						$('#cssWUD .wud-close-button').css('color',popup_wud_data[i].button_fg);
						$('#cssWUD .wud-close-button').css('font-size',popup_wud_data[i].button_fs+'px');						
						$('#cssWUD .wud-print-button').css('background-color',popup_wud_data[i].print_bg);
						$('#cssWUD .wud-print-button').css('color',popup_wud_data[i].print_fg);
						$('#cssWUD .wud-print-button').css('font-size',popup_wud_data[i].print_fs+'px');						
						$('#cssWUD .wud-title').css('display',(popup_wud_data[i].title=='no'?'none':'block'));
						
				/* END CSS values */		
						
				/* Lay-out Admin sample popup */
						if (typeof popup_wud_admin !== 'undefined') {
							popup_wud_data[i].location='mc';
							$('#cssWUD .wud-content').css('margin','0 auto');
							$('#cssWUD .wud-content').css('left','0');
							$('#cssWUD .wud-content').css('right','0');							
							$('#cssWUD .wud-content').css('position','absolute');
							$('#cssWUD .wud-content').css('top','50%');
							$('#cssWUD .wud-content').css('-webkit-transform','translateY(-50%)');
							$('#cssWUD .wud-content').css('-ms-transform','translateY(-50%)');
							$('#cssWUD .wud-content').css('transform','translateY(-50%)');							
							popup_wud_data[i].animation_in ="none";
							popup_wud_data[i].animation_out ="none";
							$('#cssWUD .wud-body-content').css('word-wrap','break-word');
							$('#cssWUD .wud-body-content').css('white-space','pre-wrap');
						}

				//OPEN the PopUp WUD
						$('html').addClass('popup-wud-open');
						setTimeout(function() {
							$('#cssWUD').show();
							
							//Animated PopUp OPEN
							if (popup_wud_data[i].animation_in !=="none") {
								$('#cssWUD .wud-content').hide();
								if (popup_wud_data[i].location=='tr' || popup_wud_data[i].location=='mr' || popup_wud_data[i].location=='br'){
									$('#cssWUD .wud-content').show(popup_wud_data[i].animation_in, { direction: "right" }, 1000);
								}
								if (popup_wud_data[i].location=='tl' || popup_wud_data[i].location=='ml' || popup_wud_data[i].location=='bl'){
									$('#cssWUD .wud-content').show(popup_wud_data[i].animation_in, { direction: "left" }, 1000);
								}
								if (popup_wud_data[i].location=='bc' || popup_wud_data[i].location=='mc'){
									$('#cssWUD .wud-content').show(popup_wud_data[i].animation_in, { direction: "down" }, 1000);
								}	
								if (popup_wud_data[i].location=='tc'){
									$('#cssWUD .wud-content').show(popup_wud_data[i].animation_in, { direction: "up" }, 1000);
								}								
							}
							
							//Simple PopUp OPEN
							else{
								$('#cssWUD .wud-content').show();
							}
							
						}, 350 );
						
						//Set Cookie
						if (popup_wud_data[i].freq!=-1) SetPopupWudCookie(cookieName, popup_wud_data[i].freq); 
						popup_wud = popup_wud_data[i];
						
						//Check Mouse Out action
						//$(document).on('mousemove', leaveFromTop);
	
						
				//Click on CLOSE [button]
						$('.wud-close-button').click(function(event) {
							//Animated PopUp CLOSE
							if (popup_wud_data[i].animation_out !=="none") {
								
								if (popup_wud_data[i].location=='tr' || popup_wud_data[i].location=='mr' || popup_wud_data[i].location=='br'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "right" }, 1000);
								}
								if (popup_wud_data[i].location=='tl' || popup_wud_data[i].location=='ml' || popup_wud_data[i].location=='bl'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "left" }, 1000);
								}
								if (popup_wud_data[i].location=='bc' || popup_wud_data[i].location=='mc'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "down" }, 1000);
								}	
								if (popup_wud_data[i].location=='tc'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "up" }, 1000);
								}
							}
							else{
								$('#cssWUD .wud-content').hide();
							}							
							PopUpClose();
						});

				//Click on CLOSE [X]
						$('.wud-close').click(function(event) {
							//Animated PopUp CLOSE
							if (popup_wud_data[i].animation_out !=="none") {
								
								if (popup_wud_data[i].location=='tr' || popup_wud_data[i].location=='mr' || popup_wud_data[i].location=='br'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "right" }, 1000);
								}
								if (popup_wud_data[i].location=='tl' || popup_wud_data[i].location=='ml' || popup_wud_data[i].location=='bl'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "left" }, 1000);
								}
								if (popup_wud_data[i].location=='bc' || popup_wud_data[i].location=='mc'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "down" }, 1000);
								}	
								if (popup_wud_data[i].location=='tc'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "up" }, 1000);
								}
							}
							else{
								$('#cssWUD .wud-content').hide();
							}							
							PopUpClose();
						});
						
				//Click on PRINT [button]
						$('.wud-print-button').click(function(event) {
							window.print();
						});
						
				//Click on OUTSIDE CONTENT
						$(document).click(function(event) { 
						  if(!$(event.target).is('#cssWUD') && $(event.target).is('.wud-dialog')){
							//Animated PopUp CLOSE
							if (popup_wud_data[i].animation_out !=="none") {
								
								if (popup_wud_data[i].location=='tr' || popup_wud_data[i].location=='mr' || popup_wud_data[i].location=='br'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "right" }, 1000);
								}
								if (popup_wud_data[i].location=='tl' || popup_wud_data[i].location=='ml' || popup_wud_data[i].location=='bl'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "left" }, 1000);
								}
								if (popup_wud_data[i].location=='bc' || popup_wud_data[i].location=='mc'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "down" }, 1000);
								}	
								if (popup_wud_data[i].location=='tc'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "up" }, 1000);
								}
							}
							else{
								$('#cssWUD .wud-content').hide();
							}								
							  PopUpClose();
						  }       
						})	
						
				//Click on ESCAPE BUTTON
						$(document).keyup(function(event) {
							event.preventDefault();
						  if(event.keyCode==27){
							//Animated PopUp CLOSE
							if (popup_wud_data[i].animation_out !=="none") {
								
								if (popup_wud_data[i].location=='tr' || popup_wud_data[i].location=='mr' || popup_wud_data[i].location=='br'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "right" }, 1000);
								}
								if (popup_wud_data[i].location=='tl' || popup_wud_data[i].location=='ml' || popup_wud_data[i].location=='bl'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "left" }, 1000);
								}
								if (popup_wud_data[i].location=='bc' || popup_wud_data[i].location=='mc'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "down" }, 1000);
								}	
								if (popup_wud_data[i].location=='tc'){
									$('#cssWUD .wud-content').hide(popup_wud_data[i].animation_out, { direction: "up" }, 1000);
								}
							}
							else{
								$('#cssWUD .wud-content').hide();
							}								
							PopUpClose();
						  } 
						});
						$(window).resize(PopupWudResize);
						break;
					} 
				}
			}
		}
	}

	
	function leaveFromTop(e) {
	e = e || window.event;
	e = jQuery.event.fix(e);
		if( e.pageY < 60 ){						  
		$('#cssWUD').show();
		$('#cssWUD .wud-content').show();
		}						  
	};
						
	function PopUpClearCSS(){
		$('#cssWUD .wud-content').css('left','');
		$('#cssWUD .wud-content').css('right','');
		$('#cssWUD .wud-content').css('top','');
		$('#cssWUD .wud-content').css('bottom','')		
		$('#cssWUD .wud-content').css('float','');
		$('#cssWUD .wud-content').css('margin','');
		$('#cssWUD .wud-content').css('margin-left','');
		$('#cssWUD .wud-content').css('margin-right','');
		$('#cssWUD .wud-content').css('margin-top','');
		$('#cssWUD .wud-content').css('margin-bottom','');
		$('#cssWUD .wud-content').css('position','');;
		$('#cssWUD .wud-content').css('-webkit-transform','');
		$('#cssWUD .wud-content').css('-ms-transform','');
		$('#cssWUD .wud-content').css('transform','');			
	}
	
	function PopUpClose() {
		$('#cssWUD.wud-dialog').unbind('click');
		$('.wud-print-button').unbind('click');
		$('#cssWUD .wud-close-button').unbind('click');
		$('#cssWUD').unbind('click');
		$(document).unbind('keyup');
		$(window).off('resize', PopupWudResize);
		if (popup_wud.id==-1) {
			$('#cssWUD .wud-body-content').empty();
			$('#wpwrap').css('visibility', 'visible');
			$(window).trigger('resize');
		}

		$('#cssWUD').fadeOut(popup_wud.id==-1?0:400, function() {
			$('#cssWUD').removeAttr('class');
			$('html').removeClass('popup-wud-open');
			if (typeof popup_wud_admin === 'undefined'){
				popupWUD();
			}  
		});
	}
	
	function PopupWudResize() {
		if ($('#cssWUD .wud-content').outerHeight()>=window.innerHeight/1.05) {
			$('#cssWUD .wud-dialog').addClass('allow-scrolling');
		} else {
			$('#cssWUD .wud-dialog').removeClass('allow-scrolling');
			$('#cssWUD .wud-close').removeAttr('style');
		}
	}
	
	if (typeof popup_wud_admin === 'undefined'){
		$(document).ready(function() {
			if ($('#popup_disable').not('checked')) {
				popupWUD();
			}
		});
	}

/* ADMIN Test PopUp*/	
	if (typeof popup_wud_admin !== 'undefined') {
		$(document).ready(function() {	
			$('.popup-wud-test').click( function(event) {
				event.preventDefault();
				popup_wud_data[0].viewed = false;
				popup_wud_data[0].click		= $('#popup_click').val();		
				popup_wud_data[0].location	= $('#popup_location').val();
				popup_wud_data[0].width		= $('#popup_width').val();
				popup_wud_data[0].animation_in		= $('#popup_animation_in').val();
				popup_wud_data[0].animation_out		= $('#popup_animation_out').val();
				popup_wud_data[0].title			= $('#popup_title').val();
				popup_wud_data[0].title_clr	= $('#popup_title_color').val();
				popup_wud_data[0].title_fs		= $('#popup_title_fs').val();
				popup_wud_data[0].text_fs		= $('#popup_text_fs').val();
				popup_wud_data[0].color		= $('#popup_color').val();
				popup_wud_data[0].bgcolor		= $('#popup_bgcolor').val();
				popup_wud_data[0].buttons	= $('#popup_buttons').val();
				popup_wud_data[0].button_bg		= $('#popup_button_bg').val();
				popup_wud_data[0].button_fg		= $('#popup_button_fg').val();
				popup_wud_data[0].button_fs		= $('#popup_button_fs').val();
				popup_wud_data[0].print_bg		= $('#popup_print_bg').val();
				popup_wud_data[0].print_fg		= $('#popup_print_fg').val();
				popup_wud_data[0].print_fs		= $('#popup_print_fs').val();
				popup_wud_data[0].poptitle	= $('#post-body-content #title').val();
				popup_wud_data[0].body		= $('#post-body-content #content').val();
				popupWUD(); 
			});
			
			$('.popup-wud-adm .color-picker').wpColorPicker();
			$('.popup-wud-adm .wp-picker-default').attr('value','Previous');
			$('.popup-wud-adm .date-picker').datepicker({dateFormat:'mm-dd-yy'});
			
			if ($('#popup_disable').prop('checked')) {
				$('.popup-wud-overlay').css('display', 'block');
				$('.popup-wud-overlay').css('position', 'absolute');
				$('.popup-wud-overlay').css('z-index', '999999');
				$('.popup-wud-test').css('visibility', 'hidden');
				$('.popup-wud-test').prop( "disabled", true );
			}
			else{
				$('.popup-wud-overlay').css('display', 'none');
				$('.popup-wud-overlay').css('position', 'initial');
				$('.popup-wud-test').prop( "disabled", false );
				$('.popup-wud-test').css('visibility', 'visible');				
				$('.popup-wud-overlay').css('z-index', '-1');
			}
			$('#popup_disable').click( function(event) {
				if ($(this).prop('checked')) {
					$('.popup-wud-overlay').css('display', 'block');
					$('.popup-wud-overlay').css('position', 'absolute');
					$('.popup-wud-overlay').css('z-index', '999999');
					$('.popup-wud-test').css('visibility', 'hidden');
					$('.popup-wud-test').prop( "disabled", true );
				} else {
					$('.popup-wud-overlay').css('display', 'none');
					$('.popup-wud-overlay').css('position', 'initial');
					$('.popup-wud-test').prop( "disabled", false );
					$('.popup-wud-test').css('visibility', 'visible');
					$('.popup-wud-overlay').css('z-index', '-1');
				}
			});
		});
	}
})( jQuery );

function SetPopupWudCookie(cname, expires) {
	var cvalue = 'Next:';
	if (expires>0) {
		var cookieDate = new Date;
		cookieDate.setTime(cookieDate.getTime()+(expires*3600000));
		expires = " expires=" + cookieDate.toGMTString() + ";";
		cvalue += cookieDate.toLocaleDateString()+'-'+cookieDate.toLocaleTimeString();
	} else {
		expires = "";
		cvalue += 'session';
	}
	var popup_wud_hostname = location.hostname.split('.');
	var popup_wud_seclev_dom = popup_wud_hostname.slice(-2).join('.');
	document.cookie = cname + "=" + cvalue + ";" + expires + " path=/; domain=." + popup_wud_seclev_dom;
}

function GetPopupWudCookie(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1);
		if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
	}
	return false;
}