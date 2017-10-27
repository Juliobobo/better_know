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
     * @Rest\View(serializerGroups={"gem"})
     * @Rest\Get("/users/{id}/gems/{gem_id}")
     */
    public function getGemAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('BetterknowBundle:User')
                ->find($request->get('id'));
        
        if (empty($user)) {
            return $this->notFound('User');
        }
        
        foreach ($user->getGems()->toArray() as $gem) {
            if ($gem->getId() == $request->get('gem_id')){
                return $em->getRepository('BetterknowBundle:Gem')
                ->find($gem->getId());
            } else {
                return $this->notFound('Gem');
            }
        }
    }
       
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"gem"})
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
        $gem->setUser($user); // Ici, le user est associé à la gem
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
    
     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/users/{id}/gems/{gem_id}")
     */
    public function removeUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('BetterknowBundle:User')
                ->find($request->get('id'));
        
        if (empty($user)) {
            return $this->notFound('User');
        }
        
        foreach ($user->getGems()->toArray() as $gem) {
            if ($gem->getId() == $request->get('gem_id')){
                $em->remove(
                        $em->getRepository('BetterknowBundle:Gem')
                            ->find($gem->getId())
                        );
                $em->flush();
                return $this->succes();
            } else {
                return $this->notFound('Gem');
            }
        }
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
