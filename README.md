EkinoWordpressBundle
====================

[![Build Status](https://secure.travis-ci.org/ekino/EkinoWordpressBundle.png?branch=master)](http://travis-ci.org/ekino/EkinoWordpressBundle) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/e842da81-16e2-47ee-9966-5112bbe1ab9c/mini.png)](https://insight.sensiolabs.com/projects/e842da81-16e2-47ee-9966-5112bbe1ab9c)

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
    security:
        firewall_name: secured_area # This is the firewall default name
        login_url: "/wp-login.php" # Absolute URL to the wordpress login page
```

Also optionally, if you want to use `UserHook` to authenticate on Symfony, you should add this configuration to your `app/security.yml`:

```yml
security:
    providers:
        main:
            entity: { class: Ekino\WordpressBundle\Entity\User, property: login }

    # Example firewall for an area within a Symfony application protected by a WordPress login
    firewalls:
        secured_area:
            pattern:    ^/admin
            access_denied_handler: ekino.wordpress.security.entry_point
            entry_point: ekino.wordpress.security.entry_point
            anonymous: ~

    access_control:
        - { path: ^/admin, roles: ROLE_WP_ADMINISTRATOR }
```

### 3) Wrap code inside web/app.php and web/app_dev.php

To avoid problem with some Wordpress plugin, you need to wrap code inside a function like this :
```php
<?php
use Symfony\Component\HttpFoundation\Request;

// change for app_dev.php
function run(){
    $loader = require_once __DIR__.'/../app/bootstrap.php.cache';

    require_once __DIR__.'/../app/AppKernel.php';

    $kernel = new AppKernel('dev', true);
    $kernel->loadClassCache();
    Request::enableHttpMethodParameterOverride();
    $request = Request::createFromGlobals();
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
}
run();
```

And now do the same for app.php

### 4) Update your Wordpress index.php file to load Symfony libraries

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

### 5) Edit .htaccess file on your Wordpress root project directory

Put the following rules:

```
DirectoryIndex index.php
IndexIgnore /symfony

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

# Extra
## Enable cross application I18n support

If you already have a wordpress plugin to handle I18n, EkinoWordpressBundle allow to persist language toggle between Symfony and wordpress.
To do so, just grab the cookie name from the wordpress plugin used and provide its name in the configuration as follow :

```yml
ekino_wordpress:
    i18n_cookie_name: pll_language # This value is the one used in "polylang" WP plugin
```

Also, you can implement your own language switcher in Symfony that work cross application. For instance :

```php
<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class LanguageController extends Controller
{
    /**
     * @param Request $request
     * @param string $locale
     *
     * @return RedirectResponse
     */
    public function toggleLocaleAction(Request $request, $locale)
    {
        $response = new RedirectResponse($this->generateUrl('homepage'));
        $response->headers->setCookie(new Cookie($this->getWpCookieName(), $locale, time() + 31536000, '/', $request->getHost()));

        return $response;
    }

    /**
     * @return string
     */
    protected function getWpCookieName()
    {
        return $this->container->getParameter('ekino.wordpress.i18n_cookie_name');
    }
}
```

## Handle with password protected post
If you use password protected posts and you have defined your own `COOKIEHASH` constant, you can provide it using the `cookie_hash` parameter in your `config.yml` file.
You will then be able to use the `wp_post_password_required` twig function that behave exactly like `post_password_required` Wordpress function.

