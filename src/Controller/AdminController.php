<?php

namespace App\Controller;

use App\Entity\Background;
use App\Entity\User;
use App\Form\BackgroundType;
use App\Form\UserType;
use App\Repository\BackgroundRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminController extends AbstractController {
	/**
	 * @Route("/administration/profil/{id}", name="admin.index",)
	 * @param UserPasswordEncoderInterface $password_encoder
	 * @param UserRepository $repository
	 *
	 * @param Request $request
	 * @param ObjectManager $manager
	 * @param Background $background
	 * @param User $user
	 *
	 * @param int $id
	 *
	 * @param BackgroundRepository $background_repository
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index( UserPasswordEncoderInterface $password_encoder, UserRepository $repository, Request $request, ObjectManager $manager, Background $background, User $user, int $id, BackgroundRepository $background_repository ) {

		$background_repository = $background_repository->find( 1 );

		$form_back = $this->createForm( BackgroundType::class, $background );
		$form_back->handleRequest( $request );
		if ( $form_back->isSubmitted() && $form_back->isValid() ) :
			// Encode the password (you could also do this via Doctrine listener)
			$manager->flush();
			$this->addFlash( 'success', 'Background mis à jour' );

			return $this->redirectToRoute( 'admin.index', [
				'id' => $user->getId()
			] );
		endif;

		$catherine = $repository->findCatherine( $id );

		$form = $this->createForm( UserType::class, $user );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() ) :
			// Encode the password (you could also do this via Doctrine listener)
			$password = $password_encoder->encodePassword( $catherine, $catherine->getPassword() );
			$user->setPassword( $password );

			$manager->persist( $catherine );
			$manager->flush();
			$this->addFlash( 'success', 'Profil mis à jour' );

			return $this->redirectToRoute( 'logout' );
		endif;

		return $this->render( 'admin/index.html.twig', [
			'background' => $background_repository,
			'user'       => $catherine,
			'form'       => $form->createView(),
			'form_back'  => $form_back->createView()
		] );
	}
}
