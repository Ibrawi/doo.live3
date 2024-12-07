export function calculateOpportunityScore(
  volume: number,
  difficulty: number,
  additionalFactors: {
    trend?: number;
    competition?: number;
    conversion?: number;
  } = {}
): number {
  const { trend = 1, competition = 1, conversion = 1 } = additionalFactors;
  
  // Base opportunity score (0-100)
  const baseScore = (volume / 1000) * (100 - difficulty) / 100;
  
  // Apply multipliers
  const finalScore = baseScore * trend * competition * conversion;
  
  // Normalize to 0-100 range
  return Math.min(100, Math.max(0, finalScore));
}