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

use Leapfu\CloudPrinter\Contracts\PrinterInterface;
use Leapfu\CloudPrinter\Http\HttpClient;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * 打印机基类
 * 提供所有打印机驱动共用的功能
 */
abstract class BaseDriver implements PrinterInterface
{
    /**
     * @var array 配置数组
     */
    protected array $config = [];

    /**
     * @var HttpClient HTTP客户端
     */
    protected HttpClient $httpClient;

    /**
     * @var LoggerInterface 日志实例
     */
    protected LoggerInterface $logger;

    /**
     * @var CacheInterface 缓存实例
     */
    protected CacheInterface $cache;

    /**
     * 获取驱动名称
     * @return string
     */
    abstract public function getDriverName(): string;

    /**
     * 构造函数
     * @param array $config 打印机配置
     * @param HttpClient $http 客户端实例
     * @param LoggerInterface $logger 日志实例
     * @param CacheInterface $cache 缓存实例
     */
    public function __construct(array $config, HttpClient $http, LoggerInterface $logger, CacheInterface $cache)
    {
        $this->config = array_merge($this->config, $config);
        // 初始化HTTP客户端
        $this->httpClient = $http;
        // 初始化日志
        $this->logger = $logger;
        // 使用传入的缓存实例
        $this->cache = $cache;
    }

    /**
     * 处理请求
     * @param string $url 请求URL
     * @param array $data 请求数据
     * @param string $method 请求方式
     * @return array
     */
    protected function handleRequest(string $url, array $data, string $method = 'POST'): array
    {
        if (strtoupper($method) === 'GET') {
            return $this->httpClient->get($url, $data);
        } else {
            return $this->httpClient->post($url, $data);
        }
    }

    /**
     * 格式化结果集
     * @param bool $success 是否成功
     * @param string $message 消息
     * @param array $data 数据
     * @return array
     */
    public function formatResult(bool $success, string $message, array $data = []): array
    {
        return [
            'success' => $success,
            'message' => $message,
            'data'    => $data,
        ];
    }
}
