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
            $item->setAccessible(true);
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
                    $item->setValue($this, $this->reflectionInstantiateClass($type));
                    break;
            }
        }
    }

    /**
     * 反射实例化类
     * @param $className
     * @return object 类的实例化
     * @throws ReflectionException
     */
    protected function reflectionInstantiateClass($className): object
    {
        $args = [];
        //反射获取类
        $refClass = new ReflectionClass($className);
        //获取构造函数
        $refController = $refClass->getConstructor();
        if (null !== $refController) {
            //如果构造函数需要传值，则构造零值
            $refControllerParams = $refController->getParameters();

            foreach ($refControllerParams as $param) {
                $type = $param->getType();
                if (null === $type) {
                    $args[] = null;
                    continue;
                }
                switch ($type->getName()) {
                    case ValueType::Float:
                    case ValueType::Int:
                        $args[] = 0;
                        break;
                    case ValueType::String:
                        $args[] = '';
                        break;
                    case ValueType::Array:
                        $args[] = [];
                        break;
                    case ValueType::Bool:
                        $args[] = false;
                        break;
                    default:
                        // other Object
                        if ($type === get_class($this)) {
                            throw new ReflectionException("Cannot reference itself");
                        }
                        $args[] = $this->reflectionInstantiateClass($type);
                        break;
                }
            }
        }
        //如果构造函数无需传值，则直接new
        return $refClass->newInstance(...$args);
    }
}


