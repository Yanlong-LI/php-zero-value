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


## update log:

    2020年1月21日~22日
        修复零值初始化属性类时，构造函数需要参数问题
        增加扫描工具，扫描未定义的类
    
    January 21-22, 2020
         Fix constructor requires parameter when zero-value initialized property class
         Add scan tools to scan undefined classes
