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
 * 打印机异常类
 *
 * 继承自 CloudPrinterException 类，添加了打印机类型信息。
 */
class PrinterException extends CloudPrinterException
{
    /**
     * 打印机类型
     *
     * @var string
     */
    protected string $printerType;

    /**
     * 创建打印机异常实例
     *
     * @param string $message 错误信息
     * @param string $printerType 打印机类型
     * @param int $code 错误码
     * @param \Throwable|null $previous 前一个异常
     * @return void
     */
    public function __construct(
        string $message = '',
        string $printerType = '',
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->printerType = $printerType;
    }

    /**
     * 获取打印机类型
     *
     * @return string
     */
    public function getPrinterType(): string
    {
        return $this->printerType;
    }
}
