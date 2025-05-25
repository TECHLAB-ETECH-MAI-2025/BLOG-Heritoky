<?php

namespace App\Repository;

use App\Entity\ArticleLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ArticleLike>
 */
class ArticleLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleLike::class);
    }

    //    /**
    //     * @return ArticleLike[] Returns an array of ArticleLike objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ArticleLike
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

   public function search(?string $search = null, string $orderColumn = 'a.id', string $orderDir = 'DESC', int $start = 0, int $length = 10)
    {
        return $this->createQueryBuilder('a')
				->leftJoin('a.categories', 'c')
				->leftJoin('a.comments', 'com')
				->leftJoin('a.likes', 'l')
				->groupBy('a.id');

			// Appliquer la recherche si elle existe
			if ($search) {
				$qb->andWhere('a.title LIKE :search OR c.title LIKE :search')
				   ->setParameter('search', '%' . $search . '%');
			}

			// Compter le nombre total d'articles
			$totalCount = $this->createQueryBuilder('a')
				->select('COUNT(a.id)')
				->getQuery()
				->getSingleScalarResult();

			// Compter le nombre d'articles filtrés
			$filteredCountQb = clone $qb;
			$filteredCount = $filteredCountQb
				->select('COUNT(DISTINCT a.id)')
				->getQuery()
				->getSingleScalarResult();

			// Appliquer le tri
			if ($orderColumn === 'commentsCount') {
				$qb->addSelect('COUNT(com.id) as commentsCount')
				   ->orderBy('commentsCount', $orderDir);
			} elseif ($orderColumn === 'likesCount') {
				$qb->addSelect('COUNT(l.id) as likesCount')
				   ->orderBy('likesCount', $orderDir);
			} elseif ($orderColumn === 'categories') {
				$qb->orderBy('c.title', $orderDir);
			} else {
				$qb->orderBy($orderColumn, $orderDir);
			}

			// Appliquer la pagination
			$qb->setFirstResult($start)
			   ->setMaxResults($length);

			return [
				'data' => $qb->getQuery()->getResult(),
				'totalCount' => $totalCount,
				'filteredCount' => $filteredCount
			];
	}

		/**
		 * Recherche des articles par titre
		 */
public function searchByTitle(string $query, int $limit = 10): array
		{
			return $this->createQueryBuilder('a')
				->leftJoin('a.categories', 'c')
				->where('a.title LIKE :query')
				->setParameter('query', '%' . $query . '%')
				->orderBy('a.createdAt', 'DESC')
				->setMaxResults($limit)
				->getQuery()
				->getResult();
		}
	
    }
