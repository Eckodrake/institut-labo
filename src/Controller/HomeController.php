<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\CategoryRepository;
use App\Repository\PrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController {
	/**
	 * @Route("/", name="home.prestation.index")
	 * @param CategoryRepository $repository
	 *
	 * @param Request $request
	 * @param ContactNotification $contact_notification
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index( CategoryRepository $repository, Request $request, ContactNotification $contact_notification) {
		$contact = new Contact();
		$form    = $this->createForm( ContactType::class, $contact );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) :
			$contact_notification->notify( $contact );
			$this->addFlash( 'success', 'Votre message a bien été envoyé');

			return $this->redirectToRoute( 'home.prestation.index' );
		endif;

		return $this->render( 'home/index.html.twig', [
			'categories' => $repository->findAll(),
			'form'       => $form->createView()
		] );
	}

	/**
	 * @Route("/prestations/{slug}-{id}", name="home.prestation.show", requirements={"slug": "[a-z0-9\-]*"})
	 * @param CategoryRepository $category
	 * @param PrestationRepository $prestation
	 * @param string $slug
	 * @param int $id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function show( CategoryRepository $category, PrestationRepository $prestation, string $slug, int $id ) {
		$category    = $category->find( $id );
		$prestations = $prestation->findBy(
			[ 'category' => $id ]
		);

		if ( $category->getSlug() !== $slug || $category->getId() !== $id ) :
			return $this->redirectToRoute( 'home.prestation.show', [
				'id'   => $category->getId(),
				'slug' => $category->getSlug()
			], 301 );
		endif;

		return $this->render( 'home/show.html.twig', [
			'category'    => $category,
			'prestations' => $prestations
		] );
	}
}
