<?php
/**
 * Plugin Name: Fox Framework
 * Plugin URI:  https://yourwebsite.com/
 * Description: 一个强大、可扩展的 WordPress 主题选项框架。
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com/
 * License: GPL-2.0+
 * Text Domain: fox-framework
 */

if (!defined('ABSPATH')) {
    exit; // 防止直接访问
}

if (!class_exists('Fox_Framework')) {
    class Fox_Framework
    {
        private static $instance = null;
        private $registered_options = [];

        /**
         * 获取框架实例（单例模式）
         */
        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * 构造函数，初始化框架
         */
        private function __construct()
        {
            $this->define_constants();
            $this->include_files();
            $this->init_hooks();
        }

        /**
         * 定义常量
         */
        private function define_constants()
        {
            define('FOX_FRAMEWORK_VERSION', '1.0.0');
            define('FOX_FRAMEWORK_DIR', get_template_directory() . '/inc/framework/');
            define('FOX_FRAMEWORK_URL', get_template_directory_uri() . '/inc/framework/');
        }

        /**
         * 引入框架核心文件
         */
        private function include_files()
        {
            require_once FOX_FRAMEWORK_DIR . 'inc/class-fox-options.php';
            require_once FOX_FRAMEWORK_DIR . 'inc/class-fox-settings.php';
            require_once FOX_FRAMEWORK_DIR . 'admin/class-fox-admin.php';
        }

        /**
         * 绑定 WordPress 钩子
         */
        private function init_hooks()
        {
            add_action('init', [$this, 'load_textdomain']);
        }

        /**
         * 加载国际化支持
         */
        public function load_textdomain()
        {
            load_theme_textdomain('fox-framework', get_template_directory() . '/inc/framework/languages/');
        }

        /**
         * 让开发者注册选项字段
         */
        public function add_options($options)
        {
            $this->registered_options = array_merge($this->registered_options, $options);
        }

        /**
         * 获取已注册的选项
         */
        public function get_registered_options()
        {
            return $this->registered_options;
        }
    }

    // 初始化框架
    function fox_framework()
    {
        return Fox_Framework::get_instance();
    }

    fox_framework();
}
