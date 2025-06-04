$(document).ready(function() {
    const emailLike = $('#user-data').data('email');

    $('.like-button').each(function() {
        const $likeButton = $(this);
        const articleId = $likeButton.data('article-id');

        fetch(`/article/${articleId}/dejalike`, {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ email: emailLike })
        })
        .then(res => res.json())
		
        .then(data => {
			console.log(data);
            if (data.success && data.liked) {
                $likeButton.addClass('liked');
            }
        })
        .catch(console.error);
    });

    $('.like-button').on('click', function () {
        const $likeButton = $(this);
        const articleId = $likeButton.data('article-id');

        fetch(`/article/${articleId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ email: emailLike })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $likeButton.toggleClass('liked', data.liked);
                $likeButton.find('.likes-count').text(data.likesCount);
            } else {
                console.warn('Erreur côté serveur :', data.error);
            }
        })
        .catch(error => {
            console.error('Erreur lors de la requête de like :', error);
        });
    });
	
});
