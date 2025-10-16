<?php
/**
 * FoxNav Content Loader
 * 
 * 内容加载器 - 动态拼接和生成主题说明内容
 * 
 * @package FoxNav
 * @subpackage Helpers
 * @since 1.0.0
 * @license GPL-3.0
 * 
 * This file is part of FoxNav Theme.
 * 
 * FoxNav Theme is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 内容加载器类
 * 负责从多个存储位置加载并拼接完整内容
 */
class Fox_Content_Loader
{
    /**
     * 内容缓存
     * @var array
     */
    private static $cache = [];
    
    /**
     * 获取使用说明完整内容
     * 
     * @return string 完整的 HTML 内容
     */
    public static function get_usage_content()
    {
        // 如果已缓存，直接返回
        if (isset(self::$cache['usage'])) {
            return self::$cache['usage'];
        }
        
        // 从 Fox_Content_Store 获取内容
        $content = '';
        if (class_exists('Fox_Content_Store')) {
            $content = Fox_Content_Store::get_full_content();
        }
        
        // 如果主存储为空，尝试从数据库备份恢复
        if (empty($content)) {
            $content = get_option('_foxnav_usage_backup', '');
        }
        
        // 缓存结果
        self::$cache['usage'] = $content;
        
        return $content;
    }
    
    /**
     * 获取版权信息内容
     * 
     * @return string 版权信息 HTML
     */
    public static function get_copyright_content()
    {
        // 如果已缓存，直接返回
        if (isset(self::$cache['copyright'])) {
            return self::$cache['copyright'];
        }
        
        // 动态生成版权信息（包含主题版本等动态数据）
        $theme = wp_get_theme();
        $version = $theme->get('Version') ?: '1.0.0';
        
        $content = sprintf(
            '<div style="background: #fff; padding: 15px; border: 1px solid #d0d7de; border-radius: 4px; margin: 20px 0; text-align: center;">
                <p style="margin: 0; color: #57606a;">
                    ❤️ <strong>FoxNav</strong> - 开源 WordPress 导航主题<br>
                    <small>版本 %s | 遵循 GPL-3.0 开源协议</small>
                </p>
            </div>',
            esc_html($version)
        );
        
        // 缓存结果
        self::$cache['copyright'] = $content;
        
        return $content;
    }
    
    /**
     * 保存内容备份到数据库
     * 仅在首次运行或内容更新时调用
     * 
     * @return bool 是否保存成功
     */
    public static function save_backup()
    {
        // 检查是否已有备份
        if (get_option('_foxnav_usage_backup')) {
            return true; // 已有备份，不重复保存
        }
        
        // 获取完整内容
        $content = self::get_usage_content();
        
        // 保存到数据库（使用不显眼的选项名）
        $saved = update_option('_foxnav_usage_backup', $content, false);
        
        // 同时保存哈希值用于完整性检查
        if ($saved && class_exists('Fox_Content_Store')) {
            update_option('_foxnav_content_hash', Fox_Content_Store::get_content_hash(), false);
        }
        
        return $saved;
    }
    
    /**
     * 清除缓存
     * 用于测试或强制重新加载
     */
    public static function clear_cache()
    {
        self::$cache = [];
    }
}

/**
 * 初始化时保存备份
 * 仅在主题激活时执行一次
 */
add_action('after_switch_theme', function() {
    Fox_Content_Loader::save_backup();
});

