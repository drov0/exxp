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

	private $api_url;
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
		$this->api_url = "https://api.steempress.io";
		//$this->api_url = "http://localhost:8001";
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
        $valid['append'] = ((isset($input['append']) && !empty($input['append'])) && $input['append'] == 'on') ? 'on' : "off";
        $valid['delay'] = ((isset($input['delay']) && !empty($input['delay']) && is_numeric($input['delay']) && $input['delay'] >= 0)) ?  htmlspecialchars($input['delay'], ENT_QUOTES) : "0";
        $valid['featured'] = ((isset($input['featured']) && !empty($input['featured'])) && $input['featured'] == 'on') ? 'on' : "off";
        $valid['footer'] = (isset($input['footer']) && !empty($input['footer'])) ? $input['footer'] : "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/steempress/'>SteemPress</a> : [%original_link%] </em><hr/></center>";
        $valid['update'] = ((isset($input['update']) && !empty($input['update'])) && $input['update'] == 'on') ? 'on' : "off";

        $users = get_users();

        for ($i = 0; $i < sizeof($users); $i++)
        {
            $valid['posting-key'.$users[$i]->data->ID] = (isset($input['posting-key'.$users[$i]->data->ID]) && !empty($input['posting-key'.$users[$i]->data->ID])) ? htmlspecialchars($input['posting-key'.$users[$i]->data->ID], ENT_QUOTES) : "";
            $valid['username'.$users[$i]->data->ID] = (isset($input['username'.$users[$i]->data->ID]) && !empty($input['username'.$users[$i]->data->ID])) ? htmlspecialchars($input['username'.$users[$i]->data->ID], ENT_QUOTES) : "";
        }

        $categories = get_categories(array('hide_empty' => FALSE));

        for ($i = 0; $i < sizeof($categories); $i++)
        {
            $valid['cat'.$categories[$i]->cat_ID] = ((isset($input['cat'.$categories[$i]->cat_ID]) && !empty($input['cat'.$categories[$i]->cat_ID])) && $input['cat'.$categories[$i]->cat_ID] == 'on') ? 'on' : "off";
        }


        return $valid;
    }

    public function options_update() {
        register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
    }





    public function Steempress_sp_publish($id)
    {

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
        if (!isset($options["seo"]))
            $options["seo"] = "on";
        if (!isset($options["vote"]))
            $options["vote"] = "on";
        if (!isset($options["append"]))
            $options["append"] = "off";
        if (!isset($options["delay"]))
            $options["delay"] = "0";
        if (!isset($options["featured"]))
            $options["featured"] = "on";
        if (!isset($options["footer"]))
            $options["footer"] = "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/steempress/'>SteemPress</a> : [%original_link%] </em><hr/></center>";
        
        $post = get_post($id);


        $categories = get_the_category($id);

        for($i = 0; $i < sizeof($categories); $i++)
        {
            if (isset($options['cat'.$categories[$i]->cat_ID]) && $options['cat'.$categories[$i]->cat_ID] == "on")
                return;
        }


        $author_id = $post->post_author;

        $username = $options["username"];
        $posting_key = $options["posting-key"];

        if (isset($options['username'.$author_id]) && $options['username'.$author_id] != "" && isset($options['posting-key'.$author_id]) && $options['posting-key'.$author_id] != "")
        {
            $username = $options['username'.$author_id];
            $posting_key = $options['posting-key'.$author_id];
        }

        $wp_tags = wp_get_post_tags($id);

        if (sizeof($wp_tags) != 0) {



            $tags = array();

            foreach ($wp_tags as $tag) {
                $tags[] = str_replace(" ", "", $tag->name);
            }

            $tags = implode(" ", $tags);

            if ($options["append"] == "on")
                $tags = $options["tags"]." ".$tags;
        }
        else
            $tags = $options["tags"];
        $link = get_permalink($post->ID);

        if ($options['seo'] == "on")
            $display_backlink = "true";
        else
            $display_backlink = "false";

        $content = $post->post_content;
        if ($options["featured"] == "on") {
            $thumbnail = wp_get_attachment_url(get_post_thumbnail_id($id), 'thumbnail');
            if ($thumbnail != "0")
                $content = "<center>" . $thumbnail . "</center> <br/>" . $post->post_content;
        }

        $domain = get_site_url();

        $version = steempress_sp_compte;

        $pos = strrpos(steempress_sp_compte, ".");

        if($pos !== false)
            $version = substr_replace(steempress_sp_compte, "", $pos, strlen("."));

        $version = ((float)$version)*100;



        $data = array("body" => array(
                "title" => $post->post_title,
                "content" => $content,
                "tags" => $tags,
                "author" => $username,
                "wif" => $posting_key,
                "original_link" => $link,
                "reward" => $options['reward'],
                "vote"=> $options["vote"],
                "delay"=> $options["delay"],
                "wordpress_id"=> $id,
                "domain"=> $domain,
                "display_backlink" => $display_backlink,
                "version" =>  $version,
                "footer" =>$options['footer'],
        ));

        // A few local verifications as to not overload the server with useless txs

        $test = $data['body'];
        // Last minute checks before sending it to the server
        if ($test['tags'] != "" && $test['author'] != "" && $test['wif'] != "") {
            // Post to the api who will publish it on the steem blockchain.
            wp_remote_post($this->api_url, $data);
        }
    }

    public function custom_bulk_actions($bulk_actions) {
        $bulk_actions['publish_to_steem'] = __( 'Publish to STEEM', 'publish_to_steem');
        return $bulk_actions;
    }

    public function custom_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
        if ( $doaction !== 'publish_to_steem' ) {
            return $redirect_to;
        }
        for ($i = sizeof($post_ids)-1; $i >= 0; $i--) {
            // Perform action for each post.
            $this->Steempress_sp_publish($post_ids[$i]);
        }
        $redirect_to = add_query_arg( 'published_to_steem', count( $post_ids ), $redirect_to );
        return $redirect_to;
    }

    public function custom_bulk_action_admin_notice() {
        if ( ! empty( $_REQUEST['published_to_steem'] ) ) {
            $published_count = intval( $_REQUEST['published_to_steem'] );
            printf( '<div id="message" class="updated fade">' .
                _n( 'Added %s post to be published on STEEM. STEEM only allows one article to be published per 5 minutes so it may take a while.',
                    'Added %s posts to be published on STEEM. STEEM only allows one article to be published per 5 minutes so it may take a while.',
                    $published_count,
                    'published_to_steem'
                ) . '</div>', $published_count );
        }
    }


    public function Steempress_sp_post($new_status, $old_status, $post)
    {
        if ('publish' === $new_status && 'publish' !== $old_status && $post->post_type === 'post') {
            if (!isset($_POST['Steempress_sp_steem_publish']) && isset($_POST['Steempress_sp_steem_do_not_publish']) ) {
                return;
            } else {
                $value = get_post_meta($post->ID, 'Steempress_sp_steem_publish', true);
                if ($value != "0")
                    $this->Steempress_sp_publish($post->ID);
            }
        }

        return;
    }

    function createSteemPublishField()
    {
        $post_id = get_the_ID();

        if (get_post_type($post_id) != 'post') {
            return;
        }

        if (get_post_status ($post_id) == 'publish')
            return;

        wp_nonce_field('Steempress_sp_custom_nonce_'.$post_id, 'Steempress_sp_custom_nonce');

        $value = get_post_meta($post_id, 'Steempress_sp_steem_publish', true);
        if ($value == "0")
            $checked = "";
        else
            $checked = "checked";

        ?>
        <div class="misc-pub-section misc-pub-section-last">
            <label><input type="checkbox" value="1" <?php echo $checked; ?> name="Steempress_sp_steem_publish" /> <input type="hidden" name="Steempress_sp_steem_do_not_publish" value="0" />Publish to steem </label>
        </div>
        <?php
    }

    function saveSteemPublishField($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (
            !isset($_POST['Steempress_sp_custom_nonce']) ||
            !wp_verify_nonce($_POST['Steempress_sp_custom_nonce'], 'Steempress_sp_custom_nonce_'.$post_id)
        ) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        if (isset($_POST['Steempress_sp_steem_publish'])) {
            update_post_meta($post_id, 'Steempress_sp_steem_publish', $_POST['Steempress_sp_steem_publish']);
        } else {
            update_post_meta($post_id, 'Steempress_sp_steem_publish', '0');
        }
    }

}
