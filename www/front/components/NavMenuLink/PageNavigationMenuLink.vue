<template lang="pug">
  NuxtLink(class="page-navigation-menu-link" :exact="false" :to="to")
    .page-navigation-menu-link-icon
      slot(name="icon")
    .page-navigation-menu-link-text
      slot
    .page-navigation-menu-link-arrow
      ArrowSVG

</template>

<script>
import ArrowSVG from '../SVG/ArrowSVG.vue'

export default {
  components: {
    ArrowSVG,
  },
  props: {
    to: {
      required: true,
      type: [Object, String],
    },
  },
}
</script>

<style lang="stylus">
@import "page-navigation-menu-link.styl"
</style>
