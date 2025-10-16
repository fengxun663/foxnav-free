<?php
/**
 * FoxNav 主题核心函数
 *
 * @package FoxNav
 * @version 1.0.0
 * @license GPL-3.0
 * 
 * This file is part of FoxNav Theme.
 * 
 * FoxNav Theme is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * FoxNav Theme is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with FoxNav Theme. If not, see <https://www.gnu.org/licenses/>.
 */

if (!defined('ABSPATH')) {
    exit;
}

// 定义主题常量
define('FOXNAV_VERSION', '1.0.0');
define('FOXNAV_THEME_DIR', get_template_directory());
define('FOXNAV_THEME_URI', get_template_directory_uri());
define('FOXNAV_INC_DIR', FOXNAV_THEME_DIR . '/inc');

/**
 * 引入核心功能文件
 */
require_once FOXNAV_INC_DIR . '/setup.php';
require_once FOXNAV_INC_DIR . '/cpt-site.php';
require_once FOXNAV_INC_DIR . '/cpt-friendlink.php';
require_once FOXNAV_INC_DIR . '/taxonomies.php';
require_once FOXNAV_INC_DIR . '/meta-boxes.php';
require_once FOXNAV_INC_DIR . '/admin-columns.php';
require_once FOXNAV_INC_DIR . '/enqueue.php';
require_once FOXNAV_INC_DIR . '/helpers.php';
require_once FOXNAV_INC_DIR . '/ajax-handlers.php';

// 引入灵狐框架
require_once FOXNAV_INC_DIR . '/framework/fox-framework.php';

// 引入内容保护相关类（轻量级保护，符合 GPL-3.0 开源精神）
require_once FOXNAV_INC_DIR . '/core/class-fox-content-store.php';
require_once FOXNAV_INC_DIR . '/helpers/content-loader.php';
require_once FOXNAV_INC_DIR . '/core/class-fox-integrity.php';

// 引入更新系统
require_once FOXNAV_INC_DIR . '/updates/class-foxnav-updater.php';
require_once FOXNAV_INC_DIR . '/updates/class-foxnav-backup.php';
require_once FOXNAV_INC_DIR . '/updates/update-page-template.php';

// 引入主题设置选项
require_once FOXNAV_INC_DIR . '/theme-options.php';

// 引入主题辅助函数
require_once FOXNAV_INC_DIR . '/theme-helpers.php';

// 引入主题使用示例（可选，用于演示如何使用设置）
require_once FOXNAV_INC_DIR . '/theme-usage-examples.php';

// 引入主题模板集成（自动对接主题设置到前端）
require_once FOXNAV_INC_DIR . '/theme-template-integration.php';

// 引入小工具
require_once FOXNAV_INC_DIR . '/widgets.php';

/**
 * 主题激活时执行
 */
function foxnav_theme_activation() {
    // 刷新固定链接规则
    flush_rewrite_rules();
    
    // 初始化内容备份
    if (class_exists('Fox_Content_Loader')) {
        Fox_Content_Loader::save_backup();
    }
}
add_action('after_switch_theme', 'foxnav_theme_activation');

/**
 * 初始化完整性检查
 */
if (class_exists('Fox_Integrity_Checker')) {
    Fox_Integrity_Checker::init();
}

/**
 * 初始化主题更新系统
 */
if (class_exists('FoxNav_Updater')) {
    new FoxNav_Updater();
}

/**
 * 处理备份下载请求
 */
add_action('admin_post_foxnav_download_backup', function() {
    if (!current_user_can('manage_options')) {
        wp_die(__('权限不足', 'foxnav'));
    }
    
    $filename = isset($_GET['file']) ? sanitize_file_name($_GET['file']) : '';
    
    if (empty($filename)) {
        wp_die(__('文件名无效', 'foxnav'));
    }
    
    FoxNav_Backup::download_backup($filename);
});

/**
 * 显示更新通知横幅
 */
add_action('admin_notices', function() {
    // 检查是否启用通知
    if (!foxnav_get_option('update_notification', true)) {
        return;
    }
    
    // 检查权限
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // 检查是否在主题页面（避免过度干扰）
    $screen = get_current_screen();
    if (!in_array($screen->id, ['themes', 'toplevel_page_fox-framework'])) {
        return;
    }
    
    // 检查是否有更新
    if (!class_exists('FoxNav_Updater') || !FoxNav_Updater::has_update()) {
        return;
    }
    
    $update_info = FoxNav_Updater::get_update_info();
    if (!$update_info) {
        return;
    }
    
    $current_version = FoxNav_Updater::get_current_version();
    ?>
    <div class="notice notice-warning is-dismissible foxnav-update-notice">
        <p>
            <strong>🎉 FoxNav 主题有新版本可用！</strong>
            当前版本：<?php echo esc_html($current_version); ?> | 
            最新版本：<strong style="color: #d63638;"><?php echo esc_html($update_info['version']); ?></strong>
        </p>
        <p>
            <a href="<?php echo esc_url(admin_url('themes.php')); ?>" class="button button-primary">立即更新</a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=fox-framework&section=updates')); ?>" class="button">查看详情</a>
            <a href="<?php echo esc_url($update_info['details_url']); ?>" class="button" target="_blank">查看更新日志</a>
        </p>
    </div>
    <?php
});

/**
 * 主题停用时执行
 */
function foxnav_theme_deactivation() {
    flush_rewrite_rules();
}
add_action('switch_theme', 'foxnav_theme_deactivation');


// 禁用古登堡编辑器

// 方法1：完全禁用古登堡编辑器，适用于所有文章类型
add_filter('use_block_editor_for_post', '__return_false');


