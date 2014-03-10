Create a phax controller
========================

### What is a phax controller ?

A phax controller is, like symfony do, a class which contains methods.

    CommentController::addAction

Phax follows symfony naming conventions for controller and action name.


#### Server-side

A phax controller...

- is a symfony controller registered as a service.
- contains actions.


#### Client-side

A phax controller...

- contains callbacks for actions which need post processing in javascript.


## Create a phax controller

For example, we start from the point that you have a comment system,
and you want that users can send a comment asynchronously.

### Create a controller

You should create a CommentAjaxController, as you do for a normal controller.
The name of the class is not important.


### Create an action

Now, you want to create an addAction, to add a comment asynchronously.

The simplest signature you can use for your action is:

``` php
<?php
// Acme\CommentBundle\Controller\CommentAjaxController.php

namespace Acme\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Phax\CoreBundle\Model\PhaxAction; // I bet you just forgot

class CommentAjaxController extends Controller
{
    /**
     * Add a comment asynchronously
     */
    public function addAction(PhaxAction $phaxAction) {}
}
```

Like this, you get the whole instance containing sent parameters from client,
and metadata such as request, controller and action name...

**Warning:**
> You MUST type your argument to notify phax to pass the whole instance of
> [PhaxAction](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Model/PhaxAction.php).

<br />
Another possible signature is just to put arguments you need.<br />
If they are named the same as your parameters you receive from ajax request,
phax will call your action by passing the goods parameters to the goods arguments.<br />
The order of arguments is not important.

``` php
public function addAction($message, $author) {}
```

This second method is usefull to reuse an action you are actually using as a normal action for ajax calls.<br />
We will see that later, in [Make a same action callable with phax AND symfony default route](4_multiController.md)

**Notice:**
> You can combine the two methods, and the order is not important.


### Return a PhaxReaction

Now you have a callable action, you cannot use normal `return $this->render();`
because it returns a full symfony response we don't need.

In a phaxAction, you must return a
[PhaxReaction](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Model/PhaxReaction.php).

``` php
// Acme\CommentBundle\Controller\CommentAjaxController.php


/**
 * Add a comment asynchronously
 */
public function addAction(PhaxAction $phaxAction)
{
    $comment = new Comment();

    // Set comment from $phaxAction arguments
    $comment
            ->setAuthor($phaxAction->get('author'))
            ->setComment($phaxAction->get('comment'))
    ;

    $isCommentValid = 0 === count($this->get('validator')->validate($comment));

    if ($isCommentValid) {
        try {
            // Your comment saving logic
        } catch (\Exception $ex) {
            // Return a phax reaction with an error
            $phaxReaction = new PhaxReaction();
            $phaxReaction->addError('Error then trying to save comment in database');
            return $phaxReaction;
        }
    }

    // Return a phax reation with a success or failure notification
    $phaxReaction = new PhaxReaction();
    $phaxReaction->set('success', $isCommentValid);

    return $phaxReaction;
}
```

Done ! your action is correct, you get $phaxAction intstance,
and returns a [PhaxReaction](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Model/PhaxReaction.php) object.


#### Use of phax service

Phax provide a
[PhaxReaction](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Model/PhaxReaction.php)
factory:
[PhaxService](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Services/PhaxService.php).

The service id is "**phax**".

There you have some reaction you should use often:

``` php
// simple reaction with parameters
return $this->get('phax')->reaction(array(
    'success'   => true,
));
```

``` php
// error reaction
return $this->get('phax')->error('The error message');
```

``` php
// a reaction with no callback
return $this->get('phax')->void();
```

See others reactions in
[PhaxService](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Services/PhaxService.php).

You have here a working phax controller.

One thing is missing, register your controller as a phax controller.


### Register your controller to be used by phax

Every phax controller is a service.

But phax use a simple naming convention for the service name:

    phax.[controller name]

For example,
if you want to register your comment controller as a phax controller,
you should use this:

``` yml
# src/Acme/CommentBundle/Resources/service.yml
services:
    phax.comment:
        class: Acme\CommentBundle\Controller\CommentAjaxController
        calls:
            - [setContainer, ["@service_container"]]
```

Now, phax knows your controller as "**comment**" controller, and have an action, "**add**Action".

**Notice:**
> I inject the whole container here,
> but sometimes you may prefer inject just some services you need.


The next step learn you how to call this action from your web client.

[Call a phax Controller from web client](2_callControllerWeb.md)

<br />
You can also call this action from command line (usefull for cron tasks).

[Call a phax Controller from command line](3_callControllerCli.md)


### Links

- [Index](https://github.com/alcalyn/phax-bundle)
- [Installation](index.md)
- [Create a phax Controller](1_createPhaxController.md)
- [Call a phax Controller from web client](2_callControllerWeb.md)
- [Call a phax Controller from command line](3_callControllerCli.md)
- [Make a same action callable with phax AND symfony default route](4_multiController.md)
- [Tips](5_tips.md)