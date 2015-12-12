<?php
/*
 * This file is part of the Ekino Wordpress package.
 *
 * (c) 2013 Ekino
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Ekino\WordpressBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class WordpressEvent.
 *
 * This is the Wordpress event class
 *
 * @author Vincent Composieux <composieux@ekino.com>
 */
class WordpressEvent extends Event
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * Constructor.
     *
     * @param array $parameters
     */
    public function __construct(array $parameters = [])
    {
        $this->parameters = $parameters;
    }

    /**
     * Returns if parameter gor given index position exists.
     *
     * @param mixed $index
     *
     * @return bool
     */
    public function hasParameter($index)
    {
        return isset($this->parameters[$index]);
    }

    /**
     * Returns a parameter of given index position.
     *
     * @param mixed $index
     *
     * @throws \InvalidArgumentException
     *
     * @return mixed
     */
    public function getParameter($index)
    {
        if (!$this->hasParameter($index)) {
            throw new \InvalidArgumentException(sprintf('Cannot retrieve parameter "%s"', $index));
        }

        return $this->parameters[$index];
    }

    /**
     * Adds a parameter.
     *
     * @param mixed $value
     *
     * @return $this
     */
    public function addParameter($value)
    {
        $this->parameters[] = $value;

        return $this;
    }
}
