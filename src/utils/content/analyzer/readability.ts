import { countSyllables } from './syllables';

export function calculateReadabilityScore(text: string): number {
  const sentences = text.split(/[.!?]+/);
  const words = text.split(/\s+/);
  const syllables = countSyllables(text);
  
  return 206.835 - 1.015 * (words.length / sentences.length) - 84.6 * (syllables / words.length);
}

export function analyzeReadability(text: string) {
  const score = calculateReadabilityScore(text);
  return {
    score,
    level: getReadabilityLevel(score),
    suggestions: getReadabilitySuggestions(score)
  };
}

function getReadabilityLevel(score: number): string {
  if (score >= 90) return 'Very Easy';
  if (score >= 80) return 'Easy';
  if (score >= 70) return 'Fairly Easy';
  if (score >= 60) return 'Standard';
  if (score >= 50) return 'Fairly Difficult';
  if (score >= 30) return 'Difficult';
  return 'Very Difficult';
}

function getReadabilitySuggestions(score: number): string[] {
  const suggestions: string[] = [];
  if (score < 60) {
    suggestions.push('Use shorter sentences');
    suggestions.push('Choose simpler words');
    suggestions.push('Break up long paragraphs');
  }
  return suggestions;
}