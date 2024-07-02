<script setup>
import {
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
import TableHeader from './Partials/TableHeader.vue'
import { ref } from 'vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import Pagination from '@/Widgets/Pagination.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import DeleteConfirmationModal from './Partials/DeleteConfirmationModal.vue'
import UpdateModal from './Partials/UpdateModal.vue'
import { TableActionButton, TableActionRow } from '@/Pages/Admin/Partials/TableAction'
import EmailLink from '@/Shared/EmailLink.vue'
import { useFlashMessages, useSearch } from '@/Composables'
import { avatarInitials } from '@/Utils'

const props = defineProps({
  users: Object,
  roles: Array,
  filters: Object,
})

const { showMessage } = useFlashMessages({ closable: true })
const searchedUser = useSearch(props.filters.search)
const isShowEditModal = ref(false)
const userForRemoval = ref(null)
const form = useForm({ id: null, role: '', is_banned: false })

function handleEdit(user) {
  form.id = user.id
  form.role = user.role
  form.is_banned = !!user.is_banned
  isShowEditModal.value = true
}

function confirmDelete() {
  router.delete(route('admin.user.destroy', { id: userForRemoval.value.id }), {
    onSuccess: () => showMessage('Пользователь удален!', 'success'),
    onError: () => showMessage('Не удалось удалить пользователя', 'warning'),
    onFinish: () => (userForRemoval.value = null),
  })
}

function confirmEdit() {
  form.post(route('admin.user.store'), {
    onSuccess: () => showMessage('Пользователь успешно обновлен', 'success'),
    onError: () => showMessage('Ошибка, не удалось удалить пользователя'),
    onFinish: () => (isShowEditModal.value = false),
  })
}
</script>

<template>
  <Head title="Пользователи" />

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
      <FwbTableRow v-for="user in users.data" class="group">
        <FwbTableCell>
          <FwbAvatar
            :alt="user.name"
            :img="user.profile_photo_url"
            rounded
            size="sm"
            :initials="avatarInitials(user.name)"
          />
        </FwbTableCell>
        <FwbTableCell v-text="user.name" />
        <FwbTableCell>
          <EmailLink :mail="user.email" />
        </FwbTableCell>
        <FwbTableCell v-text="user.role" />
        <FwbTableCell>
          <FwbBadge
            :type="user.is_banned ? 'red' : 'green'"
            v-text="user.is_banned ? 'Заблокирован' : 'Разблокирован'"
            class="!mr-0 !rounded-md"
          />
        </FwbTableCell>
        <FwbTableCell class="lg:opacity-0 group-hover:opacity-100 transition duration-75">
          <TableActionRow>
            <TableActionButton @click="handleEdit(user)">Редактировать</TableActionButton>
            <TableActionButton @click="userForRemoval = user" theme="red">
              Удалить
            </TableActionButton>
          </TableActionRow>
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
        <select v-model="form.role">
          <option v-for="role in roles" :value="role" v-text="role" />
        </select>
      </InputLabel>
      <FwbToggle v-model="form.is_banned" label="Заблокировать" color="red" />
    </div>
  </UpdateModal>
</template>

<style scoped></style>
