
			<div class="ainavpro-keyword no-big-header header-nav">
				<div id="header" class="ainavpro-keyword page-header sticky">
					<div class="ainavpro-keyword navbar navbar-expand-md">
						<div class="ainavpro-keyword container-fluid p-0 position-relative">
							<div class="ainavpro-keyword position-absolute w-100 text-center">
								<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(foxnav_get_site_title()); ?>" class="ainavpro-keyword navbar-brand d-md-none m-0">
									<span class="navbar-brand-text"><?php echo esc_html(foxnav_get_site_title()); ?></span>
								</a>
							</div>
							<div class="ainavpro-keyword nav-item d-md-none mobile-menu py-2 position-relative">
								<a href="javascript:" id="sidebar-switch" data-toggle="modal" data-target="#sidebar">
									<i class="ainavpro-keyword iconfont icon-classification icon-lg"></i>
								</a>
							</div>
							<div class="ainavpro-keyword collapse navbar-collapse order-2 order-md-1">
								<div class="ainavpro-keyword header-mini-btn">
									<label>
										<input id="mini-button" type="checkbox" checked="checked">
										<svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
											<path class="ainavpro-keyword line--1" d="M0 40h62c18 0 18-20-17 5L31 55"></path>
											<path class="ainavpro-keyword line--2" d="M0 50h80"></path>
											<path class="ainavpro-keyword line--3" d="M0 60h62c18 0 18 20-17-5L31 45"></path>
										</svg>
									</label>
								</div>
								<ul class="ainavpro-keyword navbar-nav navbar-top site-menu mr-4">
									<?php
									// 检查是否有自定义菜单
									if (has_nav_menu('primary')) {
										wp_nav_menu([
											'theme_location' => 'primary',
											'container' => false,
											'items_wrap' => '%3$s',
											'depth' => 2,
											'walker' => new Walker_Nav_Menu()
										]);
									} else {
										// 如果没有设置菜单，显示提示信息
										?>
										<li class="menu-item menu-setup-notice">
											<?php if (current_user_can('manage_options')): ?>
												<a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" 
												   title="点击前往菜单设置">
													<span>菜单未设置，请到 外观-菜单 进行设置，并勾选显示位置"顶部导航"</span>
												</a>
											<?php else: ?>
												<span>菜单未设置，请到 外观-菜单 进行设置，并勾选显示位置"顶部导航"</span>
											<?php endif; ?>
												</li>
												<?php
									}
									?>
								</ul>
							</div>

						</div>
					</div>
				</div>
				<div class="ainavpro-keyword placeholder"></div>
			</div>

			<div class="header-big  post-top no-bg mb-4">
				<div class="s-search">
					<div id="search" class="s-search mx-auto">

						<div id="search-list-menu" class="">
							<div class="s-type text-center">
								<div class="s-type-list big tab-auto-scrollbar overflow-x-auto">
									<label for="type-zhannei" class="active" data-page="home" data-id="group-a">

										<span>常用</span>
									</label>
									<label for="type-bing1" data-page="home" data-id="group-b">
										<span>搜索</span>
									</label>
									<label for="type-huggingface" data-page="home" data-id="group-d">
										<span>社区</span>
									</label>
									<label for="type-baiduyige" data-page="home" data-id="group-f">
										<span>图片</span>
									</label>
									<label for="type-taobao1" data-page="home" data-id="group-e">
										<span>生活</span>
									</label>
								</div>
							</div>
						</div>
						<form id="search_wrap" class="super-search-fm" method="get" action="<?php echo esc_url(home_url('/')); ?>">
							<input type="text" name="s" id="search_keyword" class="form-control smart-tips search-key" placeholder="站内搜索" style="outline:0" autocomplete="off" value="<?php echo get_search_query(); ?>">
							<input type="hidden" name="post_type" value="site">
							<button type="submit" id="search_submit">
								<i class="iconfont icon-search"></i>
							</button>
						</form>
						<div id="search-list" class="hide-type-list">
							<div class="search-group justify-content-center group-a s-current">
								<ul class="search-type tab-auto-scrollbar overflow-x-auto">
									<li>
										<input checked="checked" hidden="" type="radio" name="type" data-page="home" id="type-zhannei" value="" data-placeholder="站内搜索">
										<label for="type-zhannei">
											<span class="text-muted">站内</span>
										</label>
									</li>
									<li>
										<input hidden="" type="radio" name="type" data-page="home" id="type-baidu" value="https://www.baidu.com/s?wd=%s%" data-placeholder="百度一下">
										<label for="type-baidu">
											<span class="text-muted">百度</span>
										</label>
									</li>
									<li>
										<input hidden="" type="radio" name="type" data-page="home" id="type-sogou" value="https://www.sogou.com/web?query=%s%" data-placeholder="搜狗搜索">
										<label for="type-sogou">
											<span class="text-muted">搜狗</span>
										</label>
									</li>
									<li>
										<input hidden="" type="radio" name="type" data-page="home" id="type-bing" value="https://cn.bing.com/search?q=%s%" data-placeholder="必应搜索">
										<label for="type-bing">
											<span class="text-muted">必应</span>
										</label>
									</li>
									<li>
										<input hidden="" type="radio" name="type" data-page="home" id="type-google" value="https://www.google.com/search?q=%s%" data-placeholder="谷歌搜索">
										<label for="type-google">
											<span class="text-muted">谷歌</span>
										</label>
									</li>
									<li>
										<input hidden="" type="radio" name="type" data-page="home" id="type-360" value="https://www.so.com/s?q=%s%" data-placeholder="360搜索">
										<label for="type-360">
											<span class="text-muted">360</span>
										</label>
									</li>
								</ul>
							</div>
						</div>
						<div class="card search-smart-tips" style="display: none">
							<ul></ul>
						</div>
					</div>
				</div>
				<div class="bulletin-big mx-3 mx-md-0"></div>
			</div>
			<!--- -->
			
			<style>
			/* 菜单设置提示样式 */
			.menu-setup-notice {
				padding: 8px 12px;
			}
			.menu-setup-notice span,
			.menu-setup-notice a {
				color: #dc3545;
				font-size: 14px;
				text-decoration: none;
			}
			.menu-setup-notice a:hover {
				text-decoration: underline;
			}
			</style>