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

    // Construct : Urlgenerator and security userd for handle 403.
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private Security $security,
    ) {
    }

    // Handle 403 and redirect if user is not admin.
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        if ($this->security->isGranted('IS_AUTHENTICATED_FULLY') === true && $this->security->isGranted('ROLE_ADMIN') === false) {
            $request->getSession()->getFlashBag()->add('error', 'Vous devez être administrateur pour accéder à cette page.');
        }
        return new RedirectResponse($this->urlGenerator->generate('login'));
    }
}
