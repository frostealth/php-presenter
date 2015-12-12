<?php

namespace frostealth\presenter\interfaces;

/**
 * Interface DecoratorInterface
 *
 * @package frostealth\presenter\interfaces
 */
interface DecoratorInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function decorate($value);
}
