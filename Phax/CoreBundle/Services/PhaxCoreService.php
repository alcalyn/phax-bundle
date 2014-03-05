<?php

namespace Phax\CoreBundle\Services;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Phax\CoreBundle\Model\PhaxException;
use Phax\CoreBundle\Model\PhaxAction;
use Phax\CoreBundle\Model\PhaxReaction;

class PhaxCoreService extends ContainerAware
{
    /**
     * @param PhaxAction containing request and/or parameters
     * @return \Phax\CoreBundle\Model\PhaxReaction
     * @throws PhaxException if :
     *                  - controller does not exists (not declared as service "phax.XXX"
     *                  - controller does not return a PhaxReaction instance
     */
    public function action(PhaxAction $phaxAction)
    {
        $serviceName = 'phax.'.$phaxAction->getController();
        
        if (!$this->container->has($serviceName)) {
            throw new PhaxException(
                'The controller '.$phaxAction->getController().' does not exists. '.
                'It must be declared as service named '.$serviceName
            );
        }
        
        $phaxController = $this
            ->container
            ->get($serviceName)
        ;
        
        $phaxReaction = $this->callAction($phaxController, $phaxAction);
        
        if (!($phaxReaction instanceof PhaxReaction)) {
            throw new PhaxException(
                'The controller '.$phaxAction->getController().'::'.$phaxAction->getAction().
                ' must return an instance of Phax\CoreBundle\Model\PhaxReaction, '.
                get_class($phaxReaction).' returned'
            );
        }
        
        return $phaxReaction;
    }
    
    
    /**
     * Call action by passing arguments to method parameters with the same name.
     * Or pass PhaxAction if myAction(PhaxAction $arg) is used.
     * 
     * @param mixed $phaxController instance of a phax controller
     * @param PhaxAction $phaxAction instance of PhaxAction
     * @return PhaxReaction
     * @throws PhaxException if method parameters name does not correspond to $phaxAction arguments
     */
    private function callAction($phaxController, $phaxAction)
    {
        $methodName = $phaxAction->getAction().'Action';
        $method     = new \ReflectionMethod($phaxController, $methodName);
        $arguments  = array();
        
        foreach ($method->getParameters() as $parameter) {
            $parameterClass = $parameter->getClass();
            
            if ($parameterClass && $parameterClass->getName() === 'Phax\CoreBundle\Model\PhaxAction') {
                $arguments []= $phaxAction;
            } elseif (isset($phaxAction->{$parameter->getName()})) {
                $arguments []= $phaxAction->{$parameter->getName()};
            } else {
                throw new PhaxException(
                    'Action '.$methodName.' of your Phax controller '.get_class($phaxController)
                    . ' has a parameter '.$parameter->getName().' which do not correspond to any query parameter.'
                    . ' To pass entire PhaxAction instance, use "myAction(PhaxAction $phaxAction)"'
                    . ' in your method declaration.'
                );
            }
        }
        
        return call_user_func_array(array($phaxController, $methodName), $arguments);
    }
    
    
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $errorMessage = $event
                ->getException()
                ->getMessage()
        ;
        
        $event->setResponse(new Response($errorMessage));
    }
}
