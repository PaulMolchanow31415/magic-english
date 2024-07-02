<script setup>
import { Link, router } from '@inertiajs/vue3'
import ApplicationMark from '@/Shared/ApplicationMark.vue'
import { Dropdown, DropdownLink } from '@/Shared/Dropdown'
import { FwbNavbar, FwbNavbarCollapse } from 'flowbite-vue'
import { ToggleThemeButton } from '@/Shared/Buttons'
import { GlobalSearch } from '@/Widgets/GlobalSearch'
</script>

<template>
  <header>
    <FwbNavbar>
      <template #logo>
        <Link class="shrink-0" href="/">
          <ApplicationMark class="block h-9 w-auto" />
        </Link>

        <div class="hidden lg:block ms-5 me-auto flex-grow max-w-xs">
          <GlobalSearch />
        </div>
      </template>

      <template #default="{ isShowMenu }">
        <FwbNavbarCollapse :is-show-menu="isShowMenu">
          <li>
            <Link class="nav-item" :href="route('singer.list')">Музыка</Link>
          </li>
          <li>
            <Dropdown :align="isShowMenu ? 'left' : 'right'">
              <template #trigger>
                <button class="nav-item">Дополнительно</button>
              </template>

              <template #heading>Можно купить</template>

              <template #content>
                <DropdownLink :href="route('ecommerce')">
                  <Icon :icon="['fas', 'store']" class="w-4" />
                  Магазин
                </DropdownLink>
                <DropdownLink v-if="$page.props.cartItemsAmount > 0" :href="route('cart.show')">
                  <Icon :icon="['fas', 'shopping-cart']" class="w-4" />
                  Корзина ({{ $page.props.cartItemsAmount }})
                </DropdownLink>
                <DropdownLink
                  v-if="$page.props.purchasedProductsAmount > 0"
                  :href="route('purchased.lesson.all')"
                >
                  <Icon :icon="['fas', 'bag-shopping']" class="w-4" />
                  Покупки
                </DropdownLink>
              </template>
            </Dropdown>
          </li>

          <template v-if="$page.props.auth.user">
            <li v-if="$page.props.jetstream.managesProfilePhotos">
              <Dropdown :align="isShowMenu ? 'left' : 'right'">
                <template #trigger>
                  <button class="nav-item">Профиль</button>
                </template>

                <template #heading>Учетная запись</template>

                <template #content>
                  <DropdownLink :href="route('profile.show')">
                    <img
                      :alt="$page.props.auth.user.name"
                      :src="$page.props.auth.user.profile_photo_url"
                      class="-ml-0.5 h-4 w-4 rounded-full object-cover"
                    />
                    Профиль
                  </DropdownLink>
                  <DropdownLink
                    v-if="$page.props.auth.user.role === 'Администратор'"
                    :class="{ selected: route().current('admin.*') }"
                    :href="route('admin.user.index')"
                  >
                    <Icon :icon="['fas', 'database']" class="w-4" />
                    Администрирование
                  </DropdownLink>
                  <hr class="border-t border-gray-200 dark:border-gray-600" />
                  <form @submit.prevent="router.post(route('logout'))">
                    <DropdownLink as="button">
                      <Icon :icon="['fas', 'right-from-bracket']" class="w-4" />
                      Выход
                    </DropdownLink>
                  </form>
                </template>
              </Dropdown>
            </li>
          </template>
          <template v-else>
            <li>
              <Dropdown :align="isShowMenu ? 'left' : 'right'">
                <template #trigger>
                  <button class="nav-item">Вход</button>
                </template>

                <template #heading>Аутентификация</template>

                <template #content>
                  <DropdownLink :href="route('login')">
                    <Icon :icon="['fas', 'right-to-bracket']" class="w-4" />
                    Войти
                  </DropdownLink>
                  <hr class="border-t border-gray-200 dark:border-gray-600" />
                  <DropdownLink :href="route('register')">
                    <Icon :icon="['fas', 'address-card']" class="w-4" />
                    Зарегистрироваться
                  </DropdownLink>
                </template>
              </Dropdown>
            </li>
          </template>
          <li>
            <Dropdown :align="isShowMenu ? 'left' : 'right'">
              <template #trigger>
                <button class="nav-item">Обучение</button>
              </template>

              <template #content>
                <DropdownLink :href="route('skills.glossary')">
                  <Icon :icon="['fas', 'language']" class="w-4" />
                  Слова
                </DropdownLink>
                <DropdownLink :href="route('skills.courses')">
                  <Icon :icon="['fas', 'spell-check']" class="w-4" />
                  Грамматика
                </DropdownLink>
                <DropdownLink :href="route('skills.self-education')">
                  <Icon :icon="['fas', 'graduation-cap']" class="w-4" />
                  Самоучитель
                </DropdownLink>
              </template>
            </Dropdown>
          </li>
          <li v-if="isShowMenu" :class="{ 'flex w-full justify-center': isShowMenu }" class="pt-3">
            <ToggleThemeButton class="w-full">Сменить тему</ToggleThemeButton>
          </li>
        </FwbNavbarCollapse>
      </template>

      <template #right-side>
        <ToggleThemeButton class="md:ms-3" />
      </template>

      <template #menu-icon>
        <Icon :icon="['fas', 'bars']" size="lg" class="w-4" />
      </template>
    </FwbNavbar>
  </header>
</template>

<style scoped lang="postcss">
.nav-item {
  @apply block py-2 pr-4 pl-3 rounded md:p-0 text-gray-700
  md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-gray-400
  md:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white dark:hover:bg-transparent;
}
.selected {
  @apply block py-2 pr-4 pl-3 rounded md:p-0 bg-blue-700 md:bg-transparent text-white md:text-blue-700 dark:text-white;
}
</style>
