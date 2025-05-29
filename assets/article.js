// assets/article.js
console.log("JS chargé");
		import $ from 'jquery';

		$(document).ready(function() {
			
			// Système de "j'aime" en AJAX
			
			
			$('.like-button').on('click', function() {
				const $likeButton = $(this);
				const articleId = $likeButton.data('article-id');
				const emailLike = $('#user-data').data('email');


				$.ajax({
					url: `/article/${articleId}/like`,
					method: 'POST',
					dataType: 'json',
					data :{email:emailLike},
					success: function(response) {
						if (response.success) {
							if(response.liked === true){
								$likeButton.addClass('liked');
							}else{
								$likeButton.removeClass('liked');
							}
							// Mettre à jour le compteur de likes
							$likeButton.find('.likes-count').text(response.likesCount);
						}
					}
				});
			});

			
		});