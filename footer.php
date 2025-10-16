<?php
/**
 * 页脚模板
 *
 * @package FoxNav
 */
?>

				<footer>
					<div id="footer-tools" class="d-flex flex-column">
						<a href="javascript:" id="go-to-up" class="btn rounded-circle go-up m-1" rel="go-top">
							<i class="iconfont icon-to-up"></i>
						</a>
						<a href="javascript:" id="switch-mode" class="btn rounded-circle switch-dark-mode m-1" data-toggle="tooltip" data-placement="left" title="夜间模式">
							<i class="mode-ico iconfont icon-light"></i>
						</a>
					</div>
				</footer>
				<script type='text/javascript' src='<?php bloginfo('template_directory'); ?>/static/js/app.min.js' id='appjs-js'></script>
				
				<style>
				/* 左侧菜单活动状态样式 */
				.sidebar-item.active > a {
					color: var(--primary-color, #007bff) !important;
					font-weight: 600;
				}
				.sidebar-item.active > a::before {
					content: '';
					position: absolute;
					left: 0;
					top: 50%;
					transform: translateY(-50%);
					width: 3px;
					height: 80%;
					background-color: var(--primary-color, #007bff);
					border-radius: 0 2px 2px 0;
				}
				/* 平滑滚动过渡 */
				html {
					scroll-behavior: smooth;
				}
				</style>
				
				<script type='text/javascript'>
				jQuery(document).ready(function($) {
					// 平滑滚动到分类位置
					$('.scroll-to-section').on('click', function(e) {
						e.preventDefault();
						
						var targetId = $(this).attr('href');
						if (targetId && targetId.indexOf('#nav-') === 0) {
							var $target = $(targetId);
							
							if ($target.length) {
								// 计算滚动位置（考虑顶部固定导航栏的高度）
								var headerHeight = $('#header').outerHeight() || 80;
								var scrollPosition = $target.offset().top - headerHeight - 20;
								
								// 平滑滚动
								$('html, body').animate({
									scrollTop: scrollPosition
								}, 600, 'swing');
								
								// 高亮当前分类（可选）
								$('.sidebar-item').removeClass('active');
								$(this).closest('.sidebar-item').addClass('active');
							}
						}
					});
					
					// 也支持页面内其他的smooth类链接
					$('a.smooth[href^="#"]').not('.scroll-to-section').on('click', function(e) {
						var targetId = $(this).attr('href');
						if (targetId && targetId !== '#' && targetId !== '#!') {
							e.preventDefault();
							var $target = $(targetId);
							
							if ($target.length) {
								var headerHeight = $('#header').outerHeight() || 80;
								var scrollPosition = $target.offset().top - headerHeight - 20;
								
								$('html, body').animate({
									scrollTop: scrollPosition
								}, 600, 'swing');
							}
						}
					});
					
					// 页面加载时如果URL有锚点，自动滚动到对应位置
					if (window.location.hash) {
						var hash = window.location.hash;
						if (hash.indexOf('#nav-') === 0) {
							setTimeout(function() {
								var $target = $(hash);
								if ($target.length) {
									var headerHeight = $('#header').outerHeight() || 80;
									var scrollPosition = $target.offset().top - headerHeight - 20;
									
									$('html, body').animate({
										scrollTop: scrollPosition
									}, 600, 'swing');
								}
							}, 500); // 延迟500ms确保页面加载完成
						}
					}
				});
				</script>

<?php wp_footer(); ?>








