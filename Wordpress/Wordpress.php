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
     * Constructor
     *
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
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
     */
    public function getContent()
    {
        ob_start();

        define('WP_USE_THEMES', true);
        global $wp, $wp_the_query, $wp_query, $allowedentitynames;

        require_once $this->getWordpressDirectory() . 'wp-blog-header.php';

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
     * Returns Wordpress directory
     *
     * @return string
     */
    protected function getWordpressDirectory()
    {
        return sprintf('%s/../../', $this->kernel->getRootDir());
    }
}