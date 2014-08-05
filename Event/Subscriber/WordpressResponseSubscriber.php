<?php

namespace Ekino\WordpressBundle\Event\Subscriber;

use Ekino\WordpressBundle\Wordpress\WordpressResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class WordpressResponseSubscriber implements EventSubscriberInterface
{
    /**
     * @var string|array
     */
    protected $httpHeaderCallback;

    /**
     * @param string|array $httpHeaderCallback
     */
    public function __construct($httpHeaderCallback)
    {
        $this->httpHeaderCallback = $httpHeaderCallback;
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

        /** @var \WP_Query|null $wp_query */
        global $wp_query;

        if (!$wp_query) {
            return;
        }

        $callback = $this->getHttpHeadersCallback();
        $wpHeaders = (array) $callback($event->getRequest()->getUri());

        foreach ($wpHeaders as $name => $value) {
            // TODO add cache headers support
            if ($name == 'cache-control') {
                //$response->setCache($this->parseCacheHeaders($value));
                continue;
            }

            $response->headers->set($name, $value);
        }

        if ($wp_query->is_404()) {
            $response->setStatusCode(404);
        }
    }

    /**
     * @return string
     */
    public function getHttpHeadersCallback()
    {
        return $this->httpHeaderCallback;
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