<template>
  <div
    v-if="exchange"
    class="
      container
      m-auto
      p-5
      md:p-0
      flex flex-col
      lg:flex-row-reverse lg:justify-between
    "
  >
    <div
      class="w-full lg:w-[25%] mb-auto font-bold bg-black-primary rounded p-5"
    >
      <p class="text-blue-400">{{ exchange.user.name }}</p>
      <p>{{ exchange.user.email }}</p>
      <p>{{ exchange.user.phone_number }}</p>
      <div class="mt-3 flex justify-between items-center">
        <p class="font-bold">{{ exchange.country }}, {{ exchange.city }}</p>
        <p class="font-bold">{{ getDate }}</p>
      </div>
    </div>
    <div class="w-full lg:w-[70%] my-5 lg:mt-0 bg-black-primary rounded p-5">
      <div
        class="
          flex flex-col
          md:flex-row
          items-center
          justify-center
          md:justify-between
        "
      >
        <div class="flex flex-col">
          <p
            class="font-bold text-center mb-2"
            v-for="book in exchange.ownedBooks"
            :key="book.id"
          >
            {{ book.name }}
          </p>
        </div>
        <i
          class="
            material-icons-outlined
            justify-self-center
            hidden
            md:block
            text-center
            self-center
            mx-2
          "
          >swap_horiz</i
        >
        <i
          class="
            material-icons-outlined
            justify-self-center
            md:hidden
            text-center
            self-center
            my-5
          "
          >swap_vert</i
        >
        <div class="flex flex-col">
          <p
            class="font-bold text-center mb-2"
            v-for="book in exchange.wantedBooks"
            :key="book.id"
          >
            {{ book.name }}
          </p>
        </div>
      </div>
      <p class="mt-5" v-if="exchange.description">
        {{ exchange.description }}
      </p>
      <div
        class="mt-10 w-full flex flex-col justify-center items-center"
        v-if="exchange.previews.length > 0"
      >
        <img
          class="w-full max-w-[500px] h-full mb-4"
          v-for="(img, i) in exchange.previews"
          :key="i"
          :src="'data:image/png;base64,' + img.image"
        />
      </div>
    </div>
  </div>
  <div v-else class="container m-auto p-5 md:p-0 font-bold text-center">
    Not Found
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useStore } from 'vuex'
import moment from 'moment'

export default {
  setup() {
    const route = useRoute()
    const store = useStore()

    const exchange = ref()

    store.dispatch('exchange/fetch', route.params.id).then((res) => {
      exchange.value = res.data.data
    })

    const getDate = computed(() => {
      return moment(exchange.value.date).fromNow()
    })

    return {
      exchange,
      getDate,
    }
  },
}
</script>

<style></style>
