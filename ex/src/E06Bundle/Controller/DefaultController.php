<?php

namespace E06Bundle\Controller;

use DateTime;
use E03Bundle\Entity\Post;
use E01Bundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\HttpFoundation\Request;

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
            'unlike' => $r->getUnlik(),
            'lastUser' => $r->getLastuser() != NULL ? $r->getLastuser()->getUsername() : NULL ,
            'lastCreated' => $r->getLastcreated()->format('Y-m-d H:i:s')
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
     * @Route("/e06/")
     */
    public function indexAction()
    {
        if ($user = $this->getUser()){
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
            return $this->render('E06Bundle:Default:index.html.twig',
            [
                "username" => $user->getUsername(),
                "post" => $this->getPost(),
                "reput" => $reput
            ]);
        }
        return $this->render('E06Bundle:Default:links.html.twig');
    }

     /**
     * @Route("/e06/post/{id}/")
     */
    public function post($id){
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        return $this->render('E06Bundle::post.html.twig', array(
            "post" => $this->getPostId($id),
            "message" => "",
        ));
    }

    /**
     * @Route("/e06/post/modif/{id}/")
     */
    public function postModif($id, Request $request){
        if (!$this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('homepage');
        }
        $post = $this->getDoctrine()
        ->getRepository(Post::class);
        $r = $post->find($id);
        if (!$r || !is_numeric($id))
        {
            return $this->render('E06Bundle::post.html.twig', array(
            "post" => $this->getPostId(0),
            "message" => "",
        ));

        }
        $form = $this->createFormBuilder()
        ->add('content', TextType::class,
        [   
            'attr' => array(
                'value' => $r->getContent()
            )
        ])
        ->add('Modifier', SubmitType::class)
        ->getForm();
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()){
            $r->setContent($form["content"]->getData());
            $r->setLastuser($this->getUser());
            $r->setLastcreated(new DateTime(date('Y-m-d H:i:s')));
            $em = $this->getDoctrine()->getManager();
            $em->persist($r);
            $em->flush();
            return $this->render('E06Bundle:Default:index.html.twig', array(
                "username" => $this->getUser()->getUsername(),
                "post" => $this->getPost(),
                "reput" => ""
            ));
        }
        return $this->render('E06Bundle::modif.html.twig', array(
            "form" => $form->createView(),
        ));
    }
}
