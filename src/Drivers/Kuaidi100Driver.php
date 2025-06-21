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

namespace Leapfu\CloudPrinter\Drivers;

/**
 * 快递100云打印机驱动
 *
 * 快递100云打印接口文档：https://api.kuaidi100.com/help/doc/
 */
class Kuaidi100Driver extends BaseDriver
{
    /**
     * @var string API基础URL
     */
    protected string $baseUrl = 'https://api.kuaidi100.com/label/order/';

    /**
     * @var array 配置数组
     */
    protected array $config = [
        'key'    => '',    // 快递100应用key
        'secret' => '', // 快递100应用密钥
    ];

    /**
     * 获取打印机名称
     * @return string
     */
    public function getDriverName(): string
    {
        return 'kuaidi100';
    }

    /**
     * 电子面单下单接口
     * @param array $params 所需参数
     * @return array
     */
    public function order(array $params): array
    {
        return $this->request('order', $params);
    }

    /**
     * 电子面单取消接口
     * @param array $params 所需参数
     * @return array
     */
    public function query(array $params): array
    {
        return $this->request('cancel', $params);
    }

    /**
     * 打印文本
     * @param array $params 所需参数
     * @return array
     */
    public function print(array $params): array
    {
        return $this->request('custom', $params);
    }

    /**
     * 发送请求
     * @param string $action 接口名称
     * @param array $params 参数
     * @param string $method 请求方式
     * @return array
     */
    public function request(string $action, array $params, string $method = 'POST'): array
    {
        // 获取当前时间戳
        $timestamp = time();
        // 生成请求数据
        $data = [
            'method' => $action,
            'key'    => $this->config['key'],
            't'      => $timestamp,
            'sign'   => $this->generateSign($params, $timestamp),
            'param'  => json_encode($params),
        ];
        // 发送请求
        $result = $this->handleRequest($this->baseUrl, $data, $method);
        // 验证返回结果
        if ($result && isset($result['success']) && $result['success']) {
            return $this->formatResult(true, 'Success', $result['data'] ?? []);
        }
        // 如果响应失败，返回错误信息
        return $this->formatResult(false, $result['message'] ?? 'Unknown error', $result['data'] ?? []);
    }

    /**
     * 生成签名
     * @param array $params 参数
     * @param int $timestamp 时间戳
     * @return string
     */
    protected function generateSign(array $params, int $timestamp): string
    {
        return strtoupper(md5(json_encode($params) . $timestamp . $this->config['key'] . $this->config['secret']));
    }
}
