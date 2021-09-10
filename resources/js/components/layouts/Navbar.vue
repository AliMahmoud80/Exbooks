<template>
  <nav class="w-full fixed top-0 z-50">
    <ul class="container mx-auto flex justify-between items-center">
      <li class="app-name font-extrabold text-lg">
        <router-link class="bg-primary-400" to="/">EXBooks</router-link>
      </li>
      <div class="hidden md:block min-w-[24rem]">
        <form @submit="performSearch">
          <input-default
            class="text-center"
            placeholder="Looking for something ?"
            type="text"
            v-model="search"
          ></input-default>
        </form>
      </div>
      <div v-if="$store.getters.authenticated" class="flex">
        <li>
          <router-link to="/profile">
            <span class="material-icons-outlined"> person </span>
          </router-link>
        </li>
        <li>
          <router-link :to="{ name: 'create-exchange' }">
            <span class="material-icons-outlined"> add </span>
          </router-link>
        </li>
      </div>
      <div v-if="!$store.getters.authenticated" class="flex">
        <li>
          <router-link class="font-bold text-primary-400" to="/register">
            Register
          </router-link>
        </li>
        <li>
          <router-link class="font-bold" to="/login"> Login </router-link>
        </li>
      </div>
    </ul>
  </nav>
</template>

<script>
import { ref, watchEffect } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'
import InputDefault from '../InputDefault'

export default {
  components: { InputDefault },

  setup() {
    const store = useStore()
    const router = useRouter()

    const search = ref('')

    const performSearch = (e) => {
      e.preventDefault()
      store.dispatch('exchange/search', { key: search.value, page: 1 })
      router.push('/')
    }

    watchEffect(() => {
      // Clear Search Result.
      if (search.value.length === 0) {
        store.commit('exchange/clearSearch')
      }
    })

    return {
      search,
      performSearch,
    }
  },
}
</script>

<style lang="scss" scoped>
nav {
  backdrop-filter: saturate(180%) blur(5px);
  box-shadow: inset 0 -1px 0 0 hsla(0, 0%, 100%, 0.1);

  ul {
    .app-name {
      @apply lg:pl-0;

      a {
        background-clip: text;
        -webkit-text-fill-color: transparent;
        letter-spacing: 1.5px;
      }
    }
    li {
      @apply py-4 px-2;
    }
  }
}
</style>
