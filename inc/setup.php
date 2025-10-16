<?php
/**
 * 主题初始化设置
 *
 * @package FoxNav
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 主题设置
 */
function foxnav_theme_setup() {
    // 加载文本域
    load_theme_textdomain('foxnav', FOXNAV_THEME_DIR . '/languages');

    // 添加主题支持
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style'
    ]);
    add_theme_support('custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');

    // 注册导航菜单
    register_nav_menus([
        'primary' => __('顶部导航', 'foxnav'),
        'footer'  => __('页脚导航', 'foxnav'),
        'mobile'  => __('移动端菜单', 'foxnav'),
    ]);

    // 设置缩略图尺寸
    add_image_size('foxnav-screenshot', 1200, 800, true);
    add_image_size('foxnav-thumbnail', 400, 300, true);
    add_image_size('foxnav-icon', 120, 120, true);
}
add_action('after_setup_theme', 'foxnav_theme_setup');

/**
 * 注册小工具区域
 */
function foxnav_widgets_init() {
    register_sidebar([
        'name'          => __('侧边栏', 'foxnav'),
        'id'            => 'sidebar-1',
        'description'   => __('侧边栏小工具区域', 'foxnav'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
}
add_action('widgets_init', 'foxnav_widgets_init');

/**
 * 设置内容宽度
 */
if (!isset($content_width)) {
    $content_width = 1200;
}

