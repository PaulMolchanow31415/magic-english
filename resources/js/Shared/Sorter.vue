<script setup>
import { Sortable } from 'sortablejs-vue3'
import SortableItem from '@/Classes/SortableItem.js'
import { computed } from 'vue'

const props = defineProps({
  list: {
    type: Array,
    required: true,
    validator(values) {
      return values.every((i) => i instanceof SortableItem)
    },
  },
})

const emit = defineEmits(['change-order'])

const items = computed(() => props.list)

function onReplace(event) {
  const { oldIndex, newIndex } = event
  const replaced = items.value.splice(oldIndex, 1)
  items.value.splice(newIndex, 0, ...replaced)
  emit('change-order', items.value)
}
</script>

<template>
  <Sortable :list="list" :options="{}" item-key="id" tag="div" @change="onReplace">
    <template #item="{ element }">
      <div class="py-2 px-4 transition hover:bg-gray-100 active:bg-transparent" :key="element.id">
        {{ element.name }}
      </div>
    </template>
  </Sortable>
</template>

<style scoped></style>