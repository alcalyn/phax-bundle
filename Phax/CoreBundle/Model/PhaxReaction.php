<?php

namespace Phax\CoreBundle\Model;

use Phax\CoreBundle\Model\PhaxException;

/**
 * PhaxReaction, returned by phax controllers.
 * Contains parameters, errors and other metadata of reaction.
 */
class PhaxReaction implements \JsonSerializable
{
    /**
     *
     * @var array
     *  Contains Phax information :
     *      has_error               : if an error occured
     *      errors                  : array containings strings error
     *      trigger_js_reaction     : if client should trigger reaction in js with the same name (controller.action())
     *      message                 : meta message, used for command line mode
     * 
     */
    private $metadata;
    
    /**
     * Contains data defined by user
     * 
     * @var array
     */
    private $data;
    
    
    
    
    public function __construct(array $data = array())
    {
        $this->metadata = self::createMetadata();
        $this->data = $data;
    }
    
    
    private static function createMetadata()
    {
        return array(
            'has_error'             => false,
            'errors'                => array(),
            'trigger_js_reaction'   => true,
            'message'               => null,
        );
    }
    
    /**
     * Set a message in PhaxReaction metadata
     * 
     * @param string $msg
     * @return \Phax\CoreBundle\Model\PhaxReaction
     */
    public function setMetaMessage($msg)
    {
        $this->metadata['message'] = $msg;
        return $this;
    }
    
    /**
     * Get message from PhaxReaction metadata
     * 
     * @param string $msg
     * @return \Phax\CoreBundle\Model\PhaxReaction
     */
    public function getMetaMessage()
    {
        return $this->metadata['message'];
    }
    
    /**
     * Check if there is a message in PhaxReaction metadata
     * 
     * @param string $msg
     * @return \Phax\CoreBundle\Model\PhaxReaction
     */
    public function hasMetaMessage()
    {
        return !is_null($this->metadata['message']);
    }
    
    /**
     * Add an error to this reaction.
     * It will be catched in javascript
     * 
     * @param string $msg
     */
    public function addError($msg)
    {
        $this->metadata['has_error'] = true;
        $this->metadata['errors'][] = $msg;
        return $this;
    }
    
    /**
     * Remove any 
     */
    public function cleanErrors()
    {
        $this->metadata['has_error'] = false;
        $this->metadata['errors'] = array();
        return $this;
    }
    
    /**
     * Enable js reaction :
     * the reaction in js will be called with this data as argument
     * 
     * @return \Phax\CoreBundle\Model\PhaxReaction
     */
    public function enableJsReaction()
    {
        $this->metadata['trigger_js_reaction'] = true;
        return $this;
    }
    
    /**
     * Disable js reaction :
     * the reaction in js will not be called.
     * Usefull for ajax calls which don't need js post process
     * 
     * @return \Phax\CoreBundle\Model\PhaxReaction
     */
    public function disableJsReaction()
    {
        $this->metadata['trigger_js_reaction'] = false;
        return $this;
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
     * Get variable of reaction
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
     * Set variable to PhaxReaction.
     * This variable will be usable in js reaction
     * 
     * @param string $variable
     * @param mixed $value
     * @return \Phax\CoreBundle\Model\PhaxReaction $this
     * @throws PhaxException if trying to use "phax_metadata" as variable name : will conflict with js object
     */
    public function set($variable, $value)
    {
        if ($variable === 'phax_metadata') {
            throw new PhaxException('Cannot use "phax_metadata" as variable name for a PhaxReaction');
        }
        
        $this->data[$variable] = $value;
        return $this;
    }
    
    /**
     * Get all variables of reaction
     * 
     * @return array
     */
    public function all()
    {
        return $this->data;
    }
    
    
    public function __set($variable, $value)
    {
        return $this->set($variable, $value);
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
        $array['phax_metadata'] = $this->metadata;
        return $array;
    }
}
