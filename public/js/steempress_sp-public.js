
(function( $ ) {
    'use strict';

    function load_steem_capabilities() {

        const username = $("#steempress_sp_username")[0].innerText;
        const permlink = $("#steempress_sp_permlink")[0].innerText;
        const tag = $("#steempress_sp_tag")[0].innerText;

        // Create the XHR object.
        function createCORSRequest(method, url) {
            var xhr = new XMLHttpRequest();
            if ("withCredentials" in xhr) {
                // XHR for Chrome/Firefox/Opera/Safari.
                xhr.open(method, url, true);
            } else if (typeof XDomainRequest != "undefined") {
                // XDomainRequest for IE.
                xhr = new XDomainRequest();
                xhr.open(method, url);
            } else {
                // CORS not supported.
                xhr = null;
            }
            return xhr;
        }


		var url = "http://localhost:8002";

		var xhr = createCORSRequest('POST', url);
		var params = "username="+username+"&permlink="+permlink+"&tag="+tag;
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.setRequestHeader("Content-length", params.length);
		if (!xhr) {
			return;
		}

		// Response handlers.
		xhr.onload = function() {
			var data = JSON.parse(xhr.responseText);
			$("#steempress_sp_price")[0].innerHTML = data.payout;
			$("#steempress_sp_comments")[0].innerHTML = data.comments;
		};

		xhr.send(params);
	}

	$(window).load(load_steem_capabilities)

})( jQuery );


;

/*


 */

