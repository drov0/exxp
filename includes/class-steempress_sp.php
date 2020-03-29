<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://hive.blog/@howo
 * @since      1.0.0
 *
 * @package    Steempress_sp
 * @subpackage Steempress_sp/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Steempress_sp
 * @subpackage Steempress_sp/includes
 */
class Steempress_sp {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Steempress_sp_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'steempress_sp_compte' ) ) {
			$this->version = steempress_sp_compte;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'steempress_sp';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Steempress_sp_Loader. Orchestrates the hooks of the plugin.
	 * - Steempress_sp_i18n. Defines internationalization functionality.
	 * - Steempress_sp_Admin. Defines all hooks for the admin area.
	 * - Steempress_sp_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-steempress_sp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-steempress_sp-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-steempress_sp-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-steempress_sp-public.php';

		$this->loader = new Steempress_sp_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Steempress_sp_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Steempress_sp_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Steempress_sp_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
        $this->loader->add_action( 'wp_head', $plugin_admin, 'steempress_sp_add_meta_tag' );

        // Add Settings link to the plugin
        $plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
        $this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );


        $this->loader->add_action('admin_init', $plugin_admin, 'options_update');
        $this->loader->add_action( 'transition_post_status', $plugin_admin, 'steempress_sp_post', 15, 3 );
        $this->loader->add_action( 'publish_future_post',$plugin_admin, 'steempress_sp_future_post' );


        $this->loader->add_filter( 'bulk_actions-edit-post', $plugin_admin,'steempress_sp_bulk_update_action' );
        $this->loader->add_filter( 'bulk_actions-edit-post', $plugin_admin,'steempress_sp_bulk_publish_action' );
        $this->loader->add_filter( 'handle_bulk_actions-edit-post', $plugin_admin,'steempress_sp_bulk_publish_handler', 10, 3 );
        $this->loader->add_filter( 'handle_bulk_actions-edit-post', $plugin_admin,'steempress_sp_bulk_update_handler', 10, 3 );
        $this->loader->add_action( 'admin_notices', $plugin_admin,'steempress_sp_bulk_publish_notice' );
        $this->loader->add_action( 'admin_notices', $plugin_admin,'steempress_sp_bulk_update_notice' );


        $this->loader->add_action( 'save_post', $plugin_admin,'saveSteemPublishField',8);
        $this->loader->add_action('add_meta_boxes',$plugin_admin,  'steempress_sp_add_custom_box');


        $this->loader->add_action( 'show_user_profile',  $plugin_admin, 'steempress_sp_extra_user_profile_fields' );
        $this->loader->add_action( 'edit_user_profile',  $plugin_admin, 'steempress_sp_extra_user_profile_fields' );
        $this->loader->add_action( 'personal_options_update',  $plugin_admin, 'steempress_sp_save_extra_user_profile_fields' );
        $this->loader->add_action( 'edit_user_profile_update',  $plugin_admin, 'steempress_sp_save_extra_user_profile_fields' );
    }



	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Steempress_sp_Public( $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
        $this->loader->add_filter('the_content',$plugin_public, 'steempress_sp_comments');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Steempress_sp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}


}
