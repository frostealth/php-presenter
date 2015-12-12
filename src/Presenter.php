<?php

namespace frostealth\presenter;

use frostealth\presenter\exceptions\InvalidArgumentException;
use frostealth\presenter\exceptions\InvalidDecoratorException;
use frostealth\presenter\exceptions\UnknownMethodException;
use frostealth\presenter\interfaces\DecoratorInterface;
use frostealth\presenter\interfaces\PresenterInterface;

/**
 * Class Presenter
 *
 * @package frostealth\presenter
 */
abstract class Presenter implements PresenterInterface
{
    /** @var DecoratorInterface */
    protected $decorator;

    /** @var array|object */
    protected $entity;

    /**
     * @param object|array            $entity
     * @param DecoratorInterface|null $decorator
     *
     * @throws InvalidArgumentException
     * @throws InvalidDecoratorException
     */
    public function __construct($entity, DecoratorInterface $decorator = null)
    {
        if (!is_array($entity) && !is_object($entity)) {
            throw new InvalidArgumentException('Argument "$entity" must be an array or an object.');
        }

        $this->entity = $entity;
        $this->decorator = !empty($decorator) ? $decorator : new Decorator();
        $this->init();
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->{$getter}();
        }

        $value = is_array($this->entity) ? $this->entity[$name] : $this->entity->$name;

        return $this->decorator->decorate($value);
    }

    /**
     * @param string $name
     * @param mixed $args
     *
     * @return mixed
     */
    public function __call($name, $args)
    {
        if (!is_object($this->entity)) {
            throw new UnknownMethodException('Calling unknown method: ' . get_class($this) . "::$name()");
        }

        $value = call_user_func_array([$this->entity, $name], $args);

        return $this->decorator->decorate($value);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->{$getter}() !== null;
        }

        if (is_array($this->entity)) {
            return isset($this->entity[$name]);
        }

        return isset($this->entity->{$name});
    }

    /**
     * Initializes the presenter.
     * This method is invoked at the end of the constructor.
     */
    protected function init()
    {

    }
}
