import { warn } from 'vue'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

import BaseLayout from '@/Layouts/BaseLayout.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import SkillsLayout from '@/Layouts/SkillsLayout.vue'

export default function(name) {
  const page = resolvePageComponent(`../Pages/${name}.vue`, import.meta.glob('../Pages/**/*.vue'))

  page.then((module) => {
    module.default.layout = (function() {
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

      warn('Вы записали пустой массив в опцию layout', layout)
      return [BaseLayout]
    })()
  })

  return page
}
