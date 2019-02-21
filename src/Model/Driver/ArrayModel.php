<?php
/**
 * Created by PhpStorm.
 * User: Xavier Yang
 * Date: 2018/12/29
 * Time: 16:28
 * Email:499873958@qq.com
 */

namespace Xavier\Model\Driver;

class ArrayModel implements \ArrayAccess
{

    public function offsetExists($offset)
    {
        return (property_exists($this, $offset) || method_exists($this, $offset));
    }

    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

}