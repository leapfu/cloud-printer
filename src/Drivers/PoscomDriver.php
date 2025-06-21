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
 * 佳博云打印机驱动
 * 佳博云打印接口文档：https://dev.poscom.cn/docs
 */
class PoscomDriver extends BaseDriver
{
    /**
     * @var string API基础URL
     */
    protected string $baseUrl = 'https://api.poscom.cn/';

    /**
     * @var array 配置数组
     */
    protected array $config = [
        'api_key'     => '',         // 接口密钥
        'member_code' => '',      // 商户编码
    ];

    /**
     * 获取打印机名称
     * @return string
     */
    public function getDriverName(): string
    {
        return 'poscom';
    }

    /**
     * 查询（打印机）分组列表.
     * @param array $params
     * @return array
     */
    public function group(array $params): array
    {
        return $this->request('apisc/group', $params, 'GET');
    }

    /**
     * 添加（打印机）分组.
     * @param array $params
     * @return array
     */
    public function addGroup(array $params): array
    {
        return $this->request('apisc/addgroup', $params);
    }

    /**
     * 添加打印机.
     * @param array $params
     * @return array
     */
    public function addDev(array $params): array
    {
        return $this->request('apisc/adddev', $params);
    }

    /**
     * 修改设备信息.
     * @param array $params
     * @return array
     */
    public function editDev(array $params): array
    {
        return $this->request('apisc/editdev', $params);
    }

    /**
     * 删除打印机.
     * @param array $params
     * @return array
     */
    public function delDev(array $params): array
    {
        return $this->request('apisc/deldev', $params);
    }

    /**
     * 获取某台打印机状态
     * @param array $params
     * @return array
     */
    public function getStatus(array $params): array
    {
        return $this->request('apisc/getStatus', $params);
    }

    /**
     * 模板列表.
     * @param array $params
     * @return array
     */
    public function listTemplate(array $params): array
    {
        return $this->request('apisc/listTemplate', $params);
    }

    /**
     * 指定模板打印.
     * @param array $params
     * @return array
     */
    public function templetPrint(array $params): array
    {
        return $this->request('apisc/templetPrint', $params);
    }

    /**
     * 打印.
     * @param array $params
     * @return array
     */
    public function print(array $params): array
    {
        return $this->request('apisc/sendMsg', $params);
    }

    /**
     * 查询订单是否打印成功
     * @param array $params
     * @return array
     */
    public function queryState(array $params): array
    {
        return $this->request('apisc/queryState', $params, 'GET');
    }

    /**
     * 接口列表-打印机音量设置.
     * @param array $params
     * @return array
     */
    public function sendVolume(array $params): array
    {
        return $this->request('apisc/sendVolume', $params);
    }

    /**
     * 打印机切换播报类型.
     * @param array $params
     * @return array
     */
    public function setVoiceType(array $params): array
    {
        return $this->request('apisc/setVoiceType', $params);
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
        $data = [
            'memberCode' => $this->config['member_code'],
        ];
        // 合并请求参数
        $data = array_merge($data, $params);
        // 构建请求URL
        $url = $this->baseUrl . $action;
        // 发送请求
        $result = $this->handleRequest($url, $data, $method);
        // 验证返回结果
        if ($result && isset($result['code']) && ($result['code'] == 0) || ($result['code'] == 1)) {
            return $this->formatResult(true, 'Success', $result['data'] ?? []);
        }
        // 如果响应失败，返回错误信息
        return $this->formatResult(false, $result['msg'] ?? 'Unknown error', []);
    }

    /**
     * 生成签名
     * @param int $timestamp 时间戳
     * @return string
     */
    protected function generateSign(int $timestamp): string
    {
        return md5($this->config['member_code'] . $timestamp . $this->config['api_key']);
    }

    /**
     * 当前UNIX时间戳，精确到毫秒
     * @return string
     */
    public function getMillisecond()
    {
        list($s1, $s2) = explode(' ', microtime());
        return sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }
}
