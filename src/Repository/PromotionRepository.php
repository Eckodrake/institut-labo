<?php

namespace App\Repository;

use App\Entity\Promotion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Promotion|null find( $id, $lockMode = null, $lockVersion = null )
 * @method Promotion|null findOneBy( array $criteria, array $orderBy = null )
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy( array $criteria, array $orderBy = null, $limit = null, $offset = null )
 */
class PromotionRepository extends ServiceEntityRepository {
	public function __construct( RegistryInterface $registry ) {
		parent::__construct( $registry, Promotion::class );
	}

	public function findByMonth() {
		return $this->createQueryBuilder( 'd' )
		            ->andWhere( 'd.datetime >= :val' )
		            ->andWhere( 'd.type = 1' )
		            ->setParameter( 'val', new \DateTime( 'now' ) )
		            ->getQuery()
		            ->getResult();
	}

	public function findByContest() {
		return $this->createQueryBuilder( 'd' )
		            ->andWhere( 'd.datetime >= :val' )
		            ->andWhere( 'd.type = 2' )
		            ->setParameter( 'val', new \DateTime( 'now' ) )
		            ->getQuery()
		            ->getResult();
	}

	public function findByDate() {
		return $this->createQueryBuilder( 'd' )
		            ->orderBy( 'd.datetime', 'DESC' )
		            ->getQuery()
		            ->getResult();
	}

	// /**
	//  * @return Promotion[] Returns an array of Promotion objects
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
	public function findOneBySomeField($value): ?Promotion
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
