<?php
/**
 * 资源加载管理
 *
 * @package FoxNav
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 前端资源加载
 */
function foxnav_enqueue_scripts() {
    // =========================
    // CSS 样式加载
    // =========================
    
    // 1. 第三方CSS库
    wp_enqueue_style(
        'classic-theme-styles',
        FOXNAV_THEME_URI . '/static/css/classic-themes.min.css',
        [],
        '1.0'
    );
    
    wp_enqueue_style(
        'iconfont',
        FOXNAV_THEME_URI . '/static/css/iconfont.css',
        [],
        '1.0'
    );
    
    wp_enqueue_style(
        'font-awesome',
        FOXNAV_THEME_URI . '/static/css/all.min.css',
        [],
        '6.0.0'
    );
    
    wp_enqueue_style(
        'font-awesome-v4-shims',
        FOXNAV_THEME_URI . '/static/css/v4-shims.min.css',
        ['font-awesome'],
        '6.0.0'
    );
    
    wp_enqueue_style(
        'bootstrap',
        FOXNAV_THEME_URI . '/static/css/bootstrap.min.css',
        [],
        '4.4.1'
    );
    
    wp_enqueue_style(
        'swiper',
        FOXNAV_THEME_URI . '/static/css/swiper-bundle.min.css',
        [],
        '8.0.0'
    );
    
    // 2. 主题样式（依赖第三方库）
    wp_enqueue_style(
        'foxnav-main-style',
        FOXNAV_THEME_URI . '/static/css/style.min.css',
        ['bootstrap', 'font-awesome', 'swiper'],
        FOXNAV_VERSION
    );
    
    wp_enqueue_style(
        'foxnav-diy',
        FOXNAV_THEME_URI . '/static/css/diy.css',
        ['foxnav-main-style'],
        FOXNAV_VERSION
    );
    
    // 3. WordPress默认样式（style.css）
    wp_enqueue_style(
        'foxnav-style',
        get_stylesheet_uri(),
        ['foxnav-main-style'],
        FOXNAV_VERSION
    );

    // 4. 首页样式优化
    if (is_front_page() || is_home()) {
        wp_enqueue_style(
            'foxnav-home',
            FOXNAV_THEME_URI . '/assets/css/home.css',
            ['foxnav-style'],
            FOXNAV_VERSION
        );
    }

    // 5. 搜索页样式
    if (is_search()) {
        wp_enqueue_style(
            'foxnav-search',
            FOXNAV_THEME_URI . '/assets/css/search.css',
            ['foxnav-style'],
            FOXNAV_VERSION
        );
    }

    // 6. 单页模板样式
    if (is_singular('site')) {
        wp_enqueue_style(
            'foxnav-single-site',
            FOXNAV_THEME_URI . '/assets/css/single-site.css',
            ['foxnav-style'],
            FOXNAV_VERSION
        );
    }

    // =========================
    // JavaScript 脚本加载
    // =========================
    
    // 注意：WordPress已经自带jQuery，不需要重复加载
    // 如果需要使用jQuery，直接在依赖中声明 ['jquery'] 即可
    
    // 1. Popper.js（Bootstrap依赖）
    wp_enqueue_script(
        'popper',
        FOXNAV_THEME_URI . '/static/js/popper.min.js',
        ['jquery'],
        '2.11.0',
        true
    );
    
    // 2. Bootstrap JS
    wp_enqueue_script(
        'bootstrap',
        FOXNAV_THEME_URI . '/static/js/bootstrap441.min.js',
        ['jquery', 'popper'],
        '4.4.1',
        true
    );
    
    // 3. Layer弹层插件
    wp_enqueue_script(
        'layer',
        FOXNAV_THEME_URI . '/static/js/layer.js',
        ['jquery'],
        '3.5.0',
        true
    );
    
    // 4. 主题核心JS
    wp_enqueue_script(
        'foxnav-all',
        FOXNAV_THEME_URI . '/static/js/all.min.js',
        ['jquery', 'bootstrap', 'layer'],
        FOXNAV_VERSION,
        true
    );
    
    wp_enqueue_script(
        'bootstrapnews',
        FOXNAV_THEME_URI . '/static/js/bootstrapnews.js',
        ['jquery', 'foxnav-all'],
        FOXNAV_VERSION,
        true
    );
    
    wp_enqueue_script(
        'foxnav-diy',
        FOXNAV_THEME_URI . '/static/js/diy.js',
        ['jquery', 'foxnav-all'],
        FOXNAV_VERSION,
        true
    );
    
    // 5. 搜索功能JS
    wp_enqueue_script(
        'foxnav-search',
        FOXNAV_THEME_URI . '/assets/js/search.js',
        ['jquery'],
        FOXNAV_VERSION,
        true
    );
    
    // 6. 权重查询脚本（仅单页）
    if (is_singular('site')) {
        wp_enqueue_script(
            'foxnav-rank-query',
            FOXNAV_THEME_URI . '/assets/js/rank-query.js',
            ['jquery'],
            FOXNAV_VERSION,
            true
        );
    }

    // 7. 主题自定义JS
    wp_enqueue_script(
        'foxnav-main',
        FOXNAV_THEME_URI . '/assets/js/main.js',
        ['jquery'],
        FOXNAV_VERSION,
        true
    );

    // =========================
    // 传递配置数据到前端
    // =========================
    
    // 传递主题配置
    wp_localize_script('foxnav-all', 'foxnavConfig', [
        'webdir' => '/',
        'weburl' => home_url('/'),
        'memurl' => '/member',
        'webName' => get_bloginfo('name'),
        'surl' => home_url('/so_{mid}_{keyword}.html'),
        'webadd' => '/site_add.html',
        'addBtn' => '网址提交',
        'minappadd' => '/member/index.php?u=minapp-add',
        'wechatadd' => '/member/index.php?u=wechat-add',
        'artadd' => '/member/index.php?u=article-add',
        'uid' => get_current_user_id(),
    ]);
    
    // 传递主题数据
    wp_localize_script('foxnav-main', 'foxnavData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('foxnav_nonce'),
        'themeUrl' => FOXNAV_THEME_URI,
        'homeUrl' => home_url('/'),
        'lazyLoadEnabled' => foxnav_is_feature_enabled('lazy_load'),
        'lazyLoadDistance' => foxnav_get_option('lazy_load_distance', 100),
        'lazyLoadFadeSpeed' => foxnav_get_option('lazy_load_fade_speed', 300),
        'strings' => [
            'loading' => __('加载中...', 'foxnav'),
            'error' => __('出错了，请稍后重试', 'foxnav'),
        ]
    ]);
}
add_action('wp_enqueue_scripts', 'foxnav_enqueue_scripts');

