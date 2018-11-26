<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminProductController extends AbstractController {
	/**
	 * @Route("/administration/products", name="admin.product.index")
	 * @param ProductRepository $product_repository
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index( ProductRepository $product_repository ) {
		$products = $product_repository->findAll();

		return $this->render( 'admin/product/index.html.twig', [
			'products' => $products
		] );
	}

	/**
	 * @Route("/administration/products/{slug}-{id}", name="admin.product.edit", requirements={"slug": "[a-z0-9\-]*"}, methods={"GET|POST"})
	 * @param ProductRepository $product_repository
	 *
	 * @param Product $product
	 * @param Request $request
	 * @param ObjectManager $em
	 * @param string $slug
	 * @param int $id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function edit( ProductRepository $product_repository, Product $product, Request $request, ObjectManager $em, string $slug, int $id ) {
		$product_repository = $product_repository->find( $id );

		$form = $this->createForm( ProductType::class, $product );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) :
			$em->flush();
			$this->addFlash( 'success', 'Produit modifié' );

			return $this->redirectToRoute( 'admin.product.index' );
		endif;

		if ( $product->getSlug() !== $slug || $product->getId() !== $id ) :
			return $this->redirectToRoute( 'admin.product.edit', [
				'id'   => $product->getId(),
				'slug' => $product->getSlug()
			], 301 );
		endif;

		return $this->render( 'admin/product/edit.html.twig', [
			'product' => $product_repository,
			'form'    => $form->createView()
		] );
	}

	/**
	 * @Route("/administration/products/new", name="admin.product.new")
	 *
	 * @param Request $request
	 * @param ObjectManager $em
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function new( Request $request, ObjectManager $em ) {
		$product = new Product();
		$form    = $this->createForm( ProductType::class, $product );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) :
			$em->persist( $product );
			$em->flush();
			$this->addFlash( 'success', 'Produit ajouté' );

			return $this->redirectToRoute( 'admin.product.index' );
		endif;

		return $this->render( 'admin/product/new.html.twig', [
			'product' => $product,
			'form'    => $form->createView()
		] );
	}

	/**
	 * @Route("/administration/products/{slug}-{id}", name="admin.product.delete", requirements={"slug": "[a-z0-9\-]*"}, methods={"DELETE"})
	 *
	 * @param int $id
	 * @param ProductRepository $product_repository
	 * @param Product $product
	 * @param ObjectManager $em
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function delete( int $id, ProductRepository $product_repository, Product $product, ObjectManager $em, Request $request ) {
		$product_repository = $product_repository->find( $id );

		if ( $this->isCsrfTokenValid( 'delete' . $product_repository->getId(), $request->get( '_token' ) ) ) :
			$em->remove( $product );
			$em->flush();
			$this->addFlash( 'success', 'Produit supprimé' );
		endif;

		return $this->redirectToRoute( 'admin.product.index' );
	}
}
