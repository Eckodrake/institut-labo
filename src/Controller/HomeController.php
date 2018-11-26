<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\BackgroundRepository;
use App\Repository\CategoryRepository;
use App\Repository\CreationRepository;
use App\Repository\PrestationRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {
	/**
	 * @Route("/", name="home.prestation.index")
	 * @param CategoryRepository $repository
	 *
	 * @param BackgroundRepository $background_repository
	 * @param Request $request
	 * @param ContactNotification $contact_notification
	 *
	 * @param ProductRepository $product_repository
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index( CategoryRepository $repository, BackgroundRepository $background_repository, Request $request, ContactNotification $contact_notification, ProductRepository $product_repository ) {
		$background = $background_repository->find( 1 );

		$contact = new Contact();
		$form    = $this->createForm( ContactType::class, $contact );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) :
			$contact_notification->notify( $contact );
			$this->addFlash( 'success', 'Votre message a bien été envoyé' );

			return $this->redirectToRoute( 'home.prestation.index' );
		endif;

		return $this->render( 'home/index.html.twig', [
			'background' => $background,
			'categories' => $repository->findAll(),
			'products'   => $product_repository->findLimit( 3 ),
			'form'       => $form->createView()
		] );
	}

	/**
	 * @Route("/prestations/{slug}-{id}", name="home.prestation.show", requirements={"slug": "[a-z0-9\-]*"})
	 * @param CategoryRepository $category
	 * @param PrestationRepository $prestation
	 * @param CreationRepository $creation_repository
	 * @param string $slug
	 * @param int $id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function show( CategoryRepository $category, PrestationRepository $prestation, CreationRepository $creation_repository, string $slug, int $id ) {
		$category    = $category->find( $id );
		$prestations = $prestation->findBy(
			[ 'category' => $id ]
		);
		$creation    = $creation_repository->findAll();

		if ( $category->getSlug() !== $slug || $category->getId() !== $id ) :
			return $this->redirectToRoute( 'home.prestation.show', [
				'id'   => $category->getId(),
				'slug' => $category->getSlug()
			], 301 );
		endif;

		return $this->render( 'home/show.html.twig', [
			'category'    => $category,
			'prestations' => $prestations,
			'creations'   => $creation
		] );
	}
}
