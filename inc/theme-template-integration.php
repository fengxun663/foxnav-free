<?php
/**
 * 主题模板集成 - 自动对接主题设置数据到前端
 *
 * @package FoxNav
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 在body标签上添加布局类
 */
add_filter('body_class', function($classes) {
    $layout = foxnav_get_layout_style();
    $classes[] = 'layout-' . $layout;
    
    $header_style = foxnav_get_header_style();
    $classes[] = 'header-' . $header_style;
    
    return $classes;
});

/**
 * 输出动态CSS变量和样式
 */
add_action('wp_head', function() {
    $primary_color = foxnav_get_primary_color();
    $secondary_color = foxnav_get_secondary_color();
    $accent_color = foxnav_get_accent_color();
    
    ?>
    <style id="foxnav-dynamic-styles">
    /* 修复文字选择问题 */
    body * {
        user-select: text !important;
        -webkit-user-select: text !important;
        -moz-user-select: text !important;
        -khtml-user-select: text !important;
        -ms-user-select: text !important;
    }
    
    /* 图片懒加载样式 */
    <?php if (foxnav_is_feature_enabled('lazy_load')): 
        $fade_speed = foxnav_get_option('lazy_load_fade_speed', 300);
    ?>
    /* 懒加载图片初始状态 */
    img.lazy[data-src] {
        opacity: 0;
        transition: opacity <?php echo intval($fade_speed); ?>ms ease-in-out;
        min-height: 40px;
        min-width: 40px;
    }
    
    /* 懒加载图片加载完成 */
    img.lazy.loaded,
    img.lazy.error {
        opacity: 1;
    }
    
    /* 懒加载占位符背景效果 */
    img.lazy[data-src]:not(.loaded):not(.error) {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading-shimmer 1.5s ease-in-out infinite;
    }
    
    @keyframes loading-shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    <?php endif; ?>
    
    /* 主题颜色变量 */
    :root {
        --foxnav-primary: <?php echo esc_attr($primary_color); ?>;
        --foxnav-secondary: <?php echo esc_attr($secondary_color); ?>;
        --foxnav-accent: <?php echo esc_attr($accent_color); ?>;
    }
    
    /* 应用主题色到常用元素 */
    .btn-primary,
    .badge-primary,
    .bg-primary {
        background-color: <?php echo esc_attr($primary_color); ?> !important;
        border-color: <?php echo esc_attr($primary_color); ?> !important;
    }
    
    .text-primary,
    a:hover,
    .link-primary {
        color: <?php echo esc_attr($primary_color); ?> !important;
    }
    
    .btn-secondary {
        background-color: <?php echo esc_attr($secondary_color); ?> !important;
        border-color: <?php echo esc_attr($secondary_color); ?> !important;
    }
    
    .sidebar-item.active > a,
    .nav-link.active {
        color: <?php echo esc_attr($primary_color); ?> !important;
    }
    
    /* 强调色应用 */
    .badge-accent,
    .btn-accent {
        background-color: <?php echo esc_attr($accent_color); ?> !important;
        border-color: <?php echo esc_attr($accent_color); ?> !important;
        color: #fff;
    }
    
    /* 链接悬停效果 */
    a {
        transition: color 0.3s ease;
    }
    
    <?php if (foxnav_is_feature_enabled('smooth_scroll')): ?>
    /* 平滑滚动 */
    html {
        scroll-behavior: smooth;
    }
    <?php endif; ?>
    </style>
    <?php
}, 10);

/**
 * 根据设置控制功能显示
 */
add_action('wp_footer', function() {
    if (foxnav_is_feature_enabled('smooth_scroll')) {
        ?>
        <script>
        // 平滑滚动已通过CSS启用
        </script>
        <?php
    }
}, 10);

/**
 * 修改文档标题
 */
add_filter('pre_get_document_title', function($title) {
    if (is_front_page()) {
        $seo_title = foxnav_get_seo_title();
        if ($seo_title) {
            return $seo_title;
        }
    }
    return $title;
});

/**
 * 添加自定义body类以支持不同的布局
 */
add_action('wp_footer', function() {
    $layout = foxnav_get_layout_style();
    
    if ($layout === 'boxed') {
        ?>
        <style>
        .container-fluid {
            max-width: 1400px;
            margin: 0 auto;
        }
        </style>
        <?php
    } elseif ($layout === 'centered') {
        ?>
        <style>
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
        }
        </style>
        <?php
    }
}, 5);

/**
 * 输出联系信息到footer
 */
function foxnav_output_contact_footer() {
    $contact = foxnav_get_contact_info();
    
    if (empty($contact['email']) && empty($contact['phone']) && empty($contact['address'])) {
        return;
    }
    
    ?>
    <div class="footer-contact-info">
        <?php if (!empty($contact['email'])): ?>
            <div class="contact-item">
                <i class="iconfont icon-email"></i>
                <a href="mailto:<?php echo esc_attr($contact['email']); ?>">
                    <?php echo esc_html($contact['email']); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($contact['phone'])): ?>
            <div class="contact-item">
                <i class="iconfont icon-phone"></i>
                <a href="tel:<?php echo esc_attr($contact['phone']); ?>">
                    <?php echo esc_html($contact['phone']); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($contact['address'])): ?>
            <div class="contact-item">
                <i class="iconfont icon-location"></i>
                <span><?php echo esc_html($contact['address']); ?></span>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * 获取并输出Logo的辅助函数
 */
