# Cloud Printer

**Cloud Printer** 是一款高扩展性、易集成的 PHP 云小票打印 SDK，统一封装了飞鹅云、芯烨云、易联云、快递 100、映美云、佳博云、中午云、优声云等主流云打印服务，支持多驱动切换、主流框架集成、灵活配置和完善的异常处理。

---

## 主要特性

- 🚀 统一 API，屏蔽各家云打印厂商差异
- 🖨️ 支持多种主流云打印机
- 🧩 驱动可扩展，支持自定义云打印服务
- 📝 内置日志、缓存，支持自定义实现
- ⚡ 完善的异常处理体系
- 🛠️ 兼容 Laravel、ThinkPHP 等主流框架
- 📦 Composer 一键安装

---

## 快速上手

### 1. 安装依赖

```bash
composer require leapfu/cloud-printer
```

### 2. 初始化 SDK

```php
use Leapfu\CloudPrinter\CloudPrinter;

$config = require 'config/config.php';
$printer = new CloudPrinter($config);
```

### 3. 打印文本（所有驱动统一 print 方法，参数为数组）

```php
// 使用默认打印机
$result = $printer->driver()->print([
    'content' => '测试内容',
    'sn' => '打印机SN',
    'copies' => 1
]);

// 指定打印机类型
$result = $printer->driver('feie')->print([
    'content' => '内容',
    'sn' => 'SN',
    'copies' => 1
]);

// 直接调用打印机方法（动态代理）
$result = $printer->print([
    'content' => '内容',
    'sn' => 'SN',
    'copies' => 1
]);

if ($result['success']) {
    echo '打印成功';
} else {
    echo '打印失败：' . $result['message'];
}
```

> 所有驱动都实现 print 方法，参数为数组（如 ['content' => '内容', 'sn' => 'SN', 'copies' => 1]），返回统一格式。其他高级功能请查阅对应驱动扩展方法文档。

---

## 框架集成用法

### Laravel 集成

1. **自动注册**（支持 Laravel Package Discovery，无需手动配置）
2. **发布配置文件（可选）**

   ```bash
   php artisan vendor:publish --provider="Leapfu\\CloudPrinter\\Laravel\\CloudPrinterServiceProvider" --tag=config
   ```

3. **门面调用**

   ```php
   use CloudPrinter;
   CloudPrinter::driver()->print([
       'content' => '内容',
       'sn' => 'SN',
       'copies' => 1
   ]);
   ```

4. **容器调用**

   ```php
   $printer = app(Leapfu\CloudPrinter\CloudPrinter::class);
   $printer->driver()->print([
       'content' => '内容',
       'sn' => 'SN',
       'copies' => 1
   ]);
   ```

### ThinkPHP 集成

1. 在 `config/cloudprint.php` 配置参数。
2. 使用：

   ```php
   app('cloud_printer')->driver()->print([
       'content' => '内容',
       'sn' => 'SN',
       'copies' => 1
   ]);
   ```

> 如需服务注册模板，可参考 `src/ThinkPHP/provider.php`。

---

## 安全性建议

- 敏感信息建议通过 .env 或环境变量配置，不要硬编码在代码仓库。
- 日志中避免输出账号、密钥等敏感数据。

---

## 贡献与支持

- 欢迎提交 PR 或 Issue 参与共建！
- 如需更多示例或遇到问题，欢迎提交 Issue。

---

## 获取帮助与联系方式

- 📧 <leapfu@hotmail.com>
- 🐧 QQ 群：824070084（备注"云打印 SDK"）
- 🌐 官网：[https://www.leapfu.com](https://www.leapfu.com)
- 📝 Issue 反馈：[GitHub Issues](https://github.com/leapfu/cloud-printer/issues)

如有商务合作、定制开发、技术支持等需求，欢迎通过以上方式联系我们。

---
