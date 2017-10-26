<?php

namespace BetterknowBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use BetterknowBundle\Entity\Gem;
use BetterknowBundle\Form\GemType;

class GemController extends Controller
{
    
    /**
     * @Rest\View(serializerGroups={"gem"})
     * @Rest\Get("/users/{id}/gems")
     */
    public function getGemsAction(Request $request)
    {
        
        $user = $this->getDoctrine()
                ->getManager()
                ->getRepository('BetterknowBundle:User')
                ->find($request->get('id'));
        
        /* @var $user User */
        
        if (empty($user)) {
            return $this->notFound('User');
        }
        
        return $user->getGems();
    }
    
    /**
     * @Rest\View()
     * @Rest\Get("/users")
     */
    public function getGemAction(Request $request)
    {
        $user = $this->getDoctrine()
                ->getManger()
                ->getRepository('BetterknowBundle:User')
                ->find($request->get('id'));
        
        
        
        /* @var $user User */

        return $user;
    }
   
    
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED)
     * @Rest\Post("/users/{id}/gems")
     */
    public function postGemsAction(Request $request)
    {
        $user = $this->getDoctrine()
                ->getManager()
                ->getRepository('BetterknowBundle:User')
                ->find($request->get('id'));
        /* @var $user User */

        if (empty($user)) {
            return $this->notFound('User ');
        }

        $gem = new Gem();
        $gem->setPlace($user); // Ici, le user est associé à la gem
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
    
    
    private function notFound($param)
    {
        return \FOS\RestBundle\View\View::create(['message' => $param.' not found'], Response::HTTP_NOT_FOUND);
    }
}
