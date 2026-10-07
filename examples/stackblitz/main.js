import { SEOAuditor } from 'seowebchecker-seoaudit-sdk';

const auditor = new SEOAuditor();

const htmlInput = document.getElementById('htmlInput');
const runBtn = document.getElementById('runBtn');
const resultCard = document.getElementById('resultCard');
const scoreVal = document.getElementById('scoreVal');
const scoreGrade = document.getElementById('scoreGrade');
const issuesList = document.getElementById('issuesList');

function performAudit() {
  const html = htmlInput.value;
  const result = auditor.auditHtml(html, 'https://seowebchecker.com/');

  scoreVal.textContent = result.score?.overall ?? 100;
  scoreGrade.textContent = `Grade ${result.score?.grade ?? 'A'}`;

  issuesList.innerHTML = '';
  const issues = result.issues || [];

  if (issues.length === 0) {
    const emptyNotice = document.createElement('div');
    emptyNotice.className = 'issue-item pass';
    emptyNotice.textContent = 'All on-page SEO best practices and checks passed!';
    issuesList.appendChild(emptyNotice);
  } else {
    issues.forEach(issue => {
      const item = document.createElement('div');
      item.className = `issue-item ${issue.severity || 'info'}`;

      const title = document.createElement('div');
      title.className = 'issue-title';
      title.textContent = `[${(issue.severity || 'INFO').toUpperCase()}] ${issue.title}`;

      const desc = document.createElement('div');
      desc.className = 'issue-desc';
      desc.textContent = issue.message || issue.recommendation;

      item.appendChild(title);
      item.appendChild(desc);
      issuesList.appendChild(item);
    });
  }

  resultCard.style.display = 'block';
}

runBtn.addEventListener('click', performAudit);

// Automatically execute once on boot
performAudit();
