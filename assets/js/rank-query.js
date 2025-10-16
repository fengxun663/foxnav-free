/**
 * 网站权重查询功能
 * 
 * @package FoxNav
 */

(function($) {
    'use strict';

    // 权重查询类
    var RankQuery = {
        init: function() {
            this.bindEvents();
            this.autoQueryRanks();
        },

        bindEvents: function() {
            // 点击权重图标时查询
            $(document).on('click', '.seo-rank-item', function() {
                var $this = $(this);
                var rankType = $this.data('rank');
                var domain = $this.data('domain');
                var siteId = $this.data('site-id');

                if (rankType && domain && siteId) {
                    RankQuery.queryRank(rankType, domain, siteId, $this);
                }
            });
        },

        autoQueryRanks: function() {
            // 页面加载后自动查询权重
            $('.seo-rank-item').each(function() {
                var $this = $(this);
                var rankType = $this.data('rank');
                var domain = $this.data('domain');
                var siteId = $this.data('site-id');

                if (rankType && domain && siteId) {
                    // 延迟查询，避免同时请求过多
                    setTimeout(function() {
                        RankQuery.queryRank(rankType, domain, siteId, $this);
                    }, Math.random() * 2000);
                }
            });
        },

        queryRank: function(rankType, domain, siteId, $element) {
            var $img = $element.find('img');
            var $text = $element.find('.rank-text');
            
            // 显示加载状态
            $img.attr('src', foxnavData.themeUrl + '/static/images/loading.gif');
            $text.text('查询中...');
            $element.addClass('loading');

            $.ajax({
                url: foxnavData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'foxnav_query_rank',
                    nonce: foxnavData.nonce,
                    domain: domain,
                    rank_type: rankType,
                    site_id: siteId
                },
                success: function(response) {
                    if (response.success) {
                        RankQuery.updateRankDisplay($element, response.data);
                    } else {
                        RankQuery.showError($element, response.data.message);
                    }
                },
                error: function() {
                    RankQuery.showError($element, '网络错误');
                }
            });
        },

        updateRankDisplay: function($element, data) {
            var $img = $element.find('img');
            var $text = $element.find('.rank-text');
            var rankType = $element.data('rank');
            var rank = data.rank || 0;

            // 更新图片
            var imgPath = this.getRankImagePath(rankType, rank);
            $img.attr('src', imgPath);
            $img.attr('alt', this.getRankTypeName(rankType) + '权重: ' + rank);
            $img.attr('title', this.getRankTypeName(rankType) + '权重: ' + rank);
            
            // 移除初始logo的大小限制类
            $img.removeClass('rank-logo-init');

            // 隐藏文字，只显示图片
            $text.hide();

            // 更新状态
            $element.removeClass('loading');
            $element.addClass('loaded');

            // 根据权重设置颜色
            this.setRankColor($element, rank);
        },

        showError: function($element, message) {
            var $img = $element.find('img');
            var $text = $element.find('.rank-text');
            var rankType = $element.data('rank');

            // 显示错误状态（回退到logo图片）
            var logoPath = this.getRankLogoPath(rankType);
            $img.attr('src', logoPath);
            $img.attr('alt', '查询失败');
            $img.attr('title', message);
            
            // 隐藏文字
            $text.hide();

            $element.removeClass('loading');
            $element.addClass('error');
        },
        
        getRankLogoPath: function(rankType) {
            var basePath = foxnavData.themeUrl + '/static/images/';
            
            switch (rankType) {
                case 'baidu':
                    return basePath + 'br/logo.png';
                case '360':
                    return basePath + '360/logo.png';
                case 'shenma':
                    return basePath + 'sm/logo.png';
                case 'sogou':
                    return basePath + 'sg/logo.png';
                default:
                    return basePath + 'br/logo.png';
            }
        },

        getRankImagePath: function(rankType, rank) {
            var basePath = foxnavData.themeUrl + '/static/images/';
            var rankNum = Math.min(Math.max(rank, 0), 9); // 限制在0-9之间

            switch (rankType) {
                case 'baidu':
                    return basePath + 'br/' + rankNum + '.png';
                case '360':
                    return basePath + '360/' + rankNum + '.png';
                case 'shenma':
                    return basePath + 'sm/' + rankNum + '.png';
                case 'sogou':
                    return basePath + 'sg/' + rankNum + '.png';
                default:
                    return basePath + 'br/0.png';
            }
        },

        getRankTypeName: function(rankType) {
            var names = {
                'baidu': '百度',
                '360': '360',
                'shenma': '神马',
                'sogou': '搜狗'
            };
            return names[rankType] || '未知';
        },

        setRankColor: function($element, rank) {
            $element.removeClass('rank-0 rank-1 rank-2 rank-3 rank-4 rank-5 rank-6 rank-7 rank-8 rank-9');
            
            if (rank >= 7) {
                $element.addClass('rank-high');
            } else if (rank >= 4) {
                $element.addClass('rank-medium');
            } else if (rank >= 1) {
                $element.addClass('rank-low');
            } else {
                $element.addClass('rank-0');
            }
        }
    };

    // 页面加载完成后初始化
    $(document).ready(function() {
        RankQuery.init();
    });

    // 暴露到全局
    window.FoxNavRankQuery = RankQuery;

})(jQuery);
