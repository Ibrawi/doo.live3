export interface ContentAnalysis {
  wordCount: number;
  readabilityScore: number;
  keywordDensity: Map<string, number>;
  headings: HeadingStructure[];
  internalLinks: string[];
  topics: string[];
  entities: string[];
}

export interface HeadingStructure {
  level: number;
  text: string;
  children?: HeadingStructure[];
}

export interface ImprovedContent {
  content: string;
  improvements: string[];
  suggestedLinks: string[];
}

export interface ContentMetrics {
  wordCount: number;
  readingTime: number;
  keywordDensity: Map<string, number>;
  readabilityScore: number;
}