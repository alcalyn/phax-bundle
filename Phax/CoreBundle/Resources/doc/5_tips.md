Tips
====


Here is some tips and tricks about phax.


### Default action

A default action is called then you do not provide action argument in javascript or command line.

Then phax call the default action, which must be named like this:

	defaultAction(PhaxAction $phaxAction) { /* ... */ }

Then by calling in javascript:

``` javascript
phax.action('comment');
```

or in command line:

``` bash
$ php app/console phax:action comment
```

You are in fact calling defaultAction().

Not very usefull, but sometimes...


### Links

- [Index](https://github.com/alcalyn/phax-bundle)
- [Installation](index.md)
- [Create a phax Controller](1_createPhaxController.md)
- [Call a phax Controller from web client](2_callControllerWeb.md)
- [Call a phax Controller from command line](3_callControllerCli.md)
- [Make a same action callable with phax AND symfony default route](4_multiController.md)
- [Tips](5_tips.md)