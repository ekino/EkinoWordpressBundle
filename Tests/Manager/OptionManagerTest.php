<?php

namespace Ekino\WordpressBundle\Tests\Manager;

use Ekino\WordpressBundle\Manager\OptionManager;

class OptionManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $entityManager;
    protected $repository;

    /**
     * @var OptionManager
     */
    protected $manager;

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
     * Test that the repository is called with the correct argument
     */
    public function testFindOneByName()
    {
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(array('name' => 'test')));

        $this->manager->findOneByOptionName('test');
    }
}