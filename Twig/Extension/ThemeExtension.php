<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Twig\Extension;

/**
 * Class ThemeExtension.
 *
 * This extension provides native Wordpress theme integration into Symfony.
 *
 * @author Vincent Composieux <vincent.composieux@gmail.com>
 */
class ThemeExtension extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'ekino_wordpress_theme';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('wp_get_header', [$this, 'getHeader'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('wp_get_sidebar', [$this, 'getSidebar'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('wp_get_footer', [$this, 'getFooter'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Displays Wordpress theme header (and administration menu bar if available).
     */
    public function getHeader()
    {
        \get_header();

        \_wp_admin_bar_init();
        \wp_admin_bar_render();
    }

    /**
     * Displays Wordpress theme sidebar.
     */
    public function getSidebar()
    {
        \get_sidebar();
    }

    /**
     * Displays Wordpress theme footer.
     */
    public function getFooter()
    {
        \get_footer();
    }
}
