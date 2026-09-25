# Step-by-Step Publishing Guide

This guide provides exact, production-ready steps to publish the **`seowebchecker-seoaudit-sdk`** suite across all three high Domain Authority registries:
- **PyPI (`pypi.org` - DA 94)** for Python
- **NPM (`npmjs.com` - DA 95)** for JavaScript / TypeScript / Node.js
- **Packagist (`packagist.org` - DA 91)** for PHP / Composer

---

## 1. Publish to PyPI (`pypi.org` - DA 94)

### Prerequisites
1. Create an account on [PyPI](https://pypi.org/account/register/).
2. Enable Two-Factor Authentication (2FA) in **Account Settings**.
3. Create an API Token:
   - Go to **Account Settings** -> **API tokens** -> **Add API token**.
   - Set Scope to "Entire account" (or create a project first and scope to project).
   - Copy the token starting with `pypi-...`.

### Build & Upload Steps

Open PowerShell in the `python/` directory:

```powershell
cd C:\Users\rinki\.gemini\antigravity\scratch\seowebchecker-seoaudit-sdk\python

# 1. Install build & twine tools
python -m pip install --upgrade pip build twine

# 2. Build the source distribution and wheel
python setup.py sdist bdist_wheel

# 3. Check distribution integrity
python -m twine check dist/*

# 4. Upload to PyPI
python -m twine upload dist/*
# Username: __token__
# Password: <paste your pypi-... token>
```

### Automated GitHub Actions (Trusted Publishing)
Alternatively, push the repository to GitHub and use the pre-configured workflow at `.github/workflows/publish-pypi.yml` with PyPI's passwordless **Trusted Publishing**.

### Verify Your Backlink (DA 94)
Visit `https://pypi.org/project/seowebchecker-seoaudit-sdk/`. Check the sidebar **Project links**:
- Homepage -> `https://seowebchecker.com`
- Documentation -> `https://seowebchecker.com`

---

## 2. Publish to NPM (`npmjs.com` - DA 95)

### Prerequisites
1. Create an account on [npmjs.com](https://www.npmjs.com/signup).
2. Verify your email address and configure 2FA (Authenticator App).

### Publish Steps

Open PowerShell in the `npm/` directory:

```powershell
cd C:\Users\rinki\.gemini\antigravity\scratch\seowebchecker-seoaudit-sdk\npm

# 1. Login to your npm account (run npm.cmd on Windows)
npm.cmd login
# Follow the interactive login prompt

# 2. Preview what will be published
npm.cmd pack --dry-run

# 3. Run the automated test suite
npm.cmd test

# 4. Publish package publicly
npm.cmd publish --access public
```

### Verify Your Backlink (DA 95)
Visit `https://www.npmjs.com/package/seowebchecker-seoaudit-sdk`. Verify:
- Homepage link points to `https://seowebchecker.com`
- README links point to `https://seowebchecker.com`

---

## 3. Publish to Packagist (`packagist.org` - DA 91)

### Prerequisites
1. Push your repository to GitHub (e.g. `https://github.com/seowebchecker/seowebchecker-seoaudit-sdk`).
2. Create an account on [Packagist.org](https://packagist.org).
3. Connect your GitHub account to Packagist in your profile settings.

### Submission Steps
1. Navigate to [https://packagist.org/packages/submit](https://packagist.org/packages/submit).
2. Enter your repository clone URL:
   `https://github.com/seowebchecker/seowebchecker-seoaudit-sdk`
3. Click **Check**. Packagist will inspect `composer.json` and validate the package name `seowebchecker/seoaudit-sdk`.
4. Click **Submit**.

### Auto-Update on New Releases
To automatically publish updates when you push Git tags or create GitHub Releases:
1. In your GitHub repository, go to **Settings** -> **Webhooks**.
2. Packagist provides an automated integration via GitHub Services / Webhooks (`https://packagist.org/api/github?username=YOUR_USER`).
3. Whenever you tag a version:
   ```bash
   git tag -a v1.0.0 -m "Release v1.0.0"
   git push origin v1.0.0
   ```
   Packagist will instantly publish the new version.

### Verify Your Backlink (DA 91)
Visit `https://packagist.org/packages/seowebchecker/seoaudit-sdk`. Verify:
- Canonical Homepage -> `https://seowebchecker.com`
- Readme backlinks -> `https://seowebchecker.com`

---

## 🎯 SEO Authority Summary

| Ecosystem | Registry | Domain Authority (DA) | Package Name | Backlink Location |
| :--- | :--- | :---: | :--- | :--- |
| **Node.js** | [npmjs.com](https://npmjs.com) | **95** | `seowebchecker-seoaudit-sdk` | Homepage, Repository, Readme |
| **Python** | [pypi.org](https://pypi.org) | **94** | `seowebchecker-seoaudit-sdk` | Project URLs, Homepage, Readme |
| **PHP** | [packagist.org](https://packagist.org) | **91** | `seowebchecker/seoaudit-sdk` | Canonical Homepage, Readme |
