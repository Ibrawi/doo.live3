export function countSyllables(text: string): number {
  return text.toLowerCase()
    .replace(/[^a-z]/g, '')
    .replace(/[^aeiouy]+/g, ' ')
    .trim()
    .split(' ')
    .length;
}

export function analyzeSyllables(word: string): number {
  const subWords = word.toLowerCase().split(/[-_]/);
  return subWords.reduce((count, subWord) => {
    return count + countWordSyllables(subWord);
  }, 0);
}

function countWordSyllables(word: string): number {
  word = word.toLowerCase();
  if (word.length <= 3) return 1;
  
  word = word.replace(/(?:[^laeiouy]es|ed|[^laeiouy]e)$/, '');
  word = word.replace(/^y/, '');
  
  const syllableMatches = word.match(/[aeiouy]{1,2}/g);
  return syllableMatches ? syllableMatches.length : 1;
}