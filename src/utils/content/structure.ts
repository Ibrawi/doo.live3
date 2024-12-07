import type { HeadingStructure } from '../../types/content';

export function analyzeContentStructure(content: string): HeadingStructure[] {
  const headings = extractHeadings(content);
  return buildHeadingHierarchy(headings);
}

function extractHeadings(content: string): HeadingStructure[] {
  const headingRegex = /<h([1-6])[^>]*>(.*?)<\/h\1>/gi;
  const headings: HeadingStructure[] = [];
  let match;
  
  while ((match = headingRegex.exec(content)) !== null) {
    headings.push({
      level: parseInt(match[1]),
      text: match[2].replace(/<[^>]+>/g, '').trim()
    });
  }
  
  return headings;
}

function buildHeadingHierarchy(headings: HeadingStructure[]): HeadingStructure[] {
  const hierarchy: HeadingStructure[] = [];
  let currentLevel = 1;
  
  headings.forEach(heading => {
    if (heading.level === currentLevel) {
      hierarchy.push(heading);
    } else if (heading.level > currentLevel) {
      const parent = hierarchy[hierarchy.length - 1];
      if (!parent.children) parent.children = [];
      parent.children.push(heading);
    }
  });
  
  return hierarchy;
}