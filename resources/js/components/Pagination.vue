<template>
  <div class="pagination flex gap-2 items-center justify-center">
    <button v-if="page > 1" class="mr-1" @click="$emit('updatePage', page - 1)">
      <span class="material-icons-outlined">chevron_left</span>
    </button>
    <button
      class="font-bold"
      v-for="(p, index) in count"
      :key="index"
      :class="{ active: p === page }"
      @click="$emit('updatePage', p)"
    >
      {{ p }}
    </button>
    <button
      v-if="page < count"
      class="ml-1"
      @click="$emit('updatePage', page + 1)"
    >
      <span class="material-icons-outlined">chevron_right</span>
    </button>
  </div>
</template>

<script>
export default {
  props: {
    count: Number,
    page: Number,
  },
  emits: ['updatePage'],
}
</script>

<style lang="scss">
.pagination {
  button {
    @apply bg-black-primary text-white rounded-md p-2 h-8 min-w-[2rem] flex items-center justify-center transition-all duration-150 transform hover:scale-125;

    &.active {
      @apply bg-white text-black;
    }
  }
}
</style>
