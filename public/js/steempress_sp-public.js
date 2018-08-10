
(function( $ ) {
    'use strict';

    /**
     * All of the code for your admin-facing JavaScript source
     * should reside in this file.
     *
     * Note: It has been assumed you will write jQuery code here, so the
     * $ function reference has been prepared for usage within the scope
     * of this function.
     *
     * This enables you to define handlers, for when the DOM is ready:
     *
     * $(function() {
	 *
	 * });
     *
     * When the window is loaded:
     *
     * $( window ).load(function() {
	 *
	 * });
     *
     * ...and/or other possibilities.
     *
     * Ideally, it is not considered best practise to attach more than a
     * single DOM-ready or window-load handler for a particular page.
     * Although scripts in the WordPress core, Plugins and Themes may be
     * practising this, we should strive to set a better example in our own work.
     */


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
        for (var i = 0; i < comment.replies.length; i++) {
            var reply = comment_list[comment.replies[i]];
            if (reply.total_payout_value !== "0.000 SBD")
                reply.payout = reply.total_payout_value.replace("SBD", "");
            else
                reply.payout = reply.pending_payout_value.replace("SBD", "");

            reply.payout = (Math.floor(parseFloat(reply.payout)*100)/100);
            reply.payout = reply.payout.toString()+" $";

            comment.replies[i] = get_replies(reply, comment_list, author_list);
        }
        return comment;
    }


    function generate_comment_string(comments)
    {
        var str = "";

        for (var i = 0; i < comments.length; i++)
        {
            str += "<li class=\"steempress_sp_cmmnt\">\n" +
                "          <div class=\"avatar\"><img src=\"https://steemitimages.com/u/"+comments[i].author+"/avatar\" width=\"55\" height=\"55\" alt=\""+comments[i].author+"'s avatar\"></div>\n" +
                "          <div class=\"steempress_sp_cmmnt-content\">\n" +
                "            <header><a href=\"https://steemit.com/@"+comments[i].author+"\" class=\"userlink\">"+comments[i].author+"</a> - <span class=\"pubdate\">posted 6 days ago</span> "+comments[i].payout+" </header>\n" +
                "            <p class=\"steempress_sp_comment_text\">"+comments[i].body+"</p>\n" +
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

    function generate_replies_string(comment)
    {

    }


    $(window).load(function () {

        const username = $("#steempress_sp_username")[0].innerText;
        const permlink = $("#steempress_sp_permlink")[0].innerText;
        const tag = $("#steempress_sp_tag")[0].innerText;


        get_all_comments(username, permlink, tag, function (result) {

            var payout = "";

            if (result[0].total_payout_value !== "0.000 SBD")
                payout = result[0].total_payout_value.replace("SBD", "");
            else
                payout = result[0].pending_payout_value.replace("SBD", "");

            payout = (Math.floor(parseFloat(payout)*100)/100);
            payout = payout.toString()+" $";

            $("#steempress_sp_price")[0].innerHTML = payout;

            var comment_str = "<div id=\"steempress_sp_comment_container\"><ul id=\"steempress_sp_comments\">";
            comment_str += generate_comment_string(result[0].replies);
            comment_str += "</ul></div>";
            $("#steempress_sp_comments")[0].innerHTML = comment_str;


        });
    })

})( jQuery );


;

/*


 */

