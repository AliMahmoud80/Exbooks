<template>
  <div class="flex flex-col gap-y-2.5">
    <div
      v-for="(exchange, i) in exchanges"
      :key="i"
      class="flex items-center justify-between gap-x-2 mt-1"
    >
      <router-link :to="'/exchange/' + exchange.id">
        <p>{{ formatedNames(i) }}</p>
      </router-link>
      <button-default
        class="from-red-500 via-red-500 to-red-500"
        text="Delete"
        @click="deleteExchange(exchange.id)"
      ></button-default>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useStore } from 'vuex'
import ButtonDefault from '@/components/ButtonDefault'

export default {
  components: { ButtonDefault },

  setup() {
    const store = useStore()
    const exchanges = ref()

    onMounted(() => {
      store.dispatch('exchange/fetchUserExchanges').then((res) => {
        exchanges.value = res.data
      })
    })

    const formatedNames = (i) => {
      let names = []

      exchanges.value[i]['ownedBooks'].forEach((book) => {
        names.push(book.name)
      })

      names = names.toLocaleString().replaceAll(',', ', ')

      return names
    }

    const deleteExchange = (id) => {
      store.dispatch('exchange/delete', id).then((res) => {
        const data = res.data

        if (data.deleted) {
          exchanges.value = exchanges.value.filter((e) => {
            if (e.id !== data.exchange_id) {
              return e
            }
          })
        }
      })
    }

    return {
      exchanges,
      formatedNames,
      deleteExchange,
    }
  },
}
</script>

<style></style>
