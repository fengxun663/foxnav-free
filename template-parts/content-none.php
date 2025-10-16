<?php
/**
 * 无内容模板
 *
 * @package FoxNav
 */
?>

<section class="no-results not-found">
    <header class="page-header">
        <h1 class="page-title"><?php _e('未找到内容', 'foxnav'); ?></h1>
    </header>

    <div class="page-content">
        <?php if (is_search()) : ?>
            <p><?php _e('抱歉，没有找到与您的搜索相关的结果。请尝试使用其他关键词。', 'foxnav'); ?></p>
            <?php get_search_form(); ?>
        <?php else : ?>
            <p><?php _e('此处暂无内容，请稍后再来。', 'foxnav'); ?></p>
        <?php endif; ?>
    </div>
</section>









