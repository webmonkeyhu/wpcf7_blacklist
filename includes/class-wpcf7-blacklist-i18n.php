<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://webmonkey.hu
 * @since      1.0.0
 *
 * @package    WPCF7_Blacklist
 * @subpackage WPCF7_Blacklist/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WPCF7_Blacklist
 * @subpackage WPCF7_Blacklist/includes
 * @author     Webmonkey Solutions Kft. <hello@webmonkey.hu>
 */
class WPCF7_Blacklist_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wpcf7-blacklist',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
