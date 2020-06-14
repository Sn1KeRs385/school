<template lang="pug">
  ModalContainer(@close="close")
    .share-modal__container
      .share-modal__close(@click="close")
        ModalCloseSVG
      .share-modal
        .share-modal__title Поделиться достижением
        .share-modal__description Расскажите о своём достижении в других социльных сетях
        .share-modal__services
          YandexShare(
            :services="['vkontakte','facebook']"
            :lang="$i18n.locale"
            :bare="true"
            :url="url"
            :title="title"
            :description="description"
            :image="image"
          )
        .share-modal__copy-link
          div
            .share-modal__copy-link-label(
              @click="copyLink"
            ) Скопировать ссылку
          TextInput(ref="inputLink" id="copy-link" :value="url" :editable="false")
</template>

<script>
import ModalContainer from '../ModalContainer/ModalContainer.vue'
import ModalCloseSVG from '../../SVG/ModalCloseSVG.vue'
import TextInput from '../../Interactives/Inputs/TextInput/TextInput.vue'

export default {
  components: {
    ModalContainer,
    ModalCloseSVG,
    TextInput,
  },
  props: {
    url: {
      required: true,
      type: String,
    },
    title: {
      required: true,
      type: String,
    },
    description: {
      required: true,
      type: String,
    },
    image: {
      required: true,
      type: String,
    },
  },
  methods: {
    close() {
      this.$emit('close')
    },
    copyLink() {
      this.$refs.inputLink.copy()
    },
  },
}
</script>

<style lang="stylus">
@import "share-modal.styl"
</style>
