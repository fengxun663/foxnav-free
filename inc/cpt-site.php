<?php
/**
 * 注册「网址」自定义文章类型
 *
 * @package FoxNav
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 注册网址文章类型
 */
function foxnav_register_site_post_type() {
    $labels = [
        'name'                  => __('网址', 'foxnav'),
        'singular_name'         => __('网址', 'foxnav'),
        'menu_name'             => __('网址管理', 'foxnav'),
        'name_admin_bar'        => __('网址', 'foxnav'),
        'add_new'               => __('添加网址', 'foxnav'),
        'add_new_item'          => __('添加新网址', 'foxnav'),
        'new_item'              => __('新网址', 'foxnav'),
        'edit_item'             => __('编辑网址', 'foxnav'),
        'view_item'             => __('查看网址', 'foxnav'),
        'all_items'             => __('所有网址', 'foxnav'),
        'search_items'          => __('搜索网址', 'foxnav'),
        'parent_item_colon'     => __('父级网址：', 'foxnav'),
        'not_found'             => __('未找到网址', 'foxnav'),
        'not_found_in_trash'    => __('回收站中未找到网址', 'foxnav'),
        'featured_image'        => __('网站截图', 'foxnav'),
        'set_featured_image'    => __('设置网站截图', 'foxnav'),
        'remove_featured_image' => __('移除网站截图', 'foxnav'),
        'use_featured_image'    => __('使用作为网站截图', 'foxnav'),
        'archives'              => __('网址归档', 'foxnav'),
        'insert_into_item'      => __('插入到网址', 'foxnav'),
        'uploaded_to_this_item' => __('上传到此网址', 'foxnav'),
        'filter_items_list'     => __('筛选网址列表', 'foxnav'),
        'items_list_navigation' => __('网址列表导航', 'foxnav'),
        'items_list'            => __('网址列表', 'foxnav'),
    ];

    $args = [
        'labels'              => $labels,
        'public'              => true,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-admin-site-alt3',
        'query_var'           => true,
        'rewrite'             => ['slug' => 'sites', 'with_front' => false],
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt', 'author', 'revisions'],
        'show_in_rest'        => true,
        'rest_base'           => 'sites',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
    ];

    register_post_type('site', $args);
}
add_action('init', 'foxnav_register_site_post_type');

/**
 * 自定义网址列表提示文本
 */
function foxnav_site_updated_messages($messages) {
    global $post;

    $permalink = get_permalink($post);

    $messages['site'] = [
        0  => '', // 未使用
        1  => sprintf(__('网址已更新。 <a href="%s">查看网址</a>', 'foxnav'), esc_url($permalink)),
        2  => __('自定义字段已更新。', 'foxnav'),
        3  => __('自定义字段已删除。', 'foxnav'),
        4  => __('网址已更新。', 'foxnav'),
        5  => isset($_GET['revision']) ? sprintf(__('网址已恢复到 %s 的修订版本', 'foxnav'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6  => sprintf(__('网址已发布。 <a href="%s">查看网址</a>', 'foxnav'), esc_url($permalink)),
        7  => __('网址已保存。', 'foxnav'),
        8  => sprintf(__('网址已提交。 <a target="_blank" href="%s">预览网址</a>', 'foxnav'), esc_url(add_query_arg('preview', 'true', $permalink))),
        9  => sprintf(__('网址已计划发布于： <strong>%1$s</strong>。 <a target="_blank" href="%2$s">预览网址</a>', 'foxnav'), date_i18n(__('Y年m月d日 @ H:i', 'foxnav'), strtotime($post->post_date)), esc_url($permalink)),
        10 => sprintf(__('网址草稿已更新。 <a target="_blank" href="%s">预览网址</a>', 'foxnav'), esc_url(add_query_arg('preview', 'true', $permalink))),
    ];

    return $messages;
}
add_filter('post_updated_messages', 'foxnav_site_updated_messages');

