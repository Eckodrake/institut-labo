<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
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
	 * @Route("/administration/categories/{slug}-{id}", name="admin.category.edit", requirements={"slug": "[a-z0-9\-]*"})
	 * @param CategoryRepository $category_repository
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
	public function edit( CategoryRepository $category_repository, Category $category, Request $request, string $slug, int $id, ObjectManager $em ) {
		$category_repository = $category_repository->find( $id );

		if ( $category_repository->getSlug() !== $slug || $category_repository->getId() !== $id ) :
			return $this->redirectToRoute( 'admin.category.edit', [
				'id'   => $category_repository->getId(),
				'slug' => $category_repository->getSlug()
			], 301 );
		endif;

		$form = $this->createForm( CategoryType::class, $category );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) :
			$em->flush();

			return $this->redirectToRoute( 'admin.category.index' );
		endif;

		return $this->render( 'admin/category/edit.html.twig', [
			'category' => $category_repository,
			'form'     => $form->createView()
		] );
	}
}
