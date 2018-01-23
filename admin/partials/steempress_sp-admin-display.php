<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://steemit.com/@howo
 * @since      1.0.0
 *
 * @package    Sp
 * @subpackage Sp/admin/partials
 */
?>

<div class="wrap">

    <?php
    //Grab all options
    $options = get_option($this->plugin_name);
    ?>

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <form method="post" name="cleanup_options" action="options.php">
        <?php settings_fields($this->plugin_name); ?>
        <!-- remove some meta and generators from the <head> -->
        <p>Steem Username : </p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-username" name="<?php echo $this->plugin_name; ?>[username]" value="<?php echo htmlspecialchars($options["username"], ENT_QUOTES); ?>"/>
        <br />
        <p>Private Posting key : </p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-posting-key" name="<?php echo $this->plugin_name; ?>[posting-key]" value="<?php echo htmlspecialchars($options["posting-key"], ENT_QUOTES); ?>"/>
        <br />
        <!--<p> Reward : </p>
        <select name="<?php echo $this->plugin_name; ?>[reward]" id="<?php echo $this->plugin_name; ?>-reward">
            <option value="50" <?php echo ($options["reward"] == "50" ?  'selected="selected"' : '');?>>50% Steem power 50% Steem Dollars</option>
            <option value="100" <?php echo ($options["reward"] == "100" ?  'selected="selected"' : '');?>>100% Steem Power</option>
        </select>-->



        <p> Default tags : (separate each tag by a space, 5 max) </p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-tags" name="<?php echo $this->plugin_name; ?>[tags]" value="<?php echo htmlspecialchars(($options["tags"] == "" ? "steempress steem" : $options["tags"]), ENT_QUOTES); ?>"/>
        <br />

        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>

    </form>

    <p> Connectivity to the steem server : <?php

        $data = array("body" => array("test" => "test"));

        // Post to the api who will publish it on the steem blockchain.
        $result = wp_remote_post("http://steemtutorial.com:81/test", $data);

        if (is_array($result) or ($result instanceof Traversable))
            echo "<b style='color: darkgreen'>Ok</b>";
        else
            echo "<b style='color: darkgreen'>Connection error</b> <br /> Most likely your host isn't letting the plugin reach our steem server.";
        ?> </p>

</div>
