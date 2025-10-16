<?php
/**
 * FoxNav 主题辅助函数
 *
 * @package FoxNav
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 获取主题选项值
 *
 * @param string $option_name 选项名称
 * @param mixed  $default     默认值
 * @return mixed
 */
function foxnav_get_option($option_name, $default = false) {
    return Fox_Options::get($option_name, $default);
}

/**
 * 设置主题选项值
 *
 * @param string $option_name 选项名称
 * @param mixed  $value      值
 */
function foxnav_set_option($option_name, $value) {
    Fox_Options::set($option_name, $value);
}

/**
 * 获取网站Logo
 *
 * @param string $type Logo类型 (default, square, light, dark)
 * @return string
 */
function foxnav_get_logo($type = 'default') {
    $logo_field = 'site_logo';
    
    switch ($type) {
        case 'square':
            $logo_field = 'site_logo_square';
            break;
        case 'light':
            $logo_field = 'site_logo_light';
            break;
        case 'dark':
            $logo_field = 'site_logo_dark';
            break;
    }
    
    $logo = foxnav_get_option($logo_field, '');
    
    if (empty($logo) && $type !== 'default') {
        // 如果没有指定类型的Logo，回退到默认Logo
        $logo = foxnav_get_option('site_logo', '');
    }
    
    return $logo;
}

/**
 * 获取网站Favicon
 *
 * @return string
 */
function foxnav_get_favicon() {
    return foxnav_get_option('site_favicon', '');
}

/**
 * 获取网站标题
 *
 * @return string
 */
function foxnav_get_site_title() {
    $custom_title = foxnav_get_option('site_title_custom', '');
    return !empty($custom_title) ? $custom_title : get_bloginfo('name');
}

/**
 * 获取网站描述
 *
 * @return string
 */
function foxnav_get_site_description() {
    $custom_description = foxnav_get_option('site_description', '');
    return !empty($custom_description) ? $custom_description : get_bloginfo('description');
}

/**
 * 获取链接跳转方式
 *
 * @return string
 */
function foxnav_get_link_redirect_mode() {
    return foxnav_get_option('link_redirect_mode', 'direct');
}

/**
 * 获取底部图片一
 *
 * @return string
 */
function foxnav_get_bottom_image_one() {
    return foxnav_get_option('bottom_image_one', '');
}

/**
 * 获取底部图片一文字
 *
 * @return string
 */
function foxnav_get_bottom_image_one_text() {
    return foxnav_get_option('bottom_image_one_text', '商务合作');
}

/**
 * 获取底部图片二
 *
 * @return string
 */
function foxnav_get_bottom_image_two() {
    return foxnav_get_option('bottom_image_two', '');
}

/**
 * 获取底部图片二文字
 *
 * @return string
 */
function foxnav_get_bottom_image_two_text() {
    return foxnav_get_option('bottom_image_two_text', '商务合作');
}

/**
 * 获取底部文字
 *
 * @return string
 */
function foxnav_get_bottom_text() {
    return foxnav_get_option('bottom_text', '');
}

/**
 * 获取SEO标题
 *
 * @return string
 */
function foxnav_get_seo_title() {
    $seo_title = foxnav_get_option('seo_title', '');
    return !empty($seo_title) ? $seo_title : foxnav_get_site_title();
}

/**
 * 获取SEO描述
 *
 * @return string
 */
function foxnav_get_seo_description() {
    $seo_description = foxnav_get_option('seo_description', '');
    return !empty($seo_description) ? $seo_description : foxnav_get_site_description();
}

/**
 * 获取SEO关键词
 *
 * @return string
 */
function foxnav_get_seo_keywords() {
    return foxnav_get_option('seo_keywords', '');
}

/**
 * 获取Open Graph数据
 *
 * @return array
 */
function foxnav_get_og_data() {
    return [
        'title'       => foxnav_get_option('og_title', foxnav_get_seo_title()),
        'description' => foxnav_get_option('og_description', foxnav_get_seo_description()),
        'image'       => foxnav_get_option('og_image', foxnav_get_logo()),
    ];
}

/**
 * 获取联系信息
 *
 * @return array
 */
function foxnav_get_contact_info() {
    return [
        'email'   => foxnav_get_option('contact_email', ''),
        'phone'   => foxnav_get_option('contact_phone', ''),
        'address' => foxnav_get_option('contact_address', ''),
    ];
}

/**
 * 检查功能是否启用
 *
 * @param string $feature 功能名称
 * @return bool
 */
function foxnav_is_feature_enabled($feature) {
    return (bool) foxnav_get_option('enable_' . $feature, true);
}

/**
 * 获取主色调
 *
 * @return string
 */
function foxnav_get_primary_color() {
    return foxnav_get_option('primary_color', '#2271b1');
}

/**
 * 获取辅助色
 *
 * @return string
 */
function foxnav_get_secondary_color() {
    return foxnav_get_option('secondary_color', '#72aee6');
}

/**
 * 获取强调色
 *
 * @return string
 */
function foxnav_get_accent_color() {
    return foxnav_get_option('accent_color', '#f56e28');
}

/**
 * 获取布局样式
 *
 * @return string
 */
