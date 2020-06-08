<template lang="pug">
  .row.align-self-end
    .col-xl-5.col-12
      .setting-tab__field-label-container
        label.setting-tab__field-label(
          v-if="label"
          :for="forInput"
          @mousedown.prevent
        ) {{ label }}
    .col-xl.col-12
      .setting-tab__field-input
        slot
</template>

<script>
export default {
  props: {
    forInput: {
      type: [String, Number],
      default: '',
    },
    label: {
      type: String,
      default: '',
    },
  },
}
</script>

<style lang="stylus">
@import "setting-tab-field.styl"
</style>
