Call your phax controller from command line
===========================================


You have created an action in a phax controller,
you want to call it from command line ?

``` bash
$ php app/console phax:action controller action -p param1:value1 -p param2:value2
```

This line display the json reaction of the array your controller return,
and some metadata from phax.


### Use meta message

If you want to display a simple message instead of raw json,
[PhaxReaction](https://github.com/alcalyn/phax-bundle/blob/master/Phax/CoreBundle/Model/PhaxReaction.php)
provide a message attribute in its metadata.<br />
If a message is set, it will be displayed in command line as response.

``` php
// Acme\CommentBundle\Controller\CommentAjaxController.php
public function deleteOldCommentsAction(PhaxAction $phaxAction)
{
    // ...

	$phaxReaction = new PhaxReaction();

    // This will disable the javascript callback
    $phaxReaction->setMetaMessage('Old comments have been deleted.');

    return $phaxReaction;
}
```

Which is equivalent to:

``` php
// Acme\CommentBundle\Controller\CommentAjaxController.php
public function deleteOldCommentsAction(PhaxAction $phaxAction)
{
    // ...

	return $this->get('phax')->metaMessage('Old comments have been deleted.');
}
```


### Example

To call `deleteOldCommentsAction`:

``` bash
$ php app/console phax:action comment deleteOldComments
```

Will output:

``` bash
$ php app/console phax:action comment deleteOldComments
Old comments have been deleted.
$ 
```

**Notice:**
> The deleteOldComments works also in ajax, the meta message is accessible from r.phax_metadata.message


### Links

- [Index](https://github.com/alcalyn/phax-bundle)
- [Installation](index.md)
- [Create a phax Controller](1_createPhaxController.md)
- [Call a phax Controller from web client](2_callControllerWeb.md)
- [Call a phax Controller from command line](3_callControllerCli.md)
- [Make a same action callable with phax AND symfony default route](4_multiController.md)
- [Tips](5_tips.md)

