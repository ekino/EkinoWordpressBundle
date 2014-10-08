<?php

namespace Ekino\WordpressBundle\Twig\Extension;

use Ekino\WordpressBundle\Manager\CommentManager;
use Ekino\WordpressBundle\Model\Comment;

class CommentExtension extends \Twig_Extension
{
    /**
     * @var CommentManager
     */
    protected $commentManager;

    /**
     * @param CommentManager $commentManager
     */
    public function __construct(CommentManager $commentManager)
    {
        $this->commentManager = $commentManager;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'ekino_wordpress_comment';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('wp_get_comment_author_link', array($this, 'getCommentAuthorLink'), array('is_safe' => array('html'))),
        );
    }

    /**
     * @param Comment $comment
     *
     * @return string
     */
    public function getCommentAuthorLink(Comment $comment)
    {
        if (!$user = $comment->getUser()) {
            if ((!$authorUrl = $comment->getAuthorUrl()) || !preg_match('/^http(s)?:\/\/.+$/', $authorUrl)) {
                return $comment->getAuthor();
            }

            return sprintf('<a href="%s" rel="nofollow" target="_new">%s</a>', $authorUrl, $comment->getAuthor());
        }

        if ((!$userUrl = $user->getUrl()) || !preg_match('/^http(s)?:\/\/.+$/', $userUrl)) {
            return $user->getDisplayName();
        }

        return sprintf('<a href="%s" rel="nofollow" target="_new">%s</a>', $userUrl, $user->getDisplayName());
    }
}
