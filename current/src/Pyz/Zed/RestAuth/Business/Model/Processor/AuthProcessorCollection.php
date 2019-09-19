<?php

namespace Pyz\Zed\RestAuth\Business\Model\Processor;

use Countable;
use Iterator;

class AuthProcessorCollection implements Iterator, Countable
{
    /**
     * @var array
     */
    private $processors = [];

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @param \Shared\Zed\RestAuth\Business\Model\Processor\AuthProcessorInterface $authProcessor $authProcessor
     *
     * @return $this
     */
    public function add(AuthProcessorInterface $authProcessor): self
    {
        $this->processors[] = $authProcessor;

        return $this;
    }

    /**
     * @return \Shared\Zed\RestAuth\Business\Model\Processor\AuthProcessorInterface
     */
    public function current(): AuthProcessorInterface
    {
        return $this->processors[$this->position];
    }

    /**
     * @return void
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->processors[$this->position]);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->processors);
    }
}
