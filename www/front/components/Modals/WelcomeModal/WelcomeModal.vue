<template lang="pug">
  AdjacentModalContainer(@close="close")
    .welcome-modal__close(@click="close")
      ModalCloseSVG
    .welcome-modal__container
      .welcome-modal
        .welcome-modal__header {{ $t('welcome-modal.title') }}
        .welcome-modal__text(v-html="welcomeModalText")
        a(
          href="https://www.youtube.com/watch?v=Lej9b9_HjRc&feature=youtu.be"
          target="_blank"
          @click="close"
        )
          Button(
            tag="div"
          ) {{ $t('welcome-modal.buttons.button-ready') }}
        Button(
          class="button__cancel"
          @click.native="close"
        ) {{ $t('welcome-modal.buttons.button-cancel') }}

</template>

<script>
import AdjacentModalContainer from '../AdjacentModalContainer/AdjacentModalContainer.vue'
import AchievementModalField from '../AchievementModal/AchievementModalField/AchievementModalField.vue'
import ModalCloseSVG from '../../SVG/ModalCloseSVG.vue'
import MobileBackTo from '../../General/MobileBackTo/MobileBackTo.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'

export default {
  components: {
    AdjacentModalContainer,
    ModalCloseSVG,
    AchievementModalField,
    MobileBackTo,
    Button,
  },
  props: {},
  data() {
    this.$t.bind(this)
    return {}
  },
  computed: {
    welcomeModalText() {
      return this.$t('welcome-modal.message-info').join('<br />')
    },
  },
  methods: {
    close() {
      this.$emit('close')
    },
  },
}
</script>

<style lang="stylus" scoped>
@import "welcome-modal.styl"
</style>
