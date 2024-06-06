<?php

namespace App\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{

    /**
     * The above PHP function is a constructor that takes in an instance of UrlGeneratorInterface and
     * Security as dependencies.
     */
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private Security $security,
    ) {
    }// End __construct().

    /**
     * The function checks if the user is fully authenticated and not an admin, then adds an error
     * message and redirects to the login page.
     * 
     * @param Request request The `$request` parameter in the `handle` function is an instance of the
     * `Request` class in Symfony. It represents an HTTP request that is being handled by the
     * application. This object contains all the information about the request, such as the request
     * method, headers, parameters, and more.
     * @param AccessDeniedException accessDeniedException The `AccessDeniedException` is an exception
     * that is thrown when a user tries to access a resource or perform an action for which they do not
     * have the necessary permissions or authorization. In the context of the `handle` function you
     * provided, it is used to handle cases where access is denied to a
     * 
     * @return ?Response A RedirectResponse is being returned with the URL generated for the 'login'
     * route.
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        if ($this->security->isGranted('IS_AUTHENTICATED_FULLY') === true && $this->security->isGranted('ROLE_ADMIN') === false) {
            $request->getSession()->getFlashBag()->add('error', 'Vous devez être administrateur pour accéder à cette page.');
        }
        return new RedirectResponse($this->urlGenerator->generate('login'));
        
    }// End handle().
}
