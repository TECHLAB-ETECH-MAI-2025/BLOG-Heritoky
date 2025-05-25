<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    //    /**
    //     * @return Article[] Returns an array of Article objects
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

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findLast():array{
        return $this->findBy([],['createAt'=>'DESC'],4);
    }
	public function search(string $query, int $limit = 10): array
	{
		$qb = $this->createQueryBuilder('a')
			->leftJoin('a.categories', 'c')
			->addSelect('c')
			->where('a.title LIKE :query OR c.title LIKE :query')
			->setParameter('query', '%' . $query . '%')
			->orderBy('a.createAt', 'DESC')
			->setMaxResults($limit);

		return $qb->getQuery()->getResult();
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
            ->orderBy('a.createAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
	
}