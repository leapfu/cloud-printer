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
 * 优声云打印机驱动
 *
 * 优声云打印接口文档：https://www.kancloud.cn/fage/us_api/1342975
 */
class UshengDriver extends BaseDriver
{
    /**
     * @var string API基础URL
     */
    protected string $baseUrl = 'https://open-api.ushengyun.com/printer/';

    /**
     * @var array 配置数组
     */
    protected array $config = [
        'app_id'     => '',    // 优声云应用key
        'app_secret' => '', // 优声云应用密钥
    ];

    /**
     * 获取打印机名称
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'usheng';
    }

    /**
     * 获取某台打印机状态
     * @param array $params
     * @return array
     */
    public function status(array $params): array
    {
        return $this->request('status', $params);
    }

    /**
     * 打印.
     * @param array $params
     * @return array
     */
    public function print(array $params): array
    {
        return $this->request('print', $params);
    }
    /**
     * 清空待打印队列.
     * @param array $params
     * @return array
     */
    public function emptyPrintqueue(array $params): array
    {
        return $this->request('emptyprintqueue', $params);
    }

    /**
     * 取消单条未打印订单.
     * @param array $params
     * @return array
     */
    public function cancelOne(array $params): array
    {
        return $this->request('cancelone', $params);
    }

    /**
     * 查询订单是否打印成功
     * @param array $params
     * @return array
     */
    public function printStatus(array $params): array
    {
        return $this->request('printstatus', $params);
    }

    /**
     * 设置设备音量.
     * @param array $params
     * @return array
     */
    public function sound(array $params): array
    {
        return $this->request('sound', $params);
    }

    /**
     * 打印logo
     * @param array $params
     * @return array
     */
    public function logo(array $params): array
    {
        return $this->request('logo', $params);
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
            'appid'     => $this->config['app_id'],
            'timestamp' => $timestamp,
        ], $params);
        $data['sign'] = $this->generateSign($data);
        // 构建请求URL
        $url = $this->baseUrl . $action;
        // 发送请求
        $result = $this->handleRequest($url, $data, $method);
        // 验证返回结果
        if ($result && isset($result['errNum']) && $result['errNum'] == 0) {
            return $this->formatResult(true, 'Success', $response['retData'] ?? []);
        }
        // 如果响应失败，返回错误信息
        return $this->formatResult(false, $result['retMsg'] ?? 'Unknown error', $result['retData'] ?? []);
    }

    /**
     * 生成签名
     *
     * @param array $params 请求参数
     * @return string
     */
    protected function generateSign(array $params): string
    {
        $stringToSigned = '';
        ksort($params);
        foreach ($params as $k => $v) {
            if (strlen($v) > 0) {
                $stringToSigned .= $k . $v;
            }
        }
        $stringToSigned .= $this->config['app_secret'];

        return md5($stringToSigned);
    }
}
