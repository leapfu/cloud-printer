<?php

namespace Tests\Unit\Drivers;

use Leapfu\CloudPrinter\Drivers\FeieDriver;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Leapfu\CloudPrinter\Http\HttpClient;
use Leapfu\CloudPrinter\Exceptions\NetworkException;

class FeieDriverTest extends TestCase
{
    protected array $config = [
        'user' => 'test',
        'ukey' => 'test',
    ];

    public function testCanBeConstructed()
    {
        $http = $this->createMock(HttpClient::class);
        $logger = $this->createMock(LoggerInterface::class);
        $cache = $this->createMock(CacheInterface::class);
        $driver = new FeieDriver($this->config, $http, $logger, $cache);
        $this->assertInstanceOf(FeieDriver::class, $driver);
    }

    public function testGetDriverName()
    {
        $http = $this->createMock(HttpClient::class);
        $logger = $this->createMock(LoggerInterface::class);
        $cache = $this->createMock(CacheInterface::class);
        $driver = new FeieDriver($this->config, $http, $logger, $cache);
        $this->assertEquals('feie', strtolower($driver->getDriverName()));
    }

    public function testRequestSuccess()
    {
        $http = $this->createMock(HttpClient::class);
        $logger = $this->createMock(LoggerInterface::class);
        $cache = $this->createMock(CacheInterface::class);
        $http->method('post')->willReturn(['ret' => 0, 'msg' => 'ok']);
        $driver = new FeieDriver($this->config, $http, $logger, $cache);
        $result = $driver->request('print', ['content' => 'test']);
        $this->assertIsArray($result);
    }

    public function testRequestNetworkException()
    {
        $http = $this->createMock(HttpClient::class);
        $logger = $this->createMock(LoggerInterface::class);
        $cache = $this->createMock(CacheInterface::class);
        $http->method('post')->willThrowException(new NetworkException('Network error'));
        $driver = new FeieDriver($this->config, $http, $logger, $cache);
        $result = $driver->request('print', ['content' => 'test']);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('Request failed', $result['message']);
    }

    public function testPrint()
    {
        $http = $this->createMock(HttpClient::class);
        $logger = $this->createMock(LoggerInterface::class);
        $cache = $this->createMock(CacheInterface::class);
        $http->method('post')->willReturn(['ret' => 0, 'msg' => 'ok', 'data' => []]);
        $driver = new FeieDriver($this->config, $http, $logger, $cache);
        $result = $driver->print([
            'content' => 'hello',
            'sn' => 'sn123',
            'copies' => 1
        ]);
        $this->assertTrue($result['success']);
    }
}
