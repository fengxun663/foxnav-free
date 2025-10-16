<?php
/**
 * 主模板文件
 *
 * @package FoxNav
 */

get_header();
?>
<?php include( TEMPLATEPATH . '/leftnav.php' ); ?>
	<div class="main-content flex-fill">
<?php include( TEMPLATEPATH . '/banner.php' ); ?>

		<div id="content" class="container container-fluid customize-width">
					
			<div class="d-flex slider-menu-father mb-4">
				<div class="slider_menu mini_tab into" slidertab="sliderTab">
					<ul class="nav nav-pills tab-auto-scrollbar menu overflow-x-auto" role="tablist">
						<li class="anchor" style="position: absolute; width: 98.5px; height: 28px; left: 0px; opacity: 1;"></li>
						<li class="pagenumber nav-item">
							<a class="nav-link ajax-home-hot-list active load">
								<i class="fas fa-fire mr-2"></i>热门推荐
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="tab-content">
				<div id="ct-tab-sites-views0" class="tab-pane active">
					<div class="row  ajax-list-body position-relative">
						<?php
						// 获取热门网址（按点击量排序）
						$popular_sites = foxnav_get_popular_sites(12); // 显示12个热门网址
						
						// 调试信息
						error_log('=== 热门推荐调试信息 ===');
						error_log('Popular sites found count: ' . $popular_sites->found_posts);
						error_log('Popular sites post count: ' . $popular_sites->post_count);
						error_log('Have posts: ' . ($popular_sites->have_posts() ? 'YES' : 'NO'));
						
						if ($popular_sites->have_posts()) :
							while ($popular_sites->have_posts()) : $popular_sites->the_post();
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
						<div class="url-card col-6  col-2a col-sm-2a col-md-2a col-lg-3a col-xl-5a col-xxl-6a  ">
							<div class="url-body default ">
								<?php
								// 根据设置决定链接跳转方式
								$redirect_mode = foxnav_get_link_redirect_mode();
								$link_url = ($redirect_mode === 'direct') ? $site_url : get_permalink();
								$link_target = ($redirect_mode === 'direct') ? 'target="_blank"' : '';
								?>
								<a href="<?php echo esc_url($link_url); ?>" <?php echo $link_target; ?> class="card no-c  mb-4 site-<?php echo get_the_ID(); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr($site_description); ?>" <?php echo foxnav_get_link_attributes(get_the_ID()); ?>>
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
						else: 
						?>
							<!-- 无内容提示 -->
							<div class="col-12 text-center py-5">
								<div class="alert alert-info" role="alert">
									<i class="fas fa-info-circle mr-2"></i>
									<strong>暂无热门网址</strong>
									<p class="mb-0 mt-2">请先添加一些网址内容，或者等待网址获得点击量。</p>
									<?php if (current_user_can('manage_options')): ?>
										<a href="<?php echo admin_url('post-new.php?post_type=site'); ?>" class="btn btn-primary btn-sm mt-3">
											<i class="fas fa-plus mr-1"></i>添加网址
										</a>
									<?php endif; ?>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			
			
			<div class="content">
				<div class="content-wrap">
					<div class="content-layout">
						<?php
						// 获取所有顶级分类
						$categories = get_terms([
							'taxonomy' => 'site_category',
							'hide_empty' => false,
							'parent' => 0,
						]);

						// 按排序字段排序
						if (!empty($categories) && !is_wp_error($categories)) {
							usort($categories, function($a, $b) {
								$order_a = get_term_meta($a->term_id, 'category_order', true);
								$order_b = get_term_meta($b->term_id, 'category_order', true);
								$order_a = $order_a ? intval($order_a) : 0;
								$order_b = $order_b ? intval($order_b) : 0;
								return $order_a - $order_b;
							});

							// 循环输出每个分类
							foreach ($categories as $category) :
								$icon = get_term_meta($category->term_id, 'category_icon', true);
								$icon_class = $icon ? esc_attr($icon) : 'iconfont icon-tag';
								$category_link = get_term_link($category);
								
								// 查询该分类下的网址
								$sites_query = new WP_Query([
									'post_type' => 'site',
									'posts_per_page' => 24, // 每个分类显示24个
									'post_status' => 'publish',
									'tax_query' => [
										[
											'taxonomy' => 'site_category',
											'field' => 'term_id',
											'terms' => $category->term_id,
										],
									],
									'orderby' => 'date',
									'order' => 'DESC',
								]);

								// 如果该分类下有内容才显示
								if ($sites_query->have_posts()) :
						?>
						<!--分类循环-->						
						<div class="d-flex flex-fill align-items-center mb-4" id="nav-<?php echo esc_attr($category->term_id); ?>">
							<h4 class="text-gray text-lg m-0">
								<i class="site-tag <?php echo $icon_class; ?> icon-lg mr-1"></i> <?php echo esc_html($category->name); ?>
							</h4>
							<div class="flex-fill"></div>
							<a class="btn-move text-xs" href="<?php echo esc_url($category_link); ?>">更多 &gt;&gt;</a>
						</div>
						<!-- tab模式菜单 end -->
						<div class="tab-content mt-4">
							<div id="tab-<?php echo esc_attr($category->term_id); ?>" class="tab-pane active">
								<div class="row io-mx-n2 mt-4 ajax-list-body position-relative">
									<?php
									// 循环输出该分类下的网址
									while ($sites_query->have_posts()) : $sites_query->the_post();
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
									<div class="url-card col-6  col-2a col-sm-2a col-md-2a col-lg-3a col-xl-5a col-xxl-6a">
										<div class="url-body default ">
											<?php
											// 根据设置决定链接跳转方式
											$redirect_mode = foxnav_get_link_redirect_mode();
											$link_url = ($redirect_mode === 'direct') ? $site_url : get_permalink();
											$link_target = ($redirect_mode === 'direct') ? 'target="_blank"' : '';
											?>
											<a href="<?php echo esc_url($link_url); ?>" <?php echo $link_target; ?> class="card no-c  mb-4 site-<?php echo get_the_ID(); ?>" data-toggle="tooltip" data-placement="bottom" title="<?php echo esc_attr($site_description); ?>" <?php echo foxnav_get_link_attributes(get_the_ID()); ?>>
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
									<?php endwhile; wp_reset_postdata(); ?>
								</div>
							</div>
						</div>
						<?php
								endif; // 结束if have_posts
							endforeach; // 结束分类循环
						}
						?>
					</div>
				</div>
			</div>
			
			<!-- 友情链接区域 -->
			<h4 class="text-gray text-lg mb-4">
				<i class="iconfont icon-book-mark-line icon-lg mr-2" id="friendlink"></i>友情链接
			</h4>
			<div class="friendlink text-xs card">
				<div class="card-body">
					<?php
					// 获取友情链接
					$friendlinks = foxnav_get_friendlinks(10); // 获取前10个友情链接
					
					if ($friendlinks->have_posts()) :
						while ($friendlinks->have_posts()) : $friendlinks->the_post();
							$link_url = get_post_meta(get_the_ID(), '_friendlink_url', true);
							$link_description = get_post_meta(get_the_ID(), '_friendlink_description', true);
							$link_title = get_the_title();
							
							// 如果没有设置描述，使用标题
							$link_title = $link_description ?: $link_title;
							?>
							<a href="<?php echo esc_url($link_url); ?>" target="_blank" title="<?php echo esc_attr($link_title); ?>"><?php echo esc_html($link_title); ?></a>
							<?php
						endwhile;
						wp_reset_postdata();
					else :
						// 如果没有友情链接，显示默认的
						?>
						<a href="https://164pic.com/" target="_blank" title="云绘图库">云绘图库</a>
						<a href="https://www.hyaidh.com" target="_blank" title="会用AI导航">会用AI导航</a>
						<?php
					endif;
					?>
				</div>
			</div>
			
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
		</div>
	</div>
<?php
get_footer();
