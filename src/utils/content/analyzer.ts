import natural from 'natural';
import cheerio from 'cheerio';
import type { ContentAnalysis, HeadingStructure } from '../../types/content';

export async function analyzeContent(url: string): Promise<ContentAnalysis> {
  const content = await fetchContent(url);
  const $ = cheerio.load(content);
  
  return {
    wordCount: countWords($),
    readabilityScore: calculateReadability($),
    keywordDensity: analyzeKeywordDensity($),
    headings: analyzeHeadings($),
    internalLinks: findInternalLinks($),
    topics: extractTopics($),
    entities: extractEntities($)
  };
}

function countWords($: cheerio.Root): number {
  return $('body').text().split(/\s+/).length;
}

function calculateReadability($: cheerio.Root): number {
  const text = $('body').text();
  const tokenizer = new natural.SentenceTokenizer();
  const sentences = tokenizer.tokenize(text);
  
  return natural.FleschKincaid.grade(sentences.join(' '));
}

function analyzeKeywordDensity($: cheerio.Root): Map<string, number> {
  const text = $('body').text().toLowerCase();
  const words = text.split(/\s+/);
  const density = new Map<string, number>();
  
  words.forEach(word => {
    density.set(word, (density.get(word) || 0) + 1);
  });
  
  return density;
}

function analyzeHeadings($: cheerio.Root): HeadingStructure[] {
  const headings: HeadingStructure[] = [];
  
  $('h1, h2, h3, h4, h5, h6').each((_, element) => {
    headings.push({
      level: parseInt(element.tagName[1]),
      text: $(element).text().trim()
    });
  });
  
  return headings;
}

function findInternalLinks($: cheerio.Root): string[] {
  const links: string[] = [];
  
  $('a[href^="/"], a[href^="' + process.env.SITE_URL + '"]').each((_, element) => {
    links.push($(element).attr('href') || '');
  });
  
  return links;
}

function extractTopics($: cheerio.Root): string[] {
  const text = $('body').text();
  const tfidf = new natural.TfIdf();
  
  tfidf.addDocument(text);
  
  return tfidf
    .listTerms(0)
    .slice(0, 10)
    .map(term => term.term);
}

function extractEntities($: cheerio.Root): string[] {
  const text = $('body').text();
  const tokenizer = new natural.WordTokenizer();
  const tokens = tokenizer.tokenize(text);
  
  return natural.BrillPOSTagger
    .tag(tokens)
    .taggedWords
    .filter(word => word.tag.startsWith('NN'))
    .map(word => word.token);
}