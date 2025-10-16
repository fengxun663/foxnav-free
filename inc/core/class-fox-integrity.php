<?php
/**
 * FoxNav Integrity Checker
 * 
 * 完整性检查类 - 验证内容完整性并自动恢复
 * 
 * @package FoxNav
 * @subpackage Core
 * @since 1.0.0
 * @license GPL-3.0
 * 
 * This file is part of FoxNav Theme.
 * 
 * FoxNav Theme is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * 说明：
 * 本类用于保护主题使用说明和版权信息的完整性。
 * 这不是为了限制用户的自由，而是为了：
 * 1. 确保用户能看到完整的使用文档
 * 2. 保留开源协议和版权信息（GPL-3.0 要求）
 * 3. 防止意外修改导致信息丢失
 * 
 * 如需禁用此功能，可在 wp-config.php 中添加：
 * define('FOXNAV_DISABLE_INTEGRITY_CHECK', true);
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 完整性检查类
 */
class Fox_Integrity_Checker
{
    /**
     * 检查间隔（秒）
     * 默认 4 小时检查一次
     */
    const CHECK_INTERVAL = 14400; // 4 * 60 * 60
    
    /**
     * 最大自动恢复次数
     * 超过此次数后停止自动恢复，防止冲突
     */
    const MAX_RESTORE_COUNT = 3;
    
    /**
     * 恢复计数器重置时间（秒）
     * 48 小时后重置计数器
     */
    const RESET_INTERVAL = 172800; // 48 * 60 * 60
    
    /**
     * 初始化
     */
    public static function init()
    {
        // 检查是否禁用了完整性检查
        if (defined('FOXNAV_DISABLE_INTEGRITY_CHECK') && FOXNAV_DISABLE_INTEGRITY_CHECK) {
            return;
        }
        
        // 注册定时检查（使用 WordPress Cron）
        add_action('foxnav_integrity_check', [__CLASS__, 'check_and_restore']);
        
        // 如果定时任务不存在，创建它
        if (!wp_next_scheduled('foxnav_integrity_check')) {
            wp_schedule_event(time(), 'fourly', 'foxnav_integrity_check');
        }
        
        // 在后台页面加载时也进行检查（但不是每次都检查）
        add_action('admin_init', [__CLASS__, 'maybe_check'], 20);
    }
    
    /**
     * 可能执行检查
     * 使用随机概率，避免每次都检查
     */
    public static function maybe_check()
    {
        // 只在主题选项页面检查
        if (!isset($_GET['page']) || $_GET['page'] !== 'fox-framework') {
            return;
        }
        
        // 检查上次检查时间
        $last_check = get_option('_foxnav_last_check', 0);
        $current_time = time();
        
        // 如果距离上次检查不足 1 小时，跳过
        if ($current_time - $last_check < 3600) {
            return;
        }
        
        // 25% 的概率执行检查
        if (rand(1, 100) > 25) {
            return;
        }
        
        // 执行检查
        self::check_and_restore();
        
        // 更新最后检查时间
        update_option('_foxnav_last_check', $current_time, false);
    }
    
    /**
     * 检查内容完整性并在需要时恢复
     * 
     * @return bool 是否通过检查
     */
    public static function check_and_restore()
    {
        // 检查必要的类是否存在
        if (!class_exists('Fox_Content_Store') || !class_exists('Fox_Content_Loader')) {
            return false;
        }
        
        // 获取当前内容的哈希值
        $current_hash = Fox_Content_Store::get_content_hash();
        
        // 获取存储的哈希值
        $stored_hash = get_option('_foxnav_content_hash', '');
        
        // 如果哈希值匹配，说明内容完整
        if ($current_hash === $stored_hash) {
            return true;
        }
        
        // 内容可能被修改，检查恢复次数
        $restore_count = get_option('_foxnav_restore_count', 0);
        $last_restore = get_option('_foxnav_last_restore', 0);
        $current_time = time();
        
        // 如果距离上次恢复超过重置间隔，重置计数器
        if ($current_time - $last_restore > self::RESET_INTERVAL) {
            $restore_count = 0;
        }
        
        // 如果恢复次数超过限制，不再自动恢复
        if ($restore_count >= self::MAX_RESTORE_COUNT) {
            // 记录到日志（如果启用了调试）
            if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
                error_log('FoxNav: 内容完整性检查失败，但已达到最大恢复次数限制');
            }
            return false;
        }
        
        // 执行恢复
        $restored = self::restore_content();
        
        if ($restored) {
            // 更新恢复计数
            update_option('_foxnav_restore_count', $restore_count + 1, false);
            update_option('_foxnav_last_restore', $current_time, false);
            
            // 记录到日志
            if (defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG) {
                error_log(sprintf('FoxNav: 内容已自动恢复（第 %d 次）', $restore_count + 1));
            }
        }
        
        return $restored;
    }
    
    /**
     * 恢复内容到数据库
     * 
     * @return bool 是否恢复成功
     */
    private static function restore_content()
    {
        // 清除缓存
        Fox_Content_Loader::clear_cache();
        
        // 重新保存备份
        $saved = Fox_Content_Loader::save_backup();
        
        return $saved;
    }
    
    /**
     * 手动重置恢复计数器
     * 可通过 WP-CLI 或代码调用
     */
    public static function reset_restore_count()
    {
        delete_option('_foxnav_restore_count');
        delete_option('_foxnav_last_restore');
        delete_option('_foxnav_last_check');
        
        return true;
    }
    
    /**
     * 获取完整性状态信息
     * 用于调试
     * 
     * @return array 状态信息
     */
    public static function get_status()
    {
        return [
            'enabled' => !(defined('FOXNAV_DISABLE_INTEGRITY_CHECK') && FOXNAV_DISABLE_INTEGRITY_CHECK),
            'restore_count' => get_option('_foxnav_restore_count', 0),
            'last_restore' => get_option('_foxnav_last_restore', 0),
            'last_check' => get_option('_foxnav_last_check', 0),
            'hash_stored' => get_option('_foxnav_content_hash', ''),
            'hash_current' => class_exists('Fox_Content_Store') ? Fox_Content_Store::get_content_hash() : '',
        ];
    }
}

/**
 * 注册自定义 Cron 间隔
 */
add_filter('cron_schedules', function($schedules) {
    if (!isset($schedules['fourly'])) {
        $schedules['fourly'] = [
            'interval' => 14400, // 4 小时
            'display'  => __('每 4 小时', 'foxnav'),
        ];
    }
    return $schedules;
});

/**
 * 主题停用时清理定时任务
 */
register_deactivation_hook(__FILE__, function() {
    $timestamp = wp_next_scheduled('foxnav_integrity_check');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'foxnav_integrity_check');
    }
});

