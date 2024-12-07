import { GoogleTrendsAPI } from 'google-trends-api';
import cheerio from 'cheerio';
import fetch from 'node-fetch';
import type { KeywordData, CompetitorData } from '../../types/keywords';

export async function researchKeywords(topic: string): Promise<KeywordData[]> {
  // Get related keywords from Google Trends
  const trendingKeywords = await getTrendingKeywords(topic);
  
  // Get search volume and competition data
  const keywordsData = await getKeywordsData(trendingKeywords);
  
  // Analyze competitors for each keyword
  const keywordsWithCompetitors = await Promise.all(
    keywordsData.map(async keyword => ({
      ...keyword,
      competitors: await analyzeCompetitors(keyword.term)
    }))
  );
  
  return keywordsWithCompetitors;
}

async function getTrendingKeywords(topic: string): Promise<string[]> {
  const results = await GoogleTrendsAPI.relatedQueries({ keyword: topic });
  return JSON.parse(results)
    .default
    .rankedList[0]
    .rankedKeyword
    .map((item: any) => item.query)
    .slice(0, 10);
}

async function getKeywordsData(keywords: string[]): Promise<KeywordData[]> {
  return Promise.all(keywords.map(async term => {
    const volume = await getSearchVolume(term);
    const difficulty = await calculateDifficulty(term);
    
    return {
      term,
      volume,
      difficulty,
      opportunity: calculateOpportunityScore(volume, difficulty)
    };
  }));
}

async function getSearchVolume(keyword: string): Promise<number> {
  // Use Google Trends data as a proxy for search volume
  const trendsData = await GoogleTrendsAPI.interestOverTime({ keyword });
  const data = JSON.parse(trendsData);
  
  // Calculate average interest over time
  const values = data.default.timelineData.map((item: any) => item.value[0]);
  const average = values.reduce((a: number, b: number) => a + b, 0) / values.length;
  
  // Scale the value to approximate monthly searches
  return Math.round(average * 1000);
}

async function analyzeCompetitors(keyword: string): Promise<CompetitorData[]> {
  // Use DuckDuckGo search API (no API key required)
  const response = await fetch(`https://api.duckduckgo.com/?q=${encodeURIComponent(keyword)}&format=json`);
  const data = await response.json();
  
  const competitors = data.RelatedTopics.slice(0, 10);
  
  return Promise.all(competitors.map(async (result: any, index: number) => {
    const content = await fetchAndAnalyzeContent(result.FirstURL);
    
    return {
      url: result.FirstURL,
      title: result.Text,
      position: index + 1,
      contentAnalysis: {
        wordCount: content.wordCount,
        readabilityScore: content.readabilityScore,
        keywordDensity: content.keywordDensity,
        headings: content.headings,
        internalLinks: content.internalLinks
      }
    };
  }));
}

async function fetchAndAnalyzeContent(url: string) {
  const response = await fetch(url);
  const html = await response.text();
  const $ = cheerio.load(html);
  
  const text = $('body').text();
  const wordCount = text.split(/\s+/).length;
  
  return {
    wordCount,
    readabilityScore: calculateReadabilityScore(text),
    keywordDensity: calculateKeywordDensity(text),
    headings: $('h1, h2, h3').map((_, el) => $(el).text()).get(),
    internalLinks: $('a[href^="/"]').map((_, el) => $(el).attr('href')).get()
  };
}

function calculateReadabilityScore(text: string): number {
  // Implement Flesch-Kincaid readability score
  const sentences = text.split(/[.!?]+/);
  const words = text.split(/\s+/);
  const syllables = countSyllables(text);
  
  return 206.835 - 1.015 * (words.length / sentences.length) - 84.6 * (syllables / words.length);
}

function countSyllables(text: string): number {
  // Simple syllable counting algorithm
  return text.toLowerCase()
    .replace(/[^a-z]/g, '')
    .replace(/[^aeiouy]+/g, ' ')
    .trim()
    .split(' ')
    .length;
}

function calculateKeywordDensity(text: string): Map<string, number> {
  const words = text.toLowerCase().split(/\s+/);
  const density = new Map<string, number>();
  
  words.forEach(word => {
    density.set(word, (density.get(word) || 0) + 1);
  });
  
  return density;
}