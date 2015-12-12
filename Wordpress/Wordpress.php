<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Wordpress;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class Wordpress.
 *
 * This is the main application class that initializes Wordpress application
 * to retrieve the content and returns a Wordpress response
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class Wordpress
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var array
     */
    protected $globals;

    /**
     * @var string
     */
    protected $directory;

    /**
     * @var WordpressResponse
     */
    protected $response;

    /**
     * @var bool
     */
    protected $alreadyInitialized;

    /**
     * Constructor.
     *
     * @param KernelInterface $kernel    Symfony kernel instance
     * @param array           $globals   A Wordpress global variables array
     * @param string          $directory A wordpress directory (if specified in configuration)
     */
    public function __construct(KernelInterface $kernel, array $globals, $directory = null)
    {
        $this->kernel = $kernel;
        $this->globals = $globals;
        $this->directory = $directory;
    }

    /**
     * Initializes Wordpress.
     *
     * @return $this
     */
    public function initialize()
    {
        $content = $this->getContent();
        $this->response = new WordpressResponse($content);

        return $this;
    }

    /**
     * Returns Wordpress content.
     *
     * @throws \InvalidArgumentException if Wordpress loader cannot be found
     *
     * @return string
     */
    public function getContent()
    {
        ob_start();

        define('WP_USE_THEMES', true);

        $this->loadWordpress();

        return ob_get_clean();
    }

    /**
     * Loads Wordpress.
     */
    public function loadWordpress()
    {
        if (!$this->alreadyInitialized) {
            foreach ($this->globals as $globalVariable) {
                global ${$globalVariable};
            }

            $loader = $this->getWordpressDirectory().'wp-blog-header.php';

            if (!file_exists($loader)) {
                throw new \InvalidArgumentException(
                    sprintf('Unable to find Wordpress loader in: "%s".', $loader)
                );
            }

            require_once $loader;

            $this->alreadyInitialized = true;
        }
    }

    /**
     * Returns Wordpress content response.
     *
     * @return WordpressResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return null|\WP_Query
     */
    public function getWpQuery()
    {
        global $wp_query;

        if (null === $wp_query || !$wp_query instanceof \WP_Query) {
            return;
        }

        return $wp_query;
    }

    /**
     * Returns Wordpress directory if specified in configuration
     * otherwise returns default structure:.
     *
     * wordpress
     *     |- symfony <-- Symfony application must be installed on Wordpress root directory by default
     *     |- index.php
     *     |- wp-content
     *     |- ...
     *
     * @return string
     */
    protected function getWordpressDirectory()
    {
        $directory = $this->directory ?: sprintf('%s/../../', $this->kernel->getRootDir());

        return '/' == substr($directory, -1) ? $directory : $directory.'/';
    }
}
