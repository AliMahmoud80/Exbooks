<template>
  <div class="container m-auto p-5 md:p-0">
    <div class="flex flex-col md:flex-row justify-between gap-5">
      <div class="books-row flex justify-center w-full md:w-[40%]">
        <!-- Owned Books -->
        <button @click="popBook('ownedBook')">
          <i class="material-icons-outlined">remove_circle_outlined</i>
        </button>
        <button @click="pushBook('ownedBook')">
          <i class="material-icons-outlined">add_circle_outlined</i>
        </button>
        <div class="flex flex-col self-center gap-2 w-full">
          <input-default
            v-for="(_, index) in ownedBooks"
            :key="index"
            placeholder="Book name"
            v-model="ownedBooks[index].name"
            :clientValidationErrors="
              v$.ownedBooks.$each.$response.$errors[index].name
            "
            :serverValidationErrors="
              serverValidationErrors[`ownedBooks.${index}.name`]
            "
          >
          </input-default>
        </div>
      </div>
      <i
        class="
          material-icons-outlined
          justify-self-center
          hidden
          md:block
          text-center
          self-center
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
        "
        >swap_vert</i
      >
      <div class="books-row flex justify-center w-full md:w-[40%]">
        <!-- Wanted Books -->
        <button @click="popBook('wantedBook')">
          <i class="material-icons-outlined">remove_circle_outlined</i>
        </button>
        <button @click="pushBook('wantedBook')">
          <i class="material-icons-outlined">add_circle_outlined</i>
        </button>
        <div class="flex flex-col self-center gap-2 w-full">
          <input-default
            v-for="(_, index) in wantedBooks"
            :key="index"
            placeholder="Book name"
            v-model="wantedBooks[index].name"
            :clientValidationErrors="
              v$.wantedBooks.$each.$response.$errors[index].name
            "
            :serverValidationErrors="
              serverValidationErrors[`wantedBooks.${index}.name`]
            "
          ></input-default>
        </div>
      </div>
    </div>
    <textarea-default
      class="mt-6 md:mt-5 min-h-[8rem]"
      placeholder="Additional Description [optional]"
      v-model="description"
    ></textarea-default>
    <div class="flex justify-center mt-5">
      <file-pond
        class="w-full lg:w-1/2"
        ref="filePond"
        class-name="filepond"
        label-idle="Upload preview images"
        allow-multiple="true"
        accepted-file-types="image/jpeg, image/png"
      />
    </div>
    <div class="flex justify-between mt-5 gap-5 md:gap-36">
      <select-default
        :options="allCountries"
        v-model="selectedCountry"
      ></select-default>
      <select-default
        :options="allCities"
        v-model="selectedCity"
      ></select-default>
    </div>
    <button-default
      class="mt-5 float-right w-40"
      text="Add"
      @click="create"
    ></button-default>
  </div>
</template>

<script>
import { computed, onMounted, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useStore } from 'vuex'
import { useVuelidate } from '@vuelidate/core'
import {
  required,
  integer,
  maxLength,
  minLength,
  helpers,
} from '@vuelidate/validators'
import vueFilePond from 'vue-filepond'
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.esm.js'
import FilePondPluginImagePreview from 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.esm.js'
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'
import InputDefault from '@/components/InputDefault'
import TextareaDefault from '@/components/TextareaDefault'
import SelectDefault from '@/components/SelectDefault'
import ButtonDefault from '@/components/ButtonDefault'

const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview
)

export default {
  components: {
    InputDefault,
    TextareaDefault,
    SelectDefault,
    FilePond,
    ButtonDefault,
  },

  setup() {
    const store = useStore()
    const router = useRouter()
    const serverValidationErrors = ref([])

    const ownedBooks = reactive([{ name: '' }])
    const wantedBooks = reactive([{ name: '' }])

    const pushBook = (type) => {
      if (type === 'wantedBook' && wantedBooks.length < 5) {
        wantedBooks.push({ name: '' })
      }
      if (type === 'ownedBook' && ownedBooks.length < 5) {
        ownedBooks.push({ name: '' })
      }
    }
    const popBook = (type) => {
      if (type === 'wantedBook' && wantedBooks.length > 1) {
        wantedBooks.pop()
      }
      if (type === 'ownedBook' && ownedBooks.length > 1) {
        ownedBooks.pop()
      }
    }

    const description = ref()

    const filePond = ref()

    const allCountries = ref([])
    const allCities = ref([])

    const selectedCountry = ref(1)
    const selectedCity = ref(1)

    // Fetch Available countries and cities on mount.
    onMounted(() => {
      // countries
      store.dispatch('fetchCountries').then((res) => {
        allCountries.value = res.data.data
      })
      // cities
      store.dispatch('fetchCities').then((res) => {
        allCities.value = res.data.data
      })
    })

    const rules = computed(() => ({
      ownedBooks: {
        $each: helpers.forEach({
          name: {
            required,
            minLength: minLength(2),
            maxLength: maxLength(25),
          },
        }),
      },
      wantedBooks: {
        $each: helpers.forEach({
          name: {
            required,
            minLength: minLength(2),
            maxLength: maxLength(25),
          },
        }),
      },
      description: {
        minLength: minLength(2),
        maxLength: maxLength(600),
      },
      selectedCountry: {
        required,
        integer,
      },
      selectedCity: {
        required,
        integer,
      },
    }))

    const v$ = useVuelidate(rules, {
      ownedBooks,
      wantedBooks,
      description,
      selectedCountry,
      selectedCity,
    })

    // Clear vuelidate errors on page load.
    onMounted(() => {
      v$.value.ownedBooks.$each.$response.$errors[0].name = []
      v$.value.wantedBooks.$each.$response.$errors[0].name = []
    })

    // Create exchange.
    const create = () => {
      v$.value.$touch()

      if (v$.value.$invalid === true) return

      const previewImgs = filePond.value.getFiles()

      store
        .dispatch('exchange/create', {
          ownedBooks: ownedBooks,
          wantedBooks: wantedBooks,
          description: description.value,
          country: selectedCountry.value,
          city: selectedCity.value,
          previewImgs,
        })
        .then((res) => {
          const id = res.data.id
          router.push('/exchange/' + id)
        })
        .catch((e) => {
          serverValidationErrors.value = e.response.data.errors
        })
    }

    return {
      v$,
      ownedBooks,
      wantedBooks,
      pushBook,
      popBook,
      description,
      filePond,
      allCountries,
      allCities,
      selectedCountry,
      selectedCity,
      create,
      serverValidationErrors,
    }
  },
}
</script>

<style lang="scss" scoped>
button {
  @apply focus:outline-none;
}
input {
  width: 50px !important;
}
</style>
<style lang="scss">
.filepond--drop-label {
  @apply bg-black-primary text-gray-300 rounded;

  label {
    @apply font-semibold;
  }
}
.filepond--drip {
  opacity: 1 !important;
  background-color: #15181c !important;
}
</style>
