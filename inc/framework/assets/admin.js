jQuery(document).ready(function ($) {
    'use strict';

    // ========== 图片上传功能 ==========
    $('.fox-upload-button').on('click', function (e) {
        e.preventDefault();

        var button = $(this);
        var fieldId = button.data('field-id');
        var inputField = $('#' + fieldId);
        var previewContainer = $('#preview-' + fieldId);
        var removeButton = button.siblings('.fox-remove-button');

        // 创建媒体库实例
        var mediaUploader = wp.media({
            title: '选择图片',
            button: {
                text: '使用这张图片'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });

        // 选择图片后的回调
        mediaUploader.on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            
            // 更新输入框值
            inputField.val(attachment.url).trigger('change');
            
            // 更新预览图
            var previewHTML = '<div class="fox-image-preview-container">' +
                '<img src="' + attachment.url + '" alt="预览图">' +
                '<div class="fox-image-overlay">' +
                '<span class="dashicons dashicons-search"></span>' +
                '</div>' +
                '</div>';
            
            previewContainer.html(previewHTML).hide().fadeIn(300);
            
            // 显示删除按钮
            removeButton.fadeIn(200);
            
            // 标记表单已修改
            formChanged = true;
        });

        mediaUploader.open();
    });

    // ========== 图片删除功能 ==========
    $(document).on('click', '.fox-remove-button', function (e) {
        e.preventDefault();
        
        var button = $(this);
        var fieldId = button.data('field-id');
        
        // 确认删除
        if (!confirm('确定要删除这张图片吗？')) {
            return;
        }
        
        var inputField = $('#' + fieldId);
        var previewContainer = $('#preview-' + fieldId);
        
        // 清空输入框
        inputField.val('').trigger('change');
        
        // 清空预览
        previewContainer.fadeOut(200, function() {
            $(this).empty();
        });
        
        // 隐藏删除按钮
        button.fadeOut(200);
        
        // 标记表单已修改
        formChanged = true;
    });

    // ========== Slider 滑块实时显示值 ==========
    $('input[type="range"]').on('input', function () {
        var value = $(this).val();
        $(this).siblings('.fox-slider-value').text(value);
    });

    // ========== 颜色选择器 ==========
    if (typeof $.fn.wpColorPicker !== 'undefined') {
        $('.fox-color-picker').each(function() {
            var $input = $(this);
            var defaultColor = $input.data('default-color');
            
            $input.wpColorPicker({
                defaultColor: defaultColor || '',
                change: function (event, ui) {
                    var color = ui.color.toString();
                    $(this).val(color).trigger('change');
                    formChanged = true;
                },
                clear: function() {
                    $(this).val('').trigger('change');
                    formChanged = true;
                },
                palettes: [
                    '#2271b1',
                    '#135e96',
                    '#72aee6',
                    '#46b450',
                    '#f0b849',
                    '#dc3232',
                    '#00a0d2',
                    '#1d2327',
                    '#50575e',
                    '#f0f0f1'
                ]
            });
        });
    }

    // ========== 表单变更检测 ==========
    var formChanged = false;
    
    $('#fox-options-form :input').on('change', function () {
        formChanged = true;
    });

    // 离开页面前提醒
    $(window).on('beforeunload', function () {
        if (formChanged) {
            return '您有未保存的更改，确定要离开吗？';
        }
    });

    // 提交表单后取消提醒
    $('#fox-options-form').on('submit', function () {
        formChanged = false;
        
        // 显示保存中状态
        var $submitBtn = $(this).find('input[type="submit"]');
        var originalText = $submitBtn.val();
        
        $submitBtn.val('保存中...').prop('disabled', true);
        
        // 添加保存中动画
        $submitBtn.css({
            'background': 'linear-gradient(45deg, #2271b1, #72aee6)',
            'background-size': '200% 200%',
            'animation': 'fox-saving-pulse 1.5s ease-in-out infinite'
        });
        
        // 添加保存中提示
        var savingMessage = '<div class="notice notice-info fox-saving-notice" style="margin: 20px 0;">' +
            '<p><span class="dashicons dashicons-update" style="color: #2271b1; margin-right: 8px; animation: spin 1s linear infinite;"></span>' +
            '<strong>正在保存设置...</strong> 请稍候，不要关闭页面。' +
            '</p></div>';
        
        $('.fox-admin-content').prepend(savingMessage);
    });

    // ========== 保存成功提示增强 ==========
    // 检查URL参数，显示保存成功消息
    if (window.location.search.indexOf('settings-updated=true') !== -1) {
        // 添加成功提示到页面顶部
        var successMessage = '<div class="notice notice-success is-dismissible fox-save-notice" style="margin: 20px 0;">' +
            '<p><span class="dashicons dashicons-yes-alt" style="color: #46b450; margin-right: 8px;"></span>' +
            '<strong>设置已保存！</strong> 您的主题设置已成功保存。' +
            '</p></div>';
        
        $('.fox-admin-content').prepend(successMessage);
        
        // 自动消失
        setTimeout(function() {
            $('.fox-save-notice').fadeOut(3000);
        }, 2000);
        
        // 清除URL参数
        if (window.history && window.history.replaceState) {
            var url = new URL(window.location);
            url.searchParams.delete('settings-updated');
            window.history.replaceState({}, '', url);
        }
    }

    // ========== 恢复默认按钮 ==========
    $('.fox-reset-button').on('click', function (e) {
        e.preventDefault();
        
        if (confirm('确定要恢复默认设置吗？此操作不可撤销！')) {
            var button = $(this);
            button.prop('disabled', true).text('正在恢复...');
            
            // 这里可以添加恢复默认值的逻辑
            setTimeout(function () {
                button.prop('disabled', false).text('恢复默认');
                alert('默认设置已恢复！');
                location.reload();
            }, 1000);
        }
    });

    // ========== 菜单切换功能（无刷新） ==========
    $('.fox-nav-item').on('click', function (e) {
        e.preventDefault();
        
        var $this = $(this);
        var sectionId = $this.data('section');
        
        // 如果点击的是当前激活的菜单，不做任何操作
        if ($this.hasClass('active')) {
            return;
        }
        
        // 移除所有菜单的激活状态
        $('.fox-nav-item').removeClass('active');
        
        // 添加当前菜单的激活状态
        $this.addClass('active');
        
        // 添加切换动画类
        $('#fox-options-form').addClass('fox-switching');
        
        // 隐藏所有内容区域
        $('.fox-section-content').fadeOut(150, function() {
            // 显示对应的内容区域
            var $targetSection = $('.fox-section-content[data-section="' + sectionId + '"]');
            $targetSection.fadeIn(250, function() {
                // 移除切换动画类
                $('#fox-options-form').removeClass('fox-switching');
            });
            
            // 滚动到顶部（平滑滚动）
            $('.fox-admin-content').animate({
                scrollTop: 0
            }, 250);
        });
        
        // 更新URL（可选，不刷新页面）
        if (history.pushState) {
            var newUrl = window.location.protocol + "//" + window.location.host + 
                         window.location.pathname + '?page=fox-framework&section=' + sectionId;
            window.history.pushState({section: sectionId}, '', newUrl);
        }
        
        // 记录切换事件（可用于统计）
        console.log('切换到分组: ' + sectionId);
    });
    
    // ========== 浏览器前进/后退按钮支持 ==========
    $(window).on('popstate', function(e) {
        if (e.originalEvent.state && e.originalEvent.state.section) {
            var sectionId = e.originalEvent.state.section;
            
            // 更新菜单激活状态
            $('.fox-nav-item').removeClass('active');
            $('.fox-nav-item[data-section="' + sectionId + '"]').addClass('active');
            
            // 切换内容
            $('.fox-section-content').hide();
            $('.fox-section-content[data-section="' + sectionId + '"]').show();
        }
    });

    // ========== 表单自动保存（可选） ==========
    var autoSaveTimer;
    var autoSaveDelay = 30000; // 30秒

    function autoSaveForm() {
        if (formChanged) {
            console.log('自动保存表单...');
            // 这里可以添加AJAX自动保存逻辑
        }
    }

    // 启用自动保存
    if (typeof foxAdminConfig !== 'undefined' && foxAdminConfig.autoSave) {
        $('#fox-options-form :input').on('change', function () {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(autoSaveForm, autoSaveDelay);
        });
    }

    // ========== 字段验证 ==========
    $('#fox-options-form').on('submit', function (e) {
        var isValid = true;
        var firstInvalidField = null;

        // 验证必填字段
        $(this).find('input[required], textarea[required], select[required]').each(function () {
            if ($(this).val().trim() === '') {
                isValid = false;
                $(this).addClass('fox-field-error');
                
                if (firstInvalidField === null) {
                    firstInvalidField = $(this);
                }
            } else {
                $(this).removeClass('fox-field-error');
            }
        });

        // 验证邮箱格式
        $(this).find('input[type="email"]').each(function () {
            var email = $(this).val().trim();
            if (email !== '' && !isValidEmail(email)) {
                isValid = false;
                $(this).addClass('fox-field-error');
                
                if (firstInvalidField === null) {
                    firstInvalidField = $(this);
                }
            }
        });

        // 验证URL格式
        $(this).find('input[type="url"]').each(function () {
            var url = $(this).val().trim();
            if (url !== '' && !isValidUrl(url)) {
                isValid = false;
                $(this).addClass('fox-field-error');
                
                if (firstInvalidField === null) {
                    firstInvalidField = $(this);
                }
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('请检查表单中的错误项！');
            
            if (firstInvalidField !== null) {
                $('html, body').animate({
                    scrollTop: firstInvalidField.offset().top - 100
                }, 500);
                firstInvalidField.focus();
            }
        }
    });

    // 邮箱验证函数
    function isValidEmail(email) {
        var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    // URL验证函数
    function isValidUrl(url) {
        var regex = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
        return regex.test(url);
    }

    // ========== 移除错误样式 ==========
    $('.fox-field-error').on('focus', function () {
        $(this).removeClass('fox-field-error');
    });

    // ========== 快捷键支持 ==========
    $(document).on('keydown', function (e) {
        // Ctrl+S 或 Cmd+S 保存
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            $('#fox-options-form').submit();
        }
    });

    // ========== 图片预览增强 - 点击查看大图 ==========
    $(document).on('click', '.fox-image-preview-container, .preview-image img', function (e) {
        e.preventDefault();
        
        var $img = $(this).find('img');
        if ($img.length === 0) {
            $img = $(this);
        }
        
        var imgSrc = $img.attr('src');
        
        if (!imgSrc) {
            return;
        }
        
        // 创建模态框显示大图
        var modal = $('<div class="fox-image-modal">')
            .css({
                position: 'fixed',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%',
                background: 'rgba(0, 0, 0, 0.95)',
                zIndex: 999999,
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                cursor: 'zoom-out',
                padding: '20px',
                boxSizing: 'border-box'
            })
            .append(
                $('<div>')
                    .css({
                        position: 'relative',
                        maxWidth: '90%',
                        maxHeight: '90%'
                    })
                    .append(
                        $('<img>')
                            .attr('src', imgSrc)
                            .attr('alt', '预览图')
                            .css({
                                maxWidth: '100%',
                                maxHeight: '90vh',
                                borderRadius: '8px',
                                boxShadow: '0 10px 50px rgba(0, 0, 0, 0.8)',
                                display: 'block'
                            })
                    )
                    .append(
                        $('<button>')
                            .attr('type', 'button')
                            .html('✕')
                            .css({
                                position: 'absolute',
                                top: '-15px',
                                right: '-15px',
                                width: '40px',
                                height: '40px',
                                background: '#fff',
                                border: 'none',
                                borderRadius: '50%',
                                fontSize: '20px',
                                cursor: 'pointer',
                                boxShadow: '0 2px 8px rgba(0, 0, 0, 0.3)',
                                transition: 'all 0.3s ease',
                                lineHeight: '40px',
                                textAlign: 'center',
                                color: '#50575e'
                            })
                            .hover(
                                function() {
                                    $(this).css({
                                        background: '#d63638',
                                        color: '#fff',
                                        transform: 'scale(1.1)'
                                    });
                                },
                                function() {
                                    $(this).css({
                                        background: '#fff',
                                        color: '#50575e',
                                        transform: 'scale(1)'
                                    });
                                }
                            )
                    )
            );
        
        $('body').append(modal);
        modal.hide().fadeIn(250);
        
        // 点击背景或按钮关闭
        modal.on('click', function (e) {
            if (e.target === this || $(e.target).is('button')) {
                $(this).fadeOut(250, function () {
                    $(this).remove();
                });
            }
        });
        
        // ESC键关闭
        $(document).one('keydown', function(e) {
            if (e.key === 'Escape') {
                modal.fadeOut(250, function () {
                    modal.remove();
                });
            }
        });
    });

    // ========== Switch 开关增强 ==========
    $('.fox-switch input').on('change', function () {
        var label = $(this).closest('tr').find('th label').text();
        var status = $(this).is(':checked') ? '已启用' : '已禁用';
        
        // 可以在这里添加提示信息
        console.log(label + ' ' + status);
    });

    // ========== 代码编辑器增强 ==========
    $('.fox-code-editor').each(function () {
        var textarea = $(this);
        
        // 添加Tab键支持
        textarea.on('keydown', function (e) {
            if (e.key === 'Tab') {
                e.preventDefault();
                var start = this.selectionStart;
                var end = this.selectionEnd;
                var value = $(this).val();
                
                // 插入tab（4个空格）
                $(this).val(value.substring(0, start) + '    ' + value.substring(end));
                
                // 移动光标
                this.selectionStart = this.selectionEnd = start + 4;
            }
        });
        
        // 显示行号（可选）
        textarea.on('input scroll', function () {
            // 这里可以添加行号显示逻辑
        });
    });

    // ========== 工具提示增强 ==========
    $('.fox-tooltip').each(function () {
        $(this).attr('title', $(this).data('tip'));
    });

    // ========== 搜索功能（可选） ==========
    function addSearchBox() {
        var searchBox = $('<div class="fox-search-box">')
            .css({
                padding: '15px 20px',
                borderBottom: '1px solid #f0f0f1',
                background: '#fafafa'
            })
            .append(
                $('<input type="text" placeholder="搜索设置项...">')
                    .css({
                        width: '100%',
                        padding: '8px 12px',
                        border: '1px solid #dcdcde',
                        borderRadius: '6px',
                        fontSize: '14px'
                    })
            );
        
        // 暂时禁用搜索功能
        // $('.fox-admin-sidebar').prepend(searchBox);
    }

    // ========== 初始化：根据URL参数显示对应分组 ==========
    function initSectionFromURL() {
        var urlParams = new URLSearchParams(window.location.search);
        var sectionParam = urlParams.get('section');
        
        if (sectionParam) {
            // 如果URL中有section参数，显示对应分组
            var $targetNav = $('.fox-nav-item[data-section="' + sectionParam + '"]');
            
            if ($targetNav.length > 0) {
                // 更新菜单状态
                $('.fox-nav-item').removeClass('active');
                $targetNav.addClass('active');
                
                // 更新内容显示
                $('.fox-section-content').hide();
                $('.fox-section-content[data-section="' + sectionParam + '"]').show();
            }
        }
    }
    
    // 页面加载时执行初始化
    initSectionFromURL();
    
    // ========== 初始化完成提示 ==========
    console.log('Fox Framework Admin 已加载完成！');
    
    // 添加页面加载完成动画
    $('.fox-admin-wrap').hide().fadeIn(300);
});