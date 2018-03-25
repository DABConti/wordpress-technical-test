<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www2.gov.bc.ca/
 * @since      1.0.0
 *
 * @package    WordPress_Technical_Test
 * @subpackage WordPress_Technical_Test/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WordPress_Technical_Test
 * @subpackage WordPress_Technical_Test/includes
 * @author     Province of British Columbia <no-reply@gov.bc.ca>
 */
class WordPress_Technical_Test_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wordpress-technical-test',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
