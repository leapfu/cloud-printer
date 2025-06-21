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
 * 映美云打印机驱动
 * 映美云打印接口文档：https://open.jolimark.com/doc/
 */
class JolimarkDriver extends BaseDriver
{
    /**
     * @var string API基础URL
     */
    protected string $baseUrl = 'https://mcp.jolimark.com/';

    /**
     * @var array 配置数组
     */
    protected array $config = [
        'app_id'     => '',     // 映美云应用ID
        'app_secret' => '', // 映美云应用密钥
    ];

    /**
     * 获取打印机名称
     * @return string
     */
    public function getDriverName(): string
    {
        return 'jolimark';
    }

    /**
     * 添加打印机.
     * @param array $params 参数
     * @return array
     */
    public function bindPrinter(array $params): array
    {
        return $this->request('mcp/v3/sys/BindPrinter', $params);
    }

    /**
     * 检查打印机绑定结果.
     * @param array $params 参数
     * @return array
     */
    public function checkPrinterEnableBind(array $params): array
    {
        return $this->request('v3/sys/CheckPrinterEnableBind', $params);
    }

    /**
     * 删除打印机.
     * @param array $params 参数
     * @return array
     */
    public function unBindPrinter(array $params): array
    {
        return $this->request('mcp/v3/sys/UnBindPrinter', $params);
    }

    /**
     * 获取某台打印机状态
     * @param array $params 参数
     * @return array
     */
    public function queryPrinterStatus(array $params): array
    {
        return $this->request('mcp/v3/sys/QueryPrinterStatus', $params, 'GET');
    }

    /**
     * 打印映美规范HTML页面-传URL地址
     * @param array $params 参数
     * @return array
     */
    public function printHtmlUrl(array $params): array
    {
        return $this->request('mcp/v3/sys/PrintHtmlUrl', $params);
    }

    /**
     * 打印标准规范HTML页面-传URL.
     * @param array $params 参数
     * @return array
     */
    public function printHtmlToPic(array $params): array
    {
        return $this->request('mcp/v3/sys/PrintHtmlToPic', $params);
    }

    /**
     * 打印映美规范HTML页面-传HTML代码
     * @param array $params 参数
     * @return array
     */
    /**
     * @param array $params 参数
     * @return array
     */
    public function printHtmlCode(array $params): array
    {
        return $this->request('mcp/v3/sys/PrintHtmlCode', $params);
    }

    /**
     * 打印标准规范HTML页面-传HTML代码
     * @param array $params 参数
     * @return array
     */
    public function printRichHtmlCode(array $params): array
    {
        return $this->request('mcp/v3/sys/PrintRichHtmlCode', $params);
    }

    /**
     * 打印标准规范html页面-转灰度图.
     * @param array $params 参数
     * @return array
     */
    public function printHtmlToGrayPic(array $params): array
    {
        return $this->request('mcp/v3/sys/PrintHtmlToGrayPic', $params);
    }

    /**
     * 打印定点坐标文本.
     * @param array $params 参数
     * @return array
     */
    public function printPointText(array $params): array
    {
        return $this->request('mcp/v3/sys/PrintPointText', $params);
    }

    /**
     * 打印标签.
     * @param array $params 参数
     * @return array
     */
    public function printLabel(array $params): array
    {
        return $this->request('mcp/v3/sys/PrintLabel', $params);
    }

    /**
     * 打印快递面单.
     * @param array $params 参数
     * @return array
     */
    public function printExpress(array $params): array
    {
        return $this->request('mcp/v3/sys/PrintExpress', $params);
    }

    /**
     * 用户创建打印模版.
     * @param array $params 参数
     * @return array
     */
    public function printHtmlTemplate(array $params): array
    {
        return $this->request('mcp/v3/sys/PrintHtmlTemplate', $params);
    }

    /**
     * 打印ESC指令.
     * @param array $params 参数
     * @return array
     */
    public function printEsc(array $params): array
    {
        return $this->request('mcp/v3/sys/PrintEsc', $params);
    }

    /**
     * 打印本地文档.
     * @param array $params 参数
     * @return array
     */
    public function printFile(array $params): array
    {
        return $this->request('mcp/v3/sys/PrintFile', $params);
    }

