# FoxNav - 专业的 WordPress 导航主题

## 主题简介

FoxNav 是一款功能强大、界面优美的 WordPress 导航主题，专为打造专业的网址导航站点而设计。相比市面上的主流导航主题（如 OneNav、NavXia），FoxNav 在功能性、用户体验和界面设计上都有显著提升。

## 核心特性

### 🎯 后台管理功能

- **自定义文章类型「网址」** - 专业的网址管理系统
- **完善的分类体系**
  - 网址分类（支持层级）
  - 网址标签
  - 特色标签
- **强大的元框系统**
  - 网址属性设置（域名、名称、Logo、截图、介绍等）
  - SEO 优化设置（标题、描述、关键词、OG图片等）
- **智能后台列表**
  - 可视化网址信息展示
  - 截图预览
  - 状态标识（官方、已验证、赞助等）
  - 统计数据显示

### 🚀 前端功能

- **网址卡片展示** - 精美的卡片式布局
- **详情页** - 完整的网址介绍页面
- **实时搜索** - AJAX 即时搜索功能
- **收藏功能** - 支持登录/未登录用户收藏
- **点击统计** - 自动统计网址访问量
- **响应式设计** - 完美支持移动端

### 🛠️ 实用工具

- **自动获取 Favicon** - 一键获取网站图标
- **自动网站截图** - 集成第三方截图服务
- **媒体上传** - 便捷的图片上传管理
- **面包屑导航** - 清晰的导航路径
- **SEO 优化** - 内置 SEO 设置

## 系统要求

- WordPress 6.0+
- PHP 8.0+
- MySQL 5.7+ 或 MariaDB 10.3+

## 安装步骤

1. 下载主题文件
2. 将主题文件夹上传到 `/wp-content/themes/` 目录
3. 在 WordPress 后台「外观 > 主题」中激活 FoxNav 主题
4. 访问「网址管理」开始添加网址

## 文件结构

```
foxnav/
├── inc/                      # 核心功能文件
│   ├── setup.php            # 主题初始化
│   ├── cpt-site.php         # 自定义文章类型
│   ├── taxonomies.php       # 分类法注册
│   ├── meta-boxes.php       # 元框定义
│   ├── admin-columns.php    # 后台列表自定义
│   ├── enqueue.php          # 资源加载
│   ├── helpers.php          # 辅助函数
│   └── ajax-handlers.php    # AJAX 处理
├── template-parts/          # 模板片段
│   ├── content-site.php     # 网址卡片
│   └── content-none.php     # 无内容
├── assets/                  # 前端资源
│   ├── css/                 # 样式文件
│   │   └── admin.css        # 后台样式
│   └── js/                  # 脚本文件
│       ├── admin.js         # 后台脚本
│       └── main.js          # 前端脚本
├── functions.php            # 主题函数
├── style.css               # 主样式表
├── index.php               # 主模板
├── header.php              # 头部模板
├── footer.php              # 页脚模板
├── single-site.php         # 网址详情页
├── archive-site.php        # 网址归档页
└── README.md              # 说明文档
```

## 使用指南

### 添加网址

1. 进入「网址管理 > 添加网址」
2. 填写基本信息：
   - 标题：网站名称
   - 内容：详细介绍（可选）
3. 在「网址属性」元框中设置：
   - **域名（网址）**：必填，完整的网址
   - **网站名称**：可选，留空则使用标题
   - **网站图标**：可上传或自动获取
   - **网站截图**：可上传或自动截图
   - **网站介绍**：简要描述
   - **状态标识**：官方认证、已验证等
4. 在「SEO 设置」元框中优化：
   - SEO 标题和描述
   - 关键词设置
   - 社交分享图片
5. 选择分类和标签
6. 发布

### 分类管理

1. 访问「网址分类」或「网址标签」
2. 可为分类设置：
   - 图标（Dashicons 类名或 URL）
   - 主题颜色
   - 排序顺序

### 自定义功能

#### 获取网址数据

```php
$site_data = foxnav_get_site_data($post_id);
```

#### 获取热门网址

```php
$popular_sites = foxnav_get_popular_sites(10);
```

#### 获取最新网址

```php
$recent_sites = foxnav_get_recent_sites(10);
```

#### 增加点击量

```php
foxnav_increment_click($post_id);
```

## AJAX 接口

主题提供了以下 AJAX 接口：

- `foxnav_fetch_favicon` - 自动获取网站图标
- `foxnav_auto_screenshot` - 自动网站截图
- `foxnav_increment_click` - 点击统计
- `foxnav_search_sites` - 搜索网址
- `foxnav_toggle_favorite` - 收藏/取消收藏

## 自定义开发

### 修改网址卡片样式

编辑 `template-parts/content-site.php`

### 修改详情页布局

编辑 `single-site.php`

### 添加自定义字段

在 `inc/meta-boxes.php` 中添加新的元框和字段

### 扩展 AJAX 功能

在 `inc/ajax-handlers.php` 中添加新的处理函数

## 常见问题

**Q: 如何更换截图服务？**

A: 编辑 `inc/ajax-handlers.php` 中的 `foxnav_generate_screenshot()` 函数，修改为你喜欢的截图服务 API。

**Q: 如何自定义搜索结果样式？**

A: 修改 `inc/ajax-handlers.php` 中的 `foxnav_ajax_search_sites()` 函数的 HTML 输出部分。

**Q: 如何添加更多网址状态？**

A: 在 `inc/meta-boxes.php` 的元框中添加新的复选框，并在保存函数中处理。

## 技术支持

- 主题官网：https://www.foxnav.cn  https://www.foxframe.cn
- 主题演示：navfree.foxframe.cn
- 文档中心：待完善
- 问题反馈：待完善

## 更新日志

### v1.0.0 (2024-10-01)

- 初始版本发布
- 完整的后台管理功能
- 网址卡片和详情页模板
- 搜索和收藏功能
- SEO 优化支持

## 开发计划

- [ ] 前端页面样式完善
- [ ] 首页自定义布局
- [ ] 高级搜索功能
- [ ] 网址提交表单
- [ ] 导入/导出功能
- [ ] 多语言支持
- [ ] 性能优化
- [ ] 主题设置面板

## ✨ 特性特点

- 🚀 轻量极速，加载性能优秀  
- 🎨 响应式设计，支持暗黑模式  
- 🧩 模块化结构，易于扩展与二次开发  
- 🧠 自定义导航分类与标签  
- 🔍 支持搜索、统计、收藏等功能  
- 🧰 兼容主流浏览器与移动端

---

## 📦 安装使用

1. 下载本主题（或通过 `git clone`）：
   ```bash
   git clone https://github.com/fengxun663/foxnav-free.git

## 许可证

GNU General Public License v3 or later

## 致谢

感谢所有为 WordPress 生态做出贡献的开发者。

---

**由 FoxNav 团队精心打造** ❤️























