<?php
/**
 * 网址卡片模板
 *
 * @package FoxNav
 */

$site_data = foxnav_get_site_data(get_the_ID());
$link_attrs = foxnav_get_link_attributes(get_the_ID());
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('foxnav-site-card'); ?>>
    <div class="site-card-inner">
        
        <?php if ($site_data['screenshot']): ?>
            <div class="site-screenshot">
                <a href="<?php echo esc_url($site_data['url']); ?>" <?php echo $link_attrs; ?> class="foxnav-site-link" data-site-id="<?php echo get_the_ID(); ?>">
                    <img src="<?php echo esc_url($site_data['screenshot']); ?>" 
                         alt="<?php echo esc_attr(get_the_title()); ?>" 
                         loading="lazy">
                </a>
                
                <?php if ($site_data['official'] || $site_data['verified']): ?>
                    <div class="site-badges">
                        <?php if ($site_data['official']): ?>
                            <span class="badge badge-official" title="<?php _e('官方认证', 'foxnav'); ?>">
                                <span class="dashicons dashicons-yes-alt"></span>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($site_data['verified']): ?>
                            <span class="badge badge-verified" title="<?php _e('已验证', 'foxnav'); ?>">
                                <span class="dashicons dashicons-shield-alt"></span>
                            </span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="site-info">
            <div class="site-header">
                <?php if ($site_data['favicon']): ?>
                    <img src="<?php echo esc_url($site_data['favicon']); ?>" 
                         alt="" 
                         class="site-favicon">
                <?php endif; ?>
                
                <h3 class="site-title">
                    <a href="<?php the_permalink(); ?>"><?php echo esc_html($site_data['name'] ?: get_the_title()); ?></a>
                </h3>
            </div>

            <?php if ($site_data['description']): ?>
                <div class="site-description">
                    <p><?php echo esc_html(wp_trim_words($site_data['description'], 20)); ?></p>
                </div>
            <?php endif; ?>

            <div class="site-meta">
                <?php if (!empty($site_data['categories'])): ?>
                    <div class="site-categories">
                        <?php foreach (array_slice($site_data['categories'], 0, 2) as $cat): ?>
                            <a href="<?php echo esc_url(get_term_link($cat)); ?>" class="category-tag">
                                <?php echo esc_html($cat->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="site-actions">
                    <a href="<?php echo esc_url($site_data['url']); ?>" 
                       <?php echo $link_attrs; ?> 
                       class="btn btn-primary foxnav-site-link" 
                       data-site-id="<?php echo get_the_ID(); ?>">
                        <?php _e('访问', 'foxnav'); ?>
                        <span class="dashicons dashicons-external"></span>
                    </a>
                    
                    <button type="button" 
                            class="btn btn-icon foxnav-favorite-btn" 
                            data-site-id="<?php echo get_the_ID(); ?>"
                            title="<?php _e('收藏', 'foxnav'); ?>">
                        <span class="dashicons dashicons-star-filled"></span>
                        <span class="favorite-count"><?php echo $site_data['favorites']; ?></span>
                    </button>
                </div>
            </div>

            <?php if ($site_data['sponsored']): ?>
                <div class="site-sponsored">
                    <span><?php _e('赞助链接', 'foxnav'); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</article>









