<?php

namespace Phax\CoreBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Phax\CoreBundle\Model\PhaxReaction;

/**
 * Helper class for building default phax reaction,
 * such as standard reaction with some parameters,
 * standard phax error, void reaction or simple message.
 * 
 * Call this in your controller using :
 * 
 *      return $this->get('phax')->reaction(array(
 *          ...
 *      ));
 */
class PhaxService extends ContainerAware
{
    
    /**
     * @param array $parameters
     * @return \Phax\CoreBundle\Model\PhaxReaction
     * 
     * Build and return a basic reaction with parameters
     */
    public function reaction(array $parameters = array())
    {
        return new PhaxReaction($parameters);
    }
    
    
    /**
     * Build and return a PhaxReaction or Response
     * depending of query type
     * 
     * @param string $view
     * @param array $parameters
     * @return \Phax\CoreBundle\Model\PhaxReaction
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $isPhaxRequest = $this
                ->container
                ->get('request')
                ->request
                ->has('phax_metadata')
        ;
        
        if ($isPhaxRequest) {
            return $this->reaction($parameters);
        } else {
            return $this->container->get('templating')->renderResponse($view, $parameters, $response);
        }
    }
    
    
    /**
     * @param type $msg
     * @return \Phax\CoreBundle\Model\PhaxReaction
     * 
     * Build and return a valid phax error response with one message
     */
    public function error($msg)
    {
        $reaction = new PhaxReaction();
        $reaction->addError($msg);
        return $reaction;
    }
    
    
    /**
     * @return \Phax\CoreBundle\Model\PhaxReaction
     * 
     * Build and return a void phax reaction,
     * without calling js reaction function
     */
    public function void()
    {
        $reaction = new PhaxReaction();
        $reaction->disableJsReaction();
        return $reaction;
    }
    
    
    /**
     * @param string $message
     * @return \Phax\CoreBundle\Model\PhaxReaction
     * 
     * Build and return a simple message defined in metadata.
     * Usefull for display message in command line mode.
     */
    public function metaMessage($message)
    {
        $reaction = new PhaxReaction();
        $reaction->setMetaMessage($message);
        return $reaction;
    }
}
