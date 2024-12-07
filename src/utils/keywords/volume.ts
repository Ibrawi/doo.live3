import { SerpAPI } from 'serpapi';

const serpapi = new SerpAPI(process.env.SERPAPI_API_KEY);

export async function getSearchVolume(keyword: string): Promise<number> {
  try {
    const result = await serpapi.search({
      q: keyword,
      num: 1,
      output: 'search_volume'
    });
    
    return result.search_volume || 0;
  } catch (error) {
    console.error(`Error getting search volume for ${keyword}:`, error);
    return 0;
  }
}