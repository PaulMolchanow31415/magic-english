import { type DefineComponent, warn } from 'vue'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

import BaseLayout from '../../Layouts/BaseLayout.vue'
import AdminLayout from '../../Layouts/AdminLayout.vue'
import SkillsLayout from '../../Layouts/SkillsLayout.vue'

interface IModule {
  default: {
    layout: DefineComponent[] | DefineComponent
  }
}

export default function (name: string) {
  const page = resolvePageComponent(
    `../../Pages/${name}.vue`,
    // @ts-ignore
    import.meta.glob<IModule>('../../Pages/**/*.vue'),
  )

  page.then((module) => {
    module.default.layout = <DefineComponent[]>(function () {
      const { layout } = module.default

      if (name.startsWith('Admin')) {
        return [BaseLayout, AdminLayout]
      }

      if (name.startsWith('Skills')) {
        return [BaseLayout, SkillsLayout]
      }

      if (!Array.isArray(layout)) {
        return [layout || BaseLayout]
      }

      if (layout.length) {
        return layout
      }

      warn('You have written an empty array to the layout definition (defineLayout)', module)
      return [BaseLayout]
    })()
  })

  return page
}
