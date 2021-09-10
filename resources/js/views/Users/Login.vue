<template>
  <div class="container mx-auto p-5 md:p-0">
    <form
      @submit.prevent="login"
      class="mt-3 md:mt-24 w-ful md:w-[28rem] mx-auto flex flex-col gap-y-2.5"
    >
      <input-default
        placeholder="Email"
        type="text"
        v-model="email"
        :clientValidationErrors="v$.email.$errors"
        :serverValidationErrors="serverValidationErrors['email']"
      ></input-default>
      <input-default
        placeholder="Password"
        type="password"
        v-model="password"
        :clientValidationErrors="v$.password.$errors"
        :serverValidationErrors="serverValidationErrors['password']"
      ></input-default>
      <button-default class="mt-1" text="login"></button-default>
    </form>
  </div>
</template>

<script>
import { computed, ref } from 'vue'
import { useStore } from 'vuex'
import { useVuelidate } from '@vuelidate/core'
import {
  required,
  email as emailValidation,
  minLength,
  maxLength,
} from '@vuelidate/validators'
import InputDefault from '@/components/InputDefault'
import ButtonDefault from '@/components/ButtonDefault'

export default {
  components: { InputDefault, ButtonDefault },

  setup() {
    const store = useStore()

    const email = ref('')
    const password = ref('')

    const rules = computed(() => ({
      email: {
        required,
        email: emailValidation,
        maxLength: maxLength(255),
      },
      password: {
        required,
        minLength: minLength(8),
        maxLength: maxLength(255),
      },
    }))

    const v$ = useVuelidate(rules, { email, password })

    const serverValidationErrors = ref([])

    const login = () => {
      v$.value.$touch()

      if (v$.value.$invalid === true) {
        return
      }

      store
        .dispatch('login', {
          email: email.value,
          password: password.value,
        })
        .then(async () => {
          const res = await store.dispatch('fetchCurrentUser')

          const user = res.data

          store.commit('setUser', user)

          window.location = '/'
        })
        .catch((e) => {
          serverValidationErrors.value = e.response.data.errors
        })
    }

    return {
      email,
      password,
      v$,
      serverValidationErrors,
      login,
    }
  },
}
</script>

<style lang="scss" scoped>
form {
  input {
    @apply h-12;
  }
}
</style>
