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

use Doctrine\ORM\EntityManager;
use Ekino\WordpressBundle\Manager\OptionManager;
use Ekino\WordpressBundle\Repository\OptionRepository;

/**
 * Class OptionManagerTest.
 *
 * @author Xavier Coureau <xav.is@2cool4school.fr>
 */
class OptionManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var OptionRepository
     */
    protected $repository;

    /**
     * @var OptionManager
     */
    protected $manager;

    /**
     * Sets up a OptionManager instance.
     */
    protected function setUp()
    {
        $this->entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')->disableOriginalConstructor()->getMock();
        $this->repository = $this->getMockBuilder('Ekino\WordpressBundle\Repository\OptionRepository')->disableOriginalConstructor()->getMock();
        $this->entityManager->expects($this->any())
            ->method('getRepository')
            ->will($this->returnValue($this->repository));

        $this->manager = new OptionManager($this->entityManager, 'Ekino\WordpressBundle\Entity\Option');
    }

    /**
     * Test that the repository is called with the correct argument.
     */
    public function testFindOneByName()
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['name' => 'test']));

        $this->manager->findOneByOptionName('test');
    }
}
