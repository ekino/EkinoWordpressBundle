<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Tests\Event;

use Ekino\WordpressBundle\Event\WordpressEvent;

/**
 * Class WordpressEventTest.
 *
 * This is the test class for the Wordpress event class
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class WordpressEventTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests WordpressEvent constructor parameters.
     *
     * Should return correct parameters
     */
    public function testConstructorParameters()
    {
        $event = new WordpressEvent(['my-key' => 'my-value']);

        $this->assertTrue($event->hasParameter('my-key'), 'Should return true because my-key value exists');
        $this->assertFalse($event->hasParameter(1), 'Should return false because only my-key value was added');
        $this->assertEquals('my-value', $event->getParameter('my-key'));
    }

    /**
     * Tests WordpressEvent addParameter() method.
     *
     * Should return correct parameters
     */
    public function testAddParameter()
    {
        $event = new WordpressEvent();
        $event->addParameter('my-other-value');

        $this->assertTrue($event->hasParameter(0), 'Should return true because one parameter was added');
        $this->assertFalse($event->hasParameter(1), 'Should return false because only one parameter was added');
        $this->assertEquals('my-other-value', $event->getParameter(0));
    }
}
