<?php
/**
 * Created by PhpStorm.
 * User: hendra
 * Date: 27/05/17
 * Time: 17:13
 */

namespace Tokosync\Resources;


use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;

class SyncItem implements ArrayAccess, IteratorAggregate
{
    protected $attr = [];

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->offsetGet('id');
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->offsetSet('id', $id);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->offsetGet('name');
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->offsetSet('name', $name);
        return $this;
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->attr);
    }

    public function offsetGet($offset)
    {
        return $this->attr[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->attr[] = $value;
        } else {
            $this->attr[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->attr[$offset]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->attr);
    }


    /**
     * @param $source_item
     * @param $other_item
     * @return bool
     */
    public function isSimilar($other_item)
    {
        $source_name = str_replace(' ','',strtolower($this['name']));
        $dest_name = str_replace(' ','',strtolower($other_item['name']));

        return $source_name == $dest_name;
    }
}