function foxnav_display_logo_html($height = 30, $class = '') {
    $logo = foxnav_get_logo();
    $logo_light = foxnav_get_logo('light');
    $logo_dark = foxnav_get_logo('dark');
    $site_title = foxnav_get_site_title();
    
    $html = '';
    
    if ($logo_light) {
        $html .= '<img src="' . esc_url($logo_light) . '" class="logo-light ' . esc_attr($class) . '" alt="' . esc_attr($site_title) . '" height="' . esc_attr($height) . '">';
    } elseif ($logo) {
        $html .= '<img src="' . esc_url($logo) . '" class="logo-light ' . esc_attr($class) . '" alt="' . esc_attr($site_title) . '" height="' . esc_attr($height) . '">';
    } else {
        $html .= '<span class="navbar-brand-text">' . esc_html($site_title) . '</span>';
    }
    
    if ($logo_dark) {
        $html .= '<img src="' . esc_url($logo_dark) . '" class="logo-dark d-none ' . esc_attr($class) . '" alt="' . esc_attr($site_title) . '" height="' . esc_attr($height) . '">';
    } elseif ($logo) {
        $html .= '<img src="' . esc_url($logo) . '" class="logo-dark d-none ' . esc_attr($class) . '" alt="' . esc_attr($site_title) . '" height="' . esc_attr($height) . '">';
    }
    
    return $html;
}

/**
 * 输出首页横幅背景图
 */
function foxnav_get_homepage_banner_style() {
    if (!is_front_page()) {
        return '';
    }
    
    $homepage_settings = foxnav_get_homepage_settings();
    $banner = $homepage_settings['banner'];
    
    if (empty($banner)) {
        return '';
    }
    
    return 'background-image: url(' . esc_url($banner) . '); background-size: cover; background-position: center;';
}

/**
 * 根据设置调整每页显示的文章数
 */
add_action('pre_get_posts', function($query) {
    if (!is_admin() && $query->is_main_query()) {
        $posts_per_page = foxnav_get_option('posts_per_page', 10);
        if ($posts_per_page) {
            $query->set('posts_per_page', $posts_per_page);
        }
    }
});

/**
 * 懒加载图片
 */
if (foxnav_is_feature_enabled('lazy_load')) {
    add_filter('the_content', function($content) {
        // 为图片添加懒加载属性
        $content = preg_replace('/<img(.*?)src=/', '<img$1loading="lazy" src=', $content);
        return $content;
    });
    
    add_filter('post_thumbnail_html', function($html) {
        // 为特色图片添加懒加载
        if (strpos($html, 'loading=') === false) {
            $html = str_replace('<img', '<img loading="lazy"', $html);
        }
        return $html;
    });
}

/**
 * 注意：面包屑导航函数 foxnav_breadcrumb() 已在 inc/helpers.php 中定义
 * 这里不再重复定义，直接使用即可
 */

/**
 * 在主题设置中添加调试信息（仅管理员可见）
 */
if (current_user_can('manage_options') && isset($_GET['foxnav_debug'])) {
    add_action('wp_footer', function() {
        ?>
        <div style="position: fixed; bottom: 20px; right: 20px; background: #fff; padding: 20px; border: 2px solid #2271b1; border-radius: 8px; max-width: 400px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); z-index: 99999;">
            <h4 style="margin-top: 0;">主题设置调试信息</h4>
            <table style="font-size: 12px; width: 100%;">
                <tr>
                    <td><strong>主色调:</strong></td>
                    <td><?php echo esc_html(foxnav_get_primary_color()); ?></td>
                </tr>
                <tr>
                    <td><strong>布局:</strong></td>
                    <td><?php echo esc_html(foxnav_get_layout_style()); ?></td>
                </tr>
                <tr>
                    <td><strong>Logo:</strong></td>
                    <td><?php echo foxnav_get_logo() ? '✓ 已设置' : '✗ 未设置'; ?></td>
                </tr>
                <tr>
                    <td><strong>Favicon:</strong></td>
                    <td><?php echo foxnav_get_favicon() ? '✓ 已设置' : '✗ 未设置'; ?></td>
                </tr>
                <tr>
                    <td><strong>搜索:</strong></td>
                    <td><?php echo foxnav_is_feature_enabled('search') ? '✓ 启用' : '✗ 禁用'; ?></td>
                </tr>
                <tr>
                    <td><strong>评论:</strong></td>
                    <td><?php echo foxnav_is_feature_enabled('comments') ? '✓ 启用' : '✗ 禁用'; ?></td>
                </tr>
            </table>
            <p style="margin-bottom: 0; font-size: 11px; color: #666; margin-top: 10px;">
                访问地址加上 ?foxnav_debug 查看调试信息
            </p>
        </div>
        <?php
    }, 999);
}
