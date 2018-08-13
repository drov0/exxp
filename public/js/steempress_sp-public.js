
(function( $ ) {
    'use strict';

    function compare_posts(a,b) {
        if (a.payout < b.payout)
            return 1;
        if (a.payout > b.payout)
            return -1;
        return 0;
    }

    function get_whole_values(base_value, time_fractions) {
        var time_data = [base_value];
        for (var i = 0; i < time_fractions.length; i++) {
            time_data.push(parseInt(time_data[i]/time_fractions[i]));
            time_data[i] = time_data[i] % time_fractions[i];
        }; return time_data;
    };

    function datestr(elapsed)
    {
        const whole = get_whole_values(elapsed, [60,60,24]);

        var display_date = "";

        if (whole[3] !== 0) {
            if (whole[3] === 1)
                display_date += whole[3]+" day ";
            else
                display_date += whole[3]+" days ";

            if (whole[2] !== 0) {
                if (whole[2] === 1)
                    display_date += whole[2]+ " hour ago";
                else
                    display_date += whole[2]+ " hours ago";
            }
        }
        else
        {
            if (whole[2] !== 0) {
                if (whole[2] === 1)
                    display_date += whole[2]+" hour ";
                else
                    display_date += whole[2]+" hours ";

                if (whole[1] !== 0) {
                    if (whole[1] === 1)
                        display_date += whole[1]+ " minute ago";
                    else
                        display_date += whole[1]+ " minutes ago";
                }
            }
            else if (whole[1] !== 0) {
                if (whole[1] === 1)
                    display_date += whole[1]+" minute ";
                else
                    display_date += whole[1]+" minutes ";

                if (whole[0] !== 0) {
                    if (whole[0] === 1)
                        display_date += whole[0]+ " second ago";
                    else
                        display_date += whole[0]+ " seconds ago";
                }
            }
            else {
                if (whole[0] === 1)
                    display_date += whole[0] + " second ago";
                else
                    display_date += whole[0] + " seconds ago";
            }

        }

        return display_date;
    }


    function get_all_comments(author, permlink, tag, callback)
    {
        steem.api.getState(tag+"/@"+author+"/"+permlink, function (err, post) {
            if (err)
                return resolve({error:err});

            if (post['root_permlink'] === "" && post['root_author'] === "" )
                return resolve({error:"content not found"});

            var comment_list = post.content;
            var comments_ordered = [];
            var author_list = new Set([]); // will be used later to query the blockchain for user avatars

            for (const comment_id in comment_list) {

                var comment = comment_list[comment_id];

                if (comment.depth !== 0)
                    continue;
                comments_ordered.push(get_replies(comment, comment_list, author_list));
            }



            return callback(comments_ordered);
        });
    }

    function get_replies(comment, comment_list, author_list)
    {
        author_list.add(comment.author);
        comment.date = datestr(Math.floor(new Date().getTime() / 1000) - (new Date(comment.created).getTime()/1000));

        for (var i = 0; i < comment.replies.length; i++) {
            var reply = comment_list[comment.replies[i]];
            if (reply.total_payout_value !== "0.000 SBD")
                reply.payout = reply.total_payout_value.replace("SBD", "");
            else
                reply.payout = reply.pending_payout_value.replace("SBD", "");

            reply.payout = (Math.floor(parseFloat(reply.payout)*100)/100);

            comment.replies[i] = get_replies(reply, comment_list, author_list);
        }

        comment.replies.sort(compare_posts);

        return comment;
    }


    function generate_comment_string(comments)
    {
        var str = "";

        for (var i = 0; i < comments.length; i++)
        {
            str += "<li class=\"steempress_sp_cmmnt\">\n" +
                "          <div class=\"avatar\"><img src=\"https://steemitimages.com/u/"+comments[i].author+"/avatar\" style='height: 55px; width: 55px' alt=\""+comments[i].author+"'s avatar\"></div>\n" +
                "          <div class=\"steempress_sp_cmmnt-content\">\n" +
                "            <header><a href=\"https://steemit.com/@"+comments[i].author+"\" class=\"userlink\">"+comments[i].author+"</a> - <span class=\"pubdate\">"+comments[i].date+"</span> </header>\n" +
                "            <p class=\"steempress_sp_comment_text\">"+comments[i].body+"</p>\n" + comments[i].payout.toString()+" $"+
                "          </div>\n"

            if (comments[i].replies.length !== 0) {
                str += "<ul class=\"replies\">";
                str += generate_comment_string(comments[i].replies);
                str += "</ul>";
            }
            str +=    "        </li>"
        }

        return str;

    }


    function load_steem_capabilities() {

        const username = $("#steempress_sp_username")[0].innerText;
        const permlink = $("#steempress_sp_permlink")[0].innerText;
        const tag = $("#steempress_sp_tag")[0].innerText;

        get_all_comments(username, permlink, tag, function (result) {

            // TODO : Test this correctly
            if (result.error)
                return load_steem_capabilities();

            var payout = "";

            if (result[0].total_payout_value !== "0.000 SBD")
                payout = result[0].total_payout_value.replace("SBD", "");
            else
                payout = result[0].pending_payout_value.replace("SBD", "");

            payout = (Math.floor(parseFloat(payout) * 100) / 100);
            payout = payout.toString() + " $";

            $("#steempress_sp_price")[0].innerHTML = payout;

            var comment_str = "<p>Steem comments  <a href=\"https://wordpress.org/plugins/steempress/\">powered by SteemPress</a> : </p> <div id=\"steempress_sp_comment_container\"><ul id=\"steempress_sp_comments\">";
            comment_str += generate_comment_string(result[0].replies);
            comment_str += "</ul></div>";
            $("#steempress_sp_comments")[0].innerHTML = comment_str;


        });
    }

    $(window).load(load_steem_capabilities)

})( jQuery );


;

/*


 */

