<?php
/**
 * 注册「友情链接」自定义文章类型
 *
 * @package FoxNav
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 注册友情链接文章类型
 */
function foxnav_register_friendlink_post_type() {
    $labels = [
        'name'                  => __('友情链接', 'foxnav'),
        'singular_name'         => __('友情链接', 'foxnav'),
        'menu_name'             => __('友链管理', 'foxnav'),
        'name_admin_bar'        => __('友情链接', 'foxnav'),
        'add_new'               => __('添加友情链接', 'foxnav'),
        'add_new_item'          => __('添加新友情链接', 'foxnav'),
        'new_item'              => __('新友情链接', 'foxnav'),
        'edit_item'             => __('编辑友情链接', 'foxnav'),
        'view_item'             => __('查看友情链接', 'foxnav'),
        'all_items'             => __('所有友情链接', 'foxnav'),
        'search_items'          => __('搜索友情链接', 'foxnav'),
        'parent_item_colon'     => __('父级友情链接：', 'foxnav'),
        'not_found'             => __('未找到友情链接', 'foxnav'),
        'not_found_in_trash'    => __('回收站中未找到友情链接', 'foxnav'),
        'featured_image'        => __('友情链接图标', 'foxnav'),
        'set_featured_image'    => __('设置友情链接图标', 'foxnav'),
        'remove_featured_image' => __('移除友情链接图标', 'foxnav'),
        'use_featured_image'    => __('使用友情链接图标', 'foxnav'),
    ];

    $args = [
        'labels'              => $labels,
        'public'              => false,
        'publicly_queryable'  => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => true,
        'show_in_rest'        => true,
        'query_var'           => true,
        'rewrite'             => false,
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'menu_position'       => 25,
        'menu_icon'           => 'dashicons-admin-links',
        'supports'            => ['title', 'editor', 'thumbnail'],
        'show_admin_column'   => true,
    ];

    register_post_type('friendlink', $args);
}
add_action('init', 'foxnav_register_friendlink_post_type');

/**
 * 添加友情链接元数据框
 */
function foxnav_add_friendlink_meta_boxes() {
    add_meta_box(
        'friendlink_properties',
        __('友情链接属性', 'foxnav'),
        'foxnav_friendlink_properties_callback',
        'friendlink',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'foxnav_add_friendlink_meta_boxes');

/**
 * 友情链接属性元数据框回调
 */
function foxnav_friendlink_properties_callback($post) {
    wp_nonce_field('foxnav_friendlink_meta', 'foxnav_friendlink_meta_nonce');
    
    $link_url = get_post_meta($post->ID, '_friendlink_url', true);
    $link_description = get_post_meta($post->ID, '_friendlink_description', true);
    $link_order = get_post_meta($post->ID, '_friendlink_order', true);
    $link_status = get_post_meta($post->ID, '_friendlink_status', true);
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="friendlink_url"><?php _e('链接地址', 'foxnav'); ?></label>
            </th>
            <td>
                <input type="url" id="friendlink_url" name="friendlink_url" value="<?php echo esc_attr($link_url); ?>" class="large-text" required>
                <p class="description"><?php _e('请输入完整的URL地址，如：https://example.com', 'foxnav'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="friendlink_description"><?php _e('链接描述', 'foxnav'); ?></label>
            </th>
            <td>
                <textarea id="friendlink_description" name="friendlink_description" rows="3" class="large-text"><?php echo esc_textarea($link_description); ?></textarea>
                <p class="description"><?php _e('友情链接的简短描述（可选）', 'foxnav'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="friendlink_order"><?php _e('排序', 'foxnav'); ?></label>
            </th>
            <td>
                <input type="number" id="friendlink_order" name="friendlink_order" value="<?php echo esc_attr($link_order ?: 0); ?>" class="small-text">
                <p class="description"><?php _e('数字越小排序越靠前', 'foxnav'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="friendlink_status"><?php _e('状态', 'foxnav'); ?></label>
            </th>
            <td>
                <select id="friendlink_status" name="friendlink_status">
                    <option value="1" <?php selected($link_status, '1'); ?>><?php _e('启用', 'foxnav'); ?></option>
                    <option value="0" <?php selected($link_status, '0'); ?>><?php _e('禁用', 'foxnav'); ?></option>
                </select>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * 保存友情链接元数据
 */
function foxnav_save_friendlink_meta($post_id) {
    if (!isset($_POST['foxnav_friendlink_meta_nonce']) || !wp_verify_nonce($_POST['foxnav_friendlink_meta_nonce'], 'foxnav_friendlink_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = [
        '_friendlink_url' => 'esc_url_raw',
        '_friendlink_description' => 'sanitize_textarea_field',
        '_friendlink_order' => 'intval',
        '_friendlink_status' => 'sanitize_text_field',
    ];

    foreach ($fields as $field => $sanitize_callback) {
        if (isset($_POST[str_replace('_', '', $field)])) {
            $value = $_POST[str_replace('_', '', $field)];
            $value = call_user_func($sanitize_callback, $value);
            update_post_meta($post_id, $field, $value);
        }
    }
}
add_action('save_post', 'foxnav_save_friendlink_meta');

/**
 * 获取友情链接列表
 */
function foxnav_get_friendlinks($limit = -1) {
    $args = [
        'post_type' => 'friendlink',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
        'meta_query' => [
            [
                'key' => '_friendlink_status',
                'value' => '1',
                'compare' => '='
            ]
        ],
        'meta_key' => '_friendlink_order',
        'orderby' => 'meta_value_num',
        'order' => 'ASC'
    ];

    return new WP_Query($args);
}
