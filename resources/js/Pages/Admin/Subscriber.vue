<script setup>
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
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
import { TableActionButton } from '@/Pages/Admin/Partials/TableAction'
import Pagination from '@/Widgets/Pagination.vue'
import EmailLink from '@/Shared/EmailLink.vue'
import { set } from '@vueuse/core'
import { useFlashMessages, useSearch } from '@/Composables'
import { formatTimestamp } from '@/Utils'

const props = defineProps({
  subscribers: Object,
  filters: Object,
})

const searchedSubscriber = useSearch(props.filters.search)
const subscriberForRemoval = ref(null)
const { showMessage } = useFlashMessages({ closable: true })

function confirmDelete() {
  router.delete(route('admin.subscriber.destroy', { id: subscriberForRemoval.value.id }), {
    onSuccess: () => showMessage('Подписчик успешно удален', 'success'),
    onError: () => showMessage('Ошибка', 'warning'),
    onFinish: () => set(subscriberForRemoval, null),
  })
}
</script>

<template>
  <Head title="Подписчики" />

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
      <FwbTableRow v-for="subscriber in subscribers.data" class="group">
        <FwbTableCell>
          <EmailLink :mail="subscriber.email" />
        </FwbTableCell>
        <FwbTableCell>
          <FwbBadge
            :type="subscriber.is_enabled ? 'green' : 'red'"
            v-text="subscriber.is_enabled ? 'Подписан' : 'Не подписан'"
            class="!mr-0 !rounded-md whitespace-nowrap"
          />
        </FwbTableCell>
        <FwbTableCell v-text="formatTimestamp(subscriber.created_at)" />
        <FwbTableCell class="lg:opacity-0 group-hover:opacity-100 transition duration-75">
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
