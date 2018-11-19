<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController {
	/**
	 * @Route("/login", name="login")
	 * @param AuthenticationUtils $utils
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function login( AuthenticationUtils $utils ) {
		$last_username = $utils->getLastUsername();
		$error         = $utils->getLastAuthenticationError();

		return $this->render( 'security/login.html.twig', [
			'last_username' => $last_username,
			'error'         => $error
		] );
	}
}
