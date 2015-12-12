<?php

namespace frostealth\presenter;

use frostealth\presenter\interfaces\DecoratorInterface;
use frostealth\presenter\interfaces\PresentableInterface;
use frostealth\presenter\interfaces\PresenterInterface;

/**
 * Class Decorator
 *
 * @package frostealth\presenter
 */
class Decorator implements DecoratorInterface
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function decorate($value)
    {
        if ($value instanceof PresenterInterface) {
            return $value;
        }

        if ($value instanceof PresentableInterface) {
            return $value->presenter();
        }

        $result = $value;
        if (is_array($value) || ($value instanceof \Traversable && $value instanceof \ArrayAccess)) {
            $result = [];
            foreach ($value as $k => $v) {
                $result[$k] = $this->decorate($v);
            }
        }

        return $result;
    }
}
