<?php

namespace App\Controller\Api;

use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\ArticleLike;
use App\Form\CommentForm;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleLikeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ArticleController extends AbstractController
{
	public function dataTable(Request $request, ArticleRepository $articleRepository): JsonResponse
	{
		$draw = $request->query->getInt('draw');
		$start = $request->request->getInt('start');
				$length = $request->request->getInt('length');
				$search = $request->request->all('search')['value'] ?? null;
				$orders = $request->request->all('order') ?? [];

				// Colonnes pour le tri
				$columns = [
					0 => 'a.id',
					1 => 'a.title',
					2 => 'categories',
					3 => 'commentsCount',
					4 => 'likesCount',
					5 => 'a.createdAt',
				];

				// Ordre de tri
				$orderColumn = $columns[$orders[0]['column'] ?? 0] ?? 'a.id';
				$orderDir = $orders[0]['dir'] ?? 'desc';

				// Récupération des données
				$results = $articleRepository->findForDataTable($start, $length, $search, $orderColumn, $orderDir);

				// Formatage des données pour DataTables
				$data = [];
				foreach ($results['data'] as $article) {
					$categoryNames = array_map(function($category) {
						return sprintf('%s', $category->getTitle());
					}, $article->getCategories()->toArray());

					$data[] = [
						'id' => $article->getId(),
						'title' => sprintf(
							'<a href="%s">%s</a>',
							$this->generateUrl('app_article_show', ['id' => $article->getId()]),
							htmlspecialchars($article->getTitle())
						),
						'categories' => implode(' ', $categoryNames),
						'commentsCount' => $article->getComments()->count(),
						'likesCount' => $article->getLikes()->count(),
						'createdAt' => $article->getCreatedAt()->format('d/m/Y H:i'),
						'actions' => $this->renderView('article/_actions.html.twig', [
							'article' => $article
						])
					];
				}

				// Réponse au format attendu par DataTables
				return new JsonResponse([
					'draw' => $draw,
					'recordsTotal' => $results['totalCount'],
					'recordsFiltered' => $results['filteredCount'],
					'data' => $data
				]);
			}

			#[Route('/articles/search', name: 'api_articles_search', methods: ['GET'])]
			public function search(Request $request, ArticleRepository $articleRepository): JsonResponse
			{
				$query = $request->query->get('q', '');

				if (strlen($query) < 2) {
					return new JsonResponse(['results' => []]);
				}

				$articles = $articleRepository->searchByTitle($query, 10);

				$results = [];
				foreach ($articles as $article) {
					$categoryNames = array_map(function($category) {
						return $category->getTitle();
					}, $article->getCategories()->toArray());

					$results[] = [
						'id' => $article->getId(),
						'title' => $article->getTitle(),
						'categories' => $categoryNames
					];
				}

				return new JsonResponse(['results' => $results]);
			}

			#[Route('/article/{id}/comment', name: 'api_article_comment', methods: ['POST'])]
			public function addComment(Article $article, Request $request, EntityManagerInterface $entityManager):JsonResponse
			{
				$comment = new Comment();
				$comment->setArticle($article);
				$comment->setCreateAt(\DateTime::createFromImmutable(new DateTimeImmutable()));

				$form = $this->createForm(CommentForm::class, $comment);
				$form->handleRequest($request);

				if ($form->isSubmitted() && $form->isValid()) {
					$entityManager->persist($comment);
					$entityManager->flush();

					return new JsonResponse([
						'success' => true,
						'commentHtml' => $this->renderView('comment/_comment.html.twig', [
							'comment' => $comment
						]),
						'commentsCount' => $article->getComments()->count()
					]);
				}

				// En cas d'erreur, renvoyer les erreurs du formulaire
				$errors = [];
				foreach ($form->getErrors(true) as $error) {
					$errors[] = $error->getMessage();
				}

				return new JsonResponse([
					'success' => false,
					'error' => count($errors) > 0 ? $errors[0] : 'Formulaire invalide'
				], Response::HTTP_BAD_REQUEST);
			}

			#[Route('/article/{id}/like', name: 'api_article_like', methods: ['POST'])]
			public function likeArticle(
				Article $article,
				Request $request,
				EntityManagerInterface $entityManager,
				ArticleLikeRepository $likeRepository
			): JsonResponse {
				$data = json_decode($request->getContent(), true);
				$emailLike = $data['email'] ?? null;

				if (!$emailLike) {
					return new JsonResponse([
						'success' => false,
						'error' => 'Email manquant'
					], Response::HTTP_BAD_REQUEST);
				}

				// Vérifier si l'utilisateur a déjà aimé cet article
				$existingLike = $likeRepository->findOneBy([
					'article' => $article,
					'emailLike' => $emailLike
				]);

				if ($existingLike) {
					// Toggle OFF : supprimer le like existant
					$entityManager->remove($existingLike);
					$entityManager->flush();

					return new JsonResponse([
						'success' => true,
						'liked' => false,
						'likesCount' => $article->getLikes()->count()
					]);
				} else {
					// Toggle ON : ajouter un nouveau like
					$like = new ArticleLike();
					$like->setArticle($article);
					$like->setEmailLike($emailLike);
					$like->setCreateAt(new \DateTimeImmutable());

					$entityManager->persist($like);
					$entityManager->flush();

					return new JsonResponse([
						'success' => true,
						'liked' => true,
						'likesCount' => $article->getLikes()->count()
					]);
				}
			}

			#[Route('/article/{id}/dejalike', name: 'api_article_dejalike', methods: ['POST'])]
			public function dejalikeArticle(
				Article $article,
				Request $request,
				ArticleLikeRepository $likeRepository
			): JsonResponse {
				$data = json_decode($request->getContent(), true);
				$emailLike = $data['email'] ?? null;
				// Vérifier si l'utilisateur a déjà aimé cet article
				$existingLike = $likeRepository->findOneBy([
					'article' => $article,
					'emailLike' => $emailLike
				]);

				return new JsonResponse([
					'success' => true,
					'liked' => $existingLike ? true : false,
				]);
			}

			

		}