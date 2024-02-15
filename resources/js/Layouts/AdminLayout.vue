<script setup>
import SidebarLink from './Partials/SidebarLink.vue'
</script>

<template>
  <div class="py-8 flex gap-4 items-start relative container mx-auto">
    <aside class="sticky top-8 left-0 basis-1/6">
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
        <!-- <ul class="pt-4 mt-4 space-y-2 border-t border-gray-200 dark:border-gray-700">
          <li>
            <NavbarCollapsed text="">
              <li>
                <NavbarLink class="pl-6 w-full" text="" :to="route('')" />
              </li>
            </NavbarCollapsed>
          </li>
        </ul>-->
      </div>
    </aside>
    <div class="flex flex-col gap-6 flex-grow basis-5/6">
      <slot />
    </div>
  </div>
</template>
