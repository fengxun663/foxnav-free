<?php
/**
 * 小工具注册
 *
 * @package FoxNav
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 热门网站小工具
 */
class FoxNav_Popular_Sites_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'foxnav_popular_sites',
            __('热门网站', 'foxnav'),
            ['description' => __('显示访问量最高的网站', 'foxnav')]
        );
    }
    
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('热门网站', 'foxnav');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        
        echo $args['before_widget'];
        echo $args['before_title'] . esc_html($title) . $args['after_title'];
        
        $popular_sites = foxnav_get_popular_sites($number);
        
        if ($popular_sites->have_posts()) {
            echo '<ul class="foxnav-widget-list">';
            while ($popular_sites->have_posts()) {
                $popular_sites->the_post();
                $site_data = foxnav_get_site_data(get_the_ID());
                ?>
                <li class="widget-site-item">
                    <a href="<?php the_permalink(); ?>" class="site-link">
                        <?php if ($site_data['favicon']): ?>
                            <img src="<?php echo esc_url($site_data['favicon']); ?>" 
                                 alt="" class="site-favicon">
                        <?php endif; ?>
                        <div class="site-info">
                            <span class="site-name"><?php echo esc_html($site_data['name'] ?: get_the_title()); ?></span>
                            <span class="site-clicks"><?php echo number_format($site_data['clicks']); ?> 次访问</span>
                        </div>
                    </a>
                </li>
                <?php
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo '<p>' . __('暂无热门网站', 'foxnav') . '</p>';
        }
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('热门网站', 'foxnav');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('标题：', 'foxnav'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
                   name="<?php echo $this->get_field_name('title'); ?>" 
                   type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('显示数量：', 'foxnav'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" 
                   name="<?php echo $this->get_field_name('number'); ?>" 
                   type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 5;
        return $instance;
    }
}

/**
 * 最新网站小工具
 */
class FoxNav_Recent_Sites_Widget extends WP_Widget {
    
    public function __construct() {
        parent::__construct(
            'foxnav_recent_sites',
            __('最新网站', 'foxnav'),
            ['description' => __('显示最新添加的网站', 'foxnav')]
        );
    }
    
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('最新网站', 'foxnav');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        
        echo $args['before_widget'];
        echo $args['before_title'] . esc_html($title) . $args['after_title'];
        
        $recent_sites = foxnav_get_recent_sites($number);
        
        if ($recent_sites->have_posts()) {
            echo '<ul class="foxnav-widget-list">';
            while ($recent_sites->have_posts()) {
                $recent_sites->the_post();
                $site_data = foxnav_get_site_data(get_the_ID());
                ?>
                <li class="widget-site-item">
                    <a href="<?php the_permalink(); ?>" class="site-link">
                        <?php if ($site_data['favicon']): ?>
                            <img src="<?php echo esc_url($site_data['favicon']); ?>" 
                                 alt="" class="site-favicon">
                        <?php endif; ?>
                        <div class="site-info">
                            <span class="site-name"><?php echo esc_html($site_data['name'] ?: get_the_title()); ?></span>
                            <span class="site-date"><?php echo get_the_date(); ?></span>
                        </div>
                    </a>
                </li>
                <?php
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo '<p>' . __('暂无最新网站', 'foxnav') . '</p>';
        }
        
        echo $args['after_widget'];
    }
    
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('最新网站', 'foxnav');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('标题：', 'foxnav'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" 
                   name="<?php echo $this->get_field_name('title'); ?>" 
                   type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('显示数量：', 'foxnav'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" 
                   name="<?php echo $this->get_field_name('number'); ?>" 
                   type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>">
        </p>
        <?php
    }
    
    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 5;
        return $instance;
    }
}

/**
 * 注册小工具
 */
function foxnav_register_widgets() {
    register_widget('FoxNav_Popular_Sites_Widget');
    register_widget('FoxNav_Recent_Sites_Widget');
}
add_action('widgets_init', 'foxnav_register_widgets');

