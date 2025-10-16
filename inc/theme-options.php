<?php
/**
 * FoxNav 主题设置选项
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
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 注册主题设置选项
 */
add_action('init', function () {
    fox_framework()->add_options([
        
        // ========== 使用说明 ==========
        [
            'id'      => 'usage_instructions',
            'type'    => 'heading',
            'content' => '使用说明',
        ],
        [
            'id'      => 'usage_guide',
            'type'    => 'content',
            'content' => class_exists('Fox_Content_Loader') ? Fox_Content_Loader::get_usage_content() : '',
        ],
        [
            'id'      => 'theme_copyright',
            'type'    => 'content',
            'content' => class_exists('Fox_Content_Loader') ? Fox_Content_Loader::get_copyright_content() : '',
        ],
        
        // ========== 图标设置 ==========
        [
            'id'      => 'icon_settings',
            'type'    => 'heading',
            'content' => '图标设置',
        ],
        [
            'id'      => 'icon_info',
            'type'    => 'submessage',
            'style'   => 'info',
            'content' => '📱 建议上传高质量的图标文件，以确保在各种设备上都能正常显示。',
        ],
        [
            'id'      => 'site_favicon',
            'title'   => '网站 Favicon',
            'type'    => 'image',
            'default' => '',
        ],
        [
            'id'      => 'site_logo',
            'title'   => '网站 Logo',
            'type'    => 'image',
            'default' => '',
        ],
        [
            'id'      => 'site_logo_square',
            'title'   => '方形 Logo',
            'type'    => 'image',
            'default' => '',
        ],
        [
            'id'      => 'site_logo_light',
            'title'   => '浅色 Logo（深色背景使用）',
            'type'    => 'image',
            'default' => '',
        ],
        [
            'id'      => 'site_logo_dark',
            'title'   => '深色 Logo（浅色背景使用）',
            'type'    => 'image',
            'default' => '',
        ],
        
        // ========== 基础设置 ==========
        [
            'id'      => 'basic_settings',
            'type'    => 'heading',
            'content' => '基础设置',
        ],
        [
            'id'      => 'basic_info',
            'type'    => 'content',
            'content' => '<p>配置网站的基本信息，这些信息将显示在网站头部、页脚等位置。</p>',
        ],
        [
            'id'      => 'site_title_custom',
            'title'   => '自定义网站标题',
            'type'    => 'text',
            'default' => '',
        ],
        [
            'id'      => 'site_description',
            'title'   => '网站描述',
            'type'    => 'textarea',
            'default' => '',
        ],
        [
            'id'      => 'site_keywords',
            'title'   => '网站关键词',
            'type'    => 'text',
            'default' => '',
        ],
        [
            'id'      => 'contact_email',
            'title'   => '联系邮箱',
            'type'    => 'text',
            'default' => '',
        ],
        [
            'id'      => 'contact_phone',
            'title'   => '联系电话',
            'type'    => 'text',
            'default' => '',
        ],
        [
            'id'      => 'contact_address',
            'title'   => '联系地址',
            'type'    => 'textarea',
            'default' => '',
        ],
        
        // ========== SEO设置 ==========
        [
            'id'      => 'seo_settings',
            'type'    => 'heading',
            'content' => 'SEO设置',
        ],

        [
            'id'      => 'seo_warning',
            'type'    => 'submessage',
            'style'   => 'warning',
            'content' => '⚠️ <strong>重要：</strong>正确的SEO设置有助于提高网站在搜索引擎中的排名。',
        ],
        [
            'id'      => 'seo_title',
            'title'   => 'SEO标题',
            'type'    => 'text',
            'default' => '',
        ],
        [
            'id'      => 'seo_description',
            'title'   => 'SEO描述',
            'type'    => 'textarea',
            'default' => '',
        ],
        [
            'id'      => 'seo_keywords',
            'title'   => 'SEO关键词',
            'type'    => 'text',
            'default' => '',
        ],
        [
            'id'      => 'og_title',
            'title'   => 'Open Graph 标题',
            'type'    => 'text',
            'default' => '',
        ],
        [
            'id'      => 'og_description',
            'title'   => 'Open Graph 描述',
            'type'    => 'textarea',
            'default' => '',
        ],
        [
            'id'      => 'og_image',
            'title'   => 'Open Graph 图片',
            'type'    => 'image',
            'default' => '',
        ],
        [
            'id'      => 'google_analytics',
            'title'   => 'Google Analytics ID',
            'type'    => 'text',
            'default' => '',
        ],
        [
            'id'      => 'baidu_analytics',
            'title'   => '百度统计 ID',
            'type'    => 'text',
            'default' => '',
        ],
        
        // ========== 页面设置 ==========
        [
            'id'      => 'page_settings',
            'type'    => 'heading',
            'content' => '页面设置',
        ],
        [
            'id'      => 'page_info',
            'type'    => 'content',
            'content' => '<p>配置网站页面的显示选项和功能开关。</p>',
        ],
        
        // 首页设置
        [
            'id'      => 'homepage_settings',
            'type'    => 'heading',
            'content' => '首页设置',
        ],
        [
            'id'      => 'homepage_title',
            'title'   => '首页标题',
            'type'    => 'text',
            'default' => '',
        ],
        [
            'id'      => 'homepage_subtitle',
            'title'   => '首页副标题',
            'type'    => 'text',
            'default' => '',
        ],
        [
            'id'      => 'homepage_description',
            'title'   => '首页描述',
            'type'    => 'textarea',
            'default' => '',
        ],
        [
            'id'      => 'homepage_banner',
            'title'   => '首页横幅图片',
            'type'    => 'image',
            'default' => '',
        ],
        [
            'id'      => 'homepage_banner_link',
            'title'   => '首页横幅链接',
            'type'    => 'text',
            'default' => '',
        ],
        [
            'id'      => 'homepage_banner_target',
            'title'   => '首页横幅链接目标',
            'type'    => 'select',
            'options' => [
                '_self' => '当前窗口',
                '_blank' => '新窗口',
            ],
            'default' => '_self',
        ],

        [
            'id'      => 'bottom_image_one',
            'title'   => '底部图片一',
            'type'    => 'image',
            'default' => '',
        ],
        [
            'id'      => 'bottom_image_one_text',
            'title'   => '底部图片一文字',
            'type'    => 'text',
            'default' => '',
        ],
        [
            'id'      => 'bottom_image_two',
            'title'   => '底部图片二',
            'type'    => 'image',
            'default' => '',
        ],
        [
            'id'      => 'bottom_image_two_text',
            'title'   => '底部图片二文字',
            'type'    => 'text',
            'default' => '',
        ],
        
        // 广告设置
        [
            'id'      => 'ad_settings',
            'type'    => 'heading',
            'content' => '广告设置',
        ],
        [
            'id'      => 'single_page_ad_image',
            'title'   => '内容页广告图片',
            'type'    => 'image',
            'default' => '',
            'desc'    => '在网址详情页右侧显示的广告图片',
        ],
        [
            'id'      => 'single_page_ad_link',
            'title'   => '内容页广告链接',
            'type'    => 'text',
            'default' => '',
            'desc'    => '点击广告图片跳转的链接地址',
        ],
        [
            'id'      => 'single_page_ad_title',
            'title'   => '内容页广告标题',
            'type'    => 'text',
            'default' => '广告',
            'desc'    => '广告图片的alt属性',
        ],
        [
            'id'      => 'bottom_text',
            'title'   => '底部文字',
            'type'    => 'textarea',
            'default' => '',
        ],
        
        // 文章页设置
        [
            'id'      => 'post_settings',
            'type'    => 'heading',
            'content' => '文章页设置',
        ],
        [
            'id'      => 'enable_post_sidebar',
            'title'   => '启用文章页侧边栏',
            'type'    => 'switch',
            'default' => 1,
        ],
        [
            'id'      => 'enable_post_related',
            'title'   => '显示相关文章',
            'type'    => 'switch',
            'default' => 1,
        ],
        [
            'id'      => 'enable_post_share',
            'title'   => '显示分享按钮',
            'type'    => 'switch',
            'default' => 1,
        ],
        [
            'id'      => 'enable_post_author',
            'title'   => '显示作者信息',
            'type'    => 'switch',
            'default' => 1,
        ],
        
        // 功能开关
        [
            'id'      => 'feature_settings',
            'type'    => 'heading',
            'content' => '功能开关',
        ],
        [
            'id'      => 'enable_search',
            'title'   => '启用搜索功能',
            'type'    => 'switch',
            'default' => 1,
        ],
        [
            'id'      => 'enable_comments',
            'title'   => '启用评论功能',
            'type'    => 'switch',
            'default' => 1,
        ],
        [
            'id'      => 'enable_breadcrumb',
            'title'   => '启用面包屑导航',
            'type'    => 'switch',
            'default' => 1,
        ],
        [
            'id'      => 'enable_lazy_load',
            'title'   => '启用图片懒加载',
            'type'    => 'switch',
            'default' => 0,
        ],
        [
            'id'      => 'lazy_load_distance',
            'title'   => '懒加载提前距离',
            'type'    => 'number',
            'default' => 100,
            'desc'    => '图片距离可视区域多少像素时开始加载（默认100px，建议范围：0-500）',
        ],
        [
            'id'      => 'lazy_load_fade_speed',
            'title'   => '图片淡入速度',
            'type'    => 'number',
            'default' => 300,
            'desc'    => '图片加载完成后淡入显示的时间（毫秒，默认300ms，建议范围：100-1000）',
        ],
        [
            'id'      => 'enable_smooth_scroll',
            'title'   => '启用平滑滚动',
            'type'    => 'switch',
            'default' => 1,
        ],
        [
            'id'      => 'link_redirect_mode',
            'title'   => '链接跳转方式',
            'type'    => 'radio',
            'options' => [
                'direct' => '直接跳转（跳转到目标网站）',
                'detail' => '详情页（跳转到文章页面）'
            ],
            'default' => 'direct',
            'description' => '控制网址列表中的链接跳转方式。直接跳转会直接访问目标网站，详情页会先显示文章详情页面。'
        ],
        
        // 外观设置
        [
            'id'      => 'appearance_settings',
            'type'    => 'heading',
            'content' => '外观设置',
        ],
        [
            'id'      => 'primary_color',
            'title'   => '主色调',
            'type'    => 'color',
            'default' => '#2271b1',
        ],
        [
            'id'      => 'secondary_color',
            'title'   => '辅助色',
            'type'    => 'color',
            'default' => '#72aee6',
        ],
        [
            'id'      => 'accent_color',
            'title'   => '强调色',
            'type'    => 'color',
            'default' => '#f56e28',
        ],
        [
            'id'      => 'layout_style',
            'title'   => '布局样式',
            'type'    => 'select',
            'options' => [
                'boxed'     => '盒式布局',
                'full'      => '全宽布局',
                'centered'  => '居中布局',
            ],
            'default' => 'full',
        ],
        [
            'id'      => 'header_style',
            'title'   => '头部样式',
            'type'    => 'select',
            'options' => [
                'default'   => '默认样式',
                'centered'  => '居中样式',
                'minimal'   => '极简样式',
            ],
            'default' => 'default',
        ],
        
        // 高级设置
        [
            'id'      => 'advanced_settings',
            'type'    => 'heading',
            'content' => '高级设置',
        ],
        [
            'id'      => 'advanced_warning',
            'type'    => 'submessage',
            'style'   => 'warning',
            'content' => '⚠️ <strong>警告：</strong>以下设置属于高级选项，不正确的配置可能会影响网站性能。',
        ],
        [
            'id'      => 'posts_per_page',
            'title'   => '每页文章数',
            'type'    => 'number',
            'default' => 10,
        ],
        [
            'id'      => 'image_quality',
            'title'   => '图片质量',
            'type'    => 'slider',
            'min'     => 0,
            'max'     => 100,
            'step'    => 5,
            'default' => 80,
        ],
        [
            'id'      => 'cache_mode',
            'title'   => '缓存模式',
            'type'    => 'radio',
            'options' => [
                'off'       => '关闭',
                'basic'     => '基础缓存',
                'advanced'  => '高级缓存',
            ],
            'default' => 'basic',
        ],
        [
            'id'      => 'custom_css',
            'title'   => '自定义 CSS',
            'type'    => 'code_editor',
            'default' => '',
        ],
        [
            'id'      => 'custom_js',
            'title'   => '自定义 JavaScript',
            'type'    => 'code_editor',
            'default' => '',
        ],
        
        // ========== 更新管理 ==========
        [
            'id'      => 'update_settings',
            'type'    => 'heading',
            'content' => '更新管理',
        ],
        [
            'id'      => 'update_manager',
            'type'    => 'content',
            'content' => class_exists('FoxNav_Updater') ? foxnav_render_update_page() : '<p>更新系统未初始化</p>',
        ],
        [
            'id'      => 'enable_auto_backup',
            'title'   => '启用自动备份',
            'type'    => 'switcher',
            'desc'    => '更新主题前自动备份当前版本（强烈推荐）',
            'default' => true,
        ],
        [
            'id'      => 'update_notification',
            'title'   => '更新通知',
            'type'    => 'switcher',
            'desc'    => '在后台显示更新通知横幅',
            'default' => true,
        ],
        
        // 完成提示
        [
            'id'      => 'completion_message',
            'type'    => 'submessage',
            'style'   => 'success',
            'content' => '✅ <strong>恭喜！</strong>所有设置项配置完成！记得点击"保存更改"按钮保存您的设置。',
        ],
    ]);
});
