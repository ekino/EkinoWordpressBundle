<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;

/**
 * Class Post
 *
 * This is the Post entity
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
abstract class Post implements WordpressEntityInterface, WordpressContentInterface
{
    const COMMENT_STATUS_OPEN = 'open';
    const COMMENT_STATUS_CLOSED = 'closed';

    // @see http://codex.wordpress.org/Post_Status
    const STATUS_PUBLISHED = 'publish';
    const STATUS_FUTURE = 'future';
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_PRIVATE = 'private';
    const STATUS_TRASH = 'trash';
    const STATUS_AUTODRAFT = 'auto-draft';
    const STATUS_INHERIT = 'inherit';

    // @see http://codex.wordpress.org/Post_Types
    const TYPE_POST = 'post';
    const TYPE_PAGE = 'page';
    const TYPE_ATTACHMENT = 'attachment';
    const TYPE_REVISION = 'revision';
    const TYPE_NAVIGATION = 'nav_menu_item';

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var User
     */
    protected $author;

    /**
     * @var \DateTime
     */
    protected $date;

    /**
     * @var \DateTime
     */
    protected $dateGmt;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $excerpt;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $commentStatus;

    /**
     * @var string
     */
    protected $pingStatus;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $toPing;

    /**
     * @var string
     */
    protected $pinged;

    /**
     * @var \DateTime
     */
    protected $modified;

    /**
     * @var \DateTime
     */
    protected $modifiedGmt;

    /**
     * @var string
     */
    protected $contentFiltered;

    /**
     * @var integer
     */
    protected $parent = 0;

    /**
     * @var string
     */
    protected $guid;

    /**
     * @var integer
     */
    protected $menuOrder;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $mimeType;

    /**
     * @var integer
     */
    protected $commentCount;

    /**
     * @var ArrayCollection
     */
    protected $metas;

    /**
     * @var ArrayCollection
     */
    protected $comments;

    /**
     * @var ArrayCollection
     */
    protected $termRelationships;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->metas = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->termRelationships = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param User $author
     *
     * @return Post
     */
    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param int $commentCount
     *
     * @return Post
     */
    public function setCommentCount($commentCount)
    {
        $this->commentCount = $commentCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getCommentCount()
    {
        return $this->commentCount;
    }

    /**
     * @param string $content
     *
     * @return Post
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $contentFiltered
     *
     * @return Post
     */
    public function setContentFiltered($contentFiltered)
    {
        $this->contentFiltered = $contentFiltered;

        return $this;
    }

    /**
     * @return string
     */
    public function getContentFiltered()
    {
        return $this->contentFiltered;
    }

    /**
     * @param \DateTime $date
     *
     * @return Post
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $dateGmt
     *
     * @return Post
     */
    public function setDateGmt($dateGmt)
    {
        $this->dateGmt = $dateGmt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateGmt()
    {
        return $this->dateGmt;
    }

    /**
     * @param string $excerpt
     *
     * @return Post
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * @param string $guid
     *
     * @return Post
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * @param int $menuOrder
     *
     * @return Post
     */
    public function setMenuOrder($menuOrder)
    {
        $this->menuOrder = $menuOrder;

        return $this;
    }

    /**
     * @return int
     */
    public function getMenuOrder()
    {
        return $this->menuOrder;
    }

    /**
     * @param string $mimeType
     *
     * @return Post
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param \DateTime $modified
     *
     * @return Post
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * @param \DateTime $modifiedGmt
     *
     * @return Post
     */
    public function setModifiedGmt($modifiedGmt)
    {
        $this->modifiedGmt = $modifiedGmt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getModifiedGmt()
    {
        return $this->modifiedGmt;
    }

    /**
     * @param string $name
     *
     * @return Post
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param integer $parent
     *
     * @return Post
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return integer
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $password
     *
     * @return Post
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $status
     *
     * @return Post
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $commentStatus
     *
     * @return Post
     */
    public function setCommentStatus($commentStatus)
    {
        $this->commentStatus = $commentStatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getCommentStatus()
    {
        return $this->commentStatus;
    }

    /**
     * @param string $pingStatus
     *
     * @return Post
     */
    public function setPingStatus($pingStatus)
    {
        $this->pingStatus = $pingStatus;

        return $this;
    }

    /**
     * @return string
     */
    public function getPingStatus()
    {
        return $this->pingStatus;
    }

    /**
     * @param string $pinged
     *
     * @return Post
     */
    public function setPinged($pinged)
    {
        $this->pinged = $pinged;

        return $this;
    }

    /**
     * @return string
     */
    public function getPinged()
    {
        return $this->pinged;
    }

    /**
     * @param string $title
     *
     * @return Post
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $toPing
     *
     * @return Post
     */
    public function setToPing($toPing)
    {
        $this->toPing = $toPing;

        return $this;
    }

    /**
     * @return string
     */
    public function getToPing()
    {
        return $this->toPing;
    }

    /**
     * @param string $type
     *
     * @return Post
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param ArrayCollection $metas
     *
     * @return Post
     */
    public function setMetas(ArrayCollection $metas)
    {
        $this->metas = $metas;

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMetas()
    {
        return $this->metas;
    }

    /**
     * @param PostMeta $meta
     *
     * @return Post
     */
    public function addMeta(PostMeta $meta)
    {
        $this->metas[] = $meta;

        return $this;
    }

    /**
     * @param PostMeta $meta
     *
     * @return Post
     */
    public function removeMeta(PostMeta $meta)
    {
        if ($this->metas->contains($meta)) {
            $this->metas->remove($meta);
        }

        return $this;
    }

    /**
     * @param string $name
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMetaByKey($name)
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('key', $name));

        return $this->metas->matching($criteria);
    }

    /**
     * Returns user meta value from a meta key name
     *
     * @param string $name
     *
     * @return string|null
     */
    public function getMetaValue($name)
    {
        /** @var PostMeta $meta */
        foreach ($this->getMetas() as $meta) {
            if ($name == $meta->getKey()) {
                return $meta->getValue();
            }
        }

        return;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param Comment $comment
     *
     * @return Post
     */
    public function addComment(Comment $comment)
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
        }

        return $this;
    }

    /**
     * @param Comment $comment
     *
     * @return Post
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->remove($comment);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getTermRelationships()
    {
        return $this->termRelationships;
    }

    /**
     * @param TermRelationships $relationship
     *
     * @return Post
     */
    public function addTermRelationship(TermRelationships $relationship)
    {
        $this->termRelationships[] = $relationship;

        return $this;
    }

    /**
     * @param TermRelationships $relationship
     *
     * @return Post
     */
    public function removeTermRelationship(TermRelationships $relationship)
    {
        $this->termRelationships->remove($relationship);

        return $this;
    }

    /**
     * @param string $type
     *
     * @return ArrayCollection|null
     */
    public function getTaxonomiesByType($type)
    {
        $taxonomies = new ArrayCollection();

        /** @var TermRelationships $relationship */
        foreach ($this->getTermRelationships() as $relationship) {
            if ($type === $relationship->getTaxonomy()->getTaxonomy()) {
                $taxonomies[] = $relationship->getTaxonomy();
            }
        }

        return ($taxonomies->count() == 0) ? null : $taxonomies;
    }

    /**
     * @param $type
     *
     * @return ArrayCollection|null
     */
    public function getTermsByType($type)
    {
        $terms = new ArrayCollection();
        $taxonomies = $this->getTaxonomiesByType($type);

        /** @var TermTaxonomy $taxonomy */
        if ($taxonomies !== null) {
            foreach ($taxonomies as $taxonomy) {
                $terms[] = $taxonomy->getTerm();
            }
        }

        return ($terms->count() == 0) ? null : $terms;
    }

    /**
     * @return ArrayCollection
     */
    public function getTags()
    {
        return $this->getTermsByType('post_tag');
    }

    /**
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->getTaxonomiesByType('category');
    }

    /**
     * @return Term
     */
    public function getCategory()
    {
        $taxonomy = $this->getCategories() ? $this->getCategories()->first() : null;

        return $taxonomy ? $taxonomy->getTerm() : null;
    }

    /**
     * @return bool
     */
    public function isCommentingOpened()
    {
        return static::COMMENT_STATUS_OPEN == $this->getCommentStatus();
    }
}
