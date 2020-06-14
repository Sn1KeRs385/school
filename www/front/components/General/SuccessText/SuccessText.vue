<template lang="pug">
  .success-text
    .success-text__check
      CheckSVG
    span
      slot
</template>

<script>
import CheckSVG from '../../SVG/CheckSVG.vue'

export default {
  components: {
    CheckSVG,
  },
}
</script>

<style lang="stylus">
@import "success-text.styl";
</style>
