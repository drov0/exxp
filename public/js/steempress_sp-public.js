
(function( $ ) {
    'use strict';

    // Create the XHR object.
    function createCORSRequest() {
        var url = "http://localhost:8002"
        var xhr = new XMLHttpRequest();
        if ("withCredentials" in xhr) {
            // XHR for Chrome/Firefox/Opera/Safari.
            xhr.open("POST", url, true);
        } else if (typeof XDomainRequest != "undefined") {
            // XDomainRequest for IE.
            xhr = new XDomainRequest();
            xhr.open("POST", url);
        } else {
            // CORS not supported.
            xhr = null;
        }
        return xhr;
    }

    function post_request(username, permlink, tag, steempress_sp_price, steempress_sp_comment)
    {
        var xhr = createCORSRequest();
        if (steempress_sp_comment === null)
            var params = "username=" + username + "&permlink=" + permlink + "&tag=" + tag + "&display_comment=false";
        else
            var params = "username=" + username + "&permlink=" + permlink + "&tag=" + tag + "&display_comment=true";

        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader("Content-length", params.length);
        if (!xhr) {
            return;
        }
        xhr.steempress_sp_price = steempress_sp_price;

        xhr.steempress_sp_comment = steempress_sp_comment;

        xhr.username = username;
        xhr.permlink = permlink;

        // Response handlers.
        xhr.onload = function () {
            var data = JSON.parse(xhr.responseText);
            this.steempress_sp_price.innerHTML = data.payout;
            if (this.steempress_sp_comment !== null)
                this.steempress_sp_comment.innerHTML = data.comments;
        };

        xhr.send(params);
    }

    function load_steem_capabilities() {

        const usernames = document.getElementsByName("steempress_sp_username");
        const permlinks = document.getElementsByName("steempress_sp_permlink");
        const tags = document.getElementsByName("steempress_sp_tag");
        const front_page = (document.getElementsByName("steempress_sp_comments").length === 0);
        var steempress_sp_prices = document.getElementsByName("steempress_sp_price");
        var steempress_sp_comments = document.getElementsByName("steempress_sp_comments");


        for (var i = 0; i < usernames.length; i++) {

            var username = usernames[i].innerText;
            var permlink = permlinks[i].innerText;
            var tag = tags[i].innerText;

            var steempress_sp_price = steempress_sp_prices[i];
            var steempress_sp_comment = (!front_page ? steempress_sp_comments[i] : null);

            post_request(username, permlink, tag, steempress_sp_price, steempress_sp_comment);

        }
	}

	$(window).load(load_steem_capabilities)

})( jQuery );


;

/*


 */

