# seowebchecker-seoaudit-sdk

[![PyPI version](https://img.shields.io/pypi/v/seowebchecker-seoaudit-sdk.svg?color=blue)](https://pypi.org/project/seowebchecker-seoaudit-sdk/)
[![npm version](https://img.shields.io/npm/v/seowebchecker-seoaudit-sdk.svg?color=red)](https://www.npmjs.com/package/seowebchecker-seoaudit-sdk)
[![Packagist](https://img.shields.io/packagist/v/seowebchecker/seoaudit-sdk.svg?color=orange)](https://packagist.org/packages/seowebchecker/seoaudit-sdk)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![SEOWebChecker](https://img.shields.io/badge/Official%20Site-seowebchecker.com-indigo)](https://seowebchecker.com)

Lightweight, open-source client SDK suite for comprehensive website SEO audits, on-page optimization, and Core Web Vitals checks.

Official website: **[https://seowebchecker.com](https://seowebchecker.com)**

---

## 📦 Package Distribution Matrix

This repository houses the official client SDKs ready to publish to three high-authority registries:

| Registry | Ecosystem | Package Name | Authority | Status | Directory |
| :--- | :--- | :--- | :---: | :---: | :--- |
| **[NPM](https://npmjs.com)** | Node.js / TS | `seowebchecker-seoaudit-sdk` | **DA 95** | Ready | [`npm/`](./npm) |
| **[PyPI](https://pypi.org)** | Python | `seowebchecker-seoaudit-sdk` | **DA 94** | Ready | [`python/`](./python) |
| **[Packagist](https://packagist.org)** | PHP (8.2+) | `seowebchecker/seoaudit-sdk` | **DA 91** | Ready | [`php/`](./php) |

Detailed instructions on generating distribution bundles and uploading to each registry can be found in **[PUBLISHING_GUIDE.md](./PUBLISHING_GUIDE.md)**.

---

## 🚀 Quick Usage

### Python (PyPI)
```bash
pip install seowebchecker-seoaudit-sdk
```
```python
from seowebchecker_seoaudit import SEOAuditor

auditor = SEOAuditor()
result = auditor.audit("https://example.com")
print(f"Score: {result.score.overall}/100 (Grade: {result.score.grade})")
```

### Node.js (NPM)
```bash
npm install seowebchecker-seoaudit-sdk
```
```javascript
const { SEOAuditor } = require('seowebchecker-seoaudit-sdk');

const auditor = new SEOAuditor();
auditor.audit('https://example.com').then(result => {
  console.log(`Score: ${result.score.overall}/100`);
});
```

### PHP 8.2+ (Packagist)
```bash
composer require seowebchecker/seoaudit-sdk
```
```php
use SeoWebChecker\SeoAudit\Auditor;

$auditor = new Auditor();
$result = $auditor->audit('https://example.com');
echo "Score: {$result->score}/100\n";
```

---

## 🖥️ Command-Line Interface (CLI)

Each package provides a cross-platform CLI tool:

```bash
# Python CLI
seowebchecker-audit https://example.com --format markdown --output report.md

# Node.js CLI (via npx)
npx seowebchecker-seoaudit-sdk https://example.com --min-score 85

# PHP CLI
php vendor/bin/seowebchecker https://example.com --format json
```

---

## 🔍 Features & Audit Checks

- **Meta & On-Page**: `<title>` length & keywords, `<meta description>`, `<link rel="canonical">`, `<meta robots>`, `<meta viewport>`, charset, and lang.
- **Content Hierarchy**: Single `<h1>` enforcement, `<h2>`/`<h3>` subheadings structure, word count, reading time estimation, and keyword frequency.
- **Links Architecture**: Internal vs external link distribution, empty anchor detection, generic anchor warnings, and secure `target="_blank"` with `rel="noopener"`.
- **Image SEO & A11y**: Missing `alt` tags, empty `alt` attributes, format breakdowns, and next-gen format adoption (WebP, AVIF, SVG).
- **Social Graph**: OpenGraph tags (`og:title`, `og:image`, `og:description`, `og:url`) and Twitter Cards.
- **Technical & Security**: HTTPS validation, HSTS security, MIME-sniffing protection (`X-Content-Type-Options`), clickjacking protection, `robots.txt`, and `sitemap.xml`.
- **Performance & Latency**: Server response time (TTFB), total document weight, and server-side text compression (Gzip/Brotli).
- **Structured Data**: JSON-LD schema parsing and Schema.org detection.

---

## 🧪 Testing

Run all unit test suites locally across Python, Node.js, and PHP:

```powershell
.\test_all.ps1
```

---

## 📄 License

MIT License © 2026 [SEOWebChecker.com](https://seowebchecker.com).
