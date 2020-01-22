<?php
/**
 *   Author: Yanlongli <jobs@yanlongli.com>
 *   Date:   2020/1/21
 *   IDE:    PhpStorm
 *   Desc:  这是一个扩展零值初始化的案例，未初始化的属性自动初始化
 */

namespace scan_example\sub;


use ZeroValue\ZeroValue;

class Sub extends ZeroValue
{
    public $a = 0;
    public int $b;
    public string $c = "";
    public string $d = '';
}