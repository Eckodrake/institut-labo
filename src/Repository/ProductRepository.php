<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find( $id, $lockMode = null, $lockVersion = null )
 * @method Product|null findOneBy( array $criteria, array $orderBy = null )
 * @method Product[]    findAll()
 * @method Product[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class ProductRepository extends ServiceEntityRepository {
	public function __construct( RegistryInterface $registry ) {
		parent::__construct( $registry, Product::class );
	}

	/**
	 * @param int $limit
	 *
	 * @return mixed
	 */
	public function findLimit( int $limit ) {
		return $this->createQueryBuilder( 'p' )
		            ->setMaxResults( $limit )
		            ->getQuery()
		            ->getResult();
	}

	/**
	 * @param $value
	 * @param int|null $limit
	 *
	 * @return mixed
	 */
	public function findRand( $value, int $limit = null ) {

		return $this->createQueryBuilder( 'p' )
		            ->addSelect( 'RAND() as HIDDEN rand' )
		            ->andWhere( 'p.id != :val' )
		            ->setParameter( 'val', $value )
		            ->orderBy( 'rand' )
		            ->setMaxResults( $limit )
		            ->getQuery()
		            ->getResult();
	}

	// /**
	//  * @return Product[] Returns an array of Product objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('p.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/

	/*
	public function findOneBySomeField($value): ?Product
	{
		return $this->createQueryBuilder('p')
			->andWhere('p.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}
