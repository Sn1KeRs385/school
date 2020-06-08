<template lang="pug">
  .the-header
    .the-header__content
      MenuControl
      .the-header__content__space
      ProfileControl
</template>

<script>

import ProfileControl from './ProfileControl/ProfileControl.vue'
import MenuControl from './MenuControl/MenuControl.vue'
export default {
  components:{
    ProfileControl,
    MenuControl,
  },
  computed:{
    isAuthorized() {
      return this.$store.getters['auth/authorized']
    },
  }
}
</script>

<style lang="stylus">
@import "./the-header.styl"
</style>
