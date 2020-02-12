<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Tests\Manager;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Ekino\WordpressBundle\Manager\PostManager;
use Ekino\WordpressBundle\Manager\PostMetaManager;
use Ekino\WordpressBundle\Repository\PostRepository;

/**
 * Class PostManagerTest
 *
 * @author Guillaume Leclercq <g.leclercq12@gmail.com>
 */
class PostManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var PostRepository
     */
    protected $repository;

    /**
     * @var PostManager
     */
    protected $manager;

    /**
     * @var PostMetaManager
     */
    protected $postMetaManager;

    /**
     * Sets up a PostManager instance
     */
    protected function setUp()
    {
        $this->entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->repository = $this->getMockBuilder('Ekino\WordpressBundle\Repository\PostRepository')->disableOriginalConstructor()->getMock();
        $this->entityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));

        $this->postMetaManager = new PostMetaManager($this->entityManager, 'Ekino\WordpressBundle\Entity\PostMeta');
        $this->manager = new PostManager($this->entityManager, 'Ekino\WordpressBundle\Entity\Post', $this->postMetaManager);
    }

    public function testFindByCategory()
    {
        $this->repository->expects($this->once())
            ->method('findByCategory')
            ->will($this->returnValue('test'));

        $this->manager->findByCategory('test');
    }
}
