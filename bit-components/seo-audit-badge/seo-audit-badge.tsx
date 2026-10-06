import React, { useState } from 'react';

export interface SeoAuditBadgeProps {
  /** The target URL to audit */
  url?: string;
  /** Initial SEO Health score (0-100) */
  initialScore?: number;
  /** Whether to show the detailed diagnostics dropdown */
  showDetails?: boolean;
}

export function SeoAuditBadge({
  url = 'https://seowebchecker.com/',
  initialScore = 98,
  showDetails = false
}: SeoAuditBadgeProps) {
  const [expanded, setExpanded] = useState(showDetails);
  const [score, setScore] = useState(initialScore);

  const getStatusColor = (val: number) => {
    if (val >= 90) return '#10b981';
    if (val >= 70) return '#f59e0b';
    return '#ef4444';
  };

  return (
    <div style={{
      fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
      display: 'inline-block',
      backgroundColor: '#ffffff',
      border: '1px solid #e2e8f0',
      borderRadius: '8px',
      padding: '10px 14px',
      boxShadow: '0 1px 3px rgba(0,0,0,0.05)',
      maxWidth: '360px'
    }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '12px' }}>
        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
          <span style={{
            display: 'inline-block',
            width: '10px',
            height: '10px',
            borderRadius: '50%',
            backgroundColor: getStatusColor(score)
          }} />
          <span style={{ fontWeight: 600, fontSize: '13px', color: '#0f172a' }}>
            SEO Health: {score}/100
          </span>
        </div>

        <button
          onClick={() => setExpanded(!expanded)}
          style={{
            background: 'none',
            border: 'none',
            color: '#2563eb',
            fontSize: '11px',
            cursor: 'pointer',
            padding: '2px 6px',
            borderRadius: '4px',
            fontWeight: 500
          }}
        >
          {expanded ? 'Hide Details' : 'View Details'}
        </button>
      </div>

      <div style={{ fontSize: '11px', color: '#64748b', marginTop: '4px' }}>
        Target: <code style={{ color: '#0f172a' }}>{url}</code>
      </div>

      {expanded && (
        <div style={{
          marginTop: '10px',
          borderTop: '1px solid #f1f5f9',
          paddingTop: '8px',
          fontSize: '11px',
          display: 'flex',
          flexDirection: 'column',
          gap: '4px'
        }}>
          <div style={{ color: '#10b981' }}>✓ Title & Meta Description: Optimal length</div>
          <div style={{ color: '#10b981' }}>✓ Heading Structure: Single distinct &lt;h1&gt;</div>
          <div style={{ color: '#10b981' }}>✓ Canonical Link: Configured cleanly</div>
          <div style={{ color: '#10b981' }}>✓ Core Web Vitals: LCP &amp; INP Ready</div>

          <div style={{ marginTop: '6px', textAlign: 'right' }}>
            <a
              href="https://seowebchecker.com/"
              target="_blank"
              rel="noopener noreferrer"
              style={{ color: '#2563eb', textDecoration: 'none', fontWeight: 500 }}
            >
              Powered by SEOWebChecker &rarr;
            </a>
          </div>
        </div>
      )}
    </div>
  );
}

export default SeoAuditBadge;
