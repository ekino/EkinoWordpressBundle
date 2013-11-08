<?php

namespace Ekino\WordpressBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Ekino\WordpressBundle\Wordpress\WordpressResponse;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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
        $content = $this->getWordpressContent();

        return new WordpressResponse($content);
    }

    /**
     * Returns Wordpress content
     *
     * @return string
     */
    protected function getWordpressContent()
    {
        ob_start();

        define('WP_USE_THEMES', true);

        global $wp, $wp_the_query, $wp_query, $allowedentitynames;

        require_once $this->getRootDir() . '/../../wp-blog-header.php';

        return ob_get_clean();
    }

    /**
     * Returns Symfony kernel root directory
     *
     * @return string
     */
    protected function getRootDir()
    {
        return $this->get('kernel')->getRootDir();
    }
}
