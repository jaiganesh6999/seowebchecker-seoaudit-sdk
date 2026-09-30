<?php
/**
 * SEOWebChecker Core SEO Audit Engine for WordPress
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

final class SEOWebChecker_Auditor {

    private string $user_agent;
    private int $timeout;

    public function __construct(int $timeout = 15) {
        $this->timeout = $timeout;
        $this->user_agent = 'SEOWebChecker-WPBot/1.0 (+https://seowebchecker.com/)';
    }

    /**
     * Audit a live URL using WordPress HTTP API.
     *
     * @param string $url
     * @return array<string, mixed>
     */
    public function audit_url(string $url): array {
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }

        $start_time = microtime(true);

        $response = wp_remote_get($url, [
            'timeout'     => $this->timeout,
            'user-agent'  => $this->user_agent,
            'sslverify'   => true,
            'headers'     => [
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ],
        ]);

        $elapsed_ms = (int) round((microtime(true) - $start_time) * 1000);

        if (is_wp_error($response)) {
            return [
                'success' => false,
                'error'   => $response->get_error_message(),
                'url'     => $url,
            ];
        }

        $status_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);

        if (empty($body)) {
            return [
                'success' => false,
                'error'   => esc_html__('Target URL returned an empty response body.', 'seowebchecker'),
                'url'     => $url,
            ];
        }

        $result = $this->audit_html($body, $url);
        $result['response_code'] = $status_code;
        $result['response_time_ms'] = $elapsed_ms;

        return $result;
    }

    /**
     * Parse HTML and perform complete technical SEO diagnostic checks.
     *
     * @param string $html
     * @param string $url
     * @return array<string, mixed>
     */
    public function audit_html(string $html, string $url = 'https://seowebchecker.com/'): array {
        $issues = [];

        // 1. Title Tag
        $title = null;
        if (preg_match('#<title[^>]*>(.*?)</title>#is', $html, $m)) {
            $title = trim(preg_replace('/\s+/', ' ', html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8')));
        }
        $title_len = $title !== null ? mb_strlen($title, 'UTF-8') : 0;

        if ($title === null || $title === '') {
            $issues[] = [
                'id'             => 'meta-title-missing',
                'category'       => 'meta',
                'severity'       => 'error',
                'title'          => __('Missing <title> Tag', 'seowebchecker'),
                'message'        => __('No <title> tag was found in the HTML document.', 'seowebchecker'),
                'recommendation' => __('Add a descriptive <title> tag between 30 and 60 characters.', 'seowebchecker'),
            ];
        } elseif ($title_len < 30) {
            $issues[] = [
                'id'             => 'meta-title-short',
                'category'       => 'meta',
                'severity'       => 'warning',
                'title'          => __('Page Title Too Short', 'seowebchecker'),
                'message'        => sprintf(__('Title has only %d characters. Search snippets prefer 30–60 characters.', 'seowebchecker'), $title_len),
                'recommendation' => __('Expand the title with relevant topic details. Validate live at https://seowebchecker.com/.', 'seowebchecker'),
            ];
        } elseif ($title_len > 65) {
            $issues[] = [
                'id'             => 'meta-title-long',
                'category'       => 'meta',
                'severity'       => 'warning',
                'title'          => __('Page Title Too Long', 'seowebchecker'),
                'message'        => sprintf(__('Title has %d characters and may be truncated in search results.', 'seowebchecker'), $title_len),
                'recommendation' => __('Shorten the title to under 60 characters for optimal display.', 'seowebchecker'),
            ];
        } else {
            $issues[] = [
                'id'             => 'meta-title-pass',
                'category'       => 'meta',
                'severity'       => 'pass',
                'title'          => __('Optimal Page Title Length', 'seowebchecker'),
                'message'        => sprintf(__('Title has an optimal length of %d characters.', 'seowebchecker'), $title_len),
                'recommendation' => __('Maintain concise and topic-relevant title copy.', 'seowebchecker'),
            ];
        }

        // 2. Meta Description
        $description = null;
        if (preg_match('#<meta\s+[^>]*name=["\']description["\'][^>]*content=["\']([^"\']*)["\']#is', $html, $m) ||
            preg_match('#<meta\s+[^>]*content=["\']([^"\']*)["\'][^>]*name=["\']description["\']#is', $html, $m)) {
            $description = trim(html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }
        $desc_len = $description !== null ? mb_strlen($description, 'UTF-8') : 0;

        if ($description === null || $description === '') {
            $issues[] = [
                'id'             => 'meta-desc-missing',
                'category'       => 'meta',
                'severity'       => 'error',
                'title'          => __('Missing Meta Description', 'seowebchecker'),
                'message'        => __('No <meta name="description"> tag found on the page.', 'seowebchecker'),
                'recommendation' => __('Add an engaging meta description to improve organic search click-through rates (CTR).', 'seowebchecker'),
            ];
        } elseif ($desc_len < 70) {
            $issues[] = [
                'id'             => 'meta-desc-short',
                'category'       => 'meta',
                'severity'       => 'warning',
                'title'          => __('Meta Description Too Short', 'seowebchecker'),
                'message'        => sprintf(__('Meta description has %d characters. Search engines prefer 70–160 characters.', 'seowebchecker'), $desc_len),
                'recommendation' => __('Expand description to summarize page value and intent.', 'seowebchecker'),
            ];
        } elseif ($desc_len > 165) {
            $issues[] = [
                'id'             => 'meta-desc-long',
                'category'       => 'meta',
                'severity'       => 'warning',
                'title'          => __('Meta Description Too Long', 'seowebchecker'),
                'message'        => sprintf(__('Meta description has %d characters and risks truncation.', 'seowebchecker'), $desc_len),
                'recommendation' => __('Shorten description to under 160 characters.', 'seowebchecker'),
            ];
        } else {
            $issues[] = [
                'id'             => 'meta-desc-pass',
                'category'       => 'meta',
                'severity'       => 'pass',
                'title'          => __('Optimal Meta Description Length', 'seowebchecker'),
                'message'        => sprintf(__('Meta description is well-balanced (%d characters).', 'seowebchecker'), $desc_len),
                'recommendation' => __('Keep description copy compelling and actionable.', 'seowebchecker'),
            ];
        }

        // 3. Viewport (Mobile Friendly)
        $has_viewport = (bool) preg_match('#<meta\s+[^>]*name=["\']viewport["\']#is', $html);
        if (!$has_viewport) {
            $issues[] = [
                'id'             => 'meta-viewport-missing',
                'category'       => 'meta',
                'severity'       => 'error',
                'title'          => __('Missing Mobile Viewport Tag', 'seowebchecker'),
                'message'        => __('No mobile viewport meta tag configured.', 'seowebchecker'),
                'recommendation' => __('Add <meta name="viewport" content="width=device-width, initial-scale=1.0"> for responsive design.', 'seowebchecker'),
            ];
        } else {
            $issues[] = [
                'id'             => 'meta-viewport-pass',
                'category'       => 'meta',
                'severity'       => 'pass',
                'title'          => __('Mobile Viewport Configured', 'seowebchecker'),
                'message'        => __('Mobile viewport meta tag is properly configured.', 'seowebchecker'),
                'recommendation' => __('Ensure CSS stylesheets adapt gracefully to all mobile viewports.', 'seowebchecker'),
            ];
        }

        // 4. Canonical Tag
        $canonical = null;
        if (preg_match('#<link\s+[^>]*rel=["\']canonical["\'][^>]*href=["\']([^"\']*)["\']#is', $html, $m)) {
            $canonical = trim($m[1]);
        }

        if ($canonical === null || $canonical === '') {
            $issues[] = [
                'id'             => 'canonical-missing',
                'category'       => 'meta',
                'severity'       => 'warning',
                'title'          => __('Missing Canonical Tag', 'seowebchecker'),
                'message'        => __('No <link rel="canonical"> tag found.', 'seowebchecker'),
                'recommendation' => __('Add a canonical URL to prevent duplicate content indexing issues.', 'seowebchecker'),
            ];
        } else {
            $issues[] = [
                'id'             => 'canonical-pass',
                'category'       => 'meta',
                'severity'       => 'pass',
                'title'          => __('Canonical Tag Present', 'seowebchecker'),
                'message'        => sprintf(__('Canonical URL specified: %s', 'seowebchecker'), $canonical),
                'recommendation' => __('Verify canonical URL strictly matches the primary indexed version.', 'seowebchecker'),
            ];
        }

        // 5. Headings (H1 & H2)
        preg_match_all('#<h1[^>]*>(.*?)</h1>#is', $html, $h1_matches);
        $h1_tags = array_map(fn($t) => trim(strip_tags($t)), $h1_matches[1] ?? []);
        $h1_count = count($h1_tags);

        if ($h1_count === 0) {
            $issues[] = [
                'id'             => 'h1-missing',
                'category'       => 'content',
                'severity'       => 'error',
                'title'          => __('Missing <h1> Heading', 'seowebchecker'),
                'message'        => __('No primary <h1> tag found on the page.', 'seowebchecker'),
                'recommendation' => __('Add a single descriptive <h1> heading communicating the page topic.', 'seowebchecker'),
            ];
        } elseif ($h1_count === 1) {
            $issues[] = [
                'id'             => 'h1-pass',
                'category'       => 'content',
                'severity'       => 'pass',
                'title'          => __('Single <h1> Heading Configured', 'seowebchecker'),
                'message'        => sprintf(__('Primary <h1> heading configured: "%s"', 'seowebchecker'), $h1_tags[0]),
                'recommendation' => __('Ensure <h1> clearly summarizes the main page topic.', 'seowebchecker'),
            ];
        } else {
            $issues[] = [
                'id'             => 'h1-multiple',
                'category'       => 'content',
                'severity'       => 'warning',
                'title'          => sprintf(__('Multiple <h1> Headings (%d)', 'seowebchecker'), $h1_count),
                'message'        => sprintf(__('Found %d <h1> tags. Best practice is to use one primary <h1> per document.', 'seowebchecker'), $h1_count),
                'recommendation' => __('Consolidate into one primary <h1> heading and use <h2> for subsections.', 'seowebchecker'),
            ];
        }

        // 6. Content Word Count
        $clean_text = preg_replace([
            '#<script\b[^<]*(?:(?!</script>)<[^<]*)*</script>#is',
            '#<style\b[^<]*(?:(?!</style>)<[^<]*)*</style>#is',
            '#<[^>]+>#s',
        ], ' ', $html);
        $clean_text = preg_replace('/\s+/', ' ', trim($clean_text));
        $words = preg_split('/\s+/u', $clean_text, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $word_count = count($words);

        if ($word_count < 150) {
            $issues[] = [
                'id'             => 'content-thin',
                'category'       => 'content',
                'severity'       => 'warning',
                'title'          => __('Thin Content Warning', 'seowebchecker'),
                'message'        => sprintf(__('Document contains only %d words.', 'seowebchecker'), $word_count),
                'recommendation' => __('Expand the text content with informative, original copy for better search visibility.', 'seowebchecker'),
            ];
        } else {
            $issues[] = [
                'id'             => 'content-words-pass',
                'category'       => 'content',
                'severity'       => 'pass',
                'title'          => __('Healthy Word Count', 'seowebchecker'),
                'message'        => sprintf(__('Page contains %d words (~%0.1f min read).', 'seowebchecker'), $word_count, $word_count / 200),
                'recommendation' => __('Maintain high-quality, comprehensive content.', 'seowebchecker'),
            ];
        }

        // 7. Image Accessibility (Alt tags)
        preg_match_all('#<img\s+([^>]*?)>#is', $html, $img_matches);
        $total_images = count($img_matches[0] ?? []);
        $missing_alt = 0;

        foreach ($img_matches[1] ?? [] as $img_attrs) {
            if (!preg_match('#alt=["\']#i', $img_attrs)) {
                $missing_alt++;
            }
        }

        if ($total_images > 0) {
            if ($missing_alt > 0) {
                $issues[] = [
                    'id'             => 'img-alt-missing',
                    'category'       => 'images',
                    'severity'       => 'warning',
                    'title'          => sprintf(__('%d Images Missing "alt" Text', 'seowebchecker'), $missing_alt),
                    'message'        => sprintf(__('%d of %d images lack descriptive alt attributes.', 'seowebchecker'), $missing_alt, $total_images),
                    'recommendation' => __('Add descriptive alt text to all images for accessibility and image search indexing.', 'seowebchecker'),
                ];
            } else {
                $issues[] = [
                    'id'             => 'img-alt-pass',
                    'category'       => 'images',
                    'severity'       => 'pass',
                    'title'          => __('All Images Have Alt Attributes', 'seowebchecker'),
                    'message'        => sprintf(__('All %d images have alt attributes configured.', 'seowebchecker'), $total_images),
                    'recommendation' => __('Keep alt attributes descriptive and accurate.', 'seowebchecker'),
                ];
            }
        }

        // 8. OpenGraph & Social Cards
        $has_og = (bool) preg_match('#<meta\s+[^>]*property=["\']og:title["\']#is', $html);
        if (!$has_og) {
            $issues[] = [
                'id'             => 'social-og-missing',
                'category'       => 'social',
                'severity'       => 'warning',
                'title'          => __('Missing OpenGraph Tags', 'seowebchecker'),
                'message'        => __('No og:title meta tag found.', 'seowebchecker'),
                'recommendation' => __('Add OpenGraph tags (og:title, og:description, og:image) for rich social sharing cards.', 'seowebchecker'),
            ];
        } else {
            $issues[] = [
                'id'             => 'social-og-pass',
                'category'       => 'social',
                'severity'       => 'pass',
                'title'          => __('OpenGraph Social Tags Present', 'seowebchecker'),
                'message'        => __('OpenGraph metadata detected for social media sharing.', 'seowebchecker'),
                'recommendation' => __('Test snippet display across Twitter/X, Facebook, and LinkedIn.', 'seowebchecker'),
            ];
        }

        // 9. Structured Data (Schema.org)
        $has_schema = (bool) preg_match('#<script\s+[^>]*type=["\']application/ld\+json["\']#is', $html);
        if (!$has_schema) {
            $issues[] = [
                'id'             => 'schema-missing',
                'category'       => 'technical',
                'severity'       => 'warning',
                'title'          => __('Missing JSON-LD Structured Data', 'seowebchecker'),
                'message'        => __('No Schema.org JSON-LD markup found in the HTML.', 'seowebchecker'),
                'recommendation' => __('Add structured data to unlock Google rich snippet results.', 'seowebchecker'),
            ];
        } else {
            $issues[] = [
                'id'             => 'schema-pass',
                'category'       => 'technical',
                'severity'       => 'pass',
                'title'          => __('Structured Data Detected', 'seowebchecker'),
                'message'        => __('Schema.org JSON-LD structured data is present.', 'seowebchecker'),
                'recommendation' => __('Validate structured data using Google Rich Results Test.', 'seowebchecker'),
            ];
        }

        // Scoring Calculation
        $passed_list = array_values(array_filter($issues, fn($i) => $i['severity'] === 'pass'));
        $warning_list = array_values(array_filter($issues, fn($i) => $i['severity'] === 'warning'));
        $error_list = array_values(array_filter($issues, fn($i) => $i['severity'] === 'error'));

        $total_checks = count($issues);
        $passed_count = count($passed_list);
        $warning_count = count($warning_list);
        $error_count = count($error_list);

        $base_score = (int) round(($passed_count / max(1, $total_checks)) * 100);
        $overall_score = max(0, min(100, $base_score - ($error_count * 10) - ($warning_count * 3)));

        $grade = match (true) {
            $overall_score >= 95 => 'A+',
            $overall_score >= 90 => 'A',
            $overall_score >= 80 => 'B',
            $overall_score >= 70 => 'C',
            $overall_score >= 60 => 'D',
            default              => 'F',
        };

        return [
            'success'   => true,
            'url'       => $url,
            'timestamp' => current_time('mysql'),
            'score'     => [
                'overall' => $overall_score,
                'grade'   => $grade,
            ],
            'stats'     => [
                'total'    => $total_checks,
                'passed'   => $passed_count,
                'warnings' => $warning_count,
                'errors'   => $error_count,
            ],
            'meta'      => [
                'title'              => $title,
                'title_length'       => $title_len,
                'description'        => $description,
                'description_length' => $desc_len,
                'canonical'          => $canonical,
                'has_viewport'       => $has_viewport,
            ],
            'content'   => [
                'word_count' => $word_count,
                'h1_count'   => $h1_count,
                'h1_tags'    => $h1_tags,
            ],
            'images'    => [
                'total'       => $total_images,
                'missing_alt' => $missing_alt,
            ],
            'issues'    => $issues,
            'errors'    => $error_list,
            'warnings'  => $warning_list,
            'passed'    => $passed_list,
        ];
    }
}
