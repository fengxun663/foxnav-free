<?php
/**
 * AJAX 处理函数
 *
 * @package FoxNav
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 自动获取网站Favicon
 */
function foxnav_ajax_fetch_favicon() {
    check_ajax_referer('foxnav_admin_nonce', 'nonce');

    if (!current_user_can('edit_posts')) {
        wp_send_json_error(['message' => '权限不足']);
    }

    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';

    if (empty($url)) {
        wp_send_json_error(['message' => '网址不能为空']);
    }

    $favicon = foxnav_fetch_favicon($url);

    if ($favicon) {
        wp_send_json_success(['favicon' => $favicon]);
    } else {
        wp_send_json_error(['message' => '无法获取 Favicon，请手动上传']);
    }
}
add_action('wp_ajax_foxnav_fetch_favicon', 'foxnav_ajax_fetch_favicon');

/**
 * 自动网站截图
 */
function foxnav_ajax_auto_screenshot() {
    check_ajax_referer('foxnav_admin_nonce', 'nonce');

    if (!current_user_can('edit_posts')) {
        wp_send_json_error(['message' => '权限不足']);
    }

    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';

    if (empty($url)) {
        wp_send_json_error(['message' => '网址不能为空']);
    }

    // 使用第三方截图服务
    $screenshot_url = foxnav_generate_screenshot($url);

    if ($screenshot_url) {
        wp_send_json_success(['screenshot' => $screenshot_url]);
    } else {
        wp_send_json_error(['message' => '截图失败，请手动上传']);
    }
}
add_action('wp_ajax_foxnav_auto_screenshot', 'foxnav_ajax_auto_screenshot');

/**
 * 生成网站截图
 *
 * @param string $url 网址
 * @return string|false 截图URL
 */
function foxnav_generate_screenshot($url) {
    // 使用免费的截图服务API
    // 选项1: Microlink (推荐)
    $screenshot_url = 'https://api.microlink.io/?' . http_build_query([
        'url' => $url,
        'screenshot' => 'true',
        'meta' => 'false',
        'embed' => 'screenshot.url',
        'viewport.width' => 1920,
        'viewport.height' => 1080,
    ]);

    // 选项2: 如果需要使用其他服务，可以取消下面的注释
    // $screenshot_url = 'https://image.thum.io/get/width/1200/crop/800/' . urlencode($url);
    // $screenshot_url = 'https://api.screenshotmachine.com/?key=YOUR_KEY&url=' . urlencode($url) . '&dimension=1200x800';

    return $screenshot_url;
}

/**
 * 网址点击统计
 */
function foxnav_ajax_increment_click() {
    check_ajax_referer('foxnav_nonce', 'nonce');

    $site_id = isset($_POST['site_id']) ? intval($_POST['site_id']) : 0;

    if (!$site_id || get_post_type($site_id) !== 'site') {
        wp_send_json_error();
    }

    foxnav_increment_click($site_id);
    wp_send_json_success();
}
add_action('wp_ajax_foxnav_increment_click', 'foxnav_ajax_increment_click');
add_action('wp_ajax_nopriv_foxnav_increment_click', 'foxnav_ajax_increment_click');

/**
 * 搜索网址
 */
