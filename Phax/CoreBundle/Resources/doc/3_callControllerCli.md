Call your phax controller from command line
===========================================


Assuming that you have a phax controller (if not, follow
[Register your controller to be used by phax](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Resources/doc/1_createPhaxController.md#register-your-controller-to-be-used-by-phax)
to have one), you can call its actions from command line by using:

``` bash
$ php app/console phax:action controller action -p param1:value1 -p param2:value2
```

This line display the json reaction of the array your controller return,
and some metadata from phax, something like that:

``` bash
$ php app/console phax:action controller action -p param1:value1 -p param2:value2
{"success":true,"responseParam":"responseValue","phax_metadata":{"has_error":false,"errors":[],"trigger_js_reaction":true,"message":null}}
```


**Notice:**
> If you have the error
> ```The controller controller::action must return an instance of Phax\CoreBundle\Model\PhaxReaction, Symfony\Component\HttpFoundation\Response returned```
> that means that you do not return a phax reaction.
> You should [Use phax service](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Resources/doc/1_createPhaxController.md#use-of-phax-service)
> to return a phax reaction instead of a symfony response.


You can display a message instead of raw json.


### Use meta message

If you want to display a simple message instead of raw json,
[PhaxReaction](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Model/PhaxReaction.php)
provide a message attribute in its metadata.<br />
If a message is set, it will be displayed in command line as response.

``` php
// Acme\CommentBundle\Controller\CommentAjaxController.php

public function deleteOldCommentsAction($date)
{
    // ...

	$phaxReaction = new PhaxReaction();

    // This will disable the javascript callback
    $phaxReaction->setMetaMessage('Old comments have been deleted: '.$queryResult);

    return $phaxReaction;
}
```

Which is equivalent to:

``` php
// Acme\CommentBundle\Controller\CommentAjaxController.php
public function deleteOldCommentsAction($date)
{
    // ...

	return $this->get('phax')->metaMessage('Old comments have been deleted: '.$queryResult);
}
```


### Example

To call `deleteOldCommentsAction`:

``` bash
$ php app/console phax:action comment deleteOldComments -p date:2014-06-05
```

Will output:

``` bash
$ php app/console phax:action comment deleteOldComments -p date:2014-06-05
Old comments have been deleted: 3
$ 
```

**Notice:**
> The deleteOldComments action works also in ajax,
> and the meta message is accessible from r.phax_metadata.message


### Links

- [Index](https://github.com/alcalyn/phax-bundle)
- [Installation](index.md)
- [Create a phax Controller](1_createPhaxController.md)
- [Call a phax Controller from web client](2_callControllerWeb.md)
- [Call a phax Controller from command line](3_callControllerCli.md)
- [Make a same action callable with phax AND symfony default route](4_hybridController.md)
- [Tips](5_tips.md)

