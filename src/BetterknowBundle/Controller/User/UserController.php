<?php

namespace BetterknowBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use BetterknowBundle\Entity\User;
use BetterknowBundle\Form\UserType;

class UserController extends Controller
{
    
    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users")
     */
    public function getUsersAction(Request $request)
    {  
        $user = $this->getDoctrine()
                ->getManager()
                ->getRepository('BetterknowBundle:User')
                ->findAll();
        
        /* @var $user User */
        
        if (empty($user)) {
            return $this->notFound('User');
        }
        
        return $user;
    }
    
    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/users/{id}")
     */
    public function getUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('BetterknowBundle:User')
                ->find($request->get('id'));
        
        if (empty($user)) {
            return $this->notFound('User');
        }
        
        return $user;
    }
        
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"user"})
     * @Rest\Post("/users")
     */
    public function postUsersAction(Request $request)
    {  
        $user = new User();
        $form = $this->createForm(UserType::class, $user, ['validation_groups'=>['Default', 'New']]);

        $form->submit($request->request->all());

        if ($form->isValid()) {
            $encoder = $this->get('security.password_encoder');
            // le mot de passe en claire est encodé avant la sauvegarde
            $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }
    
    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Put("/users/{id}")
     */
    public function updateUserAction(Request $request)
    {
        return $this->updateUser($request, true);
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("/users/{id}")
     */
    public function patchUserAction(Request $request)
    {
        return $this->updateUser($request, false);
    }
    
    private function updateUser(Request $request, $clearMissing)
    {
        $user = $this->getDoctrine()
                ->getManager()
                ->getRepository('BetterknowBundle:User')
                ->find($request->get('id')); 
        /* @var $user User */

        if (empty($user)) {
            return $this->userNotFound();
        }

        if ($clearMissing) { // Si une mise à jour complète, le mot de passe doit être validé
            $options = ['validation_groups'=>['Default', 'FullUpdate']];
        } else {
            $options = []; // Le groupe de validation par défaut de Symfony est Default
        }

        $form = $this->createForm(UserType::class, $user, $options);

        $form->submit($request->request->all(), $clearMissing);

        if ($form->isValid()) {
            // Si l'utilisateur veut changer son mot de passe
            if (!empty($user->getPlainPassword())) {
                $encoder = $this->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($encoded);
            }
            $em = $this->getDoctrine()->getManager();
            $em->merge($user);
            $em->flush();
            return $user;
        } else {
            return $form;
        }
    }

    private function userNotFound()
    {
        return \FOS\RestBundle\View\View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
    }
}
