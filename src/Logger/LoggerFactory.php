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

namespace Leapfu\CloudPrinter\Logger;

use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggerFactory extends Logger implements LoggerInterface
{
    public function __construct(string $logDir)
    {
        parent::__construct('cloud_sdk');
        $logDir = $logDir ?: sys_get_temp_dir();
        $this->pushHandler(new StreamHandler(rtrim($logDir, '/') . '/cloud_sdk.log', Logger::DEBUG));
    }
}
