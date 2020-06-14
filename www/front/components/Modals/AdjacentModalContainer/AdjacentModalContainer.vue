<template lang="pug">
  .adjacent-modal-container(@click.self="closableByClickBack && close()")
    .adjacent-modal
      slot
</template>

<script>
export default {
  inject: ['setModal'],
  props: {
    closableByClickBack: {
      type: Boolean,
      default: false,
    },
  },
  watch: {
    $route() {
      setTimeout(this.setModal, 150)
    },
  },
  mounted() {
    window.addEventListener('keyup', this.keypress)
  },
  beforeDestroy() {
    window.removeEventListener('keyup', this.keypress)
  },
  methods: {
    close() {
      this.$emit('close')
    },
    keypress(e) {
      if (e.key === 'Escape') {
        this.close()
      }
    },
  },
}
</script>

<style lang="stylus">
@import "adjacent-modal-container.styl"
</style>
