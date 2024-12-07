export function calculateKeywordDensity(text: string): Map<string, number> {
  const words = text.toLowerCase().split(/\s+/);
  const density = new Map<string, number>();
  const totalWords = words.length;
  
  words.forEach(word => {
    density.set(word, ((density.get(word) || 0) + 1) / totalWords);
  });
  
  return density;
}

export function analyzeKeywordUsage(content: string, keywords: string[]) {
  const density = calculateKeywordDensity(content);
  const analysis = new Map<string, { density: number; suggestions: string[] }>();
  
  keywords.forEach(keyword => {
    const keywordDensity = density.get(keyword) || 0;
    const suggestions = getKeywordSuggestions(keywordDensity);
    analysis.set(keyword, { density: keywordDensity, suggestions });
  });
  
  return analysis;
}

function getKeywordSuggestions(density: number): string[] {
  const suggestions: string[] = [];
  if (density < 0.01) {
    suggestions.push('Consider using this keyword more frequently');
  } else if (density > 0.03) {
    suggestions.push('Consider reducing keyword usage to avoid over-optimization');
  }
  return suggestions;
}