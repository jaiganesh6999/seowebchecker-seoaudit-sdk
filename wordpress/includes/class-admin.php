<?php
/**
 * SEOWebChecker Admin Dashboard Page & Controller
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

final class SEOWebChecker_Admin {

    public static function init(): void {
        add_action('admin_menu', [self::class, 'register_admin_menu']);
        add_action('admin_enqueue_scripts', [self::class, 'enqueue_assets']);
        add_action('wp_ajax_seowebchecker_run_audit', [self::class, 'ajax_run_audit']);
        add_action('admin_bar_menu', [self::class, 'add_admin_bar_item'], 100);
    }

    public static function register_admin_menu(): void {
        add_menu_page(
            __('SEOWebChecker: Technical SEO Audit', 'seowebchecker'),
            __('SEO Web Checker', 'seowebchecker'),
            'manage_options',
            'seowebchecker',
            [self::class, 'render_admin_page'],
            'dashicons-chart-area',
            95
        );
    }

    public static function enqueue_assets(string $hook_suffix): void {
        // Enqueue on plugin page and post edit screens
        if ($hook_suffix !== 'toplevel_page_seowebchecker' && !in_array($hook_suffix, ['post.php', 'post-new.php'], true)) {
            return;
        }

        wp_enqueue_style(
            'seowebchecker-admin-css',
            plugins_url('assets/css/admin.css', dirname(__DIR__) . '/wordpress/seowebchecker.php'),
            [],
            '1.0.0'
        );

        wp_enqueue_script(
            'seowebchecker-admin-js',
            plugins_url('assets/js/admin.js', dirname(__DIR__) . '/wordpress/seowebchecker.php'),
            ['jquery'],
            '1.0.0',
            true
        );

        wp_localize_script('seowebchecker-admin-js', 'seowebchecker_vars', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('seowebchecker_admin_nonce'),
            'home_url' => home_url('/'),
            'i18n'     => [
                'auditing'   => __('Auditing website...', 'seowebchecker'),
                'run_audit'  => __('Run SEO Audit', 'seowebchecker'),
                'error_msg'  => __('Audit request failed. Please check the URL and try again.', 'seowebchecker'),
                'copied'     => __('Copied to clipboard!', 'seowebchecker'),
            ],
        ]);
    }

    public static function add_admin_bar_item(WP_Admin_Bar $wp_admin_bar): void {
        if (!current_user_can('manage_options') || is_admin()) {
            return;
        }

        global $wp;
        $current_url = home_url(add_query_arg([], $wp->request ?? ''));

        $wp_admin_bar->add_node([
            'id'    => 'seowebchecker-bar-node',
            'title' => '<span class="ab-icon dashicons-chart-area" style="top:2px;"></span> ' . esc_html__('Audit This Page (SEO)', 'seowebchecker'),
            'href'  => admin_url('admin.php?page=seowebchecker&audit_url=' . urlencode($current_url)),
        ]);
    }

    public static function render_admin_page(): void {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You do not have sufficient permissions to access this page.', 'seowebchecker'));
        }

        $default_url = isset($_GET['audit_url']) ? esc_url_raw(wp_unslash($_GET['audit_url'])) : home_url('/');
        ?>
        <div class="wrap seowebchecker-wrap">
            <header class="seowebchecker-header">
                <div class="seowebchecker-header-left">
                    <h1>
                        <span class="dashicons dashicons-chart-area"></span>
                        <?php esc_html_e('SEOWebChecker: Technical SEO Audit & Analyzer', 'seowebchecker'); ?>
                    </h1>
                    <p class="description">
                        <?php esc_html_e('Instant on-page diagnostics, heading structure analysis, meta tags, and Core Web Vitals readiness.', 'seowebchecker'); ?>
                    </p>
                </div>
                <div class="seowebchecker-header-right">
                    <a href="https://seowebchecker.com/" target="_blank" rel="noopener noreferrer" class="button button-secondary">
                        <?php esc_html_e('Visit SEOWebChecker.com &rarr;', 'seowebchecker'); ?>
                    </a>
                </div>
            </header>

            <!-- URL Input Bar -->
            <div class="seowebchecker-card seowebchecker-search-card">
                <form id="seowebchecker-audit-form">
                    <label for="seowebchecker-url-input"><strong><?php esc_html_e('Target Webpage URL:', 'seowebchecker'); ?></strong></label>
                    <div class="seowebchecker-input-group">
                        <input type="url" id="seowebchecker-url-input" class="regular-text" value="<?php echo esc_attr($default_url); ?>" required placeholder="https://example.com" />
                        <button type="submit" id="seowebchecker-submit-btn" class="button button-primary button-hero">
                            <span class="dashicons dashicons-search" style="vertical-align: middle;"></span>
                            <span id="seowebchecker-btn-text"><?php esc_html_e('Run Live SEO Audit', 'seowebchecker'); ?></span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Loading State -->
            <div id="seowebchecker-loading" style="display: none;" class="seowebchecker-card seowebchecker-loading-card">
                <span class="spinner is-active" style="float: none; width: 32px; height: 32px; margin-bottom: 12px;"></span>
                <h3><?php esc_html_e('Analyzing on-page SEO factors & Core Web Vitals...', 'seowebchecker'); ?></h3>
                <p><?php esc_html_e('Inspecting metadata, headings, images, mobile viewports, and social tags.', 'seowebchecker'); ?></p>
            </div>

            <!-- Error Notification -->
            <div id="seowebchecker-error" style="display: none;" class="notice notice-error">
                <p id="seowebchecker-error-text"></p>
            </div>

            <!-- Results Dashboard -->
            <div id="seowebchecker-results" style="display: none;">
                <!-- Overview Stats Grid -->
                <div class="seowebchecker-grid">
                    <div class="seowebchecker-card seowebchecker-score-card">
                        <div class="score-circle-wrapper">
                            <div class="score-circle" id="seowebchecker-score-val">--</div>
                            <div class="score-grade-badge" id="seowebchecker-grade-val">-</div>
                        </div>
                        <h3><?php esc_html_e('Overall SEO Score', 'seowebchecker'); ?></h3>
                        <p id="seowebchecker-target-url" class="score-url"></p>
                    </div>

                    <div class="seowebchecker-card seowebchecker-stat-card">
                        <h4><?php esc_html_e('Passed Checks', 'seowebchecker'); ?></h4>
                        <div class="stat-number text-success" id="seowebchecker-stat-passed">0</div>
                        <p class="description"><?php esc_html_e('Optimized SEO elements', 'seowebchecker'); ?></p>
                    </div>

                    <div class="seowebchecker-card seowebchecker-stat-card">
                        <h4><?php esc_html_e('Warnings', 'seowebchecker'); ?></h4>
                        <div class="stat-number text-warning" id="seowebchecker-stat-warnings">0</div>
                        <p class="description"><?php esc_html_e('Suboptimal factors', 'seowebchecker'); ?></p>
                    </div>

                    <div class="seowebchecker-card seowebchecker-stat-card">
                        <h4><?php esc_html_e('Errors', 'seowebchecker'); ?></h4>
                        <div class="stat-number text-danger" id="seowebchecker-stat-errors">0</div>
                        <p class="description"><?php esc_html_e('Critical issues to fix', 'seowebchecker'); ?></p>
                    </div>
                </div>

                <!-- Export & Action Buttons -->
                <div class="seowebchecker-actions-bar">
                    <button type="button" class="button" id="seowebchecker-copy-md">
                        <span class="dashicons dashicons-clipboard"></span> <?php esc_html_e('Copy Markdown Report', 'seowebchecker'); ?>
                    </button>
                    <button type="button" class="button" id="seowebchecker-download-json">
                        <span class="dashicons dashicons-download"></span> <?php esc_html_e('Download JSON', 'seowebchecker'); ?>
                    </button>
                    <a id="seowebchecker-web-report-link" href="https://seowebchecker.com/" target="_blank" rel="noopener noreferrer" class="button button-secondary">
                        <span class="dashicons dashicons-external"></span> <?php esc_html_e('Full Report on SEOWebChecker &rarr;', 'seowebchecker'); ?>
                    </a>
                </div>

                <!-- Detailed Issues Breakdown -->
                <div class="seowebchecker-card">
                    <h2><?php esc_html_e('Detailed Diagnostic Findings & Recommendations', 'seowebchecker'); ?></h2>
                    <div id="seowebchecker-issues-container">
                        <!-- Populated by admin.js -->
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public static function ajax_run_audit(): void {
        check_ajax_referer('seowebchecker_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized permission level.', 'seowebchecker'), 403);
        }

        $url = isset($_POST['url']) ? esc_url_raw(wp_unslash($_POST['url'])) : '';
        if (empty($url)) {
            wp_send_json_error(__('URL cannot be empty.', 'seowebchecker'), 400);
        }

        $auditor = new SEOWebChecker_Auditor();
        $report = $auditor->audit_url($url);

        if (empty($report['success'])) {
            wp_send_json_error($report['error'] ?? __('Failed to audit target URL.', 'seowebchecker'), 500);
        }

        wp_send_json_success($report);
    }
}
