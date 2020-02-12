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

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class ThemeExtension.
 *
 * This extension provides native Wordpress theme integration into Symfony.
 *
 * @author Vincent Composieux <vincent.composieux@gmail.com>
 */
class ThemeExtension extends AbstractExtension
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
            new TwigFunction('wp_get_header', [$this, 'getHeader'], ['is_safe' => ['html']]),
            new TwigFunction('wp_get_sidebar', [$this, 'getSidebar'], ['is_safe' => ['html']]),
            new TwigFunction('wp_get_footer', [$this, 'getFooter'], ['is_safe' => ['html']]),
            new TwigFunction('wp_get_template_part', [$this, 'getTemplatePart'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Displays Wordpress theme header (and administration menu bar if available).
     *
     * @param string|null $name
     */
    public function getHeader($name = null)
    {
        \get_header($name);

        \_wp_admin_bar_init();
        \wp_admin_bar_render();
    }

    /**
     * Displays Wordpress theme sidebar.
     *
     * @param string|null $name
     */
    public function getSidebar($name = null)
    {
        \get_sidebar($name);
    }

    /**
     * Displays Wordpress theme footer.
     *
     * @param string|null $name
     */
    public function getFooter($name = null)
    {
        \get_footer($name);
    }

    /**
     * Displays a Wordpress theme template part.
     *
     * @param string $slug
     * @param string|null $name
     */
    public function getTemplatePart($slug, $name = null)
    {
        \get_template_part($slug, $name);
    }
}
