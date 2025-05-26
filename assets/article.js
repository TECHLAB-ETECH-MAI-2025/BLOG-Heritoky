// assets/article.js
console.log("JS chargé");
		import $ from 'jquery';

		$(document).ready(function() {
			
			// Système de "j'aime" en AJAX
			
			
			$('.like-button').on('click', function() {
				const $likeButton = $(this);
				const articleId = $likeButton.data('article-id');


				$.ajax({
					url: `/article/${articleId}/like`,
					method: 'POST',
					dataType: 'json',
					success: function(response) {
						if (response.success) {
							// Mettre à jour l'état du bouton
							$likeButton.toggleClass('liked', response.liked);

							// Mettre à jour le compteur de likes
							$likeButton.find('.likes-count').text(response.likesCount);
						}
					}
				});
			});

			
		});