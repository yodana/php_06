<?php

namespace E05Bundle\Controller;

use E01Bundle\Entity\User;
require_once(__DIR__ . '/../Form/UserForm.php');
use E05Bundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends Controller
{
    /**
     * @Route("/e05/register/")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > 60)) {
            session_unset(); 
            session_destroy(); 
            session_start();
            $_SESSION['start'] = time();
        }
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $em->persist($user);
            $em->flush();
            $this->get('session')->set('time', time());
            return $this->redirectToRoute('login');
        }
        return $this->render('E05Bundle:Register:register.html.twig', array(
            "form" => $form->createView()
        ));
    }

}
