<template lang="pug">
  .tag
    .tag__text
      slot
    .tag__close(v-if="removable" @click="remove")
      TagCloseSVG
</template>

<script>
import TagCloseSVG from '../../SVG/CloseTagSVG.vue'

export default {
  components: {
    TagCloseSVG,
  },
  props: {
    removable: {
      type: Boolean,
      default: false,
    },
  },
  methods: {
    remove() {
      this.$emit('remove')
    },
  },
}
</script>

<style lang="stylus">
@import "tag.styl"
</style>
