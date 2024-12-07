import type { OptimizedContent } from '../../types';
import { LanguageTool } from 'languagetool-api';

const languageTool = new LanguageTool();

export async function optimizeContent(
  content: string,
  keywords: Array<{ term: string; score: number }>
): Promise<OptimizedContent> {
  // Check grammar and readability
  const results = await languageTool.check({
    text: content,
    language: 'en-US'
  });
  
  let optimizedContent = content;
  
  // Apply grammar corrections
  results.matches.forEach(match => {
    optimizedContent = optimizedContent.replace(
      match.context.text,
      match.replacements[0]?.value || match.context.text
    );
  });
  
  // Optimize keyword density
  optimizedContent = optimizeKeywordDensity(optimizedContent, keywords);
  
  // Generate SEO-friendly title
  const title = generateSEOTitle(optimizedContent, keywords);
  
  // Generate excerpt
  const excerpt = generateExcerpt(optimizedContent);
  
  // Calculate SEO score
  const seoScore = calculateSEOScore(optimizedContent, title, excerpt, keywords);
  
  // Find opportunities for additional internal links
  const suggestedLinks = findLinkOpportunities(optimizedContent);
  
  return {
    title,
    content: optimizedContent,
    excerpt,
    seoScore,
    suggestedLinks
  };
}

// Rest of the file remains unchanged