/**
 * FoxNav 后台脚本
 * 
 * @package FoxNav
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        /**
         * 媒体上传器
         */
        $('.foxnav-upload-media-btn').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var targetId = button.data('target');
            var targetInput = $('#' + targetId);
            
            var mediaUploader = wp.media({
                title: foxnavAdmin.strings.selectImage,
                button: {
                    text: foxnavAdmin.strings.useImage
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                targetInput.val(attachment.url);
                
                // 更新预览
                var preview = button.siblings('.foxnav-media-preview');
                if (preview.length === 0) {
                    preview = $('<div class="foxnav-media-preview"></div>');
                    button.parent().append(preview);
                }
                preview.html('<img src="' + attachment.url + '" style="max-width: 400px; margin-top: 10px;">');
                
                // 触发change事件
                targetInput.trigger('change');
            });
            
            mediaUploader.open();
        });

        /**
         * 自动获取Favicon
         */
        $('.foxnav-auto-fetch-favicon').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var urlFieldId = button.data('url-field');
            var targetId = button.data('target');
            var siteUrl = $('#' + urlFieldId).val();
            
            if (!siteUrl) {
                alert('请先输入网址');
                return;
            }
            
            button.prop('disabled', true).text(foxnavAdmin.strings.fetching);
            
            $.ajax({
                url: foxnavAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'foxnav_fetch_favicon',
                    nonce: foxnavAdmin.nonce,
                    url: siteUrl
                },
                success: function(response) {
                    if (response.success && response.data.favicon) {
                        $('#' + targetId).val(response.data.favicon);
                        
                        // 更新预览
                        var preview = button.siblings('.foxnav-media-preview');
                        if (preview.length === 0) {
                            preview = $('<div class="foxnav-media-preview"></div>');
                            button.parent().append(preview);
                        }
                        preview.html('<img src="' + response.data.favicon + '" style="max-width: 64px; max-height: 64px; margin-top: 10px;">');
                        
                        button.text(foxnavAdmin.strings.fetchSuccess);
                    } else {
                        alert(response.data.message || foxnavAdmin.strings.fetchError);
                        button.text('自动获取');
                    }
                },
                error: function() {
                    alert(foxnavAdmin.strings.fetchError);
                    button.text('自动获取');
                },
                complete: function() {
                    setTimeout(function() {
                        button.prop('disabled', false).text('自动获取');
                    }, 2000);
                }
            });
        });

        /**
         * 自动截图功能
         */
        $('.foxnav-auto-screenshot').on('click', function(e) {
            e.preventDefault();
            
            var button = $(this);
            var urlFieldId = button.data('url-field');
            var targetId = button.data('target');
            var siteUrl = $('#' + urlFieldId).val();
            
            if (!siteUrl) {
                alert('请先输入网址');
                return;
            }
            
            button.prop('disabled', true).text('截图中...');
            
            $.ajax({
                url: foxnavAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'foxnav_auto_screenshot',
                    nonce: foxnavAdmin.nonce,
                    url: siteUrl
                },
                success: function(response) {
                    if (response.success && response.data.screenshot) {
                        $('#' + targetId).val(response.data.screenshot);
                        
                        // 更新预览
                        var preview = button.siblings('.foxnav-media-preview');
                        if (preview.length === 0) {
                            preview = $('<div class="foxnav-media-preview"></div>');
                            button.parent().append(preview);
                        }
                        preview.html('<img src="' + response.data.screenshot + '" style="max-width: 400px; margin-top: 10px;">');
                        
                        button.text('截图成功');
                    } else {
                        alert(response.data.message || '截图失败，请手动上传');
                        button.text('自动截图');
                    }
                },
                error: function() {
                    alert('截图失败，请手动上传');
                    button.text('自动截图');
                },
                complete: function() {
                    setTimeout(function() {
                        button.prop('disabled', false).text('自动截图');
                    }, 2000);
                }
            });
        });

        /**
         * 媒体预览更新
         */
        $('.foxnav-media-url').on('change', function() {
            var input = $(this);
            var url = input.val();
            var preview = input.siblings('.foxnav-media-preview');
            
            if (url && (url.match(/\.(jpeg|jpg|gif|png|webp)$/i) || url.includes('favicon'))) {
                if (preview.length === 0) {
                    preview = $('<div class="foxnav-media-preview"></div>');
                    input.parent().append(preview);
                }
                
                var maxWidth = input.attr('id').includes('favicon') ? '64px' : '400px';
                preview.html('<img src="' + url + '" style="max-width: ' + maxWidth + '; margin-top: 10px;">');
            } else if (preview.length > 0) {
                preview.remove();
            }
        });

        /**
         * URL验证提示
         */
        $('#site_url').on('blur', function() {
            var url = $(this).val();
            if (url && !url.match(/^https?:\/\//i)) {
                if (confirm('网址格式可能不正确，是否自动添加 https:// 前缀？')) {
                    $(this).val('https://' + url);
                }
            }
        });

    });

})(jQuery);









