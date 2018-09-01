<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://steemit.com/@howo
 * @since      1.0.0
 *
 * @package    Steempress_sp
 * @subpackage Steempress_sp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Steempress_sp
 * @subpackage Steempress_sp/public
 */
class Steempress_sp_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/steempress_sp-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name."steem", plugin_dir_url( __FILE__ ) . 'js/steem.min.js');
		wp_enqueue_script( $this->plugin_name."public_js", plugin_dir_url( __FILE__ ) . 'js/steempress_sp-public.js', array( 'jquery' ), $this->version, false );

	}


	public function steempress_sp_comments($content)
    {

        $options = get_option($this->plugin_name);

        if (!isset($options["twoway"]))
            $options["twoway"] = "off";

        if ($options['twoway'] == "on") {
            $id = get_the_ID();

            $username = get_post_meta($id, "steempress_sp_username");
            $permlink = get_post_meta($id, "steempress_sp_permlink");
            $tag = get_post_meta($id, "steempress_sp_tag");

            $payout = "";
            $data = "";
            $comments = "";
            $front_page = "";

            if (sizeof($username) == 1 and sizeof($permlink) == 1 and sizeof($tag) == 1) {

                $username = $username[0];
                $permlink = $permlink[0];
                $tag = $tag[0];

                $data =  "<div name=\"steempress_sp_username\" style=\"display: none;\">" . $username . "</div>";
                $data .= "<div name=\"steempress_sp_permlink\" style=\"display: none;\">" . $permlink . "</div>";
                $data .= "<div name=\"steempress_sp_tag\" style=\"display: none;\">" . $tag . "</div>";


                $payout = "<div name='steempress_sp_price'>0.000$</div> <a href='#'>Upvote</a> ";
                $sign_in = "<a style=\"cursor:pointer\" onclick=\" window.open('http://localhost:8002/sc','',' scrollbars=yes,menubar=no,width=500,height=600, resizable=yes,toolbar=no,location=no,status=no')\">Sign in</a>

";
                if (!is_front_page())
                $comments = "<br/><div name='steempress_sp_comments'></div>";

            }
            return $content . $data . $payout .$sign_in. $comments.$front_page;
        } else
            return $content;

        }


}
