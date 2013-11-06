<?php

namespace Ekino\WordpressBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Ekino\WordpressBundle\Wordpress\WordpressResponse;

/**
 * Class WordpressController
 *
 * This is the controller to render Wordpress content
 */
class WordpressController extends Controller
{
    /**
     * Wordpress catch-all route action
     *
     * @return WordpressResponse
     */
    public function catchAllAction()
    {
        return new WordpressResponse();
    }

    /**
     * Wordpress custom route test
     *
     * @return Response
     */
    public function customAction()
    {
        return $this->render('EkinoWordpressBundle:Wordpress:custom.html.twig');
    }
}
