Make a same action callable with phax AND symfony default route
===============================================================


Sometimes, to avoid creating two actions doing the same thing,
one for a page loading, another for an ajax call,
you may want to separate the controller logic into another private method
in the controller, and return a symfony response in the first action, and a
[PhaxReaction](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Model/PhaxReaction.php)
in the second.

But phax allows you to reuse the same action, and adapt the response to the request.

By using:

``` php
<?php
// Acme\CommentBundle\Controller\CommentAjaxController.php

/**
 * List all comments
 */
public function listAction($page)
{
	// ...

	// Return a phax reation with a success or failure notification
	return $this->get('phax')->reaction(array(
		'comments'   => $comments,
	));
}
```

You create a controller callable by phax, but symfony can't use it because it returns a
[PhaxReaction](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Model/PhaxReaction.php),
not a symfony Response.

However, phax provide an "hybrid" response, which returns either a
[PhaxReaction](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Model/PhaxReaction.php)
or a normal symfony response depending of the request type.


### Use phax render

[PhaxService](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Services/PhaxService.php),
(the [PhaxReaction](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Model/PhaxReaction.php)
factory), provide that type of response by calling the **render** factory:

``` php
// Acme\CommentBundle\Controller\CommentAjaxController.php

/**
 * List all comments
 */
public function listAction($page)
{
	// Load your comments
	$comments = array(/* your comments load logic */);
	
	// Return either symfony response or PhaxReaction
	return $this->get('phax')->render('CommentBundle:Comment:list.html.twig', array(
		'comment'	=> $comments,
	));
}
```

This example returns an hybrid response.

If the request is a normal symfony request, it is equivalent to:

``` php
return $this->render('CommentBundle:Comment:list.html.twig', array(
	'comment'	=> $comments,
));
```

But if it as a phax call, it is equivalent to:

``` php
return $this->get('phax')->reaction(array(
	'comment'	=> $comments,
));
```


Of course that controller is as well callable from command line (see
[Call a phax Controller from command line](3_callControllerCli.md)
).

See also some tips and trick about phax: [Tips](5_tips.md)


### Links

- [Index](https://github.com/alcalyn/phax-bundle)
- [Installation](index.md)
- [Create a phax Controller](1_createPhaxController.md)
- [Call a phax Controller from web client](2_callControllerWeb.md)
- [Call a phax Controller from command line](3_callControllerCli.md)
- [Make a same action callable with phax AND symfony default route](4_multiController.md)
- [Tips](5_tips.md)