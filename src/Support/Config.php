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

namespace Leapfu\CloudPrinter\Support;

/**
 * 配置加载与合并工具类
 *
 * 支持从数组、文件路径加载配置，并与默认配置递归合并。
 */
class Config
{
    /**
     * 加载配置
     *
     * @param string|array|null $config 配置数组、文件路径或 null
     * @return array 最终合并后的配置
     */
    public static function load(string|array|null $config = null): array
    {
        // 加载默认配置
        $defaultConfig = include dirname(__DIR__, 2) . '/config/config.php';

        if (is_array($config)) {
            return self::mergeConfig($defaultConfig, $config);
        }

        if (is_string($config) && file_exists($config)) {
            $userConfig = include $config;
            return self::mergeConfig($defaultConfig, $userConfig);
        }

        // 未传入参数时，返回默认配置
        return $defaultConfig;
    }

    /**
     * 递归合并配置
     *
     * @param array $default 默认配置
     * @param array $user 用户配置
     * @return array 合并后的配置
     */
    private static function mergeConfig(array $default, array $user): array
    {
        foreach ($user as $key => $value) {
            if (is_array($value) && isset($default[$key]) && is_array($default[$key])) {
                $default[$key] = self::mergeConfig($default[$key], $value);
            } else {
                $default[$key] = $value;
            }
        }
        return $default;
    }
}
