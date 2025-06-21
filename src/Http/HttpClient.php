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

namespace Leapfu\CloudPrinter\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\GuzzleException;
use Leapfu\CloudPrinter\Exceptions\NetworkException;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

/**
 * HTTP 客户端封装类
 *
 * 处理所有与云打印服务商的HTTP通信
 */
class HttpClient implements \Psr\Log\LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var Client Guzzle HTTP 客户端
     */
    protected Client $client;

    /**
     * @var array 默认请求选项
     */
    protected array $defaultOptions = [
        'connect_timeout' => 10,
        'timeout'         => 30,
        'http_errors'     => false,
        'verify'          => true,
    ];

    /**
     * 构造函数
     *
     * @param array $options Guzzle 客户端选项
     * @param LoggerInterface|null $logger 日志记录器，可选
     */
    public function __construct(array $options = [], LoggerInterface $logger = null)
    {
        $this->logger = $logger ?? new NullLogger();
        $this->client = new Client(array_merge($this->defaultOptions, $options));
    }

    /**
     * 发送 POST 请求
     *
     * @param string $url 请求URL
     * @param array $data 请求数据
     * @param array $headers 请求头
     * @param array $options 额外选项
     * @return array
     * @throws NetworkException
     */
    public function post(string $url, array $data = [], array $headers = [], array $options = []): array
    {
        $options = array_merge([
            'json'    => $data,
            'headers' => $headers,
        ], $options);

        return $this->request('POST', $url, $options);
    }

    /**
     * 发送 GET 请求
     *
     * @param string $url 请求URL
     * @param array $query 查询参数
     * @param array $headers 请求头
     * @param array $options 额外选项
     * @return array
     * @throws NetworkException
     */
    public function get(string $url, array $query = [], array $headers = [], array $options = []): array
    {
        $options = array_merge([
            'query'   => $query,
            'headers' => $headers,
        ], $options);

        return $this->request('GET', $url, $options);
    }

    /**
     * 发送 HTTP 请求
     *
     * @param string $method 请求方法
     * @param string $url 请求URL
     * @param array $options 请求选项
     * @return array
     * @throws NetworkException 当请求失败时抛出异常
     */
    protected function request(string $method, string $url, array $options = []): array
    {
        $logContext = [
            'method'  => $method,
            'url'     => $url,
            'options' => $options,
        ];

        try {
            $this->logger->debug('Sending HTTP request', $logContext);
            // 发送请求
            $response = $this->client->request($method, $url, $options);
            // 获取状态码
            $statusCode = $response->getStatusCode();
            // 获取响应体
            $body = $response->getBody()->getContents();

            $logContext['status_code'] = $statusCode;
            $logContext['response'] = $body;
            // 响应成功
            if ($statusCode >= 200 && $statusCode < 300) {
                $this->logger->info('HTTP request successful', $logContext);
                return json_decode($body, true) ?? [];
            } else {
                $this->logger->error('HTTP request failed with error response', $logContext);
                throw new NetworkException(
                    sprintf('Request failed with status code %d: %s', $statusCode, $body),
                    $response,
                    $statusCode
                );
            }
        } catch (RequestException $e) {
            // 获取响应对象
            $response = $e->getResponse();
            // 获取状态码
            $statusCode = $response ? $response->getStatusCode() : 0;
            // 获取响应体
            $body = $response ? $response->getBody()->getContents() : 'No response';

            $logContext['error'] = $e->getMessage();
            $logContext['response'] = $body;
            $logContext['status_code'] = $statusCode;

            $this->logger->error('HTTP request failed with exception', $logContext);

            throw new NetworkException(
                sprintf('Request failed: %s', $e->getMessage()),
                $response,
                $e->getCode(),
                $e
            );
        } catch (GuzzleException $e) {
            $logContext['error'] = $e->getMessage();
            $this->logger->error('HTTP request failed with Guzzle exception', $logContext);

            throw new NetworkException(
                sprintf('Request failed: %s', $e->getMessage()),
                null,
                $e->getCode(),
                $e
            );
        }
    }
}
