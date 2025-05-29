// assets/article-list.js

import $ from 'jquery';
import 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.css';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.css';

$(document).ready(function () {
    $('#myTable').DataTable({
        responsive: true,
        paging:false,
    });
    const $searchInput = $('#search-article');
	const $searchResults = $('#search-results');
	let searchTimeout;

    $searchInput.on('input', function() {
			const query = $(this).val().trim();

			// Effacer le timeout précédent
			clearTimeout(searchTimeout);

			if (query.length < 2) {
				$searchResults.removeClass('show').html('');
				return;
			}

			// Définir un délai avant d'envoyer la requête
			searchTimeout = setTimeout(() => {
				$.ajax({
					url: '/articles/search',
					method: 'GET',
					data: { q: query },
					dataType: 'json',
					success: function(response) {
						if (response.results && response.results.length > 0) {
							let html = '';

							response.results.forEach(article => {
                                html += `
                                    <div class="search-item dropdown-item" data-id="${article.id}">
                                        <strong>${article.title}</strong><br>
                                        <small>${article.categories.join(', ')}</small>
                                    </div>
                                `;
                            });

							$searchResults.html(html).addClass('show');
						} else {
							$searchResults.html('Aucun résultat trouvé').addClass('show');
						}
					}
				});
			}, 300);
		});

		// Cliquer sur un résultat de recherche
		$(document).on('click', '.search-item', function() {
			const articleId = $(this).data('id');
			if (articleId) {
				window.location.href = `/article/${articleId}`;
			}
		});

		// Cacher les résultats quand on clique ailleurs
		$(document).on('click', function(e) {
			if (!$(e.target).closest('.search-container').length) {
				$searchResults.removeClass('show');
			}
        });

});
