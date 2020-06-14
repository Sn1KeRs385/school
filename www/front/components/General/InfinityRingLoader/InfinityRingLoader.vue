<template lang="pug">
  .infinite-ring-loader
    LoaderRing(size="40px")
</template>

<script>
import LoaderRing from '../LoaderRing.vue'

export default {
  components: {
    LoaderRing,
  },
}
</script>

<style lang="stylus">
@import "infinity-ring-loader.styl"
</style>
