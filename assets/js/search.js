/**
 * FoxNav 搜索功能
 * 支持站内搜索和多个搜索引擎
 * 
 * @package FoxNav
 */

(function($) {
    'use strict';

    var FoxNavSearch = {
        
        currentSearchType: 'site', // 默认站内搜索
        currentSearchUrl: '',
        currentPlaceholder: '站内搜索',
        
        /**
         * 初始化
         */
        init: function() {
            this.bindEvents();
            this.setDefaultSearch();
        },

        /**
         * 设置默认搜索
         */
        setDefaultSearch: function() {
            var $checked = $('input[name="type"]:checked');
            if ($checked.length > 0) {
                this.currentSearchType = $checked.attr('id');
                this.currentSearchUrl = $checked.val();
                this.currentPlaceholder = $checked.data('placeholder');
                $('#search_keyword').attr('placeholder', this.currentPlaceholder);
            }
        },

        /**
         * 绑定事件
         */
        bindEvents: function() {
            var self = this;

            // 搜索类型切换
            $('input[name="type"]').on('change', function() {
                self.currentSearchType = $(this).attr('id');
                self.currentSearchUrl = $(this).val();
                self.currentPlaceholder = $(this).data('placeholder');
                $('#search_keyword').attr('placeholder', self.currentPlaceholder).focus();
            });

            // 搜索表单提交
            $('#search_wrap').on('submit', function(e) {
                e.preventDefault();
                self.performSearch();
                return false;
            });

            // 搜索按钮点击
            $('#search_submit').on('click', function(e) {
                e.preventDefault();
                self.performSearch();
                return false;
            });

            // 回车键搜索
            $('#search_keyword').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    self.performSearch();
                    return false;
                }
            });
        },

        /**
         * 执行搜索
         */
        performSearch: function() {
            var keyword = $('#search_keyword').val().trim();
            
            if (!keyword) {
                this.showMessage('请输入搜索关键词');
                return false;
            }

            // 判断是站内搜索还是外部搜索引擎
            if (this.currentSearchType === 'type-zhannei') {
                // 站内搜索
                this.siteSearch(keyword);
            } else {
                // 外部搜索引擎
                this.externalSearch(keyword);
            }
        },

        /**
         * 站内搜索
         */
        siteSearch: function(keyword) {
            var homeUrl = typeof foxnavData !== 'undefined' ? foxnavData.homeUrl : '/';
            var searchUrl = homeUrl + '?s=' + encodeURIComponent(keyword) + '&post_type=site';
            window.location.href = searchUrl;
        },

        /**
         * 外部搜索引擎搜索
         */
        externalSearch: function(keyword) {
            if (!this.currentSearchUrl) {
                this.showMessage('搜索引擎配置错误');
                return false;
            }

            // 替换搜索URL中的关键词占位符
            var searchUrl = this.currentSearchUrl.replace('%s%', encodeURIComponent(keyword));
            
            // 在新窗口打开搜索结果
            window.open(searchUrl, '_blank');
        },

        /**
         * 显示提示消息
         */
        showMessage: function(message) {
            // 如果有layer.js，使用layer提示
            if (typeof layer !== 'undefined') {
                layer.msg(message);
            } else {
                alert(message);
            }
        }
    };

    // DOM加载完成后初始化
    $(document).ready(function() {
        FoxNavSearch.init();
    });

})(jQuery);