function foxnav_ajax_search_sites() {
    check_ajax_referer('foxnav_nonce', 'nonce');

    $keyword = isset($_POST['keyword']) ? sanitize_text_field($_POST['keyword']) : '';

    if (empty($keyword)) {
        wp_send_json_error();
    }

    $args = [
        'post_type' => 'site',
        'posts_per_page' => 10,
        's' => $keyword,
        'post_status' => 'publish',
    ];

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();
        
        echo '<ul class="foxnav-search-results-list">';
        while ($query->have_posts()) {
            $query->the_post();
            $site_data = foxnav_get_site_data(get_the_ID());
            ?>
            <li class="search-result-item">
                <a href="<?php echo esc_url(get_permalink()); ?>" class="search-result-link">
                    <?php if ($site_data['favicon']): ?>
                        <img src="<?php echo esc_url($site_data['favicon']); ?>" alt="" class="result-favicon">
                    <?php endif; ?>
                    <div class="result-info">
                        <h4><?php the_title(); ?></h4>
                        <?php if ($site_data['description']): ?>
                            <p><?php echo esc_html(wp_trim_words($site_data['description'], 15)); ?></p>
                        <?php endif; ?>
                    </div>
                </a>
            </li>
            <?php
        }
        echo '</ul>';
        
        wp_reset_postdata();
        
        $html = ob_get_clean();
        wp_send_json_success(['html' => $html]);
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_foxnav_search_sites', 'foxnav_ajax_search_sites');
add_action('wp_ajax_nopriv_foxnav_search_sites', 'foxnav_ajax_search_sites');

/**
 * 切换收藏状态
 */
function foxnav_ajax_toggle_favorite() {
    check_ajax_referer('foxnav_nonce', 'nonce');

    $site_id = isset($_POST['site_id']) ? intval($_POST['site_id']) : 0;

    if (!$site_id || get_post_type($site_id) !== 'site') {
        wp_send_json_error();
    }

    // 获取当前用户收藏列表（存储在cookie或用户meta中）
    $user_id = get_current_user_id();
    
    if ($user_id) {
        $favorites = get_user_meta($user_id, 'foxnav_favorites', true) ?: [];
        
        if (in_array($site_id, $favorites)) {
            $favorites = array_diff($favorites, [$site_id]);
            $favorited = false;
        } else {
            $favorites[] = $site_id;
            $favorited = true;
        }
        
        update_user_meta($user_id, 'foxnav_favorites', $favorites);
    } else {
        // 未登录用户使用 cookie
        $favorites = isset($_COOKIE['foxnav_favorites']) ? json_decode(stripslashes($_COOKIE['foxnav_favorites']), true) : [];
        
        if (in_array($site_id, $favorites)) {
            $favorites = array_diff($favorites, [$site_id]);
            $favorited = false;
        } else {
            $favorites[] = $site_id;
            $favorited = true;
        }
        
        setcookie('foxnav_favorites', json_encode($favorites), time() + (86400 * 365), '/');
    }

    // 更新网址的收藏计数
    $count = (int) get_post_meta($site_id, '_site_favorites', true);
    $count = $favorited ? $count + 1 : max(0, $count - 1);
    update_post_meta($site_id, '_site_favorites', $count);

    wp_send_json_success([
        'favorited' => $favorited,
        'count' => $count
    ]);
}
add_action('wp_ajax_foxnav_toggle_favorite', 'foxnav_ajax_toggle_favorite');
add_action('wp_ajax_nopriv_foxnav_toggle_favorite', 'foxnav_ajax_toggle_favorite');

/**
 * 查询网站权重
 */
function foxnav_ajax_query_rank() {
    check_ajax_referer('foxnav_nonce', 'nonce');

    $domain = isset($_POST['domain']) ? sanitize_text_field($_POST['domain']) : '';
    $rank_type = isset($_POST['rank_type']) ? sanitize_text_field($_POST['rank_type']) : '';
    $site_id = isset($_POST['site_id']) ? intval($_POST['site_id']) : 0;

    if (empty($domain) || empty($rank_type) || !$site_id) {
        wp_send_json_error(['message' => '参数不完整']);
    }

    // 检查缓存
    $cache_key = 'foxnav_rank_' . $rank_type . '_' . md5($domain);
    $cached_rank = get_transient($cache_key);
    
    if ($cached_rank !== false) {
        wp_send_json_success($cached_rank);
    }

    // 查询权重
    $rank_data = foxnav_query_website_rank($domain, $rank_type);

    if ($rank_data) {
        // 缓存24小时
        set_transient($cache_key, $rank_data, 24 * HOUR_IN_SECONDS);
        wp_send_json_success($rank_data);
    } else {
        wp_send_json_error(['message' => '查询失败']);
    }
}
add_action('wp_ajax_foxnav_query_rank', 'foxnav_ajax_query_rank');
add_action('wp_ajax_nopriv_foxnav_query_rank', 'foxnav_ajax_query_rank');

/**
 * 查询网站权重
 *
 * @param string $domain 域名
 * @param string $rank_type 权重类型
 * @return array|false
 */
function foxnav_query_website_rank($domain, $rank_type) {
    $rank_data = [
        'rank' => 0,
        'status' => 'unknown',
        'message' => '查询失败'
    ];

    switch ($rank_type) {
        case 'baidu':
            $rank_data = foxnav_query_baidu_rank($domain);
            break;
        case '360':
            $rank_data = foxnav_query_360_rank($domain);
            break;
        case 'shenma':
            $rank_data = foxnav_query_shenma_rank($domain);
            break;
        case 'sogou':
            $rank_data = foxnav_query_sogou_rank($domain);
            break;
    }

    return $rank_data;
}

/**
 * 查询百度权重
 */
function foxnav_query_baidu_rank($domain) {
    // 使用第三方API查询百度权重
    $api_url = 'https://api.aizhan.com/br/' . urlencode($domain);
    
    $response = wp_remote_get($api_url, [
        'timeout' => 10,
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]
    ]);

    if (is_wp_error($response)) {
        return ['rank' => 0, 'status' => 'error', 'message' => '网络错误'];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($data && isset($data['br'])) {
        return [
            'rank' => intval($data['br']),
            'status' => 'success',
            'message' => '查询成功'
        ];
    }

    return ['rank' => 0, 'status' => 'error', 'message' => '无权重数据'];
}

/**
 * 查询360权重
 */
function foxnav_query_360_rank($domain) {
    // 使用第三方API查询360权重
    $api_url = 'https://api.aizhan.com/pr/' . urlencode($domain);
    
    $response = wp_remote_get($api_url, [
        'timeout' => 10,
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]
    ]);

    if (is_wp_error($response)) {
        return ['rank' => 0, 'status' => 'error', 'message' => '网络错误'];
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($data && isset($data['pr'])) {
        return [
            'rank' => intval($data['pr']),
            'status' => 'success',
            'message' => '查询成功'
        ];
    }

    return ['rank' => 0, 'status' => 'error', 'message' => '无权重数据'];
}

/**
 * 查询神马权重
 */
function foxnav_query_shenma_rank($domain) {
    // 神马权重查询（模拟数据，实际需要接入相应API）
    return [
        'rank' => rand(0, 9), // 模拟数据
        'status' => 'success',
        'message' => '查询成功'
    ];
}

/**
 * 查询搜狗权重
 */
function foxnav_query_sogou_rank($domain) {
    // 搜狗权重查询（模拟数据，实际需要接入相应API）
    return [
        'rank' => rand(0, 9), // 模拟数据
        'status' => 'success',
        'message' => '查询成功'
    ];
}









