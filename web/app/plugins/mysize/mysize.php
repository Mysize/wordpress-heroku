<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              [plugin_url]
 * @since             1.1
 * @package           Mysize
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Mysize Plugin
 * Plugin URI:        https://www.mysizeid.com/
 * Description:       WooCommerce Mysize Plugin
 * Version:           1.1
 * Author:            MySize
 * Author URI:        https://www.mysizeid.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mysize-plugin
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
  die;
}

define('PLUGIN_FILE', plugin_basename(__FILE__));
define('PLUGIN_PATH', plugin_dir_path(__FILE__)); # Plugin DIR
define('PLUGIN_INC', PLUGIN_PATH . 'includes/'); # Plugin INC Folder
define('PLUGIN_DEPEN', 'woocommerce/woocommerce.php');

register_activation_hook(__FILE__, 'wc_pbp_activate_plugin');
register_deactivation_hook(__FILE__, 'wc_pbp_deactivate_plugin');
register_deactivation_hook(PLUGIN_DEPEN, 'wc_pbp_dependency_deactivate');

include "env_config.php";

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function wc_pbp_activate_plugin()
{
  require_once(PLUGIN_INC . 'helpers/class-activator.php');
  WooCommerce_Plugin_Boiler_Plate_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function wc_pbp_deactivate_plugin()
{
  require_once(PLUGIN_INC . 'helpers/class-deactivator.php');
  WooCommerce_Plugin_Boiler_Plate_Deactivator::deactivate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function wc_pbp_dependency_deactivate()
{
  require_once(PLUGIN_INC . 'helpers/class-deactivator.php');
  WooCommerce_Plugin_Boiler_Plate_Deactivator::dependency_deactivate();
}

function wc_inject_mysize()
{
  global $product;
  $product_id = $product->get_id();
  $options = get_option('wc_pbp_general');  
  $retailer_token = $options['wc_pbp_retailer_token_textbox'];  
  $chart_code = get_post_meta($product_id, 'wc_mysize_product_chart_code');
  // require(PLUGIN_INC . 'helpers/embed_script.php');
}

function wc_mysize_product_chart_code()
{

  $field = array(
    'id' => 'wc_mysize_product_chart_code',
    'label' => __('Mysize Chart Code', 'textdomain'),
    // 'data_type' => 'price' //Let WooCommerce formats our field as price field
  );

  woocommerce_wp_text_input($field);
}

/**
 * Adding a custom tab
 */
function mysize_tab($tabs)
{

  $tabs['mysize'] = array(
    'label' => __('Mysize', 'textdomain'),
    'target' => 'mysize_panel',
    'class' => array(),
  );

  return $tabs;
}

function mysize_tab_panel()
{ ?>
  <div id="mysize_panel" class="panel woocommerce_options_panel">
    <div class="options_group">
      <?php
      $field = array(
        'id' => 'wc_mysize_product_chart_code',
        'label' => __('Mysize Chart Code', 'textdomain'),
      );
      woocommerce_wp_text_input($field);
      ?>
    </div>
  </div>
  <?php
}

function save_mysize_field($post_id)
{
  $custom_field_value = isset($_POST['wc_mysize_product_chart_code']) ? $_POST['wc_mysize_product_chart_code'] : '';
  $product = wc_get_product($post_id);
  $product->update_meta_data('wc_mysize_product_chart_code', $custom_field_value);
  $product->save();
}

require_once(PLUGIN_INC . 'functions.php');
require_once(PLUGIN_PATH . 'bootstrap.php');

if (!function_exists('WooCommerce_Plugin_Boiler_Plate')) {
  function WooCommerce_Plugin_Boiler_Plate()
  {
    add_filter('woocommerce_product_data_tabs', 'mysize_tab');

    add_action('woocommerce_product_data_panels', 'mysize_tab_panel');

    add_action('woocommerce_process_product_meta', 'save_mysize_field');

    add_action('woocommerce_after_single_product', 'wc_inject_mysize', 10);

    return WooCommerce_Plugin_Boiler_Plate::get_instance();
  }
}

WooCommerce_Plugin_Boiler_Plate();

if (!function_exists('WooCommerce_MySize_Add_Columns_To_Products')) {
  function WooCommerce_MySize_Add_Columns_To_Products(&$columns)
  {
    $columns['mysize_chart_code'] = 'MySize Chart Code';
    return $columns;
  }
}
add_filter('manage_edit-product_columns', 'WooCommerce_MySize_Add_Columns_To_Products', 11);

if (!function_exists('WooCommerce_MySize_Handle_Product_Chart_Code_Column')) {
  function WooCommerce_MySize_Handle_Product_Chart_Code_Column($column, $product_id)
  {
    if ($column !== 'mysize_chart_code') return;
    $chart_code = get_post_meta($product_id, 'wc_mysize_product_chart_code', true);
    ?>
    <div data-chart-code-product-id="<?php echo $product_id ?>">
      <div class="input_chart_code">
        <input type="text" value="<?php echo $chart_code ?>">
        <button type="button" class="save">Save</button>
        <button type="button" class="cancel">Cancel</button>
      </div>
      <span><?php echo $chart_code; ?></span>
    </div>
    <?php
  }
}
add_action('manage_product_posts_custom_column', 'WooCommerce_MySize_Handle_Product_Chart_Code_Column', 10, 2);

if (!function_exists('WooCommerce_Products_Table_MySize_Chart_Code_Styles')) {
  function WooCommerce_Products_Table_MySize_Chart_Code_Styles()
  { ?>
    <style>
      div[data-chart-code-product-id] {
        position: relative;
      }

      div[data-chart-code-product-id] span {
        position: relative;
        text-decoration: none;
        border-bottom: 1px dotted #000;
        padding-left: 15px;
        cursor: pointer;
      }

      div[data-chart-code-product-id] span::after {
        position: absolute;

        display: inline-block;
        font-family: 'dashicons';
        content: '\f464';

        top: 50%;
        left: 0;
        transform: translateY(-50%);

        pointer-events: none;
      }

      div[data-chart-code-product-id] .input_chart_code {
        display: none;
        position: absolute;
        bottom: 100%;
        width: 100%;
        right: 50%;
        background-color: #b0b0b0;
      }

      div[data-chart-code-product-id] .input_chart_code.open {
        display: block;
      }

      div[data-chart-code-product-id] .input_chart_code input {
        width: 100%;
      }
    </style>
    <?php
  }
}
add_action('admin_head', 'WooCommerce_Products_Table_MySize_Chart_Code_Styles');

if (!function_exists('WooCommerce_Products_Table_MySize_Chart_Code_Scripts')) {
  function WooCommerce_Products_Table_MySize_Chart_Code_Scripts($hook)
  {
    wp_enqueue_script('WooCommerce_Products_Table_MySize_Chart_Code_Scripts', plugin_dir_url(__FILE__) . '/includes/js/products-table-chart-code-column.js');
  }
}
add_action('admin_enqueue_scripts', 'WooCommerce_Products_Table_MySize_Chart_Code_Scripts');

if (!function_exists('woocommerce_mysize_update_chart_code')) {
  function woocommerce_mysize_update_chart_code()
  {
    $product_id = $_POST['product_id'];
    $value = $_POST['value'];

    $meta_key = 'wc_mysize_product_chart_code';
    delete_post_meta($product_id, $meta_key);
    add_post_meta($product_id, $meta_key, $value);

    wp_die();
  }
}
add_action('wp_ajax_woocommerce_mysize_update_chart_code', 'woocommerce_mysize_update_chart_code');

if (!function_exists('loadPixelWooScript')){

  function loadPixelWooScript() {
      $url = $GLOBALS['url_analytics'];
      $url_widget = $GLOBALS['url_widget'];
      $options = get_option('wc_pbp_general');
      $retailer_token = $options['wc_pbp_retailer_token_textbox'];
      $size_chart_code = get_post_meta(get_queried_object_id(), 'wc_mysize_product_chart_code', true);
      if ($retailer_token != null ) {
          wp_enqueue_script( 'loadPixelExScript', "$url/pixelWoo.js?retailer_token=$retailer_token&size_chart_code=$size_chart_code");
          wp_enqueue_script( 'loadWidgetWooScript', "$url_widget/v1/js/woocommerce.js?retailer_token=$retailer_token&integration_code=$size_chart_code");
      }
  }
}

add_action( 'wp_enqueue_scripts', 'loadPixelWooScript' );
