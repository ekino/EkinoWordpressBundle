<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Event\Subscriber;

use Ekino\WordpressBundle\Wordpress\Wordpress;
use Ekino\WordpressBundle\Wordpress\WordpressResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Class WordpressResponseSubscriber
 *
 * This listener allows to manage Wordpress response into Symfony.
 */
class WordpressResponseSubscriber implements EventSubscriberInterface
{
    /**
     * @var Wordpress
     */
    protected $wordpress;

    /**
     * @param Wordpress $wordpress
     */
    public function __construct(Wordpress $wordpress)
    {
        $this->wordpress = $wordpress;
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        if (!$response instanceof WordpressResponse || $event->getRequestType() != HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        if (!$wp_query = $this->wordpress->getWpQuery()) {
            return;
        }

        if ($wp_query->is_404()) {
            $response->setStatusCode(404);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::RESPONSE => array('onKernelResponse'),
        );
    }
}
