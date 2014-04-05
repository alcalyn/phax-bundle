Call your phax controller
=========================


The solution as is:

``` javascript
// javascript
phax.action('comment', 'add', {author: 'anonymous', comment: 'hello'});
```

Phax will call your `comment` controller,
and call `add` action passing `{author: 'anonymous', comment: 'hello'}` as data.

If you try it now, you javascript console will log an error, the reason is simple:<br />
phax search for a non existant callback, but you can view the ajax call in your javascript console,
and check for success in json reponse.


#### When do phax needs a javascript callback ?

Only for actions which needs a javascript callback.

For others, you can disable the javascript reaction in your action by calling:

``` php
// Acme\CommentBundle\Controller\CommentAjaxController.php
public function addAction(PhaxAction $phaxAction)
{
    // ...

	$phaxReaction = new PhaxReaction();

    // This will disable the javascript callback
    $phaxReaction->disableJsReaction();

    return $phaxReaction;
}
```

Which is equivalent to:

``` php
// Acme\CommentBundle\Controller\CommentAjaxController.php
public function addAction(PhaxAction $phaxAction)
{
    // ...

    return $this->get('phax')->void();
}
```

This will make phax javascript side not calling the callback,
and avoid a js log if phax not find the reaction.

**Notice:**
> The js reaction is enabled by default.


*But if I need to do something in this callback ?*


### Creating javascript callbacks for a controller

As described in first page, a client side phax controller is a javascript object containing callbacks.

So you just have to create an object for comment controller, and define a callback for addAction.

##### Two rules:

- The name of the javascript object must be **the same as the service name**, without "phax." prefix.
- The callback name must be **the same as the action name**, suffixed by **Reaction** instead of Action.

Then in our case, the object is named **comment** (phax.comment),<br />
and the callback is named **addReaction** (addAction).

Then your javascript should contains something like that:

``` javascript
// javascript
var comment = {

    /**
     * Callback for add a comment,
     * just alert a message.
     * 
     * @param {object} r contains parameters you sent from the php controller
     * @returns {void}
     */
    addReaction: function (r)
    {
        if (r.success) {
            alert('Thank you ! Your comment has been saved successfully.');
        } else {
            alert('Oops, your comment is invalid...');
        }
    }
};
```


Now if you call `phax.action('comment', 'add', {author: 'anonymous', comment: 'hello'});`,
you will see an alert with your message.

**Notice:**
> If you have disabled the js reaction, the callback will not be called.


### What else ?

You know the most used part of phax, which is an ajax call and callback.

But phax allows you to call your phax actions in command line, usefull for cron tasks.<br />
For this, see
[Call a phax Controller from command line](3_callControllerCli.md).

<br />
Another usefull thing you can do with phax
is to reuse controller that you're actually using for normal controller,
but also call this controller with phax.<br />
It is a factorization purpose.<br />
This page could make your happiness for today:
[Make a same action callable with phax AND symfony default route](4_hybridController.md)


### Links

- [Index](https://github.com/alcalyn/phax-bundle)
- [Installation](index.md)
- [Create a phax Controller](1_createPhaxController.md)
- [Call a phax Controller from web client](2_callControllerWeb.md)
- [Call a phax Controller from command line](3_callControllerCli.md)
- [Make a same action callable with phax AND symfony default route](4_hybridController.md)
- [Tips](5_tips.md)
