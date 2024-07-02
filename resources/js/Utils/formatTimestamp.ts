interface FormatterOptions extends Intl.DateTimeFormatOptions {
  locale?: 'en-US' | 'ru-RU'
}

export function formatTimestamp(timestamp: string, options?: FormatterOptions) {
  return new Date(timestamp).toLocaleString(
    options?.locale ?? 'ru-RU',
    options ?? {
      day: '2-digit',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    },
  )
}
