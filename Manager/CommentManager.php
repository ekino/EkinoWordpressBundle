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
    const TYPE_PINGBACK = 'pingback';
    const TYPE_TRACKBACK = 'trackback';

    const APPROVED_PENDING = 0;
    const APPROVED_APPROVED = 1;
    const APPROVED_POST_TRASHED = 'post-trashed';
    const APPROVED_SPAM = 'spam';
    const APPROVED_TRASH = 'trash';

    /**
     * @param Comment $comment
     *
     * @return bool
     */
    public function isPingback(Comment $comment)
    {
        return static::TYPE_PINGBACK == $comment->getType();
    }

    /**
     * @param Comment $comment
     *
     * @return bool
     */
    public function isTrackback(Comment $comment)
    {
        return static::TYPE_TRACKBACK == $comment->getType();
    }
}