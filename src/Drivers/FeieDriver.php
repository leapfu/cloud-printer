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
 * 飞鹅云打印机驱动
 * 飞鹅云打印接口文档：https://help.feieyun.com/home/doc/zh
 */
class FeieDriver extends BaseDriver
{
    /**
     * @var string API基础URL
     */
    protected string $baseUrl = 'https://api.feieyun.cn/Api/Open/';

    /**
     * @var array 配置数组
     */
    protected array $config = [
        'user' => '',  // 飞鹅云后台注册的账号
        'ukey' => '',  // 飞鹅云后台生成的UKEY
    ];

    /**
     * 获取打印机名称
     * @return string
     */
    public function getDriverName(): string
    {
        return 'feie';
    }

    /**
     * 批量添加设备
     * @param array $params 参数
     * @return array
     */
    public function printerAddlist(array $params): array
    {
        return $this->request('Open_printerAddlist', $params);
    }

    /**
     * 打印文本
     * @param array $params 参数
     * @return array
     */
    public function print(array $params): array
    {
        return $this->request('Open_printMsg', $params);
    }

    /**
     * 打印标签
     * @param array $params 参数
     * @return array
     */
    public function printLabel(array $params): array
    {
        return $this->request('Open_printLabelMsg', $params);
    }

    /**
     * 删除设备
     * @param array $params
     * @return array
     */
    public function deletePrinter(array $params): array
    {
        return $this->request('Open_printerDelList', $params);
    }

    /**
     * 修改设备信息
     * @param array $params
     * @return array
     */
    public function modifyPrinter(array $params): array
    {
        return $this->request('Open_printerModify', $params);
    }

    /**
     * 清空待打印队列
     * @param array $params
     * @return array
     */
    public function clearPrinterQueue(array $params): array
    {
        return $this->request('Open_delPrinterSqs', $params);
    }

    /**
     * 查询订单是否打印成功
     * @param array $params
     * @return array
     */
    public function queryOrderStatus(array $params): array
    {
        return $this->request('Open_queryOrderState', $params);
    }

    /**
     * 查询订单状态
     * @param array $params
     * @return array
     */
    public function queryPrinterStatus(array $params): array
    {
        return $this->request('Open_queryPrinterStatus', $params);
    }

    /**
     * 查询设备信息
     * @param array $params
     * @return array
     */
    public function printerInfo(array $params): array
    {
        return $this->request('Open_printerInfo', $params);
    }

    /**
     * 查询指定日期的订单信息
     * @param array $params
     * @return array
     */
    public function orderInfoByDate(array $params): array
    {
        return $this->request('Open_queryOrderInfoByDate', $params);
    }

    /**
     * 通用请求接口
     * @param string $action 接口名称
     * @param array $params 参数
     * @param string $method 请求方法
     * @return array
     */
    public function request(string $action, array $params, string $method = 'POST'): array
    {
        $timestamp = time();
        // 合并请求数据
        $data = array_merge([
            'user'    => $this->config['user'],
            'stime'   => $timestamp,
            'sig'     => $this->generateSignature($timestamp),
            'apiname' => $action,
        ], $params);
        // 发送请求
        $result = $this->handleRequest($this->baseUrl, $data, $method);
        // 解析响应
        if ($result && isset($result['ret']) && $result['ret'] == 0) {
            return $this->formatResult(true, 'Success', $result['data'] ?? []);
        }
        // 如果响应失败，返回错误信息
        return $this->formatResult(false, $result['msg'] ?? 'Unknown error', $result['data'] ?? []);
    }

    /**
     * 生成请求签名
     * @param int $timestamp
     * @return string
     */
    private function generateSignature(int $timestamp): string
    {
        return sha1($this->config['user'] . $this->config['ukey'] . $timestamp);
    }
}
