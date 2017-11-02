<?php

namespace BetterknowBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use BetterknowBundle\Entity\Quizz;
use BetterknowBundle\Entity\Pack;
use BetterknowBundle\Form\GemType;

class PackController extends Controller
{   
    const MAX_PACKS_BDD = 100;
    
    /**
     * @Rest\View(serializerGroups={"pack"})
     * @Rest\Get("/users/{id}/packs")
     */
    public function getPacksAction(Request $request)
    {  
        $user = $this->em()
                ->getRepository('BetterknowBundle:User')
                ->find($request->get('id'));
        
        /* @var $user User */
        
        if (empty($user)) {
            return $this->notFound('User');
        }
        
        return $user->getPacks();
    }
    
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"pack"})
     * @Rest\Post("/users/{id}/pack")
     */
    public function postPackAction(Request $request)
    {
        $user = $this->em()->getRepository('BetterknowBundle:User')
                ->find($request->get('id'));
        /* @var $user User */
        
        if (empty($user)) {
            return $this->notFound('User ');
        }
        
        $pack = $this->em()->getRepository('BetterknowBundle:Pack')
                ->findAll();
        
        if(count($pack)> self::MAX_PACKS_BDD){
           $tPack = $this->takePack(array_shift($pack)->getId(),
                     array_pop($pack)->getId(), $user);
           $this->em()->flush();
           return $tPack;
        } else {
           $cPack = $this->createPack($user); 
           $this->em()->flush();
           return $cPack;
        }
    }
    
    
   // Functions // 
    private function createPack($user){
        $count = 0;
        $pack = new Pack();
        $question = $this->em()->getRepository('BetterknowBundle:Quizz')
                    ->findAll();
             
        if (empty($question)) {
            return $this->notFound('Questions ');
        }
        
        foreach ($question as $q) {
            // Change rule
            if(rand(0, 1) == 1){
                $pack->addQuizz($q);
                $count++;
            }
            if($count == 4){
                break;
            }
        }
        
        $user->addPack($pack);
        $this->em()->persist($pack);
        
        return $pack;
    }
    
    private function takePack($min, $max, $user){
        $pack = $this->em()->getRepository('BetterknowBundle:Pack')
                ->find(rand($min, $max));
        
        $idTrue = false;
        
        foreach ($user->getPacks() as $p) {
            if($p->getId() == $pack->getId()){
                $idTrue = true;
            }
        }
        
        if(!$idTrue){
            $user->addPack($pack);
        } else {
            $this->takePack($min, $max, $user);
        }
        
        return $pack;
    }
    
    private function em(){
        return $this->getDoctrine()->getManager();
    }
    
    private function notFound($param)
    {
        return \FOS\RestBundle\View\View::create(['message' => $param.' not found'], Response::HTTP_NOT_FOUND);
    }
}
