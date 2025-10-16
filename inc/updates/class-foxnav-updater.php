<?php
/**
 * FoxNav Theme Updater
 * 
 * 主题更新检查器 - 从 GitHub Releases 检查和获取更新
 * 
 * @package FoxNav
 * @subpackage Updates
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
 * 主题更新检查器类
 */
class FoxNav_Updater
{
    /**
     * GitHub 用户名
     */
    const GITHUB_USERNAME = 'fengxun663'; // TODO: 修改为您的 GitHub 用户名
    
    /**
     * GitHub 仓库名
     */
    const GITHUB_REPO = 'foxnav-free'; // TODO: 修改为您的仓库名
    
    /**
     * 更新检查间隔（秒）
     * 默认 12 小时
     */
    const CHECK_INTERVAL = 43200; // 12 * 60 * 60
    
    /**
     * 主题 Slug
     */
    private $theme_slug;
    
    /**
     * 主题数据
     */
    private $theme_data;
    
    /**
     * 初始化
     */
    public function __construct()
    {
        $this->theme_slug = 'foxnav';
        $this->theme_data = wp_get_theme($this->theme_slug);
        
        // 注册 WordPress 钩子
        add_filter('pre_set_site_transient_update_themes', [$this, 'check_for_update']);
        add_filter('themes_api', [$this, 'theme_info'], 10, 3);
        
        // 添加主题行动链接
        add_filter('theme_row_meta', [$this, 'theme_row_meta'], 10, 2);
    }
    
    /**
     * 检查更新
     * 
     * @param object $transient 主题更新 transient
     * @return object 修改后的 transient
     */
    public function check_for_update($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }
        
        // 检查缓存
        $last_check = get_transient('foxnav_update_last_check');
        if ($last_check && (time() - $last_check) < self::CHECK_INTERVAL) {
            return $transient;
        }
        
        // 获取远程版本信息
        $remote_version = $this->get_remote_version();
        
        if (!$remote_version) {
            return $transient;
        }
        
        // 保存检查时间
        set_transient('foxnav_update_last_check', time(), self::CHECK_INTERVAL);
        
        // 对比版本号
        $current_version = $this->theme_data->get('Version');
        
        if (version_compare($current_version, $remote_version['version'], '<')) {
            // 有新版本可用
            $transient->response[$this->theme_slug] = [
                'theme'       => $this->theme_slug,
                'new_version' => $remote_version['version'],
                'url'         => $remote_version['details_url'],
                'package'     => $remote_version['download_url'],
            ];
            
            // 保存更新信息到选项
            update_option('foxnav_latest_version', $remote_version);
        }
        
