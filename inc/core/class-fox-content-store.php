<?php
/*
 * @Author        : linfox
 * @Url           : zibll.com
 * @Date          : 2020-09-29 13:18:50
 * @LastEditTime: 2023-11-28 23:40:48
 * @Email         : 770349780@qq.com
 * @Project       : Zibll子比主题
 * @Description   : 一款极其优雅的Wordpress主题
 * @Read me       : 感谢您使用子比主题，主题源码有详细的注释，支持二次开发。
 * @Remind        : 使用盗版主题会存在各种未知风险。支持正版，从我做起！
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 内容存储类
 * 用于存储主题使用说明的内容
 */
class Fox_Content_Store
{
    /**
     * 解码 Unicode 转义序列（\uXXXX 格式）
     * 
     * @param string $str 包含 Unicode 转义序列的字符串
     * @return string 解码后的 UTF-8 字符串
     */
    private static function decode_unicode($str)
    {
        // 使用正则表达式替换 \uXXXX 为对应的 UTF-8 字符
        return preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $str);
    }
    
    /**
     * 获取完整的使用说明内容
     * 
     * @return string HTML 内容（UTF-8 编码）
     */
    public static function get_full_content()
    {
        // HTML 内容保持可读性（符合开源精神）
        // 中文使用 Unicode 转义序列（\uXXXX），避免编码问题
        // 这样文件内容都是纯 ASCII 字符，不受编辑器编码影响
        $content = '<div style="background: #f0f6fc; padding: 20px; border-left: 4px solid #0969da; border-radius: 4px; margin: 20px 0;">
    <h2 style="margin-top: 0; color: #0969da;">🦊 \u6b22\u8fce\u4f7f\u7528\u0020\u0066\u006f\u0078\u006e\u0061\u0076\u0020\u7075\u72d0\u5bfc\u822a\u4e3b\u9898</h2>
    
    <h3>📖 \u4e3b\u9898\u7b80\u4ecb</h3>
    <p>\u7075\u72d0\u5bfc\u822a\u4e3b\u9898\u662f\u4e00\u6b3e\u4e13\u4e3a\u7f51\u5740\u5bfc\u822a\u7ad9\u8bbe\u8ba1\u7684\u0020\u0077\u006f\u0072\u0064\u0070\u0072\u0065\u0073\u0073\u0020\u4e3b\u9898\uff0c\u4e3b\u9898\u5206\u4e3a\u5f00\u6e90\u7248\uff0c\u793e\u533a\u7248\uff0c\u4e13\u4e1a\u7248\u3002\u5f00\u6e90\u7248\u5b8c\u5168\u514d\u8d39\u53ef\u5546\u4e1a\uff0c\u652f\u6301\u4efb\u610f\u4fee\u6539\uff0c\u4e8c\u6b21\u5f00\u53d1\u3002</p>
    <p>\u793e\u533a\u7248\u529f\u80fd\u66f4\u52a0\u5f3a\u5927\uff0c\u66f4\u591a\u529f\u80fd\uff0c\u8be6\u60c5\u548c\u6f14\u793a\u8bf7\u5230\u7075\u72d0\u4e3b\u9898\u5b98\u7f51\u4e86\u89e3\u3002\u793e\u533a\u7248\u4e5f\u662f\u514d\u8d39\u7684\uff0c\u4e0d\u8fc7\u4f60\u9700\u8981\u6309\u7167\u8981\u6c42\u514d\u8d39\u83b7\u53d6\u6388\u6743\uff0c\u5982\u679c\u4f60\u6b63\u5728\u4f7f\u7528\u793e\u533a\u7248\u662f\u4ed8\u8d39\u83b7\u5f97\u7684\uff0c\u90a3\u4f60\u88ab\u9a97\u4e86\u3002</p>
    <p>\u4e13\u4e1a\u7248\uff0c\u0066\u006f\u0078\u006e\u0061\u0076\u0020\u0070\u0072\u006f\u0020\u5177\u6709\u6700\u5b8c\u5584\u7684\u529f\u80fd\uff0c\u4e0d\u7ba1\u662f\u524d\u7aef\u9875\u9762\u8fd8\u662f\u540e\u53f0\u7ba1\u7406\u9875\u9762\u7684\u0055\u0049\uff0c\u7528\u6237\u4f53\u9a8c\u90fd\u6709\u5f88\u5927\u7684\u63d0\u5347\uff0c\u652f\u6301\u5bfc\u822a\u7f51\u7ad9\u6570\u636e\u91c7\u96c6\uff0c\u53ef\u4ee5\u0041\u0049\u667a\u80fd\u4e00\u952e\u91c7\u96c6\u586b\u5145\u5185\u5bb9\uff0c\u4e5f\u53ef\u4ee5\u8f93\u5165\u76ee\u6807\u7f51\u7ad9\u8fdb\u884c\u7cbe\u51c6\u91c7\u96c6\u3002\u540c\u65f6\u6dfb\u52a0\u7f51\u7ad9\u94fe\u63a5\u65f6\u53ea\u9700\u8981\u8f93\u5165\u7f51\u5740\uff0c\u7136\u540e\u70b9\u51fb\u81ea\u52a8\u751f\u6210\uff0c\u5373\u53ef\u751f\u6210\u6807\u9898\uff0c\u006c\u006f\u0067\u006f\u56fe\u7247\uff0c\u7f51\u5740\u4ecb\u7ecd\u6458\u8981\uff0c\u7f51\u7ad9\u622a\u56fe\u7b49\uff0c\u4e0d\u9700\u8981\u4f60\u624b\u52a8\u586b\u5199\uff0c\u66f4\u591a\u529f\u80fd\u8bf7\u5230\u7075\u72d0\u4e3b\u9898\u5b98\u7f51\u8be6\u7ec6\u4e86\u89e3\u3002</p>
    <p>\u7075\u72d0\u5bfc\u822a\u4e3b\u9898\u57fa\u4e8e\u7075\u72d0\u6846\u67b6\u5f00\u53d1\u800c\u6210\uff0c\u5177\u5907\u4ee5\u4e0b\u7279\u70b9</p>
    <ul>
        <li>✨ \u73b0\u4ee3\u5316\u7684\u8bbe\u8ba1\u98ce\u683c</li>
        <li>🚀 \u4f18\u79c0\u7684\u6027\u80fd\u8868\u73b0</li>
        <li>📱 \u5b8c\u7f8e\u7684\u54cd\u5e94\u5f0f\u5e03\u5c40</li>
        <li>🎨 \u4e30\u5bcc\u7684\u81ea\u5b9a\u4e49\u9009\u9879</li>
        <li>🔍 \u652f\u6301\u5168\u7ad9\u0053\u0045\u004f\u4f18\u5316</li>
        <li>⚙️ \u5f3a\u5927\u7684\u4e3b\u9898\u8bbe\u7f6e\u9009\u9879\u529f\u80fd</li>
    </ul>
    
    <h3>🚀 \u5feb\u901f\u5f00\u59cb</h3>
    <ol>
        <li><strong>\u56fe\u6807\u8bbe\u7f6e</strong>\uff1a\u914d\u7f6e\u7f51\u7ad9 Logo \u548c Favicon</li>
        <li><strong>\u57fa\u7840\u8bbe\u7f6e</strong>\uff1a\u8bbe\u7f6e\u7f51\u7ad9\u6807\u9898\u3001\u63cf\u8ff0\u7b49\u57fa\u672c\u4fe1\u606f</li>
        <li><strong>SEO \u8bbe\u7f6e</strong>\uff1a\u4f18\u5316\u641c\u7d22\u5f15\u64ce\u6536\u5f55</li>
        <li><strong>\u9875\u9762\u8bbe\u7f6e</strong>\uff1a\u914d\u7f6e\u9996\u9875\u3001\u5e7f\u544a\u7b49\u9875\u9762\u9009\u9879</li>
        <li><strong>\u5916\u89c2\u8bbe\u7f6e</strong>\uff1a\u81ea\u5b9a\u4e49\u4e3b\u9898\u989c\u8272\u548c\u6837\u5f0f</li>
    </ol>
    
    <h3>💡 \u4f7f\u7528\u6280\u5de7</h3>
    <ul>
        <li>🖼️ <strong>\u56fe\u7247\u4f18\u5316</strong>\uff1a\u4e3b\u9898\u5df2\u5185\u7f6e\u61d2\u52a0\u8f7d\u529f\u80fd\uff0c\u53ef\u5728\u201c\u529f\u80fd\u5f00\u5173\u201d\u4e2d\u914d\u7f6e</li>
        <li>🎯 <strong>SEO \u4f18\u5316</strong>\uff1a\u5efa\u8bae\u586b\u5199\u5b8c\u6574\u7684 SEO \u4fe1\u606f\uff0c\u5305\u62ec\u5173\u952e\u8bcd\u3001\u63cf\u8ff0\u7b49</li>
        <li>📊 <strong>\u6570\u636e\u7edf\u8ba1</strong>\uff1a\u53ef\u96c6\u6210\u767e\u5ea6\u7edf\u8ba1\u3001Google Analytics \u7b49\u5de5\u5177</li>
        <li>🔧 <strong>\u9ad8\u7ea7\u8bbe\u7f6e</strong>\uff1a\u5982\u9700\u81ea\u5b9a\u4e49\u4ee3\u7801\uff0c\u8bf7\u4f7f\u7528\u201c\u9ad8\u7ea7\u8bbe\u7f6e\u201d\u4e2d\u7684\u81ea\u5b9a\u4e49 CSS/JS</li>
    </ul>
    
    <h3>📚 \u6587\u6863\u4e0e\u652f\u6301</h3>
    <p>\u5982\u9700\u66f4\u591a\u5e2e\u52a9\uff0c\u8bf7\u53c2\u8003\uff1a</p>
    <ul>
        <li>📖 <a href="&#104;&#116;&#116;&#112;&#58;&#47;&#47;&#119;&#119;&#119;&#46;&#102;&#111;&#120;&#102;&#114;&#97;&#109;&#101;&#46;&#99;&#110;" target="_blank">\u5b98\u65b9\u7f51\u7ad9</a></li>
        <li>📖 <a href="&#104;&#116;&#116;&#112;&#58;&#47;&#47;&#110;&#97;&#118;&#102;&#114;&#101;&#101;&#46;&#102;&#111;&#120;&#102;&#114;&#97;&#109;&#101;&#46;&#99;&#110;" target="_blank">\u5728\u7ebf\u6f14\u793a</a></li>
        <li>📖 <a href="#" target="_blank">\u5728\u7ebf\u6587\u6863</a></li>
        <li>💬 <a href="#" target="_blank">\u793e\u533a\u8bba\u575b</a></li>
        <li>🐛 <a href="#" target="_blank">\u95ee\u9898\u53cd\u9988</a></li>
    </ul>
    
    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #d0d7de;">
        <p style="margin: 0; color: #57606a;">
            <strong>\u63d0\u793a</strong>\uff1a\u672c\u4e3b\u9898\u4e3a GPL-3.0 \u5f00\u6e90\u9879\u76ee\uff0c\u6b22\u8fce\u8d21\u732e\u4ee3\u7801\u548c\u53cd\u9988\u5efa\u8bae\uff01
        </p>
    </div>
</div>';
        
        // 解码 Unicode 转义序列
        $content = self::decode_unicode($content);
        
        // 如果解码失败，返回空字符串
        if ($content === null || $content === false) {
            return '';
        }
        
        return $content;
    }
    
    /**
     * 获取内容的 MD5 校验值
     * 用于完整性检查
     * 
     * @return string MD5 哈希值
     */
    public static function get_content_hash()
    {
        return md5(self::get_full_content());
    }
    
    /**
     * 获取编码后的内容（用于备份或传输）
     * 
     * 如果需要在不同编码环境间传输内容，可使用此方法
     * 
     * @return string Base64 编码的内容
     */
    public static function get_encoded_content()
    {
        return base64_encode(self::get_full_content());
    }
}
