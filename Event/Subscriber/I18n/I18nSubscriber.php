<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Event\Subscriber\I18n;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class I18nSubscriber.
 *
 * This class allows to inject the Wordpress locale cookie value into the Symfony request.
 */
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
     * Constructor.
     *
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
        return [
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => [['onKernelRequest', 17]],
        ];
    }
}
