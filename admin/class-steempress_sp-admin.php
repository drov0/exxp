<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://hive.blog/@howo
 * @since      1.0.0
 *
 * @package    Steempress_sp
 * @subpackage Steempress_sp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version and all the logic for steempress's inner working.
 *
 * @package    Steempress_sp
 * @subpackage Steempress_sp/admin
 */

require('partials/steempress_sp_DOMLettersIterator.php');
require('partials/steempress_sp_DOMWordsIterator.php');
require('partials/steempress_sp_TruncateHTML.php');

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

    public function steempress_sp_save_extra_user_profile_fields( $user_id)
    {
        if ( !current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }
        // All checkboxes inputs
        $valid = array();
        $valid['reward'] = (isset($_POST['steempress_sp']['reward']) && !empty($_POST['steempress_sp']['reward'] ) && ($_POST['steempress_sp']['reward'] == "50" || $_POST['steempress_sp']['reward'] == "100")) ? $_POST['steempress_sp']['reward'] : "50";
        $valid['posting-key'] = (isset($_POST['steempress_sp']['posting-key']) && !empty($_POST['steempress_sp']['posting-key'])) ? htmlspecialchars($_POST['steempress_sp']['posting-key'], ENT_QUOTES) : "";
        $valid['tags'] = (isset($_POST['steempress_sp']['tags']) && !empty($_POST['steempress_sp']['tags'])) ? htmlspecialchars($_POST['steempress_sp']['tags'], ENT_QUOTES) : "";
        $valid['username'] = (isset($_POST['steempress_sp']['username']) && !empty($_POST['steempress_sp']['username'])) ? htmlspecialchars($_POST['steempress_sp']['username'], ENT_QUOTES) : "";
        $valid['footer-display'] = ((isset($_POST['steempress_sp']['footer-display']) && !empty($_POST['steempress_sp']['footer-display'])) && $_POST['steempress_sp']['footer-display'] == 'on') ? 'on' : "off";
        $valid['footer-top'] = ((isset($_POST['steempress_sp']['footer-top']) && !empty($_POST['steempress_sp']['footer-top'])) && $_POST['steempress_sp']['footer-top'] == 'on') ? 'on' : "off";
        $valid['vote'] = ((isset($_POST['steempress_sp']['vote']) && !empty($_POST['steempress_sp']['vote'])) && $_POST['steempress_sp']['vote'] == 'on') ? 'on' : "off";
        $valid['append'] = ((isset($_POST['steempress_sp']['append']) && !empty($_POST['steempress_sp']['append'])) && $_POST['steempress_sp']['append'] == 'on') ? 'on' : "off";
        $valid['delay'] = ((isset($_POST['steempress_sp']['delay']) && !empty($_POST['steempress_sp']['delay']) && is_numeric($_POST['steempress_sp']['delay']) && $_POST['steempress_sp']['delay'] >= 0 && $_POST['steempress_sp']['delay'] <= 87600)) ?  htmlspecialchars($_POST['steempress_sp']['delay'], ENT_QUOTES) : "0";
        $valid['featured'] = ((isset($_POST['steempress_sp']['featured']) && !empty($_POST['steempress_sp']['featured'])) && $_POST['steempress_sp']['featured'] == 'on') ? 'on' : "off";
        $valid['footer'] = (isset($_POST['steempress_sp']['footer']) && !empty($_POST['steempress_sp']['footer'])) ? $_POST['steempress_sp']['footer'] : "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/steempress/'>SteemPress</a> : [%original_link%] </em><hr/></center>";
        $valid['twoway'] = ((isset($_POST['steempress_sp']['twoway']) && !empty($_POST['steempress_sp']['twoway'])) && $_POST['steempress_sp']['twoway'] == 'on') ? 'on' : "off";
        $valid['twoway-front'] = ((isset($_POST['steempress_sp']['twoway-front']) && !empty($_POST['steempress_sp']['twoway-front'])) && $_POST['steempress_sp']['twoway-front'] == 'on') ? 'on' : "off";
        $valid['update'] = ((isset($_POST['steempress_sp']['update']) && !empty($_POST['steempress_sp']['update'])) && $_POST['steempress_sp']['update'] == 'on') ? 'on' : "off";
        $valid['wordlimit'] = ((isset($_POST['steempress_sp']['wordlimit']) && !empty($_POST['steempress_sp']['wordlimit']) && is_numeric($_POST['steempress_sp']['wordlimit']) && $_POST['steempress_sp']['wordlimit'] >= 0)) ?  htmlspecialchars($_POST['steempress_sp']['wordlimit'], ENT_QUOTES) : "0";


        if ($valid['posting-key'] == "posting key set. Enter another one to change it")
        {
            $valid['posting-key'] = get_the_author_meta( $this->plugin_name."posting-key" , $user_id);
        }

        $categories = get_categories(array('hide_empty' => FALSE));

        for ($i = 0; $i < sizeof($categories); $i++)
        {
            $valid['cat'.$categories[$i]->cat_ID] = ((isset($_POST['steempress_sp']['cat'.$categories[$i]->cat_ID]) && !empty($_POST['steempress_sp']['cat'.$categories[$i]->cat_ID])) && $_POST['steempress_sp']['cat'.$categories[$i]->cat_ID] == 'on') ? 'on' : "off";
        }

        foreach ($valid as $key => $value) {
            update_user_meta( $user_id, $this->plugin_name.$key , $value);
        }

    }


    public function validate($input) {

        $options = $this->steempress_sp_get_options();

        // All checkboxes inputs
        $valid = array();
        $valid['reward'] = (isset($input['reward']) && !empty($input['reward'] ) && ($input['reward'] == "50" || $input['reward'] == "100")) ? $input['reward'] : "50";

        $valid['posting-key'] = (isset($input['posting-key']) && !empty($input['posting-key'])) ? htmlspecialchars($input['posting-key'], ENT_QUOTES) : "";
        if ($valid['posting-key'] == "posting key set. Enter another one to change it")
        {
            $valid['posting-key'] = $options['posting-key'];
        }

        $valid['tags'] = (isset($input['tags']) && !empty($input['tags'])) ? htmlspecialchars($input['tags'], ENT_QUOTES) : "";
        $valid['username'] = (isset($input['username']) && !empty($input['username'])) ? htmlspecialchars($input['username'], ENT_QUOTES) : "";
        $valid['verification-code'] = (isset($input['verification-code']) && !empty($input['verification-code'])) ? htmlspecialchars($input['verification-code'], ENT_QUOTES) : "";
        $valid['footer-display'] = ((isset($input['footer-display']) && !empty($input['footer-display'])) && $input['footer-display'] == 'on') ? 'on' : "off";
        $valid['footer-top'] = ((isset($input['footer-top']) && !empty($input['footer-top'])) && $input['footer-top'] == 'on') ? 'on' : "off";

        $valid['vote'] = ((isset($input['vote']) && !empty($input['vote'])) && $input['vote'] == 'on') ? 'on' : "off";
        $valid['append'] = ((isset($input['append']) && !empty($input['append'])) && $input['append'] == 'on') ? 'on' : "off";
        $valid['delay'] = ((isset($input['delay']) && !empty($input['delay']) && is_numeric($input['delay']) && $input['delay'] >= 0 && $input['delay'] <= 87600)) ?  htmlspecialchars($input['delay'], ENT_QUOTES) : "0";
        $valid['featured'] = ((isset($input['featured']) && !empty($input['featured'])) && $input['featured'] == 'on') ? 'on' : "off";
        $valid['footer'] = (isset($input['footer']) && !empty($input['footer'])) ? $input['footer'] : "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/steempress/'>SteemPress</a> : [%original_link%] </em><hr/></center>";
        $valid['twoway'] = ((isset($input['twoway']) && !empty($input['twoway'])) && $input['twoway'] == 'on') ? 'on' : "off";
        $valid['twoway-front'] = ((isset($input['twoway-front']) && !empty($input['twoway-front'])) && $input['twoway-front'] == 'on') ? 'on' : "off";
        $valid['update'] = ((isset($input['update']) && !empty($input['update'])) && $input['update'] == 'on') ? 'on' : "off";
        $valid['wordlimit'] = ((isset($input['wordlimit']) && !empty($input['wordlimit']) && is_numeric($input['wordlimit']) && $input['wordlimit'] >= 0)) ?  htmlspecialchars($input['wordlimit'], ENT_QUOTES) : "0";
        $valid['license-key'] = (isset($input['license-key']) && !empty($input['license-key'])) ? htmlspecialchars($input['license-key'], ENT_QUOTES) : "";

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

        $post = get_post($id);

        $options = $this->steempress_sp_get_options($post);

        $error = [];

        $categories = get_the_category($id);

        for($i = 0; $i < sizeof($categories); $i++)
        {
            if (isset($options['cat'.$categories[$i]->cat_ID]) && $options['cat'.$categories[$i]->cat_ID] == "on") {
                array_push($error, $categories[$i]->name);
            }
        }

        $username = $options["username"];
        $posting_key = $options["posting-key"];

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

        if ($options['footer-display'] == "on")
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


        if ($options['wordlimit'] != "0") {
            $limit = intval($options["wordlimit"]);
            $content = steempressspTruncateHTML::truncateWords($content, $limit, '');
        }

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
            "footer_top" =>$options['footer-top'],
            "error" => json_encode($error),
            "license" => $options['license-key'],
        ));


        // Last minute checks before sending it to the server

        // Post to the api who will publish it on the hive blockchain.
        $result = wp_remote_post(steempress_sp_api_url, $data);
        if (!isset($result->errors)) {
            update_post_meta($id,'steempress_sp_permlink',$result['body']);
            update_post_meta($id,'steempress_sp_author',$username);
        }
    }

    public function steempress_sp_bulk_update_action($bulk_actions) {


        $options = $this->steempress_sp_get_options();

        if (!isset($options["update"]))
            $options["update"] = "on";

        if ($options["update"] == "on")
            $bulk_actions['update_to_steem'] = __('Update to HIVE', 'update_to_steem');

        return $bulk_actions;
    }

    public function steempress_sp_bulk_publish_action($bulk_actions) {
        $bulk_actions['publish_to_steem'] = __( 'Publish to hive', 'publish_to_steem');
        return $bulk_actions;
    }


    public function steempress_sp_bulk_publish_handler( $redirect_to, $doaction, $post_ids ) {
        if ( $doaction !== 'publish_to_steem' ) {
            return $redirect_to;
        }
        for ($i = sizeof($post_ids)-1; $i >= 0; $i--) {
            // Perform action for each post.
            $this->Steempress_sp_publish($post_ids[$i]);
        }
        $redirect_to = add_query_arg('published_to_steem', count( $post_ids ), $redirect_to );
        return $redirect_to;
    }

    public function steempress_sp_bulk_update_handler( $redirect_to, $doaction, $post_ids ) {
        if ( $doaction !== 'update_to_steem' ) {
            return $redirect_to;
        }

        $updated = 0;

        for ($i = sizeof($post_ids)-1; $i >= 0; $i--) {
            // Perform action for each post.
            if ($this->steempress_sp_update($post_ids[$i], true) == 1)
                $updated++;
        }

        if ($updated != count($post_ids))
            $redirect_to = add_query_arg('updated_to_steem_err', $updated, $redirect_to );
        else
            $redirect_to = add_query_arg('updated_to_steem', $updated, $redirect_to );
        return $redirect_to;
    }

    public function steempress_sp_bulk_update_notice() {
        if (!empty( $_REQUEST['updated_to_steem'])) {
            $published_count = intval( $_REQUEST['updated_to_steem'] );
            printf( '<div id="message" class="updated fade">' .
                _n( 'Added %s post to be updated on HIVE. Check your posting queue on <a href="https://steempress.io">https://steempress.io</a> to track the progress.',
                    'Added %s posts to be updated on HIVE. Check your posting queue on <a href="https://steempress.io">https://steempress.io</a> to track the progress.',
                    $published_count,
                    'updated_to_steem'
                ) . '</div>', $published_count );
        }

        if (!empty($_REQUEST['updated_to_steem_err']))
        {
            $published_count = intval( $_REQUEST['updated_to_steem_err'] );
            printf( '<div id="message" class="updated fade">' .
                _n( 'Your post was not updated probably because the metadata was not correctly set. Please edit the article you wanted to update on HIVE and edit the metadata. Then resubmit it.',
                    'Added %s posts to be updated on HIVE. Some were not updated probably because the metadata was not correctly set. Please edit the articles you want to update to HIVE and edit the metadata. Then resubmit them.',
                    $published_count,
                    'updated_to_steem'
                ) . '</div>', $published_count );
        }
    }

    public function steempress_sp_bulk_publish_notice() {
        if (!empty($_REQUEST['published_to_steem'])) {
            $published_count = intval( $_REQUEST['published_to_steem'] );
            printf( '<div id="message" class="updated fade">' .
                _n( 'Added %s post to be published on HIVE. HIVE only allows one article to be published per 5 minutes so it may take a while. Check your posting queue on <a href="https://steempress.io/dashboard">https://steempress.io/dashboard</a>  to track the progress.',
                    'Added %s posts to be published on HIVE. HIVE only allows one article to be published per 5 minutes so it may take a while. check your posting queue on <a href="https://steempress.io/dashboard">https://steempress.io/dashboard</a> to track the progress.',
                    $published_count,
                    'published_to_steem'
                ) . '</div>', $published_count );
        }
    }

    function steempress_sp_future_post( $post_id ) {

        // See if the publish to hive checkbox is checked.
        $value = get_post_meta($post_id, 'Steempress_sp_steem_publish', true);
        if ($value != "0")
            $this->Steempress_sp_publish($post_id);
    }

    public function steempress_sp_post($new_status, $old_status, $post)
    {


        // If post is empty/ doesn't have the hidden_mm attribute this means that we are using gutenberg
        if ($_POST == [] || !isset($_POST['hidden_mm'])) {
            return;
        }

        // New post
        if ($new_status == 'publish' &&  $old_status != 'publish' && $post->post_type == 'post') {
            if (!isset($_POST['Steempress_sp_steem_publish']) && isset($_POST['Steempress_sp_steem_do_not_publish']) )
                return;



            $this->Steempress_sp_publish($post->ID);

            // Edited post
        } else if ($new_status == 'publish' &&  $old_status == 'publish' && $post->post_type == 'post') {
            if (!isset($_POST['Steempress_sp_steem_update']) && isset($_POST['Steempress_sp_steem_do_not_update']) )
                return;
            $this->steempress_sp_update($post->ID, false);
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
            <label><input type="checkbox" value="1" <?php echo $checked; ?> name="Steempress_sp_steem_publish" /> <input type="hidden" name="Steempress_sp_steem_do_not_publish" value="0" />Publish to hive </label>
        </div>
        <?php
    }

    function create_update_checkbox()
    {
        $post_id = get_the_ID();

        if (get_post_type($post_id) != 'post') {
            return;
        }

        if (get_post_status ($post_id) != 'publish')
            return;

        $options = $this->steempress_sp_get_options();

        if (!isset($options["update"]))
            $options["update"] = "on";

        if ($options["update"] != "on")
            return;

        wp_nonce_field('Steempress_sp_custom_update_nonce_'.$post_id, 'Steempress_sp_custom_update_nonce');

        $value = get_post_meta($post_id, 'Steempress_sp_steem_update', true);
        if ($value == "0")
            $checked = "";
        else
            $checked = "checked";

        ?>
        <div class="misc-pub-section misc-pub-section-last">
            <label><input type="checkbox" value="1" <?php echo $checked; ?> name="Steempress_sp_steem_update" /> <input type="hidden" name="Steempress_sp_steem_do_not_update" value="0" />Update to hive </label>
        </div>
        <?php
    }

    function saveSteemPublishField($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || !current_user_can('edit_post', $post_id) || $_POST == [])
            return;

        if (isset($_POST['Steempress_sp_steem_publish']) || isset($_POST['Steempress_sp_steem_do_not_publish']) ) {

            if ($_POST['Steempress_sp_steem_publish'] === '1' ) {

                // If post is empty/ doesn't have the hidden_mm attribute this means that we are using gutenberg
                    if ($_POST == [] || !isset($_POST['hidden_mm'])) {
                        if (get_post_meta($post_id, 'steempress_sp_author', true ) == "" && get_post_status ($post_id) == 'publish')
                            $this->Steempress_sp_publish($post_id);
                    }

                update_post_meta($post_id, 'Steempress_sp_steem_publish', $_POST['Steempress_sp_steem_publish']);
            } else {
                update_post_meta($post_id, 'Steempress_sp_steem_publish', '0');
            }
        }

        if (isset($_POST['Steempress_sp_steem_update'])) {
            if ($_POST['Steempress_sp_steem_update'] === '1') {
                // If post is empty/ doesn't have the hidden_mm attribute this means that we are using gutenberg
                if ($_POST == [] || !isset($_POST['hidden_mm'])) {
                    $this->steempress_sp_update($post_id);
                }

                update_post_meta($post_id, 'Steempress_sp_steem_update', $_POST['Steempress_sp_steem_update']);
            } else {
                update_post_meta($post_id, 'Steempress_sp_steem_update', '0');
            }
        }

        if (array_key_exists('steempress_sp_permlink', $_POST) && array_key_exists('steempress_sp_author', $_POST)) {
            update_post_meta($post_id,'steempress_sp_permlink',$_POST['steempress_sp_permlink']);
            update_post_meta($post_id,'steempress_sp_author',$_POST['steempress_sp_author']);
        }
    }

    public function steempress_sp_custom_box_html($post)
    {

        $author_id = $post->post_author;
        $post_id = get_the_ID();

        // Not published yet.
        if (get_post_status ($post_id) != 'publish')
        {

            $value = get_post_meta($post_id, 'Steempress_sp_steem_publish', true);
            if ($value == "0")
                $checked = "";
            else
                $checked = "checked";


            wp_nonce_field('Steempress_sp_custom_nonce_'.$post_id, 'Steempress_sp_custom_nonce');

            $body  = '<label><input type="checkbox" value="1" '.$checked.' name="Steempress_sp_steem_publish" /> <input type="hidden" name="Steempress_sp_steem_do_not_publish" value="0" />Publish to hive </label>';

            echo $body;

        } else {

            $options = $this->steempress_sp_get_options($post);


            if (!isset($options["update"]))
                $options["update"] = "on";

            if ($options["update"] == "on")
            {
                wp_nonce_field('Steempress_sp_custom_update_nonce_'.$post_id, 'Steempress_sp_custom_update_nonce');

                $value = get_post_meta($post_id, 'Steempress_sp_steem_update', true);
                if ($value == "0")
                    $checked = "";
                else
                    $checked = "checked";

                $body = '<div class="misc-pub-section misc-pub-section-last"><label><input type="checkbox" value="1"  '.$checked.'  name="Steempress_sp_steem_update" /> <input type="hidden" name="Steempress_sp_steem_do_not_update" value="0" />Update to hive </label></div>';
            } else
            {
                $body = "";
            }


            if (!isset($options["username"]))
                $options["username"] = "";


            $author = $options["username"];

            if (isset($options['username' . $author_id]) && $options['username' . $author_id] != "") {
                $author = $options['username' . $author_id];
            }

            $permlink = get_post_meta($post->ID, 'steempress_sp_permlink', true);
            $meta_author = get_post_meta($post->ID, 'steempress_sp_author', true);

            if ($meta_author != $author && $meta_author != "")
                $author = $meta_author;

            $body .= "<p>These options are only for advanced users regarding hive integration</p>
              <label for=\"steempress_sp_author\">Author : </label><br>
              <input type='text' name='steempress_sp_author' value='" . $author . "'/><br>
              <label for=\"steempress_sp_author\">Permlink</label> 
              <input type='text' name='steempress_sp_permlink' value='" . $permlink . "'/><br>
              ";
            // Minified js to handle the "test parameters" function
            $body .= "<script>function steempress_sp_createCORSRequest(){var e=\"" . steempress_sp_twoway_api_back . "/test_param\",t=new XMLHttpRequest;return\"withCredentials\"in t?t.open(\"POST\",e,!0):\"undefined\"!=typeof XDomainRequest?(t=new XDomainRequest).open(\"POST\",e):t=null,t}function steempress_sp_test_params(){document.getElementById(\"steempress_sp_status\").innerHTML=\"loading...\";var e=steempress_sp_createCORSRequest(),s=document.getElementsByName(\"steempress_sp_author\")[0].value,n=document.getElementsByName(\"steempress_sp_permlink\")[0].value,r=\"username=\"+s+\"&permlink=\"+n;e.setRequestHeader(\"Content-type\",\"application/x-www-form-urlencoded\"),e&&(e.username=s,e.permlink=n,e.onload=function(){var t=e.responseText;document.getElementById(\"steempress_sp_status\").innerHTML=\"ok\"===t?\"The parameters are correct. this article is linked to this <a href='https://hive.blog/@\"+this.username+\"/\"+this.permlink+\"'>hive post</a>\":\"Error : the permlink or username is incorrect.\"},e.send(r))}</script>";

            $body .= "<button type=\"button\" onclick='steempress_sp_test_params()'>Test parameters</button><br/><p id='steempress_sp_status'></p>";


            echo $body;
        }
    }

    public function steempress_sp_add_meta_tag()
    {
        $options = $this->steempress_sp_get_options();

        if ($options['verification-code'] !== "")
        {
            echo '<meta name="steempress_sp_verification" content="'.htmlspecialchars($options['verification-code'], ENT_QUOTES).'" />';
        }
    }

    function steempress_sp_add_custom_box()
    {
        $post_id = get_the_ID();

        if (get_post_type($post_id) != 'post') {
            return;
        }

        add_meta_box(
            'steempress_sp_box_id',
            'SteemPress options',
            array($this,'steempress_sp_custom_box_html'),
            'post',
            'side'
        );
    }


    function steempress_sp_get_options($post = null) {
        if ($post != null)
            $author_id = $post->post_author;
        else
            $author_id = get_current_user_id();

        $options = get_option($this->plugin_name);

        // avoid undefined errors when running it for the first time :
        if (!isset($options["username"]))
            $options["username"] = "";
        if (!isset($options["posting-key"]))
            $options["posting-key"] = "";
        if (!isset($options["reward"]))
            $options["reward"] = "50";
        if (!isset($options["tags"]))
            $options["tags"] = "";
        if (!isset($options["footer-display"]))
            $options["footer-display"] = "on";
        if (!isset($options["footer-top"]))
            $options["footer-top"] = "off";
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
        if (!isset($options["twoway"]))
            $options["twoway"] = "off";
        if (!isset($options["update"]))
            $options["update"] = "on";
        if (!isset($options["twoway-front"]))
            $options["twoway-front"] = "off";
        if (!isset($options["wordlimit"]))
            $options["wordlimit"] = "0";
        if (!isset($options["license-key"]))
            $options["license-key"] = "";
        if (!isset($options["verification-code"]))
            $options["verification-code"] = "";

        $categories = get_categories(array('hide_empty' => FALSE));

        for ($i = 0; $i < sizeof($categories); $i++)
        {
            if (!isset($options['cat'.$categories[$i]->cat_ID]))
                $options['cat'.$categories[$i]->cat_ID] = "off";
        }


        if (get_the_author_meta( $this->plugin_name."username" , $author_id) != "" && get_the_author_meta( $this->plugin_name."posting-key" , $author_id) != "") {
            // avoid undefined errors when running it for the first time :
            if (get_the_author_meta($this->plugin_name . "username", $author_id) == "")
                $options["username"] = "";
            else
                $options["username"] = get_the_author_meta($this->plugin_name . "username", $author_id);

            if (get_the_author_meta($this->plugin_name . "posting-key", $author_id) == "")
                $options["posting-key"] = "";
            else
                $options["posting-key"] = get_the_author_meta($this->plugin_name . "posting-key", $author_id);

            if (get_the_author_meta($this->plugin_name . "reward", $author_id) == "")
                $options["reward"] = "50";
            else
                $options["reward"] = get_the_author_meta($this->plugin_name . "reward", $author_id);

            if (get_the_author_meta($this->plugin_name . "tags", $author_id) == "")
                $options["tags"] = "";
            else
                $options["tags"] = get_the_author_meta($this->plugin_name . "tags", $author_id);

            if (get_the_author_meta($this->plugin_name . "footer-display", $author_id) == "")
                $options["footer-display"] = "on";
            else
                $options["footer-display"] = get_the_author_meta($this->plugin_name . "footer-display", $author_id);

            if (get_the_author_meta($this->plugin_name . "footer-top", $author_id) == "")
                $options["footer-top"] = "off";
            else
                $options["footer-top"] = get_the_author_meta($this->plugin_name . "footer-top", $author_id);


            if (get_the_author_meta($this->plugin_name . "vote", $author_id) == "")
                $options["vote"] = "on";
            else
                $options["vote"] = get_the_author_meta($this->plugin_name . "vote", $author_id);


            if (get_the_author_meta($this->plugin_name . "append", $author_id) == "")
                $options["append"] = "off";
            else
                $options["append"] = get_the_author_meta($this->plugin_name . "append", $author_id);


            if (get_the_author_meta($this->plugin_name . "delay", $author_id) == "")
                $options["delay"] = "0";
            else
                $options["delay"] = get_the_author_meta($this->plugin_name . "delay", $author_id);


            if (get_the_author_meta($this->plugin_name . "featured", $author_id) == "")
                $options["featured"] = "on";
            else
                $options["featured"] = get_the_author_meta($this->plugin_name . "featured", $author_id);


            if (get_the_author_meta($this->plugin_name . "footer", $author_id) == "")
                $options["footer"] = "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/steempress/'>SteemPress</a> : [%original_link%] </em><hr/></center>";
            else
                $options["footer"] = get_the_author_meta($this->plugin_name . "footer", $author_id);


            if (get_the_author_meta($this->plugin_name . "update", $author_id) == "")
                $options["update"] = "on";
            else
                $options["update"] = get_the_author_meta($this->plugin_name . "update", $author_id);

            if (get_the_author_meta($this->plugin_name . "wordlimit", $author_id) == "")
                $options["wordlimit"] = "0";
            else
                $options["wordlimit"] = get_the_author_meta($this->plugin_name . "wordlimit", $author_id);

            $categories = get_categories(array('hide_empty' => FALSE));

            for ($i = 0; $i < sizeof($categories); $i++) {
                if (get_the_author_meta($this->plugin_name . 'cat' . $categories[$i]->cat_ID, $author_id) == "")
                    $options['cat' . $categories[$i]->cat_ID] = "off";
                else
                    $options['cat' . $categories[$i]->cat_ID] = get_the_author_meta($this->plugin_name . 'cat' . $categories[$i]->cat_ID, $author_id);
            }
        }



        return $options;
    }

    /* Returned codes :
    1 : ok
    -1 : metadata is incorrect
    -2 : update is not activated
    -3 : Post is not in the published state
    */
    function steempress_sp_update($post_id, $bulk = false)
    {
        $post = get_post($post_id);
        if ($post->post_status == "publish") {

            if (!isset($_POST['Steempress_sp_steem_update']) && isset($_POST['Steempress_sp_steem_do_not_update']) )
                return;

            $options = $this->steempress_sp_get_options($post);

            if ($options["update"] == "on" || $bulk) {
                $username = $options["username"];
                $posting_key = $options["posting-key"];

                $wp_tags = wp_get_post_tags($post_id);

                if (sizeof($wp_tags) != 0) {

                    $tags = array();

                    foreach ($wp_tags as $tag) {
                        $tags[] = str_replace(" ", "", $tag->name);
                    }

                    $tags = implode(" ", $tags);

                    if ($options["append"] == "on")
                        $tags = $options["tags"] . " " . $tags;
                } else
                    $tags = $options["tags"];
                $link = get_permalink($post->ID);

                if ($options['footer-display'] == "on")
                    $display_backlink = "true";
                else
                    $display_backlink = "false";

                $content = $post->post_content;
                if ($options["featured"] == "on") {
                    $thumbnail = wp_get_attachment_url(get_post_thumbnail_id($post_id), 'thumbnail');
                    if ($thumbnail != "0")
                        $content = "<center>" . $thumbnail . "</center> <br/>" . $post->post_content;
                }

                $version = steempress_sp_compte;

                $pos = strrpos(steempress_sp_compte, ".");

                if ($pos !== false)
                    $version = substr_replace(steempress_sp_compte, "", $pos, strlen("."));

                $version = ((float)$version) * 100;

                $permlink = get_post_meta($post_id, "steempress_sp_permlink");

                if ($options['wordlimit'] != "0") {
                    $limit = intval($options["wordlimit"]);
                    $content = steempressspTruncateHTML::truncateWords($content, $limit, '');
                }

                $data = array("body" => array(
                    "title" => $post->post_title,
                    "content" => $content,
                    "tags" => $tags,
                    "author" => $username,
                    "wif" => $posting_key,
                    "original_link" => $link,
                    "wordpress_id" => $post_id,
                    "display_backlink" => $display_backlink,
                    "footerTop" => $options['footer-top'],
                    "version" => $version,
                    "footer" => $options['footer'],
                    "permlink" => $permlink[0],
                    "vote"=> $options["vote"],
                    "reward" => $options['reward'],
                ));

                    // Post to the api who will update it on the hive blockchain.
                    $result = wp_remote_post(steempress_sp_api_url . "/update", $data);
                    if (!isset($result->errors)) {
                        $data = $result['body'];
                        if ($data == "ok")
                            return 1;
                        else
                            return -1;
                    }
            } else
                return -2;
        } else
            return -3;
    }


    // Small func to easily test post data when debugging.
    function steempress_sp_test_post($data = "no data")
    {
        $data = array("body" => array(
            "data_test" => json_encode($data)
        ));
        wp_remote_post(steempress_sp_api_url . "/dev", $data);
    }

    function steempress_sp_extra_user_profile_fields( $user )
    {
        include_once('partials/steempress_sp-user-display.php');
    }


}
