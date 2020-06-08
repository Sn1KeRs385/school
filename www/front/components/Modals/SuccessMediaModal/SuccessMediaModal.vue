<template lang="pug">
  .success-media-modal
    .success-media-modal__header
      SuccessSVG
    .success-media-modal__body {{ $t('achievement-modal.success') }}
      .success-media-modal__body__button
        Button(
          :submit="true"
          @click.native="submitSuccess"
        ) {{ $t('achievement-modal.success-go') }}
</template>

<script>
import SuccessSVG from '../../SVG/SuccessSVG.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'

export default {
  components: {
    SuccessSVG,
    Button,
  },
  methods: {
    submitSuccess() {
      this.$emit('submitSuccess')
    },
  },
}
</script>

<style lang="stylus">
@import "success-media-modal.styl"
</style>
