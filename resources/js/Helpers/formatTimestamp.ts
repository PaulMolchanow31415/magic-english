type DateLocale = 'en-US' | 'ru-RU'

export default function (timestamp: string, locale?: DateLocale) {
  return new Date(timestamp).toLocaleString(locale ?? 'ru-RU', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}
