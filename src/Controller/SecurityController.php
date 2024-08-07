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


    /**
     * The loginAction function checks if the user is authenticated fully and displays login form with
     * error message if not.
     * 
     * @param Request $request the request of HTTP foundation component.
     * 
     * @param AuthenticationUtils $authenticationUtils Utility service that provides authentication error details and the last entered username.
     * 
     * @return Response The user is not authenticated fully, the method will return a rendered view of the
     *                  login form with the last username and any authentication error. If the user is already
     *                  authenticated, it will redirect to the homepage.
     */
    public function loginAction(AuthenticationUtils $authenticationUtils)
    {
        if ($this->isGranted("IS_AUTHENTICATED_FULLY") === false) {
            $error = $authenticationUtils->getLastAuthenticationError();
            $lastUsername = $authenticationUtils->getLastUsername();

            return $this->render(
                'security/login.html.twig',
                [
                    'last_username' => $lastUsername,
                    'error'         => $error,
                ]
            );
        }
        
        return $this->redirectToRoute("homepage");

    } // End loginAction().


    /**
     * @codeCoverageIgnore
     */
    #[Route('/login_check', name: 'login_check')]


    /**
     * Code for checking if user is correctly logged.
     *
     * @return void
     */
    public function loginCheck(): void
    {

        // This code is never executed.
    } // End loginCheck().


    /**
     * @codeCoverageIgnore
     */
    #[Route('/logout', name: 'logout')]


    /**
     * Function for logout url user.
     *
     * @return void
     */
    public function logoutCheck(): void
    {

        // This code is never executed.
    } // End logoutCheck().


}
