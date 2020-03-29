<?php

if ( !current_user_can( 'edit_user', $user->ID ) || !current_user_can('edit_posts') )  {
    return false;
}

?>

<label class="wrap">


    <div style="float: right; margin-right: 10%"> <a href="https://steempress.io/dashboard">Steempress post queue</a> </div>
    <?php

    //Grab all options
    $options = [];

    // avoid undefined errors when running it for the first time :
    if (get_the_author_meta( $this->plugin_name."username" , $user->ID) == "")
        $options["username"] = "";
    else
        $options["username"] = get_the_author_meta( $this->plugin_name."username" , $user->ID);

    if (get_the_author_meta( $this->plugin_name."posting-key" , $user->ID) == "")
        $options["posting-key"] = "";
    else
        $options["posting-key"] = get_the_author_meta( $this->plugin_name."posting-key" , $user->ID);

    if (get_the_author_meta( $this->plugin_name."reward" , $user->ID) == "")
        $options["reward"] = "50";
    else
        $options["reward"] = get_the_author_meta( $this->plugin_name."reward" , $user->ID);

    if (get_the_author_meta( $this->plugin_name."tags" , $user->ID) == "")
        $options["tags"] = "";
    else
        $options["tags"] = get_the_author_meta( $this->plugin_name."tags" , $user->ID);

    if (get_the_author_meta( $this->plugin_name."footer-display" , $user->ID) == "")
        $options["footer-display"] = "on";
    else
        $options["footer-display"] = get_the_author_meta( $this->plugin_name."footer-display" , $user->ID);

    if (get_the_author_meta( $this->plugin_name."footer-top" , $user->ID) == "")
        $options["footer-top"] = "off";
    else
        $options["footer-top"] = get_the_author_meta( $this->plugin_name."footer-top" , $user->ID);


    if (get_the_author_meta( $this->plugin_name."vote" , $user->ID) == "")
        $options["vote"] = "on";
    else
        $options["vote"] = get_the_author_meta( $this->plugin_name."vote" , $user->ID);


    if (get_the_author_meta( $this->plugin_name."append" , $user->ID) == "")
        $options["append"] = "off";
    else
        $options["append"] = get_the_author_meta( $this->plugin_name."append" , $user->ID);


    if (get_the_author_meta( $this->plugin_name."delay" , $user->ID) == "")
        $options["delay"] = "0";
    else
        $options["delay"] = get_the_author_meta( $this->plugin_name."delay" , $user->ID);


    if (get_the_author_meta( $this->plugin_name."featured" , $user->ID) == "")
        $options["featured"] = "on";
    else
        $options["featured"] = get_the_author_meta( $this->plugin_name."featured" , $user->ID);


    if (get_the_author_meta( $this->plugin_name."footer" , $user->ID) == "")
        $options["footer"] = "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/steempress/'>SteemPress</a> : [%original_link%] </em><hr/></center>";
    else
        $options["footer"] = get_the_author_meta( $this->plugin_name."footer" , $user->ID);


    if (get_the_author_meta( $this->plugin_name."update" , $user->ID) == "")
        $options["update"] = "on";
    else
        $options["update"] = get_the_author_meta( $this->plugin_name."update" , $user->ID);

    if (get_the_author_meta( $this->plugin_name."wordlimit" , $user->ID) == "")
        $options["wordlimit"] = "0";
    else
        $options["wordlimit"] = get_the_author_meta( $this->plugin_name."wordlimit" , $user->ID);


    if ($options['posting-key'] != "")
        $options['posting-key-display'] = "posting key set. Enter another one to change it";

    $categories = get_categories(array('hide_empty' => FALSE));

    for ($i = 0; $i < sizeof($categories); $i++)
    {
        if (get_the_author_meta( $this->plugin_name.'cat'.$categories[$i]->cat_ID , $user->ID) == "")
            $options['cat'.$categories[$i]->cat_ID] = "off";
        else
            $options['cat'.$categories[$i]->cat_ID] = get_the_author_meta( $this->plugin_name.'cat'.$categories[$i]->cat_ID , $user->ID);
    }

    ?>

    <h2> SteemPress Options</h2>

    <p> Join us on the discord server : https://discord.gg/W2KyAbm </p>
        <p>hive account : </p>
        <p>hive Username : </p>
        <input type="text" class="regular-text" maxlength="16" id="<?php echo $this->plugin_name; ?>-username" name="<?php echo $this->plugin_name; ?>[username]" value="<?php echo htmlspecialchars($options["username"], ENT_QUOTES); ?>"/>
        <br />
        <?php
        if ($options["posting-key"] == "" || $options['username'] == "")
            echo "Don't have a hive account ? Sign up <a href='https://steempress.io/signup'> here</a>"
        ?>
        <p>Private Posting key : </p>
        <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-posting-key" name="<?php echo $this->plugin_name; ?>[posting-key]" value="<?php echo htmlspecialchars($options["posting-key-display"], ENT_QUOTES); ?>"/>
        <br />

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

    <input type="checkbox" id="<?php echo $this->plugin_name; ?>-append-tags" name="<?php echo $this->plugin_name; ?>[append]"  <?php echo $options['append'] == "off" ? '' : 'checked="checked"' ?>><label for="<?php echo $this->plugin_name; ?>-append-tags"> Always add the default tags before the post tags. (For instance if the post tags are "life travel" and your default tag is "french", the tags used on the post will be "french life travel") </label><br/>
    <input type="checkbox" id="<?php echo $this->plugin_name; ?>-vote" name="<?php echo $this->plugin_name; ?>[vote]"  <?php echo $options['vote'] == "off" ? '' : 'checked="checked"' ?>><label for="<?php echo $this->plugin_name; ?>-vote"> Self vote </label><br>
    <input type="checkbox" id="<?php echo $this->plugin_name; ?>-footer-display" name="<?php echo $this->plugin_name; ?>[footer-display]"  <?php echo $options['footer-display'] == "off" ? '' : 'checked="checked"' ?>><label for="<?php echo $this->plugin_name; ?>-footer-display"> Add the footer text to the end of the article. </label><br>
    <input type="checkbox" id="<?php echo $this->plugin_name; ?>-footer-top" name="<?php echo $this->plugin_name; ?>[footer-top]"  <?php echo $options['footer-top'] == "off" ? '' : 'checked="checked"' ?>><label for="<?php echo $this->plugin_name; ?>-footer-top"> Add the footer text to the top of the article.</label><br>
    <input type="checkbox" id="<?php echo $this->plugin_name; ?>-featured" name="<?php echo $this->plugin_name; ?>[featured]"  <?php echo $options['featured'] == "off" ? '' : 'checked="checked"' ?>><label for="<?php echo $this->plugin_name; ?>-featured"> Add featured images on top of the hive post.</label><br>
    <input type="checkbox" id="<?php echo $this->plugin_name; ?>-update" name="<?php echo $this->plugin_name; ?>[update]"  <?php echo $options['update'] == "off" ? '' : 'checked="checked"' ?>> <label for="<?php echo $this->plugin_name; ?>-update">Update the hive post when updating on wordpress.</label><br>

    <br/>

    <p> Footer text : <br>  the tag [%original_link%] will be replaced by the link of the article on your blog. </p>
    <br/>
    <textarea maxlength="30000" type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-footer" name="<?php echo $this->plugin_name; ?>[footer]"><?php echo ($options["footer"] == "" ? "<br /><center><hr/><em>Posted from my blog with <a href='https://wordpress.org/plugins/steempress/'>SteemPress</a> : [%original_link%] </em><hr/></center>" : $options["footer"]) ?> </textarea>
    <br />
    Category filter : <br/>
    Check the categories that you want steempress to ignore.<br/>
    <?php

    for ($i = 0; $i < sizeof($categories); $i++)
    {
        echo "<input type='checkbox' id='".$this->plugin_name."-cat".$categories[$i]->cat_ID."' name='".$this->plugin_name."[cat".$categories[$i]->cat_ID."]' ".($options['cat'.$categories[$i]->cat_ID] == "on" ? "checked='checked'" : "").">".$categories[$i]->name."<br>";
    }

    ?>
    <br/>

    <p> Word limit : only publish the first x words to the hive blockchain, set to 0 to publish the entire article. </p>
    <input type="number" class="regular-text" id="<?php echo $this->plugin_name; ?>-wordlimit" name="<?php echo $this->plugin_name; ?>[wordlimit]" value="<?php echo htmlspecialchars(($options["wordlimit"] == "" ? "0" : $options["wordlimit"]), ENT_QUOTES); ?>"/>
    <br />
    <p><?php

        $version = steempress_sp_compte;

        $pos = strrpos(steempress_sp_compte, ".");

        if($pos !== false)
            $version = substr_replace(steempress_sp_compte, "", $pos, strlen("."));

        $version = ((float)$version)*100;

        $data = array("body" => array("author" => $options['username'], "wif" => $options['posting-key'], "vote" => $options['vote'], "reward" => $options['reward'], "version" =>  $version, "footer" => $options['footer']));

        // Post to the api who will publish it on the hive blockchain.
        $result = wp_remote_post(steempress_sp_api_url."/test", $data);
        if (is_array($result) or ($result instanceof Traversable)) {
            echo "Connectivity to the hive server : <b style='color: darkgreen'>Ok</b> <br/>";
            $text = $result['body'];
            if ($text == "ok")
                echo "Default Username/posting key  : <b style='color: red'> Wrong</b> <br/> Are you sure you used the private posting key and not the public posting key or password ?";
            else if ($text == "wif ok")
                echo "Default username/posting key  : <b style='color: darkgreen'>Ok</b> ";

        }
        else
            echo " Connectivity to the hive server : <b style='color: red'>Connection error</b> <br /> Most likely your host isn't letting the plugin reach our hive server.";
        ?> </p>
</div>