<?php
/**
 *   Author: Yanlongli <jobs@yanlongli.com>
 *   Date:   2020/1/21
 *   IDE:    PhpStorm
 *   Desc:  辅助扫描工具，扫描定义的属性是否安全初始化
 */
declare(strict_types=1);

namespace ZeroValue;

use ReflectionClass;
use ReflectionException;

class ScanZeroValue extends ZeroValue
{
    /**
     * @var string 扫描目录
     */
    protected string $path;

    protected array $map = [];

    /**
     * @var bool 是否包含未定义
     */
    protected bool $includeUndefined = false;

    /**
     * ScanZeroValue constructor.
     * @param string $path
     * @throws ReflectionException
     */
    public function __construct(string $path)
    {
        parent::__construct();
        $this->path = $path;
    }

    /**
     * 开始扫描
     * @throws ReflectionException
     */
    public function run()
    {
        $this->scanFiles($this->path);
        return $this->map;
    }

    /**
     * 内部递归器，递归扫描所有的子目录
     * @param string $path
     * @throws ReflectionException
     */
    protected function scanFiles(string $path)
    {
        if (is_dir($path)) {
            // 扫描目录并递归解析

            $_dirArr = scandir($path);

            foreach ($_dirArr as $item) {
                // 跳过当前目录和上级目录
                if (in_array($item, ['.', '..'])) {
                    continue;
                }
                //排除隐藏文件
                if ("." === $item[0]) {
                    continue;
                }
                if (is_dir($path . '/' . $item)) {
                    $this->scanFiles($path . '/' . $item);
                }

                //排除非php文件
                if (".php" !== substr($item, -4)) {
                    continue;
                }

                $this->ParsingFile($path . '/' . $item);

            }

        } else {
            $this->ParsingFile($path);
        }
    }

    /**
     * @param string $filePath
     * @throws ReflectionException
     */
    protected function parsingFile(string $filePath)
    {
        $content = file_get_contents($filePath);

        //匹配类名臣规则
        $classReg = '/class\s*(\w*)\s*{/';

        preg_match($classReg, $content, $_className);
        // 非类文件，不再
        if (count($_className) === 0) {
            return;
        }
        $map = $this->parsingAttributes($filePath);
        $namespaceReg = '/namespace\s*([\\\\?\w*]+)/';
        preg_match($namespaceReg, $content, $_namespace);
        $_namespacePath = '';
        if (count($_namespace) !== 0) {
            $_namespacePath = $_namespace[1] . '\\';
        }

        $this->filterInitializationAttr($_namespacePath . $_className[1], $map);
        if (!empty($map)) {
            $this->map[$filePath] = $map;
        }
    }

    protected function parsingAttributes($filePath)
    {
        $map = [];

        $file = fopen($filePath, "r");
        $line = 0;
        while (!feof($file)) {
            $line++;
            $lineContent = fgets($file);
            $attributeReg = '/(public|private|protected)\s+(static\s+)?(\\\\?\w+\s+)?(\$\w+)\s*;/';
            preg_match_all($attributeReg, $lineContent, $_attributes);
            if (count($_attributes[0]) > 0) {
                $count = count($_attributes[0]);
                for ($i = 0; $i < $count; $i++) {
                    $map[$line][] = [
                        'source' => $_attributes[0][$i],
                        'acl' => $_attributes[1][$i],
                        'static' => $_attributes[2][$i],
                        'type' => $_attributes[3][$i],
                        'attr' => $_attributes[4][$i],
                    ];
                }

            }
        }

        fclose($file);
        return $map;
    }

    /**\
     * @param $className
     * @param $mapAttr
     * @throws ReflectionException
     */
    protected function filterInitializationAttr($className, &$mapAttr): void
    {
        //实例化对象
        $classObject = $this->reflectionInstantiateClass($className);
        //获取类反射
        $refClass = new ReflectionClass($className);

        // 获取类的属性列表
        $refClassProperties = $refClass->getProperties();
        foreach ($refClassProperties as $refClassProperty) {
            // 设置安全访问
            $refClassProperty->setAccessible(true);
            // 判断是否初始化
            if ($refClassProperty->isInitialized($classObject)) {
                // 从map中移除指定的
                foreach ($mapAttr as $lineKey => &$line) {
                    foreach ($line as $itemKey => $item) {
                        if ($item['attr'] === '$' . $refClassProperty->getName()) {
                            unset($line[$itemKey]);
                            if (count($mapAttr[$lineKey]) === 0) {
                                unset($mapAttr[$lineKey]);
                            }
                            break 2;
                        }
                    }
                }

            }
        }
    }


    /**
     * @param bool $includeUndefined
     * @deprecated
     */
    public function setIncludeUndefined(bool $includeUndefined): void
    {
        $this->includeUndefined = $includeUndefined;
    }
}
