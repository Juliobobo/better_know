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

class QuizzController extends Controller
{
    
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
