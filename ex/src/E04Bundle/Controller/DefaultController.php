<?php

namespace E04Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    public $name = ['dog', 'frog',  'mouse', 'lion'];
    public function getPost(){
        $table = [];
        $post = $this->getDoctrine()
        ->getRepository(Post::class);
        $r = (array)$post->findAll();
        foreach($r as $t){
            array_push($table, [
                'id' => $t->getId(),
                'title' => $t->getTitle(),
                'date' => $t->getCreated()->format('Y-m-d H:i:s'),
                'creator' => $t->getUser()->getUserName(),
            ]);
        }
    return $table;
    }

    /**
     * @Route("/e04/", name="session")
     */
    public function indexAction()
    {
        if ($this->get('session')->get('time')){
            $time = time() - $this->get('session')->get('time');
        }
        else{
            $this->get('session')->set('time', time());
            $time = time() - $this->get('session')->get('time');
        }
        if ($user = $this->getUser()){
            return $this->render('E04Bundle:Default:index.html.twig',
            [
                "username" => $user->getUsername(),
            ]
        );
        }
        if (!isset($_SESSION['name'])){
            session_destroy();
            session_start();
            $_SESSION['start'] = time();
            $_SESSION['name'] = $this->name[rand(0, 3)];
        }
        if (isset($_SESSION['start']) && (time() - $_SESSION['start'] > 60)) {
            session_unset(); 
            session_destroy();        
        }
        if (isset($_SESSION['name'])){
            return $this->render('E04Bundle:Default:links.html.twig',
            [
                "username" => $_SESSION['name'],
                "time" => $time
            ]);
        }
        return $this->redirectToRoute('session');
    }
}
