<?php

namespace Tests\Unit;

use Leapfu\CloudPrinter\CloudPrinter;
use Leapfu\CloudPrinter\Exceptions\PrinterException;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;

class CloudPrinterTest extends TestCase
{
    protected array $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->config = require __DIR__ . '/../../config/config.php';
        // 填充最小可用配置，防止测试报错
        $this->config['drivers']['feie']['user'] = 'test';
        $this->config['drivers']['feie']['ukey'] = 'test';
    }

    public function testInstanceCanBeCreated()
    {
        $printer = new CloudPrinter($this->config);
        $this->assertInstanceOf(CloudPrinter::class, $printer);
    }

    public function testGetDriverInstance()
    {
        $printer = new CloudPrinter($this->config);
        $driver = $printer->driver('feie');
        $this->assertEquals('feie', strtolower($driver->getDriverName()));
    }

    public function testSetLoggerAndCache()
    {
        $printer = new CloudPrinter($this->config);
        $printer->setLogger(new NullLogger());
        $mockCache = $this->createMock(CacheInterface::class);
        $printer->setCache($mockCache);
        $this->assertTrue(true); // 只验证无异常
    }

    public function testDynamicCall()
    {
        $printer = new CloudPrinter($this->config);
        $driver = $printer->driver();
        if (method_exists($driver, 'getDriverName')) {
            $this->assertEquals($driver->getDriverName(), $printer->getDriverName());
        } else {
            $this->markTestSkipped('驱动未实现 getDriverName');
        }
    }

    public function testInvalidDriverThrowsException()
    {
        $this->expectException(PrinterException::class);
        $printer = new CloudPrinter($this->config);
        $printer->driver('not_exist');
    }
}
