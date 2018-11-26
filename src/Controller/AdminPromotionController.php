<?php

namespace App\Controller;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminPromotionController extends AbstractController {
	/**
	 * @Route("/administration/promotions", name="admin.promotion.index")
	 * @param PromotionRepository $promotion_repository
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index( PromotionRepository $promotion_repository ) {
		$promotion_repository = $promotion_repository->findByDate();
		$now                  = new \DateTime( 'now' );

		return $this->render( 'admin/promotion/index.html.twig', [
			'promotions' => $promotion_repository,
			'now'        => $now
		] );
	}

	/**
	 * @Route("/administration/promotions/{slug}-{id}", name="admin.promotion.edit", requirements={"slug": "[a-z0-9\-]*"}, methods={"GET|POST"})
	 *
	 * @param PromotionRepository $promotion_repository
	 *
	 * @param Promotion $promotion
	 * @param Request $request
	 * @param ObjectManager $em
	 * @param int $id
	 * @param string $slug
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function edit( PromotionRepository $promotion_repository, Promotion $promotion, Request $request, ObjectManager $em, int $id, string $slug ) {
		$promotion_repository = $promotion_repository->find( $id );

		$form = $this->createForm( PromotionType::class, $promotion );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) :
			$em->flush();
			$this->addFlash( 'success', 'Promotion modifiée' );

			return $this->redirectToRoute( 'admin.promotion.index' );
		endif;

		if ( $promotion_repository->getSlug() !== $slug || $promotion_repository->getId() !== $id ) :
			return $this->redirectToRoute( 'admin.category.edit', [
				'id'   => $promotion_repository->getId(),
				'slug' => $promotion_repository->getSlug()
			], 301 );
		endif;

		return $this->render( 'admin/promotion/edit.html.twig', [
			'promotion' => $promotion_repository,
			'form'      => $form->createView()

		] );
	}

	/**
	 * @Route("/administration/promotions/new", name="admin.promotion.new")
	 *
	 * @param Request $request
	 *
	 * @param ObjectManager $em
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function new( Request $request, ObjectManager $em ) {
		$promotion = new Promotion();
		$form      = $this->createForm( PromotionType::class, $promotion );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) :
			$em->persist( $promotion );
			$em->flush();
			$this->addFlash( 'success', 'Promotion ajoutée' );

			return $this->redirectToRoute( 'admin.promotion.index' );
		endif;

		return $this->render( 'admin/promotion/new.html.twig', [
			'form'      => $form->createView(),
			'promotion' => $promotion
		] );
	}

	/**
	 * @Route("/administration/promotions/{slug}-{id}", name="admin.promotion.delete", requirements={"slug": "[a-z0-9\-]*"}, methods={"DELETE"})
	 * @param int $id
	 * @param PromotionRepository $promotion_repository
	 * @param Promotion $promotion
	 * @param ObjectManager $em
	 *
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deletePrestation( int $id, PromotionRepository $promotion_repository, Promotion $promotion, ObjectManager $em, Request $request ) {
		$promotion_repository = $promotion_repository->find( $id );

		if ( $this->isCsrfTokenValid( 'delete' . $promotion_repository->getId(), $request->get( '_token' ) ) ) :
			$em->remove( $promotion );
			$em->flush();
			$this->addFlash( 'success', 'Promotion supprimée' );
		endif;

		return $this->redirectToRoute( 'admin.promotion.index' );
	}
}
