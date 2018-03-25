<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www2.gov.bc.ca/
 * @since             1.0.0
 * @package           WordPress_Technical_Test
 *
 * @wordpress-plugin
 * Plugin Name:       WordPress Technical Test
 * Plugin URI:        https://www2.gov.bc.ca/
 * Description:       The WordPress technical test for the Province of British Columbia - GCPE.
 * Version:           1.0.0
 * Author:            Province of British Columbia
 * Author URI:        https://www2.gov.bc.ca/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wordpress-technical-test
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wordpress-technical-test-activator.php
 */
function activate_wordpress_technical_test() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wordpress-technical-test-activator.php';
	WordPress_Technical_Test_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wordpress-technical-test-deactivator.php
 */
function deactivate_wordpress_technical_test() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wordpress-technical-test-deactivator.php';
	WordPress_Technical_Test_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wordpress_technical_test' );
register_deactivation_hook( __FILE__, 'deactivate_wordpress_technical_test' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wordpress-technical-test.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wordpress_technical_test() {

	$plugin = new WordPress_Technical_Test();
	$plugin->run();

}
run_wordpress_technical_test();


?>