<?php
/**
 * FoxNav Update Page Template
 * 
 * 更新管理页面模板
 * 
 * @package FoxNav
 * @subpackage Updates
 * @since 1.0.0
 * @license GPL-3.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 渲染更新管理页面
 * 
 * @return string HTML 内容
 */
function foxnav_render_update_page()
{
    ob_start();
    
    // 处理手动检查更新
    if (isset($_POST['check_update']) && check_admin_referer('foxnav_check_update')) {
        $update_info = FoxNav_Updater::manual_check();
        if ($update_info) {
            echo '<div class="notice notice-success is-dismissible"><p>✅ 更新检查完成！</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>❌ 无法连接到更新服务器</p></div>';
        }
    }
    
    // 处理创建备份
    if (isset($_POST['create_backup']) && check_admin_referer('foxnav_create_backup')) {
        $backup_result = FoxNav_Backup::create_backup();
        if (is_wp_error($backup_result)) {
            echo '<div class="notice notice-error is-dismissible"><p>❌ ' . esc_html($backup_result->get_error_message()) . '</p></div>';
        } else {
            echo '<div class="notice notice-success is-dismissible"><p>✅ 备份创建成功！</p></div>';
        }
    }
    
    // 处理删除备份
    if (isset($_GET['delete_backup']) && check_admin_referer('foxnav_delete_backup_' . $_GET['delete_backup'])) {
        $deleted = FoxNav_Backup::delete_backup($_GET['delete_backup']);
        if ($deleted) {
            echo '<div class="notice notice-success is-dismissible"><p>✅ 备份已删除</p></div>';
        }
    }
    
    $current_version = FoxNav_Updater::get_current_version();
    $update_info = FoxNav_Updater::get_update_info();
    $has_update = FoxNav_Updater::has_update();
    
    // 输出 CSS
   //  foxnav_update_page_css();
    ?>
    
    <div class="foxnav-update-manager" style="max-width: 1200px;">
        
        <!-- 版本信息卡片 -->
        <div class="card" style="margin-bottom: 20px; padding: 20px;">
            <h2 style="margin-top: 0;">📦 版本信息</h2>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <strong>当前版本：</strong>
                    <span style="font-size: 18px; color: #2271b1;"><?php echo esc_html($current_version); ?></span>
                </div>
                <div>
                    <strong>最新版本：</strong>
                    <?php if ($has_update && $update_info): ?>
                        <span style="font-size: 18px; color: #d63638;"><?php echo esc_html($update_info['version']); ?> 🆕</span>
                    <?php else: ?>
                        <span style="font-size: 18px; color: #00a32a;"><?php echo esc_html($current_version); ?> ✅</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if ($has_update && $update_info): ?>
                <div class="notice notice-warning inline" style="margin: 0; padding: 12px;">
                    <p style="margin: 0;">
                        <strong>🎉 有新版本可用！</strong> 
                        版本 <?php echo esc_html($update_info['version']); ?> 已发布
                        <?php if (!empty($update_info['published_at'])): ?>
                            （<?php echo esc_html(human_time_diff(strtotime($update_info['published_at']))); ?>前）
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="notice notice-success inline" style="margin: 0; padding: 12px;">
                    <p style="margin: 0;">✅ 您正在使用最新版本的 FoxNav 主题</p>
                </div>
            <?php endif; ?>
            
            <div style="margin-top: 20px;">
                <form method="post" style="display: inline;">
                    <?php wp_nonce_field('foxnav_check_update'); ?>
                    <button type="submit" name="check_update" class="button">
                        🔄 检查更新
                    </button>
                </form>
                
                <?php if ($has_update && $update_info): ?>
                    <a href="<?php echo esc_url(admin_url('themes.php')); ?>" class="button button-primary" style="margin-left: 10px;">
                        ⬆️ 前往更新
                    </a>
                    <a href="<?php echo esc_url($update_info['details_url']); ?>" target="_blank" class="button" style="margin-left: 10px;">
                        📖 查看更新日志
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- 更新日志 -->
        <?php if ($has_update && $update_info && !empty($update_info['changelog'])): ?>
        <div class="card" style="margin-bottom: 20px; padding: 20px;">
            <h2 style="margin-top: 0;">📝 更新日志</h2>
            <div class="foxnav-changelog" style="background: #f6f7f7; padding: 15px; border-radius: 4px; max-height: 400px; overflow-y: auto;">
                <?php echo wp_kses_post($update_info['changelog']); ?>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- 备份管理 -->
        <div class="card" style="margin-bottom: 20px; padding: 20px;">
            <h2 style="margin-top: 0;">💾 备份管理</h2>
            
            <p>在更新主题前，建议先创建备份以便在出现问题时恢复。</p>
            
            <form method="post" style="margin-bottom: 20px;">
                <?php wp_nonce_field('foxnav_create_backup'); ?>
                <button type="submit" name="create_backup" class="button button-secondary">
                    ➕ 创建新备份
                </button>
            </form>
            
            <?php
            $backups = FoxNav_Backup::get_backups();
            if (!empty($backups)):
            ?>
                <h3>现有备份</h3>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>版本</th>
                            <th>创建时间</th>
                            <th>文件大小</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($backups as $backup): ?>
                        <tr>
                            <td><strong><?php echo esc_html($backup['version']); ?></strong></td>
                            <td><?php echo esc_html($backup['created']); ?></td>
                            <td><?php echo esc_html(FoxNav_Backup::format_size($backup['size'])); ?></td>
                            <td>
                                <a href="<?php echo esc_url(admin_url('admin-post.php?action=foxnav_download_backup&file=' . urlencode($backup['filename']))); ?>" class="button button-small">
                                    ⬇️ 下载
                                </a>
                                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin.php?page=fox-framework&section=updates&delete_backup=' . urlencode($backup['filename'])), 'foxnav_delete_backup_' . $backup['filename'])); ?>" 
                                   class="button button-small" 
                                   onclick="return confirm('确定要删除此备份吗？');">
                                    🗑️ 删除
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="notice notice-info inline" style="margin: 0;">
                    <p>暂无备份。点击上方按钮创建第一个备份。</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- GitHub 信息 -->
        <div class="card" style="padding: 20px;">
            <h2 style="margin-top: 0;">🔗 更新源信息</h2>
            <p>
                <strong>GitHub 仓库：</strong> 
                <a href="https://github.com/<?php echo FoxNav_Updater::GITHUB_USERNAME; ?>/<?php echo FoxNav_Updater::GITHUB_REPO; ?>" target="_blank">
                    <?php echo FoxNav_Updater::GITHUB_USERNAME; ?>/<?php echo FoxNav_Updater::GITHUB_REPO; ?>
                </a>
            </p>
            <p>
                <strong>更新方式：</strong> 通过 GitHub Releases 自动更新
            </p>
            <p>
                <strong>检查频率：</strong> 每 12 小时自动检查一次
            </p>
            
            <div class="notice notice-info inline" style="margin-top: 15px;">
                <p style="margin: 0;">
                    <strong>💡 提示：</strong>
                    主题更新完全符合 GPL-3.0 开源协议。您可以自由修改、再分发本主题。
                    如需禁用自动更新检查，请在主题设置中关闭相关选项。
                </p>
            </div>
        </div>
        
    </div>
    
    <?php
    return ob_get_clean();
}

/**
 * 输出更新管理页面的 CSS
 */
function foxnav_update_page_css()
{
    ?>
    <style>
    .foxnav-update-manager .card {
        background: #fff;
        border: 1px solid #c3c4c7;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
    }
    .foxnav-update-manager .notice.inline {
        display: block;
    }
    .foxnav-changelog {
        font-size: 14px;
        line-height: 1.6;
    }
    .foxnav-changelog h2 {
        font-size: 16px;
        margin-top: 15px;
        margin-bottom: 10px;
    }
    .foxnav-changelog h3 {
        font-size: 14px;
        margin-top: 10px;
        margin-bottom: 8px;
    }
    .foxnav-changelog ul {
        margin-left: 20px;
    }
    .foxnav-changelog li {
        margin-bottom: 5px;
    }
    </style>
    <?php
}



