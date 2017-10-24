<?php

namespace BetterknowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\ViewHandler;
use FOS\RestBundle\View\View;
use BetterknowBundle\Entity\Gem;

class GemController extends Controller
{
    
    /**
     * @Rest\View()
     * @Rest\Get("/gems")
     */
    public function getGemsAction(Request $request)
    {
        
        $gems = $this->get('doctrine.orm.entity_manager')
                ->getRepository('BetterknowBundle:Gem')
                ->findAll();
        
        /* @var $gems Gem[] */
        
        
        $formatted = [];
        foreach ($gems as $gem) {
            $formatted[] = [
               'id' => $gem->getId(),
               'gender' => $gem->getGender(),
               'timeReceive' => $gem->getTimeReceive(),
            ];
        }
        
        // Création d'une vue FOSRestBundle
        $view = View::create($formatted);
        $view->setFormat('json');

        return $this->get('fos_rest.view_handler')->handle($view);
        
    }
    
    /**
     * @Rest\View()
     * @Rest\Get("/gems/{gem_id}")
     */
    public function getGemAction(Request $request)
    {
        $gem = $this->get('doctrine.orm.entity_manager')
                ->getRepository('BetterknowBundle:Gem')
                ->find($request->get('gem_id'));
        
        /* @var $gem Gem */
        
        if (empty($gem)) {
            return new JsonResponse(['message' => 'Gem not found'], Response::HTTP_NOT_FOUND);
        }
        
        $formatted = [
           'id' => $gem->getId(),
            'gender' => $gem->getGender(),
            'timeReceive' => $gem->getTimeReceive(),
        ];
        
        // Création d'une vue FOSRestBundle
        $view = View::create($formatted);
        $view->setFormat('json');

        return $this->get('fos_rest.view_handler')->handle($view);
    }
}
