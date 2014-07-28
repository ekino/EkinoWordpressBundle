EkinoWordpressBundle
====================

[![Build Status](https://secure.travis-ci.org/ekino/EkinoWordpressBundle.png?branch=master)](http://travis-ci.org/ekino/EkinoWordpressBundle)

This bundle is used to bring some Symfony services into Wordpress and manipulates Wordpress using Symfony.

Here are some features:

* Use custom Symfony services into Wordpress,
* Use Symfony to manipulate Wordpress database,
* Create custom Symfony routes out of Wordpress,
* When authenticated on Wordpress, authenticated on Symfony too with correct user roles. *(requires ekino-wordpress-symfony Wordpress plugin)*
* Catch some Wordpress hooks to be dispatched by Symfony EventDispatcher *(requires ekino-wordpress-symfony Wordpress plugin)*

---

## Installation

### 1) Install Symfony into your Wordpress project

Install your Wordpress project and/or get into your root project directory and install symfony like this:

`php composer.phar create-project symfony/framework-standard-edition symfony/`

### 2) Install ekino/wordpress-bundle into Symfony's project

After, edit `symfony/composer.json` file to add this bundle package:

```yml
"require": {
    ...
    "ekino/wordpress-bundle": "dev-master"
},
```

Run `php composer.phar update ekino/wordpress-bundle`

Then, add the bundle into `symfony/app/AppKernel.php`:

```php
<?php
    public function registerBundles()
    {
        $bundles = array(
            ...
            new Ekino\WordpressBundle\EkinoWordpressBundle(),
        );

        ...

        return $bundles;
    }
```

Add the WordpressBundle routing file in your `symfony/app/config/routing.yml`, after your custom routes to catch all Wordpress routes:

```yml
...
ekino_wordpress:
    resource: "@EkinoWordpressBundle/Resources/config/routing.xml"
```

Optionnally, you can specify the following options in your `app/config.yml`:

```yml
ekino_wordpress:
    table_prefix: wp_ # If you have a specific Wordpress table prefix
    wordpress_directory: /my/wordpress/directory # If you have a specific Wordpress directory structure
    load_twig_extension: true # If you want to enable native WordPress functions (ie : get_option() => wp_get_option())
```

Also optionally, if you want to use `UserHook` to authenticate on Symfony, you should add this configuration to your `app/security.yml`:

```yml
security:
    providers:
        main:
            entity: { class: Ekino\WordpressBundle\Entity\User, property: login }
```

### 3) Update your Wordpress index.php file to load Symfony libraries

```php
<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Retrieves or sets the Symfony Dependency Injection container
 *
 * @param ContainerInterface|string $id
 *
 * @return mixed
 */
function symfony($id)
{
    static $container;

    if ($id instanceof ContainerInterface) {
        $container = $id;
        return;
    }

    return $container->get($id);
}

$loader = require_once __DIR__.'/symfony/app/bootstrap.php.cache';

// Load application kernel
require_once __DIR__.'/symfony/app/AppKernel.php';

$sfKernel = new AppKernel('dev', true);
$sfKernel->loadClassCache();
$sfKernel->boot();

// Add Symfony container as a global variable to be used in Wordpress
$sfContainer = $sfKernel->getContainer();

if (true === $sfContainer->getParameter('kernel.debug', false)) {
    Debug::enable();
}

$sfContainer->enterScope('request');

symfony($sfContainer);

$sfRequest = Request::createFromGlobals();
$sfResponse = $sfKernel->handle($sfRequest);
$sfResponse->send();

$sfKernel->terminate($sfRequest, $sfResponse);
```

### 4) Edit .htaccess file on your Wordpress root project directory

Put the following rules:

```
DirectoryIndex index.php

<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteCond %{REQUEST_URI}::$1 ^(/.+)/(.*)::\2$
    RewriteRule ^(.*) - [E=BASE:%1]

    RewriteCond %{ENV:REDIRECT_STATUS} ^$
    RewriteRule ^index\.php(/(.*)|$) %{ENV:BASE}/$2 [R=301,L]

    RewriteCond %{REQUEST_FILENAME} -f [OR]
    RewriteCond %{REQUEST_FILENAME} -d
    RewriteRule .? - [L]

    # Rewrite all other queries to the front controller.
    RewriteRule .? %{ENV:BASE}/index.php [L]
</IfModule>

<IfModule !mod_rewrite.c>
    <IfModule mod_alias.c>
        RedirectMatch 302 ^/$ /index.php/
    </IfModule>
</IfModule>
```

You're ready to go.

---

## Use in Symfony

You can call Wordpress table managers in Symfony by calling the following services:

*Service identifier* | *Type*
--- | ---
ekino.wordpress.manager.comment | Wordpress comment manager
ekino.wordpress.manager.comment_meta | Wordpress comment metas manager
ekino.wordpress.manager.link | Wordpress link manager
ekino.wordpress.manager.option | Wordpress option manager
ekino.wordpress.manager.post | Wordpress post manager
ekino.wordpress.manager.post_meta | Wordpress post metas manager
ekino.wordpress.manager.term | Wordpress term manager
ekino.wordpress.manager.term_relationships | Wordpress term relationships manager
ekino.wordpress.manager.term_taxonomy | Wordpress taxonomy manager
ekino.wordpress.manager.user | Wordpress user manager
ekino.wordpress.manager.user_meta | Wordpress user metas manager

So in custom Symfony controllers, you can create / update / delete data in Wordpress database, like that:

```php
# Here an example that sets user #2 as author for post #1
$postManager = $this->get('ekino.wordpress.manager.post');
$userManager = $this->get('ekino.wordpress.manager.user');

$user = $userManager->find(2);

$post = $postManager->find(1);
$post->setAuthor($user);

$postManager->save($post);
```

---

## Use in Wordpress

### Call a service from Symfony container

Simply use the `symfony()` method and call your custom service like that:

```php
$service = symfony('my.custom.symfony.service');
```
