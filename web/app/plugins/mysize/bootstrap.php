<?php 
/**
 * Plugin Main File
 *
 * @link [plugin_url]
 * @package [package]
 * @subpackage [package]/core
 * @since [version]
 */
if ( ! defined( 'WPINC' ) ) { die; }
 
class WooCommerce_Plugin_Boiler_Plate {
	public $version = '[version]';
	public $plugin_vars = array();
	
	protected static $_instance = null; # Required Plugin Class Instance
    protected static $functions = null; # Required Plugin Class Instance
	protected static $admin = null;     # Required Plugin Class Instance
	protected static $settings = null;  # Required Plugin Class Instance

    /**
     * Creates or returns an instance of this class.
     */
    public static function get_instance() {
        if ( null == self::$_instance ) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    /**
     * Class Constructor
     */
    public function __construct() {
        $this->define_constant();
        $this->load_required_files();
        $this->init_class();
        add_action('plugins_loaded', array( $this, 'after_plugins_loaded' ));
        add_filter('load_textdomain_mofile',  array( $this, 'load_plugin_mo_files' ), 10, 2);
    }
	
	/**
	 * Throw error on object clone.
	 *
	 * Cloning instances of the class is forbidden.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cloning instances of the class is forbidden.', PLUGIN_TXT), PLUGIN_V );
	}	

	/**
	 * Disable unserializing of the class
	 *
	 * Unserializing instances of the class is forbidden.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Unserializing instances of the class is forbidden.',PLUGIN_TXT), PLUGIN_V);
	}

    /**
     * Loads Required Plugins For Plugin
     */
    private function load_required_files(){
       $this->load_files(PLUGIN_INC.'class-*.php');
	   $this->load_files(PLUGIN_SETTINGS.'class-wp-*.php');
        
       if(wc_pbp_is_request('admin')){
           $this->load_files(PLUGIN_ADMIN.'class-*.php');
       } 

        do_action('wc_pbp_before_addons_load');
		$this->load_addons();
    }
    
	public function load_addons(){ 
		$addons = wc_pbp_get_active_addons();
		if(!empty($addons)){
			foreach($addons as $addon){
				if(apply_filters('wc_pbp_load_addon',true,$addon)){
					do_action('wc_pbp_before_'.$addon.'_addon_load');
					$this->load_addon($addon);
					do_action('wc_pbp_after_'.$addon.'_addon_load');
				}
			}
		}
	}
    
	public function load_addon($file){
		if(file_exists(PLUGIN_ADDON.$file)){
			$this->load_files(PLUGIN_ADDON.$file);
		} else if(file_exists($file = apply_filters('wc_pbp_addon_file_location',$file))) {
			$this->load_files($file);
		} else {
            if(has_action('wc_pbp_addon_'.$file.'_load')){
                do_action('wc_pbp_addon_'.$file.'_load');    
            } else {
                
                wc_pbp_deactivate_addon($file);
            }
			
		}
	}
   
    /**
     * Inits loaded Class
     */
    private function init_class(){
        do_action('wc_pbp_before_init');
        self::$functions = new WooCommerce_Plugin_Boiler_Plate_Functions;
		self::$settings = new WooCommerce_Plugin_Boiler_Plate_Settings_Framework; 

        if(wc_pbp_is_request('admin')){
            self::$admin = new WooCommerce_Plugin_Boiler_Plate_Admin;
        }
        do_action('wc_pbp_after_init');
    }
    
	# Returns Plugin's Functions Instance
	public function func(){
		return self::$functions;
	}
	
	# Returns Plugin's Settings Instance
	public function settings(){
		return self::$settings;
	}
	
	# Returns Plugin's Admin Instance
	public function admin(){
		return self::$admin;
	}
    
    /**
     * Loads Files Based On Give Path & regex
     */
    protected function load_files($path,$type = 'require'){
        foreach( glob( $path ) as $files ){
            if($type == 'require'){ require_once( $files ); } 
			else if($type == 'include'){ include_once( $files ); }
        } 
    }
    
    /**
     * Set Plugin Text Domain
     */
    public function after_plugins_loaded(){
        load_plugin_textdomain(PLUGIN_TXT, false, PLUGIN_LANGUAGE_PATH );
    }
    
    /**
     * load translated mo file based on wp settings
     */
    public function load_plugin_mo_files($mofile, $domain) {
        if (PLUGIN_TXT === $domain)
            return PLUGIN_LANGUAGE_PATH.'/'.get_locale().'.mo';

        return $mofile;
    }
    
    /**
     * Define Required Constant
     */
    private function define_constant(){
        $this->define('PLUGIN_NAME', 'WooCommerce MySize Plugin'); # Plugin Name
        $this->define('PLUGIN_SLUG', 'woocommerce-my-size-plugin'); # Plugin Slug
        $this->define('PLUGIN_TXT',  'woocommerce-my-size-plugin'); #plugin lang Domain
		$this->define('PLUGIN_DB', 'wc_pbp_');
		$this->define('PLUGIN_V',$this->version); # Plugin Version
		
		$this->define('PLUGIN_LANGUAGE_PATH',PLUGIN_PATH.'languages'); # Plugin Language Folder
		$this->define('PLUGIN_ADMIN',PLUGIN_INC.'admin/'); # Plugin Admin Folder
		$this->define('PLUGIN_SETTINGS',PLUGIN_ADMIN.'settings_framework/'); # Plugin Settings Folder
		$this->define('PLUGIN_ADDON',PLUGIN_PATH.'addons/');
        
		$this->define('PLUGIN_URL',plugins_url('', __FILE__ ).'/');  # Plugin URL
		$this->define('PLUGIN_CSS',PLUGIN_URL.'includes/css/'); # Plugin CSS URL
		$this->define('PLUGIN_IMG',PLUGIN_URL.'includes/img/'); # Plugin IMG URL
		$this->define('PLUGIN_JS',PLUGIN_URL.'includes/js/'); # Plugin JS URL
        
        
        $this->define('PLUGIN_ADDON_URL',PLUGIN_URL.'addons/');  # Plugin URL
		$this->define('PLUGIN_ADDON_CSS',PLUGIN_ADDON_URL.'includes/css/'); # Plugin CSS URL
		$this->define('PLUGIN_ADDON_IMG',PLUGIN_ADDON_URL.'includes/img/'); # Plugin IMG URL
		$this->define('PLUGIN_ADDON_JS',PLUGIN_ADDON_URL.'includes/js/'); # Plugin JS URL
    }
	
    /**
	 * Define constant if not already set
	 * @param  string $name
	 * @param  string|bool $value
	 */
    protected function define($key,$value){
        if(!defined($key)){
            define($key,$value);
        }
    }
    
}