<template lang="pug">
  AdjacentModalContainer(@close="close")
    .rating-settings-modal__close(@click="close")
      ModalCloseSVG
    .rating-settings-modal
      .rating-settings-modal__back-to-profile
        MobileBackTo(
          @click.native="close"
        ) {{ $t('rating.settings.back-to') }}
      RatingSettings(
        pre-id="modal"
        :settings="settings"
        :loading="loading"
        @loadingChange="loadingChange"
        @changeRatingList="changeRatingList"
      )
</template>

<script>
import AdjacentModalContainer from '../AdjacentModalContainer/AdjacentModalContainer.vue'
import RatingSettings from '../../Rating/RatingSettings/RatingSettings.vue'
import MobileBackTo from '../../General/MobileBackTo/MobileBackTo.vue'
import ModalCloseSVG from '../../SVG/ModalCloseSVG.vue'

export default {
  components: {
    RatingSettings,
    AdjacentModalContainer,
    MobileBackTo,
    ModalCloseSVG,
  },
  props: {
    settings: {
      required: true,
      type: Object,
    },
    loading: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    screenWidth() {
      return this.$store.state.screenWidth
    },
  },
  watch: {
    screenWidth(width) {
      if (width >= 992) {
        this.close()
      }
    },
  },
  methods: {
    close() {
      this.$emit('close')
    },
    loadingChange(loading) {
      this.$emit('loadingChange', loading)
    },
    changeRatingList(ratingList) {
      this.$emit('changeRatingList', ratingList)
    },
  },
}
</script>

<style lang="stylus">
@import "rating-settings-modal.styl"
</style>
