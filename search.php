<?php
/**
 * 搜索结果页面模板
 *
 * @package FoxNav
 */

get_header();
?>
<?php include( TEMPLATEPATH . '/leftnav.php' ); ?>
	<div class="main-content flex-fill">
<?php include( TEMPLATEPATH . '/banner.php' ); ?>

		<div id="content" class="container container-fluid customize-width">
		<div class="search-results-header mb-4">
			<h1 class="page-title">
				<?php
				printf(
					esc_html__('搜索结果：%s', 'foxnav'),
					'<span class="search-keyword">' . get_search_query() . '</span>'
				);
				?>
			</h1>
			<?php if (have_posts()) : ?>
				<p class="search-results-count">
					<?php
					global $wp_query;
					printf(
						esc_html__('找到 %d 个结果', 'foxnav'),
						$wp_query->found_posts
					);
					?>
				</p>
			<?php endif; ?>
		</div>

		<?php if (have_posts()) : ?>
			<div class="search-results">
				<div class="row">
					<?php
					while (have_posts()) :
						the_post();
						$site_data = foxnav_get_site_data(get_the_ID());
						$site_url = $site_data['url'];
						$site_name = $site_data['name'] ?: get_the_title();
						$site_description = $site_data['description'];
						$site_favicon = $site_data['favicon'];
						$site_logo = $site_data['logo'];
						
						// 优先使用logo，其次favicon
						$image_url = $site_logo ?: $site_favicon;
						if (!$image_url) {
							$image_url = get_template_directory_uri() . '/static/images/ai.svg';
						}
					?>
					<div class="url-card col-6 col-2a col-sm-2a col-md-2a col-lg-3a col-xl-5a col-xxl-6a">
						<div class="url-body default">
							<a href="<?php echo esc_url($site_url); ?>" target="_blank" class="card no-c mb-4 site-<?php echo get_the_ID(); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr($site_description); ?>" <?php echo foxnav_get_link_attributes(get_the_ID()); ?>>
								<div class="card-body url-content d-flex align-items-center">
									<div class="url-img rounded-circle mr-2 d-flex align-items-center justify-content-center">
										<?php echo foxnav_lazy_img($image_url, $site_name, 'unfancybox'); ?>
									</div>
									<div class="url-info flex-fill">
										<div class="text-sm overflowClip_1">
											<strong><?php echo esc_html($site_name); ?></strong>
										</div>
										<p class="overflowClip_1 m-0 text-muted text-xs"><?php echo esc_html($site_description); ?></p>
									</div>
								</div>
							</a>
						</div>
					</div>
					<?php endwhile; ?>
				</div>

				<!-- 分页导航 -->
				<div class="pagination-wrapper mt-4">
					<?php
					the_posts_pagination([
						'mid_size' => 2,
						'prev_text' => __('&laquo; 上一页', 'foxnav'),
						'next_text' => __('下一页 &raquo;', 'foxnav'),
					]);
					?>
				</div>
			</div>

		<?php else : ?>
			<div class="no-results">
				<div class="alert alert-info">
					<h3><?php esc_html_e('没有找到相关结果', 'foxnav'); ?></h3>
					<p><?php esc_html_e('请尝试其他关键词搜索', 'foxnav'); ?></p>
					
					<div class="search-suggestions mt-4">
						<h4><?php esc_html_e('搜索建议：', 'foxnav'); ?></h4>
						<ul>
							<li><?php esc_html_e('检查关键词拼写是否正确', 'foxnav'); ?></li>
							<li><?php esc_html_e('尝试使用更常见的关键词', 'foxnav'); ?></li>
							<li><?php esc_html_e('尝试使用更少的关键词', 'foxnav'); ?></li>
						</ul>
					</div>

					<!-- 显示热门网址 -->
					<div class="popular-sites mt-4">
						<h4><?php esc_html_e('热门推荐：', 'foxnav'); ?></h4>
						<div class="row">
							<?php
							$popular_sites = foxnav_get_popular_sites(6);
							if ($popular_sites->have_posts()) :
								while ($popular_sites->have_posts()) : $popular_sites->the_post();
									$site_data = foxnav_get_site_data(get_the_ID());
									$site_url = $site_data['url'];
									$site_name = $site_data['name'] ?: get_the_title();
									$site_description = $site_data['description'];
									$site_favicon = $site_data['favicon'];
									$site_logo = $site_data['logo'];
									
									$image_url = $site_logo ?: $site_favicon;
									if (!$image_url) {
										$image_url = get_template_directory_uri() . '/static/images/ai.svg';
									}
							?>
							<div class="url-card col-6 col-2a col-sm-2a col-md-2a col-lg-3a col-xl-5a col-xxl-6a">
								<div class="url-body default">
									<a href="<?php echo esc_url($site_url); ?>" target="_blank" class="card no-c mb-4" <?php echo foxnav_get_link_attributes(get_the_ID()); ?>>
										<div class="card-body url-content d-flex align-items-center">
											<div class="url-img rounded-circle mr-2 d-flex align-items-center justify-content-center">
												<?php echo foxnav_lazy_img($image_url, $site_name, 'unfancybox'); ?>
											</div>
											<div class="url-info flex-fill">
												<div class="text-sm overflowClip_1">
													<strong><?php echo esc_html($site_name); ?></strong>
												</div>
												<p class="overflowClip_1 m-0 text-muted text-xs"><?php echo esc_html($site_description); ?></p>
											</div>
										</div>
									</a>
								</div>
							</div>
							<?php
								endwhile;
								wp_reset_postdata();
							endif;
							?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
		
		<!-- 底部信息区域 -->
		<div class="friendlink text-xs card" style="">
			<div class="footer-inner card rounded-xl m-0">
				<div class="footer-text card-body text-muted text-center text-md-left">
					<div class="row my-4">
						<div class="col-12 col-md-4 mb-4 mb-md-0">
							<a class="footer-logo" href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(foxnav_get_site_title()); ?>">
								<?php
								$logo = foxnav_get_logo();
								if ($logo) {
									echo '<img src="' . esc_url($logo) . '" class="logo-light mb-3" alt="' . esc_attr(foxnav_get_site_title()) . '" height="80">';
									echo '<img src="' . esc_url($logo) . '" class="logo-dark d-none mb-3" alt="' . esc_attr(foxnav_get_site_title()) . '" height="80">';
								} else {
									echo '<h3>' . esc_html(foxnav_get_site_title()) . '</h3>';
								}
								?>
							</a>
							<div class="text-sm"><?php echo esc_html(foxnav_get_site_description()); ?></div>
							<?php
							// 显示底部文字设置
							$bottom_text = foxnav_get_bottom_text();
							if (!empty($bottom_text)) :
							?>
							<div class="text-sm mt-2 text-muted"><?php echo wp_kses_post($bottom_text); ?></div>
							<?php endif; ?>
						</div>
						<div class="col-12 col-md-5 mb-4 mb-md-0">
							<div class="footer-social"></div>
						</div>
						<div class="col-12 col-md-3 text-md-right mb-4 mb-md-0">
							<?php
							// 获取底部图片设置
							$bottom_image_one = foxnav_get_bottom_image_one();
							$bottom_image_one_text = foxnav_get_bottom_image_one_text();
							$bottom_image_two = foxnav_get_bottom_image_two();
							$bottom_image_two_text = foxnav_get_bottom_image_two_text();
							
							// 如果设置了图片一，则显示
							if (!empty($bottom_image_one)) :
							?>
							<div class="footer-mini-img" data-toggle="tooltip" title="" data-original-title="<?php echo esc_attr($bottom_image_one_text); ?>">
								<p class="bg-light rounded-lg p-1">
									<img class=" " src="<?php echo esc_url($bottom_image_one); ?>" alt="<?php echo esc_attr($bottom_image_one_text); ?>">
								</p>
								<span class="text-muted text-ss mt-2"><?php echo esc_html($bottom_image_one_text); ?></span>
							</div>
							<?php endif; ?>
							
							<?php
							// 如果设置了图片二，则显示
							if (!empty($bottom_image_two)) :
							?>
							<div class="footer-mini-img" data-toggle="tooltip" title="" data-original-title="<?php echo esc_attr($bottom_image_two_text); ?>">
								<p class="bg-light rounded-lg p-1">
									<img class=" " src="<?php echo esc_url($bottom_image_two); ?>" alt="<?php echo esc_attr($bottom_image_two_text); ?>">
								</p>
								<span class="text-muted text-ss mt-2"><?php echo esc_html($bottom_image_two_text); ?></span>
							</div>
							<?php endif; ?>
							
							<?php
							// 如果两个图片都没有设置，显示默认图片
							if (empty($bottom_image_one) && empty($bottom_image_two)) :
							?>
							<div class="footer-mini-img" data-toggle="tooltip" title="" data-original-title="商务合作">
								<p class="bg-light rounded-lg p-1">
									<img class=" " src="<?php echo get_template_directory_uri(); ?>/static/picture/hzz.jpg" alt="商务合作">
								</p>
								<span class="text-muted text-ss mt-2">商务合作</span>
							</div>
							<div class="footer-mini-img" data-toggle="tooltip" title="" data-original-title="商务合作">
								<p class="bg-light rounded-lg p-1">
									<img class=" " src="<?php echo get_template_directory_uri(); ?>/static/picture/hzz.jpg" alt="商务合作">
								</p>
								<span class="text-muted text-ss mt-2">商务合作</span>
							</div>
							<?php endif; ?>
						</div>
					</div>
					<div class="footer-copyright text-xs">
						Copyright © <script>document.write((new Date()).getFullYear())</script>
						<a href="<?php echo esc_url(home_url('/')); ?>" class="text-info"><?php echo esc_html(foxnav_get_site_title()); ?></a> 
						All Rights Reserved
						<?php
						$contact = foxnav_get_contact_info();
						// 这里可以添加备案号等信息
						?>
					</div>
				</div>
			</div>
		</div>
		</div>
	</div>

<?php
get_footer();

