<template lang="pug">
  TheMainLayout
    .the-sign-layout
      .the-sign-layout__body
        .the-sign-layout__content
          slot
</template>

<script>
import TheMainLayout from '../TheMainLayout/index.vue'
import DoubleTabs from '../../General/DoubleTabs/DoubleTabs.vue'

export default {
  components: {
    TheMainLayout,
    DoubleTabs,
  },
  computed: {
    isSignIn() {
      return this.$route.name.includes('signin')
    },
    getClassSelector() {
      return {
        'the-sign-layout__tabs-selector--left': this.isSignIn,
        'the-sign-layout__tabs-selector--right': !this.isSignIn,
      }
    },
  },
}
</script>

<style lang="stylus">
@import "the-sign-layout.styl"
</style>
