/**
 * FoxNav 前端脚本
 * 
 * @package FoxNav
 */

(function($) {
    'use strict';

    var FoxNav = {
        
        /**
         * 初始化
         */
        init: function() {
            this.handleSiteClick();
            this.handleSearch();
            this.handleFavorite();
            this.lazyLoadImages();
        },

        /**
         * 处理网址点击统计
         */
        handleSiteClick: function() {
            $('.foxnav-site-link').on('click', function() {
                var siteId = $(this).data('site-id');
                
                if (siteId) {
                    $.ajax({
                        url: foxnavData.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'foxnav_increment_click',
                            nonce: foxnavData.nonce,
                            site_id: siteId
                        }
                    });
                }
            });
        },

        /**
         * 搜索功能
         */
        handleSearch: function() {
            var searchInput = $('#foxnav-search');
            var searchResults = $('#foxnav-search-results');
            var searchTimeout;

            if (searchInput.length === 0) {
                return;
            }

            searchInput.on('input', function() {
                var keyword = $(this).val().trim();
                
                clearTimeout(searchTimeout);
                
                if (keyword.length < 2) {
                    searchResults.hide();
                    return;
                }
                
                searchTimeout = setTimeout(function() {
                    $.ajax({
                        url: foxnavData.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'foxnav_search_sites',
                            nonce: foxnavData.nonce,
                            keyword: keyword
                        },
                        beforeSend: function() {
                            searchResults.html('<div class="search-loading">' + foxnavData.strings.loading + '</div>').show();
                        },
                        success: function(response) {
                            if (response.success && response.data.html) {
                                searchResults.html(response.data.html).show();
                            } else {
                                searchResults.html('<div class="search-empty">未找到相关网址</div>').show();
                            }
                        },
                        error: function() {
                            searchResults.html('<div class="search-error">' + foxnavData.strings.error + '</div>').show();
                        }
                    });
                }, 300);
            });

            // 点击外部关闭搜索结果
            $(document).on('click', function(e) {
                if (!$(e.target).closest('#foxnav-search, #foxnav-search-results').length) {
                    searchResults.hide();
                }
            });

            // ESC键关闭
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    searchResults.hide();
                }
            });
        },

        /**
         * 收藏功能
         */
        handleFavorite: function() {
            $(document).on('click', '.foxnav-favorite-btn', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var siteId = button.data('site-id');
                
                if (!siteId) {
                    return;
                }
                
                $.ajax({
                    url: foxnavData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'foxnav_toggle_favorite',
                        nonce: foxnavData.nonce,
                        site_id: siteId
                    },
                    success: function(response) {
                        if (response.success) {
                            button.toggleClass('favorited');
                            
                            var count = button.find('.favorite-count');
                            if (count.length > 0) {
                                count.text(response.data.count);
                            }
                        }
                    }
                });
            });
        },

        /**
         * 图片懒加载 - 真正的懒加载（滚动加载）
         */
        lazyLoadImages: function() {
            // 检查懒加载是否启用
            var lazyEnabled = typeof foxnavData !== 'undefined' && foxnavData.lazyLoadEnabled;
            
            if (!lazyEnabled) {
                return; // 如果未启用懒加载，直接返回
            }

            // 选择所有带有data-src属性的懒加载图片
            var lazyImages = document.querySelectorAll('img.lazy[data-src]');
            
            if (lazyImages.length === 0) {
                return; // 没有需要懒加载的图片
            }
            
            // 调试信息
            if (window.console && console.log) {
                console.log('🚀 懒加载初始化，共找到 ' + lazyImages.length + ' 张图片');
            }

            // 图片加载函数
            function loadImage(img) {
                if (!img.dataset.src) {
                    return;
                }
                
                // 调试信息（可选）
                if (window.console && console.log) {
                    console.log('🖼️ 懒加载图片:', img.dataset.src);
                }
                
                var tempImg = new Image();
                tempImg.onload = function() {
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    img.removeAttribute('data-src');
                    
                    // 调试信息
                    if (window.console && console.log) {
                        console.log('✅ 图片加载成功:', img.src);
                    }
                };
                tempImg.onerror = function() {
                    img.classList.add('error');
                    img.removeAttribute('data-src');
                    
                    // 调试信息
                    if (window.console && console.warn) {
                        console.warn('❌ 图片加载失败:', img.dataset.src);
                    }
                };
                tempImg.src = img.dataset.src;
            }

            // 获取配置参数
            var distance = (foxnavData && foxnavData.lazyLoadDistance) ? foxnavData.lazyLoadDistance : 100;
            var fadeSpeed = (foxnavData && foxnavData.lazyLoadFadeSpeed) ? foxnavData.lazyLoadFadeSpeed : 300;
            
            // 使用Intersection Observer（现代浏览器最佳方案）
                if ('IntersectionObserver' in window) {
                    var imageObserver = new IntersectionObserver(function(entries, observer) {
                        entries.forEach(function(entry) {
                            if (entry.isIntersecting) {
                            var img = entry.target;
                            loadImage(img);
                            // 停止观察已加载的图片
                            imageObserver.unobserve(img);
                        }
                    });
                }, {
                    rootMargin: distance + 'px 0px', // 使用配置的提前距离
                    threshold: 0.01
                });

                // 观察所有懒加载图片
                lazyImages.forEach(function(img) {
                        imageObserver.observe(img);
                    });
                
                // 保存observer到全局，以便后续动态添加的图片也能懒加载
                window.foxnavLazyObserver = imageObserver;
                
            } else {
                // 降级方案：使用scroll事件（兼容旧浏览器）
                var lazyLoadThrottleTimeout;
                
                function lazyLoad() {
                    if (lazyLoadThrottleTimeout) {
                        clearTimeout(lazyLoadThrottleTimeout);
                    }

                    lazyLoadThrottleTimeout = setTimeout(function() {
                        var scrollTop = window.pageYOffset;
                        var windowHeight = window.innerHeight;
                        
                        lazyImages.forEach(function(img) {
                            if (img.dataset.src) {
                                var rect = img.getBoundingClientRect();
                                var imgTop = rect.top + scrollTop;
                                
                                // 检查图片是否在可视区域内（使用配置的提前距离）
                                if (imgTop < (scrollTop + windowHeight + distance) && (imgTop + rect.height) > (scrollTop - distance)) {
                                    loadImage(img);
                                }
                            }
                        });
                        
                        // 如果所有图片都加载完了，移除事件监听
                        var remaining = document.querySelectorAll('img.lazy[data-src]');
                        if (remaining.length === 0) {
                            window.removeEventListener('scroll', lazyLoad);
                            window.removeEventListener('resize', lazyLoad);
                            window.removeEventListener('orientationchange', lazyLoad);
                        }
                    }, 20);
                }

                // 绑定事件
                window.addEventListener('scroll', lazyLoad);
                window.addEventListener('resize', lazyLoad);
                window.addEventListener('orientationchange', lazyLoad);
                
                // 初始加载可视区域的图片
                lazyLoad();
            }
        }
    };

    // DOM 加载完成后初始化
    $(document).ready(function() {
        FoxNav.init();
    });

})(jQuery);















































