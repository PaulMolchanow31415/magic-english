<script setup>
import {
  FwbA,
  FwbAvatar,
  FwbBadge,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
  FwbToggle,
} from 'flowbite-vue'
import TableHeader from '@/Shared/TableHeader.vue'
import { ref } from 'vue'
import { Head, router, useForm, usePage } from '@inertiajs/vue3'
import Pagination from '@/Shared/Pagination.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import { useQuickEnableRef } from '@/Composables/index.js'
import { set, watchThrottled } from '@vueuse/core'
import DeleteConfirmationModal from '@/Shared/Admin/DeleteConfirmationModal.vue'
import Toaster from '@/Shared/Toaster.vue'
import Toast from '@/Classes/Toast.js'
import UpdateModal from '@/Shared/Admin/UpdateModal.vue'

const props = defineProps({
  users: Object,
  roles: Array,
  filters: Object,
})

const page = usePage()
const searchedUser = ref(props.filters.search || '')
const isShowEditModal = ref(false)
const userForRemoval = ref(null)
const form = useForm({ id: null, role: '', is_banned: false })
const userSaved = ref(false)
const userDeleted = ref(false)
const isError = ref(false)

function handleEdit(user) {
  form.id = user.id
  form.role = user.role
  form.is_banned = !!user.is_banned
  set(isShowEditModal, true)
}

function confirmDelete() {
  router.delete(route('admin.user.destroy', { id: userForRemoval.value.id }), {
    onSuccess: () => useQuickEnableRef(userDeleted),
    onError: () => useQuickEnableRef(isError),
    onFinish: () => set(userForRemoval, null),
  })
}

function confirmEdit() {
  form.post(route('admin.user.store'), {
    onSuccess: () => useQuickEnableRef(userSaved),
    onError: () => useQuickEnableRef(isError),
    onFinish: () => set(isShowEditModal, false),
  })
}

watchThrottled(
  searchedUser,
  (value) =>
    router.get(
      route('admin.user.index'),
      { search: `${value}` },
      {
        preserveState: true,
        preserveScroll: true,
        replace: true,
      },
    ),
  { throttle: 500 },
)
</script>

<template>
  <Head title="Пользователи" />

  <Toaster
    :tosts="[
      new Toast({ type: 'success', isShow: userSaved, value: 'Пользователь успешно обновлен' }),
      new Toast({ type: 'success', isShow: userDeleted, value: 'Пользователь удален!' }),
      new Toast({ type: 'warning', isShow: isError, value: 'Ошибка' }),
    ]"
  />

  <TableHeader v-model:searched-value="searchedUser" search-placeholder="Найти пользователя" />

  <FwbTable>
    <FwbTableHead>
      <FwbTableHeadCell v-text="'Фото'" />
      <FwbTableHeadCell v-text="'Имя'" />
      <FwbTableHeadCell v-text="'Email'" />
      <FwbTableHeadCell v-text="'Роль'" />
      <FwbTableHeadCell v-text="'Статус'" />
      <FwbTableHeadCell>
        <span class="sr-only">Действия</span>
      </FwbTableHeadCell>
    </FwbTableHead>
    <FwbTableBody>
      <FwbTableRow v-for="user in users.data">
        <FwbTableCell>
          <FwbAvatar :alt="user.name" :img="user.profile_photo_url" rounded />
        </FwbTableCell>
        <FwbTableCell v-text="user.name" />
        <FwbTableCell>
          <FwbA :href="`mailto:${user.email}`">{{ user.email }}</FwbA>
        </FwbTableCell>
        <FwbTableCell v-text="user.role" />
        <FwbTableCell>
          <FwbBadge
            :type="user.is_banned ? 'red' : 'green'"
            v-text="user.is_banned ? 'Заблокирован' : 'Разблокирован'"
            class="!mr-0 !rounded-md"
          />
        </FwbTableCell>
        <FwbTableCell>
          <div class="flex gap-6">
            <button
              @click="handleEdit(user)"
              type="button"
              class="text-blue-600 dark:text-blue-400 hover:underline"
            >
              Редактировать
            </button>
            <button
              @click="userForRemoval = user"
              type="button"
              class="text-red-600 dark:text-red-400 hover:underline"
            >
              Удалить
            </button>
          </div>
        </FwbTableCell>
      </FwbTableRow>
    </FwbTableBody>
  </FwbTable>

  <Pagination :data="users" />

  <!-- Delete modal -->
  <DeleteConfirmationModal
    :show="!!userForRemoval"
    @confirm="confirmDelete"
    @close="userForRemoval = null"
  >
    <template #message v-if="userForRemoval">
      Вы уверены, что хотите удалить пользователя
      <b>{{ userForRemoval.name }}</b
      >&nbsp;?
    </template>
  </DeleteConfirmationModal>

  <!-- Edit modal -->
  <UpdateModal
    title="Обновление пользователя"
    :show="isShowEditModal"
    :loading="form.processing"
    size="md"
    @confirm="confirmEdit"
    @close="isShowEditModal = false"
  >
    <div class="space-y-6">
      <InputLabel value="Роль пользователя">
        <select
          v-model="form.role"
          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
        >
          <option v-for="role in roles" :value="role" v-text="role" />
        </select>
      </InputLabel>
      <FwbToggle v-model="form.is_banned" label="Заблокировать" color="red" />
    </div>
  </UpdateModal>
</template>

<style scoped></style>

<!--
/*  .filters-dropdown-label {
      @apply flex items-center p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600;
    }
    .filters-dropdown-checkbox {
      @apply w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600
      dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500;
    }
    .filters-dropdown-text {
      @apply w-full ms-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300 select-none;
    }*/
<template #filters>
        <li>
          <label class="filters-dropdown-label">
            <input
              v-model="selectedFilters.is_banned"
              type="checkbox"
              class="filters-dropdown-checkbox"
            />
            <span class="filters-dropdown-text">Заблокированный</span>
          </label>
        </li>
        <li>
          <label class="filters-dropdown-label">
            <input
              v-model="selectedFilters.is_admin"
              type="checkbox"
              class="filters-dropdown-checkbox"
            />
            <span class="filters-dropdown-text">Администратор</span>
          </label>
        </li>
      </template>-->