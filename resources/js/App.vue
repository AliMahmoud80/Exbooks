<template>
  <app-navbar></app-navbar>
  <router-view class="mt-20" />
</template>

<script>
import AppNavbar from '@/components/layouts/Navbar'

export default {
  components: { AppNavbar },
}
</script>
