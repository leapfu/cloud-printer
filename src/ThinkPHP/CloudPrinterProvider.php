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

namespace Leapfu\CloudPrinter\ThinkPHP;

use think\App;
use Leapfu\CloudPrinter\CloudPrinter;
use think\Facade;

class CloudPrinterProvider
{
    public function register(App $app)
    {
        $this->app->bind(CloudPrinter::class, function () {
            $config = $this->app->config->get('cloudprint');
            $instance = new CloudPrinter($config);
            $instance->setLogger($this->app->log);
            $instance->setCache($this->app->cache);
            return $instance;
        });

        // 注册门面
        Facade::bind('CloudPrinter', CloudPrinter::class);
    }
}