/**
 * 后台资源加载
 */
function foxnav_admin_enqueue_scripts($hook) {
    global $post_type;

    // 只在网址编辑页面加载
    if ($post_type !== 'site') {
        return;
    }

    // 媒体上传
    wp_enqueue_media();

    // 后台脚本
    wp_enqueue_script(
        'foxnav-admin',
        FOXNAV_THEME_URI . '/assets/js/admin.js',
        ['jquery', 'jquery-ui-sortable'],
        FOXNAV_VERSION,
        true
    );

    // 后台样式
    wp_enqueue_style(
        'foxnav-admin',
        FOXNAV_THEME_URI . '/assets/css/admin.css',
        [],
        FOXNAV_VERSION
    );

    // 传递数据
    wp_localize_script('foxnav-admin', 'foxnavAdmin', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('foxnav_admin_nonce'),
        'strings' => [
            'selectImage' => __('选择图片', 'foxnav'),
            'useImage' => __('使用此图片', 'foxnav'),
            'fetching' => __('获取中...', 'foxnav'),
            'fetchSuccess' => __('获取成功', 'foxnav'),
            'fetchError' => __('获取失败', 'foxnav'),
        ]
    ]);
}
add_action('admin_enqueue_scripts', 'foxnav_admin_enqueue_scripts');

/**
 * 添加编辑器样式
 */
function foxnav_add_editor_styles() {
    add_editor_style('assets/css/editor-style.css');
}
add_action('admin_init', 'foxnav_add_editor_styles');









