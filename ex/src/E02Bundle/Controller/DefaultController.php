<?php

namespace E02Bundle\Controller;

use E01Bundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    public function getTable(){
        $table = [];
        $a = ["id", "username"];
        $post = $this->getDoctrine()
        ->getRepository(User::class);
        $r = (array)$post->findAll();
        foreach($r as $t){
            $i = 0;
            $tab = [];
            foreach((array)$t as $e){
                if ($i < 2)
                    $tab[$a[$i]] = $e;
                $i++;
            }
            array_push($table, [
                'id' => $tab["id"],
                'username' => $tab["username"],
            ]);
        }
        return $table;
    }

    
    /**
     * @Route("/e02/admin/", name="admin")
     */
    public function indexAction()
    {
        $table = $this->getTable();
        return $this->render('E02Bundle:Default:index.html.twig',[
            'message' => "",
            'table' => $table
        ]);
    }

    /**
     * @Route("/e02/delete/{id}/")
     */
    public function delete($id){
        $table = $this->getTable();
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Unable to access this page!');
        if(!is_numeric($id) || $id == $this->getUser()->getId()){
            return $this->render('E02Bundle:Default:index.html.twig',[
                'message' => "This id is not possible.",
                'table' => $table
            ]);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $product = $repository->find($id);
        if (!$product){
            return $this->render('E02Bundle:Default:index.html.twig',[
                'message' => "This id doesn't exist.",
                'table' => $table
            ]);
        }
        $entityManager->remove($product);
        $entityManager->flush();
        $this->get('session')->set('time', time());
        return $this->render('E02Bundle:Default:index.html.twig',[
            'message' => "User bien supprimé",
            'table' => $this->getTable()
        ]);
    }
}
