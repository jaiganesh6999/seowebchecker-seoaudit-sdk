# SEOWebChecker Postman API Collection 📮

Official Postman API Collection and Environment for [SEOWebChecker](https://seowebchecker.com/) — On-page technical SEO audits, meta tag validation, Open Graph verification, and Core Web Vitals checks.

[![Postman](https://img.shields.io/badge/Postman-Public%20API%20Network-orange?logo=postman)](https://www.postman.com/)
[![Powered by SEOWebChecker](https://img.shields.io/badge/Audits%20by-SEOWebChecker.com-4CAF50.svg)](https://seowebchecker.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

---

## ⚡ Direct Import (Web & Desktop)

You can import this collection directly into **Postman Web** or **Postman Desktop**:

### Option A: Import via Raw URL (1-Click)
1. Open **[Postman](https://web.postman.co/)** (Web or Desktop app).
2. Click the **"Import"** button in the top left.
3. Paste this raw URL:
   ```
   https://raw.githubusercontent.com/jaiganesh6999/seowebchecker-seoaudit-sdk/main/postman/seowebchecker.postman_collection.json
   ```
4. Click **Import**.

### Option B: Import Local Files
1. In Postman, click **Import**.
2. Drag and drop:
   - [`seowebchecker.postman_collection.json`](./seowebchecker.postman_collection.json)
   - [`seowebchecker.postman_environment.json`](./seowebchecker.postman_environment.json)

---

## 🌐 Publishing to the Postman Public API Network (DA 92)

To make your collection publicly discoverable by millions of developers on the **Postman Public API Network** (`postman.com/explore`):

1. **Sign in to Postman:**
   Go to [https://www.postman.com/](https://www.postman.com/) and sign in.
2. **Create a Public Workspace:**
   - Click **Workspaces** (top left) → **Create Workspace**.
   - Name: `SEOWebChecker`.
   - Visibility: Select **Public**.
   - Summary: `Official workspace for SEOWebChecker technical SEO and audit APIs.`
3. **Import Collection:**
   - In your new public workspace, import `seowebchecker.postman_collection.json`.
4. **Publish / Share Collection:**
   - Click the **`...`** (more options) next to the imported collection.
   - Select **Share** → **Via Run in Postman button** or **Publish to API Network**.
   - Provide the overview and link to **`https://seowebchecker.com/`**.

---

## 📦 Included Requests

| Request | Method | Description |
| :--- | :---: | :--- |
| **1. Quick SEO Health Score** | `GET` | Rapid 0–100 score and letter grade (A+ to F). |
| **2. Full Technical SEO Audit** | `POST` | Comprehensive 50+ check technical SEO report in JSON. |
| **3. Meta Tags & Social Previews** | `GET` | Validates title, description, canonical link, mobile viewport, and OpenGraph/Twitter previews. |
| **4. Core Web Vitals Diagnostics** | `GET` | Evaluates LCP, CLS, INP, page weight, and TTFB. |
| **5. Image & Alt Text Inspection** | `GET` | Scans `<img>` elements for missing alt tags and modern formats. |

---

## 📄 License

MIT License. See [LICENSE](../LICENSE) for details.
