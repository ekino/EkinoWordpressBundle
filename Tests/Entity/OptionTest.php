<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Tests\Entity;

use Ekino\WordpressBundle\Entity\Option;

/**
 * Class OptionTest.
 *
 * This is the Wordpress option entity test
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class OptionTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Test entity getters & setters.
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
