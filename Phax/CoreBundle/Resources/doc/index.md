Getting started with Phax
=========================

This bundle help you to make a structured architecture for an ajax application under symfony2.
Create a controller, declare it as a service, and you can call its action from javascript.

## Prerequisites

This version of the bundle requires

- Symfony2
- PHP >= 5.4.0
- jQuery (jQuery.post function)


## Installation

Installation is 4 steps, nothing very interesting...

1. Download PhaxCoreBundle using composer
2. Enable the Bundle
3. Register Phax route
4. Javascript integration

### Step 1: Download PhaxCoreBundle using composer

```js
{
    "require": {
        "phax/phax-bundle": "dev-master"
    }
}
```


### Step 2: Enable the Bundle

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Phax\CoreBundle\PhaxCoreBundle(),
    );
}
```


### Step 3: Register Phax route

Phax needs to register one route: its controller used for every ajax call.

``` yml
#app/routing.yml
phax:
    resource: "@PhaxCoreBundle/Resources/config/routing.yml"
```


### Step 4: Javascript integration

Phax needs also in client side to know which url call then you call an action.
Add this block at the end of your html template.

Twig:

``` html
{# Phax integration #}
{% javascripts
    '@PhaxCoreBundle/Resources/public/js/*'
%}
    <script src="{{ asset_url }}" type="text/javascript"></script>
{% endjavascripts %}
<script type="text/javascript">
    var phaxConfig = {
        www_script: '{{ path('phax_script') }}'
    }
</script>
{# END Phax integration #}
```

**Note:**

> If your application does not use jQuery yet, add the library yourself by adding this line:
> `<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>`


## Voilà ! phax is now installed.

You can test it by calling help/default controller in your javascript console:

``` javascript
phax.action('help')
```

Or in a terminal:

``` bash
$ php app/console phax:action help
```


## Next steps

Now phax is working, you can create your own ajax controllers for your ajax application.

- [Create a phax Controller](1_createPhaxController.md)
- [Call a phax Controller from web client](2_callControllerWeb.md)
- [Call a phax Controller from command line](3_callControllerCli.md)
- [Make a same action callable with phax AND symfony default route](4_multiController.md)


