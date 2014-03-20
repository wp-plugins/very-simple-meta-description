<?php
/**
 * Plugin Name: Very Simple Meta Description
 * Description: This is a very simple plugin to add meta description in the header of your WordPress blog. For more info please check readme file.
 * Version: 1.0
 * Author: Guido van der Leest
 * Author URI: http://www.guidovanderleest.nl
 * License: GNU General Public License v3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: metadescription
 * Domain Path: translation
 */


// load the plugin's text domain
function vcmd_init() { 
	load_plugin_textdomain( 'metadescription', false, dirname( plugin_basename( __FILE__ ) ) . '/translation' );
}
add_action('plugins_loaded', 'vcmd_init');
 

// add the admin options page
function vsmd_menu_page() {
    add_options_page( __( 'Meta Description', 'metadescription' ), __( 'Meta Description', 'metadescription' ), 'manage_options', 'vsmd', 'vsmd_options_page' );
}
add_action( 'admin_menu', 'vsmd_menu_page' );


// add the admin settings and such 
function vsmd_admin_init() {
    register_setting( 'vsmd-options', 'vsmd-setting', 'vsmd_clean_input' );
    add_settings_section( 'vsmd-section', __( 'Description', 'metadescription' ), 'vsmd_section_callback', 'vsmd' );
    add_settings_field( 'vsmd-field', __( 'Your meta description', 'metadescription' ), 'vsmd_field_callback', 'vsmd', 'vsmd-section' );
}
add_action( 'admin_init', 'vsmd_admin_init' );


function vsmd_section_callback() {
    echo __( ' This is a very simple plugin to add meta description of max. 150 characters in the header of your WordPress blog. If no meta description is entered, the blog description will be used.', 'metadescription' ); 
}


function vsmd_field_callback() {
    $setting = esc_attr( get_option( 'vsmd-setting' ) );
    echo "<input type='text' size='50' maxlength='150' name='vsmd-setting' value='$setting' />";
}


// function to check inputfield
function vsmd_clean_input($str){
	$str1 = preg_replace("/(\s){2,}/",'$1',$str);
	$allowed = "/[^a-z0-9\\040\\.\\-\\_\\\\]/i";
	$str1 = preg_replace($allowed,"",$str1);
	return $str1;
}


// display the admin options page
function vsmd_options_page() {
?>
<div class="wrap"> 
	<div id="icon-themes" class="icon32"></div> 
	<h2><?php _e( 'Very Simple Meta Description', 'metadescription' ); ?></h2> 
	<form action="options.php" method="POST">
	<?php settings_fields( 'vsmd-options' ); ?>
	<?php do_settings_sections( 'vsmd' ); ?>
	<?php submit_button(__('Save Meta', 'metadescription')); ?>
	</form>
</div>
<?php
}


// include meta description in header 
function vsmd_meta_description() {
	$meta = esc_attr( get_option( 'vsmd-setting' ) );
	$descr = get_bloginfo( 'description' );
	if (empty($meta)) 
	{
	echo '<meta name="description" content="'.$descr.'" />'."\n";
	}
	else {
	echo '<meta name="description" content="'.$meta.'" />'."\n";
	}
}
add_action( 'wp_head', 'vsmd_meta_description' );
?>