<?php
if (!defined('ABSPATH')) {
    exit;
}

// Theme Setup
function doorillio_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'doorillio'),
        'footer' => __('Footer Menu', 'doorillio'),
    ));
}
add_action('after_setup_theme', 'doorillio_setup');

// Enqueue Scripts and Styles
function doorillio_scripts() {
    wp_enqueue_style('doorillio-tailwind', get_template_directory_uri() . '/assets/css/tailwind.css');
    wp_enqueue_style('doorillio-style', get_stylesheet_uri());
    wp_enqueue_script('doorillio-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'doorillio_scripts');

// Custom Post Types
function doorillio_register_post_types() {
    register_post_type('health_guide', array(
        'labels' => array(
            'name' => __('Health Guides', 'doorillio'),
            'singular_name' => __('Health Guide', 'doorillio')
        ),
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-book-alt',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt')
    ));
}
add_action('init', 'doorillio_register_post_types');

// Custom Taxonomies
function doorillio_register_taxonomies() {
    register_taxonomy('health_category', 'health_guide', array(
        'labels' => array(
            'name' => __('Health Categories', 'doorillio'),
            'singular_name' => __('Health Category', 'doorillio')
        ),
        'hierarchical' => true,
        'show_admin_column' => true
    ));
}
add_action('init', 'doorillio_register_taxonomies');

// Widget Areas
function doorillio_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'doorillio'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here to appear in your sidebar.', 'doorillio'),
        'before_widget' => '<div class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'doorillio_widgets_init');