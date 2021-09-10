<template>
  <textarea
    class="
      box-border
      py-1.5
      px-5
      h-9
      rounded
      bg-black-primary
      w-full
      font-bold
      outline-none
    "
    v-bind="$attrs"
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
  ></textarea>
</template>

<script>
export default {
  props: {
    modelValue: String,
  },
}
</script>

<style></style>
