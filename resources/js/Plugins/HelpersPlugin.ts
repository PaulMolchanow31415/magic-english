import { App, Plugin } from 'vue'

interface FormatterOptions extends Intl.DateTimeFormatOptions {
  locale?: 'en-US' | 'ru-RU'
}

export class Helpers {
  private static Holder = class {
    static readonly HOLDER_INSTANCE = new Helpers()
  }

  static getInstance() {
    return this.Holder.HOLDER_INSTANCE
  }

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

  generateRandomId(size = 7) {
    return Math.random()
      .toString(36)
      .slice(2, 2 + size)
  }
}

export const HelpersPlugin: Plugin = {
  install(app: App) {
    app.config.globalProperties.$helpers = Helpers.getInstance()
  },
}
