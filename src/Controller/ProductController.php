<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController {
	/**
	 * @Route("/products", name="product.index")
	 * @param ProductRepository $product_repository
	 *
	 * @param PaginatorInterface $paginator
	 *
	 * @param Request $request
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index( ProductRepository $product_repository, PaginatorInterface $paginator, Request $request ) {
		$products = $paginator->paginate(
			$product_repository->findAll(),
			$request->query->getInt( 'page', 1 ),
			9
		);

		return $this->render( 'product/index.html.twig', [
			'products' => $products
		] );
	}

	/**
	 * @Route("/products/{slug}-{id}", name="product.show", requirements={"slug": "[a-z0-9\-]*"})
	 * @param string $slug
	 * @param int $id
	 * @param ProductRepository $product_repository
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function show( string $slug, int $id, ProductRepository $product_repository ) {
		$product = $product_repository->find( $id );

		$other_product = $product_repository->findRand( $product, 4 );

		if ( $product->getSlug() !== $slug || $product->getId() !== $id ) :
			return $this->redirectToRoute( 'home.prestation.show', [
				'id'   => $product->getId(),
				'slug' => $product->getSlug()
			], 301 );
		endif;

		return $this->render( 'product/show.html.twig', [
			'product'       => $product,
			'other_product' => $other_product
		] );
	}
}
