<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1>Doorillio Content Optimizer</h1>

    <div class="card">
        <h2>Content Optimization Settings</h2>
        
        <form method="post" action="options.php">
            <?php settings_fields('doorillio_optimizer_settings'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="auto_optimize">Auto-Optimization</label>
                    </th>
                    <td>
                        <input type="checkbox" id="auto_optimize" name="doorillio_auto_optimize" 
                               value="1" <?php checked(get_option('doorillio_auto_optimize'), 1); ?>>
                        <p class="description">Automatically optimize content when publishing</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="min_word_count">Minimum Word Count</label>
                    </th>
                    <td>
                        <input type="number" id="min_word_count" name="doorillio_min_word_count" 
                               value="<?php echo esc_attr(get_option('doorillio_min_word_count', 300)); ?>">
                        <p class="description">Minimum number of words for optimal SEO score</p>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">
                        <label for="internal_links">Internal Links</label>
                    </th>
                    <td>
                        <input type="number" id="internal_links" name="doorillio_internal_links" 
                               value="<?php echo esc_attr(get_option('doorillio_internal_links', 3)); ?>">
                        <p class="description">Target number of internal links per post</p>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>

    <div class="card">
        <h2>Optimization Statistics</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Optimized Posts</td>
                    <td><?php echo esc_html($this->get_optimized_posts_count()); ?></td>
                </tr>
                <tr>
                    <td>Average SEO Score</td>
                    <td><?php echo esc_html($this->get_average_seo_score()); ?></td>
                </tr>
                <tr>
                    <td>Posts Needing Optimization</td>
                    <td><?php echo esc_html($this->get_unoptimized_posts_count()); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>