        return $transient;
    }
    
    /**
     * 从 GitHub API 获取最新版本信息
     * 
     * @return array|false 版本信息或 false
     */
    private function get_remote_version()
    {
        // 检查缓存
        $cached = get_transient('foxnav_remote_version');
        if ($cached !== false) {
            return $cached;
        }
        
        // GitHub API URL
        $api_url = sprintf(
            'https://api.github.com/repos/%s/%s/releases/latest',
            self::GITHUB_USERNAME,
            self::GITHUB_REPO
        );
        
        // 发送请求
        $response = wp_remote_get($api_url, [
            'timeout' => 10,
            'headers' => [
                'Accept' => 'application/vnd.github.v3+json',
            ],
        ]);
        
        // 检查错误
        if (is_wp_error($response)) {
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (empty($data) || !isset($data['tag_name'])) {
            return false;
        }
        
        // 解析版本信息
        $version_info = [
            'version'      => ltrim($data['tag_name'], 'v'), // 移除 'v' 前缀
            'download_url' => $this->get_download_url($data),
            'details_url'  => $data['html_url'],
            'changelog'    => isset($data['body']) ? $data['body'] : '',
            'published_at' => isset($data['published_at']) ? $data['published_at'] : '',
        ];
        
        // 缓存 6 小时
        set_transient('foxnav_remote_version', $version_info, 6 * HOUR_IN_SECONDS);
        
        return $version_info;
    }
    
    /**
     * 从 Release 数据中获取下载 URL
     * 
     * @param array $release_data Release 数据
     * @return string 下载 URL
     */
    private function get_download_url($release_data)
    {
        // 优先使用 Assets 中的 ZIP 文件
        if (!empty($release_data['assets'])) {
            foreach ($release_data['assets'] as $asset) {
                if (isset($asset['name']) && strpos($asset['name'], '.zip') !== false) {
                    return $asset['browser_download_url'];
                }
            }
        }
        
        // 回退到源码 ZIP
        return isset($release_data['zipball_url']) ? $release_data['zipball_url'] : '';
    }
    
    /**
     * 提供主题详情信息
     * 
     * @param false|object|array $result 结果
     * @param string $action API 动作
     * @param object $args 参数
     * @return false|object 主题信息
     */
    public function theme_info($result, $action, $args)
    {
        if ($action !== 'theme_information') {
            return $result;
        }
        
        if ($args->slug !== $this->theme_slug) {
            return $result;
        }
        
        // 获取远程版本信息
        $remote_version = $this->get_remote_version();
        
        if (!$remote_version) {
            return $result;
        }
        
        // 构建主题信息对象
        $info = new stdClass();
        $info->name = $this->theme_data->get('Name');
        $info->slug = $this->theme_slug;
        $info->version = $remote_version['version'];
        $info->author = $this->theme_data->get('Author');
        $info->download_link = $remote_version['download_url'];
        $info->sections = [
            'description' => $this->theme_data->get('Description'),
            'changelog'   => $this->parse_changelog($remote_version['changelog']),
        ];
        
        return $info;
    }
    
    /**
     * 解析 Changelog（Markdown 转 HTML）
     * 
     * @param string $markdown Markdown 内容
     * @return string HTML 内容
     */
    private function parse_changelog($markdown)
    {
        if (empty($markdown)) {
            return '<p>暂无更新日志</p>';
        }
        
        // 简单的 Markdown 转换
        $html = $markdown;
        
        // 标题
        $html = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $html);
        $html = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $html);
        
        // 列表
        $html = preg_replace('/^- (.+)$/m', '<li>$1</li>', $html);
        $html = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $html);
        
        // 换行
        $html = nl2br($html);
        
        return $html;
    }
    
    /**
     * 添加主题行动链接
     * 
     * @param array $links 链接数组
     * @param string $theme_slug 主题 Slug
     * @return array 修改后的链接
     */
    public function theme_row_meta($links, $theme_slug)
    {
        if ($theme_slug !== $this->theme_slug . '/style.css') {
            return $links;
        }
        
        // 添加查看更新日志链接
        $links[] = sprintf(
            '<a href="%s" target="_blank">%s</a>',
            admin_url('admin.php?page=fox-framework&section=updates'),
            __('查看更新', 'foxnav')
        );
        
        return $links;
    }
    
    /**
     * 手动检查更新
     * 
     * @return array|false 更新信息或 false
     */
    public static function manual_check()
    {
        // 清除缓存
        delete_transient('foxnav_update_last_check');
        delete_transient('foxnav_remote_version');
        
        // 强制检查
        $updater = new self();
        return $updater->get_remote_version();
    }
    
    /**
     * 获取当前版本
     * 
     * @return string 版本号
     */
    public static function get_current_version()
    {
        $theme = wp_get_theme('foxnav');
        return $theme->get('Version');
    }
    
    /**
     * 获取更新信息
     * 
     * @return array|false 更新信息
     */
    public static function get_update_info()
    {
        return get_option('foxnav_latest_version', false);
    }
    
    /**
     * 检查是否有更新可用
     * 
     * @return bool 是否有更新
     */
    public static function has_update()
    {
        $current = self::get_current_version();
        $latest = get_option('foxnav_latest_version', false);
        
        if (!$latest || !isset($latest['version'])) {
            return false;
        }
        
        return version_compare($current, $latest['version'], '<');
    }
}






