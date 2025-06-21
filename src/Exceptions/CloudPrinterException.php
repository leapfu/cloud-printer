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

namespace Leapfu\CloudPrinter\Exceptions;

/**
 * 云打印SDK异常基类
 * 所有云打印SDK中的异常都应该继承此类，以提供一致的异常处理方式。
 * 此类扩展了PHP的Exception，并添加了对原始响应数据的支持。
 */
class CloudPrinterException extends \Exception
{
}
