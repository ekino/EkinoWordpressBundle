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

use Ekino\WordpressBundle\Entity\Post;
use Ekino\WordpressBundle\Entity\PostMeta;
use Ekino\WordpressBundle\Repository\PostMetaRepository;

/**
 * Class PostMetaManager
 *
 * This is the PostMeta entity manager
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class PostMetaManager extends BaseManager
{
    /**
     * @var PostMetaRepository
     */
    protected $repository;

    /**
     * @param int    $postId
     * @param string $metaName
     * @param bool   $fetchOneResult
     *
     * @return array|\Ekino\WordpressBundle\Entity\PostMeta
     */
    public function getPostMeta($postId, $metaName, $fetchOneResult = false)
    {
        $query = $this->repository->getPostMetaQuery($postId, $metaName);

        return $fetchOneResult ? $query->getOneOrNullResult() : $query->getResult();
    }

    /**
     * @param Post $post
     *
     * @return PostMeta|null
     */
    public function getThumbnailPostId(Post $post)
    {
        return $this->getPostMeta($post->getId(), '_thumbnail_id', true);
    }
}
