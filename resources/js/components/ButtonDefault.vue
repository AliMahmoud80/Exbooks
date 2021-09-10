<template>
  <button
    v-bind="$attrs"
    class="
      text-white
      font-semibold
      py-1.5
      px-2.5
      rounded
      bg-gradient-to-r
      from-primary-600
      via-primary-500
      to-primary-500
      focus:outline-none
    "
  >
    {{ text }}
  </button>
</template>

<script>
export default {
  props: {
    text: String,
  },
}
</script>

<style></style>
