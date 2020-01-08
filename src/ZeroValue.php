<?php
/**
 *   Author: Yanlongli <jobs@yanlongli.com>
 *   Date:   2020/1/8
 *   IDE:    PhpStorm
 *   Desc:  Initialize zero value
 */
declare(strict_types=1);

namespace ZeroValue;

use ReflectionClass;
use ReflectionException;

/**
 * Class ZeroValue
 * @package ZeroValue
 */
abstract class ZeroValue
{
    /**
     * ZeroValue constructor.
     * @throws ReflectionException
     */
    public function __construct()
    {
        $self = new ReflectionClass($this);
        $arr = $self->getProperties();
        foreach ($arr as $item) {
            if ($item->isInitialized($this)) {
                continue;
            }

            $type = $item->getType()->getName();
            switch ($type) {
                case ValueType::Int:
                case ValueType::Float:
                    $item->setValue($this, 0);
                    break;
                case ValueType::Bool:
                    $item->setValue($this, false);
                    break;
                case ValueType::Array:
                    $item->setValue($this, []);
                    break;
                case ValueType::String:
                    $item->setValue($this, "");
                    break;
                default:
                    // other Object
                    if ($type === get_class($this)) {
                        throw new ReflectionException("Cannot reference itself");
                    }
                    $item->setValue($this, new $type());
                    break;
            }
        }
    }
}


