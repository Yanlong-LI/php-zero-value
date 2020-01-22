<?php
/**
 *   Author: Yanlongli <jobs@yanlongli.com>
 *   Date:   2020/1/21
 *   IDE:    PhpStorm
 *   Desc:  测试用例
 */
declare(strict_types=1);

use ZeroValue\ScanZeroValue;

include __DIR__ . '/../vendor/autoload.php';

$tools = new ScanZeroValue(__DIR__ . '/scan_example');
$result = $tools->run();

foreach ($result as $key => $item) {
    echo $key . PHP_EOL;
    foreach ($item as $line => $lineAttr) {
        echo "      行:" . $line . PHP_EOL;
        foreach ($lineAttr as $attr) {
            echo "            源:" . $attr['source'] . PHP_EOL;
        }
    }
}