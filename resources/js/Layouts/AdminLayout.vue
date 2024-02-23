<script setup>
import SidebarLink from './Partials/SidebarLink.vue'
import SidebarCollapsed from '@/Layouts/Partials/SidebarCollapsed.vue'
import Dropdown from '@/Shared/Dropdown.vue'
import DropdownLink from '@/Shared/DropdownLink.vue'
</script>

<template>
  <div class="py-8 flex gap-4 items-start relative container mx-auto">
    <aside class="hidden lg:block sticky top-8 left-0 basis-1/6">
      <div class="overflow-y-auto py-5 px-3 h-full bg-white dark:bg-gray-800 rounded-lg shadow-md">
        <ul class="space-y-2">
          <li>
            <SidebarLink :to="route('admin.user.index')" icon="users" text="Пользователи" />
          </li>
          <li>
            <SidebarLink :to="route('admin.faq.index')" icon="person-circle-question" text="FAQ" />
          </li>
          <li>
            <SidebarLink :to="route('admin.subscriber.index')" icon="bell" text="Подписчики" />
          </li>
          <li>
            <SidebarLink :to="route('admin.discussion.index')" icon="comments" text="Обсуждения" />
          </li>
          <li>
            <SidebarCollapsed text="Продажа">
              <li>
                <SidebarLink
                  :to="route('admin.product.index')"
                  icon="person-chalkboard"
                  text="Продукты"
                  class="pl-6 w-full"
                />
              </li>
            </SidebarCollapsed>
          </li>
          <li>
            <SidebarLink :to="route('admin.vocabulary.index')" icon="language" text="Лексика" />
          </li>
          <li>
            <SidebarLink :to="route('admin.dictionary.index')" icon="book" text="Словари" />
          </li>
          <li>
            <SidebarLink :to="route('admin.course.index')" icon="folder-tree" text="Курсы" />
          </li>
          <li>
            <SidebarLink
              :to="route('admin.lesson.index')"
              icon="graduation-cap"
              text="Самоучитель"
            />
          </li>
        </ul>
        <ul class="collapsed-list">
          <li>
            <SidebarCollapsed text="Музыка">
              <li>
                <SidebarLink
                  class="pl-6 w-full"
                  icon="podcast"
                  text="Авторы"
                  :to="route('admin.author.index')"
                />
              </li>
              <li>
                <SidebarLink
                  class="pl-6 w-full"
                  icon="music"
                  text="Песни"
                  :to="route('admin.author.index')"
                />
              </li>
              <li>
                <SidebarLink
                  class="pl-6 w-full"
                  icon="compact-disc"
                  text="Альбомы"
                  :to="route('admin.author.index')"
                />
              </li>
            </SidebarCollapsed>
          </li>
        </ul>
        <!-- <ul class="collapsed-list">
          <li>
            <SidebarCollapsed text="">
              <li>
                <SidebarLink class="pl-6 w-full" icon="" text="" :to="route('')" />
              </li>
            </SidebarCollapsed>
          </li>
        </ul>-->
      </div>
    </aside>
    <div class="flex flex-col gap-6 flex-grow lg:basis-5/6">
      <div class="lg:hidden flex justify-center">
        <Dropdown>
          <template #trigger>
            <button
              type="button"
              class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white sm:rounded-lg border border-gray-100 dark:border-gray-700 py-1 px-2.5 transition shadow hover:shadow-lg focus:shadow-lg rounded-lg"
            >
              <span class="sr-only">Открыть меню</span>
              <Icon :icon="['fas', 'ellipsis']" />
            </button>
          </template>

          <template #content>
            <DropdownLink :href="route('admin.user.index')">
              <span class="dropdown-line">
                <Icon :icon="['fas', 'users']" class="w-4" />
                Пользователи
              </span>
            </DropdownLink>
            <DropdownLink :href="route('admin.faq.index')">
              <span class="dropdown-line">
                <Icon :icon="['fas', 'person-circle-question']" class="w-4" />
                FAQ
              </span>
            </DropdownLink>
            <DropdownLink :href="route('admin.subscriber.index')">
              <span class="dropdown-line">
                <Icon :icon="['fas', 'bell']" class="w-4" />
                Подписчики
              </span>
            </DropdownLink>
            <DropdownLink :href="route('admin.discussion.index')">
              <span class="dropdown-line">
                <Icon :icon="['fas', 'comments']" class="w-4" />
                Обсуждения
              </span>
            </DropdownLink>
            <DropdownLink :href="route('admin.vocabulary.index')">
              <span class="dropdown-line">
                <Icon :icon="['fas', 'language']" class="w-4" />
                Лексика
              </span>
            </DropdownLink>
            <DropdownLink :href="route('admin.dictionary.index')">
              <span class="dropdown-line">
                <Icon :icon="['fas', 'book']" class="w-4" />
                Словари
              </span>
            </DropdownLink>
            <DropdownLink :href="route('admin.course.index')">
              <span class="dropdown-line">
                <Icon :icon="['fas', 'folder-tree']" class="w-4" />
                Курсы
              </span>
            </DropdownLink>
            <DropdownLink :href="route('admin.lesson.index')">
              <span class="dropdown-line">
                <Icon :icon="['fas', 'graduation-cap']" class="w-4" />
                Самоучитель
              </span>
            </DropdownLink>
            <DropdownLink :href="route('admin.product.index')">
              <span class="dropdown-line">
                <Icon :icon="['fas', 'person-chalkboard']" class="w-4" />
                Продукты
              </span>
            </DropdownLink>
          </template>
        </Dropdown>
      </div>

      <slot />
    </div>
  </div>
</template>

<style scoped>
.collapsed-list {
  @apply pt-4 mt-4 space-y-2 border-t border-gray-200 dark:border-gray-700;
}
</style>
