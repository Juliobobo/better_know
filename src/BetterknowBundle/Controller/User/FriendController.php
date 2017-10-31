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
            if ($friend->getFriend()->getId() == $request->get('friend_id')){
                return $em->getRepository('BetterknowBundle:User')
                    ->find($friend->getFriend()->getId());
            } 
        }
        return $this->notFound('Friend');     
    }
       
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"friend"})
     * @Rest\Post("/demand/users/{user_id}/friends/{friend_id}")
     */
    public function demandFriendShipAction(Request $request)
    {
        $emUser = $this->getDoctrine()
                ->getManager()
                ->getRepository('BetterknowBundle:User');
        
        $user = $emUser->find($request->get('user_id'));
        /* @var $user User */
        
        $friend = $emUser->find($request->get('friend_id'));
        /* @var $friend User */
        
        if (empty($user) || empty($friend)) {
            return $this->notFound('User ');
        }
        
        if($user->isFriend($friend)){
            return \FOS\RestBundle\View\View::create([
                'message' => $friend->getUsername().' is already your friend'], 
                Response::HTTP_NOT_FOUND);
        }
        
        if($user->getId() == $friend->getId()){
            return \FOS\RestBundle\View\View::create([
                'message' => $friend->getUsername().' find friend, it is you'], 
                Response::HTTP_NOT_FOUND);
        }
        
        $friendShip = new Friend();
        $friendShip->setUser($user); // Ici, le user est associé au friend
        $friendShip->setFriend($friend);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($friendShip);
        $em->flush();
        
        return $friendShip;
    }
    
    /**
     * @Rest\View(statusCode=Response::HTTP_CREATED, serializerGroups={"friend", "confirmShip"})
     * @Rest\Post("/confirm/users/{user_id}/friends/{friend_id}")
     */
    public function confirmFriendShipAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('BetterknowBundle:User')
                ->find($request->get('user_id'));
        /* @var $user User */
        
        $friend = $em->getRepository('BetterknowBundle:User')
                ->find($request->get('friend_id'));
        /* @var $friend User */
        
        $friendShip = $em->getRepository('BetterknowBundle:Friend')
                    ->findOneBy(
                            array('user' => $friend, 'friend' => $user)
                    );
        
        if(!$friendShip->getState()) {
            $friendShip->setState(true);
        }
        
        $friendShipInverse = new Friend();
        $friendShipInverse->setUser($user) 
                        ->setFriend($friend)
                        ->setState(true);
        
        $em->persist($friendShipInverse);
        $em->flush();
        
        return $friendShipInverse;
    }
    
     /**
     * @Rest\View(statusCode=Response::HTTP_NO_CONTENT)
     * @Rest\Delete("/users/{user_id}/friends/{friend_id}")
     */
    public function removeUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('BetterknowBundle:User')
                ->find($request->get('user_id'));
        /* @var $user User */
        
        $friend = $em->getRepository('BetterknowBundle:User')
                ->find($request->get('friend_id'));
        /* @var $friend User */
        
        if (empty($user) || empty($friend)) {
            return $this->notFound('User ');
        }
        
        $friendShip = $em->getRepository('BetterknowBundle:Friend')
                    ->findOneBy(
                            array('user' => $friend, 'friend' => $user)
                    );
        
        if (empty($friendShip)) {
            return $this->notFound('FriendShip ');
        }
        
        $em->remove($friendShip);
        $em->flush();
        
        return $this->succes();

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
