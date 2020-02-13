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

use Ekino\WordpressBundle\Entity\Post;
use Ekino\WordpressBundle\Manager\PostManager;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class PostExtension.
 *
 * This extension provides native Wordpress functions into Twig.
 */
class PostExtension extends AbstractExtension
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
     * @param PostManager     $postManager     A post manager instance
     * @param OptionExtension $optionExtension A Twig option extension instance
     * @param string|null     $cookieHash      A cookie has string
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
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('wp_comments_open', [$this, 'isCommentingOpened']),
            new TwigFunction('wp_get_permalink', [$this, 'getPermalink']),
            new TwigFunction('wp_get_the_post_thumbnail_url', [$this, 'getThumbnailUrl']),
            new TwigFunction('wp_have_comments', [$this, 'haveComments']),
            new TwigFunction('wp_post_password_required', [$this, 'isPostPasswordRequired'], ['needs_context' => true]),
        ];
    }

    /**
     * @param int|Post $postId     A Wordpress post identifier
     * @param bool     $isAbsolute Determines if you want to retrieve an absolute URL
     *
     * @throws \UnexpectedValueException
     *
     * @return string
     */
    public function getPermalink($postId, $isAbsolute = false)
    {
        $post = $postId instanceof Post ? $postId : $this->postManager->find($postId);

        if (!$post) {
            throw new \UnexpectedValueException(sprintf('No post with ID "%d"', $postId));
        }

        $permalinkStructure = $this->optionExtension->getOption('permalink_structure', '')->getValue();

        $relativeUrl = $this->replacePostArguments($permalinkStructure, $post);

        if ($isAbsolute) {
            $home = $this->optionExtension->getOption('home');

            return rtrim($home->getValue(), '/').'/'.ltrim($relativeUrl, '/');
        }

        return $relativeUrl;
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
