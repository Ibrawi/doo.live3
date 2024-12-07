import fetch from 'node-fetch';
import puppeteer from 'puppeteer';

export async function fetchContent(url: string): Promise<string> {
  try {
    // Try simple fetch first
    const response = await fetch(url);
    if (response.ok) {
      return await response.text();
    }
    
    // If fetch fails, use Puppeteer for JavaScript-rendered content
    return await fetchWithPuppeteer(url);
  } catch (error) {
    console.error(`Error fetching content from ${url}:`, error);
    throw error;
  }
}

async function fetchWithPuppeteer(url: string): Promise<string> {
  const browser = await puppeteer.launch({
    headless: 'new',
    args: ['--no-sandbox']
  });
  
  try {
    const page = await browser.newPage();
    await page.goto(url, { waitUntil: 'networkidle0' });
    const content = await page.content();
    return content;
  } finally {
    await browser.close();
  }
}