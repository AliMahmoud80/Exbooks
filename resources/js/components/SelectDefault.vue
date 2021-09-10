<template>
  <select
    class="bg-black-primary w-full p-3 rounded outlline-none"
    :value="modelValue"
    v-bind="$attrs"
    @change="$emit('update:modelValue', $event.target.value)"
  >
    <option v-for="(option, index) in options" :key="index" :value="option.id">
      {{ option.name }}
    </option>
  </select>
</template>

<script>
export default {
  props: {
    modelValue: [String, Number],
    options: Array,
  },
}
</script>

<style lang="scss" scoped>
select {
  @apply font-semibold;

  option {
    @apply font-semibold;
    @apply text-white;
  }
}
</style>
