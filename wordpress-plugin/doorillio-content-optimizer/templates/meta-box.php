<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="doorillio-meta-box">
    <div class="seo-score">
        <label>SEO Score:</label>
        <div class="score-display">
            <span class="score-value <?php echo esc_attr($this->get_score_class($seo_score)); ?>">
                <?php echo esc_html($seo_score); ?>
            </span>
        </div>
    </div>

    <div class="optimization-status">
        <label>Status:</label>
        <span class="status-value">
            <?php echo esc_html($optimization_status ?: 'Not optimized'); ?>
        </span>
    </div>

    <div class="optimization-actions">
        <button type="button" class="button button-primary" id="optimize-content">
            Optimize Content
        </button>
        <span class="spinner"></span>
    </div>
</div>

<style>
.doorillio-meta-box {
    padding: 10px;
}

.seo-score {
    margin-bottom: 15px;
}

.score-display {
    margin-top: 5px;
}

.score-value {
    padding: 3px 8px;
    border-radius: 3px;
    font-weight: bold;
}

.score-good {
    background-color: #0f834d;
    color: white;
}

.score-average {
    background-color: #ffb900;
    color: black;
}

.score-poor {
    background-color: #dc3232;
    color: white;
}

.optimization-status {
    margin-bottom: 15px;
}

.optimization-actions {
    display: flex;
    align-items: center;
    gap: 10px;
}

.spinner {
    float: none;
    margin: 0;
}
</style>

<script>
jQuery(document).ready(function($) {
    $('#optimize-content').on('click', function() {
        const button = $(this);
        const spinner = button.next('.spinner');
        const postId = $('#post_ID').val();

        button.prop('disabled', true);
        spinner.addClass('is-active');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'doorillio_optimize_content',
                post_id: postId,
                nonce: '<?php echo wp_create_nonce("doorillio_optimize"); ?>'
            },
            success: function(response) {
                if (response.success) {
                    $('.score-value').text(response.data.seo_score);
                    $('.status-value').text('Optimized');
                }
            },
            complete: function() {
                button.prop('disabled', false);
                spinner.removeClass('is-active');
            }
        });
    });
});
</script>