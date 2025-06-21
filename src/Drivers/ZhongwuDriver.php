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
 * 中午云打印机驱动
 * 中午云打印接口文档：https://5q2gw3mpeg.k.topthink.com/dayin.html
 */
class ZhongwuDriver extends BaseDriver
{
    /**
     * @var string API基础URL
     */
    protected string $baseUrl = 'http://api.zhongwuyun.com/';

    /**
     * @var array 配置数组
     */
    protected array $config = [
        'app_id'     => '',    // 中午云应用key
        'app_secret' => '', // 中午云应用密钥
    ];

    /**
     * 获取打印机名称
     *
     * @return string
     */
    public function getDriverName(): string
    {
        return 'zhongwu';
    }

    /**
     * 获取某台打印机状态
     * @param array $params
     * @return array
     */
    public function status(array $params): array
    {
        return $this->request('status', $params, 'GET');
    }

    /**
     * 打印.
     * @param array $params
     * @return array
     */
    public function print(array $params): array
    {
        return $this->request('', $params);
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
        return $this->request('printstatus', $params, true);
    }

    /**
     * 设置音量.
     * @param array $params
     * @return array
     */
    public function sound(array $params): array
    {
        return $this->request('sound', $params);
    }

    /**
     * 设置语音（未上线）.
     * @param array $params
     * @return array
     */
    public function voice(array $params): array
    {
        return $this->request('voice', $params);
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
        // 生成请求数据
        $data = array_merge([
            'appid'     => $this->config['app_id'],
            'timestamp' => time(),
        ], $params);
        // 添加签名
        $data['sign'] = $this->generateSign($data);
        // 构建请求URL
        $url = $this->baseUrl . $action;
        // 发送请求
        $result = $this->handleRequest($url, $data, $method);
        // 验证返回结果
        if ($result && isset($result['errNum']) && $result['errNum'] == 0) {
            return $this->formatResult(true, 'Success', $result['retData'] ?? []);
        }
        // 如果响应失败，返回错误信息
        return $this->formatResult(false, $result['errMsg'] ?? 'Unknown error', $result['retData'] ?? []);
    }

    /**
     * 生成签名
     * @param array $params
     * @return string
     */
    protected function generateSign(array $params): string
    {
        $str = '';
        // 按key排序
        ksort($params);
        // 拼接字符串
        foreach ($params as $k => $v) {
            $str .= $k . $v;
        }
        // 添加应用密钥
        $str .= $this->config['app_secret'];
        // 返回签名结果
        return md5($str);
    }
}
