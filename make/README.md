# SEOWebChecker - Make.com (Integromat) Custom App Integration

[![Platform](https://img.shields.io/badge/Platform-seowebchecker.com-brightgreen)](https://seowebchecker.com/)
[![Make Integration](https://img.shields.io/badge/Make.com-Custom%20App-purple.svg)](https://www.make.com/en/integrations)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Official [Make.com](https://www.make.com/) (formerly Integromat) custom application for **SEOWebChecker**. Connects technical SEO audits and automated regression alerts directly into visual workflows across thousands of Make apps (Slack, Notion, Google Sheets, Airtable, HubSpot, Telegram, and Email).

Powered by the [SEOWebChecker](https://seowebchecker.com/) platform.

---

## ⚡ Module Features

### Action: `Run Technical SEO Audit`
- **Purpose**: Audits any target webpage URL in real time.
- **Parameters**:
  - `url` (Text, Required): Full webpage URL to inspect.
  - `alertThreshold` (Number, Optional): Alert score threshold (default: 85).
- **Returned Metrics**:
  - `score`: Technical SEO health score (0–100).
  - `grade`: Letter grade (`A+` through `F`).
  - `status`: `PASS` or `ALERT`.
  - `isAlert`: Boolean flag indicating if score fell below threshold.
  - `title`: Title tag & length audit.
  - `metaDescription`: Meta description length & truncation checks.
  - `issuesCount` & `issues`: Detailed list of detected SEO issues.
  - `passesCount` & `passes`: List of passed checks.
  - `auditedAt`: ISO timestamp.
  - `platformUrl`: `https://seowebchecker.com/`.

---

## 🚀 How to Build & Publish on Make.com

### Step 1: Open Make Developer Hub
1. Log in to your account at **[make.com](https://www.make.com/)**.
2. In the left navigation bar, click **Developer** -> **My Apps** (or visit [make.com/en/developer](https://www.make.com/en/developer)).
3. Click **Create a new app**:
   - **App name**: `SEOWebChecker`
   - **App label**: `SEOWebChecker`
   - **Description**: `Real-time technical SEO auditing engine that validates on-page HTML, headings, meta tags, and mobile readiness with automated alerts.`
   - **Theme color**: `#10B981` (Emerald Green)
   - **Language**: English

---

### Step 2: Configure the App Base & Connection
1. In the **Base** settings tab:
   - **Base URL**: `https://seowebchecker.com/`
2. In the **Connections** tab:
   - Click **Add Connection** -> Type: **API Key** (or leave as direct HTTP if using public auditing).

---

### Step 3: Add the "Run Technical SEO Audit" Module
1. Under **Modules**, click **Add Module**:
   - **Type**: `Action`
   - **Name**: `runAudit`
   - **Label**: `Run Technical SEO Audit`
   - **Description**: `Audits a webpage URL for technical SEO issues, returning scores, grades, and diagnostics.`
2. **Parameters**:
   - In the **Mappable Parameters** tab, paste the JSON from [`make/modules/run_audit_parameters.json`](modules/run_audit_parameters.json).
3. **Interface**:
   - In the **Interface** tab, paste the JSON from [`make/modules/run_audit_interface.json`](modules/run_audit_interface.json).
4. **Samples**:
   - In the **Samples** tab, paste the JSON from [`make/modules/run_audit_samples.json`](modules/run_audit_samples.json).
5. Click **Save**.

---

### Step 4: Test in a Scenario
1. Go to **Scenarios** in Make.
2. Click **Create a new scenario**.
3. Click the **+** button and search for **SEOWebChecker**.
4. Select **Run Technical SEO Audit**, enter `https://seowebchecker.com/`, and click **Run once**.
5. Inspect the output bubble to confirm all scores and diagnostic metrics load successfully.

---

### Step 5: Invite Users & Publish to Make Directory
1. **Private Sharing**:
   - Under your app's **Settings** -> **Invitations**, generate a public invite link. Anyone with this link can immediately add SEOWebChecker to their Make scenarios!
2. **Public Directory Publishing**:
   - Under the **Publishing** tab, submit SEOWebChecker for review by the Make Partner Team to be listed in the official [Make App Directory](https://www.make.com/en/integrations).

---

## 📄 License

MIT License. Engineered by the [SEOWebChecker](https://seowebchecker.com/) team.