function foxnav_get_layout_style() {
    return foxnav_get_option('layout_style', 'full');
}

/**
 * 获取头部样式
 *
 * @return string
 */
function foxnav_get_header_style() {
    return foxnav_get_option('header_style', 'default');
}

/**
 * 获取首页设置
 *
 * @return array
 */
function foxnav_get_homepage_settings() {
    return [
        'title'       => foxnav_get_option('homepage_title', ''),
        'subtitle'    => foxnav_get_option('homepage_subtitle', ''),
        'description' => foxnav_get_option('homepage_description', ''),
        'banner'      => foxnav_get_option('homepage_banner', ''),
    ];
}

/**
 * 输出自定义CSS
 */
function foxnav_output_custom_css() {
    $custom_css = foxnav_get_option('custom_css', '');
    
    if (!empty($custom_css)) {
        echo '<style type="text/css" id="foxnav-custom-css">';
        echo $custom_css;
        echo '</style>';
    }
}

/**
 * 输出自定义JavaScript
 */
function foxnav_output_custom_js() {
    $custom_js = foxnav_get_option('custom_js', '');
    
    if (!empty($custom_js)) {
        echo '<script type="text/javascript" id="foxnav-custom-js">';
        echo $custom_js;
        echo '</script>';
    }
}

/**
 * 输出分析代码
 */
function foxnav_output_analytics() {
    $google_analytics = foxnav_get_option('google_analytics', '');
    $baidu_analytics = foxnav_get_option('baidu_analytics', '');
    
    // Google Analytics
    if (!empty($google_analytics)) {
        echo "<!-- Google Analytics -->\n";
        echo "<script async src=\"https://www.googletagmanager.com/gtag/js?id={$google_analytics}\"></script>\n";
        echo "<script>\n";
        echo "window.dataLayer = window.dataLayer || [];\n";
        echo "function gtag(){dataLayer.push(arguments);}\n";
        echo "gtag('js', new Date());\n";
        echo "gtag('config', '{$google_analytics}');\n";
        echo "</script>\n";
    }
    
    // 百度统计
    if (!empty($baidu_analytics)) {
        echo "<!-- 百度统计 -->\n";
        echo "<script>\n";
        echo "var _hmt = _hmt || [];\n";
        echo "(function() {\n";
        echo "var hm = document.createElement(\"script\");\n";
        echo "hm.src = \"https://hm.baidu.com/hm.js?{$baidu_analytics}\";\n";
        echo "var s = document.getElementsByTagName(\"script\")[0];\n";
        echo "s.parentNode.insertBefore(hm, s);\n";
        echo "})();\n";
        echo "</script>\n";
    }
}

/**
 * 输出SEO meta标签
 */
function foxnav_output_seo_meta() {
    $seo_title = foxnav_get_seo_title();
    $seo_description = foxnav_get_seo_description();
    $seo_keywords = foxnav_get_seo_keywords();
    
    if (!empty($seo_title)) {
        echo '<meta name="title" content="' . esc_attr($seo_title) . '">' . "\n";
    }
    
    if (!empty($seo_description)) {
        echo '<meta name="description" content="' . esc_attr($seo_description) . '">' . "\n";
    }
    
    if (!empty($seo_keywords)) {
        echo '<meta name="keywords" content="' . esc_attr($seo_keywords) . '">' . "\n";
    }
}

/**
 * 输出Open Graph meta标签
 */
function foxnav_output_og_meta() {
    $og_data = foxnav_get_og_data();
    
    if (!empty($og_data['title'])) {
        echo '<meta property="og:title" content="' . esc_attr($og_data['title']) . '">' . "\n";
    }
    
    if (!empty($og_data['description'])) {
        echo '<meta property="og:description" content="' . esc_attr($og_data['description']) . '">' . "\n";
    }
    
    if (!empty($og_data['image'])) {
        echo '<meta property="og:image" content="' . esc_url($og_data['image']) . '">' . "\n";
    }
    
    echo '<meta property="og:type" content="website">' . "\n";
    echo '<meta property="og:url" content="' . esc_url(home_url()) . '">' . "\n";
    echo '<meta property="og:site_name" content="' . esc_attr(foxnav_get_site_title()) . '">' . "\n";
}

/**
 * 懒加载图片辅助函数
 * 
 * @param string $src 图片URL
 * @param string $alt 图片alt属性
 * @param string $class 额外的CSS类
 * @return string 图片HTML标签
 */
function foxnav_lazy_img($src, $alt = '', $class = '') {
    // 检查是否启用懒加载
    $lazy_enabled = foxnav_is_feature_enabled('lazy_load');
    
    if ($lazy_enabled) {
        // 懒加载模式 - 使用data-src和占位符
        $placeholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
        
        $classes = trim('lazy ' . $class);
        return sprintf(
            '<img class="%s" src="%s" data-src="%s" alt="%s" loading="lazy">',
            esc_attr($classes),
            $placeholder,
            esc_url($src),
            esc_attr($alt)
        );
    } else {
        // 不使用懒加载 - 直接加载图片
        return sprintf(
            '<img class="%s" src="%s" alt="%s">',
            esc_attr($class),
            esc_url($src),
            esc_attr($alt)
        );
    }
}
