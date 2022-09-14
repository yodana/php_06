<?php

namespace E01Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Controller
{
    /**
     * @Route("/e01/login/", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils)
    {
        if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > 60)) {
            session_unset(); 
            session_destroy(); 
        }
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        
        $errors = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('E01Bundle:Login:login.html.twig', array(
            "errors" => $errors,
            "lastUsername" =>$lastUsername
        ));
    }

     /**
     * @Route("/e01/logout/", name="logout")
     */
    public function logoutAction(){

    }
}
