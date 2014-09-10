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

use Ekino\WordpressBundle\Model\Comment;

/**
 * Class CommentManager
 *
 * This is the Comment entity manager
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class CommentManager extends BaseManager
{
    /**
     * @param Comment $comment
     *
     * @return bool
     */
    public function isPingback(Comment $comment)
    {
        return Comment::TYPE_PINGBACK == $comment->getType();
    }

    /**
     * @param Comment $comment
     *
     * @return bool
     */
    public function isTrackback(Comment $comment)
    {
        return Comment::TYPE_TRACKBACK == $comment->getType();
    }
}