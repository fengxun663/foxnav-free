<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="UTF-8">
		<meta name="renderer" content="webkit">
		<meta name="force-rendering" content="webkit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<meta name="theme-color" content="<?php echo esc_attr(foxnav_get_primary_color()); ?>">
		
		<?php
		// 输出Favicon
		$favicon = foxnav_get_favicon();
		if ($favicon) {
			echo '<link rel="icon" href="' . esc_url($favicon) . '" type="image/x-icon">';
			echo '<link rel="shortcut icon" href="' . esc_url($favicon) . '" type="image/x-icon">';
		}
		?>
		
		<title><?php echo esc_html(foxnav_get_seo_title()); ?></title>
		
		<?php
		// 输出SEO meta标签
		foxnav_output_seo_meta();
		?>
	<?php
	// 输出Open Graph meta标签
	foxnav_output_og_meta();
	
	// 输出自定义颜色CSS变量
	$primary_color = foxnav_get_primary_color();
	$secondary_color = foxnav_get_secondary_color();
	$accent_color = foxnav_get_accent_color();
	?>
	<style>
	:root {
		--primary-color: <?php echo esc_attr($primary_color); ?>;
		--secondary-color: <?php echo esc_attr($secondary_color); ?>;
		--accent-color: <?php echo esc_attr($accent_color); ?>;
	}
	</style>
	
	<?php 
	/**
	 * wp_head() 会自动输出所有通过 wp_enqueue_style() 和 wp_enqueue_script() 注册的CSS和JS
	 * 所有资源的加载配置请查看 inc/enqueue.php 文件
	 */
	wp_head(); 
	?>
	</head>
	<body class="home blog sidebar_no">	
