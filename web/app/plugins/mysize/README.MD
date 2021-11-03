# MySize WooCommerce Plugin
Mysize WooCommerce integration

# Plugin Files
## mysize.php 
Main plugin file

**wc_inject_mysize()** - Injects embed script to product via action: `add_action('woocommerce_after_single_product_summary',  'wc_inject_mysize',  10);`

**WooCommerce_MySize_Add_Columns_To_Products(&$columns)** - Adds ***Mysize chart code*** column to product index page via filter: 
`add_filter('manage_edit-product_columns',  'WooCommerce_MySize_Add_Columns_To_Products',  11);`

**mysize_tab($tabs)** - Adds custom tab to product meta via filter: `add_filter('woocommerce_product_data_tabs',  'mysize_tab');`

**mysize_tab_panel()** - Adds custom tab panel to product meta via filter: `add_action('woocommerce_product_data_panels',  'mysize_tab_panel');`

**save_mysize_field($post_id)** - Saves chart code to product via action: `add_action('woocommerce_process_product_meta',  'save_mysize_field');`

## includes/helpers/embed_script.php 
The script to be embedded to product page
