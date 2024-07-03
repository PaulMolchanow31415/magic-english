import { App, Plugin } from 'vue'

interface FormatterOptions extends Intl.DateTimeFormatOptions {
  locale?: 'en-US' | 'ru-RU'
}

export class Helpers {
  shuffle<T>(array: T[]) {
    return array.sort(() => Math.random() - 0.5)
  }

  randomInt(min: number, max: number) {
    const minCeiled = Math.ceil(min)
    const maxFloored = Math.floor(max)
    return Math.floor(Math.random() * (maxFloored - minCeiled + 1) + minCeiled)
  }

  formatTimestamp(timestamp: string | number, options?: FormatterOptions) {
    // prettier-ignore
    return new Date(timestamp).toLocaleString(options?.locale ?? 'ru-RU', options ?? {
      day: '2-digit',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
    })
  }

  avatarInitials(name = '-') {
    const _ = name.trim().split(' ')
    const s = _[0].charAt(0)
    return (_.length > 1 ? s + _[1].charAt(0) : s).toUpperCase()
  }
}

export const HelpersPlugin: Plugin = {
  install(app: App) {
    app.config.globalProperties.$helpers = new Helpers()
  },
}
