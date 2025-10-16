<?php
/**
 * 后台管理列表自定义列
 *
 * @package FoxNav
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 添加网址列表自定义列
 */
function foxnav_add_site_columns($columns) {
    $new_columns = [];
    
    foreach ($columns as $key => $value) {
        if ($key === 'title') {
            $new_columns[$key] = $value;
            $new_columns['site_info'] = __('网址信息', 'foxnav');
            $new_columns['site_screenshot'] = __('截图', 'foxnav');
            $new_columns['site_status'] = __('状态', 'foxnav');
        } elseif ($key === 'date') {
            $new_columns['site_stats'] = __('统计', 'foxnav');
            $new_columns[$key] = $value;
        } else {
            $new_columns[$key] = $value;
        }
    }
    
    return $new_columns;
}
add_filter('manage_site_posts_columns', 'foxnav_add_site_columns');

/**
 * 自定义列内容输出
 */
function foxnav_site_column_content($column, $post_id) {
    switch ($column) {
        case 'site_info':
            $site_url = get_post_meta($post_id, '_site_url', true);
            $site_name = get_post_meta($post_id, '_site_name', true);
            $site_favicon = get_post_meta($post_id, '_site_favicon', true);
            
            echo '<div class="foxnav-site-info">';
            
            if ($site_favicon) {
                echo '<img src="' . esc_url($site_favicon) . '" class="foxnav-favicon" style="width: 24px; height: 24px; vertical-align: middle; margin-right: 8px;">';
            }
            
            if ($site_name) {
                echo '<strong>' . esc_html($site_name) . '</strong><br>';
            }
            
            if ($site_url) {
                $domain = parse_url($site_url, PHP_URL_HOST);
                echo '<a href="' . esc_url($site_url) . '" target="_blank" rel="noopener">' . esc_html($domain) . '</a>';
                echo ' <span class="dashicons dashicons-external" style="font-size: 12px;"></span>';
            } else {
                echo '<span style="color: #d63638;">' . __('未设置网址', 'foxnav') . '</span>';
            }
            
            echo '</div>';
            break;

        case 'site_screenshot':
            $screenshot = get_post_meta($post_id, '_site_screenshot', true);
            if (!$screenshot) {
                $screenshot = get_the_post_thumbnail_url($post_id, 'thumbnail');
            }
            
            if ($screenshot) {
                echo '<img src="' . esc_url($screenshot) . '" style="max-width: 80px; height: auto; border-radius: 4px; border: 1px solid #ddd;">';
            } else {
                echo '<span class="dashicons dashicons-format-image" style="font-size: 40px; color: #ddd;"></span>';
            }
            break;

        case 'site_status':
            $official = get_post_meta($post_id, '_site_official', true);
            $verified = get_post_meta($post_id, '_site_verified', true);
            $nofollow = get_post_meta($post_id, '_site_nofollow', true);
            $sponsored = get_post_meta($post_id, '_site_sponsored', true);
            
            echo '<div class="foxnav-status-badges">';
            
            if ($official === '1') {
                echo '<span class="foxnav-badge foxnav-badge-official" title="' . __('官方认证', 'foxnav') . '">';
                echo '<span class="dashicons dashicons-yes-alt"></span> ' . __('官方', 'foxnav');
                echo '</span><br>';
            }
            
            if ($verified === '1') {
                echo '<span class="foxnav-badge foxnav-badge-verified" title="' . __('已验证', 'foxnav') . '">';
                echo '<span class="dashicons dashicons-shield-alt"></span> ' . __('已验证', 'foxnav');
                echo '</span><br>';
            }
            
            if ($nofollow === '1') {
                echo '<span class="foxnav-badge foxnav-badge-nofollow" title="' . __('Nofollow 链接', 'foxnav') . '">';
                echo 'Nofollow';
                echo '</span><br>';
            }
            
            if ($sponsored === '1') {
                echo '<span class="foxnav-badge foxnav-badge-sponsored" title="' . __('赞助链接', 'foxnav') . '">';
                echo '<span class="dashicons dashicons-money-alt"></span> ' . __('赞助', 'foxnav');
                echo '</span>';
            }
            
            if (!$official && !$verified && !$nofollow && !$sponsored) {
                echo '<span style="color: #999;">—</span>';
            }
            
            echo '</div>';
            break;

        case 'site_stats':
            // 这里可以添加点击量、收藏等统计数据
            $clicks = get_post_meta($post_id, '_site_clicks', true) ?: 0;
            $favorites = get_post_meta($post_id, '_site_favorites', true) ?: 0;
            
            echo '<div class="foxnav-stats">';
            echo '<span class="dashicons dashicons-visibility"></span> ' . number_format($clicks) . '<br>';
            echo '<span class="dashicons dashicons-star-filled"></span> ' . number_format($favorites);
            echo '</div>';
            break;
    }
}
add_action('manage_site_posts_custom_column', 'foxnav_site_column_content', 10, 2);

