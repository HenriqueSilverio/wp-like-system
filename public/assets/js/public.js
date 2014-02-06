(function($) {
	'use strict';

	$(function () {
		var $btnLike = $('#btn-like');

		$btnLike.on('click', function(e) {
			var postID = $btnLike.data('postid');

			$.ajax({
				type: 'POST',
				dataType: 'json',
				url: wplsAjax.url,
				data: {
					action: 'like-post',
					nonce: wplsAjax.nonce,
					doLike: 'true',
					postID: postID
				}
			}).done(function(data) {
				var $msgBtn  = $('.msg-btn'),
					$msgLike = $('#msg-like');

				$msgBtn.text(data.msg_btn);
				$msgLike.text(data.msg_like);
			});

			e.preventDefault();
		});
	});
}(jQuery));
