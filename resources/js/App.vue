<template>
  <app-navbar></app-navbar>
  <Loading v-if="$store.state.loading" />
  <router-view class="mt-20" />
</template>

<script>
import AppNavbar from '@/components/layouts/Navbar'
import Loading from '@/views/Loading'

export default {
  components: { AppNavbar, Loading },
}
</script>
