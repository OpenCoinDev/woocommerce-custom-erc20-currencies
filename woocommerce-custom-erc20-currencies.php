<?php
/*
Plugin Name:  WooCommerce Customize ERC20 Currency
Plugin URI:   https://inkerk.github.io/blog/2018/11/01/woocommerce-customize-erc20-currency-plugin/
Description:  A Plugin can add a new Currency for ERC20 Support.
Version:      0.0.3
Author:       inKerk Blockchain Inc.
Author URI:   http://www.inkerk.com/
License:      GPL3
License URI:  https://www.gnu.org/licenses/gpl-3.0.html
Text Domain:  woo-custom-erc20
Domain Path:  /languages
 */
if (!defined('ABSPATH')) {
	exit;
}
function woo_custom_erc20_load_plugin_textdomain() {

	$path = basename(dirname(__FILE__)) . '/languages';
	$result = load_plugin_textdomain('woo-custom-erc20', FALSE, $path);
	if ($result) {
		return;
	}

	// $locale = apply_filters('theme_locale', get_locale(), 'woo-custom-erc20');
	// die("Could not find $path/$locale.mo.");
}
add_action('plugins_loaded', 'woo_custom_erc20_load_plugin_textdomain');
/**
 * custom currency
 */
add_filter('woocommerce_currencies', 'woo_custom_erc20_add_my_currency');

function woo_custom_erc20_add_my_currency($currencies) {
	$options = get_option('woo_custom_erc20_settings');

	$currencies['ERC20'] = $options['currency_name'];
	return $currencies;
}

/**
 * custom currency symbol
 */
add_filter('woocommerce_currency_symbol', 'woo_custom_erc20_add_my_currency_symbol', 10, 2);

function woo_custom_erc20_add_my_currency_symbol($currency_symbol, $currency) {
	$options = get_option('woo_custom_erc20_settings');
	switch ($currency) {
	case 'ERC20':$currency_symbol = $options['currency_symbol'];
		break;
	}
	return $currency_symbol;
}
add_action('admin_menu', 'woo_custom_erc20_add_admin_menu');
add_action('admin_init', 'woo_custom_erc20_settings_init');

function woo_custom_erc20_add_admin_menu() {

	add_options_page(__('WooCommerce Customize ERC20 Currency', 'woo-custom-erc20'), __('WooCommerce Customize ERC20 Currency', 'woo-custom-erc20'), 'manage_options', 'woocommerce_customize_erc20_currency', 'woo_custom_erc20_options_page');

}

function woo_custom_erc20_settings_init() {

	register_setting('woo_custom_erc20_plugin_page', 'woo_custom_erc20_settings');

	add_settings_section(
		'woo_custom_erc20_woo_custom_erc20_plugin_page_section',
		__('Currency Settings', 'woo-custom-erc20'),
		'woo_custom_erc20_settings_section_callback',
		'woo_custom_erc20_plugin_page'
	);

	add_settings_field(
		'currency_name',
		__('Currency Name', 'woo-custom-erc20'),
		'woo_custom_erc20_currency_name_render',
		'woo_custom_erc20_plugin_page',
		'woo_custom_erc20_woo_custom_erc20_plugin_page_section'
	);

	add_settings_field(
		'currency_symbol',
		__('Currency Symbol', 'woo-custom-erc20'),
		'woo_custom_erc20_currency_symbol_render',
		'woo_custom_erc20_plugin_page',
		'woo_custom_erc20_woo_custom_erc20_plugin_page_section'
	);

}

function woo_custom_erc20_currency_name_render() {

	$options = get_option('woo_custom_erc20_settings');
	?>
    <input type='text' name='woo_custom_erc20_settings[currency_name]' value='<?php echo $options['currency_name']; ?>'>
    <?php

}

function woo_custom_erc20_currency_symbol_render() {

	$options = get_option('woo_custom_erc20_settings');
	?>
    <input type='text' name='woo_custom_erc20_settings[currency_symbol]' value='<?php echo $options['currency_symbol']; ?>'>
    <?php

}

function woo_custom_erc20_settings_section_callback() {

	echo __('Settings Your Currency below.', 'woo-custom-erc20');

}

function woo_custom_erc20_options_page() {

	?>
    <form action='options.php' method='post'>

        <h1><?php _e('WooCommerce Customize ERC20 Currency', 'woo-custom-erc20');?></h1>

        <?php
settings_fields('woo_custom_erc20_plugin_page');
	do_settings_sections('woo_custom_erc20_plugin_page');
	submit_button();
	?>

    </form>
    <?php

}
