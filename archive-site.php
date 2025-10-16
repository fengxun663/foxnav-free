<?php
/**
 * 网址归档页模板
 *
 * @package FoxNav
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        
        <header class="page-header">
            <?php
            the_archive_title('<h1 class="page-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header>

        <?php if (have_posts()) : ?>

            <div class="foxnav-sites-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    get_template_part('template-parts/content', 'site');
                endwhile;
                ?>
            </div>

            <?php
            the_posts_pagination([
                'mid_size'  => 2,
                'prev_text' => __('&larr; 上一页', 'foxnav'),
                'next_text' => __('下一页 &rarr;', 'foxnav'),
            ]);

        else :
            get_template_part('template-parts/content', 'none');
        endif;
        ?>

    </div>
</main>

<?php
get_footer();





























