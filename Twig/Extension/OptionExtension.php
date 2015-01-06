<?php

namespace Ekino\WordpressBundle\Twig\Extension;

use Ekino\WordpressBundle\Manager\OptionManager;

class OptionExtension extends \Twig_Extension
{
    /**
     * @var OptionManager
     */
    protected $optionManager;

    /**
     * @return string
     */
    public function getName()
    {
        return 'ekino_wordpress_option';
    }

    /**
     * @param OptionManager $optionManager
     */
    public function __construct(OptionManager $optionManager)
    {
        $this->optionManager = $optionManager;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('wp_get_option', array($this, 'getOption')),
            new \Twig_SimpleFunction('wp_is_active_sidebar', array($this, 'isActiveSidebar')),
        );
    }

    /**
     * @param string $optionName
     * @param mixed  $defaultValue
     *
     * @return mixed
     */
    public function getOption($optionName, $defaultValue = null)
    {
        $option = $this->optionManager->findOneByOptionName($optionName);

        return $option ?: $defaultValue;
    }

    /**
     * @param string $sidebarName
     *
     * @return bool
     */
    public function isActiveSidebar($sidebarName)
    {
        return $this->optionManager->isActiveSidebar($sidebarName);
    }
}
