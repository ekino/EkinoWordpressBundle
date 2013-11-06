EkinoWordpressBundle
####################

This bundle is used to bring some Symfony services into Wordpress.

Here are some examples:

* Use custom Symfony services into Wordpress,
* Use Symfony to manipulate Wordpress database,
* Create custom Symfony routes out of Wordpress

Installation
------------

### 1) Install Symfony into your Wordpress project

Install your Wordpress project and/or get into your root project directory and install symfony like this:

`php composer.phar create-project symfony/framework-standard-edition symfony/`

### 2) Update your Symfony composer.json file to add ekino/wordpress-bundle

After, edit `symfony/composer.json` file to add the official Wordpress git repository:

```yml
"require": {
    ...
    "ekino/wordpress-bundle": "dev-master"
},
```

Run `php composer.phar update ekino/wordpress-bundle`

Then, add the bundle into `symfony/AppKernel.php`:

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

### 3) Update your Wordpress index.php file to load Symfony

```php
<?php

use Ekino\WordpressBundle\Wordpress\WordpressResponse;
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/symfony/app/bootstrap.php.cache';

// Load application kernel
require_once __DIR__.'/symfony/app/AppKernel.php';

$sfKernel = new AppKernel('prod', false);
$sfKernel->loadClassCache();
$sfKernel->boot();

// Add Symfony container as a global variable to be used in Wordpress
global $sfContainer;

$sfContainer = $sfKernel->getContainer();

// Handle request to return a Response or a WordpressResponse
$sfRequest = Request::createFromGlobals();
$sfResponse = $sfKernel->handle($sfRequest);

if ($sfResponse instanceof WordpressResponse) {
    define('WP_USE_THEMES', true);

    require( dirname( __FILE__ ) . '/wp-blog-header.php' );
} else {
    $sfResponse->send();
}

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

Use in Symfony
----------------

You can call Wordpress table managers in Symfony by calling the following services:

- ekino.wordpress.manager.comment
- ekino.wordpress.manager.comment_meta
- ekino.wordpress.manager.link
- ekino.wordpress.manager.option
- ekino.wordpress.manager.post
- ekino.wordpress.manager.post_meta
- ekino.wordpress.manager.term
- ekino.wordpress.manager.term_relationships
- ekino.wordpress.manager.term_taxonomy
- ekino.wordpress.manager.user
- ekino.wordpress.manager.user_meta

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

Use in Wordpress
----------------

### Call a service from Symfony container

Simply use the `$sfContainer` global variable and call your custom service like that:

```php
$service = $sfContainer->get('my.custom.symfony.service');
```