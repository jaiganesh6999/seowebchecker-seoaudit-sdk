/**
 * SEOWebChecker WordPress Admin JavaScript
 */

/* global jQuery, seowebchecker_vars */
(function ($) {
  'use strict';

  let latestAuditReport = null;

  // 1. Dashboard Form Submission
  $('#seowebchecker-audit-form').on('submit', function (e) {
    e.preventDefault();

    const targetUrl = $('#seowebchecker-url-input').val().trim();
    if (!targetUrl) return;

    $('#seowebchecker-error').hide();
    $('#seowebchecker-results').hide();
    $('#seowebchecker-loading').show();
    $('#seowebchecker-submit-btn').prop('disabled', true);

    $.ajax({
      url: seowebchecker_vars.ajax_url,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'seowebchecker_run_audit',
        nonce: seowebchecker_vars.nonce,
        url: targetUrl,
      },
      success: function (res) {
        $('#seowebchecker-loading').hide();
        $('#seowebchecker-submit-btn').prop('disabled', false);

        if (res.success && res.data) {
          latestAuditReport = res.data;
          renderDashboardResults(res.data);
        } else {
          showError(res.data || seowebchecker_vars.i18n.error_msg);
        }
      },
      error: function (xhr) {
        $('#seowebchecker-loading').hide();
        $('#seowebchecker-submit-btn').prop('disabled', false);
        const errMsg = xhr.responseJSON && xhr.responseJSON.data ? xhr.responseJSON.data : seowebchecker_vars.i18n.error_msg;
        showError(errMsg);
      },
    });
  });

  function showError(msg) {
    $('#seowebchecker-error-text').text(msg);
    $('#seowebchecker-error').show();
  }

  function renderDashboardResults(report) {
    const score = report.score ? report.score.overall : 0;
    const grade = report.score ? report.score.grade : 'F';

    $('#seowebchecker-score-val').text(score);
    $('#seowebchecker-grade-val').text(grade);
    $('#seowebchecker-target-url').text(report.url);

    // Color score circle
    const circle = $('.score-circle-wrapper');
    const gradeBadge = $('#seowebchecker-grade-val');
    let borderColor = '#d63638';
    if (score >= 90) borderColor = '#008a20';
    else if (score >= 80) borderColor = '#2271b1';
    else if (score >= 70) borderColor = '#d68100';

    circle.css('border-color', borderColor);
    gradeBadge.css('background', borderColor);

    // Stats
    $('#seowebchecker-stat-passed').text(report.stats.passed || 0);
    $('#seowebchecker-stat-warnings').text(report.stats.warnings || 0);
    $('#seowebchecker-stat-errors').text(report.stats.errors || 0);

    // External link
    $('#seowebchecker-web-report-link').attr('href', 'https://seowebchecker.com/');

    // Issues list
    const container = $('#seowebchecker-issues-container');
    container.empty();

    if (report.issues && report.issues.length > 0) {
      report.issues.forEach(function (issue) {
        const severity = issue.severity || 'pass';
        const html = `
          <div class="seowebchecker-issue-item severity-${severity}">
            <div class="issue-header">
              <span class="issue-title">${escapeHtml(issue.title)}</span>
              <span class="issue-badge">${severity}</span>
            </div>
            <div class="issue-message">${escapeHtml(issue.message)}</div>
            ${issue.recommendation ? `<div class="issue-recommendation"><strong>Recommendation:</strong> ${escapeHtml(issue.recommendation)}</div>` : ''}
          </div>
        `;
        container.append(html);
      });
    }

    $('#seowebchecker-results').slideDown(250);
  }

  // 2. Post Editor Metabox Runner
  $('#seowebchecker-run-post-audit').on('click', function () {
    const btn = $(this);
    const postId = btn.data('post-id');
    const spinner = $('#seowebchecker-metabox-spinner');

    btn.prop('disabled', true);
    spinner.addClass('is-active');

    $.ajax({
      url: seowebchecker_vars.ajax_url,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'seowebchecker_audit_post',
        nonce: seowebchecker_vars.nonce,
        post_id: postId,
      },
      success: function (res) {
        btn.prop('disabled', false);
        spinner.removeClass('is-active');

        if (res.success && res.data) {
          renderMetaboxResults(res.data);
        } else {
          alert(res.data || seowebchecker_vars.i18n.error_msg);
        }
      },
      error: function () {
        btn.prop('disabled', false);
        spinner.removeClass('is-active');
        alert(seowebchecker_vars.i18n.error_msg);
      },
    });
  });

  function renderMetaboxResults(report) {
    const score = report.score ? report.score.overall : 0;
    const grade = report.score ? report.score.grade : 'F';

    $('#seowebchecker-post-score').text(score);
    const gradeEl = $('#seowebchecker-post-grade');
    gradeEl.text(grade);

    let color = '#d63638';
    if (score >= 90) color = '#008a20';
    else if (score >= 80) color = '#2271b1';
    else if (score >= 70) color = '#d68100';
    gradeEl.css('background', color);

    const list = $('#seowebchecker-post-issues-list');
    list.empty();

    if (report.issues && report.issues.length > 0) {
      report.issues.slice(0, 5).forEach(function (issue) {
        const icon = issue.severity === 'pass' ? '✅' : (issue.severity === 'warning' ? '⚠️' : '❌');
        list.append(`<li style="margin-bottom: 6px;">${icon} <strong>${escapeHtml(issue.title)}</strong></li>`);
      });
    }

    $('#seowebchecker-metabox-results').show();
  }

  // 3. Export Buttons
  $('#seowebchecker-copy-md').on('click', function () {
    if (!latestAuditReport) return;
    const md = generateMarkdown(latestAuditReport);
    navigator.clipboard.writeText(md).then(function () {
      alert(seowebchecker_vars.i18n.copied);
    });
  });

  $('#seowebchecker-download-json').on('click', function () {
    if (!latestAuditReport) return;
    const dataStr = 'data:text/json;charset=utf-8,' + encodeURIComponent(JSON.stringify(latestAuditReport, null, 2));
    const dlAnchorElem = document.createElement('a');
    dlAnchorElem.setAttribute('href', dataStr);
    dlAnchorElem.setAttribute('download', 'seowebchecker-audit-' + Date.now() + '.json');
    dlAnchorElem.click();
  });

  function generateMarkdown(r) {
    let md = `# SEO Audit Report: ${r.url}\n`;
    md += `**Score**: ${r.score.overall}/100 (Grade: ${r.score.grade})\n`;
    md += `**Passed**: ${r.stats.passed} | **Warnings**: ${r.stats.warnings} | **Errors**: ${r.stats.errors}\n\n`;
    md += `## Findings\n\n`;
    if (r.issues) {
      r.issues.forEach(function (i) {
        md += `- [${i.severity.toUpperCase()}] **${i.title}**: ${i.message}\n`;
      });
    }
    md += `\n---\n*Report generated via SEOWebChecker WordPress Plugin (https://seowebchecker.com/)*\n`;
    return md;
  }

  function escapeHtml(str) {
    if (!str) return '';
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

})(jQuery);
