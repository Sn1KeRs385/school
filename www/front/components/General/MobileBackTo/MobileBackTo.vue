<template lang="pug">
  .mobile-back-to
    .mobile-back-to__arrow
      ArrowSVG
    slot

</template>

<script>
import ArrowSVG from '../../SVG/ArrowSVG.vue'

export default {
  components: {
    ArrowSVG,
  },
}
</script>

<style lang="stylus">
@import "mobile-back-to.styl"
</style>
