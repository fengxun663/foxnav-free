<?php
/**
 * 网址元框 - 属性和SEO设置
 *
 * @package FoxNav
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 注册网址属性元框
 */
function foxnav_add_site_meta_boxes() {
    // 网址属性元框
    add_meta_box(
        'foxnav_site_properties',
        __('网址属性', 'foxnav'),
        'foxnav_site_properties_callback',
        'site',
        'normal',
        'high'
    );

    // SEO设置元框
    add_meta_box(
        'foxnav_seo_settings',
        __('SEO 设置', 'foxnav'),
        'foxnav_seo_settings_callback',
        'site',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'foxnav_add_site_meta_boxes');

/**
 * 网址属性元框回调
 */
function foxnav_site_properties_callback($post) {
    wp_nonce_field('foxnav_site_properties_nonce', 'foxnav_site_properties_nonce');

    $site_url = get_post_meta($post->ID, '_site_url', true);
    $site_name = get_post_meta($post->ID, '_site_name', true);
    $site_logo = get_post_meta($post->ID, '_site_logo', true);
    $site_screenshot = get_post_meta($post->ID, '_site_screenshot', true);
    $site_description = get_post_meta($post->ID, '_site_description', true);
    $site_favicon = get_post_meta($post->ID, '_site_favicon', true);
    $site_official = get_post_meta($post->ID, '_site_official', true);
    $site_verified = get_post_meta($post->ID, '_site_verified', true);
    $site_nofollow = get_post_meta($post->ID, '_site_nofollow', true);
    $site_sponsored = get_post_meta($post->ID, '_site_sponsored', true);
    ?>
    <div class="foxnav-meta-box">
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="site_url"><?php _e('域名（网址）', 'foxnav'); ?> <span class="required">*</span></label>
                </th>
                <td>
                    <input type="url" id="site_url" name="site_url" value="<?php echo esc_url($site_url); ?>" class="large-text" required>
                    <p class="description"><?php _e('请输入完整的网址，包括 http:// 或 https://', 'foxnav'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="site_name"><?php _e('网站名称', 'foxnav'); ?></label>
                </th>
                <td>
                    <input type="text" id="site_name" name="site_name" value="<?php echo esc_attr($site_name); ?>" class="large-text">
                    <p class="description"><?php _e('网站的官方名称，留空则使用文章标题', 'foxnav'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="site_favicon"><?php _e('网站图标（Favicon）', 'foxnav'); ?></label>
                </th>
                <td>
                    <div class="foxnav-media-upload">
                        <input type="url" id="site_favicon" name="site_favicon" value="<?php echo esc_url($site_favicon); ?>" class="large-text foxnav-media-url">
                        <button type="button" class="button foxnav-upload-media-btn" data-target="site_favicon"><?php _e('上传图标', 'foxnav'); ?></button>
                        <button type="button" class="button foxnav-auto-fetch-favicon" data-url-field="site_url" data-target="site_favicon"><?php _e('自动获取', 'foxnav'); ?></button>
                        <?php if ($site_favicon): ?>
                            <div class="foxnav-media-preview">
                                <img src="<?php echo esc_url($site_favicon); ?>" style="max-width: 64px; max-height: 64px; margin-top: 10px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <p class="description"><?php _e('网站的 favicon 图标，推荐 32x32 或 64x64 像素', 'foxnav'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="site_logo"><?php _e('网站 Logo', 'foxnav'); ?></label>
                </th>
                <td>
                    <div class="foxnav-media-upload">
                        <input type="url" id="site_logo" name="site_logo" value="<?php echo esc_url($site_logo); ?>" class="large-text foxnav-media-url">
                        <button type="button" class="button foxnav-upload-media-btn" data-target="site_logo"><?php _e('上传 Logo', 'foxnav'); ?></button>
                        <?php if ($site_logo): ?>
                            <div class="foxnav-media-preview">
                                <img src="<?php echo esc_url($site_logo); ?>" style="max-width: 200px; max-height: 100px; margin-top: 10px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <p class="description"><?php _e('网站的品牌 Logo，推荐透明背景 PNG', 'foxnav'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="site_screenshot"><?php _e('网站截图', 'foxnav'); ?></label>
                </th>
                <td>
                    <div class="foxnav-media-upload">
                        <input type="url" id="site_screenshot" name="site_screenshot" value="<?php echo esc_url($site_screenshot); ?>" class="large-text foxnav-media-url">
                        <button type="button" class="button foxnav-upload-media-btn" data-target="site_screenshot"><?php _e('上传截图', 'foxnav'); ?></button>
                        <button type="button" class="button button-primary foxnav-auto-screenshot" data-url-field="site_url" data-target="site_screenshot"><?php _e('自动截图', 'foxnav'); ?></button>
                        <?php if ($site_screenshot): ?>
                            <div class="foxnav-media-preview">
                                <img src="<?php echo esc_url($site_screenshot); ?>" style="max-width: 400px; margin-top: 10px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <p class="description"><?php _e('网站首页截图，推荐 1200x800 像素。也可使用特色图像代替', 'foxnav'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="site_description"><?php _e('网站介绍', 'foxnav'); ?></label>
                </th>
                <td>
                    <textarea id="site_description" name="site_description" rows="5" class="large-text"><?php echo esc_textarea($site_description); ?></textarea>
                    <p class="description"><?php _e('简要介绍这个网站的功能和特点，留空则使用文章摘要', 'foxnav'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php _e('网站状态', 'foxnav'); ?></label>
                </th>
                <td>
                    <fieldset>
                        <label>
                            <input type="checkbox" name="site_official" value="1" <?php checked($site_official, '1'); ?>>
                            <?php _e('官方认证', 'foxnav'); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="site_verified" value="1" <?php checked($site_verified, '1'); ?>>
                            <?php _e('已验证', 'foxnav'); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="site_nofollow" value="1" <?php checked($site_nofollow, '1'); ?>>
                            <?php _e('添加 nofollow 属性', 'foxnav'); ?>
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="site_sponsored" value="1" <?php checked($site_sponsored, '1'); ?>>
                            <?php _e('赞助链接', 'foxnav'); ?>
                        </label>
                    </fieldset>
                </td>
            </tr>
        </table>
    </div>

    <style>
        .foxnav-meta-box .required { color: #d63638; }
        .foxnav-media-upload { display: flex; gap: 8px; align-items: flex-start; flex-wrap: wrap; }
        .foxnav-media-upload .foxnav-media-url { flex: 1; min-width: 300px; }
        .foxnav-media-preview { width: 100%; margin-top: 10px; }
        .foxnav-media-preview img { border: 1px solid #ddd; padding: 4px; background: #fff; }
    </style>
    <?php
}

/**
 * SEO设置元框回调
 */
function foxnav_seo_settings_callback($post) {
    wp_nonce_field('foxnav_seo_settings_nonce', 'foxnav_seo_settings_nonce');

    $seo_title = get_post_meta($post->ID, '_seo_title', true);
    $seo_description = get_post_meta($post->ID, '_seo_description', true);
    $seo_keywords = get_post_meta($post->ID, '_seo_keywords', true);
    $seo_canonical = get_post_meta($post->ID, '_seo_canonical', true);
    $seo_robots = get_post_meta($post->ID, '_seo_robots', true);
    $seo_og_image = get_post_meta($post->ID, '_seo_og_image', true);
    ?>
    <div class="foxnav-seo-box">
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="seo_title"><?php _e('SEO 标题', 'foxnav'); ?></label>
                </th>
                <td>
                    <input type="text" id="seo_title" name="seo_title" value="<?php echo esc_attr($seo_title); ?>" class="large-text">
                    <p class="description">
                        <?php _e('自定义搜索引擎显示的标题，留空则使用文章标题。推荐 50-60 字符', 'foxnav'); ?>
                        <span id="seo_title_count" style="margin-left: 10px;"></span>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="seo_description"><?php _e('SEO 描述', 'foxnav'); ?></label>
                </th>
                <td>
                    <textarea id="seo_description" name="seo_description" rows="3" class="large-text"><?php echo esc_textarea($seo_description); ?></textarea>
                    <p class="description">
                        <?php _e('搜索引擎结果页显示的描述，推荐 150-160 字符', 'foxnav'); ?>
                        <span id="seo_description_count" style="margin-left: 10px;"></span>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="seo_keywords"><?php _e('SEO 关键词', 'foxnav'); ?></label>
                </th>
                <td>
                    <input type="text" id="seo_keywords" name="seo_keywords" value="<?php echo esc_attr($seo_keywords); ?>" class="large-text">
                    <p class="description"><?php _e('用逗号分隔多个关键词，如：工具,设计,在线', 'foxnav'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="seo_canonical"><?php _e('规范链接 (Canonical)', 'foxnav'); ?></label>
                </th>
                <td>
                    <input type="url" id="seo_canonical" name="seo_canonical" value="<?php echo esc_url($seo_canonical); ?>" class="large-text">
                    <p class="description"><?php _e('指定此页面的规范 URL，避免重复内容问题', 'foxnav'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="seo_robots"><?php _e('搜索引擎索引', 'foxnav'); ?></label>
                </th>
                <td>
                    <select id="seo_robots" name="seo_robots" class="regular-text">
                        <option value="" <?php selected($seo_robots, ''); ?>><?php _e('默认（index, follow）', 'foxnav'); ?></option>
                        <option value="noindex, nofollow" <?php selected($seo_robots, 'noindex, nofollow'); ?>><?php _e('不索引，不跟踪', 'foxnav'); ?></option>
                        <option value="noindex, follow" <?php selected($seo_robots, 'noindex, follow'); ?>><?php _e('不索引，跟踪', 'foxnav'); ?></option>
                        <option value="index, nofollow" <?php selected($seo_robots, 'index, nofollow'); ?>><?php _e('索引，不跟踪', 'foxnav'); ?></option>
                    </select>
                    <p class="description"><?php _e('控制搜索引擎如何索引此页面', 'foxnav'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="seo_og_image"><?php _e('社交分享图片', 'foxnav'); ?></label>
                </th>
                <td>
                    <div class="foxnav-media-upload">
                        <input type="url" id="seo_og_image" name="seo_og_image" value="<?php echo esc_url($seo_og_image); ?>" class="large-text foxnav-media-url">
                        <button type="button" class="button foxnav-upload-media-btn" data-target="seo_og_image"><?php _e('上传图片', 'foxnav'); ?></button>
                        <?php if ($seo_og_image): ?>
                            <div class="foxnav-media-preview">
                                <img src="<?php echo esc_url($seo_og_image); ?>" style="max-width: 400px; margin-top: 10px;">
                            </div>
                        <?php endif; ?>
                    </div>
                    <p class="description"><?php _e('在社交媒体分享时显示的图片，推荐 1200x630 像素', 'foxnav'); ?></p>
                </td>
            </tr>
        </table>
    </div>

    <script>
    jQuery(document).ready(function($) {
        // 字符计数
        function updateCharCount(inputId, counterId) {
            var input = $('#' + inputId);
            var counter = $('#' + counterId);
            var count = input.val().length;
            counter.text('当前：' + count + ' 字符');
            
            if (inputId === 'seo_title' && (count > 60 || count < 50 && count > 0)) {
                counter.css('color', '#d63638');
            } else if (inputId === 'seo_description' && (count > 160 || count < 150 && count > 0)) {
                counter.css('color', '#d63638');
            } else {
                counter.css('color', '#008a00');
            }
        }

        $('#seo_title').on('input', function() {
            updateCharCount('seo_title', 'seo_title_count');
        });

        $('#seo_description').on('input', function() {
            updateCharCount('seo_description', 'seo_description_count');
        });

        // 初始化计数
        updateCharCount('seo_title', 'seo_title_count');
        updateCharCount('seo_description', 'seo_description_count');
    });
    </script>
    <?php
}

/**
 * 保存网址属性元数据
 */
function foxnav_save_site_properties($post_id) {
    // 验证 nonce
    if (!isset($_POST['foxnav_site_properties_nonce']) || 
        !wp_verify_nonce($_POST['foxnav_site_properties_nonce'], 'foxnav_site_properties_nonce')) {
        return;
    }

    // 检查自动保存
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // 检查权限
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // 保存网址属性
    $fields = [
        'site_url' => 'esc_url_raw',
        'site_name' => 'sanitize_text_field',
        'site_logo' => 'esc_url_raw',
        'site_screenshot' => 'esc_url_raw',
        'site_description' => 'sanitize_textarea_field',
        'site_favicon' => 'esc_url_raw',
    ];

    foreach ($fields as $field => $sanitize_callback) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, call_user_func($sanitize_callback, $_POST[$field]));
        }
    }

    // 保存复选框
    $checkboxes = ['site_official', 'site_verified', 'site_nofollow', 'site_sponsored'];
    foreach ($checkboxes as $checkbox) {
        update_post_meta($post_id, '_' . $checkbox, isset($_POST[$checkbox]) ? '1' : '0');
    }
}
add_action('save_post_site', 'foxnav_save_site_properties');

/**
 * 保存SEO设置元数据
 */
function foxnav_save_seo_settings($post_id) {
    // 验证 nonce
    if (!isset($_POST['foxnav_seo_settings_nonce']) || 
        !wp_verify_nonce($_POST['foxnav_seo_settings_nonce'], 'foxnav_seo_settings_nonce')) {
        return;
    }

    // 检查自动保存
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // 检查权限
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // 保存SEO字段
    $fields = [
        'seo_title' => 'sanitize_text_field',
        'seo_description' => 'sanitize_textarea_field',
        'seo_keywords' => 'sanitize_text_field',
        'seo_canonical' => 'esc_url_raw',
        'seo_robots' => 'sanitize_text_field',
        'seo_og_image' => 'esc_url_raw',
    ];

    foreach ($fields as $field => $sanitize_callback) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, call_user_func($sanitize_callback, $_POST[$field]));
        }
    }
}
add_action('save_post_site', 'foxnav_save_seo_settings');


