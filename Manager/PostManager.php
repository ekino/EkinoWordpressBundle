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

/**
 * Class PostManager
 *
 * This is the Post entity manager
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class PostManager extends BaseManager
{
    const COMMENT_STATUS_OPEN = 'open';
    const COMMENT_STATUS_CLOSED = 'closed';

    // @see http://codex.wordpress.org/Post_Status
    const STATUS_PUBLISHED  = 'publish';
    const STATUS_FUTURE     = 'future';
    const STATUS_DRAFT      = 'draft';
    const STATUS_PENDING    = 'pending';
    const STATUS_PRIVATE    = 'private';
    const STATUS_TRASH      = 'trash';
    const STATUS_AUTODRAFT  = 'auto-draft';
    const STATUS_INHERIT    = 'inherit';

    // @see http://codex.wordpress.org/Post_Types
    const TYPE_POST         = 'post';
    const TYPE_PAGE         = 'page';
    const TYPE_ATTACHMENT   = 'attachment';
    const TYPE_REVISION     = 'revision';
    const TYPE_NAVIGATION   = 'nav_menu_item';

    /**
     * @param Post $post
     *
     * @return bool
     */
    public function isCommentingOpened(Post $post)
    {
        return static::COMMENT_STATUS_OPEN == $post->getCommentStatus();
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
}