    /**
     * 打印远程文档.
     * @param array $params 参数
     * @return array
     */
    public function fileByUrlPrint(array $params): array
    {
        return $this->request('mcp/v3/sys/PrintFileByUrl', $params);
    }

    /**
     * 增值税专用发票打印.
     * @param array $params 参数
     * @return array
     */
    public function printInvoice(array $params): array
    {
        return $this->request('mcp/v2/sys/PrintInvoice', $params);
    }

    /**
     * 清空待打印队列.
     * @param array $params 参数
     * @return array
     */
    public function cancelNotPrintTask(array $params): array
    {
        return $this->request('mcp/v3/sys/CancelNotPrintTask', $params);
    }

    /**
     * 查询订单是否打印成功
     * @param array $params 参数
     * @return array
     */
    public function queryPrintTaskStatus(array $params): array
    {
        return $this->request('mcp/v3/sys/QueryPrintTaskStatus', $params, 'GET');
    }

    /**
     * 查询未打印的任务
     * @param array $params 参数
     * @return array
     */
    public function queryNotPrintTask(array $params): array
    {
        return $this->request('mcp/v3/sys/QueryNotPrintTask', $params, 'GET');
    }

    /**
     * 打印 (type 控制打印类型)
     * @param array $params
     * @return array
     */
    public function print(array $params): array
    {
        $type = $params['type'] ?? 1;
        unset($params['type']);
        // 根据打印类型进行调用
        return match ($type) {
            // 简单url
            1  => $this->printHtmlUrl($params),
            // 简单html
            2  => $this->printHtmlCode($params),
            // 复杂url转图片
            3  => $this->printHtmlToPic($params),
            // 复杂url转灰度图
            4  => $this->printHtmlToGrayPic($params),
            // 映美模版
            5  => $this->printHtmlTemplate($params),
            // 坐标
            6  => $this->printPointText($params),
            // 快递单
            7  => $this->printExpress($params),
            // 打印复杂页面(html代码)
            8  => $this->printRichHtmlCode($params),
            // 打印ESC指令
            9  => $this->printEsc($params),
            // 打印本地文件
            10 => $this->printFile($params),
            // 打印远程文件
            11 => $this->fileByUrlPrint($params),
        };
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
        // 判断是否需要添加AccessToken
        if ($action != 'mcp/v2/sys/GetAccessToken') {
            $params['access_token'] = $this->getAccessToken();
        }
        // 添加公共参数
        $data = array_merge(['app_id' => $this->config['app_id']], $params);
        // 构建请求URL
        $url = $this->baseUrl . $action;
        // 发送请求
        $result = $this->handleRequest($url, $data, $method);
        // 验证返回结果
        if ($result && isset($result['return_code']) && $result['return_code'] == 0) {
            return $this->formatResult(true, 'Success', $result['return_data'] ?? []);
        }
        // 如果响应失败，返回错误信息
        return $this->formatResult(false, $result['return_msg'] ?? 'Unknown error', $result['return_data'] ?? []);
    }

    /**
     * 获取AccessToken
     * @return string
     * @throws PrinterException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    protected function getAccessToken(): string
    {
        $cacheKey = $this->getDriverName() . 'access_token_' .
            $this->config['app_id'] . "_" .
            $this->config['app_secret'];
        // 尝试从缓存获取AccessToken
        $cachedToken = $this->cache->get($cacheKey);
        if ($cachedToken) {
            return $cachedToken;
        }
        // 生成时间戳
        $timestamp = time();
        // 如果缓存中没有，则请求新的AccessToken
        $result = $this->request('mcp/v2/sys/GetAccessToken', [
            'time_stamp' => $timestamp,
            'sign'       => $this->generateSign($timestamp),
            'sign_type'  => 'MD5',
        ], 'GET');
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
        $str = http_build_query([
            'app_id'     => $this->config['app_id'],
            'sign_type'  => 'MD5',
            'time_stamp' => $timestamp,
            'key'        => $this->config['app_secret'],
        ]);

        return strtoupper(md5($str));
    }
}
