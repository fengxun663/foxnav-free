<?php
/**
 * FoxNav Theme Backup Manager
 * 
 * 主题备份管理器 - 在更新前备份主题文件
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
 * 主题备份管理类
 */
class FoxNav_Backup
{
    /**
     * 备份目录
     */
    private static $backup_dir;
    
    /**
     * 最大备份数量
     */
    const MAX_BACKUPS = 3;
    
    /**
     * 初始化
     */
    public static function init()
    {
        // 设置备份目录
        $upload_dir = wp_upload_dir();
        self::$backup_dir = $upload_dir['basedir'] . '/foxnav-backups';
        
        // 确保备份目录存在
        if (!file_exists(self::$backup_dir)) {
            wp_mkdir_p(self::$backup_dir);
            
            // 添加 .htaccess 保护
            self::protect_backup_dir();
        }
    }
    
    /**
     * 保护备份目录
     */
    private static function protect_backup_dir()
    {
        $htaccess_file = self::$backup_dir . '/.htaccess';
        $htaccess_content = "deny from all\n";
        
        file_put_contents($htaccess_file, $htaccess_content);
        
        // 添加 index.php
        $index_file = self::$backup_dir . '/index.php';
        file_put_contents($index_file, '<?php // Silence is golden');
    }
    
    /**
     * 创建主题备份
     * 
     * @return array|WP_Error 备份信息或错误
     */
    public static function create_backup()
    {
        self::init();
        
        // 检查是否启用备份
        if (!foxnav_get_option('enable_auto_backup', true)) {
            return new WP_Error('backup_disabled', __('自动备份已禁用', 'foxnav'));
        }
        
        // 生成备份文件名
        $theme_version = FoxNav_Updater::get_current_version();
        $timestamp = current_time('Y-m-d_H-i-s');
        $backup_filename = sprintf('foxnav-v%s-%s.zip', $theme_version, $timestamp);
        $backup_filepath = self::$backup_dir . '/' . $backup_filename;
        
        // 主题目录
        $theme_dir = get_template_directory();
        
        // 创建 ZIP 文件
        if (!class_exists('ZipArchive')) {
            return new WP_Error('no_zip', __('服务器不支持 ZipArchive', 'foxnav'));
        }
        
        $zip = new ZipArchive();
        
        if ($zip->open($backup_filepath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return new WP_Error('zip_failed', __('无法创建 ZIP 文件', 'foxnav'));
        }
        
        // 添加主题文件到 ZIP
        self::add_directory_to_zip($zip, $theme_dir, 'foxnav');
        
        $zip->close();
        
        // 清理旧备份
        self::cleanup_old_backups();
        
        // 保存备份信息
        $backup_info = [
            'filename'  => $backup_filename,
            'filepath'  => $backup_filepath,
            'version'   => $theme_version,
            'size'      => filesize($backup_filepath),
            'created'   => current_time('mysql'),
            'timestamp' => time(),
        ];
        
        self::save_backup_info($backup_info);
        
        return $backup_info;
    }
    
    /**
     * 添加目录到 ZIP
     * 
     * @param ZipArchive $zip ZIP 对象
     * @param string $source_dir 源目录
     * @param string $zip_path ZIP 中的路径
     */
    private static function add_directory_to_zip($zip, $source_dir, $zip_path = '')
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source_dir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        
        foreach ($files as $file) {
            if ($file->isDir()) {
                continue;
            }
            
            $file_path = $file->getRealPath();
            $relative_path = substr($file_path, strlen($source_dir) + 1);
            
            // 跳过某些目录和文件
            if (self::should_skip_file($relative_path)) {
                continue;
            }
            
            $zip->addFile($file_path, $zip_path . '/' . $relative_path);
        }
    }
    
    /**
     * 是否跳过文件
     * 
     * @param string $relative_path 相对路径
     * @return bool 是否跳过
     */
    private static function should_skip_file($relative_path)
    {
        $skip_patterns = [
            '.git',
            '.svn',
            'node_modules',
            '.DS_Store',
            'Thumbs.db',
            '.idea',
            '.vscode',
        ];
        
        foreach ($skip_patterns as $pattern) {
            if (strpos($relative_path, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * 清理旧备份
     */
    private static function cleanup_old_backups()
    {
        $backups = self::get_backups();
        
        if (count($backups) <= self::MAX_BACKUPS) {
            return;
        }
        
        // 按时间排序
        usort($backups, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
        });
        
        // 删除多余的备份
        $backups_to_delete = array_slice($backups, self::MAX_BACKUPS);
        
        foreach ($backups_to_delete as $backup) {
            if (file_exists($backup['filepath'])) {
                unlink($backup['filepath']);
            }
        }
        
        // 更新备份列表
        $remaining_backups = array_slice($backups, 0, self::MAX_BACKUPS);
        update_option('foxnav_backups', $remaining_backups);
    }
    
    /**
     * 保存备份信息
     * 
     * @param array $backup_info 备份信息
     */
    private static function save_backup_info($backup_info)
    {
        $backups = get_option('foxnav_backups', []);
        array_unshift($backups, $backup_info);
        update_option('foxnav_backups', $backups);
    }
    
    /**
     * 获取所有备份
     * 
     * @return array 备份列表
     */
    public static function get_backups()
    {
        return get_option('foxnav_backups', []);
    }
    
    /**
     * 删除备份
     * 
     * @param string $filename 文件名
     * @return bool 是否成功
     */
    public static function delete_backup($filename)
    {
        self::init();
        
        $filepath = self::$backup_dir . '/' . $filename;
        
        if (!file_exists($filepath)) {
            return false;
        }
        
        $deleted = unlink($filepath);
        
        if ($deleted) {
            // 从列表中移除
            $backups = self::get_backups();
            $backups = array_filter($backups, function($backup) use ($filename) {
                return $backup['filename'] !== $filename;
            });
            update_option('foxnav_backups', array_values($backups));
        }
        
        return $deleted;
    }
    
    /**
     * 下载备份文件
     * 
     * @param string $filename 文件名
     */
    public static function download_backup($filename)
    {
        self::init();
        
        $filepath = self::$backup_dir . '/' . $filename;
        
        if (!file_exists($filepath)) {
            wp_die(__('备份文件不存在', 'foxnav'));
        }
        
        // 安全检查
        if (!current_user_can('manage_options')) {
            wp_die(__('权限不足', 'foxnav'));
        }
        
        // 发送文件
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
    
    /**
     * 格式化文件大小
     * 
     * @param int $bytes 字节数
     * @return string 格式化后的大小
     */
    public static function format_size($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}






