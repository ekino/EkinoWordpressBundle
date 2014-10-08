<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Manager;

use Ekino\WordpressBundle\Model\Post;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class PostManager
 *
 * This is the Post entity manager
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class PostManager extends BaseManager
{
    /**
     * @param Post $post
     *
     * @return bool
     */
    public function isCommentingOpened(Post $post)
    {
        return Post::COMMENT_STATUS_OPEN == $post->getCommentStatus();
    }

    /**
     * @param Post $post
     *
     * @return bool
     */
    public function hasComments(Post $post)
    {
        return 0 < $post->getCommentCount();
    }

    /**
     * @param Post $post
     * @param Request $request
     * @param string $cookieHash
     *
     * @return bool
     */
    public function isPasswordRequired(Post $post, Request $request, $cookieHash)
    {
        if (!$post->getPassword()) {
            return false;
        }

        $cookies = $request->cookies;

        if (!$cookies->has('wp-postpass_' . $cookieHash)) {
            return true;
        }

        $hash = stripslashes($cookies->get('wp-postpass_' . $cookieHash));

        if (0 !== strpos($hash, '$P$B')) {
            return true;
        }

        $wpHasher = new \PasswordHash(8, true);

        return !$wpHasher->CheckPassword($post->getPassword(), $hash);
    }
}
