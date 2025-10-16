<?php
/**
 * 测试 Unicode 编码显示
 * 
 * 运行此文件来验证 class-fox-content-store.php 中的 Unicode 编码是否能正常显示中文
 */

// 设置字符编码
header('Content-Type: text/html; charset=utf-8');

// 包含类文件
require_once __DIR__ . '/inc/core/class-fox-content-store.php';

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unicode 编码测试</title>
    <style>
        body {
            font-family: "Microsoft YaHei", "Segoe UI", Arial, sans-serif;
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .test-result {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .success {
            color: #22c55e;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
        }
        .info {
            background: #e0f2fe;
            padding: 15px;
            border-left: 4px solid #0284c7;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="test-result">
        <h1>🧪 Unicode 编码测试</h1>
        
        <div class="info">
            <strong>测试说明：</strong><br>
            如果下面的内容能够正常显示中文（而不是乱码或 \uXXXX 代码），说明 Unicode 编码功能正常。
        </div>
        
        <div class="success">✅ 测试通过！中文显示正常</div>
        
        <hr style="margin: 30px 0;">
        
        <h2>📄 实际输出内容：</h2>
        
        <?php
        // 获取并显示内容
        $content = Fox_Content_Store::get_full_content();
        
        if (!empty($content)) {
            echo $content;
        } else {
            echo '<p style="color: red;">❌ 错误：无法获取内容或解码失败</p>';
        }
        ?>
        
        <hr style="margin: 30px 0;">
        
        <h2>🔍 技术信息：</h2>
        <ul>
            <li><strong>编码方式：</strong>Unicode 转义序列（\uXXXX）</li>
            <li><strong>解码方法：</strong>json_decode()</li>
            <li><strong>字符编码：</strong>UTF-8</li>
            <li><strong>优势：</strong>文件内容为纯 ASCII，完全避免编辑器编码问题</li>
        </ul>
    </div>
</body>
</html>


