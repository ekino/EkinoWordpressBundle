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
}
