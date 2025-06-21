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

use Leapfu\CloudPrinter\Exceptions\PrinterException;

/**
 * 易联云打印机驱动
 *
 * 易联云打印接口文档：http://doc2.10ss.net/332006
 */
class YilianDriver extends BaseDriver
{
    /**
     * @var string API基础URL
     */
    protected string $baseUrl = 'https://open-api.10ss.net/';

    /**
     * @var array 配置数组
     */
    protected array $config = [
        'client_id'     => '',  // 易联云应用ID
        'client_secret' => '', // 易联云应用密钥
    ];

    /**
     * 获取打印机名称
     * @return string
     */
    public function getDriverName(): string
    {
        return 'yilian';
    }

    /**
     * 添加打印机.
     * @param array $params
     * @return array
     */
    public function addPrinter(array $params): array
    {
        return $this->request('printer/addprinter', $params);
    }

    /**
     * 删除打印机.
     * @param array $params
     * @return array
     */
    public function deletePrinter(array $params): array
    {
        return $this->request('printer/deleteprinter', $params);
    }

    /**
     * 获取某台打印机状态
     * @param array $params
     * @return array
     */
    public function getPrintStatus(array $params): array
    {
        return $this->request('printer/getprintstatus', $params);
    }

    /**
     * 关机重启接口.
     * @param array $params
     * @return array
     */
    public function shutdownreStart(array $params): array
    {
        return $this->request('printer/shutdownrestart', $params);
    }

    /**
     * 声音调节接口.
     * @param array $params
     * @return array
     */
    public function setSound(array $params): array
    {
        return $this->request('printer/setsound', $params);
    }

    /**
     * 设置内置语音接口.
     * @param array $params
     * @return array
     */
    public function setVoice(array $params): array
    {
        return $this->request('printer/setvoice', $params);
    }

    /**
     * 删除内置语音接口.
     * @param array $params
     * @return array
     */
    public function deleteVoice(array $params): array
    {
        return $this->request('printer/deletevoice', $params);
    }

    /**
     * 文本打印.
     * @param array $params
     * @return array
     */
    public function print(array $params): array
    {
        return $this->request('print/index', $params);
    }

    /**
     * 图形打印.
     * @param array $params
     * @return array
     */
    public function picturePrint(array $params): array
    {
        return $this->request('pictureprint/index', $params);
    }

    /**
     * 面单打印.
     * @param array $params
     * @return array
     */
    public function expressPrint(array $params): array
    {
        return $this->request('expressprint/index', $params);
    }

    /**
     * 清空待打印队列.
     * @param array $params
     * @return array
     */
    public function cancelOne(array $params): array
    {
        return $this->request('printer/cancelone', $params);
    }

    /**
     * 取消所有未打印订单.
     * @param array $params
     * @return array
     */
    public function cancelAll(array $params): array
    {
        return $this->request('printer/cancelall', $params);
    }

    /**
     * 查询订单是否打印成功
     * @param array $params
     * @return array
     */
    public function getOrderStatus(array $params): array
    {
        return $this->request('printer/getorderstatus', $params);
    }

    /**
     * 获取订单列表
     * @param array $params
     * @return array
     */
    public function getOrderpagingList(array $params): array
    {
        return $this->request('printer/getorderpaginglist', $params);
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
            'client_id' => $this->config['client_id'],
            'sign'      => $this->generateSign($timestamp),
            'timestamp' => $timestamp,
            'id'        => $this->uuid(),
        ];
        // 如果不是OAuth认证请求，则添加AccessToken
        if ($action !== 'oauth/oauth') {
            $data['access_token'] = $this->getAccessToken();
        }
        // 合并请求参数
        $data = array_merge($data, $params);
        // 构建请求URL
        $url = $this->baseUrl . $action;
        // 发送请求
        $result = $this->handleRequest($url, $data, $method);
        // 验证返回结果
        if ($result && isset($result['error']) && $result['error'] == 0) {
            return $this->formatResult(true, 'Success', $result['body'] ?? []);
        }
        // 如果响应失败，返回错误信息
        return $this->formatResult(false, $result['error_description'] ?? 'Unknown error', $result['body'] ?? []);
    }

    /**
     * 获取AccessToken
     * @return string
     * @throws PrinterException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function getAccessToken(): string
    {
        $cacheKey = $this->getDriverName() . 'accesstoken_' .
            $this->config['client_id'] . '_' .
            $this->config['client_secret'];
        // 尝试从缓存获取AccessToken
        $cachedToken = $this->cache->get($cacheKey);
        if ($cachedToken) {
            return $cachedToken;
        }
        // 如果缓存中没有，则请求新的AccessToken
        $result = $this->request('oauth/oauth', [
            'grant_type' => 'client_credentials',
            'scope'      => 'all',
        ]);
        // 检查请求结果
        if (!$result['success'] || empty($result['data']['access_token'])) {
            throw new PrinterException(
                'AccessToken获取失败: ' . $result['message'],
                $this->getDriverName(),
            );
        }
        // 缓存AccessToken
        $this->cache->set($cacheKey, $result['data']['access_token'], $result['data']['expires_in']);

        return $result['data']['access_token'];
    }

    /**
     * 生成签名
     * @param int $timestamp 时间戳
     * @return string
     */
    protected function generateSign(int $timestamp): string
    {
        return md5($this->config['client_id'] . $timestamp . $this->config['client_secret']);
    }

    /**
     * 生成 UUID
     * @return string
     */
    protected function uuid(): string
    {
        $chars = md5(uniqid(mt_rand(), true));

        return substr($chars, 0, 8) . '-'
            . substr($chars, 8, 4) . '-'
            . substr($chars, 12, 4) . '-'
            . substr($chars, 16, 4) . '-'
            . substr($chars, 20, 12);
    }
}
