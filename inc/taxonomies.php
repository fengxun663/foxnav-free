<?php
/**
 * 注册网址分类法
 *
 * @package FoxNav
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 注册网址分类
 */
function foxnav_register_site_category() {
    $labels = [
        'name'                       => __('网址分类', 'foxnav'),
        'singular_name'              => __('网址分类', 'foxnav'),
        'menu_name'                  => __('网址分类', 'foxnav'),
        'all_items'                  => __('所有分类', 'foxnav'),
        'parent_item'                => __('父级分类', 'foxnav'),
        'parent_item_colon'          => __('父级分类：', 'foxnav'),
        'new_item_name'              => __('新分类名称', 'foxnav'),
        'add_new_item'               => __('添加新分类', 'foxnav'),
        'edit_item'                  => __('编辑分类', 'foxnav'),
        'update_item'                => __('更新分类', 'foxnav'),
        'view_item'                  => __('查看分类', 'foxnav'),
        'separate_items_with_commas' => __('用逗号分隔分类', 'foxnav'),
        'add_or_remove_items'        => __('添加或移除分类', 'foxnav'),
        'choose_from_most_used'      => __('从常用分类中选择', 'foxnav'),
        'popular_items'              => __('热门分类', 'foxnav'),
        'search_items'               => __('搜索分类', 'foxnav'),
        'not_found'                  => __('未找到分类', 'foxnav'),
        'no_terms'                   => __('无分类', 'foxnav'),
        'items_list'                 => __('分类列表', 'foxnav'),
        'items_list_navigation'      => __('分类列表导航', 'foxnav'),
    ];

    $args = [
        'labels'            => $labels,
        'hierarchical'      => true,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,
        'rest_base'         => 'site-categories',
        'rewrite'           => ['slug' => 'site-category', 'with_front' => false, 'hierarchical' => true],
    ];

    register_taxonomy('site_category', ['site'], $args);
}
add_action('init', 'foxnav_register_site_category');

/**
 * 注册网址标签
 */
function foxnav_register_site_tag() {
    $labels = [
        'name'                       => __('网址标签', 'foxnav'),
        'singular_name'              => __('网址标签', 'foxnav'),
        'menu_name'                  => __('网址标签', 'foxnav'),
        'all_items'                  => __('所有标签', 'foxnav'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'new_item_name'              => __('新标签名称', 'foxnav'),
        'add_new_item'               => __('添加新标签', 'foxnav'),
        'edit_item'                  => __('编辑标签', 'foxnav'),
        'update_item'                => __('更新标签', 'foxnav'),
        'view_item'                  => __('查看标签', 'foxnav'),
        'separate_items_with_commas' => __('用逗号分隔标签', 'foxnav'),
        'add_or_remove_items'        => __('添加或移除标签', 'foxnav'),
        'choose_from_most_used'      => __('从常用标签中选择', 'foxnav'),
        'popular_items'              => __('热门标签', 'foxnav'),
        'search_items'               => __('搜索标签', 'foxnav'),
        'not_found'                  => __('未找到标签', 'foxnav'),
        'no_terms'                   => __('无标签', 'foxnav'),
        'items_list'                 => __('标签列表', 'foxnav'),
        'items_list_navigation'      => __('标签列表导航', 'foxnav'),
    ];

    $args = [
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => true,
        'show_in_rest'      => true,
        'rest_base'         => 'site-tags',
        'rewrite'           => ['slug' => 'site-tag', 'with_front' => false],
    ];

    register_taxonomy('site_tag', ['site'], $args);
}
add_action('init', 'foxnav_register_site_tag');

/**
 * 注册特色标签
 */
function foxnav_register_featured_tag() {
    $labels = [
        'name'                       => __('特色标签', 'foxnav'),
        'singular_name'              => __('特色标签', 'foxnav'),
        'menu_name'                  => __('特色标签', 'foxnav'),
        'all_items'                  => __('所有特色标签', 'foxnav'),
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'new_item_name'              => __('新特色标签名称', 'foxnav'),
        'add_new_item'               => __('添加新特色标签', 'foxnav'),
        'edit_item'                  => __('编辑特色标签', 'foxnav'),
        'update_item'                => __('更新特色标签', 'foxnav'),
        'view_item'                  => __('查看特色标签', 'foxnav'),
        'separate_items_with_commas' => __('用逗号分隔特色标签', 'foxnav'),
        'add_or_remove_items'        => __('添加或移除特色标签', 'foxnav'),
        'choose_from_most_used'      => __('从常用特色标签中选择', 'foxnav'),
        'popular_items'              => __('热门特色标签', 'foxnav'),
        'search_items'               => __('搜索特色标签', 'foxnav'),
        'not_found'                  => __('未找到特色标签', 'foxnav'),
        'no_terms'                   => __('无特色标签', 'foxnav'),
        'items_list'                 => __('特色标签列表', 'foxnav'),
        'items_list_navigation'      => __('特色标签列表导航', 'foxnav'),
    ];

    $args = [
        'labels'            => $labels,
        'hierarchical'      => false,
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud'     => false,
        'show_in_rest'      => true,
        'rest_base'         => 'featured-tags',
        'rewrite'           => ['slug' => 'featured', 'with_front' => false],
    ];

    register_taxonomy('featured_tag', ['site'], $args);
}
add_action('init', 'foxnav_register_featured_tag');

/**
 * 为分类添加自定义字段（图标、颜色等）
 */
function foxnav_add_category_custom_fields($term) {
    $term_id = $term->term_id ?? 0;
    $icon = get_term_meta($term_id, 'category_icon', true);
    $color = get_term_meta($term_id, 'category_color', true);
    $order = get_term_meta($term_id, 'category_order', true);
    ?>
    <tr class="form-field">
        <th scope="row"><label for="category_icon"><?php _e('分类图标', 'foxnav'); ?></label></th>
        <td>
            <input type="text" name="category_icon" id="category_icon" value="<?php echo esc_attr($icon); ?>" class="regular-text">
            <p class="description"><?php _e('输入 Dashicons 类名或图标 URL', 'foxnav'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="category_color"><?php _e('分类颜色', 'foxnav'); ?></label></th>
        <td>
            <input type="color" name="category_color" id="category_color" value="<?php echo esc_attr($color ?: '#3498db'); ?>">
            <p class="description"><?php _e('选择此分类的主题颜色', 'foxnav'); ?></p>
        </td>
    </tr>
    <tr class="form-field">
        <th scope="row"><label for="category_order"><?php _e('排序', 'foxnav'); ?></label></th>
        <td>
            <input type="number" name="category_order" id="category_order" value="<?php echo esc_attr($order ?: 0); ?>" class="small-text">
            <p class="description"><?php _e('数字越小，排序越靠前', 'foxnav'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('site_category_edit_form_fields', 'foxnav_add_category_custom_fields');

/**
 * 保存分类自定义字段
 */
function foxnav_save_category_custom_fields($term_id) {
    if (isset($_POST['category_icon'])) {
        update_term_meta($term_id, 'category_icon', sanitize_text_field($_POST['category_icon']));
    }
    if (isset($_POST['category_color'])) {
        update_term_meta($term_id, 'category_color', sanitize_hex_color($_POST['category_color']));
    }
    if (isset($_POST['category_order'])) {
        update_term_meta($term_id, 'category_order', intval($_POST['category_order']));
    }
}
add_action('edited_site_category', 'foxnav_save_category_custom_fields');
add_action('create_site_category', 'foxnav_save_category_custom_fields');

