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
    if (!isset($options["featured"]))
        $options["featured"] = "on";
    if (!isset($options["footer"]))
        $options["footer"] = "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/steempress/'>SteemPress</a> : [%original_link%] </em><hr/></center>";


    $users = get_users();
    $categories = get_categories(array('hide_empty' => FALSE));

    for ($i = 0; $i < sizeof($users); $i++) {
        if (!isset($options['username'.$users[$i]->data->ID]))
            $options['username'.$users[$i]->data->ID] = "";
        if (!isset($options['posting-key'.$users[$i]->data->ID]))
            $options['posting-key'.$users[$i]->data->ID] = "";
    }


    for ($i = 0; $i < sizeof($categories); $i++)
    {
        if (!isset($options['cat'.$categories[$i]->cat_ID]))
            $options['cat'.$categories[$i]->cat_ID] = "off";
    }

    ?>

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <p> Join us on the discord server : https://discord.gg/W2KyAbm </p>
    <form method="post" name="cleanup_options" action="options.php">
        <?php settings_fields($this->plugin_name); ?>
        <!-- remove some meta and generators from the <head> -->

        <p>Default steem account : </p>
        <p>Steem Username : </p>
        <input type="text" class="regular-text" maxlength="16" id="<?php echo $this->plugin_name; ?>-username" name="<?php echo $this->plugin_name; ?>[username]" value="<?php echo htmlspecialchars($options["username"], ENT_QUOTES); ?>"/>
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
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-seo" name="<?php echo $this->plugin_name; ?>[seo]"  <?php echo $options['seo'] == "off" ? '' : 'checked="checked"' ?>> Add the footer text to the end of the article.<br>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-featured" name="<?php echo $this->plugin_name; ?>[featured]"  <?php echo $options['featured'] == "off" ? '' : 'checked="checked"' ?>> Add featured images on top of the steem post.<br>

        <br/>

        <p> Footer text : <br>  the tag [%original_link%] will be replaced by the link of the article on your blog. </p>
        <br/>
        <textarea maxlength="500" type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-footer" name="<?php echo $this->plugin_name; ?>[footer]"><?php echo ($options["footer"] == "" ? "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/steempress/'>SteemPress</a> : [%original_link%] </em><hr/></center>" : $options["footer"]) ?> </textarea>
        <br />


        <button class="steempress_sp_collapsible" type="button">Define more users</button>
        <div class="steempress_sp_content">
            <br/>
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
        </div>
        <br/>
        Category filter : <br/>
        Check the categories that you want steempress to ignore.<br/>
        <?php

        for ($i = 0; $i < sizeof($categories); $i++)
        {
            echo "<input type='checkbox' id='".$this->plugin_name."-cat".$categories[$i]->cat_ID."' name='".$this->plugin_name."[cat".$categories[$i]->cat_ID."]' ".($options['cat'.$categories[$i]->cat_ID] == "on" ? "checked='checked'" : "").">".$categories[$i]->name."<br>";
        }

        ?>


        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>
    </form>
    <p><?php

        $version = steempress_sp_compte;

        $pos = strrpos(steempress_sp_compte, ".");

        if($pos !== false)
            $version = substr_replace(steempress_sp_compte, "", $pos, strlen("."));

        $version = ((float)$version)*100;

        $data = array("body" => array("author" => $options['username'], "wif" => $options['posting-key'], "vote" => $options['vote'], "reward" => $options['reward'], "version" =>  $version, "footer" => $options['footer']));

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
                    $data = array("body" => array("author" => $options['username'.$users[$i]->data->ID], "wif" => $options['posting-key'.$users[$i]->data->ID], "vote" => $options['vote'], "reward" => $options['reward'], "version" =>  $version, "footer" => $options['footer']));
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


<script>
    var coll = document.getElementsByClassName("steempress_sp_collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {
        coll[i].addEventListener("click", function() {
            this.classList.toggle("active");
            var content = this.nextElementSibling;
            if (content.style.maxHeight){
                content.style.maxHeight = null;
            } else {
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
    }
</script>