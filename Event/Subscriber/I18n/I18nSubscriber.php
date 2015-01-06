<?php

namespace Ekino\WordpressBundle\Event\Subscriber\I18n;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class I18nSubscriber implements EventSubscriberInterface
{
    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var string
     */
    protected $wordpressI18nCookieName;

    /**
     * @param string $defaultLocale
     * @param string $wordpressI18nCookieName
     */
    public function __construct($defaultLocale, $wordpressI18nCookieName)
    {
        $this->defaultLocale = $defaultLocale;
        $this->wordpressI18nCookieName = $wordpressI18nCookieName;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->getSession();
        $locale = $request->cookies->get($this->wordpressI18nCookieName, $session->get('_locale', $this->defaultLocale));

        $session->set('_locale', $locale);
        $request->setLocale($locale);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }
}
