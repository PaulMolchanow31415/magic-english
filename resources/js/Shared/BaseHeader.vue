<script setup>
import { Link, router } from '@inertiajs/vue3'
import ApplicationMark from '@/Shared/ApplicationMark.vue'
import DropdownLink from '@/Shared/DropdownLink.vue'
import { ref } from 'vue'
import { FwbInput, FwbNavbar, FwbNavbarCollapse } from 'flowbite-vue'
import ToggleThemeButton from '@/Shared/ToggleThemeButton.vue'
import Dropdown from './Dropdown.vue'

const searched = ref('')
</script>

<template>
  <header>
    <FwbNavbar>
      <template #logo>
        <Link class="shrink-0" href="/">
          <ApplicationMark class="block h-9 w-auto" />
        </Link>

        <div class="ms-5 flex-grow pe-[5%] lg:pe-[25%] xl:pe-[40%] 2xl:pe-[50%]">
          <FwbInput
            v-model.lazy="searched"
            type="search"
            placeholder="Поиск..."
            class="text-gray-700 dark:text-gray-300"
            size="sm"
          >
            <template #prefix>
              <Icon class="text-gray-700 dark:text-gray-300" :icon="['fas', 'magnifying-glass']" />
            </template>
          </FwbInput>
        </div>
      </template>

      <template #default="{ isShowMenu }">
        <FwbNavbarCollapse :is-show-menu="isShowMenu">
          <li>
            <!-- :class="{ selected: route().current() }" -->
            <Link class="nav-item" href="/"> Музыка </Link>
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
                      <Icon :icon="['fas', 'database']" />
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
                        <Icon :icon="['fas', 'right-from-bracket']" />
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
                      <Icon :icon="['fas', 'right-to-bracket']" />
                      Войти
                    </span>
                  </DropdownLink>
                  <hr class="border-t border-gray-200 dark:border-gray-600" />
                  <DropdownLink :href="route('register')">
                    <span class="dropdown-line">
                      <Icon :icon="['fas', 'address-card']" />
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
                    <Icon :icon="['fas', 'language']" />
                    Слова
                  </span>
                </DropdownLink>
                <DropdownLink href="/">
                  <span class="dropdown-line">
                    <Icon :icon="['fas', 'spell-check']" />
                    Грамматика
                  </span>
                </DropdownLink>
                <DropdownLink href="/">
                  <span class="dropdown-line">
                    <Icon :icon="['fas', 'graduation-cap']" />
                    Самоучитель
                  </span>
                </DropdownLink>
              </template>
            </Dropdown>
          </li>
          <li
            v-show="isShowMenu"
            :class="{ 'flex w-full justify-center': isShowMenu }"
            class="pt-3"
          >
            <ToggleThemeButton class="w-full">Сменить тему</ToggleThemeButton>
          </li>
        </FwbNavbarCollapse>
      </template>

      <template #right-side>
        <ToggleThemeButton />
      </template>

      <template #menu-icon>
        <Icon :icon="['fas', 'bars']" size="lg" class="w-4" />
      </template>
    </FwbNavbar>
  </header>
</template>

<style scoped lang="postcss">
.selected {
  @apply block py-2 pr-4 pl-3 rounded md:p-0 bg-blue-700 md:bg-transparent text-white md:text-blue-700 dark:text-white;
}
</style>
<!--/*.nav-item {
@apply text-sm font-medium text-gray-800 dark:text-gray-200 dark:hover:text-white;
}*/-->

<!--/*const switchToTeam = (team) => {
router.put(route('current-team.update'), { team_id: team.id }, { preserveState: false })
}*/

// const showingNavigationDropdown = ref(false)-->

