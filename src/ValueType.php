<?php
/**
 *   Author: Yanlongli <jobs@yanlongli.com>
 *   Date:   2020/1/8
 *   IDE:    PhpStorm
 *   Desc:  Value Type
 */
declare(strict_types=1);

namespace ZeroValue;

/**
 * Class ValueType
 * @package ZeroValue
 */
abstract class ValueType
{
    const String = 'string';
    const Float = 'float';
    const Int = 'int';
    const Array = 'array';
    const Bool = 'bool';
}