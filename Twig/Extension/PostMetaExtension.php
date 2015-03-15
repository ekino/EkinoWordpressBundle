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

use Ekino\WordpressBundle\Manager\PostMetaManager;

/**
 * Class PostMetaExtension
 *
 * This extension provides native Wordpress functions into Twig.
 */
class PostMetaExtension extends \Twig_Extension
{
    /**
     * @var PostMetaManager
     */
    protected $postMetaManager;

    /**
     * @param PostMetaManager $postMetaManager
     */
    public function __construct(PostMetaManager $postMetaManager)
    {
        $this->postMetaManager = $postMetaManager;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ekino_wordpress_post_meta';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('wp_get_post_meta', array($this, 'getPostMeta')),
        );
    }

    /**
     * @param int    $postId
     * @param string $metaName
     * @param bool   $fetchOneResult
     *
     * @return array|\Ekino\WordpressBundle\Entity\PostMeta
     */
    public function getPostMeta($postId, $metaName, $fetchOneResult = false)
    {
        return $this->postMetaManager->getPostMeta($postId, $metaName, $fetchOneResult);
    }
}
