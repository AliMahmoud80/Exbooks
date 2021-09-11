<template>
  <div class="container mx-auto p-5 md:p-0">
    <form
      @submit.prevent="register"
      class="mt-3 md:mt-24 w-ful md:w-[28rem] mx-auto flex flex-col gap-y-2.5"
    >
      <input-default
        placeholder="Name"
        type="text"
        v-model="name"
        :clientValidationErrors="v$.name.$errors"
        :serverValidationErrors="serverValidationErrors['name']"
      >
      </input-default>
      <input-default
        placeholder="Email"
        type="text"
        v-model="email"
        :clientValidationErrors="v$.email.$errors"
        :serverValidationErrors="serverValidationErrors['email']"
      ></input-default>
      <input-default
        placeholder="Phone Number"
        type="tel"
        v-model="phone_number"
        :clientValidationErrors="v$.phone_number.$errors"
        :serverValidationErrors="serverValidationErrors['phone_number']"
      ></input-default>
      <input-default
        placeholder="Password"
        type="password"
        v-model="password"
        :clientValidationErrors="v$.password.$errors"
        :serverValidationErrors="serverValidationErrors['password']"
      ></input-default>
      <input-default
        placeholder="Confirm Password"
        v-model="password_confirmation"
        :clientValidationErrors="v$.password_confirmation.$errors"
        :serverValidationErrors="
          serverValidationErrors['password_confirmation']
        "
        type="password"
      ></input-default>
      <button-default
        :disabled="disabledButton"
        class="mt-1"
        text="Register"
      ></button-default>
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
  sameAs,
} from '@vuelidate/validators'
import InputDefault from '@/components/InputDefault'
import ButtonDefault from '@/components/ButtonDefault'

export default {
  components: { InputDefault, ButtonDefault },

  setup() {
    const store = useStore()

    const disabledButton = ref(false)
    const name = ref('')
    const email = ref('')
    const phone_number = ref('')
    const password = ref('')
    const password_confirmation = ref('')

    const rules = computed(() => ({
      name: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(20),
      },
      email: {
        required,
        email: emailValidation,
        maxLength: maxLength(255),
      },
      phone_number: {
        required,
        minLength: minLength(9),
        maxLength: maxLength(30),
      },
      password: {
        required,
        minLength: minLength(8),
        maxLength: maxLength(255),
      },
      password_confirmation: {
        required,
        sameAsPassword: sameAs(password),
      },
    }))

    const v$ = useVuelidate(rules, {
      name,
      email,
      phone_number,
      password,
      password_confirmation,
    })

    const serverValidationErrors = ref([])

    const register = () => {
      v$.value.$touch()

      if (v$.value.$invalid === true) {
        return
      }

      disabledButton.value = true

      store
        .dispatch('register', {
          name: name.value,
          email: email.value,
          phone_number: phone_number.value,
          password: password.value,
          password_confirmation: password_confirmation.value,
        })
        .then(async () => {
          const res = await store.dispatch('fetchCurrentUser')

          const user = res.data

          store.commit('setUser', user)

          window.location = '/'
        })
        .catch((e) => {
          if (e.response) {
            serverValidationErrors.value = e.response.data.errors
          }
          disabledButton.value = false
        })
    }

    return {
      name,
      email,
      phone_number,
      password,
      password_confirmation,
      v$,
      serverValidationErrors,
      register,
      disabledButton,
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
