<script setup>
import { Link, router } from '@inertiajs/vue3'
import ApplicationMark from '@/Shared/ApplicationMark.vue'
import { Dropdown, DropdownLink } from '@/Shared/Dropdown'
import { FwbNavbar, FwbNavbarCollapse } from 'flowbite-vue'
import ToggleThemeButton from '@/Shared/ToggleThemeButton.vue'
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

              <template #content>
                <span class="dropdown-heading">Можно купить</span>
                <DropdownLink :href="route('ecommerce')">
                  <span class="dropdown-line">
                    <Icon :icon="['fas', 'store']" class="w-4" />
                    Магазин
                  </span>
                </DropdownLink>
                <DropdownLink v-if="$page.props.cartItemsAmount > 0" :href="route('cart.show')">
                  <span class="dropdown-line">
                    <Icon :icon="['fas', 'shopping-cart']" class="w-4" />
                    Корзина ({{ $page.props.cartItemsAmount }})
                  </span>
                </DropdownLink>
                <DropdownLink
                  v-if="$page.props.purchasedProductsAmount > 0"
                  :href="route('purchased.lesson.all')"
                >
                  <span class="dropdown-line">
                    <Icon :icon="['fas', 'bag-shopping']" class="w-4" />
                    Покупки
                  </span>
                </DropdownLink>
              </template>
            </Dropdown>
          </li>

          <template v-if="$page.props.auth.user">
            <li>
              <Dropdown :align="isShowMenu ? 'left' : 'right'">
                <template #trigger>
                  <button class="nav-item" v-if="$page.props.jetstream.managesProfilePhotos">
                    Профиль
                  </button>
                </template>

                <template #content>
                  <span class="dropdown-heading">Учетная запись</span>
                  <DropdownLink :href="route('profile.show')">
                    <span class="dropdown-line">
                      <img
                        :alt="$page.props.auth.user.name"
                        :src="$page.props.auth.user.profile_photo_url"
                        class="-ml-0.5 h-4 w-4 rounded-full object-cover"
                      />
                      Профиль
                    </span>
                  </DropdownLink>
                  <DropdownLink
                    v-if="$page.props.auth.user.role === 'Администратор'"
                    :class="{ selected: route().current('admin.*') }"
                    :href="route('admin.user.index')"
                  >
                    <span class="dropdown-line">
                      <Icon :icon="['fas', 'database']" class="w-4" />
                      Администрирование
                    </span>
                  </DropdownLink>
                  <DropdownLink
                    v-if="$page.props.jetstream.hasApiFeatures"
                    :href="route('api-tokens.index')"
                  >
                    API Ключи
                  </DropdownLink>
                  <hr class="border-t border-gray-200 dark:border-gray-600" />
                  <form @submit.prevent="router.post(route('logout'))">
                    <DropdownLink as="button">
                      <span class="dropdown-line">
                        <Icon :icon="['fas', 'right-from-bracket']" class="w-4" />
                        Выход
                      </span>
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

                <template #content>
                  <span class="dropdown-heading">Аутентификация</span>
                  <DropdownLink :href="route('login')">
                    <span class="dropdown-line">
                      <Icon :icon="['fas', 'right-to-bracket']" class="w-4" />
                      Войти
                    </span>
                  </DropdownLink>
                  <hr class="border-t border-gray-200 dark:border-gray-600" />
                  <DropdownLink :href="route('register')">
                    <span class="dropdown-line">
                      <Icon :icon="['fas', 'address-card']" class="w-4" />
                      Зарегистрироваться
                    </span>
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
                  <span class="dropdown-line">
                    <Icon :icon="['fas', 'language']" class="w-4" />
                    Слова
                  </span>
                </DropdownLink>
                <DropdownLink :href="route('skills.courses')">
                  <span class="dropdown-line">
                    <Icon :icon="['fas', 'spell-check']" class="w-4" />
                    Грамматика
                  </span>
                </DropdownLink>
                <DropdownLink :href="route('skills.self-education')">
                  <span class="dropdown-line">
                    <Icon :icon="['fas', 'graduation-cap']" class="w-4" />
                    Самоучитель
                  </span>
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
.dropdown-heading {
  @apply block px-4 py-2 text-xs text-gray-400;
}
.nav-item {
  @apply block py-2 pr-4 pl-3 rounded md:p-0 text-gray-700
  md:hover:bg-transparent md:border-0 md:hover:text-blue-700 dark:text-gray-400
  md:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white dark:hover:bg-transparent;
}
.selected {
  @apply block py-2 pr-4 pl-3 rounded md:p-0 bg-blue-700 md:bg-transparent text-white md:text-blue-700 dark:text-white;
}
</style>
