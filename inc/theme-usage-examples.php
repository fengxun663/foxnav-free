<?php
/**
 * FoxNav 主题设置使用示例
 *
 * @package FoxNav
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 在头部输出分析代码和自定义CSS
 */
add_action('wp_head', function() {
    // 输出分析代码
    foxnav_output_analytics();
    
    // 输出自定义CSS
    foxnav_output_custom_css();
}, 99);

/**
 * 在页脚输出自定义JavaScript
 */
add_action('wp_footer', function() {
    foxnav_output_custom_js();
}, 99);

/**
 * 在header.php中使用Logo的示例
 */
function foxnav_display_logo() {
    $logo = foxnav_get_logo();
    
    if (!empty($logo)) {
        echo '<img src="' . esc_url($logo) . '" alt="' . esc_attr(foxnav_get_site_title()) . '" class="site-logo">';
    } else {
        echo '<h1 class="site-title">' . esc_html(foxnav_get_site_title()) . '</h1>';
    }
}

/**
 * 在header.php中使用网站标题的示例
 */
function foxnav_display_site_title() {
    echo '<h1 class="site-title">' . esc_html(foxnav_get_site_title()) . '</h1>';
}

/**
 * 在header.php中使用网站描述的示例
 */
function foxnav_display_site_description() {
    $description = foxnav_get_site_description();
    
    if (!empty($description)) {
        echo '<p class="site-description">' . esc_html($description) . '</p>';
    }
}

/**
 * 根据设置显示面包屑导航的示例
 */
function foxnav_display_breadcrumb() {
    if (foxnav_is_feature_enabled('breadcrumb')) {
        // 这里可以调用面包屑导航的代码
        echo '<nav class="breadcrumb">面包屑导航</nav>';
    }
}

/**
 * 根据设置显示搜索功能的示例
 */
function foxnav_display_search() {
    if (foxnav_is_feature_enabled('search')) {
        echo '<div class="search-form">';
        get_search_form();
        echo '</div>';
    }
}

/**
 * 根据设置显示相关文章的示例
 */
function foxnav_display_related_posts() {
    if (foxnav_is_feature_enabled('post_related') && is_single()) {
        // 这里可以调用相关文章的代码
        echo '<div class="related-posts">相关文章</div>';
    }
}

/**
 * 根据设置显示分享按钮的示例
 */
function foxnav_display_share_buttons() {
    if (foxnav_is_feature_enabled('post_share') && is_single()) {
        // 这里可以调用分享按钮的代码
        echo '<div class="share-buttons">分享按钮</div>';
    }
}

/**
 * 根据设置显示作者信息的示例
 */
function foxnav_display_author_info() {
    if (foxnav_is_feature_enabled('post_author') && is_single()) {
        // 这里可以调用作者信息的代码
        echo '<div class="author-info">作者信息</div>';
    }
}

/**
 * 根据设置显示评论的示例
 */
function foxnav_display_comments() {
    if (foxnav_is_feature_enabled('comments')) {
        comments_template();
    }
}

/**
 * 在footer.php中显示联系信息的示例
 */
function foxnav_display_contact_info() {
    $contact = foxnav_get_contact_info();
    
    if (!empty($contact['email'])) {
        echo '<p>邮箱：<a href="mailto:' . esc_attr($contact['email']) . '">' . esc_html($contact['email']) . '</a></p>';
    }
    
    if (!empty($contact['phone'])) {
        echo '<p>电话：<a href="tel:' . esc_attr($contact['phone']) . '">' . esc_html($contact['phone']) . '</a></p>';
    }
    
    if (!empty($contact['address'])) {
        echo '<p>地址：' . esc_html($contact['address']) . '</p>';
    }
}

/**
 * 根据布局样式添加CSS类的示例
 */
function foxnav_get_layout_class() {
    $layout = foxnav_get_layout_style();
    return 'layout-' . $layout;
}

/**
 * 根据头部样式添加CSS类的示例
 */
function foxnav_get_header_class() {
    $header_style = foxnav_get_header_style();
    return 'header-' . $header_style;
}

/**
 * 输出主色调CSS变量的示例
 */
function foxnav_output_color_variables() {
    $primary_color = foxnav_get_primary_color();
    $secondary_color = foxnav_get_secondary_color();
    $accent_color = foxnav_get_accent_color();
    
    echo '<style>';
    echo ':root {';
    echo '--primary-color: ' . esc_attr($primary_color) . ';';
    echo '--secondary-color: ' . esc_attr($secondary_color) . ';';
    echo '--accent-color: ' . esc_attr($accent_color) . ';';
    echo '}';
    echo '</style>';
}

/**
 * 根据首页设置显示首页内容的示例
 */
function foxnav_display_homepage_content() {
    if (is_home() || is_front_page()) {
        $homepage = foxnav_get_homepage_settings();
        
        if (!empty($homepage['banner'])) {
            echo '<div class="homepage-banner">';
            echo '<img src="' . esc_url($homepage['banner']) . '" alt="首页横幅">';
            echo '</div>';
        }
        
        if (!empty($homepage['title'])) {
            echo '<h1 class="homepage-title">' . esc_html($homepage['title']) . '</h1>';
        }
        
        if (!empty($homepage['subtitle'])) {
            echo '<h2 class="homepage-subtitle">' . esc_html($homepage['subtitle']) . '</h2>';
        }
        
        if (!empty($homepage['description'])) {
            echo '<div class="homepage-description">' . wp_kses_post($homepage['description']) . '</div>';
        }
    }
}

/**
 * 根据设置显示侧边栏的示例
 */
function foxnav_display_sidebar() {
    if (foxnav_is_feature_enabled('post_sidebar') || is_home() || is_archive()) {
        get_sidebar();
    }
}

/**
 * 根据图片质量设置优化图片的示例
 */
function foxnav_optimize_image($image_url, $width = null, $height = null) {
    $quality = foxnav_get_option('image_quality', 80);
    
    // 这里可以添加图片优化逻辑
    // 例如使用WordPress的图片处理功能
    
    return $image_url;
}

/**
 * 根据缓存设置处理缓存的示例
 */
function foxnav_handle_cache() {
    $cache_mode = foxnav_get_option('cache_mode', 'basic');
    
    switch ($cache_mode) {
        case 'off':
            // 不启用缓存
            break;
        case 'basic':
            // 基础缓存
            break;
        case 'advanced':
            // 高级缓存
            break;
    }
}

/**
 * 根据每页文章数设置调整查询的示例
 */
function foxnav_adjust_posts_per_page($query) {
    if (!is_admin() && $query->is_main_query()) {
        $posts_per_page = foxnav_get_option('posts_per_page', 10);
        $query->set('posts_per_page', $posts_per_page);
    }
}
add_action('pre_get_posts', 'foxnav_adjust_posts_per_page');

/**
 * 根据懒加载设置添加属性的示例
 */
function foxnav_add_lazy_loading($content) {
    if (foxnav_is_feature_enabled('lazy_load')) {
        // 为图片添加懒加载属性
        $content = preg_replace('/<img(.*?)src=/', '<img$1loading="lazy" src=', $content);
    }
    
    return $content;
}
add_filter('the_content', 'foxnav_add_lazy_loading');

/**
 * 根据平滑滚动设置添加JavaScript的示例
 */
function foxnav_add_smooth_scroll() {
    if (foxnav_is_feature_enabled('smooth_scroll')) {
        echo '<script>';
        echo 'document.documentElement.style.scrollBehavior = "smooth";';
        echo '</script>';
    }
}
add_action('wp_head', 'foxnav_add_smooth_scroll');
