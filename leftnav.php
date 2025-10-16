		<div id="sidebar" class="sticky sidebar-nav fade">
			<div class="modal-dialog h-100  sidebar-nav-inner">
				<div class="sidebar-logo border-bottom border-color">
					<div class="logo overflow-hidden">
						<h1 class="text-hide position-absolute"><?php bloginfo('name'); ?></h1>
						<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name'); ?>" class="logo-expanded">
							<?php
							$logo = foxnav_get_logo();
							$logo_light = foxnav_get_logo('light');
							$logo_dark = foxnav_get_logo('dark');
							
							if ($logo_light) {
								echo '<img src="' . esc_url($logo_light) . '" height="80" class="logo-light" alt="' . esc_attr(foxnav_get_site_title()) . '">';
							} elseif ($logo) {
								echo '<img src="' . esc_url($logo) . '" height="80" class="logo-light" alt="' . esc_attr(foxnav_get_site_title()) . '">';
							} else {
								echo '<h3 class="logo-text">' . esc_html(foxnav_get_site_title()) . '</h3>';
							}
							
							if ($logo_dark) {
								echo '<img src="' . esc_url($logo_dark) . '" height="80" class="logo-dark d-none" alt="' . esc_attr(foxnav_get_site_title()) . '">';
							} elseif ($logo) {
								echo '<img src="' . esc_url($logo) . '" height="80" class="logo-dark d-none" alt="' . esc_attr(foxnav_get_site_title()) . '">';
							}
							?>
						</a>
						<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php bloginfo('name'); ?>" class="logo-collapsed">
							<?php
							$square_logo = foxnav_get_logo('square');
							
							if ($square_logo) {
								echo '<img src="' . esc_url($square_logo) . '" height="40" class="logo-light" alt="' . esc_attr(foxnav_get_site_title()) . '">';
								echo '<img src="' . esc_url($square_logo) . '" height="40" class="logo-dark d-none" alt="' . esc_attr(foxnav_get_site_title()) . '">';
							} elseif ($logo) {
								echo '<img src="' . esc_url($logo) . '" height="40" class="logo-light" alt="' . esc_attr(foxnav_get_site_title()) . '">';
								echo '<img src="' . esc_url($logo) . '" height="40" class="logo-dark d-none" alt="' . esc_attr(foxnav_get_site_title()) . '">';
							} else {
								echo '<h4 class="logo-text">' . esc_html(foxnav_get_site_title()) . '</h4>';
							}
							?>
						</a>
					</div>
				</div>
				<div class="sidebar-menu flex-fill">
					<div class="sidebar-scroll">
						<div class="sidebar-menu-inner">
							<ul>
								<?php
								// 获取所有分类，按排序字段排序
								$categories = get_terms([
									'taxonomy' => 'site_category',
									'hide_empty' => false,
									'parent' => 0, // 只获取顶级分类
								]);

								// 自定义排序：按category_order元数据排序
								if (!empty($categories) && !is_wp_error($categories)) {
									usort($categories, function($a, $b) {
										$order_a = get_term_meta($a->term_id, 'category_order', true);
										$order_b = get_term_meta($b->term_id, 'category_order', true);
										$order_a = $order_a ? intval($order_a) : 0;
										$order_b = $order_b ? intval($order_b) : 0;
										return $order_a - $order_b;
									});

									foreach ($categories as $category) {
										$icon = get_term_meta($category->term_id, 'category_icon', true);
										$icon_class = $icon ? esc_attr($icon) : 'iconfont icon-tag';
										
										// 如果是首页，使用锚点定位；否则跳转到分类页面
										if (is_front_page() || is_home()) {
											$category_link = '#nav-' . $category->term_id;
											$link_class = 'smooth scroll-to-section';
										} else {
											$category_link = get_term_link($category);
											$link_class = '';
										}
										
										// 获取子分类
										$child_categories = get_terms([
											'taxonomy' => 'site_category',
											'hide_empty' => false,
											'parent' => $category->term_id,
										]);
										
										$has_children = !empty($child_categories) && !is_wp_error($child_categories) && count($child_categories) > 0;
										?>
										<li class="sidebar-item menu-item-has-children">
											<a href="<?php echo esc_url($category_link); ?>" class="smooth <?php echo esc_attr($link_class); ?>" data-category-id="<?php echo esc_attr($category->term_id); ?>">
												<i class="site-tag <?php echo $icon_class; ?> icon-lg mr-1"></i>
												<span><?php echo esc_html($category->name); ?></span>
											</a>
											<i class="iconfont icon-arrow-r-m sidebar-more text-sm"></i>
											<?php if ($has_children) : ?>
												<ul style="display: none;">
													<?php
													// 子分类也按排序排列
													usort($child_categories, function($a, $b) {
														$order_a = get_term_meta($a->term_id, 'category_order', true);
														$order_b = get_term_meta($b->term_id, 'category_order', true);
														$order_a = $order_a ? intval($order_a) : 0;
														$order_b = $order_b ? intval($order_b) : 0;
														return $order_a - $order_b;
													});
													
													foreach ($child_categories as $child) :
														// 子分类也使用锚点定位（如果在首页）
														if (is_front_page() || is_home()) {
															$child_link = '#nav-' . $child->term_id;
															$child_link_class = 'scroll-to-section';
														} else {
															$child_link = get_term_link($child);
															$child_link_class = '';
														}
													?>
													<li>
														<a href="<?php echo esc_url($child_link); ?>" class="<?php echo esc_attr($child_link_class); ?>" data-category-id="<?php echo esc_attr($child->term_id); ?>">
															<span><?php echo esc_html($child->name); ?></span>
											</a>
										</li>
													<?php endforeach; ?>
									</ul>
											<?php endif; ?>
								</li>
										<?php
									}
								}
								?>

