<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://hive.blog/@howo
 * @since      1.0.0
 *
 * @package    Exxp_wp
 * @subpackage Exxp_wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version and all the logic for exxp's inner working.
 *
 * @package    Exxp_wp
 * @subpackage Exxp_wp/admin
 */

require('partials/exxp_wp_DOMLettersIterator.php');
require('partials/exxp_wp_DOMWordsIterator.php');
require('partials/exxp_wp_TruncateHTML.php');

class Exxp_wp_Admin {

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
         * defined in Exxp_wp_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Exxp_wp_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/exxp_wp-admin.css', array(), $this->version, 'all' );

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
         * defined in Exxp_wp_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Exxp_wp_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/exxp_wp-admin.js', array( 'jquery' ), $this->version, false );

    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     *
     * @since    1.0.0
     */

    public function add_plugin_admin_menu() {
        add_options_page( 'Exxp Options', 'Exxp', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
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
        include_once('partials/exxp_wp-admin-display.php');
    }

    public function exxp_wp_save_extra_user_profile_fields( $user_id)
    {
        if ( !current_user_can( 'edit_user', $user_id ) ) {
            return false;
        }
        // All checkboxes inputs
        $valid = array();
        $valid['reward'] = (isset($_POST['exxp_wp']['reward']) && !empty($_POST['exxp_wp']['reward'] ) && ($_POST['exxp_wp']['reward'] == "50" || $_POST['exxp_wp']['reward'] == "100")) ? $_POST['exxp_wp']['reward'] : "50";
        $valid['posting-key'] = (isset($_POST['exxp_wp']['posting-key']) && !empty($_POST['exxp_wp']['posting-key'])) ? sanitize_text_field($_POST['exxp_wp']['posting-key']) : "";
        $valid['tags'] = (isset($_POST['exxp_wp']['tags']) && !empty($_POST['exxp_wp']['tags'])) ? sanitize_text_field($_POST['exxp_wp']['tags']) : "";
        $valid['username'] = (isset($_POST['exxp_wp']['username']) && !empty($_POST['exxp_wp']['username'])) ? sanitize_user($_POST['exxp_wp']['username']) : "";
        $valid['footer-display'] = ((isset($_POST['exxp_wp']['footer-display']) && !empty($_POST['exxp_wp']['footer-display'])) && $_POST['exxp_wp']['footer-display'] == 'on') ? 'on' : "off";
        $valid['footer-top'] = ((isset($_POST['exxp_wp']['footer-top']) && !empty($_POST['exxp_wp']['footer-top'])) && $_POST['exxp_wp']['footer-top'] == 'on') ? 'on' : "off";
        $valid['vote'] = ((isset($_POST['exxp_wp']['vote']) && !empty($_POST['exxp_wp']['vote'])) && $_POST['exxp_wp']['vote'] == 'on') ? 'on' : "off";
        $valid['append'] = ((isset($_POST['exxp_wp']['append']) && !empty($_POST['exxp_wp']['append'])) && $_POST['exxp_wp']['append'] == 'on') ? 'on' : "off";
        $valid['delay'] = ((isset($_POST['exxp_wp']['delay']) && !empty($_POST['exxp_wp']['delay']) && is_numeric($_POST['exxp_wp']['delay']) && $_POST['exxp_wp']['delay'] >= 0 && $_POST['exxp_wp']['delay'] <= 87600)) ?  (int) $_POST['exxp_wp']['delay'] : "0";
        $valid['featured'] = ((isset($_POST['exxp_wp']['featured']) && !empty($_POST['exxp_wp']['featured'])) && $_POST['exxp_wp']['featured'] == 'on') ? 'on' : "off";
        $valid['footer'] = (isset($_POST['exxp_wp']['footer']) && !empty($_POST['exxp_wp']['footer'])) ? $_POST['exxp_wp']['footer'] : "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/exxp/'>Exxp</a> : [%original_link%] </em><hr/></center>";
        $valid['twoway'] = ((isset($_POST['exxp_wp']['twoway']) && !empty($_POST['exxp_wp']['twoway'])) && $_POST['exxp_wp']['twoway'] == 'on') ? 'on' : "off";
        $valid['twoway-front'] = ((isset($_POST['exxp_wp']['twoway-front']) && !empty($_POST['exxp_wp']['twoway-front'])) && $_POST['exxp_wp']['twoway-front'] == 'on') ? 'on' : "off";
        $valid['update'] = ((isset($_POST['exxp_wp']['update']) && !empty($_POST['exxp_wp']['update'])) && $_POST['exxp_wp']['update'] == 'on') ? 'on' : "off";
        $valid['wordlimit'] = ((isset($_POST['exxp_wp']['wordlimit']) && !empty($_POST['exxp_wp']['wordlimit']) && is_numeric($_POST['exxp_wp']['wordlimit']) && $_POST['exxp_wp']['wordlimit'] >= 0)) ? (int) $_POST['exxp_wp']['wordlimit'] : "0";


        if ($valid['posting-key'] == "posting key set. Enter another one to change it")
        {
            $valid['posting-key'] = get_the_author_meta( $this->plugin_name."posting-key" , $user_id);
        }

        $categories = get_categories(array('hide_empty' => FALSE));

        for ($i = 0; $i < sizeof($categories); $i++)
        {
            $valid['cat'.$categories[$i]->cat_ID] = ((isset($_POST['exxp_wp']['cat'.$categories[$i]->cat_ID]) && !empty($_POST['exxp_wp']['cat'.$categories[$i]->cat_ID])) && $_POST['exxp_wp']['cat'.$categories[$i]->cat_ID] == 'on') ? 'on' : "off";
        }

        foreach ($valid as $key => $value) {
            update_user_meta( $user_id, $this->plugin_name.$key , $value);
        }

    }


    public function validate($input) {

        $options = $this->exxp_wp_get_options();

        // All checkboxes inputs
        $valid = array();
        $valid['reward'] = (isset($input['reward']) && !empty($input['reward'] ) && ($input['reward'] == "50" || $input['reward'] == "100")) ? $input['reward'] : "50";

        $valid['posting-key'] = (isset($input['posting-key']) && !empty($input['posting-key'])) ? sanitize_text_field($input['posting-key']) : "";
        if ($valid['posting-key'] == "posting key set. Enter another one to change it")
        {
            $valid['posting-key'] = $options['posting-key'];
        }

        $valid['tags'] = (isset($input['tags']) && !empty($input['tags'])) ? sanitize_text_field($input['tags']) : "";
        $valid['username'] = (isset($input['username']) && !empty($input['username'])) ? sanitize_user($input['username']) : "";
        $valid['verification-code'] = (isset($input['verification-code']) && !empty($input['verification-code'])) ? sanitize_text_field($input['verification-code']) : "";
        $valid['footer-display'] = ((isset($input['footer-display']) && !empty($input['footer-display'])) && $input['footer-display'] == 'on') ? 'on' : "off";
        $valid['footer-top'] = ((isset($input['footer-top']) && !empty($input['footer-top'])) && $input['footer-top'] == 'on') ? 'on' : "off";

        $valid['vote'] = ((isset($input['vote']) && !empty($input['vote'])) && $input['vote'] == 'on') ? 'on' : "off";
        $valid['append'] = ((isset($input['append']) && !empty($input['append'])) && $input['append'] == 'on') ? 'on' : "off";
        $valid['delay'] = ((isset($input['delay']) && !empty($input['delay']) && is_numeric($input['delay']) && $input['delay'] >= 0 && $input['delay'] <= 87600)) ?  (int) $input['delay'] : "0";
        $valid['featured'] = ((isset($input['featured']) && !empty($input['featured'])) && $input['featured'] == 'on') ? 'on' : "off";
        $valid['footer'] = (isset($input['footer']) && !empty($input['footer'])) ? $input['footer'] : "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/exxp/'>Exxp</a> : [%original_link%] </em><hr/></center>";
        $valid['twoway'] = ((isset($input['twoway']) && !empty($input['twoway'])) && $input['twoway'] == 'on') ? 'on' : "off";
        $valid['twoway-front'] = ((isset($input['twoway-front']) && !empty($input['twoway-front'])) && $input['twoway-front'] == 'on') ? 'on' : "off";
        $valid['update'] = ((isset($input['update']) && !empty($input['update'])) && $input['update'] == 'on') ? 'on' : "off";
        $valid['wordlimit'] = ((isset($input['wordlimit']) && !empty($input['wordlimit']) && is_numeric($input['wordlimit']) && $input['wordlimit'] >= 0)) ?  (int) $input['wordlimit'] : "0";
        $valid['license-key'] = (isset($input['license-key']) && !empty($input['license-key'])) ? sanitize_text_field($input['license-key']) : "";

        $users = get_users();

        for ($i = 0; $i < sizeof($users); $i++)
        {
            $valid['posting-key'.$users[$i]->data->ID] = (isset($input['posting-key'.$users[$i]->data->ID]) && !empty($input['posting-key'.$users[$i]->data->ID])) ? sanitize_text_field($input['posting-key'.$users[$i]->data->ID]) : "";
            $valid['username'.$users[$i]->data->ID] = (isset($input['username'.$users[$i]->data->ID]) && !empty($input['username'.$users[$i]->data->ID])) ? sanitize_text_field($input['username'.$users[$i]->data->ID]) : "";
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





    public function Exxp_wp_publish($id)
    {

        $post = get_post($id);

        $options = $this->exxp_wp_get_options($post);

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

        $version = exxp_wp_compte;

        $pos = strrpos(exxp_wp_compte, ".");

        if($pos !== false)
            $version = substr_replace(exxp_wp_compte, "", $pos, strlen("."));

        $version = ((float)$version)*100;


        if ($options['wordlimit'] != "0") {
            $limit = intval($options["wordlimit"]);
            $content = exxpxpTruncateHTML::truncateWords($content, $limit, '');
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
        $result = wp_remote_post(exxp_wp_api_url, $data);
        if (!isset($result->errors)) {
            update_post_meta($id,'steempress_wp_permlink', sanitize_text_field($result['body']));
            update_post_meta($id,'steempress_wp_author', sanitize_user($username));
        }
    }

    public function exxp_wp_bulk_update_action($bulk_actions) {


        $options = $this->exxp_wp_get_options();

        if (!isset($options["update"]))
            $options["update"] = "on";

        if ($options["update"] == "on")
            $bulk_actions['update_to_steem'] = __('Update to HIVE', 'update_to_steem');

        return $bulk_actions;
    }

    public function exxp_wp_bulk_publish_action($bulk_actions) {
        $bulk_actions['publish_to_steem'] = __( 'Publish to hive', 'publish_to_steem');
        return $bulk_actions;
    }


    public function exxp_wp_bulk_publish_handler( $redirect_to, $doaction, $post_ids ) {
        if ( $doaction !== 'publish_to_steem' ) {
            return $redirect_to;
        }
        for ($i = sizeof($post_ids)-1; $i >= 0; $i--) {
            // Perform action for each post.
            $this->Exxp_wp_publish($post_ids[$i]);
        }
        $redirect_to = add_query_arg('published_to_steem', count( $post_ids ), $redirect_to );
        return $redirect_to;
    }

    public function exxp_wp_bulk_update_handler( $redirect_to, $doaction, $post_ids ) {
        if ( $doaction !== 'update_to_steem' ) {
            return $redirect_to;
        }

        $updated = 0;

        for ($i = sizeof($post_ids)-1; $i >= 0; $i--) {
            // Perform action for each post.
            if ($this->exxp_wp_update($post_ids[$i], true) == 1)
                $updated++;
        }

        if ($updated != count($post_ids))
            $redirect_to = add_query_arg('updated_to_steem_err', $updated, $redirect_to );
        else
            $redirect_to = add_query_arg('updated_to_steem', $updated, $redirect_to );
        return $redirect_to;
    }

    public function exxp_wp_bulk_update_notice() {
        if (!empty( $_REQUEST['updated_to_steem'])) {
            $published_count = intval( $_REQUEST['updated_to_steem'] );
            printf( '<div id="message" class="updated fade">' .
                _n( 'Added %s post to be updated on HIVE. Check your posting queue on <a href="https://exxp.io">https://exxp.io</a> to track the progress.',
                    'Added %s posts to be updated on HIVE. Check your posting queue on <a href="https://exxp.io">https://exxp.io</a> to track the progress.',
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

    public function exxp_wp_bulk_publish_notice() {
        if (!empty($_REQUEST['published_to_steem'])) {
            $published_count = intval( $_REQUEST['published_to_steem'] );
            printf( '<div id="message" class="updated fade">' .
                _n( 'Added %s post to be published on HIVE. HIVE only allows one article to be published per 5 minutes so it may take a while. Check your posting queue on <a href="https://exxp.io/dashboard">https://exxp.io/dashboard</a>  to track the progress.',
                    'Added %s posts to be published on HIVE. HIVE only allows one article to be published per 5 minutes so it may take a while. check your posting queue on <a href="https://exxp.io/dashboard">https://exxp.io/dashboard</a> to track the progress.',
                    $published_count,
                    'published_to_steem'
                ) . '</div>', $published_count );
        }
    }

    function exxp_wp_future_post( $post_id ) {

        // See if the publish to hive checkbox is checked.
        $value = get_post_meta($post_id, 'Exxp_wp_steem_publish', true);
        if ($value != "0")
            $this->Exxp_wp_publish($post_id);
    }

    public function exxp_wp_post($new_status, $old_status, $post)
    {
        // If post is empty/ doesn't have the hidden_mm attribute this means that we are using gutenberg
        if ($_POST == [] || !isset($_POST['hidden_mm'])) {
            return;
        }

        // New post
        if ($new_status == 'publish' &&  $old_status != 'publish' && $post->post_type == 'post') {
            if (!isset($_POST['Exxp_wp_steem_publish']) && isset($_POST['Exxp_wp_steem_do_not_publish']) )
                return;

            $this->Exxp_wp_publish($post->ID);

            // Edited post
        } else if ($new_status == 'publish' &&  $old_status == 'publish' && $post->post_type == 'post') {
            if (!isset($_POST['Exxp_wp_steem_update']) && isset($_POST['Exxp_wp_steem_do_not_update']) )
                return;
            $this->exxp_wp_update($post->ID, false);
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

        wp_nonce_field('Exxp_wp_custom_nonce_'.$post_id, 'Exxp_wp_custom_nonce');

        $value = get_post_meta($post_id, 'Exxp_wp_steem_publish', true);
        if ($value == "0")
            $checked = "";
        else
            $checked = "checked";

        ?>
        <div class="misc-pub-section misc-pub-section-last">
            <label><input type="checkbox" value="1" <?php echo $checked; ?> name="Exxp_wp_steem_publish" /> <input type="hidden" name="Exxp_wp_steem_do_not_publish" value="0" />Publish to hive </label>
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

        $options = $this->exxp_wp_get_options();

        if (!isset($options["update"]))
            $options["update"] = "on";

        if ($options["update"] != "on")
            return;

        wp_nonce_field('Exxp_wp_custom_update_nonce_'.$post_id, 'Exxp_wp_custom_update_nonce');

        $value = get_post_meta($post_id, 'Exxp_wp_steem_update', true);
        if ($value == "0")
            $checked = "";
        else
            $checked = "checked";

        ?>
        <div class="misc-pub-section misc-pub-section-last">
            <label><input type="checkbox" value="1" <?php echo $checked; ?> name="Exxp_wp_steem_update" /> <input type="hidden" name="Exxp_wp_steem_do_not_update" value="0" />Update to hive </label>
        </div>
        <?php
    }

    function saveSteemPublishField($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || !current_user_can('edit_post', $post_id) || $_POST == [])
            return;

        if (isset($_POST['Exxp_wp_steem_publish']) || isset($_POST['Exxp_wp_steem_do_not_publish']) ) {

            if ($_POST['Exxp_wp_steem_publish'] === '1' ) {

                // If post is empty/ doesn't have the hidden_mm attribute this means that we are using gutenberg
                    if ($_POST == [] || !isset($_POST['hidden_mm'])) {
                        if (get_post_meta($post_id, 'steempress_wp_author', true ) == "" && get_post_status ($post_id) == 'publish')
                            $this->Exxp_wp_publish($post_id);
                    }

                update_post_meta($post_id, 'Exxp_wp_steem_publish', $_POST['Exxp_wp_steem_publish']);
            } else {
                update_post_meta($post_id, 'Exxp_wp_steem_publish', '0');
            }
        }

        if (isset($_POST['Exxp_wp_steem_update'])) {
            if ($_POST['Exxp_wp_steem_update'] === '1') {
                // If post is empty/ doesn't have the hidden_mm attribute this means that we are using gutenberg
                if ($_POST == [] || !isset($_POST['hidden_mm'])) {
                    $this->exxp_wp_update($post_id);
                }

                update_post_meta($post_id, 'Exxp_wp_steem_update', $_POST['Exxp_wp_steem_update']);
            } else {
                update_post_meta($post_id, 'Exxp_wp_steem_update', '0');
            }
        }

        if (array_key_exists('steempress_wp_permlink', $_POST) && array_key_exists('steempress_wp_author', $_POST)) {


            update_post_meta($post_id,'steempress_wp_permlink', sanitize_text_field($_POST['steempress_wp_permlink']));
            update_post_meta($post_id,'steempress_wp_author', sanitize_user($_POST['steempress_wp_author']));
        }
    }

    public function exxp_wp_custom_box_html($post)
    {

        $author_id = $post->post_author;
        $post_id = get_the_ID();

        // Not published yet.
        if (get_post_status ($post_id) != 'publish')
        {

            $value = get_post_meta($post_id, 'Exxp_wp_steem_publish', true);
            if ($value == "0")
                $checked = "";
            else
                $checked = "checked";


            wp_nonce_field('Exxp_wp_custom_nonce_'.$post_id, 'Exxp_wp_custom_nonce');

            $body  = '<label><input type="checkbox" value="1" '.$checked.' name="Exxp_wp_steem_publish" /> <input type="hidden" name="Exxp_wp_steem_do_not_publish" value="0" />Publish to hive </label>';

            echo $body;

        } else {

            $options = $this->exxp_wp_get_options($post);


            if (!isset($options["update"]))
                $options["update"] = "on";

            if ($options["update"] == "on")
            {
                wp_nonce_field('Exxp_wp_custom_update_nonce_'.$post_id, 'Exxp_wp_custom_update_nonce');

                $value = get_post_meta($post_id, 'Exxp_wp_steem_update', true);
                if ($value == "0")
                    $checked = "";
                else
                    $checked = "checked";

                $body = '<div class="misc-pub-section misc-pub-section-last"><label><input type="checkbox" value="1"  '.$checked.'  name="Exxp_wp_steem_update" /> <input type="hidden" name="Exxp_wp_steem_do_not_update" value="0" />Update to hive </label></div>';
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

            $permlink = get_post_meta($post->ID, 'steempress_wp_permlink', true);
            $meta_author = get_post_meta($post->ID, 'steempress_wp_author', true);

            if ($meta_author != $author && $meta_author != "")
                $author = $meta_author;

            // Sanitize
            $author = sanitize_user($author);
            $permlink = sanitize_text_field($permlink);

            $body .= "<p>These options are only for advanced users regarding hive integration</p>
              <label for=\"steempress_wp_author\">Author : </label><br>
              <input type='text' name='steempress_wp_author' value='" . $author . "'/><br>
              <label for=\"steempress_wp_author\">Permlink</label> 
              <input type='text' name='steempress_wp_permlink' value='" . $permlink . "'/><br>
              ";
            // Minified js to handle the "test parameters" function
            $body .= "<script>function exxp_wp_createCORSRequest(){var e=\"" . exxp_wp_twoway_api_back . "/test_param\",t=new XMLHttpRequest;return\"withCredentials\"in t?t.open(\"POST\",e,!0):\"undefined\"!=typeof XDomainRequest?(t=new XDomainRequest).open(\"POST\",e):t=null,t}function exxp_wp_test_params(){document.getElementById(\"exxp_wp_status\").innerHTML=\"loading...\";var e=exxp_wp_createCORSRequest(),s=document.getElementsByName(\"steempress_wp_author\")[0].value,n=document.getElementsByName(\"steempress_wp_permlink\")[0].value,r=\"username=\"+s+\"&permlink=\"+n;e.setRequestHeader(\"Content-type\",\"application/x-www-form-urlencoded\"),e&&(e.username=s,e.permlink=n,e.onload=function(){var t=e.responseText;document.getElementById(\"exxp_wp_status\").innerHTML=\"ok\"===t?\"The parameters are correct. this article is linked to this <a href='https://hive.blog/@\"+this.username+\"/\"+this.permlink+\"'>hive post</a>\":\"Error : the permlink or username is incorrect.\"},e.send(r))}</script>";

            $body .= "<button type=\"button\" onclick='exxp_wp_test_params()'>Test parameters</button><br/><p id='exxp_wp_status'></p>";


            echo $body;
        }
    }

    public function exxp_wp_add_meta_tag()
    {
        $options = $this->exxp_wp_get_options();

        if ($options['verification-code'] !== "")
        {
            echo '<meta name="exxp_wp_verification" content="'.sanitize_text_field($options['verification-code']).'" />';
        }
    }

    function exxp_wp_add_custom_box()
    {
        $post_id = get_the_ID();

        if (get_post_type($post_id) != 'post') {
            return;
        }

        add_meta_box(
            'exxp_wp_box_id',
            'Exxp options',
            array($this,'exxp_wp_custom_box_html'),
            'post',
            'side'
        );
    }


    function exxp_wp_get_options($post = null) {
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
            $options["footer"] = "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/exxp/'>Exxp</a> : [%original_link%] </em><hr/></center>";
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
                $options["footer"] = "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/exxp/'>Exxp</a> : [%original_link%] </em><hr/></center>";
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
    function exxp_wp_update($post_id, $bulk = false)
    {
        $post = get_post($post_id);
        if ($post->post_status == "publish") {

            if (!isset($_POST['Exxp_wp_steem_update']) && isset($_POST['Exxp_wp_steem_do_not_update']) )
                return;

            $options = $this->exxp_wp_get_options($post);

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

                $version = exxp_wp_compte;

                $pos = strrpos(exxp_wp_compte, ".");

                if ($pos !== false)
                    $version = substr_replace(exxp_wp_compte, "", $pos, strlen("."));

                $version = ((float)$version) * 100;

                $permlink = get_post_meta($post_id, "steempress_wp_permlink");

                if ($options['wordlimit'] != "0") {
                    $limit = intval($options["wordlimit"]);
                    $content = exxpxpTruncateHTML::truncateWords($content, $limit, '');
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
                    $result = wp_remote_post(exxp_wp_api_url . "/update", $data);
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
    function exxp_wp_test_post($data = "no data")
    {
        $data = array("body" => array(
            "data_test" => json_encode($data)
        ));
        wp_remote_post(exxp_wp_api_url . "/dev", $data);
    }

    function exxp_wp_extra_user_profile_fields( $user )
    {
        include_once('partials/exxp_wp-user-display.php');
    }


}