<!--  <header class="py-5">
  <nav class="max-w-6xl px-2.5 mx-auto flex justify-between gap-5">
    <div class="flex items-center gap-6">
      <Link class="shrink-0" href="/">
        <ApplicationMark class="block h-9 w-auto" />
      </Link>
      <FwbInput
        v-model.lazy="searched"
        type="search"
        placeholder="Поиск..."
        class="text-gray-700 dark:text-gray-300"
      >
        <template #prefix>
          <Icon class="text-gray-700 dark:text-gray-300" :icon="['fas', 'magnifying-glass']" />
        </template>
      </FwbInput>
    </div>
    &lt;!&ndash;  Controls   &ndash;&gt;
    <div class="flex gap-2.5">
      <ToggleThemeButton />

      &lt;!&ndash;  Mobile button  &ndash;&gt;
      <div class="flex items-center sm:hidden">
        <button
          class="control-btn"
          @click="showingNavigationDropdown = !showingNavigationDropdown"
        >
          <Icon v-if="showingNavigationDropdown" :icon="['fas', 'xmark']" class="lg" class="w-4" />
          <Icon v-else :icon="['fas', 'bars']" class="lg" class="w-4" />
        </button>
      </div>
    </div>
    <ul class="flex items-center gap-6">
      <li><Link href="/" class="nav-item">Музыка</Link></li>
      <template v-if="$page.props.auth.user">
        <li>
          <Dropdown align="right" width="48">
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
                    loading="lazy"
                    class="-ml-0.5 h-4 w-4 rounded-full object-cover"
                  />
                  Профиль
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
                    <Icon :icon="['fas', 'right-from-bracket']" />
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
          <Dropdown align="right" width="48">
            <template #trigger>
              <button class="nav-item">Вход</button>
            </template>

            <template #content>
              <span class="dropdown-heading">Аутентификация</span>
              <DropdownLink :href="route('login')">
                <span class="dropdown-line">
                  <Icon :icon="['fas', 'right-to-bracket']" />
                  Войти
                </span>
              </DropdownLink>
              <hr class="border-t border-gray-200 dark:border-gray-600" />
              <DropdownLink :href="route('register')">
                <span class="dropdown-line">
                  <Icon :icon="['fas', 'address-card']" />
                  Зарегистрироваться
                </span>
              </DropdownLink>
            </template>
          </Dropdown>
        </li>
      </template>
      <li>
        <Dropdown>
          <template #trigger>
            <FwbDropdown text="Тренировки" />
          </template>

          <template #content>
            <span class="dropdown-heading">Учетная запись</span>
            <DropdownLink href="/">
              <span class="dropdown-line">
                <Icon :icon="['fas', 'language']" />
                Слова
              </span>
            </DropdownLink>
            <DropdownLink href="/">
              <span class="dropdown-line">
                <Icon :icon="['fas', 'spell-check']" />
                Грамматика
              </span>
            </DropdownLink>
            <DropdownLink href="/">
              <span class="text-gray-500">Проверка навыков</span>
            </DropdownLink>
          </template>
        </Dropdown>
      </li>
    </ul>
  </nav>
</header>-->

<!--

<nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
&lt;!&ndash; Primary Navigation Menu &ndash;&gt;
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex justify-between">
  <div class="flex">
    &lt;!&ndash; Logo &ndash;&gt;
    <div class="shrink-0 flex items-center">
      <Link href="/">
        <ApplicationMark class="block h-9 w-auto" />
      </Link>
      <button class="mx-2 dark:text-white" @click="toggleDark()" type="button">
        Switch Theme
      </button>
    </div>

    &lt;!&ndash; Navigation Links &ndash;&gt;
    <Link href="/">Главная</Link>
  </div>

  <div class="hidden sm:flex sm:items-center sm:ms-6">
    <div class="ms-3 relative">
      &lt;!&ndash; Teams Dropdown &ndash;&gt;
      <Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
        <template #trigger>
          <span class="inline-flex rounded-md">
            <button
              class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150"
              type="button"
            >
              {{ $page.props.auth.user.current_team.name }}

              <svg
                class="ms-2 -me-0.5 h-4 w-4"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </button>
          </span>
        </template>

        <template #content>
          <div class="w-60">
            &lt;!&ndash; Team Management &ndash;&gt;
            <div class="block px-4 py-2 text-xs text-gray-400">Manage Team</div>

            &lt;!&ndash; Team Settings &ndash;&gt;
            <DropdownLink :href="route('teams.show', $page.props.auth.user.current_team)">
              Team Settings
            </DropdownLink>

            <DropdownLink
              v-if="$page.props.jetstream.canCreateTeams"
              :href="route('teams.create')"
            >
              Create New Team
            </DropdownLink>

            &lt;!&ndash; Team Switcher &ndash;&gt;
            <template v-if="$page.props.auth.user.all_teams.length > 1">
              <div class="border-t border-gray-200 dark:border-gray-600" />

              <div class="block px-4 py-2 text-xs text-gray-400">Switch Teams</div>

              <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                <form @submit.prevent="switchToTeam(team)">
                  <DropdownLink as="button">
                    <div class="flex items-center">
                      <svg
                        v-if="team.id === $page.props.auth.user.current_team_id"
                        class="me-2 h-5 w-5 text-green-400"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                      >
                        <path
                          d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        />
                      </svg>

                      <div>
                        {{ team.name }}
                      </div>
                    </div>
                  </DropdownLink>
                </form>
              </template>
            </template>
          </div>
        </template>
      </Dropdown>
    </div>

    &lt;!&ndash; Settings Dropdown &ndash;&gt;
    <div class="ms-3 relative">
      <Dropdown align="right" width="48">
        <template #trigger>
          <button
            v-if="$page.props.jetstream.managesProfilePhotos"
            class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
          >
            <img
              :alt="$page.props.auth.user.name"
              :src="$page.props.auth.user.profile_photo_url"
              class="h-8 w-8 rounded-full object-cover"
            />
          </button>

          <span v-else class="inline-flex rounded-md">
            <button
              class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150"
              type="button"
            >
              {{ $page.props.auth.user.name }}

              <svg
                class="ms-2 -me-0.5 h-4 w-4"
                fill="none"
                stroke="currentColor"
                stroke-width="1.5"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </button>
          </span>
        </template>

        <template #content>
          &lt;!&ndash; Account Management &ndash;&gt;
          <div class="block px-4 py-2 text-xs text-gray-400">Учетная запись</div>

          <DropdownLink :href="route('profile.show')">Профиль</DropdownLink>

          <DropdownLink
            v-if="$page.props.jetstream.hasApiFeatures"
            :href="route('api-tokens.index')"
          >
            API Ключи
          </DropdownLink>

          <div class="border-t border-gray-200 dark:border-gray-600" />

          &lt;!&ndash; Authentication &ndash;&gt;
          <form @submit.prevent="logout">
            <DropdownLink as="button">Выход</DropdownLink>
          </form>
        </template>
      </Dropdown>
    </div>
  </div>

  &lt;!&ndash; Hamburger &ndash;&gt;
  <div class="-me-2 flex items-center sm:hidden">
    <button
      class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
      @click="showingNavigationDropdown = !showingNavigationDropdown"
    >
      <Icon v-if="showingNavigationDropdown" :icon="['fas', 'xmark']" class="lg" class="w-4" />
      <Icon v-else :icon="['fas', 'bars']" class="lg" class="w-4" />
    </button>
  </div>
