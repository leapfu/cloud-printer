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

namespace Leapfu\CloudPrinter\Laravel;

use Illuminate\Support\ServiceProvider;
use Leapfu\CloudPrinter\CloudPrinter;

class CloudPrinterServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CloudPrinter::class, function ($app) {
            $config = config('cloudprint');
            $printer = new CloudPrinter($config);
            $printer->setLogger($app['log']);
            $printer->setCache($app['cache.store']);
            return $printer;
        });
    }

    public function boot()
    {
        // 发布配置文件
        $this->publishes([
            __DIR__ . '/../../../config/config.php' => config_path('cloudprint.php'),
        ], 'config');
    }
}
