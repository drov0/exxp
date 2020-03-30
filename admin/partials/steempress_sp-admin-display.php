<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://hive.blog/@howo
 * @since      1.0.0
 *
 * @package    Sp
 * @subpackage Sp/admin/partials
 */


?>

<div class="wrap">


    <?php


    if ($options["posting-key"] != "" && $options['username'] != "") {
        echo '<div style="float: right; margin-right: 10%"> <a href="https://steempress.io/dashboard">Steempress post queue</a> </div>';
    }
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

    if ($options['posting-key'] != "")
        $options['posting-key-display'] = "posting key set. Enter another one to change it";

    $categories = get_categories(array('hide_empty' => FALSE));

    for ($i = 0; $i < sizeof($categories); $i++)
    {
        if (!isset($options['cat'.$categories[$i]->cat_ID]))
            $options['cat'.$categories[$i]->cat_ID] = "off";
    }

    ?>
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <p> Join us on the discord server : <a href="https://discord.gg/W2KyAbm">https://discord.gg/W2KyAbm</a> </p>
    <form method="post" name="cleanup_options" action="options.php">
        <?php settings_fields($this->plugin_name); ?>


        <div style="float: right; margin-right: 40%; margin-top: -5%">
            <?php

            if ($options["posting-key"] != "" && $options['username'] != "") {
                echo "<p > This is a placeholder for a future feature .</p >";
                echo "<label for=".$this->plugin_name."-license-key> License key :</label > <br /><br />";
                echo "<input type = 'text' class='regular-text' id ='".$this->plugin_name."-license-key' name = '".$this->plugin_name."[license-key]' value = '".htmlspecialchars($options["license-key"], ENT_QUOTES)."' />";
            }
            ?>
        </div>


        <p>Default hive account : </p>
        <p>Hive Username : </p>
        <input type="text" class="regular-text" maxlength="16" id="<?php echo $this->plugin_name; ?>-username" name="<?php echo $this->plugin_name; ?>[username]" value="<?php echo htmlspecialchars($options["username"], ENT_QUOTES); ?>"/>
        <br />

        <p>Private Posting key : </p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-posting-key" name="<?php echo $this->plugin_name; ?>[posting-key]" value="<?php echo htmlspecialchars($options["posting-key-display"], ENT_QUOTES); ?>"/>
        <br />
        <br />
        <?php
        if ($options["posting-key"] == "" || $options['username'] == "") {
            echo "If you've registered through <a href='https://steempress.io/signup'>https://steempress.io/signup</a> please enter the verification code that you recieved here : <br/>";
            echo "<input placeholder='verification code ' type='text' class='regular-text' maxlength='20' id='" . $this->plugin_name . "-verification-code' name='".$this->plugin_name."[verification-code]' value='" . htmlspecialchars($options["verification-code"], ENT_QUOTES)."'/>";

            submit_button('Save all changes', 'primary','submit', TRUE);

            if ($options["verification-code"] != "") {
                $data = array("body" => array(
                    "domain" => get_site_url(),
                    "verification_code" => $options['verification-code']
                ));
                $result = wp_remote_post(steempress_sp_api_url."/verification_code", $data);
                $text = $result['body'];
                if ($text == "verification_ok" || $text == "verification_not_new")
                    echo "Thank you for verifying your blog. You will receive an email with a sign up link once the application has been be reviewed, please check your spam folder.<br/> If you didn't receive anything after a week, contact us at <b>contact@steempress.io</b>";
                else
                    echo "Your verification code is incorrect, please make sure it's the right one that you recieved by email. If you believe this is an error, please contact us at <b>contact@steempress.io</b>";
            }
            exit("");

        }
        ?>
        <p> Reward : </p>
        <select name="<?php echo $this->plugin_name; ?>[reward]" id="<?php echo $this->plugin_name; ?>-reward">
            <option value="50" <?php echo ($options["reward"] == "50" ?  'selected="selected"' : '');?>>50% hive power 50% hive Dollars</option>
            <option value="100" <?php echo ($options["reward"] == "100" ?  'selected="selected"' : '');?>>100% hive Power</option>
        </select>



        <p> Default tags : <br> separate each tag by a space, 5 max <br> Will be used if you don't specify tags when publishing. </p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-tags" name="<?php echo $this->plugin_name; ?>[tags]" value="<?php echo htmlspecialchars(($options["tags"] == "" ? "steempress blog" : $options["tags"]), ENT_QUOTES); ?>"/>
        <br />
        <p> Delay posts : Your posts will get published to hive x minutes after being published on your blog. A value of 0 posts your articles to hive as soon as you publish them. maximum value is 87600, 2 months. </p>
        <input type="number" max="87600" class="regular-text" id="<?php echo $this->plugin_name; ?>-delay" name="<?php echo $this->plugin_name; ?>[delay]" value="<?php echo htmlspecialchars(($options["delay"] == "" ? "0" : $options["delay"]), ENT_QUOTES); ?>"/>
        <br />
        <br />

        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-append-tags" name="<?php echo $this->plugin_name; ?>[append]"  <?php echo $options['append'] == "off" ? '' : 'checked="checked"' ?>> <label for="<?php echo $this->plugin_name; ?>-append-tags">Always add the default tags before the post tags. (For instance if the post tags are "life travel" and your default tag is "french", the tags used on the post will be "french life travel")</label> <br/>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-vote" name="<?php echo $this->plugin_name; ?>[vote]"  <?php echo $options['vote'] == "off" ? '' : 'checked="checked"' ?>><label for="<?php echo $this->plugin_name; ?>-vote"> Self vote</label><br>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-footer-display" name="<?php echo $this->plugin_name; ?>[footer-display]"  <?php echo $options['footer-display'] == "off" ? '' : 'checked="checked"' ?>><label for="<?php echo $this->plugin_name; ?>-footer-display"> Add the footer text to the end of the article.</label><br>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-footer-top" name="<?php echo $this->plugin_name; ?>[footer-top]"  <?php echo $options['footer-top'] == "off" ? '' : 'checked="checked"' ?>><label for="<?php echo $this->plugin_name; ?>-footer-top"> Add the footer text to the top of the article.</label><br>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-featured" name="<?php echo $this->plugin_name; ?>[featured]"  <?php echo $options['featured'] == "off" ? '' : 'checked="checked"' ?>><label for="<?php echo $this->plugin_name; ?>-featured"> Add featured images on top of the hive post.</label><br>
        <input type="checkbox" id="<?php echo $this->plugin_name; ?>-update" name="<?php echo $this->plugin_name; ?>[update]"  <?php echo $options['update'] == "off" ? '' : 'checked="checked"' ?>><label for="<?php echo $this->plugin_name; ?>-update"> Update the hive post when updating on wordpress.</label><br>

        <br/>

        <p> Footer text : <br>  the tag [%original_link%] will be replaced by the link of the article on your blog. </p>
        <br/>
        <textarea maxlength="30000" type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-footer" name="<?php echo $this->plugin_name; ?>[footer]"><?php echo ($options["footer"] == "" ? "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/steempress/'>SteemPress</a> : [%original_link%] </em><hr/></center>" : $options["footer"]) ?></textarea>
        <br />

        <br/>
        Category filter : <br/>
        Check the categories that you want steempress to ignore.<br/>
        <?php

        for ($i = 0; $i < sizeof($categories); $i++)
        {
            echo "<input type='checkbox' id='".$this->plugin_name."-cat".$categories[$i]->cat_ID."' name='".$this->plugin_name."[cat".$categories[$i]->cat_ID."]' ".($options['cat'.$categories[$i]->cat_ID] == "on" ? "checked='checked'" : "")."><label for='".$this->plugin_name."-cat".$categories[$i]->cat_ID."'>".$categories[$i]->name."</label><br>";
        }

        ?>

        <br/>
        Two way integration (BETA) <br/>
        Displays hive features including, upvotes, pending rewards, comments and hive log in on the blog interface. <br/>
        <?php
        echo "<input type='checkbox' id='".$this->plugin_name."-twoway' name='".$this->plugin_name."[twoway]' ".($options['twoway'] == "on" ? "checked='checked'" : "")."> <label for='".$this->plugin_name."-twoway'> Activate for posts </label>  <br/>";
        echo "<input type='checkbox' id='".$this->plugin_name."-twoway-front' name='".$this->plugin_name."[twoway-front]' ".($options['twoway-front'] == "on" ? "checked='checked'" : "")."><label for='".$this->plugin_name."-twoway-front'>  Activate for front page (requires two way integration for posts to be active)</label>";

        ?>
        <br />
        <p> Word limit : only publish the first x words to the hive blockchain, set to 0 to publish the entire article. </p>
        <input type="number" class="regular-text" id="<?php echo $this->plugin_name; ?>-wordlimit" name="<?php echo $this->plugin_name; ?>[wordlimit]" value="<?php echo htmlspecialchars(($options["wordlimit"] == "" ? "0" : $options["wordlimit"]), ENT_QUOTES); ?>"/>
        <br />


        <?php

        submit_button('Save all changes', 'primary','submit', TRUE); ?>


    </form>
    <p><?php


        $version = steempress_sp_compte;

        $pos = explode(".", $version);

        if(sizeof($pos) > 2)
        {
            $pos = strrpos($version, ".");
            $version = substr_replace($version, "", $pos, strlen("."));
        }
        $version = ((float)$version)*100;

        $data = array("body" => array(
            "author" => $options['username'],
            "wif" => $options['posting-key'],
            "vote" => $options['vote'],
            "reward" => $options['reward'],
            "version" =>  $version,
            "footer" => $options['footer'],
            "license" => $options['license-key'],
        ));

        // Post to the api who will publish it on the hive blockchain.
        $result = wp_remote_post(steempress_sp_api_url."/test", $data);


        if (is_array($result) or ($result instanceof Traversable)) {
            echo "Connectivity to the hive server : <b style='color: darkgreen'>Ok</b> <br/>";
            $text = $result['body'];

            if ($text == "ok")
                echo "Default Username/posting key  : <b style='color: red'> Wrong</b> <br/> Are you sure you used the private posting key and not the public posting key or password ?";
            else if ($text == "wif ok" || is_numeric($text) || $text == "noexist" || $text == "expired") {
                echo "Default username/posting key  : <b style='color: darkgreen'>Ok</b> <br/>";

                if (is_numeric($text))
                    echo "Steempress premium : <b style='color: darkgreen'>Ok</b> Expiration date : ".date('d/m/Y H:i', $text);
                else if ($text == "noexist")
                    echo "Steempress premium : <b style='color: red'>License key not found</b>";
                else if ($text == "expired")
                    echo "Steempress premium : <b style='color: red'>Your premium package has expired please renew it at <a href='#'>https://premium.steempress.io</a></b>";

            }
        }
        else
            echo " Connectivity to the hive server : <b style='color: red'>Connection error</b> <br /> Most likely your host isn't letting the plugin reach our hive server.";
        ?> </p>
</div>