<?php
								// 获取所有分类，按排序字段排序
								$categories = get_terms([
									'taxonomy' => 'site_category',
									'hide_empty' => false,
									'parent' => 0, // 只获取顶级分类
								]);

								// 自定义排序：按category_order元数据排序
								if (!empty($categories) && !is_wp_error($categories)) {
									usort($categories, function($a, $b) {
										$order_a = get_term_meta($a->term_id, 'category_order', true);
										$order_b = get_term_meta($b->term_id, 'category_order', true);
										$order_a = $order_a ? intval($order_a) : 0;
										$order_b = $order_b ? intval($order_b) : 0;
										return $order_a - $order_b;
									});

									foreach ($categories as $category) {
										$icon = get_term_meta($category->term_id, 'category_icon', true);
										$icon_class = $icon ? esc_attr($icon) : 'iconfont icon-tag';
										
										// 如果是首页，使用锚点定位；否则跳转到分类页面
										if (is_front_page() || is_home()) {
											$category_link = '#nav-' . $category->term_id;
											$link_class = 'smooth scroll-to-section';
										} else {
											$category_link = get_term_link($category);
											$link_class = '';
										}
										
										// 获取子分类
										$child_categories = get_terms([
											'taxonomy' => 'site_category',
											'hide_empty' => false,
											'parent' => $category->term_id,
										]);
										
										$has_children = !empty($child_categories) && !is_wp_error($child_categories) && count($child_categories) > 0;
										?>
										<li class="sidebar-item menu-item-has-children">
											<a href="<?php echo esc_url($category_link); ?>" class="smooth <?php echo esc_attr($link_class); ?>" data-category-id="<?php echo esc_attr($category->term_id); ?>">
												<i class="site-tag <?php echo $icon_class; ?> icon-lg mr-1"></i>
												<span><?php echo esc_html($category->name); ?></span>
											</a>
											<i class="iconfont icon-arrow-r-m sidebar-more text-sm"></i>
											<?php if ($has_children) : ?>
												<ul style="display: none;">
													<?php
													// 子分类也按排序排列
													usort($child_categories, function($a, $b) {
														$order_a = get_term_meta($a->term_id, 'category_order', true);
														$order_b = get_term_meta($b->term_id, 'category_order', true);
														$order_a = $order_a ? intval($order_a) : 0;
														$order_b = $order_b ? intval($order_b) : 0;
														return $order_a - $order_b;
													});
													
													foreach ($child_categories as $child) :
														// 子分类也使用锚点定位（如果在首页）
														if (is_front_page() || is_home()) {
															$child_link = '#nav-' . $child->term_id;
															$child_link_class = 'scroll-to-section';
														} else {
															$child_link = get_term_link($child);
															$child_link_class = '';
														}
													?>
													<li>
														<a href="<?php echo esc_url($child_link); ?>" class="<?php echo esc_attr($child_link_class); ?>" data-category-id="<?php echo esc_attr($child->term_id); ?>">
															<span><?php echo esc_html($child->name); ?></span>
											</a>
										</li>
													<?php endforeach; ?>
									</ul>
											<?php endif; ?>
								</li>
										<?php
									}
								}
								?>
								
								<?php
								// 获取所有分类，按排序字段排序
								$categories = get_terms([
									'taxonomy' => 'site_category',
									'hide_empty' => false,
									'parent' => 0, // 只获取顶级分类
								]);

								// 自定义排序：按category_order元数据排序
								if (!empty($categories) && !is_wp_error($categories)) {
									usort($categories, function($a, $b) {
										$order_a = get_term_meta($a->term_id, 'category_order', true);
										$order_b = get_term_meta($b->term_id, 'category_order', true);
										$order_a = $order_a ? intval($order_a) : 0;
										$order_b = $order_b ? intval($order_b) : 0;
										return $order_a - $order_b;
									});

									foreach ($categories as $category) {
										$icon = get_term_meta($category->term_id, 'category_icon', true);
										$icon_class = $icon ? esc_attr($icon) : 'iconfont icon-tag';
										
										// 如果是首页，使用锚点定位；否则跳转到分类页面
										if (is_front_page() || is_home()) {
											$category_link = '#nav-' . $category->term_id;
											$link_class = 'smooth scroll-to-section';
										} else {
											$category_link = get_term_link($category);
											$link_class = '';
										}
										
										// 获取子分类
										$child_categories = get_terms([
											'taxonomy' => 'site_category',
											'hide_empty' => false,
											'parent' => $category->term_id,
										]);
										
										$has_children = !empty($child_categories) && !is_wp_error($child_categories) && count($child_categories) > 0;
										?>
										<li class="sidebar-item menu-item-has-children">
											<a href="<?php echo esc_url($category_link); ?>" class="smooth <?php echo esc_attr($link_class); ?>" data-category-id="<?php echo esc_attr($category->term_id); ?>">
												<i class="site-tag <?php echo $icon_class; ?> icon-lg mr-1"></i>
												<span><?php echo esc_html($category->name); ?></span>
											</a>
											<i class="iconfont icon-arrow-r-m sidebar-more text-sm"></i>
											<?php if ($has_children) : ?>
												<ul style="display: none;">
													<?php
													// 子分类也按排序排列
													usort($child_categories, function($a, $b) {
														$order_a = get_term_meta($a->term_id, 'category_order', true);
														$order_b = get_term_meta($b->term_id, 'category_order', true);
														$order_a = $order_a ? intval($order_a) : 0;
														$order_b = $order_b ? intval($order_b) : 0;
														return $order_a - $order_b;
													});
													
													foreach ($child_categories as $child) :
														// 子分类也使用锚点定位（如果在首页）
														if (is_front_page() || is_home()) {
															$child_link = '#nav-' . $child->term_id;
															$child_link_class = 'scroll-to-section';
														} else {
															$child_link = get_term_link($child);
															$child_link_class = '';
														}
													?>
													<li>
														<a href="<?php echo esc_url($child_link); ?>" class="<?php echo esc_attr($child_link_class); ?>" data-category-id="<?php echo esc_attr($child->term_id); ?>">
															<span><?php echo esc_html($child->name); ?></span>
											</a>
										</li>
													<?php endforeach; ?>
									</ul>
											<?php endif; ?>
								</li>
										<?php
									}
								}
								?>								
							</ul>
						</div>
					</div>
				</div>
				<div class="border-top py-2 border-color">
					<div class="flex-bottom">
						<ul></ul>
					</div>
				</div>
			</div>
		</div>
		<!-- 左侧导航end -->