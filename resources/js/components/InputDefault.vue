<template>
  <input
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
    @keypress="$emit('update:serverValidationErrors', [])"
  />
  <div v-if="clientValidationErrors && clientValidationErrors.length !== 0">
    <p
      v-for="(error, index) in clientValidationErrors"
      :key="index"
      class="text-red-500 text-sm mx-4"
    >
      {{ error.$message }}
    </p>
  </div>
  <div v-if="serverValidationErrors && serverValidationErrors.length !== 0">
    <p
      v-for="(error, index) in serverValidationErrors"
      :key="index"
      class="text-red-500 text-sm mx-4"
    >
      {{ error }}
    </p>
  </div>
</template>

<script>
export default {
  props: {
    modelValue: [String, Number],
    clientValidationErrors: Array,
    serverValidationErrors: Array,
  },
}
</script>
