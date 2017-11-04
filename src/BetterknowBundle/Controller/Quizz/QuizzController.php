<?php

namespace BetterknowBundle\Controller\Quizz;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use BetterknowBundle\Entity\Quizz;
use BetterknowBundle\Form\QuizzType;
use Doctrine\Common\Collections\ArrayCollection;

class QuizzController extends Controller
{
    
    const MIN_FRIEND = 5;
    
    /**
     * @Rest\View(serializerGroups={"quizz"})
     * @Rest\Get("/quizz")
     */
    public function getQuizzsAction(Request $request)
    {  
        $quizz = $this->em()
                ->getRepository('BetterknowBundle:Quizz')
                ->findAll();
        
        /* @var $quizz Quizz */
        
        if (empty($quizz)) {
            return $this->notFound('Quizz');
        }
        
        return $quizz;
    }
    
    /**
     * @Rest\View(serializerGroups={"quizz"})
     * @Rest\Get("/quizz/{id}")
     */
    public function getQuizzAction(Request $request)
    {  
        $quizz = $this->em()
                ->getRepository('BetterknowBundle:Quizz')
                ->find($request->get('id'));
        
        /* @var $quizz Quizz */
        
        if (empty($quizz)) {
            return $this->notFound('Quizz');
        }
        
        return $quizz;
    }
    
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"quizz"})
     * @Rest\Post("/quizz")
     */
    public function postQuizzAction(Request $request)
    {
        $quizz = new Quizz();
        
        $form = $this->createForm(QuizzType::class, $quizz);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->em();
            $em->persist($quizz);
            $em->flush();
            return $quizz;
        } else {
            return $form;
        }
    }
    
    /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/quizz/{id}")
     */
    public function removeQuizzAction(Request $request)
    {
        $quizz = $this->em()->getRepository('BetterknowBundle:Quizz')
                    ->find($request->get('id'));

        if (empty($quizz)) {
            return $this->notFound('Quizz');
        }
        
        $this->em()->remove($quizz);
        $this->em()->flush();
        
        return $this->succes();
    }
    
    
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"response"})
     * @Rest\Get("/responses/{id_user}")
     */
    public function getChoiceResponsesAction(Request $request)
    {
        $user = $this->em()->getRepository('BetterknowBundle:User')
                ->find($request->get('id_user'));
        
        $friendList = $user->getFriends();
        
        $choice_list = new ArrayCollection();
        $count = 0;
        
        if(count($friendList) < self::MIN_FRIEND) {
            // On va chercher les amis de mes amis recursivité
           return $this->searchFriends($friendList);
        } else {
            foreach($friendList as $f){
                if(rand(0, 1) == 1){
                    $choice_list->add($f->getFriend());
                    $count++;
                }
                
                if($count == 4) break;
            }
            return $choice_list;
        }
    }
    
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"quizz"})
     * @Rest\Post("/quizz/{id_quizz}/response/{id_response}")
     */
    public function postResponseAction(Request $request)
    {
        $quizz = $this->em()->getRepository('BetterknowBundle:Quizz')
                    ->find($request->get('id_quizz'));
        
        if (empty($quizz)) {
            return $this->notFound('Quizz');
        }
        
        $response = $this->em()->getRepository('BetterknowBundle:User')
                    ->find($request->get('id_response'));
        
        if (empty($response)) {
            return $this->notFound('User');
        }
        
        $quizz->addResponse($response);
        
        $this->em()->flush();
        
        return $quizz;
    }
    
    
    private function searchFriends($friendList){
        
        $list = new ArrayCollection();
        
        foreach($friendList as $f){
            $myFriend = $this->em()->getRepository('BetterknowBundle:User')
                    ->find($f->getFriend()->getId());
          
            if(count($myFriend->getFriends()) > self::MIN_FRIEND){
                $count = 0;
                foreach($myFriend->getFriends() as $m){
                    if(rand(0,1) == 1){
                        $list->add($m->getFriend());
                        $count++;
                        
                        if($count == 4) break;
                    }            
                }
                return $list;
            }
        }
        
        if(count($list) == 0){
            $this->searchFriends($myFriend->getFriends());
        }
    }
    
    private function em(){
        return $this->getDoctrine()->getManager();
    }
    
    private function notFound($param)
    {
        return \FOS\RestBundle\View\View::create(['message' => $param.' not found'], Response::HTTP_NOT_FOUND);
    }
    
    private function succes()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'Success'], Response::HTTP_NOT_FOUND);
    }
}
