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

namespace Leapfu\CloudPrinter;

use Leapfu\CloudPrinter\Cache\FileCache;
use Leapfu\CloudPrinter\Contracts\PrinterInterface;
use Leapfu\CloudPrinter\Exceptions\PrinterException;
use Leapfu\CloudPrinter\Http\HttpClient;
use Leapfu\CloudPrinter\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Leapfu\CloudPrinter\Support\Config;

/**
 * 云打印SDK主类
 *
 * 提供统一的接口来操作各种云打印机
 */
class CloudPrinter
{
    /**
     * @var array 配置数组
     */
    protected array $config;

    /**
     * @var HttpClient HTTP客户端
     */
    protected HttpClient $httpClient;

    /**
     * @var LoggerInterface 日志记录器
     */
    protected LoggerInterface $logger;

    /**
     * @var CacheInterface 缓存实例
     */
    protected CacheInterface $cache;

    /**
     * @var array 打印机实例缓存
     */
    protected array $drivers = [];

    /**
     * 构造函数
     * @param array|string|null $config 配置
     */
    public function __construct(array|string|null $config = null)
    {
        // 加载配置
        $this->config = Config::load($config);
        // 初始化日志
        $this->logger = new LoggerFactory($this->config['log_path'] ?? '');
        // 初始化缓存
        $this->cache = new FileCache($this->config['cache_path'] ?? '');
        // 初始化HTTP客户端
        $this->httpClient = new HttpClient($this->config['http'] ?? [], $this->logger);
    }

    /**
     * 设置日志记录器
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger): static
    {
        $this->logger = $logger;

        $this->httpClient->setLogger($logger);

        return $this;
    }

    /**
     * 设置缓存实例
     * @param CacheInterface $cache
     * @return $this
     */
    public function setCache(CacheInterface $cache): static
    {
        $this->cache = $cache;
        return $this;
    }

    /**
     * 获取打印机实例
     * @param string|null $printerType 打印机类型，如果为null则使用默认打印机
     * @return PrinterInterface
     * @throws PrinterException 当打印机类型无效或配置错误时抛出
     */
    public function driver(string $printerType = null): PrinterInterface
    {
        $printerType = $printerType ?: $this->config['default'];

        if (!isset($this->drivers[$printerType])) {
            if (!isset($this->config['drivers'][$printerType])) {
                throw new PrinterException("Printer type not found: {$printerType}", $printerType);
            }

            $printerConfig = $this->config['drivers'][$printerType];

            if (empty($printerConfig) || !is_array($printerConfig)) {
                throw new PrinterException("Invalid printer configuration: {$printerType}", $printerType);
            }

            try {
                $this->drivers[$printerType] = $this->createPrinter($printerType, $printerConfig);
            } catch (\Exception $e) {
                $this->logger->error("Failed to create printer instance: " . $e->getMessage(), [
                    'printer_type' => $printerType,
                    'exception'    => $e
                ]);

                throw new PrinterException("Failed to create printer instance: " . $e->getMessage(), $printerType, 0, $e);
            }
        }

        return $this->drivers[$printerType];
    }

    /**
     * 创建打印机实例
     * @param string $type 打印机类型
     * @param array $config 打印机配置
     * @return PrinterInterface
     * @throws PrinterException 当打印机类不存在或创建失败时抛出
     */
    protected function createPrinter(string $type, array $config): PrinterInterface
    {
        // 支持自定义打印机类
        if (isset($config['class'])) {
            $printerClass = $config['class'];
            unset($config['class']);
        } else {
            $printerClass = 'Leapfu\\CloudPrinter\\Drivers\\' . ucfirst($type) . 'Driver';
        }

        if (!class_exists($printerClass)) {
            throw new PrinterException("Printer class does not exist: {$printerClass}", $type);
        }

        try {
            $reflection = new \ReflectionClass($printerClass);
            // 修正参数传递顺序：传递正确的Cache实例
            $printer = $reflection->newInstanceArgs([
                $config,
                $this->httpClient,
                $this->logger,
                $this->cache
            ]);

            if (!$printer instanceof PrinterInterface) {
                throw new PrinterException("The printer class must implement the PrinterInterface interface", $type);
            }

            return $printer;
        } catch (\ReflectionException $e) {
            throw new PrinterException("Unable to instantiated printer class: {$printerClass}", $type, 0, $e);
        } catch (\Throwable $e) {
            throw new PrinterException("Failed to create printer instance: " . $e->getMessage(), $type, 0, $e);
        }
    }

    /**
     * 动态调用
     * @param string $name 方法名
     * @param array $arguments 参数
     * @return mixed
     * @throws PrinterException
     */
    public function __call(string $name, array $arguments)
    {
        return $this->driver()->$name(...$arguments);
    }
}
