<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://steemit.com/@howo
 * @since      1.0.0
 *
 * @package    Sp
 * @subpackage Sp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sp
 * @subpackage Sp/admin
 */
class Sp_Admin {

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
		 * defined in Sp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sp-admin.css', array(), $this->version, 'all' );

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
		 * defined in Sp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Sp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sp-admin.js', array( 'jquery' ), $this->version, false );

	}

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */

    public function add_plugin_admin_menu() {

        /*
         * Add a settings page for this plugin to the Settings menu.
         *
         * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
         *
         *        Administration Menus: http://codex.wordpress.org/Administration_Menus
         *
         */
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
        include_once('partials/sp-admin-display.php');
    }

    public function validate($input) {
        // All checkboxes inputs
        $valid = array();
        $valid['reward'] = (isset($input['reward']) && !empty($input['reward'] ) && ($input['reward'] == "50" || $input['reward'] == "100")) ? $input['reward'] : "50";
        $valid['posting-key'] = (isset($input['posting-key']) && !empty($input['posting-key'])) ? htmlspecialchars($input['posting-key'], ENT_QUOTES) : "";
        $tags = (isset($input['tags']) && !empty($input['tags'])) ? htmlspecialchars($input['tags'], ENT_QUOTES) : "";
        $valid['username'] = (isset($input['username']) && !empty($input['username'])) ? htmlspecialchars($input['username'], ENT_QUOTES) : "";

        //if ()

        $valid['tags'] = $tags;

        return $valid;
    }

    public function options_update() {
        register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
    }


    public function sp_post($new_status, $old_status, $post) {
        if('publish' === $new_status && 'publish' !== $old_status && $post->post_type === 'post') {
            //$converter = new HtmlConverter();
            $options = get_option($this->plugin_name);
            $data = array("body"=>array("title"=>$post->post_title, "content"=>$post->post_content, "tags"=>$options["tags"], "author"=>$options["username"], "wif"=>$options["posting-key"]));

            wp_remote_post("http://steemtutorial.com:81/", $data);

        }

        return;
    }




}
