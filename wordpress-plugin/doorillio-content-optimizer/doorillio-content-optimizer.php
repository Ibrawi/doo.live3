<?php
/**
 * Plugin Name: Doorillio Content Optimizer
 * Description: Integrates Doorillio content optimization with WordPress
 * Version: 1.0.0
 * Author: Doorillio
 */

if (!defined('ABSPATH')) {
    exit;
}

class DoorillioContentOptimizer {
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_api_routes']);
        add_action('publish_post', [$this, 'handle_post_publish']);
        add_action('draft_to_publish', [$this, 'handle_draft_to_publish']);
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('add_meta_boxes', [$this, 'add_optimization_meta_box']);
        add_action('save_post', [$this, 'save_optimization_meta']);
    }

    public function register_api_routes() {
        register_rest_route('doorillio/v1', '/hooks', [
            'methods' => 'POST',
            'callback' => [$this, 'register_hook'],
            'permission_callback' => [$this, 'check_permission']
        ]);

        register_rest_route('doorillio/v1', '/optimize', [
            'methods' => 'POST',
            'callback' => [$this, 'optimize_content'],
            'permission_callback' => [$this, 'check_permission']
        ]);
    }

    public function add_admin_menu() {
        add_menu_page(
            'Doorillio Optimizer',
            'Content Optimizer',
            'manage_options',
            'doorillio-optimizer',
            [$this, 'render_admin_page'],
            'dashicons-performance',
            30
        );
    }

    public function render_admin_page() {
        include plugin_dir_path(__FILE__) . 'templates/admin-page.php';
    }

    public function add_optimization_meta_box() {
        add_meta_box(
            'doorillio_optimization',
            'Content Optimization',
            [$this, 'render_meta_box'],
            'post',
            'side',
            'high'
        );
    }

    public function render_meta_box($post) {
        $seo_score = get_post_meta($post->ID, '_doorillio_seo_score', true);
        $optimization_status = get_post_meta($post->ID, '_doorillio_optimization_status', true);
        
        include plugin_dir_path(__FILE__) . 'templates/meta-box.php';
    }

    public function save_optimization_meta($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (!current_user_can('edit_post', $post_id)) return;

        $fields = ['_doorillio_seo_score', '_doorillio_optimization_status'];
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
    }

    public function handle_post_publish($post_id) {
        $this->optimize_content($post_id);
        $this->trigger_webhook('publish_post', $post_id);
    }

    public function handle_draft_to_publish($post) {
        $this->optimize_content($post->ID);
        $this->trigger_webhook('draft_to_publish', $post->ID);
    }

    public function optimize_content($post_id) {
        $post = get_post($post_id);
        if (!$post) return;

        // Basic content optimization
        $content = $post->post_content;
        $title = $post->post_title;

        // Optimize title
        $optimized_title = $this->optimize_title($title);

        // Optimize content
        $optimized_content = $this->optimize_post_content($content);

        // Update post
        wp_update_post([
            'ID' => $post_id,
            'post_title' => $optimized_title,
            'post_content' => $optimized_content
        ]);

        // Calculate and save SEO score
        $seo_score = $this->calculate_seo_score($optimized_title, $optimized_content);
        update_post_meta($post_id, '_doorillio_seo_score', $seo_score);
        update_post_meta($post_id, '_doorillio_optimization_status', 'optimized');

        return [
            'success' => true,
            'seo_score' => $seo_score
        ];
    }

    private function optimize_title($title) {
        // Title optimization logic
        $max_length = 60;
        if (strlen($title) > $max_length) {
            $title = substr($title, 0, $max_length - 3) . '...';
        }
        return $title;
    }

    private function optimize_post_content($content) {
        // Content optimization logic
        $content = $this->add_internal_links($content);
        $content = $this->optimize_headings($content);
        $content = $this->optimize_paragraphs($content);
        return $content;
    }

    private function add_internal_links($content) {
        // Internal linking logic
        return $content;
    }

    private function optimize_headings($content) {
        // Heading optimization logic
        return $content;
    }

    private function optimize_paragraphs($content) {
        // Paragraph optimization logic
        return $content;
    }

    private function calculate_seo_score($title, $content) {
        $score = 100;

        // Title checks
        if (strlen($title) < 30 || strlen($title) > 60) {
            $score -= 10;
        }

        // Content checks
        $word_count = str_word_count(strip_tags($content));
        if ($word_count < 300) {
            $score -= 20;
        }

        return max(0, $score);
    }

    private function trigger_webhook($hook_name, $post_id) {
        $webhook_url = get_option("doorillio_webhook_{$hook_name}");
        if (!$webhook_url) return;

        wp_remote_post($webhook_url, [
            'body' => json_encode([
                'hook' => $hook_name,
                'post_id' => $post_id
            ]),
            'headers' => [
                'Content-Type' => 'application/json',
                'X-Doorillio-Webhook' => wp_create_nonce('doorillio_webhook')
            ]
        ]);
    }

    public function check_permission() {
        return current_user_can('manage_options');
    }
}

new DoorillioContentOptimizer();