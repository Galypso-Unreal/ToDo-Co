<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    // Login function for user on login page.
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        if ($this->isGranted("IS_AUTHENTICATED_FULLY") === false) {
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render('security/login.html.twig', array(
                'last_username' => $lastUsername,
                'error'         => $error,
            ));
        } else {
            return $this->redirectToRoute("homepage");
        }
    }

    /**
     * @codeCoverageIgnore
     */
    #[Route('/login_check', name: 'login_check')]
    // Code for checking if user is correctly logged.
    public function loginCheck(): void
    {
        // This code is never executed.
    }

    /**
     * @codeCoverageIgnore
     */
    #[Route('/logout', name: 'logout')]
    // Function for logout url user.
    public function logoutCheck(): void
    {
        // This code is never executed.
    }
}
