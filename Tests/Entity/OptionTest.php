<?php

namespace Ekino\WordpressBundle\Tests\Entity;

use Ekino\WordpressBundle\Entity\Option;

/**
 * Class OptionTest
 *
 * This is the Wordpress option entity test
 */
class OptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test entity getters & setters
     */
    public function testGettersSetters()
    {
        $entity = new Option();

        $entity->setAutoload('autoloaded');
        $entity->setName('option name');
        $entity->setValue('option value');

        $this->assertEquals('autoloaded', $entity->getAutoload());
        $this->assertEquals('option name', $entity->getName());
        $this->assertEquals('option value', $entity->getValue());
    }
}