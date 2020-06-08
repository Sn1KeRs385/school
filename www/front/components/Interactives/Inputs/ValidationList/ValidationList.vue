<template lang="pug">
  TransitionGroup(
    name="validation-list"
    tag="div"
  )
    div(
      v-for="result in getInvalidResults"
      :key="result.name"
      class="validation-list-item"
    ) {{ result.message }}
</template>

<script>
export default {
  props: {
    validations: {
      required: true,
      type: Array,
    },
  },
  computed: {
    getInvalidResults() {
      return this.validations.filter(k => !k.isValid)
    },
  },
}
</script>

<style lang="stylus">
@import "validation-list.styl"
</style>
