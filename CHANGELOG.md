# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-01-XX

### Added
- 初始版本发布
- 支持飞鹅云、芯烨云、易联云、快递100、映美云、佳博云、中午云、优声云等主流云打印服务
- 统一的 API 接口，屏蔽各家云打印厂商差异
- 驱动可扩展架构，支持自定义云打印服务
- 内置日志、缓存功能，支持自定义实现
- 完善的异常处理体系
- Laravel 和 ThinkPHP 框架集成支持
- 完整的单元测试覆盖
- 详细的文档和使用示例

### Features
- CloudPrinter 主类：统一管理配置、日志、缓存、HTTP 客户端和各类打印机驱动实例
- BaseDriver 基类：封装 HTTP、日志、缓存等通用能力
- PrinterInterface 接口：定义统一的打印机驱动接口
- 配置管理：支持多种配置来源，递归合并默认配置和用户配置
- HTTP 客户端：基于 Guzzle，支持日志、异常处理
- 缓存系统：基于 Symfony Cache，PSR 兼容
- 日志系统：基于 Monolog，PSR 兼容
- 异常体系：细分网络、打印机等异常，便于精确捕获和处理

### Framework Integration
- Laravel 集成：ServiceProvider、自动发现、门面类
- ThinkPHP 集成：provider.php 模板，支持服务注册

### Documentation
- 完整的 README 文档
- 框架集成指南
- 安全性建议
- 联系方式和支持信息 