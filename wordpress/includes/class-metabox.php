<?php
/**
 * SEOWebChecker Post Editor Metabox Integration
 * Compatible with PHP 8.2
 *
 * Official Website: https://seowebchecker.com/
 *
 * @package SEOWebChecker
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

final class SEOWebChecker_Metabox {

    public static function init(): void {
        add_action('add_meta_boxes', [self::class, 'register_metabox']);
        add_action('wp_ajax_seowebchecker_audit_post', [self::class, 'ajax_audit_post']);
    }

    public static function register_metabox(): void {
        $post_types = get_post_types(['public' => true], 'names');
        foreach ($post_types as $post_type) {
            add_meta_box(
                'seowebchecker_audit_metabox',
                __('SEOWebChecker: Live On-Page SEO Scorecard', 'seowebchecker'),
                [self::class, 'render_metabox'],
                $post_type,
                'side',
                'high'
            );
        }
    }

    public static function render_metabox(WP_Post $post): void {
        wp_nonce_field('seowebchecker_metabox_nonce', 'seowebchecker_nonce');
        $permalink = get_permalink($post->ID);
        ?>
        <div id="seowebchecker-metabox-wrapper">
            <p class="description">
                <?php esc_html_e('Inspect technical SEO factors before publishing.', 'seowebchecker'); ?>
            </p>

            <div style="margin-bottom: 12px;">
                <button type="button" class="button button-primary" id="seowebchecker-run-post-audit" data-post-id="<?php echo esc_attr((string) $post->ID); ?>" data-url="<?php echo esc_url($permalink ?: ''); ?>">
                    <?php esc_html_e('Run On-Page SEO Audit', 'seowebchecker'); ?>
                </button>
                <span class="spinner" id="seowebchecker-metabox-spinner" style="float: none; margin: 0 0 0 6px;"></span>
            </div>

            <div id="seowebchecker-metabox-results" style="display: none;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; padding: 10px; background: #f6f7f7; border-radius: 4px;">
                    <div>
                        <strong><?php esc_html_e('SEO Score:', 'seowebchecker'); ?></strong>
                        <span id="seowebchecker-post-score" style="font-size: 1.3em; font-weight: bold; margin-left: 4px;">--</span>/100
                    </div>
                    <div>
                        <span id="seowebchecker-post-grade" style="display: inline-block; padding: 2px 8px; border-radius: 4px; font-weight: bold; color: #fff; background: #666;">-</span>
                    </div>
                </div>

                <ul id="seowebchecker-post-issues-list" style="margin: 0; padding: 0; list-style: none; font-size: 12px;">
                    <!-- Populated via JS -->
                </ul>

                <p style="margin-top: 10px; font-size: 11px; text-align: center;">
                    <a href="https://seowebchecker.com/" target="_blank" rel="noopener noreferrer">
                        <?php esc_html_e('Audit live URL on SEOWebChecker &rarr;', 'seowebchecker'); ?>
                    </a>
                </p>
            </div>
        </div>
        <?php
    }

    public static function ajax_audit_post(): void {
        check_ajax_referer('seowebchecker_admin_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(__('Unauthorized permission level.', 'seowebchecker'), 403);
        }

        $post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
        $post = get_post($post_id);

        if (!$post) {
            wp_send_json_error(__('Invalid post ID provided.', 'seowebchecker'), 400);
        }

        $url = get_permalink($post->ID);
        if (!$url) {
            $url = home_url('/?p=' . $post->ID);
        }

        // Render preview HTML for draft or published post
        $html = sprintf(
            '<!DOCTYPE html><html><head><title>%s</title><meta name="description" content="%s"></head><body><h1>%s</h1>%s</body></html>',
            esc_html(get_the_title($post)),
            esc_attr(wp_strip_all_tags(get_the_excerpt($post))),
            esc_html(get_the_title($post)),
            apply_filters('the_content', $post->post_content)
        );

        $auditor = new SEOWebChecker_Auditor();
        $report = $auditor->audit_html($html, $url);

        wp_send_json_success($report);
    }
}
