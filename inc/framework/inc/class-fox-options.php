<?php
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Fox_Options')) {
    class Fox_Options
    {
        /**
         * 获取选项值
         */
        public static function get($option_name, $default = false)
        {
            $options = get_option('fox_framework_options', []);
            return isset($options[$option_name]) ? $options[$option_name] : $default;
        }

        /**
         * 设置选项值
         */
        public static function set($option_name, $value)
        {
            $options = get_option('fox_framework_options', []);
            $options[$option_name] = $value;
            update_option('fox_framework_options', $options);
        }
    }
}
