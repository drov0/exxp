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
 * @package           Steempress_sp
 *
 * @wordpress-plugin
 * Plugin Name:       Steempress
 * Description:       Publishes your article to the hive blockchain automatically to allow you to earn money and reach new audiences.
 * Version:           2.6.3
 * Author:            Martin Lees
 * Author URI:        https://hive.blog/@howo
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       steempress_sp
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
define( 'steempress_sp_compte', '2.6.3');

define( 'steempress_sp_api_url', 'https://api.steempress.io');
define( 'steempress_sp_twoway_api_url', 'https://two.steempress.io');
define( 'steempress_sp_twoway_api_back', 'https://sc.steempress.io');


//define( 'steempress_sp_api_url', 'http://localhost:8001');
//define( 'steempress_sp_twoway_api_url', 'http://localhost:3000');
//define( 'steempress_sp_twoway_api_back', 'http://localhost:8102');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-steempress_sp-activator.php
 */
function activate_steempress_sp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-steempress_sp-activator.php';
	Steempress_sp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-steempress_sp-deactivator.php
 */
function deactivate_steempress_sp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-steempress_sp-deactivator.php';
	Steempress_sp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_steempress_sp' );
register_deactivation_hook( __FILE__, 'deactivate_steempress_sp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-steempress_sp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_steempress_sp() {

	$plugin = new Steempress_sp();
	$plugin->run();

}
run_steempress_sp();
