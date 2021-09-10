<template>
  <div class="container mx-auto">
    <form @submit="performSearch" class="w-10/12 mx-auto py-2 md:hidden">
      <input-default
        v-model="search"
        placeholder="Looking for something ?"
        class="text-center"
      ></input-default>
    </form>
    <div v-if="$store.getters['exchange/exchanges'].length > 0">
      <div class="flex flex-wrap justify-center px-5 md:p-0">
        <card
          v-for="exchange in $store.getters['exchange/exchanges']"
          :key="exchange.id"
          :id="exchange.id"
          :wantedBooks="exchange.wantedBooks"
          :ownedBooks="exchange.ownedBooks"
          :Country="exchange.country"
          :City="exchange.city"
          :date="exchange.date"
        ></card>
      </div>
      <div class="flex justify-center my-5">
        <Pagination
          v-if="$store.getters['exchange/pagination']['last_page'] > 1"
          :count="$store.getters['exchange/pagination']['last_page']"
          :page="$store.getters['exchange/pagination']['current_page']"
          @updatePage="updatePage"
        />
      </div>
    </div>
    <div v-else class="pt-10">
      <h1 class="font-bold w-full text-center">No Exchanges Found.</h1>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, watchEffect } from 'vue'
import { useStore } from 'vuex'
import Card from '@/components/Card'
import Pagination from '@/components/Pagination'
import InputDefault from '@/components/InputDefault'

export default {
  components: { Card, Pagination, InputDefault },

  setup() {
    const store = useStore()
    const search = ref('')

    const performSearch = (e) => {
      e.preventDefault()
      store.dispatch('exchange/search', { key: search.value, page: 1 })
    }

    watchEffect(() => {
      // Clear Search Result.
      if (search.value.length === 0) {
        store.commit('exchange/clearSearch')
      }
    })

    onMounted(() => {
      store.dispatch('exchange/fetchAll', 1)
    })

    const updatePage = (page) => {
      store.dispatch('exchange/paginate', page)
    }

    return {
      updatePage,
      search,
      performSearch,
    }
  },
}
</script>

<style></style>
