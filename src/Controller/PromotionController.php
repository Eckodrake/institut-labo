<?php

namespace App\Controller;

use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PromotionController extends AbstractController {
	/**
	 * @Route("/promotions/promo-du-mois", name="month.promotion.index")
	 * @param PromotionRepository $promotion_repository
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function month( PromotionRepository $promotion_repository ) {

		$promotion_repository = $promotion_repository->findByMonth();

		return $this->render( 'promotion/month/index.html.twig', [
			'promotions' => $promotion_repository
		] );
	}

	/**
	 * @Route("/promotions/concours", name="contest.promotion.index")
	 * @param PromotionRepository $promotion_repository
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function contest( PromotionRepository $promotion_repository ) {

		$promotion_repository = $promotion_repository->findByContest();

		return $this->render( 'promotion/contest/index.html.twig', [
			'promotions' => $promotion_repository
		] );
	}
}
