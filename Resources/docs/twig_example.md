# Example for Twig

## Theme Wordpress TwentyTwenty

This is a dummy example for a page controlled by Symfony.

### Twig

```jinja
{{ wp_get_header() }}

<main id="site-content" role="main">

    <article class="post-1 page type-page status-publish hentry">

        <header class="entry-header has-text-align-center header-footer-group">

            <div class="entry-header-inner section-inner medium">

                {% if archive_title is defined and archive_title is not empty %}
                    <h1 class="entry-title">{{ archive_title }}</h1>
                {% endif %}

                {% if archive_subtitle is defined and archive_subtitle is not empty %}
                    <div class="entry-subtitle section-inner thin max-percentage intro-text">{{ archive_subtitle|striptags('strong') }}</div>
                {% endif %}

            </div><!-- .entry-header-inner -->

        </header>

        <div class="post-inner thin ">

            <div class="entry-content">
                {{ msg|raw }}
            </div>
        </div>

    </article>
</main>

{{ wp_get_template_part( 'template-parts/footer-menus-widgets' ) }}

{{ wp_get_footer() }}
```

### Controller

The route _/example_ is dealt with Symfony.

```php
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/example", name="sf_example", methods={"GET"})
     */
    public function index()
    {
        return $this->render('index.html.twig', [
            'archive_title' => 'Archive title',
            'archive_subtitle' => 'Archive subtitle',
            'msg' => '<p>Hello world!</p>'
        ]);
    }
}

```
