<?php

namespace BetterknowBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use BetterknowBundle\Entity\Friend;
use BetterknowBundle\Form\FriendType;

class FriendController extends Controller
{
    
    /**
     * @Rest\View(serializerGroups={"friend"})
     * @Rest\Get("/users/{id}/friends")
     */
    public function getFriendsAction(Request $request)
    {  
        $user = $this->getDoctrine()
                ->getManager()
                ->getRepository('BetterknowBundle:User')
                ->find($request->get('id'));
        
        /* @var $user User */
        
        if (empty($user)) {
            return $this->notFound('User');
        }
        
        return $user->getFriends();
    }
    
    /**
     * @Rest\View(serializerGroups={"friend"})
     * @Rest\Get("/users/{id}/friends/{friend_id}")
     */
    public function getFriendAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('BetterknowBundle:User')
                ->find($request->get('id'));
        
        if (empty($user)) {
            return $this->notFound('User');
        }
        
        foreach ($user->getFriends()->toArray() as $friend) {
            if ($friend->getId() == $request->get('friend_id')){
                return $em->getRepository('BetterknowBundle:Friend')
                ->find($friend->getId());
            } else {
                return $this->notFound('Friend');
            }
        }
    }
       
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"friend"})
     * @Rest\Post("/users/{user_id}/friends/{friend_id}")
     */
    public function postFriendsAction(Request $request)
    {
        $user = $this->getDoctrine()
                ->getManager()
                ->getRepository('BetterknowBundle:User')
                ->find($request->get('user_id'));
        /* @var $user User */
        
        $friend = $this->getDoctrine()
                ->getManager()
                ->getRepository('BetterknowBundle:User')
                ->find($request->get('friend_id'));
        /* @var $friend User */
        
        if (empty($user)) {
            return $this->notFound('User ');
        }
        
        if($user->isFriend($friend)){
            return \FOS\RestBundle\View\View::create([
                'message' => $friend->getUsername().' is already your friend'], 
                Response::HTTP_NOT_FOUND);
        }
        
        $friendShip = new Friend();
        $friendShip->setUser($user); // Ici, le user est associé au friend
        $friendShip->setFriend($friend);
        $form = $this->createForm(FriendType::class, $friendShip);

        // Le paramétre false dit à Symfony de garder les valeurs dans notre
        // entité si l'utilisateur n'en fournit pas une dans sa requête
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($friend);
            $em->flush();
            return $friend;
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