</div>
</div>

&lt;!&ndash; Responsive Navigation Menu &ndash;&gt;
<div
:class="{
  block: showingNavigationDropdown,
  hidden: !showingNavigationDropdown,
}"
class="sm:hidden"
>
<div class="pt-2 pb-3 space-y-1">
  <ResponsiveNavLink :active="route().current('dashboard')" :href="route('dashboard')">
    Dashboard
  </ResponsiveNavLink>
</div>

&lt;!&ndash; Responsive Settings Options &ndash;&gt;
<div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
  <div class="flex items-center px-4">
    <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 me-3">
      <img
        :alt="$page.props.auth.user.name"
        :src="$page.props.auth.user.profile_photo_url"
        class="h-10 w-10 rounded-full object-cover"
      />
    </div>

    <div>
      <div class="font-medium text-base text-gray-800 dark:text-gray-200">
        {{ $page.props.auth.user.name }}
      </div>
      <div class="font-medium text-sm text-gray-500">
        {{ $page.props.auth.user.email }}
      </div>
    </div>
  </div>

  <div class="mt-3 space-y-1">
    <ResponsiveNavLink
      :active="route().current('profile.show')"
      :href="route('profile.show')"
    >
      Profile
    </ResponsiveNavLink>

    <ResponsiveNavLink
      v-if="$page.props.jetstream.hasApiFeatures"
      :active="route().current('api-tokens.index')"
      :href="route('api-tokens.index')"
    >
      API Tokens
    </ResponsiveNavLink>

    &lt;!&ndash; Authentication &ndash;&gt;
    <form method="POST" @submit.prevent="logout">
      <ResponsiveNavLink as="button"> Выход </ResponsiveNavLink>
    </form>

    &lt;!&ndash; Team Management &ndash;&gt;
    <template v-if="$page.props.jetstream.hasTeamFeatures">
      <div class="border-t border-gray-200 dark:border-gray-600" />

      <div class="block px-4 py-2 text-xs text-gray-400">Manage Team</div>

      &lt;!&ndash; Team Settings &ndash;&gt;
      <ResponsiveNavLink
        :active="route().current('teams.show')"
        :href="route('teams.show', $page.props.auth.user.current_team)"
      >
        Team Settings
      </ResponsiveNavLink>

      <ResponsiveNavLink
        v-if="$page.props.jetstream.canCreateTeams"
        :active="route().current('teams.create')"
        :href="route('teams.create')"
      >
        Create New Team
      </ResponsiveNavLink>

      &lt;!&ndash; Team Switcher &ndash;&gt;
      <template v-if="$page.props.auth.user.all_teams.length > 1">
        <div class="border-t border-gray-200 dark:border-gray-600" />

        <div class="block px-4 py-2 text-xs text-gray-400">Switch Teams</div>

        <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
          <form @submit.prevent="switchToTeam(team)">
            <ResponsiveNavLink as="button">
              <div class="flex items-center">
                <svg
                  v-if="team.id === $page.props.auth.user.current_team_id"
                  class="me-2 h-5 w-5 text-green-400"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="1.5"
                  viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                  />
                </svg>
                <div>{{ team.name }}</div>
              </div>
            </ResponsiveNavLink>
          </form>
        </template>
      </template>
    </template>
  </div>
</div>
</div>
</nav>

&lt;!&ndash; Page Heading &ndash;&gt;
<header v-if="$slots.header" class="bg-white dark:bg-gray-800 shadow">
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
<slot name="header" />
</div>
</header>-->