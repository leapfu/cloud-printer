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

use Psr\Http\Message\ResponseInterface;

/**
 * 请求异常类
 *
 * 继承自 CloudPrinterException 类，添加了response信息。
 */
class NetworkException extends CloudPrinterException
{
    protected ?ResponseInterface $response;

    public function __construct(
        string $message,
        ?ResponseInterface $response = null,
        int $code = 0,
        \Throwable $previous = null
    ) {
        $this->response = $response;
        parent::__construct($message, $code, $previous);
    }

    /**
     * 获取原始响应对象
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
