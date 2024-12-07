export interface KeywordData {
  term: string;
  volume: number;
  difficulty: number;
  opportunity: number;
  competitors?: CompetitorData[];
}

export interface CompetitorData {
  url: string;
  title: string;
  position: number;
  contentAnalysis: {
    wordCount: number;
    readabilityScore: number;
    keywordDensity: Map<string, number>;
    headings: string[];
    internalLinks: string[];
  };
}

export interface KeywordResearchResult {
  keyword: KeywordData;
  relatedKeywords: KeywordData[];
  competitorAnalysis: CompetitorData[];
  contentSuggestions: ContentSuggestion[];
}

export interface ContentSuggestion {
  title: string;
  outline: string[];
  targetWordCount: number;
  keyPoints: string[];
  suggestedLinks: string[];
}