<?php

namespace BetterknowBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use BetterknowBundle\Entity\Gem;

class GemController extends Controller
{

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

        return new JsonResponse($formatted);
    }
    

    public function getGemAction(Request $request)
    {
        $gem = $this->get('doctrine.orm.entity_manager')
                ->getRepository('BetterknowBundle:Gem')
                ->find($request->get('gem_id'));
        
        /* @var $gem Gem */
        
        
        $formatted = [
           'id' => $gem->getId(),
            'gender' => $gem->getGender(),
            'timeReceive' => $gem->getTimeReceive(),
        ];

        return new JsonResponse($formatted);
    }
}
