<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Exxp_wp
 *
 * @wordpress-plugin
 * Plugin Name:       Exxp wp
 * Description:       Publishes your article to the hive blockchain automatically to allow you to ear n money and reach new audiences.
 * Version:           2.6.6
 * Author:            Martin Lees
 * Author URI:        https://hive.blog/@howo
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       exxp_wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * using SemVer - https://semver.org
 */
define( 'exxp_wp_compte', '2.6.6');

define( 'exxp_wp_api_url', 'https://api.exxp.io');
define( 'exxp_wp_twoway_api_url', 'https://two.exxp.io');
define( 'exxp_wp_twoway_api_back', 'https://sc.exxp.io');


//define( 'exxp_wp_api_url', 'http://localhost:8001');
//define( 'exxp_wp_twoway_api_url', 'http://localhost:3000');
//define( 'exxp_wp_twoway_api_back', 'http://localhost:8102');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-exxp_wp-activator.php
 */
function activate_exxp_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-exxp_wp-activator.php';
	Exxp_wp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-exxp_wp-deactivator.php
 */
function deactivate_exxp_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-exxp_wp-deactivator.php';
	Exxp_wp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_exxp_wp' );
register_deactivation_hook( __FILE__, 'deactivate_exxp_wp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-exxp_wp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_exxp_wp() {

	$plugin = new Exxp_wp();
	$plugin->run();

}
run_exxp_wp();
