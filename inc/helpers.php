<?php
/**
 * 辅助函数
 *
 * @package FoxNav
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 获取网址完整信息
 *
 * @param int $post_id 文章ID
 * @return array 网址信息数组
 */
function foxnav_get_site_data($post_id) {
    $data = [
        'id' => $post_id,
        'title' => get_the_title($post_id),
        'url' => get_post_meta($post_id, '_site_url', true),
        'name' => get_post_meta($post_id, '_site_name', true),
        'description' => get_post_meta($post_id, '_site_description', true) ?: get_the_excerpt($post_id),
        'favicon' => get_post_meta($post_id, '_site_favicon', true),
        'logo' => get_post_meta($post_id, '_site_logo', true),
        'screenshot' => get_post_meta($post_id, '_site_screenshot', true) ?: get_the_post_thumbnail_url($post_id, 'full'),
        'official' => get_post_meta($post_id, '_site_official', true) === '1',
        'verified' => get_post_meta($post_id, '_site_verified', true) === '1',
        'nofollow' => get_post_meta($post_id, '_site_nofollow', true) === '1',
        'sponsored' => get_post_meta($post_id, '_site_sponsored', true) === '1',
        'categories' => wp_get_post_terms($post_id, 'site_category'),
        'tags' => wp_get_post_terms($post_id, 'site_tag'),
        'featured_tags' => wp_get_post_terms($post_id, 'featured_tag'),
        'clicks' => (int) get_post_meta($post_id, '_site_clicks', true),
        'favorites' => (int) get_post_meta($post_id, '_site_favorites', true),
    ];

    return apply_filters('foxnav_site_data', $data, $post_id);
}

/**
 * 获取网址链接属性
 *
 * @param int $post_id 文章ID
 * @return string 链接属性字符串
 */
function foxnav_get_link_attributes($post_id) {
    $attributes = ['target="_blank"', 'rel="noopener"'];

    if (get_post_meta($post_id, '_site_nofollow', true) === '1') {
        $attributes[] = 'rel="nofollow"';
    }

    if (get_post_meta($post_id, '_site_sponsored', true) === '1') {
        $attributes[] = 'rel="sponsored"';
    }

    return implode(' ', $attributes);
}

/**
 * 增加网址点击量
 *
 * @param int $post_id 文章ID
 */
function foxnav_increment_click($post_id) {
    $clicks = (int) get_post_meta($post_id, '_site_clicks', true);
    update_post_meta($post_id, '_site_clicks', $clicks + 1);
}

/**
 * 获取站点域名
 *
 * @param int $post_id 文章ID
 * @return string 域名
 */
function foxnav_get_site_domain($post_id) {
    $url = get_post_meta($post_id, '_site_url', true);
    if (!$url) {
        return '';
    }
    
    $parsed = parse_url($url);
    return $parsed['host'] ?? '';
}

/**
 * 输出面包屑导航
 */
function foxnav_breadcrumb() {
    if (is_front_page()) {
        return;
    }

    $breadcrumb = '<nav class="foxnav-breadcrumb" aria-label="' . esc_attr__('面包屑导航', 'foxnav') . '">';
    $breadcrumb .= '<ol class="breadcrumb-list">';
    $breadcrumb .= '<li><a href="' . esc_url(home_url('/')) . '">' . __('首页', 'foxnav') . '</a></li>';

    if (is_singular('site')) {
        $categories = wp_get_post_terms(get_the_ID(), 'site_category');
        if (!empty($categories)) {
            $category = $categories[0];
            $breadcrumb .= '<li><a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a></li>';
        }
        $breadcrumb .= '<li class="active">' . get_the_title() . '</li>';
    } elseif (is_tax('site_category')) {
        $term = get_queried_object();
        if ($term->parent) {
            $parent = get_term($term->parent, 'site_category');
            $breadcrumb .= '<li><a href="' . esc_url(get_term_link($parent)) . '">' . esc_html($parent->name) . '</a></li>';
        }
        $breadcrumb .= '<li class="active">' . esc_html($term->name) . '</li>';
    } elseif (is_post_type_archive('site')) {
        $breadcrumb .= '<li class="active">' . __('所有网址', 'foxnav') . '</li>';
    }

    $breadcrumb .= '</ol>';
    $breadcrumb .= '</nav>';

    echo $breadcrumb;
}

/**
 * 获取热门网址
 *
 * @param int $limit 数量限制
 * @return WP_Query
 */
function foxnav_get_popular_sites($limit = 10) {
    // 先尝试按点击量查询
    $args = [
        'post_type' => 'site',
        'posts_per_page' => $limit,
        'meta_key' => '_site_clicks',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'post_status' => 'publish',
    ];

    $query = new WP_Query($args);
    
    // 如果没有结果，尝试按发布日期查询（不依赖meta_key）
    if (!$query->have_posts()) {
        $args = [
            'post_type' => 'site',
            'posts_per_page' => $limit,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_status' => 'publish',
        ];
        $query = new WP_Query($args);
    }

    return $query;
}

/**
 * 获取最新网址
 *
 * @param int $limit 数量限制
 * @return WP_Query
 */
function foxnav_get_recent_sites($limit = 10) {
    $args = [
        'post_type' => 'site',
        'posts_per_page' => $limit,
        'orderby' => 'date',
        'order' => 'DESC',
        'post_status' => 'publish',
    ];

    return new WP_Query($args);
}

/**
 * 检测URL是否有效
 *
 * @param string $url 网址
 * @return bool
 */
function foxnav_validate_url($url) {
    if (empty($url)) {
        return false;
    }

    $response = wp_remote_head($url, [
        'timeout' => 10,
        'sslverify' => false,
    ]);

    if (is_wp_error($response)) {
        return false;
    }

    $code = wp_remote_retrieve_response_code($response);
    return $code >= 200 && $code < 400;
}

/**
 * 自动获取网站Favicon
 *
 * @param string $url 网址
 * @return string|false Favicon URL或false
 */
function foxnav_fetch_favicon($url) {
    if (empty($url)) {
        return false;
    }

    $parsed = parse_url($url);
    $domain = $parsed['scheme'] . '://' . $parsed['host'];

    // 尝试常见的favicon路径
    $favicon_urls = [
        $domain . '/favicon.ico',
        $domain . '/favicon.png',
        'https://www.google.com/s2/favicons?domain=' . $parsed['host'],
        'https://api.faviconkit.com/' . $parsed['host'] . '/64',
    ];

    foreach ($favicon_urls as $favicon_url) {
        $response = wp_remote_head($favicon_url, ['timeout' => 5]);
        if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
            return $favicon_url;
        }
    }

    return false;
}

/**
 * 获取相关网站
 *
 * @param int $post_id 当前文章ID
 * @param int $limit 数量限制
 * @return WP_Query
 */
function foxnav_get_related_sites($post_id, $limit = 6) {
    // 获取当前文章的分类
    $categories = wp_get_post_terms($post_id, 'site_category');
    $category_ids = wp_list_pluck($categories, 'term_id');

    $args = [
        'post_type' => 'site',
        'posts_per_page' => $limit,
        'post__not_in' => [$post_id],
        'post_status' => 'publish',
        'orderby' => 'rand',
    ];

    // 如果有分类，按分类筛选
    if (!empty($category_ids)) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'site_category',
                'field' => 'term_id',
                'terms' => $category_ids,
            ],
        ];
    }

    return new WP_Query($args);
}






