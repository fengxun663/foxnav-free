<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Fox_Settings')) {
    class Fox_Settings
    {
        public function __construct()
        {
            add_action('admin_init', [$this, 'register_settings']);
            add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
            add_action('admin_notices', [$this, 'show_save_notice']);
        }

        /**
         * 加载后台 JS 和 CSS 文件
         */
        public function enqueue_scripts($hook)
        {
            // 只在主题设置页面加载
            if ($hook !== 'toplevel_page_fox-framework') {
                return;
            }
            
            // 加载 WordPress 媒体库
            wp_enqueue_media();
            
            // 加载 WordPress 颜色选择器
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
            
            // 加载主题框架资源
            wp_enqueue_script('fox-framework-admin', FOX_FRAMEWORK_URL . 'assets/admin.js', ['jquery', 'wp-color-picker'], '2.0.0', true);
            wp_enqueue_style('fox-framework-admin', FOX_FRAMEWORK_URL . 'assets/admin.css', ['wp-color-picker'], '2.0.0');
        }
        

        /**
         * 注册设置字段
         */
        public function register_settings()
        {
            register_setting(
                'fox_framework_options_group', 
                'fox_framework_options'
            );

            add_settings_section(
                'fox_framework_section',
                __('主题设置', 'fox-framework'),
                null,
                'fox-framework'
            );

            $fields = fox_framework()->get_registered_options();

            if (!empty($fields)) {
                foreach ($fields as $field) {
                    // content、heading、submessage 字段不需要保存数据，只用于显示
                    $non_saving_types = ['content', 'heading', 'submessage'];
                    
                    add_settings_field(
                        $field['id'],
                        isset($field['title']) ? $field['title'] : '',
                        [$this, 'render_field'],
                        'fox-framework',
                        'fox_framework_section',
                        $field
                    );
                }
            }
        }

        /**
         * 显示保存成功消息
         */
        public function show_save_notice()
        {
            // 只在主题设置页面显示
            $screen = get_current_screen();
            if ($screen->id !== 'toplevel_page_fox-framework') {
                return;
            }
            
            // 检查是否有保存成功的参数
            if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true') {
                echo '<div class="notice notice-success is-dismissible fox-save-notice">';
                echo '<p><span class="dashicons dashicons-yes-alt" style="color: #46b450; margin-right: 8px;"></span>';
                echo '<strong>设置已保存！</strong> 您的主题设置已成功保存。';
                echo '</p>';
                echo '</div>';
                
                // 添加自动消失的JavaScript
                echo '<script>
                jQuery(document).ready(function($) {
                    setTimeout(function() {
                        $(".fox-save-notice").fadeOut(3000);
                    }, 2000);
                });
                </script>';
            }
        }

        /**
         * 渲染字段
         */
        public function render_field($field)
        {
            // content、heading、submessage 类型不需要获取值
            $non_value_types = ['content', 'heading', 'submessage'];
            $value = '';
            
            if (!in_array($field['type'], $non_value_types)) {
                $default = isset($field['default']) ? $field['default'] : '';
                $value = Fox_Options::get($field['id'], $default);
            }

            switch ($field['type']) {
                case 'text':
                    echo '<input type="text" name="fox_framework_options[' . esc_attr($field['id']) . ']" value="' . esc_attr($value) . '" />';
                    break;

                case 'checkbox':
                    echo '<input type="checkbox" name="fox_framework_options[' . esc_attr($field['id']) . ']" value="1" ' . checked(1, $value, false) . ' />';
                    break;

                case 'color':
                    ?>
                    <input type="text" 
                           class="fox-color-picker" 
                           name="fox_framework_options[<?php echo esc_attr($field['id']); ?>]" 
                           value="<?php echo esc_attr($value); ?>" 
                           data-default-color="<?php echo esc_attr(isset($field['default']) ? $field['default'] : ''); ?>" />
                    <?php
                    break;

                case 'textarea':
                    echo '<textarea name="fox_framework_options[' . esc_attr($field['id']) . ']">' . esc_textarea($value) . '</textarea>';
                    break;

                case 'image':
                    ?>
                    <div class="fox-image-upload-wrapper">
                        <input type="text" 
                               id="<?php echo esc_attr($field['id']); ?>" 
                               class="fox-image-url-input"
                               name="fox_framework_options[<?php echo esc_attr($field['id']); ?>]" 
                               value="<?php echo esc_url($value); ?>" 
                               placeholder="图片URL..." />
                        
                        <div class="fox-image-buttons">
                            <button type="button" class="button button-primary fox-upload-button" data-field-id="<?php echo esc_attr($field['id']); ?>">
                                <span class="dashicons dashicons-upload" style="margin-top: 3px;"></span>
                                <?php _e('上传图片', 'fox-framework'); ?>
                            </button>
                            
                            <?php if ($value): ?>
                                <button type="button" class="button fox-remove-button" data-field-id="<?php echo esc_attr($field['id']); ?>" style="color: #d63638;">
                                    <span class="dashicons dashicons-trash" style="margin-top: 3px;"></span>
                                    <?php _e('删除图片', 'fox-framework'); ?>
                                </button>
                            <?php else: ?>
                                <button type="button" class="button fox-remove-button" data-field-id="<?php echo esc_attr($field['id']); ?>" style="display: none; color: #d63638;">
                                    <span class="dashicons dashicons-trash" style="margin-top: 3px;"></span>
                                    <?php _e('删除图片', 'fox-framework'); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                        
                        <div class="fox-image-preview" id="preview-<?php echo esc_attr($field['id']); ?>">
                            <?php if ($value): ?>
                                <div class="fox-image-preview-container">
                                    <img src="<?php echo esc_url($value); ?>" alt="预览图">
                                    <div class="fox-image-overlay">
                                        <span class="dashicons dashicons-search"></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php
                    break;
                    
                case 'number':
                    echo '<input type="number" name="fox_framework_options[' . esc_attr($field['id']) . ']" value="' . esc_attr($value) . '" />';
                    break;
                
                case 'slider':
                    echo '<input type="range" name="fox_framework_options[' . esc_attr($field['id']) . ']" min="' . esc_attr($field['min']) . '" max="' . esc_attr($field['max']) . '" step="' . esc_attr($field['step']) . '" value="' . esc_attr($value) . '" />';
                    echo '<span class="fox-slider-value">' . esc_attr($value) . '</span>';
                    break;
                
                case 'checkbox_group':
                    if (!empty($field['options']) && is_array($field['options'])) {
                        foreach ($field['options'] as $key => $label) {
                            $checked = (is_array($value) && in_array($key, $value)) ? 'checked' : '';
                            echo '<label><input type="checkbox" name="fox_framework_options[' . esc_attr($field['id']) . '][]" value="' . esc_attr($key) . '" ' . $checked . '> ' . esc_html($label) . '</label><br>';
                        }
                    }
                    break;
                
                case 'radio':
                    if (!empty($field['options']) && is_array($field['options'])) {
                        foreach ($field['options'] as $key => $label) {
                            $checked = ($value == $key) ? 'checked' : '';
                            echo '<label><input type="radio" name="fox_framework_options[' . esc_attr($field['id']) . ']" value="' . esc_attr($key) . '" ' . $checked . '> ' . esc_html($label) . '</label><br>';
                        }
                    }
                    break;
                
                case 'select':
                    if (!empty($field['options']) && is_array($field['options'])) {
                        echo '<select name="fox_framework_options[' . esc_attr($field['id']) . ']">';
                        foreach ($field['options'] as $key => $label) {
                            $selected = ($value == $key) ? 'selected' : '';
                            echo '<option value="' . esc_attr($key) . '" ' . $selected . '>' . esc_html($label) . '</option>';
                        }
                        echo '</select>';
                    }
                    break;
                
                case 'switch':
                    echo '<label class="fox-switch">';
                    echo '<input type="checkbox" name="fox_framework_options[' . esc_attr($field['id']) . ']" value="1" ' . checked(1, $value, false) . '>';
                    echo '<span class="fox-slider"></span>';
                    echo '</label>';
                    break;
                
                case 'group':
                    if (!empty($field['fields']) && is_array($field['fields'])) {
                        foreach ($field['fields'] as $sub_field) {
                            echo '<div class="fox-group-field">';
                            $sub_field['id'] = $field['id'] . '[' . $sub_field['id'] . ']';
                            $sub_field_value = isset($value[$sub_field['id']]) ? $value[$sub_field['id']] : $sub_field['default'];
                            $sub_field['value'] = $sub_field_value;
                            $this->render_field($sub_field);
                            echo '</div>';
                        }
                    }
                    break;
                
                case 'code_editor':
                    echo '<textarea class="fox-code-editor" name="fox_framework_options[' . esc_attr($field['id']) . ']" rows="10" style="width: 100%; font-family: monospace;">' . esc_textarea($value) . '</textarea>';
                    break;
                
                case 'content':
                    // Content 字段 - 用于显示静态内容，不需要保存
                    echo '<div class="fox-content-field">';
                    if (!empty($field['content'])) {
                        echo wp_kses_post($field['content']);
                    }
                    echo '</div>';
                    break;
                
                case 'heading':
                    // Heading 字段 - 用于显示标题/分隔符
                    echo '<div class="fox-heading-field">';
                    if (!empty($field['content'])) {
                        echo '<h3>' . esc_html($field['content']) . '</h3>';
                    }
                    echo '</div>';
                    break;
                
                case 'submessage':
                    // Submessage 字段 - 用于显示提示信息
                    $msg_type = isset($field['style']) ? $field['style'] : 'normal';
                    echo '<div class="fox-submessage-field fox-submessage-' . esc_attr($msg_type) . '">';
                    if (!empty($field['content'])) {
                        echo wp_kses_post($field['content']);
                    }
                    echo '</div>';
                    break;
                                
                    

                default:
                    echo '<input type="text" name="fox_framework_options[' . esc_attr($field['id']) . ']" value="' . esc_attr($value) . '" />';
                    break;
            }
        }
    }

    new Fox_Settings();
}
