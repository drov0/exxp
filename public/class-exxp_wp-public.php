<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://hive.blog/@howo
 * @since      1.0.0
 *
 * @package    Exxp_wp
 * @subpackage Exxp_wp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Exxp_wp
 * @subpackage Exxp_wp/public
 */
class Exxp_wp_Public {

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
		 * defined in Exxp_wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Exxp_wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/exxp_wp-public.css', array(), $this->version, 'all' );

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
		 * defined in Exxp_wp_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Exxp_wp_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name."iframeResizer", plugin_dir_url( __FILE__ ) . 'js/exxp_wp-iframeResizer.min.js');
		wp_enqueue_script( $this->plugin_name."public_js", plugin_dir_url( __FILE__ ) . 'js/exxp_wp-public.js', array( 'jquery' ), $this->version, false );

	}


	public function exxp_wp_comments($content)
    {
        $options = get_option($this->plugin_name);

        if (!isset($options["twoway"]))
            $options["twoway"] = "off";
        if (!isset($options["twoway-front"]))
            $options["twoway-front"] = "off";

        if ($options['twoway'] == "on") {
            $id = get_the_ID();
            
            $post = get_post($id);

            $permlink = get_post_meta($id, "steempress_sp_permlink", true);

            $author_id = $post->post_author;

            if (!isset($options["username"]))
                $options["username"] = "";


            $author = $options["username"];


            if (isset($options['username' . $author_id]) && $options['username' . $author_id] != "") {
                $author = $options['username' . $author_id];
            }

            $meta_author = get_post_meta($post->ID, 'steempress_sp_author', true);

            if ($meta_author != $author && $meta_author != "")
                $author = $meta_author;

            $exxp = "";

            $link = get_permalink($post->ID);

            if ($permlink != "" && $author != "") {
                // If it's the front page, we display a smaller iframe.
               $exxp = "<div id='exxp_wp_comment_feed'>";
                if (is_front_page())
                    $exxp .= "<iframe name='exxp_wp_embed'  onload=\"iFrameResize({ heightCalculationMethod:'min'})\" src=\"".exxp_wp_twoway_api_url."/?author=".$author."&permlink=".$permlink."&display_comment=false&parent=".$link."\" style=\"border: 0; width: 100%;margin-bottom: 0px !important;\"></iframe>";
                else
                    $exxp .= "<iframe name='exxp_wp_embed'  onload=\"iFrameResize({ scrolling:true, heightCalculationMethod:'min'})\" src=\"".exxp_wp_twoway_api_url."/?author=".$author."&permlink=".$permlink."&display_comment=true&parent=".$link."\" style=\"border: 0; width: 100%; margin-bottom: 0px !important;\"></iframe>";

                $exxp .= "</div>";
            }

            if ($options["twoway-front"] === "off" && is_front_page())
                return $content;

            return $content .  $exxp;
        } else
            return $content;

        }


}
