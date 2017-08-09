<?php
/*
=== PopUp WUD ===
Contributors: wistudat.be
Plugin Name: PopUp WUD
Donate Reason: Enough for a cup of coffee?
Donate link: https://www.paypal.me/WudPluginsCom
Description: Easily create responsive  popups, with optionally: print with a single click.
Author: Danny WUD
Author URI: https://wud-plugins.com
Plugin URI: https://wud-plugins.com
Tags: popup, pop-up, pop up, advertise, marketing, popover, responsive, wordpress popup, wp popup, print, print button, shortcode, short code, modal, modal window, modal popup
Requires at least: 3.6
Tested up to: 4.7
Stable tag: 1.0.6
Version: 1.0.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: popup-wud
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
//==============================================================================//
$POPUP_WUD_VERSION='1.0.6';
// Store the latest version.
if (get_option('popup_wud_version')!=$POPUP_WUD_VERSION) {popup_wud_update();}
//==============================================================================//
if ( ! defined( 'POPUP_WUD_URL' ) ) {
	define( 'POPUP_WUD_URL', plugin_dir_url( __FILE__ ) );
	define( 'POPUP_WUD_PATH',  plugin_dir_path( __FILE__ ) );
	add_action( 'init', function(){ new popup_wud_class();}, 0 );
}

// START POPUP_WUD CLASS
class popup_wud_class {
	
	//Define constants
	const POPUP_WUD =  'popup_wud', POPUP_WUD_DIS = 'popup_disable', POPUP_WUD_FRQ = 'popup_freq', POPUP_WUD_EXP = 'popup_expires', POPUP_WUD_PAG = 'popup_pages', POPUP_WUD_CLK = 'popup_click',
	      POPUP_WUD_LOC = 'popup_location', POPUP_WUD_WTH = 'popup_width', POPUP_WUD_TIT = 'popup_title', POPUP_WUD_CLR = 'popup_color', POPUP_WUD_BGR = 'popup_bgcolor', 
		  POPUP_WUD_BUT = 'popup_buttons', POPUP_WUD_BBG = 'popup_button_bg', POPUP_WUD_BFG = 'popup_button_fg', POPUP_WUD_PBG = 'popup_print_bg', POPUP_WUD_PFG = 'popup_print_fg',
		  POPUP_WUD_BFS = 'popup_button_fs', POPUP_WUD_PFS = 'popup_print_fs', POPUP_WUD_TCL = 'popup_title_color', POPUP_WUD_TFS = 'popup_title_fs', POPUP_WUD_XFS = 'popup_text_fs', 
		  POPUP_WUD_ANI = 'popup_animation_in', POPUP_WUD_ANO = 'popup_animation_out';
	
	//Init and actions
	public function __construct() {
		$page_type=NULL;
		$this->popup_wud_init();
		add_action( 'save_post_'.self::POPUP_WUD, array( $this, 'post_save' ) );
		add_filter( 'manage_edit-'.self::POPUP_WUD.'_columns', array( $this, 'popup_wud_columns' ) ) ;
		add_action( 'manage_'.self::POPUP_WUD.'_posts_custom_column', array( $this, 'popup_wud_columns_data' ) , 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'popup_wud_build' ) );
		add_shortcode( 'popupwud', 'popup_wud_comm' );
		add_action( 'init',          'wpdocs_theme_add_editor_styles' );
		add_action( 'pre_get_posts', 'wpdocs_theme_add_editor_styles' );
		add_action('wp_enqueue_scripts', 'popup_wud_jquery');
	}

	// Send values to Javascript
	public function popup_wud_build() {	
	global $page_type, $POPUP_WUD_VERSION;
		if(!is_admin() && (is_page() || is_single() || is_archive() || is_home() || is_front_page() )) {
			if(is_front_page() || is_home()){$page_type="index";}
			elseif(is_page()){$page_type="page";}
			elseif(is_single()){$page_type="post";}
			elseif(is_archive()){$page_type="archive";}
			else {$page_type="unknow";}
		}	
		
		$popup_content = get_posts( array( 'post_type' => self::POPUP_WUD ) );
		$popup_var = array();
		foreach ($popup_content as $content_popup) {
			$vars = array();
			$vars['page_type']	= $page_type;
			$vars['disable']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_DIS, true );
			$vars['animation_in']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_ANI, true );
			$vars['animation_out']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_ANO, true );
			$vars['freq']		= get_post_meta( $content_popup->ID, self::POPUP_WUD_FRQ, true );
			$vars['expires']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_EXP, true );
			$vars['pages']		= get_post_meta( $content_popup->ID, self::POPUP_WUD_PAG, true );
			$vars['click']		= get_post_meta( $content_popup->ID, self::POPUP_WUD_CLK, true );
			$vars['location']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_LOC, true );
			$vars['width']		= get_post_meta( $content_popup->ID, self::POPUP_WUD_WTH, true );
			$vars['title']		= get_post_meta( $content_popup->ID, self::POPUP_WUD_TIT, true );
			$vars['title_clr']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_TCL, true );
			$vars['title_fs']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_TFS, true );
			$vars['color']		= get_post_meta( $content_popup->ID, self::POPUP_WUD_CLR, true );
			$vars['bgcolor']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_BGR, true );
			$vars['buttons']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_BUT, true );
			$vars['button_bg']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_BBG, true );
			$vars['button_fg']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_BFG, true );
			$vars['button_fs']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_BFS, true );
			$vars['print_bg']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_PBG, true );
			$vars['print_fg']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_PFG, true );
			$vars['print_fs']	= get_post_meta( $content_popup->ID, self::POPUP_WUD_PFS, true );			
			$vars['id']			= $content_popup->ID;
			$vars['slug']		= $content_popup->post_name;
			$vars['poptitle']	= $content_popup->post_title;
			$vars['body']		= apply_filters( 'the_content', $content_popup->post_content );

			if ( $vars['disable']!='yes' ) {
				if ( empty( $vars['expires'] ) ) {
					$popup_var[] = $vars;
				} else {
					$dateParts = explode( '-', $vars['expires'] );
					if ( count( $dateParts ) == 3 ) {
						$expires = mktime( 23, 59, 59, $dateParts[0], $dateParts[1], $dateParts[2] );
						if ( time() <= $expires) {
							$popup_var[] = $vars;
						}
					} else {
					}
				}
			}
		}
		if ( count( $popup_var ) > 0 ) {
			wp_register_script( 'popup_wud', POPUP_WUD_URL . 'js/popup-wud.js', 'jQuery', $POPUP_WUD_VERSION , true );
			wp_enqueue_script( 'popup_wud' );
			wp_enqueue_style( 'popup_wud', POPUP_WUD_URL . 'css/popup-wud.css'  );
			wp_localize_script( 'popup_wud', 'popup_wud_data', $popup_var );
			add_action( 'wp_footer' , array( $this , 'popup_wud_template' ) );
		}
	}

	
	// PopUp save data
	public function post_save( $post_id ) {
		
		//wp_nonce_field( plugin_basename(__FILE__), self::POPUP_WUD . '_nonce' );
		
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ self::POPUP_WUD . '_nonce' ] ) && wp_verify_nonce( $_POST[ self::POPUP_WUD . '_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
		if ( $is_autosave || $is_revision || !$is_valid_nonce) return;
		// For the PopUp Cookie value
		$POPUP_WUD_DATA = get_post_meta( $post_id );
		
	// Check Data
		$disable = sanitize_text_field( @$_POST[self::POPUP_WUD_DIS] );
		$animation_in = sanitize_text_field( @$_POST[self::POPUP_WUD_ANI] );
		$animation_out = sanitize_text_field( @$_POST[self::POPUP_WUD_ANO] );
		$freq = sanitize_text_field( @$_POST[self::POPUP_WUD_FRQ] );
		$expires = sanitize_text_field( @$_POST[self::POPUP_WUD_EXP] );
		$pages = sanitize_text_field( @$_POST[self::POPUP_WUD_PAG] );
		$click = sanitize_text_field( @$_POST[self::POPUP_WUD_CLK] );
		$location = sanitize_text_field( @$_POST[self::POPUP_WUD_LOC] );
		$width = sanitize_text_field( @$_POST[self::POPUP_WUD_WTH] );
		$title = sanitize_text_field( @$_POST[self::POPUP_WUD_TIT] );
		$color = strtolower( sanitize_text_field( @$_POST[self::POPUP_WUD_CLR] ) );
		$bgcolor = strtolower( sanitize_text_field( @$_POST[self::POPUP_WUD_BGR] ) );
		$buttons = sanitize_text_field( @$_POST[self::POPUP_WUD_BUT] );
		$popup_button_bg = strtolower( sanitize_text_field( @$_POST[self::POPUP_WUD_BBG] ) );
		$popup_button_fg = strtolower( sanitize_text_field( @$_POST[self::POPUP_WUD_BFG] ) );
		$popup_print_bg = strtolower( sanitize_text_field( @$_POST[self::POPUP_WUD_PBG] ) );
		$popup_print_fg = strtolower( sanitize_text_field( @$_POST[self::POPUP_WUD_PFG] ) );
		$popup_button_fs = sanitize_text_field( @$_POST[self::POPUP_WUD_BFS] );
		$popup_print_fs = sanitize_text_field( @$_POST[self::POPUP_WUD_PFS] );
		$popup_title_color = strtolower( sanitize_text_field( @$_POST[self::POPUP_WUD_TCL] ) );
		$popup_title_fs = sanitize_text_field( @$_POST[self::POPUP_WUD_TFS] ) ;
		$popup_text_fs = sanitize_text_field( @$_POST[self::POPUP_WUD_XFS] ) ;

		//Text color
		if ( substr($color, 0, 1) != '#' ) $color = '#' . $color;
		if ( !preg_match( '/^#(?:[0-9a-fA-F]{3}){1,2}$/i', $color ) ) $color = '#000000';
		//Text background
		if ( substr($bgcolor, 0, 1) != '#' ) $bgcolor = '#' . $bgcolor;
		if ( !preg_match( '/^#(?:[0-9a-fA-F]{3}){1,2}$/i', $bgcolor ) ) $bgcolor = '#FFFFFF';
		//Close background
		if ( substr($popup_button_bg, 0, 1) != '#' ) $popup_button_bg = '#' . $popup_button_bg;
		if ( !preg_match( '/^#(?:[0-9a-fA-F]{3}){1,2}$/i', $popup_button_bg ) ) $popup_button_bg = '#000000';
		//Close color
		if ( substr($popup_button_fg, 0, 1) != '#' ) $popup_button_fg = '#' . $popup_button_fg;
		if ( !preg_match( '/^#(?:[0-9a-fA-F]{3}){1,2}$/i', $popup_button_fg ) ) $popup_button_fg = '#FFFFFF';
		//Print background
		if ( substr($popup_print_bg, 0, 1) != '#' ) $popup_print_bg = '#' . $popup_print_bg;
		if ( !preg_match( '/^#(?:[0-9a-fA-F]{3}){1,2}$/i', $popup_print_bg ) ) $popup_print_bg = '#000000';
		//Print color
		if ( substr($popup_print_fg, 0, 1) != '#' ) $popup_print_fg = '#' . $popup_print_fg;
		if ( !preg_match( '/^#(?:[0-9a-fA-F]{3}){1,2}$/i', $popup_print_fg ) ) $popup_print_fg = '#FFFFFF';
		//Ttle color 
		if ( substr($popup_title_color, 0, 1) != '#' ) $popup_title_color = '#' . $popup_title_color;
		if ( !preg_match( '/^#(?:[0-9a-fA-F]{3}){1,2}$/i', $popup_title_color ) ) $popup_title_color = '#000000';
		
		// Save the Data	
		update_post_meta( $post_id, self::POPUP_WUD_DIS, $disable ); 
		update_post_meta( $post_id, self::POPUP_WUD_ANI, $animation_in );
		update_post_meta( $post_id, self::POPUP_WUD_ANO, $animation_out );
		if ( is_numeric($freq) && $freq >= -1 && $freq <= 876000 ) update_post_meta( $post_id, self::POPUP_WUD_FRQ, $freq );
		update_post_meta( $post_id, self::POPUP_WUD_EXP, $expires );
		if ( !empty($pages) ) update_post_meta( $post_id, self::POPUP_WUD_PAG, $pages );
		if ( !empty($click) ) update_post_meta( $post_id, self::POPUP_WUD_CLK, $click );
		if ( !empty($location) ) update_post_meta( $post_id, self::POPUP_WUD_LOC, $location );
		if ( !empty($width) ) update_post_meta( $post_id, self::POPUP_WUD_WTH, $width );
		if ( !empty($title) ) update_post_meta( $post_id, self::POPUP_WUD_TIT, $title );
		if ( !empty($color) ) update_post_meta( $post_id, self::POPUP_WUD_CLR, $color );
		if ( !empty($bgcolor) ) update_post_meta( $post_id, self::POPUP_WUD_BGR, $bgcolor );
		if ( !empty($buttons) ) update_post_meta( $post_id, self::POPUP_WUD_BUT, $buttons );
		if ( !empty($popup_button_bg) ) update_post_meta( $post_id, self::POPUP_WUD_BBG, $popup_button_bg );
		if ( !empty($popup_button_fg) ) update_post_meta( $post_id, self::POPUP_WUD_BFG, $popup_button_fg );
		if ( !empty($popup_print_bg) ) update_post_meta( $post_id, self::POPUP_WUD_PBG, $popup_print_bg );
		if ( !empty($popup_print_fg) ) update_post_meta( $post_id, self::POPUP_WUD_PFG, $popup_print_fg );
		if ( !empty($popup_button_fs) ) update_post_meta( $post_id, self::POPUP_WUD_BFS, $popup_button_fs );
		if ( !empty($popup_print_fs) ) update_post_meta( $post_id, self::POPUP_WUD_PFS, $popup_print_fs );
		if ( !empty($popup_title_color) ) update_post_meta( $post_id, self::POPUP_WUD_TCL, $popup_title_color );
		if ( !empty($popup_title_fs) ) update_post_meta( $post_id, self::POPUP_WUD_TFS, $popup_title_fs );
		if ( !empty($popup_text_fs) ) update_post_meta( $post_id, self::POPUP_WUD_XFS, $popup_text_fs );
		
		// PopUp Cookie set
		if ( $freq != @$POPUP_WUD_DATA[self::POPUP_WUD_FRQ][0] ) {
			$popup_wud_hostname = explode( '.', $_SERVER['HTTP_HOST']);
			$popup_wud_seclev_dom = '.'.implode( '.', array_slice( $popup_wud_hostname, -2 ) );
			unset( $_COOKIE['popup-wud-' . $post_id] );
			setcookie( 'popup-wud-' . $post_id, '', 1, '/', $popup_wud_seclev_dom );
		}
	}

	// PopUp meta box
	public function add_meta_box_wud() {
		add_meta_box('popup_metabox', __( 'PopUp WUD Options', 'popup-wud' ), array($this, 'generate_metabox' ), self::POPUP_WUD, 'side', 'default');
	}
	
	// PopUp meta box content
	public function generate_metabox( $post ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'jquery-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_style( 'popup_wud', POPUP_WUD_URL . 'css/popup-wud.css'  );
		wp_enqueue_script( 'popup_wud', POPUP_WUD_URL . 'js/popup-wud.js' );
		wp_enqueue_style( 'popup_wud_adm', POPUP_WUD_URL . 'css/popup-wud-adm.css'  );
		wp_enqueue_script( 'popup_wud_adm', POPUP_WUD_URL . 'js/popup-wud-admin.js' );
		
		$POPUP_WUD_DATA = get_post_meta( $post->ID );
		wp_nonce_field( plugin_basename(__FILE__), self::POPUP_WUD . '_nonce' );

		// Get the meta values
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_DIS] ) ) $popup_disable = $POPUP_WUD_DATA[self::POPUP_WUD_DIS][0]; else $popup_disable = '';
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_ANI] ) ) $popup_animation_in = $POPUP_WUD_DATA[self::POPUP_WUD_ANI][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_ANO] ) ) $popup_animation_out = $POPUP_WUD_DATA[self::POPUP_WUD_ANO][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_FRQ] ) ) $popup_freq = floatval( $POPUP_WUD_DATA[self::POPUP_WUD_FRQ][0] );
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_EXP] ) ) $popup_expires = $POPUP_WUD_DATA[self::POPUP_WUD_EXP][0]; else $popup_expires = '';
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_PAG] ) ) $popup_pages = $POPUP_WUD_DATA[self::POPUP_WUD_PAG][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_CLK] ) ) $popup_click = $POPUP_WUD_DATA[self::POPUP_WUD_CLK][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_WTH] ) ) $popup_width = intval( $POPUP_WUD_DATA[self::POPUP_WUD_WTH][0] ); else $popup_width = '800';
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_TIT] ) ) $popup_title = $POPUP_WUD_DATA[self::POPUP_WUD_TIT][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_CLR] ) ) $popup_color = $POPUP_WUD_DATA[self::POPUP_WUD_CLR][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_BGR] ) ) $popup_bgcolor = $POPUP_WUD_DATA[self::POPUP_WUD_BGR][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_LOC] ) ) $popup_location = $POPUP_WUD_DATA[self::POPUP_WUD_LOC][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_BUT] ) ) $popup_buttons = $POPUP_WUD_DATA[self::POPUP_WUD_BUT][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_BBG] ) ) $popup_button_bg = $POPUP_WUD_DATA[self::POPUP_WUD_BBG][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_BFG] ) ) $popup_button_fg = $POPUP_WUD_DATA[self::POPUP_WUD_BFG][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_PBG] ) ) $popup_print_bg = $POPUP_WUD_DATA[self::POPUP_WUD_PBG][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_PFG] ) ) $popup_print_fg = $POPUP_WUD_DATA[self::POPUP_WUD_PFG][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_BFS] ) ) $popup_button_fs = $POPUP_WUD_DATA[self::POPUP_WUD_BFS][0]; else $popup_button_fs = '20';
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_PFS] ) ) $popup_print_fs = $POPUP_WUD_DATA[self::POPUP_WUD_PFS][0]; else $popup_print_fs = '20';
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_TCL] ) ) $popup_title_color = $POPUP_WUD_DATA[self::POPUP_WUD_TCL][0];
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_TFS] ) ) $popup_title_fs = $POPUP_WUD_DATA[self::POPUP_WUD_TFS][0]; else $popup_title_fs = '20';
			if ( isset ( $POPUP_WUD_DATA[self::POPUP_WUD_XFS] ) ) $popup_text_fs = $POPUP_WUD_DATA[self::POPUP_WUD_XFS][0]; else $popup_text_fs = '12';
		// Load the template
		require_once ( POPUP_WUD_PATH . 'template/template.php' );
?>
	
	<script>
	var popup_wud_admin = true;
	var popup_wud_data = [{"disable":"","freq":"-1","expires":"","pages":"all","id":"-1","slug":"popup-wud-test"}];
	jQuery('body').append( jQuery('#cssWUD') );
	</script>
	
<?php $popup_wud_comment =  __("Once you have used the slider, you can use the right/left arrow button to adjust the value.", "popup-wud");	?>
<div class="popup-wud-action">
		
		<button class="button popup-wud-test"><?php _e( 'Test this PopUp ', 'popup-wud' ); ?></button>
		
		<b style="margin-left: 6px;"><label><?php _e( 'Disable this PopUp ', 'popup-wud' ); ?>  </label>
		<input id="popup_disable" name="popup_disable" value="yes"<?php echo ($popup_disable=='yes' ? ' checked' : ''); ?> type="checkbox"></b>
</div>		
	
<div class="popup-wud-active">
<img class="popup-wud-overlay"src=" <?php echo POPUP_WUD_URL.'images/overlay.png'; ?>">
<table class="popup-wud-adm">
<tbody>
<!--PopUp WUD Options -->		
		<tr style=" display: block;"><td>
		<strong style="color: #309df5; font-weight: 700;"><?php _e( 'Options', 'popup-wud' ); ?></strong>
		</td></tr>		

		<tr class="popup-wud-adm-sub">
			
			<td><label><?php _e( 'Frequency', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<select class="popup-wud-max" id="popup_freq" name="popup_freq">
					<option value="0"<?php echo ($popup_freq==0 ? ' selected' : ''); ?>><?php _e( 'Once a session', 'popup-wud' ); ?></option>
					<option value="-1"<?php echo ($popup_freq==-1 ? ' selected' : ''); ?>> <?php _e( 'Every refresh (Test Only!)', 'popup-wud' ); ?></option>
					<option value="0.01666"<?php echo ($popup_freq==0.01666 ? ' selected' : ''); ?>> 1 <?php _e( 'minute (Test Only!)', 'popup-wud' ); ?></option>
					<option value="0.08333"<?php echo ($popup_freq==0.08333 ? ' selected' : ''); ?>>5 <?php _e( 'minutes', 'popup-wud' ); ?></option>
					<option value="0.16666"<?php echo ($popup_freq==0.16666 ? ' selected' : ''); ?>>10 <?php _e( 'minutes', 'popup-wud' ); ?></option>
					<option value="0.25"<?php echo ($popup_freq==0.25 ? ' selected' : ''); ?>>15 <?php _e( 'minutes', 'popup-wud' ); ?></option>
					<option value="0.5"<?php echo ($popup_freq==0.5 ? ' selected' : ''); ?>>30 <?php _e( 'minutes', 'popup-wud' ); ?></option>
					<option value="1"<?php echo ($popup_freq==1 ? ' selected' : ''); ?>><?php _e( 'Hourly', 'popup-wud' ); ?></option>
					<option value="2"<?php echo ($popup_freq==2 ? ' selected' : ''); ?>>2 <?php _e( 'hours', 'popup-wud' ); ?></option>
					<option value="4"<?php echo ($popup_freq==4 ? ' selected' : ''); ?>>4 <?php _e( 'hours', 'popup-wud' ); ?></option>
					<option value="8"<?php echo ($popup_freq==8 ? ' selected' : ''); ?>>8 <?php _e( 'hours', 'popup-wud' ); ?></option>
					<option value="16"<?php echo ($popup_freq==16 ? ' selected' : ''); ?>>16 <?php _e( 'hours', 'popup-wud' ); ?></option>
					<option value="24"<?php echo ($popup_freq==24 ? ' selected' : ''); ?>><?php _e( 'Daily', 'popup-wud' ); ?></option>
					<option value="168"<?php echo ($popup_freq==168 ? ' selected' : ''); ?>><?php _e( 'Weekly', 'popup-wud' ); ?></option>
					<option value="720"<?php echo ($popup_freq==720 ? ' selected' : ''); ?>><?php _e( 'Monthly', 'popup-wud' ); ?></option>
					<option value="8760"<?php echo ($popup_freq==8760 ? ' selected' : ''); ?>><?php _e( 'Yearly', 'popup-wud' ); ?></option>
					<option value="87600"<?php echo ($popup_freq>8760 ? ' selected' : ''); ?>><?php _e( 'Once', 'popup-wud' ); ?></option>
				</select>
			</td>
		</tr>
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Expires after', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<input id="popup_expires" name="popup_expires" value="<?php echo $popup_expires; ?>" placeholder="<?php _e( 'Never', 'popup-wud' ); ?>" type="text" class="popup-wud-max popup-wud-max date-picker" title="<?php _e( 'Format: mm-dd-yy (leave empty to never expire)', 'popup-wud' ); ?>">
			</td>
		</tr>
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Display on', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<select class="popup-wud-max" id="popup_pages" name="popup_pages">
					<option value="shortcode"<?php echo ($popup_pages=='shortcode' ? ' selected' : ''); ?>><?php _e( 'Short Code only', 'popup-wud' ); ?></option>
					<option value="all"<?php echo ($popup_pages=='all' ? ' selected' : ''); ?>><?php _e( 'All content', 'popup-wud' ); ?></option>
					<option value="front"<?php echo ($popup_pages=='front' ? ' selected' : ''); ?>><?php _e( 'Front page only', 'popup-wud' ); ?></option>
					<option value="interior"<?php echo ($popup_pages=='interior' ? ' selected' : ''); ?>><?php _e( 'Posts, pages and archives', 'popup-wud' ); ?></option>
					<option value="postpage"<?php echo ($popup_pages=='postpage' ? ' selected' : ''); ?>><?php _e( 'Posts and pages', 'popup-wud' ); ?></option>
					<option value="post"<?php echo ($popup_pages=='post' ? ' selected' : ''); ?>><?php _e( 'Posts only', 'popup-wud' ); ?></option>
					<option value="page"<?php echo ($popup_pages=='page' ? ' selected' : ''); ?>><?php _e( 'Pages only', 'popup-wud' ); ?></option>
					<option value="archive"<?php echo ($popup_pages=='archive' ? ' selected' : ''); ?>><?php _e( 'Archives only', 'popup-wud' ); ?></option>
					<option value="postarchive"<?php echo ($popup_pages=='postarchive' ? ' selected' : ''); ?>><?php _e( 'Posts and archives', 'popup-wud' ); ?></option>
					<option value="pagearchive"<?php echo ($popup_pages=='pagearchive' ? ' selected' : ''); ?>><?php _e( 'Pages and archives', 'popup-wud' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td><label><?php _e( 'Short Code', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">		
				<b id="showsh" style="font-size:12px; color:red; font-weight: 900;">[popupwud id="<?php echo $post->ID; ?>"]</b>
			</td>
		</tr>
</tbody>
</table>
<table class="popup-wud-adm">
<tbody>		
<!--PopUp WUD Lay-out-->		
		<tr style=" display: block;"><td>
		<strong style="color: #309df5; font-weight: 700;"><?php _e( 'PopUp Lay-out', 'popup-wud' ); ?></strong>
		</td></tr>	
		
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Open Animation', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<select class="popup-wud-max" id="popup_animation_in" name="popup_animation_in">
				    <option value="none"<?php echo ($popup_animation_in=='none' ? ' selected' : ''); ?>><?php _e( 'None', 'popup-wud' ); ?></option>
					<option value="blind"<?php echo ($popup_animation_in=='blind' ? ' selected' : ''); ?>><?php _e( 'Blind', 'popup-wud' ); ?></option>
					<option value="bounce"<?php echo ($popup_animation_in=='bounce' ? ' selected' : ''); ?>><?php _e( 'Bounce', 'popup-wud' ); ?></option>
					<option value="clip"<?php echo ($popup_animation_in=='clip' ? ' selected' : ''); ?>><?php _e( 'Clip', 'popup-wud' ); ?></option>
					<option value="drop"<?php echo ($popup_animation_in=='drop' ? ' selected' : ''); ?>><?php _e( 'Drop', 'popup-wud' ); ?></option>
					<option value="explode"<?php echo ($popup_animation_in=='explode' ? ' selected' : ''); ?>><?php _e( 'Explode', 'popup-wud' ); ?></option>
					<option value="fade"<?php echo ($popup_animation_in=='fade' ? ' selected' : ''); ?>><?php _e( 'Fade', 'popup-wud' ); ?></option>
					<option value="fold"<?php echo ($popup_animation_in=='fold' ? ' selected' : ''); ?>><?php _e( 'Fold', 'popup-wud' ); ?></option>
					<option value="highlight"<?php echo ($popup_animation_in=='highlight' ? ' selected' : ''); ?>><?php _e( 'HighLight', 'popup-wud' ); ?></option>
					<option value="puff"<?php echo ($popup_animation_in=='puff' ? ' selected' : ''); ?>><?php _e( 'Puff', 'popup-wud' ); ?></option>
					<option value="pulsate"<?php echo ($popup_animation_in=='pulsate' ? ' selected' : ''); ?>><?php _e( 'Pulsate', 'popup-wud' ); ?></option>
					<option value="scale"<?php echo ($popup_animation_in=='scale' ? ' selected' : ''); ?>><?php _e( 'Scale', 'popup-wud' ); ?></option>
					<option value="shake"<?php echo ($popup_animation_in=='shake' ? ' selected' : ''); ?>><?php _e( 'Shake', 'popup-wud' ); ?></option>
					<option value="slide"<?php echo ($popup_animation_in=='slide' ? ' selected' : ''); ?>><?php _e( 'Slide', 'popup-wud' ); ?></option>
				</select>
			</td>
		</tr>

		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Close Animation', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<select class="popup-wud-max" id="popup_animation_out" name="popup_animation_out">
				    <option value="none"<?php echo ($popup_animation_out=='none' ? ' selected' : ''); ?>><?php _e( 'None', 'popup-wud' ); ?></option>
					<option value="blind"<?php echo ($popup_animation_out=='blind' ? ' selected' : ''); ?>><?php _e( 'Blind', 'popup-wud' ); ?></option>
					<option value="bounce"<?php echo ($popup_animation_out=='bounce' ? ' selected' : ''); ?>><?php _e( 'Bounce', 'popup-wud' ); ?></option>
					<option value="clip"<?php echo ($popup_animation_out=='clip' ? ' selected' : ''); ?>><?php _e( 'Clip', 'popup-wud' ); ?></option>
					<option value="drop"<?php echo ($popup_animation_out=='drop' ? ' selected' : ''); ?>><?php _e( 'Drop', 'popup-wud' ); ?></option>
					<option value="explode"<?php echo ($popup_animation_out=='explode' ? ' selected' : ''); ?>><?php _e( 'Explode', 'popup-wud' ); ?></option>
					<option value="fade"<?php echo ($popup_animation_out=='fade' ? ' selected' : ''); ?>><?php _e( 'Fade', 'popup-wud' ); ?></option>
					<option value="fold"<?php echo ($popup_animation_out=='fold' ? ' selected' : ''); ?>><?php _e( 'Fold', 'popup-wud' ); ?></option>
					<option value="highlight"<?php echo ($popup_animation_out=='highlight' ? ' selected' : ''); ?>><?php _e( 'HighLight', 'popup-wud' ); ?></option>
					<option value="puff"<?php echo ($popup_animation_out=='puff' ? ' selected' : ''); ?>><?php _e( 'Puff', 'popup-wud' ); ?></option>
					<option value="pulsate"<?php echo ($popup_animation_out=='pulsate' ? ' selected' : ''); ?>><?php _e( 'Pulsate', 'popup-wud' ); ?></option>
					<option value="scale"<?php echo ($popup_animation_out=='scale' ? ' selected' : ''); ?>><?php _e( 'Scale', 'popup-wud' ); ?></option>
					<option value="shake"<?php echo ($popup_animation_out=='shake' ? ' selected' : ''); ?>><?php _e( 'Shake', 'popup-wud' ); ?></option>
					<option value="slide"<?php echo ($popup_animation_out=='slide' ? ' selected' : ''); ?>><?php _e( 'Slide', 'popup-wud' ); ?></option>
				</select>
			</td>
		</tr>
		
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Location', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<select class="popup-wud-max " id="popup_location" name="popup_location">
					<option value="tl"<?php echo ($popup_location=='tl' ? ' selected' : ''); ?>><?php _e( 'Left -> Top', 'popup-wud' ); ?></option>
					<option value="ml"<?php echo ($popup_location=='ml' ? ' selected' : ''); ?>><?php _e( 'Left -> Middle', 'popup-wud' ); ?></option>					
					<option value="bl"<?php echo ($popup_location=='bl' ? ' selected' : ''); ?>><?php _e( 'Left -> Bottom', 'popup-wud' ); ?></option>
					<option value="" disabled="disabled">─────────────</option>
					<option value="tc"<?php echo ($popup_location=='tc' ? ' selected' : ''); ?>><?php _e( 'Center -> Top', 'popup-wud' ); ?></option>
					<option value="mc"<?php echo ($popup_location=='mc' ? ' selected' : ''); ?>><?php _e( 'Center -> Middle', 'popup-wud' ); ?></option>					
					<option value="bc"<?php echo ($popup_location=='bc' ? ' selected' : ''); ?>><?php _e( 'Center -> Bottom', 'popup-wud' ); ?></option>
					<option value="" disabled="disabled">─────────────</option>
					<option value="tr"<?php echo ($popup_location=='tr' ? ' selected' : ''); ?>><?php _e( 'Right -> Top', 'popup-wud' ); ?></option>
					<option value="mr"<?php echo ($popup_location=='mr' ? ' selected' : ''); ?>><?php _e( 'Right -> Middle', 'popup-wud' ); ?></option>					
					<option value="br"<?php echo ($popup_location=='br' ? ' selected' : ''); ?>><?php _e( 'Right -> Bottom', 'popup-wud' ); ?></option>					
				</select>
			</td>
		</tr>
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Max width', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<div id="popup-wud-tip"><b class="popup-trigger" style="background:#3A6779; color: white;">&nbsp;?&nbsp;</b><div class="tooltip"><?php echo __("Value = min.: 200px & max.: 2000px", "popup-wud")."<br><br>".$popup_wud_comment; ?></div></div>
				<input size="2" id="popup_width_val" type="text" style="font-weight:bolder;" value="<?php echo $popup_width; ?>" readonly/>
				<input class="popup-wud-max " id="popup_width" name="popup_width" type="range" min="200" max="2000" value="<?php echo $popup_width; ?>" onchange="popup_width_val.value = popup_width.value" oninput="popup_width_val.value = popup_width.value" placeholder="800" type="text" title="<?php _e( 'Maximum width in pixels', 'popup-wud' ); ?>">
			</td>
		</tr>
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Font size', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<div id="popup-wud-tip"><b class="popup-trigger" style="background:#3A6779; color: white;">&nbsp;?&nbsp;</b><div class="tooltip"><?php echo __("Value = min.: 10px & max.: 60px", "popup-wud")."<br><br>".$popup_wud_comment; ?></div></div>
				<input size="2" id="popup_text_fs_val" type="text" style="font-weight:bolder;" value="<?php echo $popup_text_fs; ?>" readonly/>
				<input class="popup-wud-max " id="popup_text_fs" name="popup_text_fs" type="range" min="10" max="60" value="<?php echo $popup_text_fs; ?>" onchange="popup_text_fs_val.value = popup_text_fs.value" oninput="popup_text_fs_val.value = popup_text_fs.value" placeholder="20" type="text" title="<?php _e( 'Font size in pixels', 'popup-wud' ); ?>">
			</td>
		</tr>		
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Color', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<input id="popup_color" name="popup_color" value="<?php echo (empty($popup_color)?'#000000':$popup_color); ?>" placeholder="#000000" type="text" class="popup-wud-max color-picker" data-default-color="<?php echo (empty($popup_color)?'#000000':$popup_color); ?>">
			</td>
		</tr>
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Background', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<input id="popup_bgcolor" name="popup_bgcolor" value="<?php echo (empty($popup_bgcolor)?'#FFFFFF':$popup_bgcolor); ?>" placeholder="#FFFFFF" type="text" class="popup-wud-max color-picker" data-default-color="<?php echo (empty($popup_bgcolor)?'#FFFFFF':$popup_bgcolor); ?>">
			</td>
		</tr>
</tbody>
</table>
<table class="popup-wud-adm">
<tbody>		
<!--PopUp WUD Title -->	
		<tr style=" display: block;"><td>
		<strong style="color: #309df5; font-weight: 700;"><?php _e( 'PopUp Title', 'popup-wud' ); ?></strong>
		</td></tr>

		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Enabled', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<select class="popup-wud-max" id="popup_title" name="popup_title">
					<option value="yes"<?php echo ($popup_title=='yes' ? ' selected' : ''); ?>><?php _e( 'Yes', 'popup-wud' ); ?></option>
					<option value="no"<?php echo ($popup_title=='no' ? ' selected' : ''); ?>><?php _e( 'No', 'popup-wud' ); ?></option>
				</select>
			</td>
		</tr>
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Font size', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<div id="popup-wud-tip"><b class="popup-trigger" style="background:#3A6779; color: white;">&nbsp;?&nbsp;</b><div class="tooltip"><?php echo __("Value = min.: 10px & max.: 60px", "popup-wud")."<br><br>".$popup_wud_comment; ?></div></div>
				<input size="2" id="popup_title_fs_val" type="text" style="font-weight:bolder;" value="<?php echo $popup_title_fs; ?>" readonly/>
				<input class="popup-wud-max " id="popup_title_fs" name="popup_title_fs" type="range" min="10" max="60" value="<?php echo $popup_title_fs; ?>" onchange="popup_title_fs_val.value = popup_title_fs.value" oninput="popup_title_fs_val.value = popup_title_fs.value" placeholder="20" type="text" title="<?php _e( 'Font size in pixels', 'popup-wud' ); ?>">
			</td>
		</tr>		
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Color', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<input id="popup_title_color" name="popup_title_color" value="<?php echo (empty($popup_title_color)?'#000000':$popup_title_color); ?>" placeholder="#000000" type="text" class="popup-wud-max color-picker" data-default-color="<?php echo (empty($popup_title_color)?'#000000':$popup_title_color); ?>">
			</td>
		</tr>	
</tbody>
</table>
<table class="popup-wud-adm">
<tbody>		
<!--PopUp WUD Close Button-->		
		<tr style=" display: block;"><td>
		<strong style="color: #309df5; font-weight: 700;"><?php _e( 'Close Button', 'popup-wud' ); ?></strong>
		</td></tr>
		
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Location', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<select class="popup-wud-max " id="popup_buttons" name="popup_buttons">
					<option value="no"<?php echo ($popup_buttons=='no' ? ' selected' : ''); ?>><?php _e( 'Hidden', 'popup-wud' ); ?></option>
					<option value="left"<?php echo ($popup_buttons=='left' ? ' selected' : ''); ?>><?php _e( 'Left', 'popup-wud' ); ?></option>
					<option value="center"<?php echo ($popup_buttons=='center' ? ' selected' : ''); ?>><?php _e( 'Center', 'popup-wud' ); ?></option>
					<option value="right"<?php echo ($popup_buttons=='right' ? ' selected' : ''); ?>><?php _e( 'Right', 'popup-wud' ); ?></option>
				</select>
			</td>
		</tr>
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Font size', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<div id="popup-wud-tip"><b class="popup-trigger" style="background:#3A6779; color: white;">&nbsp;?&nbsp;</b><div class="tooltip"><?php echo __("Value = min.: 10px & max.: 60px", "popup-wud")."<br><br>".$popup_wud_comment; ?></div></div>
				<input size="2" id="popup_button_fs_val" type="text" style="font-weight:bolder;" value="<?php echo $popup_button_fs; ?>" readonly/>
				<input class="popup-wud-max " id="popup_button_fs" name="popup_button_fs" type="range" min="10" max="60" value="<?php echo $popup_button_fs; ?>" onchange="popup_button_fs_val.value = popup_button_fs.value" oninput="popup_button_fs_val.value = popup_button_fs.value" placeholder="20" type="text" title="<?php _e( 'Font size in pixels', 'popup-wud' ); ?>">
			</td>
		</tr>		
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Color', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<input id="popup_button_fg" name="popup_button_fg" value="<?php echo (empty($popup_button_fg)?'#FFFFFF':$popup_button_fg); ?>" placeholder="#FFFFFF" type="text" class="popup-wud-max color-picker" data-default-color="<?php echo (empty($popup_button_fg)?'#FFFFFF':$popup_button_fg); ?>">
			</td>
		</tr>		
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Background', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<input id="popup_button_bg" name="popup_button_bg" value="<?php echo (empty($popup_button_bg)?'#000000':$popup_button_bg); ?>" placeholder="#000000" type="text" class="popup-wud-max color-picker" data-default-color="<?php echo (empty($popup_button_bg)?'#000000':$popup_button_bg); ?>">
			</td>
		</tr>
</tbody>
</table>
<table class="popup-wud-adm">
<tbody>		
<!--PopUp WUD Print Button -->		
		<tr style=" display: block;"><td>
		<strong style="color: #309df5; font-weight: 700;"><?php _e( 'Print Button', 'popup-wud' ); ?></strong>
		</td></tr>
		
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Enabled', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<select class="popup-wud-max " id="popup_click" name="popup_click" title="<?php _e( 'Allows user to print the popup contents via a button or clicking inside the popup', 'popup-wud' ); ?>">
					<option value="close"<?php echo ($popup_click=='close' ? ' selected' : ''); ?>><?php _e( 'No', 'popup-wud' ); ?></option>
					<option value="print"<?php echo ($popup_click=='print' ? ' selected' : ''); ?>><?php _e( 'Yes', 'popup-wud' ); ?></option>
				</select>
			</td>
		</tr>		
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Font size', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<div id="popup-wud-tip"><b class="popup-trigger" style="background:#3A6779; color: white;">&nbsp;?&nbsp;</b><div class="tooltip"><?php echo __("Value = min.: 10px & max.: 50px", "popup-wud")."<br><br>".$popup_wud_comment; ?></div></div>
				<input size="2" id="popup_print_fs_val" type="text" style="font-weight:bolder;" value="<?php echo $popup_print_fs; ?>" readonly/>
				<input class="popup-wud-max " id="popup_print_fs" name="popup_print_fs" type="range" min="10" max="50" value="<?php echo $popup_print_fs; ?>" onchange="popup_print_fs_val.value = popup_print_fs.value" oninput="popup_print_fs_val.value = popup_print_fs.value" placeholder="18" type="text" title="<?php _e( 'Font size in pixels', 'popup-wud' ); ?>">
			</td>
		</tr>
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Color', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<input id="popup_print_fg" name="popup_print_fg" value="<?php echo (empty($popup_print_fg)?'#FFFFFF':$popup_print_fg); ?>" placeholder="#FFFFFF" type="text" class="popup-wud-max color-picker" data-default-color="<?php echo (empty($popup_print_fg)?'#FFFFFF':$popup_print_fg); ?>">
			</td>
		</tr>		
		<tr class="popup-wud-adm-sub">
			<td><label><?php _e( 'Background', 'popup-wud' ); ?></label></td>
			<td id="popup_wud_td">
				<input id="popup_print_bg" name="popup_print_bg" value="<?php echo (empty($popup_print_bg)?'#000000':$popup_print_bg); ?>" placeholder="#000000" type="text" class="popup-wud-max color-picker" data-default-color="<?php echo (empty($popup_print_bg)?'#000000':$popup_print_bg); ?>">
			</td>
		</tr>		
</tbody>		
	</table>
</div>	

<div class="popup-wud-bottom">

		<a href="https://wordpress.org/support/plugin/popup-wud" target="_blank">
		<img class="popup-wud-logo"src=" <?php echo POPUP_WUD_URL.'images/wud-support.png'; ?>">
		</a>
		<a href="https://wud-plugins.com" target="_blank">
		<img class="popup-wud-logo"src=" <?php echo POPUP_WUD_URL.'images/popup-wud.png'; ?>">
		</a>	
		
</div>	
	
<?php
	}

	//Set popup in footer
	public function popup_wud_template() {
		require_once ( POPUP_WUD_PATH . 'template/template.php' );
	}
	
	//Init popup menu and labels
	private function popup_wud_init() {
		$labels = array(
			'name'				=> _x( 'PopUp WUD', 'Post Type General Name', 'popup-wud' ),
			'singular_name'	=> _x( 'Popup', 'Post Type Singular Name', 'popup-wud' ),
			'menu_name'		=> __( 'PopUp WUD', 'popup-wud' ),
			'all_items'			=> __( 'All Popups', 'popup-wud' ),
			'view_item'			=> __( 'View Popup', 'popup-wud' ),
			'add_new_item'	=> __( 'Add New Popup', 'popup-wud' ),
			'edit_item'			=> __( 'Edit Popup', 'popup-wud' ),
			'update_item'		=> __( 'Update Popup', 'popup-wud' ),
			'search_items'		=> __( 'Search Popup', 'popup-wud' ),
		);
		$args = array(
			'label'							=> self::POPUP_WUD,
			'description'				=> __( 'PopUp WUD', 'popup-wud' ),
			'labels'						=> $labels,
			'supports'					=> array( 'title', 'editor', 'revisions' ),
			'taxonomies'				=> array(),
			'hierarchical'				=> false,
			'public'						=> true,
			'show_ui'					=> true,
			'show_in_menu'			=> true,
			'show_in_nav_menus'	=> false,
			'show_in_admin_bar'		=> false,
			'menu_position'			=> 25,
			'menu_icon'					=> POPUP_WUD_URL.'images/wud_icon.png',
			'can_export'				=> false,
			'has_archive'				=> false,
			'exclude_from_search'	=> true,
			'publicly_queryable'		=> true,
			'rewrite'						=> false,
			'capability_type'			=> 'post',
			'register_meta_box_cb'	=> array( $this, 'add_meta_box_wud' ),
		);
		register_post_type( self::POPUP_WUD, $args );
	}

	//PopUp List columns
	public function popup_wud_columns( $columns ) {
		unset ($columns['date']);
		$columns += array(
			self::POPUP_WUD_DIS => __( 'Active', 'popup-wud' ),
			self::POPUP_WUD_FRQ => __( 'Freq', 'popup-wud' ),
			self::POPUP_WUD_EXP => __( 'Expires', 'popup-wud' ),
			self::POPUP_WUD_PAG => __( 'Activated', 'popup-wud' ),
			self::POPUP_WUD_BUT => __( 'Close', 'popup-wud' ),
			self::POPUP_WUD_CLK => __( 'Print', 'popup-wud' ),
			self::POPUP_WUD_WTH => __( 'Width', 'popup-wud' ),
			'date' => __( 'Date', 'popup-wud' )
		);
		return $columns;
	}
	
	//PopUp List columns data
	public function popup_wud_columns_data( $column, $post_id ) {
		global $post;
		if ( $column == self::POPUP_WUD_DIS ) {
			$meta = get_post_meta( $post_id, self::POPUP_WUD_DIS, true );
			if ( $meta!='yes' ) echo('&#10004;');
			else echo('&#10006;');
			
		} elseif( $column == self::POPUP_WUD_FRQ ) {
			$meta = floatval( get_post_meta( $post_id, self::POPUP_WUD_FRQ, true ) );
			if ( $meta>8760 ) _e( 'Once', 'popup-wud' );
			elseif( $meta==8760 ) _e( 'Yearly', 'popup-wud' );
			elseif( $meta==720 ) _e( 'Monthly', 'popup-wud' );
			elseif( $meta==168 ) _e( 'Weekly', 'popup-wud' );
			elseif( $meta==24 ) _e( 'Daily', 'popup-wud' );
			elseif( $meta==1 ) _e( 'Hourly', 'popup-wud' );
			elseif( $meta==0 ) _e( 'Session', 'popup-wud' );
			elseif( $meta==0.5 ) _e( '30 mins', 'popup-wud' );
			elseif( $meta==0.25 ) _e( '15 mins', 'popup-wud' );
			elseif( $meta==0.16666 ) _e( '10 mins', 'popup-wud' );
			elseif( $meta==0.08333 ) _e( '5 mins', 'popup-wud' );
			elseif( $meta==0.01666 ) _e( '1 min', 'popup-wud' );
			elseif( $meta==-1 ) _e( 'Always', 'popup-wud' );
			else {
				echo $meta;
				_e( ' Hours', 'popup-wud' );
			}
			
		} elseif( $column == self::POPUP_WUD_EXP ) {
			$meta = get_post_meta( $post_id, self::POPUP_WUD_EXP, true );
			if ( empty( $meta ) ) echo('&#10006;');
			else echo $meta;
			
		} elseif( $column == self::POPUP_WUD_PAG ) {
			$meta = get_post_meta( $post_id, self::POPUP_WUD_PAG, true );
			if ( $meta=='all' ) _e( 'All', 'popup-wud' );
			elseif( $meta=='front' ) _e( 'Front', 'popup-wud' );
			elseif( $meta=='interior' ) _e( 'Post/Page/Archive', 'popup-wud' );
			elseif( $meta=='postpage' ) _e( 'Post/Page', 'popup-wud' );
			elseif( $meta=='post' ) _e( 'Post', 'popup-wud' );
			elseif( $meta=='page' ) _e( 'Page', 'popup-wud' );
			elseif( $meta=='archive' ) _e( 'Archive', 'popup-wud' );
			elseif( $meta=='pagearchive' ) _e( 'Page/Archive', 'popup-wud' );
			elseif( $meta=='postarchive' ) _e( 'Post/Archive', 'popup-wud' );
			elseif( $meta=='shortcode' ) _e( 'Shortcode', 'popup-wud' );
					
		} elseif( $column == self::POPUP_WUD_BUT ) {
			$meta = get_post_meta( $post_id, self::POPUP_WUD_BUT, true );
			if ( $meta=='no' ) echo('&#10006;');
			elseif( $meta=='left' ) _e( 'Left', 'popup-wud' );
			elseif( $meta=='center' ) _e( 'Center', 'popup-wud' );
			elseif( $meta=='right' ) _e( 'Right', 'popup-wud' );
			
		} elseif( $column == self::POPUP_WUD_CLK ) {
			$meta = get_post_meta( $post_id, self::POPUP_WUD_CLK, true );
			if ( $meta=='close' ) echo('&#10004;');
			elseif( $meta=='print' ) echo('&#10006;');
			
		} elseif( $column == self::POPUP_WUD_WTH ) {
			$meta = get_post_meta( $post_id, self::POPUP_WUD_WTH, true );
			echo $meta . ' px';
		}
	}	
	
}
// END POPUP_WUD CLASS


// Shortcode PopUp Wud
function popup_wud_comm( $atts ) {
	global $page_type;
	  //Replace invallid charters	
	  $find = array('/″/', '/”/', '/"/');	
	  $atts = preg_replace($find, '', $atts);
	  //Shortcode extract
	  extract( shortcode_atts(array('id' => ''), $atts ));
		//Check the ID
		if(isset($atts["id"]) && $atts["id"]!='' ){
			if(is_numeric($atts["id"]) && $atts["id"] >= 0 && $atts["id"] == round($atts["id"], 0)){
				$popupid=$atts["id"];
			}
			//If no valid id
			else{return;}
		}//If no id
		else{return;}

		//Get PopUp ID
		$POPUP_WUD_DATA = get_post( $popupid );
		
		//Create PopUp if data is found
		if(!empty($POPUP_WUD_DATA )){
			
			//Check or page is for shortcode only
			if(get_post_meta( $POPUP_WUD_DATA->ID, 'popup_pages', true )!=="shortcode"){
				return;
			}		

			//Check PopUp ID and Post Type
			if($POPUP_WUD_DATA && get_post_type( $popupid ) == 'popup_wud'){
				$popup_var_2 = array();
				$vars_2 = array();
				$vars_2['page_type']	= "all";
				$vars_2['disable']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_disable', true );
				$vars_2['animation_in']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_animation_in', true );
				$vars_2['animation_out']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_animation_out', true );
				$vars_2['freq']		= floatval(get_post_meta( $POPUP_WUD_DATA->ID, 'popup_freq', true ));
				$vars_2['expires']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_expires', true );
				$vars_2['pages']		= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_pages', true );
				$vars_2['click']		= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_click', true );
				$vars_2['location']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_location', true );
				$vars_2['width']		= intval( get_post_meta( $POPUP_WUD_DATA->ID, 'popup_width', true) );
				$vars_2['title']		= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_title', true );
				$vars_2['title_clr']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_title_color', true );
				$vars_2['title_fs']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_title_fs', true );
				$vars_2['text_fs']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_text_fs', true );
				$vars_2['color']		= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_color', true );
				$vars_2['bgcolor']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_bgcolor', true );
				$vars_2['buttons']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_buttons', true );
				$vars_2['button_bg']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_button_bg', true );
				$vars_2['button_fg']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_button_fg', true );
				$vars_2['button_fs']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_button_fs', true );
				$vars_2['print_bg']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_print_bg', true );
				$vars_2['print_fg']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_print_fg', true );
				$vars_2['print_fs']	= get_post_meta( $POPUP_WUD_DATA->ID, 'popup_print_fs', true );			
				$vars_2['id']			= $POPUP_WUD_DATA->ID;
				$vars_2['slug']		= $POPUP_WUD_DATA->post_name;
				$vars_2['poptitle']	= $POPUP_WUD_DATA->post_title;
				$vars_2['body']		= apply_filters( 'the_content', $POPUP_WUD_DATA->post_content );				

				if ( $vars_2['disable']!='yes' ) {
					if ( empty( $vars_2['expires'] ) ) {
						$popup_var_2[] = $vars_2;
					} else {
						$dateParts = explode( '-', $vars_2['expires'] );
						if ( count( $dateParts ) == 3 ) {
							$expires = mktime( 23, 59, 59, $dateParts[0], $dateParts[1], $dateParts[2] );
							if ( time() <= $expires) {
								$popup_var_2[] = $vars_2;
							}
						} else {
						}
					}
				}
				
				wp_localize_script( 'popup_wud', 'popup_wud_data', $popup_var_2 );
			}
		}

	}

	
function popup_wud_jquery() {	
		wp_enqueue_script('jquery');
		wp_enqueue_script("jquery-effects-core");
		wp_enqueue_script("jquery-effects-slide");	
		wp_enqueue_script("jquery-effects-blind");
		wp_enqueue_script("jquery-effects-bounce");
		wp_enqueue_script("jquery-effects-clip");
		wp_enqueue_script("jquery-effects-drop");
		wp_enqueue_script("jquery-effects-explode");
		wp_enqueue_script("jquery-effects-fade");
		wp_enqueue_script("jquery-effects-fold");
		wp_enqueue_script("jquery-effects-highlight");
		wp_enqueue_script("jquery-effects-puff");
		wp_enqueue_script("jquery-effects-pulsate");
		wp_enqueue_script("jquery-effects-scale");
		wp_enqueue_script("jquery-effects-shake");
		wp_enqueue_script("jquery-effects-size");
		wp_enqueue_script("jquery-effects-transfer");
}	

function wpdocs_theme_add_editor_styles() {
    global $post;
    $my_post_type = 'popup_wud';
 
    // New post (init hook).
    if ( false !== stristr( $_SERVER['REQUEST_URI'], 'post-new.php' )
            && ( isset( $_GET['post_type'] ) === true && $my_post_type == $_GET['post_type'] )
    ) {
        add_editor_style( POPUP_WUD_URL . '/css/editor-style-' . $my_post_type . '.css' );
    }
 
    // Edit post (pre_get_posts hook).
    if ( stristr( $_SERVER['REQUEST_URI'], 'post.php' ) !== false
            && is_object( $post )
            && $my_post_type == get_post_type( $post->ID )
    ) {
        add_editor_style( POPUP_WUD_URL . '/css/editor-style-' . $my_post_type . '.css' );
    }
}
	
//Update version number
function popup_wud_update(){
	global $POPUP_WUD_VERSION; 
		update_option('popup_wud_version', $POPUP_WUD_VERSION);			
}

?>