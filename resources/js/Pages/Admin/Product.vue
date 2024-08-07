<script setup>
import { Head, router, useForm } from '@inertiajs/vue3'
import { reactive, ref, watchEffect } from 'vue'
import { useFlashMessages, usePrice, useSearch } from '@/Composables'
import TableHeader from '@/Pages/Admin/Partials/TableHeader.vue'
import {
  FwbA,
  FwbAvatar,
  FwbButton,
  FwbInput,
  FwbRange,
  FwbTable,
  FwbTableBody,
  FwbTableCell,
  FwbTableHead,
  FwbTableHeadCell,
  FwbTableRow,
} from 'flowbite-vue'
import { TableActionButton, TableActionRow } from '@/Pages/Admin/Partials/TableAction'
import Pagination from '@/Widgets/Pagination.vue'
import UpdateModal from '@/Pages/Admin/Partials/UpdateModal.vue'
import PhotoUploader from '@/Pages/Admin/Partials/PhotoUploader.vue'
import InputLabel from '@/Shared/InputLabel.vue'
import TextRedactor from '@/Shared/TextRedactor.vue'
import PriceInput from '@/Pages/Admin/Partials/PriceInput.vue'
import DeleteConfirmationModal from '@/Pages/Admin/Partials/DeleteConfirmationModal.vue'

const props = defineProps({
  products: Object,
  filters: Object,
})

const searchedProduct = useSearch(props.filters.search)
const productForRemoval = ref(null)
const { showMessage } = useFlashMessages({ closable: true })

const form = useForm({
  id: null,
  name: '',
  content: '',
  price: 10,
  stripe_price_id: '',
  photo: null,
  photo_external_path: null,
})

const { price, min, max, clear } = usePrice(form.price)

const editable = reactive({
  isShowModal: false,
  poster_url: '',
})

function handleCreate() {
  editable.isShowModal = true
  editable.poster_url = null
  form.reset()
}

function handleEdit(product) {
  form.id = product.id
  form.name = product.name
  form.content = product.content
  form.price = product.price
  form.stripe_price_id = product.stripe_price_id
  form.photo = null
  form.photo_external_path = null
  editable.poster_url = product.poster_url
  editable.isShowModal = true
}

function confirmUpdate() {
  form.post(route('admin.product.store'), {
    preserveScroll: true,
    onSuccess() {
      showMessage('Продукт успешно сохранен', 'success')
      editable.isShowModal = false
      editable.poster_url = null
      form.reset()
    },
    onError: () => showMessage('Ошибка', 'warning'),
  })
}

function confirmDelete() {
  form.delete(route('admin.product.destroy', productForRemoval.value.id), {
    onSuccess: () => showMessage('Продукт удален', 'success'),
    onError: () => showMessage('Ошибка', 'warning'),
    onFinish: () => (productForRemoval.value = null),
    preserveScroll: true,
    preserveState: true,
  })
}

function deletePoster() {
  router.put(
    route('admin.product.delete-poster'),
    { filename: editable.poster_url },
    {
      onSuccess: () => {
        editable.poster_url = null
        form.photo_external_path = null
      },
      preserveScroll: true,
    },
  )
}

watchEffect(() => (form.price = price.value))
</script>

<template>
  <Head title="Продукт" />

  <TableHeader
    v-model:searched-value="searchedProduct"
    search-placeholder="Найти продукт"
    addable
    @add="handleCreate"
  />

  <FwbTable striped>
    <FwbTableHead>
      <FwbTableHeadCell v-text="'Фото'" />
      <FwbTableHeadCell v-text="'Название'" />
      <FwbTableHeadCell>Цена&nbsp;₽</FwbTableHeadCell>
      <FwbTableHeadCell>
        <span class="sr-only">Действия</span>
      </FwbTableHeadCell>
    </FwbTableHead>
    <FwbTableBody>
      <FwbTableRow v-for="product in products.data" :key="product.id" class="group">
        <FwbTableCell>
          <FwbAvatar
            size="xs"
            :alt="product.name"
            :img="product.poster_url"
            :initials="$helpers.avatarInitials(product.name)"
          />
        </FwbTableCell>
        <FwbTableCell v-text="product.name" />
        <FwbTableCell v-text="product.price" />
        <FwbTableCell class="lg:opacity-0 group-hover:opacity-100 transition duration-75">
          <TableActionRow class="pe-4">
            <TableActionButton @click="handleEdit(product)">Редактировать</TableActionButton>
            <TableActionButton theme="red" @click="productForRemoval = product">
              Удалить
            </TableActionButton>
          </TableActionRow>
        </FwbTableCell>
      </FwbTableRow>
    </FwbTableBody>
  </FwbTable>

  <Pagination :data="products" />

  <!-- Edit modal -->
  <UpdateModal
    size="4xl"
    title="Обновление продукта"
    :show="editable.isShowModal"
    :loading="form.processing"
    @confirm="confirmUpdate"
    @close="editable.isShowModal = false"
  >
    <div class="grid grid-cols-2 gap-y-6 gap-x-3">
      <InputLabel value="Название">
        <FwbInput v-model="form.name" :validation-status="form.errors.name ? 'error' : ''">
          <template #validationMessage>{{ form.errors.name }}</template>
        </FwbInput>
      </InputLabel>

      <InputLabel value="Stripe price id">
        <FwbInput
          placeholder="price_xxx"
          v-model="form.stripe_price_id"
          :validation-status="form.errors.stripe_price_id ? 'error' : ''"
        >
          <template #validationMessage>{{ form.errors.stripe_price_id }}</template>
        </FwbInput>
      </InputLabel>

      <PriceInput v-model="price" :min="min" :error="form.errors.price" />

      <div class="place-content-center flex items-center gap-4">
        <FwbRange v-model.number="price" :min="min" :max="max" label="" class="grow" />
        <FwbButton @click="clear" type="button" square class="px-4 shrink-0">
          <span class="sr-only">Очистить</span>
          <Icon :icon="['fas', 'brush']" />
        </FwbButton>
      </div>

      <div class="col-span-2">
        <TextRedactor
          toolbar-style="full"
          v-model="form.content"
          placeholder="Содержание"
          :error="form.errors.content"
        />
      </div>

      <PhotoUploader
        class="col-span-2"
        v-model="form.photo"
        v-model:external-path="form.photo_external_path"
        :has-photo="!!editable.poster_url"
        @delete-photo="deletePoster"
      />
    </div>
  </UpdateModal>

  <!-- Delete modal -->
  <DeleteConfirmationModal
    :show="!!productForRemoval"
    @confirm="confirmDelete"
    @close="productForRemoval = null"
  >
    <template #message v-if="productForRemoval">
      Вы уверены, что хотите удалить продукт
      <b>{{ productForRemoval.name }}</b
      >&nbsp;? Для полного удаления из системы зайдите на
      <FwbA href="https://dashboard.stripe.com/test/products">Stripe</FwbA> и удалите его там.
    </template>
  </DeleteConfirmationModal>
</template>

<style scoped></style>
