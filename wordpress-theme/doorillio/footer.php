<footer class="bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="py-16 border-b border-gray-800">
            <?php get_template_part('template-parts/newsletter-box'); ?>
        </div>

        <div class="py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <?php if (has_custom_logo()): ?>
                    <?php the_custom_logo(); ?>
                <?php else: ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="text-white text-2xl font-semibold">
                        <?php bloginfo('name'); ?>
                    </a>
                <?php endif; ?>
                
                <p class="mt-4 text-gray-400">
                    <?php bloginfo('description'); ?>
                </p>
            </div>

            <?php if (is_active_sidebar('footer-1')): ?>
                <div class="footer-widget">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php endif; ?>

            <?php if (is_active_sidebar('footer-2')): ?>
                <div class="footer-widget">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div>
            <?php endif; ?>

            <?php if (is_active_sidebar('footer-3')): ?>
                <div class="footer-widget">
                    <?php dynamic_sidebar('footer-3'); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="py-6 border-t border-gray-800">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                    <?php _e('All rights reserved.', 'doorillio'); ?>
                </p>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container' => false,
                    'menu_class' => 'flex space-x-6 mt-4 md:mt-0',
                    'fallback_cb' => false,
                ));
                ?>
            </div>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>