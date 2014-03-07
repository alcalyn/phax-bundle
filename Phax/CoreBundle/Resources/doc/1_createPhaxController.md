Create a phax controller
========================

### What is a phax controller ?

A phax controller is, like symfony do, a class/method identified as this:

    controller/action

- _controller_ is the name of your class, following symfony standards (XxxController)
- _action_ is the name of your method, following symfony standards (doSomethingAction)


#### Server-side

A phax controller...

- is a symfony service.
- contains actions such as symfony action's.


#### Client-side

A phax controller...

- contains callbacks for actions which need post processing in javascript, named `doSomethingReaction`.


## Create a phax controller

For example, we start from the point that you have a comment system,
and you want that users can send a comment asyncronously.

### Create a controller

You should create a CommentAjaxController, as you do for a normal controller.
The name of the class is not important.


### Create an action

Now, you want to create an addAction, to add a comment asyncronously.

You have 2 ways to create a phax action:

- By getting the whole $phaxAction object which contains all arguments:

``` php
<?php

namespace Acme\CommentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Phax\CoreBundle\Model\PhaxAction; // I bet you just forgot

class CommentAjaxController extends Controller
{
    /**
     * Add a comment asyncronously
     */
    public function addAction(PhaxAction $phaxAction) {}
}
```

** Warning: **
> You MUST use argument typing `PhaxAction` to notify Phax to pass the whole action


- By getting only the parameters you want:

``` php
public function addAction($message, $author) {}
```

The second method is usefull to use a same action for phax calls, or normal symfony route,
we will see that later, in [Make a same action callable with phax AND symfony default route](4_multiController.md)

** Notice: **
> You can combine the two methods


### Return a PhaxReaction

Now you have a callable action, you cannot use normal `return $this->render();`
because it returns a full http response we don't need.

In a phaxAction, you must return a `PhaxReaction`.

``` php
/**
 * Add a comment asyncronously
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


Done ! your action is correct, you get $phaxAction arguments,
and returns a PhaxReaction object.

One thing is missing, register your controller as a phax controller.


### Register your controller to be used by phax

Every phax controller is a service.

But phax use a simple naming convention for the service name:

    phax.[controller name]

For example, if you want to register your comment controller as a phax controller
to send comment asyncronously, you should use this:

``` yml
services:
    phax.comment:
        class: Acme\CommentBundle\Controller\CommentAjaxController
        calls:
            - [setContainer, ["@service_container"]]
```

Now, phax know your controller as "comment" controller, and have an action, "addAction".


The next step learn you how to call this action from your web client.

[Call a phax Controller from web client](2_callControllerWeb.md)

<br />
You can also call this action from command line (usefull for cron tasks).

[Call a phax Controller from command line](3_callControllerCli.md)
