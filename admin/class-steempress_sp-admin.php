<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://steemit.com/@howo
 * @since      1.0.0
 *
 * @package    Steempress_sp
 * @subpackage Steempress_sp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Steempress_sp
 * @subpackage Steempress_sp/admin
 */
class Steempress_sp_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Steempress_sp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Steempress_sp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/steempress_sp-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Steempress_sp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Steempress_sp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/steempress_sp-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */

    public function add_plugin_admin_menu() {
        add_options_page( 'SteemPress Options', 'SteemPress', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
        );
    }

    /**
     * Add settings action link to the plugins page.
     *
     * @since    1.0.0
     */

    public function add_action_links( $links ) {
        /*
        *  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
        */
        $settings_link = array(
            '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
        );
        return array_merge(  $settings_link, $links );

    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */

    public function display_plugin_setup_page() {
        include_once('partials/steempress_sp-admin-display.php');
    }

    public function validate($input) {
        // All checkboxes inputs
        $valid = array();
        $valid['reward'] = (isset($input['reward']) && !empty($input['reward'] ) && ($input['reward'] == "50" || $input['reward'] == "100")) ? $input['reward'] : "50";
        $valid['posting-key'] = (isset($input['posting-key']) && !empty($input['posting-key'])) ? htmlspecialchars($input['posting-key'], ENT_QUOTES) : "";
        $valid['tags'] = (isset($input['tags']) && !empty($input['tags'])) ? htmlspecialchars($input['tags'], ENT_QUOTES) : "";
        $valid['username'] = (isset($input['username']) && !empty($input['username'])) ? htmlspecialchars($input['username'], ENT_QUOTES) : "";
        $valid['seo'] = ((isset($input['seo']) && !empty($input['seo'])) && $input['seo'] == 'on') ? 'on' : "off";
        $valid['vote'] = ((isset($input['vote']) && !empty($input['vote'])) && $input['vote'] == 'on') ? 'on' : "off";


        return $valid;
    }

    public function options_update() {
        register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
    }


    public function Steempress_sp_post($new_status, $old_status, $post)
    {
        if ('publish' === $new_status && 'publish' !== $old_status && $post->post_type === 'post') {
            $options = get_option($this->plugin_name);

            // Avoid undefined errors
            if (!isset($options["username"]))
                $options["username"] = "";
            if (!isset($options["posting-key"]))
                $options["posting-key"] = "";
            if (!isset($options["reward"]))
                $options["reward"] = "100";
            if (!isset($options["tags"]))
                $options["tags"] = "";
            if (!isset($options["tags"]))
                $options["tags"] = "";
            if (!isset($options["tags"]))
                $options["tags"] = "";
            if (!isset($options["seo"]))
                $options["seo"] = "on";
            if (!isset($options["vote"]))
                $options["vote"] = "on";

            $wp_tags = wp_get_post_tags($post->ID);

            if (sizeof($wp_tags) != 0) {

                $tags = [];

                foreach ($wp_tags as $tag) {
                    $tags[] = $tag->name;
                }

                $tags = implode(" ", $tags);
            }
            else
                $tags = $options["tags"];

            if ($options['seo'] == "on")
                $link = get_permalink($post->ID);
            else
                $link = "";

            $data = array("body" => array("title" => $post->post_title, "content" => $post->post_content, "tags" => $tags, "author" => $options["username"], "wif" => $options["posting-key"], "original_link" => $link, "reward" => $options['reward']));

            // A few local verifications as to not overload the server with useless txs

            $test = $data['body'];

            // Last minute checks before sending it to the server
            if ($test['tags'] != "" && $test['username'] != "" && $test['posting-key'] != "") {
                // Post to the api who will publish it on the steem blockchain.
                wp_remote_post("https://steemgifts.com", $data);
            }
        }

        return;
    }



}
