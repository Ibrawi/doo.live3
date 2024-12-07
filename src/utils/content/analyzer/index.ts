export * from './readability';
export * from './syllables';
export * from './density';

import type { ContentAnalysis } from '../../../types/content';
import { calculateReadabilityScore } from './readability';
import { calculateKeywordDensity } from './density';
import cheerio from 'cheerio';

export async function analyzeContent(html: string): Promise<ContentAnalysis> {
  const $ = cheerio.load(html);
  const text = $('body').text();
  
  return {
    wordCount: text.split(/\s+/).length,
    readabilityScore: calculateReadabilityScore(text),
    keywordDensity: calculateKeywordDensity(text),
    headings: extractHeadings($),
    internalLinks: extractInternalLinks($),
    topics: extractTopics(text),
    entities: extractEntities(text)
  };
}

function extractHeadings($: cheerio.Root): string[] {
  return $('h1, h2, h3').map((_, el) => $(el).text()).get();
}

function extractInternalLinks($: cheerio.Root): string[] {
  return $('a[href^="/"]').map((_, el) => $(el).attr('href')).get();
}

function extractTopics(text: string): string[] {
  // Implement topic extraction logic
  return [];
}

function extractEntities(text: string): string[] {
  // Implement entity extraction logic
  return [];
}