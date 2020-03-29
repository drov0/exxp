<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://hive.blog/@howo
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

		wp_enqueue_script( $this->plugin_name."iframeResizer", plugin_dir_url( __FILE__ ) . 'js/iframeResizer.min.js');
		wp_enqueue_script( $this->plugin_name."public_js", plugin_dir_url( __FILE__ ) . 'js/steempress_sp-public.js', array( 'jquery' ), $this->version, false );

	}


	public function steempress_sp_comments($content)
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

            $steempress = "";

            $link = get_permalink($post->ID);

            if ($permlink != "" && $author != "") {
                // If it's the front page, we display a smaller iframe.
               $steempress = "<div id='steempress_sp_comment_feed'>";
                if (is_front_page())
                    $steempress .= "<iframe name='steempress_sp_embed'  onload=\"iFrameResize({ heightCalculationMethod:'min'})\" src=\"".steempress_sp_twoway_api_url."/?author=".$author."&permlink=".$permlink."&display_comment=false&parent=".$link."\" style=\"border: 0; width: 100%;margin-bottom: 0px !important;\"></iframe>";
                else
                    $steempress .= "<iframe name='steempress_sp_embed'  onload=\"iFrameResize({ scrolling:true, heightCalculationMethod:'min'})\" src=\"".steempress_sp_twoway_api_url."/?author=".$author."&permlink=".$permlink."&display_comment=true&parent=".$link."\" style=\"border: 0; width: 100%; margin-bottom: 0px !important;\"></iframe>";

                $steempress .= "</div>";
            }

            if ($options["twoway-front"] === "off" && is_front_page())
                return $content;

            return $content .  $steempress;
        } else
            return $content;

        }


}
