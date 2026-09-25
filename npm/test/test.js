/**
 * Tests for seowebchecker-seoaudit-sdk (NPM)
 */

const assert = require('assert');
const { SEOAuditor, formatConsole, formatMarkdown } = require('../index');

const sampleHtml = `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>SEOWebChecker: Free Website SEO Audit and Analysis Tool</title>
  <meta name="description" content="Audit your website with 50+ real-time SEO checks. Discover technical errors and optimize on-page SEO.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="canonical" href="https://seowebchecker.com">
  <meta property="og:title" content="SEOWebChecker">
  <script type="application/ld+json">{"@context":"https://schema.org","@type":"WebSite","name":"SEOWebChecker"}</script>
</head>
<body>
  <h1>Free Online Website SEO Audit Tool</h1>
  <h2>Optimize Your Search Rankings</h2>
  <p>Run fast on-page audits with instant actionable recommendations and comprehensive diagnostics.</p>
  <img src="logo.webp" alt="SEOWebChecker Logo">
</body>
</html>`;

const auditor = new SEOAuditor();
const result = auditor.auditHtml(sampleHtml, 'https://seowebchecker.com');

console.log('Testing SEOAuditor...');
assert(result.score.overall >= 80, `Expected score >= 80, got ${result.score.overall}`);
assert.strictEqual(result.meta.title, 'SEOWebChecker: Free Website SEO Audit and Analysis Tool');
assert.strictEqual(result.content.h1_tags.length, 1);
assert.strictEqual(result.images.missing_alt, 0);
assert(result.stats.passed > 5, 'Expected passed checks');

console.log('Testing formatters...');
const consoleText = formatConsole(result, false);
assert(consoleText.includes('SEOWebChecker SEO Audit Report'));

const mdText = formatMarkdown(result);
assert(mdText.includes('# SEO Audit Report'));

console.log('All Node.js SDK tests passed successfully!');
