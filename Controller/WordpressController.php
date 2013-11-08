<?php

namespace Ekino\WordpressBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ekino\WordpressBundle\Wordpress\Wordpress;

/**
 * Class WordpressController
 *
 * This is the controller to render Wordpress content
 *
 * @author Vincent Composieux <composieux@ekino.com>
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
        return $this->getWordpress()->getResponse();
    }

    /**
     * Returns Wordpress service
     *
     * @return Wordpress
     */
    protected function getWordpress()
    {
        return $this->get('ekino.wordpress.wordpress');
    }

    public function customAction()
    {
        $this->getWordpress()->getContent();

        global $wp;
        var_dump($wp); exit;
    }
}
