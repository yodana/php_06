<?php

namespace E05Bundle\Controller;

use E01Bundle\E01Bundle;
use E01Bundle\Entity\User;
use E03Bundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    public function getPostId($id){
        $table = [];
        $post = $this->getDoctrine()
        ->getRepository(Post::class);
        $r = $post->find($id);
        if ($r){
        array_push($table, [
            'id' => $r->getId(),
            'title' => $r->getTitle(),
            'date' => $r->getCreated()->format('Y-m-d H:i:s'),
            'content' => $r->getContent(),
            'creator' => $r->getUser()->getUserName(),
            'like' => $r->getLik(),
            'unlike' => $r->getUnlik()
        ]);
        }
        return $table;
    }

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
     * @Route("/e05/")
     */
    public function indexAction()
    {
        $reput = 0;
        $entityManager = $this->getDoctrine()->getManager();
        $qb = $entityManager->createQueryBuilder();
        $post = $qb
            ->select('a')
            ->from(Post::class, 'a')
            ->where('a.user = ' . $this->getUser()->getId())
            ->getQuery()->getResult();
        foreach($post as $p){
            $reput = $reput + ($p->getLik() - $p->getUnlik());
        }
        if ($user = $this->getUser()){
            return $this->render('E05Bundle:Default:index.html.twig',
            [
                "username" => $user->getUsername(),
                "post" => $this->getPost(),
                "reput" => $reput
            ]);
        }
        return $this->render('E05Bundle:Default:links.html.twig');
    }

     /**
     * @Route("/e05/post/{id}/")
     */
    public function post($id){
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        return $this->render('E05Bundle::post.html.twig', array(
            "post" => $this->getPostId($id),
            "message" => "",
        ));
    }
    /**
     * @Route("/e05/post/{id}/{event}")
     */
    public function likePost($id, $event){
        $message = "";
        $em = $this->getDoctrine()->getManager();
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        if ($event == "like" || $event == "unlike"){
            $post = $this->getDoctrine()
                ->getRepository(Post::class);
            $r = $post->find($id);
            if (in_array($id, $this->getUser()->getLikes()))
                $message = "Already put your opinion";
            else if ($event == "like"){
                $r->setLik($r->getLik() + 1);
                $this->getUser()->setLikes($id);
            }
            else if ($event == "unlike"){
                $r->setUnlik($r->getUnlik() + 1);
                $this->getUser()->setLikes($id);
            }
            $em->persist($r);
            $em->flush();
        }
        return $this->render('E05Bundle::post.html.twig', array(
            "post" => $this->getPostId($id),
            "message" => $message,
        ));
    }

}
