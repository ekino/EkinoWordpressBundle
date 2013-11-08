<?php

namespace Ekino\WordpressBundle\Wordpress;

use Symfony\Component\HttpKernel\KernelInterface;

use Ekino\WordpressBundle\Wordpress\WordpressResponse;

/**
 * Class Wordpress
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
     * @var WordpressResponse
     */
    protected $response;

    /**
     * @var string
     */
    protected $wordpressDirectory;

    /**
     * Constructor
     *
     * @param KernelInterface $kernel    Symfony kernel instance
     * @param string          $directory A wordpress directory (if specified in configuration)
     */
    public function __construct(KernelInterface $kernel, $directory = null)
    {
        $this->kernel    = $kernel;
        $this->directory = $directory;
    }

    /**
     * Initialize Wordpress
     */
    public function initialize()
    {
        $content = $this->getContent();
        $this->response = new WordpressResponse($content);
    }

    /**
     * Returns Wordpress content
     *
     * @return string
     *
     * @throws \InvalidArgumentException if Wordpress loader cannot be found
     */
    public function getContent()
    {
        ob_start();

        define('WP_USE_THEMES', true);

        global $wp, $wp_the_query, $wp_query, $allowedentitynames;

        $loader = $this->getWordpressDirectory() . 'wp-blog-header.php';

        if (!file_exists($loader)) {
            throw new \InvalidArgumentException(
                sprintf('Unable to find Wordpress loader in: "%s".', $loader)
            );
        }

        require_once $loader;

        return ob_get_clean();
    }

    /**
     * Returns Wordpress content response
     *
     * @return WordpressResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Returns Wordpress directory if specified in configuration
     * otherwise returns default structure:
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

        return '/' == substr($directory, -1) ? $directory : $directory . '/';
    }
}