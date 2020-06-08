<template lang="pug">
  .double-tabs
    .double-tabs__tabs
      .double-tabs__tab(:class="leftTabClasses")
        slot(name="left")
      .double-tabs__tab(:class="rightTabClasses")
        slot(name="right")
    .double-tabs__bottom-line
      .double-tabs__selector(:class="selectorClasses")
</template>

<script>
import ToolTip from '../ToolTip/ToolTip.vue'

export default {
  components: {
    ToolTip,
  },
  props: {
    tab: {
      type: String,
      default: 'left',
    },
    modalAchievementShowed: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    isLeft() {
      return this.tab === 'left'
    },
    isRight() {
      return this.tab === 'right'
    },
    selectorClasses() {
      return {
        'double-tabs__selector--right': this.isRight,
      }
    },
    leftTabClasses() {
      return {
        'double-tabs__tab--selected': this.isLeft,
      }
    },
    rightTabClasses() {
      return {
        'double-tabs__tab--selected': this.isRight,
      }
    },
  },
}
</script>

<style lang="stylus">
@import "double-tabs.styl"
</style>
