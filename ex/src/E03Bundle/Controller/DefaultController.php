<?php

namespace E03Bundle\Controller;

use DateTime;
use E03Bundle\Entity\Post;
require_once('../src/E03Bundle/Form/PostForm.php');
use E03Bundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
        ]);
        }
        return $table;
    }


    /**
     * @Route("/e03/")
     */
    public function indexAction()
    {
        if ($user = $this->getUser()){
        return $this->render('E03Bundle:Default:index.html.twig',
        [
            "username" => $user->getUsername(),
            "post" => $this->getPost()
        ]);
    }
    return $this->render('E03Bundle:Default:links.html.twig', [
        "post" => $this->getPost()
    ]);
    }

    /**
     * @Route("/e03/create/")
     */
    public function postCreate(Request $request)
    {
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        $em = $this->getDoctrine()->getManager();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $post->setUser($this->getUser());
            $post->setCreated(new DateTime(date('Y-m-d H:i:s')));
            $em->persist($post);
            $em->flush();
            $this->get('session')->set('time', time());         
            return $this->redirectToRoute('homepage');
        }
        return $this->render('E03Bundle:Default:create.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/e03/post/{id}/")
     */
    public function post($id){
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        return $this->render('E03Bundle::post.html.twig', array(
            "post" => $this->getPostId($id),
        ));
    }
}
