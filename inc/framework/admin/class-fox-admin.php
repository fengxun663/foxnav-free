<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Fox_Admin')) {
    class Fox_Admin
    {
        public function __construct()
        {
            add_action('admin_menu', [$this, 'add_admin_menu']);
        }

        /**
         * 添加后台菜单
         */
        public function add_admin_menu()
        {
            add_menu_page(
                __('主题选项', 'fox-framework'),
                __('主题选项', 'fox-framework'),
                'manage_options',
                'fox-framework',
                [$this, 'render_admin_page'],
                'dashicons-admin-generic',
                60
            );
        }

    /**
     * 渲染后台页面
     */
    public function render_admin_page()
    {
        $sections = $this->get_sections();
        $current_section = isset($sections[0]) ? $sections[0]['id'] : '';
        // 根据URL参数确定激活分组（无JS时也可工作）
        if (isset($_GET['section']) && !empty($_GET['section'])) {
            $requested = sanitize_text_field($_GET['section']);
            foreach ($sections as $sec) {
                if ($sec['id'] === $requested) {
                    $current_section = $requested;
                    break;
                }
            }
        }
        
        ?>
        <div class="wrap fox-admin-wrap">
            <div class="fox-admin-header">
                <h1><?php _e('主题设置', 'fox-framework'); ?></h1>
                <p class="description">配置您的主题选项，让网站更符合您的需求</p>
            </div>
            
            <div class="fox-admin-container">
                <!-- 左侧菜单 -->
                <div class="fox-admin-sidebar">
                    <nav class="fox-nav-menu">
                        <?php foreach ($sections as $index => $section): ?>
                            <a href="<?php echo esc_url( admin_url( 'admin.php?page=fox-framework&section=' . $section['id'] ) ); ?>" 
                               data-section="<?php echo esc_attr($section['id']); ?>"
                               class="fox-nav-item <?php echo ($section['id'] === $current_section) ? 'active' : ''; ?>">
                                <span class="fox-nav-icon"><?php echo $section['icon']; ?></span>
                                <span class="fox-nav-label"><?php echo $section['title']; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </nav>
                </div>
                
                <!-- 右侧内容 -->
                <div class="fox-admin-content">
                    <form method="post" action="options.php" id="fox-options-form">
                        <?php settings_fields('fox_framework_options_group'); ?>
                        
                        <?php
                        // 渲染所有分组的内容，用 data-section 标识
                        foreach ($sections as $index => $section) {
                            $visible = ($section['id'] === $current_section) ? '' : 'display: none;';
                            echo '<div class="fox-section-content" data-section="' . esc_attr($section['id']) . '" style="' . $visible . '">';
                            $this->render_section_fields($section['id']);
                            echo '</div>';
                        }
                        ?>
                        
                        <div class="fox-admin-footer">
                            <?php submit_button('保存设置', 'primary', 'submit', false); ?>
                            <button type="button" class="button fox-reset-button">恢复默认</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * 获取分组信息
     */
    private function get_sections()
    {
        return [
            [
                'id'    => 'usage',
                'title' => '使用说明',
                'icon'  => '📖',
            ],
            [
                'id'    => 'icons',
                'title' => '图标设置',
                'icon'  => '🎨',
            ],
            [
                'id'    => 'basic',
                'title' => '基础设置',
                'icon'  => '⚙️',
            ],
            [
                'id'    => 'seo',
                'title' => 'SEO设置',
                'icon'  => '🔍',
            ],
            [
                'id'    => 'pages',
                'title' => '页面设置',
                'icon'  => '📄',
            ],
            [
                'id'    => 'appearance',
                'title' => '外观设置',
                'icon'  => '🎭',
            ],
            [
                'id'    => 'advanced',
                'title' => '高级设置',
                'icon'  => '🔧',
            ],
            [
                'id'    => 'updates',
                'title' => '更新管理',
                'icon'  => '🔄',
            ],
        ];
    }
    
    /**
     * 渲染指定分组的字段
     */
    private function render_section_fields($section_id)
    {
        $fields = fox_framework()->get_registered_options();
        
        if (empty($fields)) {
            echo '<div class="fox-empty-message">暂无设置项</div>';
            return;
        }
        
        // 根据字段ID判断所属分组
        $section_fields = [];
        foreach ($fields as $field) {
            // 确保字段有id
            if (!isset($field['id'])) {
                continue;
            }
            
            $field_section = $this->get_field_section($field['id']);
            if ($field_section === $section_id) {
                $section_fields[] = $field;
            }
        }
        
        if (empty($section_fields)) {
            echo '<div class="fox-empty-message">该分组暂无设置项</div>';
            return;
        }
        
        $fox_settings = new Fox_Settings();
        $table_open = false;
        
        foreach ($section_fields as $field) {
            // content、heading、submessage 字段单独渲染，不放在表格中
            $non_table_types = ['heading', 'submessage', 'content'];
            
            if (in_array($field['type'], $non_table_types)) {
                if ($table_open) {
                echo '</table>';
                    $table_open = false;
                }
                $fox_settings->render_field($field);
                continue;
            }
            
            if (!$table_open) {
                echo '<table class="form-table" role="presentation">';
                $table_open = true;
            }
            
                echo '<tr>';
                echo '<th scope="row">';
                if (isset($field['title'])) {
                    echo '<label>' . esc_html($field['title']) . '</label>';
                }
                echo '</th>';
                echo '<td>';
                $fox_settings->render_field($field);
                echo '</td>';
                echo '</tr>';
        }
        
        if ($table_open) {
        echo '</table>';
        }
    }
    
    /**
     * 根据字段在选项数组中的位置智能判断所属分组
     */
    private function get_field_section($field_id)
    {
        $fields = fox_framework()->get_registered_options();
        $current_section = 'usage'; // 默认分组改为 usage（第一个section）
        
        foreach ($fields as $field) {
            // 确保字段有id
            if (!isset($field['id'])) {
                continue;
            }
            
            // 如果找到目标字段，返回当前section
            if ($field['id'] === $field_id) {
                // 如果当前字段本身是heading，根据其content确定section
                if (isset($field['type']) && $field['type'] === 'heading') {
                    return $this->map_heading_field_to_section($field);
                }
                return $current_section;
            }
            
            // 如果遇到heading字段，更新当前section（为后续字段使用）
            if (isset($field['type']) && $field['type'] === 'heading') {
                $current_section = $this->map_heading_field_to_section($field);
            }
        }
        
        return $current_section;
    }
    
    /**
     * 将heading内容映射到section ID
     */
    private function map_heading_to_section($heading_content)
    {
        $mapping = [
            '使用说明' => 'usage',
            '图标设置' => 'icons', 
            '基础设置' => 'basic',
            'SEO设置' => 'seo',
            '页面设置' => 'pages',
            '首页设置' => 'pages',
            '广告设置' => 'pages',
            '文章页设置' => 'pages',
            '功能开关' => 'pages',
            '外观设置' => 'appearance',
            '高级设置' => 'advanced',
            '更新管理' => 'updates',
        ];
        
        return isset($mapping[$heading_content]) ? $mapping[$heading_content] : 'basic';
    }
 
    /**
     * 将heading字段映射到section ID（优先使用ID，更稳健）
     */
    private function map_heading_field_to_section($field)
    {
        $id = isset($field['id']) ? $field['id'] : '';
        switch ($id) {
            case 'usage_instructions':
                return 'usage';
            case 'icon_settings':
                return 'icons';
            case 'basic_settings':
                return 'basic';
            case 'seo_settings':
                return 'seo';
            case 'page_settings':
            case 'homepage_settings':
            case 'ad_settings':
            case 'post_settings':
            case 'feature_settings':
                return 'pages';
            case 'appearance_settings':
                return 'appearance';
            case 'advanced_settings':
            return 'advanced';
            case 'update_settings':
                return 'updates';
            default:
                // 回退到根据标题内容映射
                if (isset($field['content'])) {
                    return $this->map_heading_to_section($field['content']);
                }
                return 'usage';
        }
    }
    }

    new Fox_Admin();
}
