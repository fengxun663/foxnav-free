# FoxNav 核心类库

本目录包含 FoxNav 主题的核心类库。

## 文件说明

### class-fox-content-store.php
**内容存储类** - 使用 Base64 编码存储主题使用说明内容

- 将内容分为 4 个部分存储
- 使用 Base64 编码（非加密）
- 提供内容哈希值用于完整性验证

### class-fox-integrity.php
**完整性检查类** - 验证内容完整性并自动恢复

- 每 4 小时自动检查一次
- 使用 MD5 哈希值验证
- 自动恢复被修改的内容（最多 3 次/48 小时）
- 可通过配置禁用

## 禁用保护机制

如需禁用内容保护，在 `wp-config.php` 中添加：

```php
define('FOXNAV_DISABLE_INTEGRITY_CHECK', true);
```

## 许可证

所有文件遵循 GPL-3.0 开源协议。






