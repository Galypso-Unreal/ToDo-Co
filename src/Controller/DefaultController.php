<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{


    #[Route('/', name: 'homepage')]

    
    /**
     * The index function in PHP renders the default/index.html.twig template.
     *
     * @return Response `index` function is returning the rendered template `default/index.html.twig`.
     */
    public function index()
    {
        return $this->render('default/index.html.twig');

    }// End index().


}
// End class DefaultController.