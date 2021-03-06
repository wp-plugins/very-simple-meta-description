<?php
/**
 * Plugin Name: Very Simple Meta Description
 * Description: This is a very simple plugin to add meta description in the header of your WordPress blog. For more info please check readme file.
 * Version: 1.7
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
    register_setting( 'vsmd-options', 'vsmd-setting', 'sanitize_text_field' );
    add_settings_section( 'vsmd-section', __( 'Description', 'metadescription' ), 'vsmd_section_callback', 'vsmd' );
    add_settings_field( 'vsmd-field', __( 'Your meta description', 'metadescription' ), 'vsmd_field_callback', 'vsmd', 'vsmd-section' );
}
add_action( 'admin_init', 'vsmd_admin_init' );


function vsmd_section_callback() {
    echo __( 'This is a very simple plugin to add meta description of max. 150 characters in the header of your WordPress blog.', 'metadescription' ); 
}


function vsmd_field_callback() {
    $setting = esc_attr( get_option( 'vsmd-setting' ) );
    echo "<input type='text' size='60' maxlength='150' name='vsmd-setting' value='$setting' />";
}


// display the admin options page
function vsmd_options_page() {
?>
<div class="wrap"> 
	<div id="icon-plugins" class="icon32"></div> 
	<h1><?php _e( 'Very Simple Meta Description', 'metadescription' ); ?></h1> 
	<form action="options.php" method="POST">
	<?php settings_fields( 'vsmd-options' ); ?>
	<?php do_settings_sections( 'vsmd' ); ?>
	<?php submit_button(__('Save Meta', 'metadescription')); ?>
	</form>
	<p><?php _e( 'Search engines like Google use the meta description in search results.', 'metadescription' ); ?></p>
	<p><?php _e( 'Note: if no meta description is entered, the blog description will be used.', 'metadescription' ); ?></p>
</div>
<?php
}


// include meta description in header 
function vsmd_meta_description() {
	$vsmd_meta = esc_attr( get_option( 'vsmd-setting' ) );
	$vsmd_descr = get_bloginfo( 'description' );
	if (empty($vsmd_meta)) {
		echo '<meta name="description" content="'.$vsmd_descr.'" />'."\n";
	}
	else {
		echo '<meta name="description" content="'.$vsmd_meta.'" />'."\n";
	}
}
add_action( 'wp_head', 'vsmd_meta_description' );
?>