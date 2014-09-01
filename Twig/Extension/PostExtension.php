<?php

namespace Ekino\WordpressBundle\Twig\Extension;

use Ekino\WordpressBundle\Model\Post;
use Ekino\WordpressBundle\Manager\PostManager;

class PostExtension extends \Twig_Extension
{
    /**
     * @var \Ekino\WordpressBundle\Manager\PostManager
     */
    protected $postManager;

    /**
     * @var OptionExtension
     */
    protected $optionExtension;

    /**
     * @param PostManager $postManager
     * @param OptionExtension $optionExtension
     */
    public function __construct(PostManager $postManager, OptionExtension $optionExtension)
    {
        $this->postManager = $postManager;
        $this->optionExtension = $optionExtension;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ekino_wordpress_post';
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('wp_get_permalink', array($this, 'getPermalink')),
        );
    }

    /**
     * @param int|Post $postId
     *
     * @return string
     *
     * @throws \UnexpectedValueException
     */
    public function getPermalink($postId)
    {
        $post = $postId instanceof Post ? $postId : $this->postManager->find($postId);

        if (!$post) {
            throw new \UnexpectedValueException(sprintf('No post with ID "%d"', $postId));
        }

        $permalinkStructure = $this->optionExtension->getOption('permalink_structure', '')->getValue();

        return $this->replacePostArguments($permalinkStructure, $post);
    }

    /**
     * @param string $permalinkStructure
     * @param Post $post
     *
     * @return string
     */
    public function replacePostArguments($permalinkStructure, Post $post)
    {
        $postDate = $post->getDate();

        $permalinkStructure = str_replace('%year%', $postDate->format('Y'), $permalinkStructure);
        $permalinkStructure = str_replace('%monthnum%', $postDate->format('m'), $permalinkStructure);
        $permalinkStructure = str_replace('%day%', $postDate->format('d'), $permalinkStructure);
        $permalinkStructure = str_replace('%post_id%', $post->getId(), $permalinkStructure);
        $permalinkStructure = str_replace('%postname%', $post->getName(), $permalinkStructure);

        return $permalinkStructure;
    }
}