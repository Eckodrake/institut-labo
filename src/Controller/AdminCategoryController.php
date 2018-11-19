<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Prestation;
use App\Form\CategoryType;
use App\Form\PrestationType;
use App\Repository\CategoryRepository;
use App\Repository\PrestationRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AbstractController {
	/**
	 * @Route("/administration/categories", name="admin.category.index")
	 * @param CategoryRepository $category
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index( CategoryRepository $category ) {
		$categories = $category->findAll();

		return $this->render( 'admin/category/index.html.twig', [
			'categories' => $categories
		] );
	}

	/**
	 * @Route("/administration/categories/new", name="admin.category.new")
	 * @param Request $request
	 * @param ObjectManager $em
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function new( Request $request, ObjectManager $em ) {
		$category = new Category();
		$form     = $this->createForm( CategoryType::class, $category );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) :
			$em->persist( $category );
			$em->flush();
			$this->addFlash( 'success', 'Catégorie ajoutée');

			return $this->redirectToRoute( 'admin.category.index' );
		endif;

		return $this->render( 'admin/category/new.html.twig', [
			'category' => $category,
			'form'     => $form->createView()
		] );
	}

	/**
	 * @Route("/administration/categories/{slug}-{id}", name="admin.category.edit", requirements={"slug": "[a-z0-9\-]*"})
	 * @param CategoryRepository $category_repository
	 * @param PrestationRepository $prestation_repository
	 * @param Category $category
	 *
	 * @param Request $request
	 *
	 * @param string $slug
	 * @param int $id
	 *
	 * @param ObjectManager $em
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function edit( CategoryRepository $category_repository, PrestationRepository $prestation_repository, Category $category, Request $request, string $slug, int $id, ObjectManager $em ) {
		$category_repository   = $category_repository->find( $id );
		$prestation_repository = $prestation_repository->findBy( [ 'category' => $id ] );

		if ( $category_repository->getSlug() !== $slug || $category_repository->getId() !== $id ) :
			return $this->redirectToRoute( 'admin.category.edit', [
				'id'   => $category_repository->getId(),
				'slug' => $category_repository->getSlug()
			], 301 );
		endif;

		// Category form
		$form = $this->createForm( CategoryType::class, $category );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) :
			$em->flush();
			$this->addFlash( 'success', 'Catégorie modifiée');

			return $this->redirectToRoute( 'admin.category.index' );
		endif;

		return $this->render( 'admin/category/edit.html.twig', [
			'category'    => $category_repository,
			'prestations' => $prestation_repository,
			'form'        => $form->createView()
		] );
	}

	/**
	 * @Route("/administration/categories/{slug}-{id}/new-prestation", name="admin.prestation.new", requirements={"slug": "[a-z0-9\-]*"})
	 * @param Request $request
	 *
	 * @param ObjectManager $em
	 *
	 * @param CategoryRepository $category_repository
	 *
	 * @param string $slug
	 * @param int $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function newPrestation( Request $request, ObjectManager $em, CategoryRepository $category_repository, string $slug, int $id ) {
		$category_repository = $category_repository->find( $id );

		if ( $category_repository->getSlug() !== $slug || $category_repository->getId() !== $id ) :
			return $this->redirectToRoute( 'admin.category.edit', [
				'id'   => $category_repository->getId(),
				'slug' => $category_repository->getSlug()
			], 301 );
		endif;

		$prestation = new Prestation();
		$prestation->setCategory( $category_repository );
		$form = $this->createForm( PrestationType::class, $prestation );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) :
			$em->persist( $prestation );
			$em->flush();
			$this->addFlash( 'success', 'Prestation ajoutée');

			return $this->redirectToRoute( 'admin.category.edit', [
				'slug' => $category_repository->getSlug(),
				'id'   => $category_repository->getId()
			] );
		endif;

		return $this->render( 'admin/prestation/new.html.twig', [
			'category' => $category_repository,
			'form'     => $form->createView()
		] );
	}

	/**
	 * @Route("/administration/prestations/{slug}-{id}", name="admin.prestation.edit", requirements={"slug": "[a-z0-9\-]*"}, methods={"GET|POST"})
	 * @param string $slug
	 * @param int $id
	 * @param PrestationRepository $prestation_repository
	 *
	 * @param Prestation $prestation
	 * @param Request $request
	 *
	 * @param ObjectManager $em
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function editPrestation( string $slug, int $id, PrestationRepository $prestation_repository, Prestation $prestation, Request $request, ObjectManager $em ) {
		$prestation_repository = $prestation_repository->find( $id );

		if ( $prestation_repository->getSlug() !== $slug || $prestation_repository->getId() !== $id ) :
			return $this->redirectToRoute( 'admin.prestation.edit', [
				'id'   => $prestation_repository->getId(),
				'slug' => $prestation_repository->getSlug()
			], 301 );
		endif;

		// Prestation form
		$form = $this->createForm( PrestationType::class, $prestation );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) :
			$em->flush();
			$this->addFlash( 'success', 'Prestation modifiée');

			return $this->redirectToRoute( 'admin.category.index' );
		endif;

		return $this->render( 'admin/prestation/edit.html.twig', [
			'prestation' => $prestation_repository,
			'form'       => $form->createView()
		] );
	}

	/**
	 * @Route("/administration/prestations/{slug}-{id}", name="admin.prestation.delete", requirements={"slug": "[a-z0-9\-]*"}, methods={"DELETE"})
	 * @param int $id
	 * @param PrestationRepository $prestation_repository
	 * @param Prestation $prestation
	 * @param ObjectManager $em
	 *
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deletePrestation( int $id, PrestationRepository $prestation_repository, Prestation $prestation, ObjectManager $em, Request $request ) {
		$prestation_repository = $prestation_repository->find( $id );

		if ($this->isCsrfTokenValid( 'delete' . $prestation_repository->getId(), $request->get('_token'))) :
			$em->remove( $prestation );
			$em->flush();
			$this->addFlash( 'success', 'Prestation supprimée');
		endif;

		return $this->redirectToRoute( 'admin.category.index' );
	}
}
