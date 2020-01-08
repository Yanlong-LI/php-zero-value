Initialize Zero Value 自动初始化零值
===
>Initialize zero value. For variable type modification added in php7.4 and above, uninitialized variable reading will throw uninitialized error. Use the reflection mechanism to set zero values for the variables of the class.

>初始化零值,针对PHP7.4 及以上版本新增的变量类型修饰，未初始化的变量读取将会抛出未初始化错误。使用反射机制，为类的变量设定零值。

只能针对类的属性进行初始化，静态属性推荐手动做初始化零值

string zero value empty string

int or float zero value `0`

array zero value `array()`

bool zero value `false`

other zero value  `new className()`

## Usage mode 使用方式

composer require yanlongli/zero-value

```php
use ZeroValue\ZeroValue;

class ExampleD extends ZeroValue
{
    //todo 
}
```

## Example Demo 演示示例
```php

use ZeroValue\ZeroValue;include "src/ZeroValue.php";
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

```

## Matters needing attention  注意事项
PHP is an interpreted language. There is no mandatory rule that classes cannot refer to each other, but you should avoid this problem. For example, class A contains class B, and class B contains class A

PHP 是解释语言，没有强制规定类不能相互引用，但你应该避免这个问题。如：class A 含有 class B ，class B 含有 class A.
```php
// 这是应该避免 This should be avoided
class A {
    public B $b;
}
class B {
    public A $a;
}

// 这是应该避免 This should be avoided
class C {
    public D $d;
}
class D {
    public E $e;
}
class E {
    public C $c;
}
```