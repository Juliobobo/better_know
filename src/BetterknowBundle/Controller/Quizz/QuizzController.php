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
        
        $form = $this->createForm(GemType::class, $gem);

        // Le paramétre false dit à Symfony de garder les valeurs dans notre
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($gem);
            $em->flush();
            return $gem;
        } else {
            return $form;
        }
    }
    
    private function em(){
        return $this->getDoctrine()->getManager();
    }
    
    private function notFound($param)
    {
        return \FOS\RestBundle\View\View::create(['message' => $param.' not found'], Response::HTTP_NOT_FOUND);
    }
    
}
