<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Controller;

use Ekino\WordpressBundle\Wordpress\WordpressResponse;
use Ekino\WordpressBundle\Wordpress\Wordpress;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

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
     *
     * @throws NotAcceptableHttpException
     */
    public function catchAllAction()
    {
        $content = $this->getWordpress()->getContent();

        global $wp_query;

        if (!$wp_query) {
            throw new NotAcceptableHttpException('The "$wp_query" wordpress global variable is not defined');
        }

        return new WordpressResponse($content, $wp_query->is_404() ? WordpressResponse::HTTP_NOT_FOUND : WordpressResponse::HTTP_OK);
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
}
