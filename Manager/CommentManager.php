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
    const COMMENT_TYPE_PINGBACK = 'pingback';
    const COMMENT_TYPE_TRACKBACK = 'trackback';

    const COMMENT_APPROVED_PENDING = 0;
    const COMMENT_APPROVED_APPROVED = 1;
    const COMMENT_APPROVED_POST_TRASHED = 'post-trashed';
    const COMMENT_APPROVED_SPAM = 'spam';
    const COMMENT_APPROVED_TRASH = 'trash';

    /**
     * @param Comment $comment
     *
     * @return bool
     */
    public function isPingback(Comment $comment)
    {
        return static::COMMENT_TYPE_PINGBACK == $comment->getType();
    }

    /**
     * @param Comment $comment
     *
     * @return bool
     */
    public function isTrackback(Comment $comment)
    {
        return static::COMMENT_TYPE_TRACKBACK == $comment->getType();
    }
}