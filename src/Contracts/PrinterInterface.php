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

namespace Leapfu\CloudPrinter\Contracts;

/**
 * 打印机接口
 *
 * 定义所有打印机驱动必须实现的方法
 */
interface PrinterInterface
{
    /**
     * 获取打印机名称
     * @return string
     */
    public function getDriverName(): string;

    /**
     * 打印文本
     * @param array $params 所需参数
     * @return array
     */
    public function print(array $params): array;

    /**
     * 通用请求接口
     * @param string $action 接口名称
     * @param array $params 参数
     * @param string $method 请求方式
     * @return array
     */
    public function request(string $action, array $params, string $method = 'POST'): array;
}
