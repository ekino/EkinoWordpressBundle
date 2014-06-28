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
    public function __contruct(OptionManager $optionManager)
    {
        $this->optionManager = $optionManager;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_option', array($this, 'getOption')),
        );
    }

    /**
     * @param string $optionName
     * @param mixed $defaultValue
     *
     * @return mixed
     */
    public function getOption($optionName, $defaultValue = null)
    {
        $option = $this->optionManager->findOneByOptionName($optionName);

        return $option ?: $defaultValue;
    }
}