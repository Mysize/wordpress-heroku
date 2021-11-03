<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @link [plugin_url]
 * @package [package]
 * @subpackage [package]/core
 * @since [version]
 */
class WooCommerce_Plugin_Boiler_Plate_Deactivator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
    public static function deactivate() {
        $options = get_option('wc_pbp_general');
        $retailer_token = $options['wc_pbp_retailer_token_textbox'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $GLOBALS['url_api'] . '/woocommerce/uninstall?shop_id=' . $retailer_token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);
        curl_close($ch);
    }

	public static function dependency_deactivate(){ 
		if ( is_plugin_active(PLUGIN_FILE) ) {
			add_action('update_option_active_plugins', array(__CLASS__,'deactivate_dependent'));
		}
	}
	
	public static function deactivate_dependent(){
		deactivate_plugins(PLUGIN_FILE);
	}

}