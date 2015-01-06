<?php

namespace Ekino\WordpressBundle\Event\Subscriber;

use Ekino\WordpressBundle\Wordpress\Wordpress;
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
     * @var Wordpress
     */
    protected $wordpress;

    /**
     * @param string|array $httpHeaderCallback
     * @param Wordpress    $wordpress
     */
    public function __construct($httpHeaderCallback, Wordpress $wordpress)
    {
        $this->httpHeaderCallback = $httpHeaderCallback;
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

        $callback = $this->getHttpHeadersCallback();
        $wpHeaders = (array) call_user_func_array($callback, array($event->getRequest()->getUri()));

        foreach ($wpHeaders as $name => $value) {
            if ($name == 'cache-control') {
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
