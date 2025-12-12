export type CurrencyCode = 'XAF' | 'EUR' | 'USD'

// Taux de conversion de 1 XAF vers la devise cible.
export const DEFAULT_RATES: Record<CurrencyCode, number> = {
  XAF: 1,
  EUR: 0.00152, // ~1 EUR = 655.96 XAF
  USD: 0.0011, // approximation, à ajuster si besoin
}

export function convertCurrency(
  amount: number,
  from: CurrencyCode,
  to: CurrencyCode,
  rates: Record<CurrencyCode, number> = DEFAULT_RATES,
) {
  if (from === to) return amount

  const fromRate = rates[from]
  const toRate = rates[to]
  if (!fromRate || !toRate) return amount

  // Passage par la base XAF.
  const amountInXaf = from === 'XAF' ? amount : amount / fromRate
  return to === 'XAF' ? amountInXaf : amountInXaf * toRate
}

export function formatCurrency(
  amount: number,
  currency: CurrencyCode,
  locale = 'fr-FR',
) {
  return new Intl.NumberFormat(locale, {
    style: 'currency',
    currency,
    maximumFractionDigits: 0,
  }).format(amount)
}

export function formatFromXaf(
  amountInXaf: number,
  to: CurrencyCode = 'XAF',
  locale = 'fr-FR',
  rates: Record<CurrencyCode, number> = DEFAULT_RATES,
) {
  const value = convertCurrency(amountInXaf, 'XAF', to, rates)
  return formatCurrency(value, to, locale)
}
