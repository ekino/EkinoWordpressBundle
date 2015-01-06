<?php

namespace Ekino\WordpressBundle\Twig\Extension;

use Ekino\WordpressBundle\Entity\Post;
use Ekino\WordpressBundle\Manager\PostManager;

class PostExtension extends \Twig_Extension
{
    /**
     * @var PostManager
     */
    protected $postManager;

    /**
     * @var OptionExtension
     */
    protected $optionExtension;

    /**
     * @var string|null
     */
    protected $cookieHash;

    /**
     * @param PostManager     $postManager
     * @param OptionExtension $optionExtension
     * @param string|null     $cookieHash
     */
    public function __construct(PostManager $postManager, OptionExtension $optionExtension, $cookieHash = null)
    {
        $this->postManager = $postManager;
        $this->optionExtension = $optionExtension;
        $this->cookieHash = $cookieHash;
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
            new \Twig_SimpleFunction('wp_comments_open', array($this, 'isCommentingOpened')),
            new \Twig_SimpleFunction('wp_get_permalink', array($this, 'getPermalink')),
            new \Twig_SimpleFunction('wp_get_the_post_thumbnail_url', array($this, 'getThumbnailUrl')),
            new \Twig_SimpleFunction('wp_have_comments', array($this, 'haveComments')),
            new \Twig_SimpleFunction('wp_post_password_required', array($this, 'isPostPasswordRequired'), array('needs_context' => true)),
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
     * @param Post   $post
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

    /**
     * @param array $context
     * @param Post  $post
     *
     * @return bool
     */
    public function isPostPasswordRequired(array $context, Post $post)
    {
        if (null === $this->cookieHash) {
            $this->cookieHash = '';

            if ($siteUrlOption = $this->optionExtension->getOption('siteurl')) {
                $this->cookieHash = md5($siteUrlOption->getValue());
            }
        }

        return $this->postManager->isPasswordRequired($post, $context['app']->getRequest(), $this->cookieHash);
    }

    /**
     * @param Post $post
     *
     * @return bool
     */
    public function isCommentingOpened(Post $post)
    {
        return $post->isCommentingOpened();
    }

    /**
     * @param Post $post
     *
     * @return bool
     */
    public function haveComments(Post $post)
    {
        return 0 < $post->getCommentCount();
    }

    /**
     * @param Post $post
     *
     * @return string
     */
    public function getThumbnailUrl(Post $post)
    {
        return $this->postManager->getThumbnailPath($post);
    }
}
