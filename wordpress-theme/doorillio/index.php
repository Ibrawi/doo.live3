<?php get_header(); ?>

<main class="container mx-auto px-4 py-8">
    <?php if (is_home() && !is_paged()): ?>
        <?php get_template_part('template-parts/featured-article'); ?>
        <?php get_template_part('template-parts/category-grid'); ?>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8">
            <?php if (have_posts()): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <?php while (have_posts()): the_post(); ?>
                        <?php get_template_part('template-parts/content', get_post_format()); ?>
                    <?php endwhile; ?>
                </div>
                
                <?php the_posts_pagination(array(
                    'prev_text' => __('Previous', 'doorillio'),
                    'next_text' => __('Next', 'doorillio'),
                )); ?>
            <?php else: ?>
                <?php get_template_part('template-parts/content', 'none'); ?>
            <?php endif; ?>
        </div>

        <aside class="lg:col-span-4 space-y-8">
            <?php get_sidebar(); ?>
        </aside>
    </div>
</main>

<?php get_footer(); ?>