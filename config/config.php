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

return [
    // 默认打印机类型
    'default'    => 'feie',
    // 缓存配置
    'cache_path' => __DIR__ . '/../cache',
    // 日志目录
    'log_path'   => __DIR__ . '/../logs',
    // HTTP客户端配置
    'http'       => [
        'timeout'         => 30,      // 请求超时时间(秒)
        'connect_timeout' => 10, // 连接超时时间(秒)
        'verify'          => true,      // 是否验证SSL证书
    ],

    // 打印机配置
    'drivers'    => [
        // 飞鹅云打印
        'feie'      => [
            'user' => '',  // 飞鹅云后台注册的账号
            'ukey' => '',  // 飞鹅云后台生成的UKEY
        ],

        // 易联云打印
        'yilian'    => [
            'client_id'     => '',  // 易联云应用ID
            'client_secret' => '', // 易联云应用密钥
        ],

        // 芯烨云打印
        'xpyun'     => [
            'user'     => '',     // 芯烨云账号
            'user_key' => '', // 芯烨云用户密钥
        ],

        // 快递100云打印
        'kuaidi100' => [
            'key'    => '',    // 快递100应用key
            'secret' => '', // 快递100应用密钥
        ],

        // 优声云打印
        'usheng'    => [
            'app_id'     => '',    // 优声云应用key
            'app_secret' => '', // 优声云应用密钥
        ],

        // 中午云打印
        'zhongwu'   => [
            'app_id'     => '',    // 中午云应用key
            'app_secret' => '', // 中午云应用密钥
        ],


        // 佳博云打印
        'poscom'    => [
            'api_key'     => '',         // 接口密钥
            'member_code' => '',      // 商户编码
        ],

        // 映美云打印
        'jolimark'  => [
            'app_id'     => '',     // 映美云应用ID
            'app_secret' => '', // 映美云应用密钥
        ],
    ],
];
