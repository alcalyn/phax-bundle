Call your phax controller
=========================


The solution as is:

``` javascript
phax.action('comment', 'add', {author: 'anonymous', comment: 'hello'});
```

Phax will call your `comment` controller,
and call `add` action passing `{author: 'anonymous', comment: 'hello'}` as data.

If you try it now, you javascript console will log an error, the reason is simple:<br />
phax search for a non existant callback, but you can view the ajax call,
and check for success in json reponse.


#### When do phax needs a javascript object ?

Only for actions which needs a javascript callback.

For others, you can disable the javascript reaction in your action by calling:

``` php
public function addAction(PhaxAction $phaxAction)
{
    // ...

    // This will disable the javascript callback
    $phaxReaction->disableJsReaction();

    return $phaxReaction;
}
```

This will make phax javascript side not calling the callback,
and avoid a js error if phax not find the reaction.

** Notice: **
> The js reaction is enabled by default.


*But if I need to do something in this callback ?*


### Creating javascript callbacks for a controller

As described in first page, a client side phax controller is a javascript object containing callbacks.

So you just have to create an object for comment controller, and define a callback for addAction.

Two rules:

- The name of the object must be the same as registered as service, without "phax." prefix
- The callback name is like `[action name]Reaction`

Then in our case, the object is named **comment**, and the callback is named **addReaction**.

``` javascript
var comment = {

    /**
     * Callback for add a comment,
     * just alert a message.
     * 
     * @param {object} r
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


Now if you do not disable js reaction,
and call `phax.action('comment', 'add', {author: 'anonymous', comment: 'hello'});`,
you will see an alert with your message.



#### What else ?

You know the most used part of phax, which is an ajax call and callback.

But phax allows you to call your phax actions in command line, usefull for cron tasks.<br />
For this, see
[Call a phax Controller from command line](3_callControllerCli.md).

<br />
Another usefull thing you can do later, with a little experience with phax,
is to use a same controller that you're actually using for normal controller,
but also call them with phax.

It is a factorization purpose.

This page could make your happiness for today:
[Make a same action callable with phax AND symfony default route](4_multiController.md)

