<?php
/**
 *   Author: Yanlongli <jobs@yanlongli.com>
 *   Date:   2020/1/21
 *   IDE:    PhpStorm
 *   Desc:  这是一个未扩展零值初始化的案例，经过构造函数初始化的自动排除
 */

namespace scan_example;

class Example1
{
    public Example2 $a;
    public static int $q = 1;
    public int $e;
    public string $c = "";
    public string $d = '';

    public function __construct()
    {
        $this->e = 1;
    }
}