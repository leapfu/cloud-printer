<?php
// +----------------------------------------------------------------------
// | 蓝斧LEAPFU [ 探索不止，步履不停 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2024 https://www.leapfu.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed Cloud Printer SDK is open source under the MIT license
// +----------------------------------------------------------------------
// | Author: Leapfu  <leapfu@hotmail.com>
// +----------------------------------------------------------------------

namespace Leapfu\CloudPrinter\ThinkPHP\Facade;

use think\Facade;
use Leapfu\CloudPrinter\CloudPrinter;
use Leapfu\CloudPrinter\Contracts\PrinterInterface;

/**
 * ThinkPHP云打印Facade
 *
 * @method static PrinterInterface driver(string $printerType = null) 获取打印机实例
 */
class CloudPrinter extends Facade
{
    /**
     * 获取Facade对应类名
     * @return string
     */
    protected static function getFacadeClass(): string
    {
        return CloudPrinter::class;
    }
}
