import React from 'react';
import { SeoAuditBadge } from './seo-audit-badge';

export const BasicSeoBadge = () => {
  return (
    <SeoAuditBadge url="https://seowebchecker.com/" initialScore={98} />
  );
};

export const ExpandedSeoBadge = () => {
  return (
    <SeoAuditBadge url="https://seowebchecker.com/" initialScore={100} showDetails={true} />
  );
};
