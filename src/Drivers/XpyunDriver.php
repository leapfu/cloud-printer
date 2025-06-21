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
 * 芯烨云打印机驱动
 *
 * 芯烨云打印接口文档：https://www.xpyun.net/open/index.html
 */
class XpyunDriver extends BaseDriver
{
    /**
     * @var string API基础URL
     */
    protected string $baseUrl = 'https://open.xpyun.net/api/openapi/xprinter/';

    /**
     * @var array 配置数组
     */
    protected array $config = [
        'user'     => '',     // 芯烨云账号
        'user_key' => '', // 芯烨云用户密钥
    ];

    /**
     * 获取打印机名称
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'xpyun';
    }

    /**
     * 添加打印机到开发者账户（可批量） 【必接】.
     * @param array $params
     * @return array
     */
    public function addPrinters(array $params): array
    {
        return $this->request('addPrinters', $params);
    }

    /**
     * 删除批量打印机.
     * @param array $params
     * @return array
     */
    public function delPrinters(array $params): array
    {
        return $this->request('delPrinters', $params);
    }

    /**
     * 修改打印机信息.
     * @param array $params
     * @return array
     */
    public function updPrinter(array $params): array
    {
        return $this->request('updPrinter', $params);
    }

    /**
     * 获取打印机状态
     * @param array $params
     * @return array
     */
    public function queryPrinterStatus(array $params): array
    {
        return $this->request('queryPrinterStatus', $params);
    }

    /**
     * 打印订单
     * @param array $params
     * @return array
     */
    public function print(array $params): array
    {
        return $this->request('print', $params);
    }

    /**
     * 标签机打印订单
     * @param array $params
     * @return array
     */
    public function printLabel(array $params): array
    {
        return $this->request('printLabel', $params);
    }

    /**
     * 清空待打印队列.
     * @param array $params
     * @return array
     */
    public function delPrinterQueue(array $params): array
    {
        return $this->request('delPrinterQueue', $params);
    }

    /**
     * 查询订单是否打印成功
     * @param array $params
     * @return array
     */
    public function queryOrderState(array $params): array
    {
        return $this->request('queryOrderState', $params);
    }

    /**
     * 查询指定打印机某天的订单统计数.
     * @param array $params
     * @return array
     */
    public function queryOrderStatis(array $params): array
    {
        return $this->request('queryOrderStatis', $params);
    }

    /**
     * 获取打印机状态
     * @param array $params
     * @return array
     */
    public function queryPrintersStatus(array $params): array
    {
        return $this->request('queryPrintersStatus', $params);
    }

    /**
     * 设置打印机语音类型.
     * @param array $params
     * @return array
     */
    public function setVoiceType(array $params): array
    {
        return $this->request('setVoiceType', $params);
    }

    /**
     * 金额播报.
     * @param array $params
     * @return array
     */
    public function playVoice(array $params): array
    {
        return $this->request('playVoice', $params);
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
        $data = array_merge([
            'user'      => $this->config['user'],
            'timestamp' => $timestamp,
            'sign'      => $this->generateSign($timestamp),
        ], $params);
        // 构建请求URL
        $url = $this->baseUrl . $action;
        // 发送请求
        $result = $this->handleRequest($url, $data, $method);
        // 验证返回结果
        if ($result && isset($result['code']) && $result['code'] == 0) {
            return $this->formatResult(true, 'Success', $result['data'] ?? []);
        }
        // 如果响应失败，返回错误信息
        return $this->formatResult(false, $result['msg'] ?? 'Unknown error', $result['data'] ?? []);
    }

    /**
     * 生成签名
     * @param int $timestamp 时间戳
     * @return string
     */
    protected function generateSign(int $timestamp): string
    {
        return sha1($this->config['user'] . $this->config['user_key'] . $timestamp);
    }
}
