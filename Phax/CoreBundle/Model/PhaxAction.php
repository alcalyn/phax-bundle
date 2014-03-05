<?php

namespace Phax\CoreBundle\Model;


/**
 * PhaxAction, created at phax request.
 * Contains response data
 */
class PhaxAction implements \JsonSerializable
{
    
    /**
     * Name of the controller
     * 
     * @var string
     */
    private $controller;
    
    /**
     * Name of the action. Will call [action_name]Action()
     * 
     * @var string 
     */
    private $action;
    
    /**
     * Request instance
     * 
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;
    
    /**
     * True if the request is called in command line
     * False if called by ajax query
     * 
     * @var boolean 
     */
    private $isCli;
    
    /**
     * Contains action parameters
     * 
     * @var array
     */
    private $data;
    
    
    public function __construct($controller, $action = null, $params = array())
    {
        $this->controller   = $controller;
        $this->action       = $action;
        $this->data         = $params;
    }
    
    /**
     * Return request instance used for this action
     * 
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }
    
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Phax\CoreBundle\Model\PhaxAction
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }
    
    /**
     * Return current locale of request
     * 
     * @return string
     */
    public function getLocale()
    {
        return $this->getRequest()->getLocale();
    }

    /**
     * Return if this action has been requested from command line
     * 
     * @return boolean
     */
    public function isCli()
    {
        return $this->isCli;
    }

    /**
     * @param boolean $isCli
     * @return \Phax\CoreBundle\Model\PhaxAction
     */
    public function setIsCli($isCli)
    {
        $this->isCli = $isCli;
        return $this;
    }
    
    /**
     * Return the name of the controller
     * 
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Return the name of the action
     * 
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
    
    /**
     * Check if $variable exists
     * 
     * @param string $variable
     * @return boolean
     */
    public function has($variable)
    {
        return isset($this->data[$variable]);
    }
    
    /**
     * Get variable from ajax request,
     * or return $default if not exists
     * 
     * @param string $variable
     * @param mixed $default
     * @return mixed
     */
    public function get($variable, $default = null)
    {
        return $this->has($variable) ?
            $this->data[$variable] :
            $default ;
    }
    
    /**
     * Get all variables of action
     * 
     * @return array
     */
    public function all()
    {
        return $this->data;
    }
    
    public function __get($variable)
    {
        return $this->get($variable);
    }
    
    public function __isset($variable)
    {
        return $this->has($variable);
    }

    public function jsonSerialize()
    {
        $array = $this->data;
        $array['phax_metadata'] = array(
            'controller'    => $this->getController(),
            'action'        => $this->getAction(),
            'isCli'        => $this->isCli(),
        );
        
        return $array;
    }
}
