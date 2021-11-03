<?php 
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link [plugin_url]
 * @package [package]
 * @subpackage [package]/core
 * @since [version]
 */
class WooCommerce_Plugin_Boiler_Plate_Activator {
	
    public function __construct() {
    }
	
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		require_once(PLUGIN_INC.'helpers/class-version-check.php');
		require_once(PLUGIN_INC.'helpers/class-dependencies.php');
		
		if(WooCommerce_Plugin_Boiler_Plate_Dependencies(PLUGIN_DEPEN)){
			WooCommerce_Plugin_Boiler_Plate_Version_Check::activation_check('3.7');	
		} else {
			if ( is_plugin_active(PLUGIN_FILE) ) { deactivate_plugins(PLUGIN_FILE);} 
			wp_die(wc_pbp_dependency_message());
		}
	} 
 
}