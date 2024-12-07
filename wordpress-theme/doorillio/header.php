<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('min-h-screen bg-gray-50 font-sans'); ?>>
    <?php wp_body_open(); ?>
    
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center flex-1">
                    <?php if (has_custom_logo()): ?>
                        <?php the_custom_logo(); ?>
                    <?php else: ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-2xl font-semibold">
                            <?php bloginfo('name'); ?>
                        </a>
                    <?php endif; ?>
                    
                    <nav class="hidden xl:flex items-center space-x-6 ml-12">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'container' => false,
                            'menu_class' => 'flex space-x-6',
                            'fallback_cb' => false,
                        ));
                        ?>
                    </nav>
                </div>

                <div class="flex items-center space-x-6">
                    <?php get_search_form(); ?>
                    <button class="xl:hidden text-gray-700 hover:text-emerald-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div class="mobile-menu hidden xl:hidden bg-white border-t border-gray-100">
        <?php
        wp_nav_menu(array(
            'theme_location' => 'primary',
            'container' => 'nav',
            'container_class' => 'px-4 py-2 space-y-1',
            'fallback_cb' => false,
        ));
        ?>
    </div>