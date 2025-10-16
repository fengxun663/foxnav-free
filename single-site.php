<?php
/**
 * 网址详情页模板
 *
 * @package FoxNav
 */

get_header();
?>
<?php include( TEMPLATEPATH . '/leftnav.php' ); ?>
	<div class="main-content flex-fill">
<?php include( TEMPLATEPATH . '/banner.php' ); ?>

<?php while (have_posts()) : the_post(); ?>
	<?php
	// 获取网址数据
	$site_data = foxnav_get_site_data(get_the_ID());
	$link_attrs = foxnav_get_link_attributes(get_the_ID());
	?>

	<div id="content" class="container my-4 my-md-5">
		<div class="row site-content py-4 py-md-5 mb-xl-5 mb-0 mx-xxxl-n5">
			<!-- 网址信息 -->
			<div class="col-12 col-sm-5 col-md-4 col-lg-3">
				<div class="siteico">
					<?php
					// 图片调用优先级：特色图片 > 网站截图 > 网站logo > 默认图片
					$display_image = '';
					$image_alt = $site_data['name'] ?: get_the_title();
					
					// 1. 优先使用特色图片
					if (has_post_thumbnail()) {
						$display_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
					}
					// 2. 其次使用网站截图
					elseif ($site_data['screenshot']) {
						$display_image = $site_data['screenshot'];
					}
					// 3. 再次使用网站logo
					elseif ($site_data['logo']) {
						$display_image = $site_data['logo'];
					}
					// 4. 最后使用默认图片
					else {
						$display_image = get_template_directory_uri() . '/static/images/ai.svg';
					}
					?>
				<?php echo foxnav_lazy_img($display_image, $image_alt, 'img-cover unfancybox loaded'); ?>
					<div class="tool-actions text-center mt-md-4">
						<a href="javascript:;" onclick="add_fav(2,<?php echo get_the_ID(); ?>);" class="btn btn-like btn-icon btn-light rounded-circle p-2 mx-3 mx-md-2" data-original-title="收藏">
							<span class="flex-column text-height-xs">
								<i class="icon-lg iconfont icon-heart"></i>
								<small class="like-count text-xs mt-1"><?php echo $site_data['favorites']; ?></small>
							</span>
						</a>
						<a href="javascript:;" class="btn-share-toggler btn btn-icon btn-light rounded-circle p-2 mx-3 mx-md-2" data-toggle="tooltip" data-placement="top" title="" data-original-title="浏览">
							<span class="flex-column text-height-xs">
								<i class="icon-lg iconfont icon-chakan"></i>
								<small class="share-count text-xs mt-1"><?php echo $site_data['clicks']; ?></small>
							</span>
						</a>
					</div>
				</div>
			</div>
			<div class="col mt-4 mt-sm-0">
				<div class="site-body text-sm">
					<h1 class="site-name h3 my-3"><?php echo esc_html($site_data['name'] ?: get_the_title()); ?></h1>
					<div class="mt-2">
						<?php if ($site_data['description']): ?>
							<p class="mb-2"><?php echo esc_html($site_data['description']); ?></p>
						<?php endif; ?>
						<div class="mt-2 sites-seo-load">
							<?php
							// 获取网站域名用于权重查询
							$site_domain = foxnav_get_site_domain(get_the_ID());
							$site_id = get_the_ID();
							?>
							<!-- 百度权重 -->
							<div class="seo-rank-item" data-rank="baidu" data-domain="<?php echo esc_attr($site_domain); ?>" data-site-id="<?php echo $site_id; ?>">
							<img class="mt-1 rank-logo-init" src="<?php bloginfo('template_directory'); ?>/static/images/br/logo.png" id="br_<?php echo $site_id; ?>" alt="百度权重" title="百度权重查询中...">
								<span class="rank-text">百度</span>
							</div>
							
							<!-- 360权重 -->
							<div class="seo-rank-item" data-rank="360" data-domain="<?php echo esc_attr($site_domain); ?>" data-site-id="<?php echo $site_id; ?>">
							<img class="mt-1 rank-logo-init" src="<?php bloginfo('template_directory'); ?>/static/images/360/logo.png" id="pr360_<?php echo $site_id; ?>" alt="360权重" title="360权重查询中...">
								<span class="rank-text">360</span>
							</div>
							
							<!-- 神马权重 -->
							<div class="seo-rank-item" data-rank="shenma" data-domain="<?php echo esc_attr($site_domain); ?>" data-site-id="<?php echo $site_id; ?>">
							<img class="mt-1 rank-logo-init" src="<?php bloginfo('template_directory'); ?>/static/images/sm/logo.png" id="pr_<?php echo $site_id; ?>" alt="神马权重" title="神马权重查询中...">
								<span class="rank-text">神马</span>
							</div>
							
							<!-- 搜狗权重 -->
							<div class="seo-rank-item" data-rank="sogou" data-domain="<?php echo esc_attr($site_domain); ?>" data-site-id="<?php echo $site_id; ?>">
							<img class="mt-1 rank-logo-init" src="<?php bloginfo('template_directory'); ?>/static/images/sg/logo.png" id="sg_<?php echo $site_id; ?>" alt="搜狗权重" title="搜狗权重查询中...">
								<span class="rank-text">搜狗</span>
							</div>
						</div>
						<div class="site-go mt-3">
							<div id="security_check_img"></div>
							<span class="site-go-url">
								<?php if ($site_data['url']): ?>
									<a href="<?php echo esc_url($site_data['url']); ?>" title="<?php echo esc_attr($site_data['name'] ?: get_the_title()); ?>" onclick="clickout(<?php echo get_the_ID(); ?>);" target="_blank" class="btn btn-arrow mr-2" <?php echo $link_attrs; ?>>
										<span>链接直达
											<i class="iconfont icon-arrow-r-m"></i>
										</span>
									</a>
								<?php endif; ?>
							</span>

							<a href="javascript:;" class="btn btn-danger qr-img tooltip-toggle rounded-lg" onclick="layer.msg('请登录');">
								<i class="iconfont icon-statement icon-lg"></i>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-12 col-lg-4 mt-4 mt-lg-0">
				<div class="apd apd-right">
				<?php
					// 获取主题设置中的广告配置
					$ad_image = foxnav_get_option('single_page_ad_image', '');
					$ad_link = foxnav_get_option('single_page_ad_link', '');
					$ad_title = foxnav_get_option('single_page_ad_title', '广告');
					
					if ($ad_image && $ad_link):
					?>
						<a href="<?php echo esc_url($ad_link); ?>" rel="nofollow sponsored" target="_blank" style="position: relative;">
							<span style="position: absolute; left: 4px; top: 4px; color: white !important; background: rgba(0,0,0,0.5); padding: 2px 8px; border-radius: 3px; font-size: 12px;">
								<i class="fas fa-ad"></i> 广告
							</span>
							<img class="rounded" src="<?php echo esc_url($ad_image); ?>" width="100%" alt="<?php echo esc_attr($ad_title); ?>">
						</a>
					<?php elseif ($ad_image): ?>
						<div style="position: relative;">
							<span style="position: absolute; left: 4px; top: 4px; color: white !important; background: rgba(0,0,0,0.5); padding: 2px 8px; border-radius: 3px; font-size: 12px;">
								<i class="fas fa-ad"></i> 广告
						</span>
							<img class="rounded" src="<?php echo esc_url($ad_image); ?>" width="100%" alt="<?php echo esc_attr($ad_title); ?>">
						</div>
					<?php else: ?>
						<!-- 默认广告位占位 -->
						<div class="ad-placeholder text-center p-4" style="background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px;">
							<i class="fas fa-ad fa-3x text-muted mb-3"></i>
							<p class="text-muted mb-0">广告位</p>
							<?php if (current_user_can('manage_options')): ?>
								<a href="<?php echo admin_url('admin.php?page=fox-framework'); ?>" class="btn btn-sm btn-primary mt-2">
									设置广告
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<main class="content" role="main">
			<div class="content-wrap">
				<div class="content-layout">
					<div class="panel site-content card transparent">
						<div class="card-body p-0">
							<div class="panel-body single my-4">
								<h2>网站描述：</h2>
								<?php if (get_the_content()): ?>
									<div class="site-description-content">
										<?php the_content(); ?>
									</div>
								<?php elseif ($site_data['description']): ?>
									<div class="site-description-content">
										<p><?php echo wp_kses_post(wpautop($site_data['description'])); ?></p>
									</div>
								<?php else: ?>
									<p class="text-muted">暂无详细描述</p>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="alert alert-warning text-sm">
						©️版权声明：非特殊说明，本站 AI导航网上的教程均由本站作者及会员发布，原作品版权归属原作者，本站只做收藏展示。
					</div>
					
					<!-- 相关网站推荐 -->
					<?php
					$related_sites = foxnav_get_related_sites(get_the_ID(), 6);
					if ($related_sites->have_posts()):
					?>
					<h2 class="text-gray text-lg my-4">
						<i class="site-tag iconfont icon-tag icon-lg mr-1"></i>类似网站
					</h2>
					<div class="row mb-n4">
						<?php while ($related_sites->have_posts()): $related_sites->the_post(); ?>
							<?php
							$related_site_data = foxnav_get_site_data(get_the_ID());
							$related_link_attrs = foxnav_get_link_attributes(get_the_ID());
							?>
							<div class="col-6 col-sm-4 col-md-3 col-lg-2 mb-4">
								<div class="url-card">
									<div class="url-body default">
										<?php
										$redirect_mode = foxnav_get_link_redirect_mode();
										$link_url = ($redirect_mode === 'direct') ? $related_site_data['url'] : get_permalink();
										$link_target = ($redirect_mode === 'direct') ? 'target="_blank"' : '';
										?>
										<a href="<?php echo esc_url($link_url); ?>" <?php echo $link_target; ?> class="card no-c mb-4 site-<?php echo get_the_ID(); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr($related_site_data['description']); ?>" <?php echo $related_link_attrs; ?>>
											<div class="card-body url-content d-flex align-items-center">
												<div class="url-img rounded-circle mr-2 d-flex align-items-center justify-content-center">
													<?php
													// 相关网站图片调用优先级：特色图片 > 网站截图 > 网站logo > 网站favicon > 默认图片
													$related_image_url = '';
													$related_image_alt = $related_site_data['name'] ?: get_the_title();
													
													// 1. 优先使用特色图片
													if (has_post_thumbnail()) {
														$related_image_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
													}
													// 2. 其次使用网站截图
													elseif ($related_site_data['screenshot']) {
														$related_image_url = $related_site_data['screenshot'];
													}
													// 3. 再次使用网站logo
													elseif ($related_site_data['logo']) {
														$related_image_url = $related_site_data['logo'];
													}
													// 4. 然后使用网站favicon
													elseif ($related_site_data['favicon']) {
														$related_image_url = $related_site_data['favicon'];
													}
													// 5. 最后使用默认图片
													else {
														$related_image_url = get_template_directory_uri() . '/static/images/ai.svg';
													}
												echo foxnav_lazy_img($related_image_url, $related_image_alt, 'unfancybox');
													?>
												</div>
												<div class="url-info flex-fill">
													<div class="text-sm overflowClip_1">
														<strong><?php echo esc_html($related_site_data['name'] ?: get_the_title()); ?></strong>
													</div>
													<p class="overflowClip_1 m-0 text-muted text-xs"><?php echo esc_html($related_site_data['description']); ?></p>
												</div>
											</div>
										</a>
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
					<?php 
					wp_reset_postdata();
					endif; ?>
				</div>
			</div>
		</main>
	</div>
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
										<img class=" " src="static/picture/hzz.jpg" alt="商务合作AI导航猫-AI导航网_AI工具箱_AI网站大全_AI绘画网站站">
									</p>
									<span class="text-muted text-ss mt-2">商务合作</span>
								</div>
								<div class="footer-mini-img" data-toggle="tooltip" title="" data-original-title="商务合作">
									<p class="bg-light rounded-lg p-1">
										<img class=" " src="static/picture/hzz.jpg" alt="商务合作AI导航猫-AI导航网_AI工具箱_AI网站大全_AI绘画网站">
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
<?php endwhile; ?>
<?php
get_footer();
