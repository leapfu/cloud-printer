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

namespace Leapfu\CloudPrinter\Cache;

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

class FileCache implements CacheInterface
{
    protected CacheInterface $cache;

    protected string $cacheNamespace = 'cloud_printer';

    public function __construct(string $cacheDir)
    {
        $psr6 = new FilesystemAdapter($this->cacheNamespace, 0, $cacheDir ?: null);
        $this->cache = new Psr16Cache($psr6);
    }

    // 实现所有 PSR-16 方法
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->cache->get($key, $default);
    }

    public function set(string $key, mixed $value, null|int|\DateInterval $ttl = null): bool
    {
        return $this->cache->set($key, $value, $ttl);
    }

    public function delete(string $key): bool
    {
        return $this->cache->delete($key);
    }

    public function clear(): bool
    {
        return $this->cache->clear();
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return $this->cache->getMultiple($keys, $default);
    }

    public function setMultiple(iterable $values, null|int|\DateInterval $ttl = null): bool
    {
        return $this->cache->setMultiple($values, $ttl);
    }

    public function deleteMultiple(iterable $keys): bool
    {
        return $this->cache->deleteMultiple($keys);
    }

    public function has(string $key): bool
    {
        return $this->cache->has($key);
    }
}
