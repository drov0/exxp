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

    // avoid undefined errors when running it for the first time :
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

    ?>

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    <form method="post" name="cleanup_options" action="options.php">
        <?php settings_fields($this->plugin_name); ?>
        <!-- remove some meta and generators from the <head> -->
        <p>Steem Username : </p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-username" name="<?php echo $this->plugin_name; ?>[username]" value="<?php echo htmlspecialchars($options["username"], ENT_QUOTES); ?>"/>
        <br />
        <?php
        if ($options["posting-key"] == "" || $options['username'] == "")
            echo "Don't have a steem account ? Sign up <a href='https://steemit.com/pick_account'> here</a>"
        ?>
        <p>Private Posting key : </p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-posting-key" name="<?php echo $this->plugin_name; ?>[posting-key]" value="<?php echo htmlspecialchars($options["posting-key"], ENT_QUOTES); ?>"/>
        <br />
        <p> Reward : </p>
        <select name="<?php echo $this->plugin_name; ?>[reward]" id="<?php echo $this->plugin_name; ?>-reward">
            <option value="50" <?php echo ($options["reward"] == "50" ?  'selected="selected"' : '');?>>50% Steem power 50% Steem Dollars</option>
            <option value="100" <?php echo ($options["reward"] == "100" ?  'selected="selected"' : '');?>>100% Steem Power</option>
        </select>



        <p> Default tags : <br> separate each tag by a space, 5 max <br> Will be used if you don't specify tags when publishing. </p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-tags" name="<?php echo $this->plugin_name; ?>[tags]" value="<?php echo htmlspecialchars(($options["tags"] == "" ? "steempress steem" : $options["tags"]), ENT_QUOTES); ?>"/>
        <br />
        <br />

        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-vote" name="<?php echo $this->plugin_name; ?>[vote]"  <?php echo $options['vote'] == "off" ? '' : 'checked="checked"' ?>> Self vote<br>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-seo" name="<?php echo $this->plugin_name; ?>[seo]"  <?php echo $options['seo'] == "off" ? '' : 'checked="checked"' ?>> Add original link to the steem article.<br>


        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>

    </form>


    <p><?php

        $data = array("body" => array("author" => $options['username'], "wif" => $options['posting-key'], "vote" => $options['vote'], "reward" => $options['reward']));

        // Post to the api who will publish it on the steem blockchain.
        $result = wp_remote_post("https://steemgifts.com/test", $data);
        if (is_array($result) or ($result instanceof Traversable))
            $text = $result['body'];
            if ($text == "ok")
                echo "Connectivity to the steem server : <b style='color: darkgreen'>Ok</b> <br/>
                      Username/posting key  : <b style='color: red'> Wrong</b> <br/> Are you sure you used the private posting key and not the public posting key or password ?";
            else if ($text == "wif ok")
                echo "Connectivity to the steem server : <b style='color: darkgreen'>Ok</b> <br/>
                      Username/posting key  : <b style='color: darkgreen'>Ok</b> ";
        else
            echo " Connectivity to the steem server : <b style='color: red'>Connection error</b> <br /> Most likely your host isn't letting the plugin reach our steem server.";
        ?> </p>

</div>