/**
 * 设置可排序的列
 */
function foxnav_sortable_site_columns($columns) {
    $columns['site_stats'] = 'site_clicks';
    return $columns;
}
add_filter('manage_edit-site_sortable_columns', 'foxnav_sortable_site_columns');

/**
 * 自定义排序查询
 */
function foxnav_site_columns_orderby($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ('site_clicks' === $orderby) {
        $query->set('meta_key', '_site_clicks');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'foxnav_site_columns_orderby');

/**
 * 添加自定义筛选器
 */
function foxnav_add_site_filters() {
    global $typenow;
    
    if ($typenow !== 'site') {
        return;
    }
    
    // 按状态筛选
    $current_status = isset($_GET['site_status']) ? $_GET['site_status'] : '';
    ?>
    <select name="site_status">
        <option value=""><?php _e('所有状态', 'foxnav'); ?></option>
        <option value="official" <?php selected($current_status, 'official'); ?>><?php _e('官方认证', 'foxnav'); ?></option>
        <option value="verified" <?php selected($current_status, 'verified'); ?>><?php _e('已验证', 'foxnav'); ?></option>
        <option value="sponsored" <?php selected($current_status, 'sponsored'); ?>><?php _e('赞助链接', 'foxnav'); ?></option>
    </select>
    <?php
}
add_action('restrict_manage_posts', 'foxnav_add_site_filters');

/**
 * 处理自定义筛选查询
 */
function foxnav_filter_sites_by_status($query) {
    global $pagenow, $typenow;
    
    if ($pagenow === 'edit.php' && $typenow === 'site' && isset($_GET['site_status']) && $_GET['site_status'] !== '') {
        $status = sanitize_text_field($_GET['site_status']);
        
        $meta_query = [
            [
                'key' => '_site_' . $status,
                'value' => '1',
                'compare' => '='
            ]
        ];
        
        $query->set('meta_query', $meta_query);
    }
}
add_filter('parse_query', 'foxnav_filter_sites_by_status');

/**
 * 添加后台样式
 */
function foxnav_admin_column_styles() {
    global $typenow;
    
    if ($typenow !== 'site') {
        return;
    }
    ?>
    <style>
        .foxnav-site-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .foxnav-favicon {
            flex-shrink: 0;
        }
        .foxnav-status-badges {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .foxnav-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
            white-space: nowrap;
        }
        .foxnav-badge .dashicons {
            font-size: 14px;
            width: 14px;
            height: 14px;
        }
        .foxnav-badge-official {
            background: #d4edda;
            color: #155724;
        }
        .foxnav-badge-verified {
            background: #cce5ff;
            color: #004085;
        }
        .foxnav-badge-nofollow {
            background: #f8d7da;
            color: #721c24;
        }
        .foxnav-badge-sponsored {
            background: #fff3cd;
            color: #856404;
        }
        .foxnav-stats {
            font-size: 12px;
            color: #666;
        }
        .foxnav-stats .dashicons {
            font-size: 14px;
            width: 14px;
            height: 14px;
            vertical-align: middle;
        }
        .column-site_screenshot {
            width: 100px;
        }
        .column-site_status {
            width: 120px;
        }
        .column-site_stats {
            width: 80px;
        }
    </style>
    <?php
}
add_action('admin_head', 'foxnav_admin_column_styles');









