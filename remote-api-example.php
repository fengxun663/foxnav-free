<?php
/**
 * 远程API示例文件
 * 
 * 将此文件部署到您的远程服务器上
 * 例如：https://your-domain.com/api/theme-info.php
 * 
 * 然后在 class-fox-protection.php 中设置：
 * private static $remote_api = 'https://your-domain.com/api/theme-info.php';
 */

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

// 验证请求来源（可选）
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$theme_domain = $_SERVER['HTTP_THEME_DOMAIN'] ?? '';

// 记录请求日志（可选）
// file_put_contents('access.log', date('Y-m-d H:i:s') . " - {$theme_domain} - {$user_agent}\n", FILE_APPEND);

// 返回使用说明内容
$response = [
    'success' => true,
    'version' => '1.0.0',
    'updated' => date('Y-m-d H:i:s'),
    'content' => '
        <div class="fox-usage-guide">
            <h2>🚀 主题使用说明</h2>
            
            <div class="alert alert-info">
                <h3>👋 欢迎使用灵狐导航主题</h3>
                <p>灵狐导航请到：</p>
                <ol>
                    <li>网址设置 → 处理设置 SEO设置 /页面设置</li>
                    <li>外观设置 → 管理颜色、图标、伴奏等设置</li>
                    <li>功能开关 → 启用懒加载、平滑滚动、搜索等</li>
                    <li>颜色主题 → 设置主要色、次要色、强调色/手机屏示茶等</li>
                </ol>
            </div>
            
            <div class="alert alert-warning">
                <h3>⚠️ 版权声明</h3>
                <p><b>本主题由国内开发者 狐狸狸 (FoxNav) 开发，免费开源使用 GPL-3.0 协议。</b></p>
                <p>你可以:</p>
                <ul>
                    <li>✅ 自由自改</li>
                    <li>✅ 修改后再发布</li>
                    <li>✅ 内嵌其他主题中使用（需保留版权信息）</li>
                </ul>
                <p>你不可以:</p>
                <ul>
                    <li>❌ 删除版权信息</li>
                    <li>❌ 删除开发者信息</li>
                    <li>❌ 将主题伪装成商业产品</li>
                </ul>
            </div>
            
            <div class="alert alert-success">
                <h3>💚支持</h3>
                <p>如果你觉得这个主题有价值，请支持我们的发展！</p>
                <ul>
                    <li>👉 <a href="https://gitee.com/foxnav" target="_blank">Gitee 开源地址</a></li>
                    <li>👉 <a href="https://github.com/foxnav" target="_blank">GitHub 开源地址</a></li>
                    <li>📮 <a href="https://www.foxnav.com" target="_blank">官网</a></li>
                </ul>
            </div>
            
            <div class="alert alert-primary">
                <h3>🔄 更新日志</h3>
                <ul>
                    <li><b>v1.0.0</b> (2024-01-01) - 初始版本发布</li>
                    <li><b>v1.1.0</b> (2024-02-01) - 添加懒加载功能</li>
                    <li><b>v1.2.0</b> (2024-03-01) - 优化SEO设置</li>
                </ul>
                <p><small>💡 提示：内容由云端实时更新</small></p>
            </div>
        </div>
    ',
];

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

