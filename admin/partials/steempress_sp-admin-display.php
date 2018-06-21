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
        $options["reward"] = "50";
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
    if (!isset($options["append"]))
        $options["append"] = "off";
    if (!isset($options["delay"]))
        $options["delay"] = "0";



    $users = get_users();


    for ($i = 0; $i < sizeof($users); $i++) {
        if (!isset($options['username'.$users[$i]->data->ID]))
            $options['username'.$users[$i]->data->ID] = "";
        if (!isset($options['posting-key'.$users[$i]->data->ID]))
            $options['posting-key'.$users[$i]->data->ID] = "";
    }

    ?>

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <p> Join us on the discord server : https://discord.gg/W2KyAbm </p>
    <form method="post" name="cleanup_options" action="options.php">
        <?php settings_fields($this->plugin_name); ?>
        <!-- remove some meta and generators from the <head> -->

        <p>Default steem account : </p>
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
        <p> Delay posts : Your posts will get published to steem x minutes after being published on your blog. A value of 0 posts your articles to steem as soon as you publish them.</p>
        <input type="number" class="regular-text" id="<?php echo $this->plugin_name; ?>-delay" name="<?php echo $this->plugin_name; ?>[delay]" value="<?php echo htmlspecialchars(($options["delay"] == "" ? "0" : $options["delay"]), ENT_QUOTES); ?>"/>
        <br />
        <br />

        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-append-tags" name="<?php echo $this->plugin_name; ?>[append]"  <?php echo $options['append'] == "off" ? '' : 'checked="checked"' ?>> Always add the default tags before the post tags. (For instance if the post tags are "life travel" and your default tag is "french", the tags used on the post will be "french life travel") <br/>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-vote" name="<?php echo $this->plugin_name; ?>[vote]"  <?php echo $options['vote'] == "off" ? '' : 'checked="checked"' ?>> Self vote<br>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-seo" name="<?php echo $this->plugin_name; ?>[seo]"  <?php echo $options['seo'] == "off" ? '' : 'checked="checked"' ?>> Add the original link to the steem article.<br>

        <br/>

        Define more users : <br/>

        If user x publishes a post and you have set his username/private key, it will get posted on his account instead of the default one.
        <br />
        <br />
        <?php



        for ($i = 0; $i < sizeof($users); $i++)
        {
            echo "Name : ".$users[$i]->data->display_name."<br/>";
            echo "Role : ".$users[$i]->roles[0]."<br/>";

            echo '<p> Steem username :</p>';
            echo '<input type="text" class="regular-text" id="'.$this->plugin_name.'-username-'.$users[$i]->data->ID.'" name="'.$this->plugin_name.'[username'.$users[$i]->data->ID.']" value="'.htmlspecialchars($options["username".$users[$i]->data->ID], ENT_QUOTES).'"/><br />';
            echo '<p>Private Posting key : </p> <input type="text" class="regular-text" id="'.$this->plugin_name.'-posting-key-'.$users[$i]->data->ID.'" name="'.$this->plugin_name.'[posting-key'.$users[$i]->data->ID.']" value="'.htmlspecialchars($options["posting-key".$users[$i]->data->ID], ENT_QUOTES).'"/><br/><br/>';
        }

        ?>

        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>
    </form>
    <p><?php

        $data = array("body" => array("author" => $options['username'], "wif" => $options['posting-key'], "vote" => $options['vote'], "reward" => $options['reward'], "version" =>  ((float)steempress_sp_compte)*100));

        // Post to the api who will publish it on the steem blockchain.
        $result = wp_remote_post($this->api_url."/test", $data);
        if (is_array($result) or ($result instanceof Traversable)) {
            echo "Connectivity to the steem server : <b style='color: darkgreen'>Ok</b> <br/>";
            $text = $result['body'];
            if ($text == "ok")
                      echo "Default Username/posting key  : <b style='color: red'> Wrong</b> <br/> Are you sure you used the private posting key and not the public posting key or password ?";
            else if ($text == "wif ok")
                echo "Default username/posting key  : <b style='color: darkgreen'>Ok</b> ";

            echo "<br/>";
            echo "<br/>";
            for ($i = 0; $i < sizeof($users); $i++)
            {
                if ($options['username'.$users[$i]->data->ID] != "" && $options['posting-key'.$users[$i]->data->ID] != "")
                {
                    echo "Name : ".$users[$i]->data->display_name."<br/>";
                    echo "Role : ".$users[$i]->roles[0]."<br/>";
                    $data = array("body" => array("author" => $options['username'.$users[$i]->data->ID], "wif" => $options['posting-key'.$users[$i]->data->ID], "vote" => $options['vote'], "reward" => $options['reward'], "version" =>  ((float)steempress_sp_compte)*100));
                    $result = wp_remote_post($this->api_url."/test", $data);
                    $text = $result['body'];
                    if ($text == "ok")
                        echo "Username/posting key  : <b style='color: red'> Wrong</b> <br/> Are you sure you used the private posting key and not the public posting key or password ?<br/>";
                    else if ($text == "wif ok")
                        echo "username/posting key  : <b style='color: darkgreen'>Ok</b> <br/>";

                    echo "<br/>";
                }


            }

        }
        else
            echo " Connectivity to the steem server : <b style='color: red'>Connection error</b> <br /> Most likely your host isn't letting the plugin reach our steem server.";
        ?> </p>

</div>
