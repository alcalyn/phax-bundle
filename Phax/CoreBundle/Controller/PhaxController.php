<?php

namespace Phax\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Phax\CoreBundle\Model\PhaxResponse;
use Phax\CoreBundle\Model\PhaxAction;

class PhaxController extends Controller
{
    /**
     * Phax main controller.
     * Call this controller using phax.action() in js.
     * (Define your PhaxConfig.www_script as the route of this controller).
     * 
     * @return \Phax\CoreBundle\Model\PhaxResponse
     */
    public function phaxAction()
    {
        $request = $this->get('request');
        
        $params = $request->request->all();
        
        $controller = $params['phax_metadata']['controller'];
        $action     = $params['phax_metadata']['action'];
        
        $phaxAction = new PhaxAction($controller, $action, $params);
        $phaxAction
                ->setRequest($request)
                ->setIsCli(false)
        ;
        
        $phaxReaction = $this
                ->get('phax_core')
                ->action($phaxAction)
        ;

        return new PhaxResponse($phaxReaction);
    }
}
