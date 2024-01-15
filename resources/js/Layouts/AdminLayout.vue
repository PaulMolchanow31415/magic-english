<script setup>
import NavbarLink from '@/Admin/NavbarLink.vue'
import NavbarCollapsed from '@/Admin/NavbarCollapsed.vue'
</script>

<template>
  <div class="py-8 flex gap-4 items-start relative container mx-auto">
    <aside class="sticky top-8 left-0 basis-1/6">
      <div class="overflow-y-auto py-5 px-3 h-full bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <ul class="space-y-2">
          <li>
            <NavbarLink :to="route('admin.user.index')" icon="users" text="Пользователи" />
          </li>
          <li>
            <NavbarLink
              :to="route('admin.faq.index')"
              icon="person-circle-question"
              text="Вопросы"
            />
          </li>
          <li>
            <NavbarCollapsed text="Категории">
              <li>
                <NavbarLink
                  class="pl-6 w-full"
                  text="Лексика"
                  :to="route('admin.vocabulary-category.index')"
                />
              </li>
            </NavbarCollapsed>
          </li>
          <li>
            <NavbarLink :to="route('admin.vocabulary.index')" icon="language" text="Лексика" />
          </li>
        </ul>
      </div>
    </aside>
    <div class="flex flex-col gap-6 flex-grow basis-5/6">
      <slot />
    </div>
  </div>
</template>