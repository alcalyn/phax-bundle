<?php

namespace Phax\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Phax\CoreBundle\Model\PhaxAction;

class HelpController extends Controller
{
    
    /**
     * Return a help page in console.log() or console format
     * 
     * @param \Phax\CoreBundle\Model\PhaxAction $phaxAction
     * @return \Phax\CoreBundle\Model\PhaxReaction
     */
    public function defaultAction(PhaxAction $phaxAction)
    {
        $isCli = $phaxAction->isCli();
        
        $pageTemplate = $isCli ?
                'PhaxCoreBundle:Help:help_page.cli.twig' :
                'PhaxCoreBundle:Help:help_page.web.twig' ;
        
        $pageContent = $this
                ->render($pageTemplate)
                ->getContent()
        ;
        
        return $this->get('phax')->metaMessage($pageContent);
    }
    
    
    /**
     * Return all parameters sent in phax action
     * 
     * @param \Phax\CoreBundle\Model\PhaxAction $phaxAction
     * @return \Phax\CoreBundle\Model\PhaxReaction
     */
    public function testAction(PhaxAction $phaxAction)
    {
        $a = 1 / (2 - 2);
        $data = $phaxAction->jsonSerialize();
        $data['phaxAction_metadata'] = $data['phax_metadata'];
        
        return $this->get('phax')->reaction($data);
    }
    
    
    /**
     * Return a pong with datetime
     * 
     * @param \Phax\CoreBundle\Model\PhaxAction $phaxAction
     * @return \Phax\CoreBundle\Model\PhaxReaction
     */
    public function pingAction()
    {
        return $this->get('phax')->metaMessage(
            'pong ('.date('l j F Y, G:i:s').')'
        );
    }
}
