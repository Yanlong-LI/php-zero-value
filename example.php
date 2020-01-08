<?php
/**
 *   Author: Yanlongli <jobs@yanlongli.com>
 *   Date:   2020/1/8
 *   IDE:    PhpStorm
 *   Desc:
 */

use ZeroValue\ZeroValue;

include "vendor/autoload.php";

class ExampleA
{
    public $a;
}

class ExampleB
{
    public int $a;
}

class ExampleC
{
    public int $a = 0;
}

class ExampleD extends ZeroValue
{
    public int $a;
}

// No prompt exception PHP < 7.4  Writing style
$ea = new ExampleA();
var_dump($ea->a);
// 7.4 Writing style   Fatal error: Uncaught Error: Typed property ExampleB::$a must not be accessed before initialization in example.php on line 35
$eb = new ExampleB();
var_dump($eb->a);
// No prompt exception  7.4  Initialization Writing style
$ec = new ExampleC();
var_dump($ec->a);
// Automatic zero reflection injection
$ed = new ExampleD();
var_dump($ed->a);