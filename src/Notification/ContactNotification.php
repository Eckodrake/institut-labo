<?php

namespace App\Notification;

use App\Entity\Contact;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Twig\Environment;

class ContactNotification extends AbstractController {
	/**
	 * @var \Swift_Mailer
	 */
	private $mailer;
	/**
	 * @var Environment
	 */
	private $environment;
	/**
	 * @var UserRepository
	 */
	private $repository;

	public function __construct( \Swift_Mailer $mailer, Environment $environment, UserRepository $repository ) {
		$this->mailer      = $mailer;
		$this->environment = $environment;
		$this->repository = $repository;
	}

	public function notify( Contact $contact ) {
		try {

			$repository = $this->repository->findOneBy( ['id' => 2] );
			$user = $repository->getEmail() != null ? $repository->getEmail() : 'contact@webdeval.be' ;

			$message = ( new \Swift_Message( 'Contact institut' ) )
				->setFrom( 'noreply@institut.be' )
				->setTo( $user )
				->setReplyTo( $contact->getEmail() )
				->setBody( $this->environment->render( 'contact/contact.html.twig', [
					'contact' => $contact
				] ) );
			$this->mailer->send( $message );
		} catch ( \Twig_Error_Loader $e ) {
		} catch ( \Twig_Error_Runtime $e ) {
		} catch ( \Twig_Error_Syntax $e ) {
		}
	}
}