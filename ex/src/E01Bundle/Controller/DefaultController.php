<?php

namespace E01Bundle\Controller;

use E03Bundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
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
     * @Route("e01/", name="homepage")
     */
    public function indexAction()
    {
        if ($user = $this->getUser()){
            return $this->render('E01Bundle:Default:index.html.twig',
            [
                "username" => $user->getUsername(),
                "reput" => "",
            ]);
        }
        return $this->render('E01Bundle:Default:links.html.twig');
    }

}
