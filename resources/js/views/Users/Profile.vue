<template>
  <div class="container mx-auto p-5 md:p-0">
    <form
      v-if="edit"
      @submit.prevent="update"
      class="mt-3 md:mt-24 w-ful md:w-[28rem] mx-auto flex flex-col gap-y-2.5"
    >
      <input-default
        placeholder="Name"
        type="text"
        v-model="user.name"
        :clientValidationErrors="v$.name.$errors"
        :serverValidationErrors="serverValidationErrors['name']"
      >
      </input-default>
      <input-default
        placeholder="Email"
        type="text"
        v-model="user.email"
        :clientValidationErrors="v$.email.$errors"
        :serverValidationErrors="serverValidationErrors['email']"
      ></input-default>
      <input-default
        placeholder="Phone Number"
        type="tel"
        pattern="(01)[0-9]{9}"
        v-model="user.phoneNumber"
        :clientValidationErrors="v$.phoneNumber.$errors"
        :serverValidationErrors="serverValidationErrors['phone_number']"
      ></input-default>
      <input-default
        placeholder="Current password"
        type="password"
        v-model="user.currentPassword"
        :clientValidationErrors="v$.currentPassword.$errors"
        :serverValidationErrors="serverValidationErrors['current_password']"
      ></input-default>
      <button-default class="mt-1" text="Update"></button-default>
      <button-default
        class="mt-1"
        text="Back"
        @click="edit = false"
      ></button-default>
    </form>
    <div
      v-else-if="updatePass"
      class="mt-3 md:mt-24 w-ful md:w-[28rem] mx-auto flex flex-col gap-y-2.5"
    >
      <input-default
        placeholder="Current password"
        type="password"
        v-model="user.currentPassword"
        :clientValidationErrors="v$.currentPassword.$errors"
        :serverValidationErrors="serverValidationErrors['current_password']"
      ></input-default>
      <input-default
        placeholder="New password"
        type="password"
        v-model="user.newPassword"
        :clientValidationErrors="v$.newPassword.$errors"
        :serverValidationErrors="serverValidationErrors['new_password']"
      ></input-default>
      <input-default
        placeholder="Confirm new password"
        v-model="user.passwordConfirmation"
        :clientValidationErrors="v$.passwordConfirmation.$errors"
        :serverValidationErrors="
          serverValidationErrors['password_confrimation']
        "
        type="password"
      ></input-default>
      <button-default
        class="mt-1"
        text="Update"
        @click="update"
      ></button-default>
      <button-default
        class="mt-1"
        text="Back"
        @click="updatePass = false"
      ></button-default>
    </div>
    <div
      v-else
      class="
        mt-3
        md:mt-24
        w-ful
        md:w-[28rem]
        mx-auto
        flex flex-col
        gap-y-2.5
        font-bold
        text-center
      "
    >
      <p>{{ $store.getters.user.name }}</p>
      <p>{{ $store.getters.user.email }}</p>
      <p>{{ $store.getters.user.phone_number }}</p>
      <div class="mt-5 flex gap-2">
        <button-default
          class="w-1/2"
          text="Edit"
          @click="edit = true"
        ></button-default>
        <button-default
          class="w-1/2"
          text="Update Password"
          @click="updatePass = true"
        ></button-default>
      </div>
    </div>
    <div class="mt-16 w-ful md:w-[28rem] mx-auto">
      <user-exchanges-list />
    </div>
  </div>
</template>

<script>
import { computed, ref, reactive, watch } from 'vue'
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
import UserExchangesList from '@/components/UserExchangesList'

export default {
  components: { InputDefault, ButtonDefault, UserExchangesList },

  setup() {
    const store = useStore()

    const edit = ref(false)
    const updatePass = ref(false)

    const user = reactive({
      name: store.getters.user.name,
      email: store.getters.user.email,
      phoneNumber: store.getters.user.phone_number,
      currentPassword: '',
      newPassword: '',
      passwordConfirmation: '',
    })

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
      phoneNumber: {
        required,
        minLength: minLength(11),
        maxLength: maxLength(20),
      },
      currentPassword: {
        required,
        minLength: minLength(8),
        maxLength: maxLength(255),
      },
      newPassword: {
        required,
        minLength: minLength(8),
        maxLength: maxLength(255),
      },
      passwordConfirmation: {
        required,
        sameAsPassword: sameAs(user.newPassword),
      },
    }))

    const v$ = useVuelidate(rules, user)

    const serverValidationErrors = ref([])

    const update = () => {
      v$.value.$touch()

      if (edit.value) {
        if (
          !(
            v$.value.name.$error ||
            v$.value.email.$error ||
            v$.value.phoneNumber.$error ||
            v$.value.currentPassword.$error
          )
        ) {
          store
            .dispatch('update', { user, updatePassword: false })
            .then((res) => {
              const data = res.data

              if (data.updated) {
                store.commit('setUser', data.user)
                window.location = '/profile'
              }
            })
            .catch((e) => {
              if (e.response) {
                serverValidationErrors.value = e.response.data.errors
              }
            })
        }
      } else if (updatePass.value) {
        if (
          !(
            v$.value.currentPassword.$error ||
            v$.value.newPassword.$error ||
            v$.value.passwordConfirmation.$error
          )
        ) {
          store
            .dispatch('update', { user, updatePassword: true })
            .then((res) => {
              if (res.data.updated) {
                window.location = '/profile'
              }
            })
            .catch((e) => {
              if (e.response) {
                serverValidationErrors.value = e.response.data.errors
              }
            })
        }
      } else {
        return
      }
    }

    // Clear Errors on change of edit and updatePass modes.
    watch([edit, updatePass], () => {
      v$.value.$reset()
      serverValidationErrors.value = []
    })

    return {
      edit,
      updatePass,
      user,
      v$,
      serverValidationErrors,
      update,
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
