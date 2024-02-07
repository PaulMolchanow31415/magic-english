<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import Toaster from '@/Shared/Toaster.vue'
import Toast from '@/Classes/Toast.js'
import TableHeader from '@/Pages/Admin/Partials/TableHeader.vue'
import {
  FwbBadge,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'
import TableActionButton from '@/Pages/Admin/Partials/TableActionButton.vue'
import Pagination from '@/Shared/Pagination.vue'
import EmailLink from '@/Shared/EmailLink.vue'
import { set } from '@vueuse/core'
import { useSearch } from '@/Composables/useSearch.js'
import { useQuickEnableRef } from '@/Composables/useQuickEnableRef.js'

const props = defineProps({
  subscribers: Object,
  filters: Object,
})

const searchedSubscriber = useSearch('admin.subscriber.index', props.filters.search)
const isDeleted = ref(false)
const isError = ref(false)
const subscriberForRemoval = ref(null)

function confirmDelete() {
  router.delete(route('admin.subscriber.destroy', { id: subscriberForRemoval.value.id }), {
    onSuccess: () => useQuickEnableRef(isDeleted),
    onError: () => useQuickEnableRef(isDeleted),
    onFinish: () => set(subscriberForRemoval, null),
  })
}
</script>

<template>
  <Head title="Подписчики" />

  <Toaster
    :tosts="[
      new Toast({ type: 'success', isShow: isDeleted, value: 'Подписчик успешно удален' }),
      new Toast({ type: 'warning', isShow: isError, value: 'Ошибка' }),
    ]"
  />

  <TableHeader v-model:searched-value="searchedSubscriber" search-placeholder="Найти подписчика" />

  <FwbTable>
    <FwbTableHead>
      <FwbTableHeadCell v-text="'Email'" />
      <FwbTableHeadCell v-text="'Статус'" />
      <FwbTableHeadCell v-text="'Дата создания'" />
      <FwbTableHeadCell>
        <span class="sr-only">Действия</span>
      </FwbTableHeadCell>
    </FwbTableHead>
    <FwbTableBody>
      <FwbTableRow v-for="subscriber in subscribers.data">
        <FwbTableCell>
          <EmailLink :mail="subscriber.email" />
        </FwbTableCell>
        <FwbTableCell>
          <FwbBadge
            :type="subscriber.is_enabled ? 'green' : 'red'"
            v-text="subscriber.is_enabled ? 'Подписан' : 'Не подписан'"
            class="!mr-0 !rounded-md"
          />
        </FwbTableCell>
        <FwbTableCell v-text="subscriber.created_at" />
        <FwbTableCell>
          <TableActionButton @click="subscriberForRemoval = subscriber" theme="red">
            Удалить
          </TableActionButton>
        </FwbTableCell>
      </FwbTableRow>
    </FwbTableBody>
  </FwbTable>

  <Pagination :data="subscribers" />

  <!-- Delete modal -->
  <DeleteConfirmationModal
    :show="!!subscriberForRemoval"
    @confirm="confirmDelete"
    @close="subscriberForRemoval = null"
  >
    <template #message v-if="subscriberForRemoval">
      Вы уверены, что хотите удалить подписчика
      <b>{{ subscriberForRemoval.email }}</b
      >&nbsp;?
    </template>
  </DeleteConfirmationModal>
</template>

<style scoped></style>