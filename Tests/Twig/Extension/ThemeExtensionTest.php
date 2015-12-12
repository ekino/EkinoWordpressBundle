<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace {
    /**
     * @return string
     */
    function get_header()
    {
        echo 'wordpress header';
    }

    /**
     * @return string
     */
    function get_sidebar()
    {
        echo 'wordpress sidebar';
    }

    /**
     * @return string
     */
    function get_footer()
    {
        echo 'wordpress footer';
    }

    /**
     * @return string
     */
    function _wp_admin_bar_init()
    {
        echo 'wordpress admin bar init';
    }

    /**
     * @return string
     */
    function wp_admin_bar_render()
    {
        echo 'wordpress admin bar render';
    }
}

namespace Ekino\WordpressBundle\Tests\Twig\Extension {

    use Ekino\WordpressBundle\Twig\Extension\ThemeExtension;

    /**
     * Class ThemeExtensionTest.
     *
     * @author Vincent Composieux <vincent.composieux@gmail.com>
     */
    class ThemeExtensionTest extends \PHPUnit_Framework_TestCase
    {
        /**
         * @var ThemeExtension
         */
        protected $extension;

        /**
         * Sets up a Twig Theme extension instance.
         */
        protected function setUp()
        {
            if (!class_exists('\Twig_Extension')) {
                $this->markTestSkipped('Twig is not enabled');
            }

            $this->extension = new ThemeExtension();
        }

        /**
         * Tests the getName() method.
         */
        public function testGetName()
        {
            $this->assertEquals('ekino_wordpress_theme', $this->extension->getName());
        }

        /**
         * Tests the getFunctions() method.
         */
        public function testGetFunctions()
        {
            $this->assertContainsOnly('\Twig_SimpleFunction', $this->extension->getFunctions());
        }

        /**
         * Tests the getHeader() method (Twig function: wp_get_header()).
         */
        public function testGetHeader()
        {
            ob_start();
            $this->extension->getHeader();
            $content = ob_get_clean();

            $this->assertEquals('wordpress headerwordpress admin bar initwordpress admin bar render', $content);
        }

        /**
         * Tests the getSidebar() method (Twig function: wp_get_sidebar()).
         */
        public function testGetSidebar()
        {
            ob_start();
            $this->extension->getSidebar();
            $content = ob_get_clean();

            $this->assertEquals('wordpress sidebar', $content);
        }

        /**
         * Tests the getFooter() method (Twig function: wp_get_footer()).
         */
        public function testGetFooter()
        {
            ob_start();
            $this->extension->getFooter();
            $content = ob_get_clean();

            $this->assertEquals('wordpress footer', $content);
        }
    }
}
