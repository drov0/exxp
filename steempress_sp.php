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
 * Description:       Publishes your article to the steem blockchain automatically to allow you to earn money and reach new audiences.
 * Version:           1.3.1
 * Author:            Martin Lees
 * Author URI:        https://steemit.com/@howo
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
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'steempress_sp_compte', '1.3.1' );

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
