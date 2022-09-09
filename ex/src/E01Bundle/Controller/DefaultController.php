<?php

namespace E01Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="/")
     */
    public function indexAction()
    {
        if ($this->isGranted('ROLE_USER') == true){
            $user = $this->getUser();
            return $this->render('E01Bundle:Default:index.html.twig',
            [
                "username" => $user->getUsername(),
            ]
        );
        }
        return $this->render('E01Bundle:Default:links.html.twig');
    }

}
