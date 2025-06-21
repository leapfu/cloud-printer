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

use think\Service;
use Leapfu\CloudPrinter\CloudPrinter;

class CloudPrinterProvider extends Service
{
    public function register()
    {
        $this->app->bind(CloudPrinter::class, function () {
            $config = $this->app->config->get('cloudprint', []);
            $instance = new CloudPrinter($config);
            
            // 设置日志和缓存（如果可用）
            if ($this->app->has('log')) {
                $instance->setLogger($this->app->log);
            }
            if ($this->app->has('cache')) {
                $instance->setCache($this->app->cache);
            }
            
            return $instance;
        });

        // 注册助手函数
        $this->app->bind('cloud_printer', CloudPrinter::class);
    }

    public function boot()
    {
        // 启动时的初始化逻辑（如果需要）
    }
}
