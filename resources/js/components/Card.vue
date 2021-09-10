<template>
  <router-link :to="'/exchange/' + id" class="w-full p-2 md:w-2/4 lg:w-1/3">
    <div class="card h-full flex flex-col bg-black-primary rounded-md p-3">
      <div class="card-content flex flex-grow items-center justify-between">
        <div class="wanted-books w-[40%] flex flex-col p-3">
          <p v-for="book in ownedBooks" class="text-center mb-2" :key="book.id">
            {{ book.name }}
          </p>
        </div>
        <span class="material-icons-outlined p-3 self-center">swap_horiz</span>
        <div class="owned-books w-[40%] flex flex-col p-3">
          <p
            v-for="book in wantedBooks"
            class="text-center mb-2"
            :key="book.id"
          >
            {{ book.name }}
          </p>
        </div>
      </div>
      <div class="card-footer w-full flex justify-between px-3 pt-2">
        <p class="location w-[40%] text-center font-bold text-sm">
          {{ Country }}, {{ City }}
        </p>
        <p class="location w-[40%] text-center font-bold text-sm">
          {{ getDate }}
        </p>
      </div>
    </div>
  </router-link>
</template>

<script>
import { computed } from 'vue'
import moment from 'moment'

export default {
  props: {
    id: Number,
    wantedBooks: Array,
    ownedBooks: Array,
    City: String,
    Country: String,
    date: String,
  },

  setup(props) {
    const getDate = computed(() => {
      return moment(props.date).fromNow()
    })

    return {
      getDate,
    }
  },
}
</script>

<style lang="scss" scoped>
.wanted-books,
.owned-books {
  @apply font-semibold;
}
.wanted-books p:last-child {
  @apply mb-0;
}
</style>
