<template lang="pug">
  AdjacentModalContainer(
    :closable-by-click-back="false"
    @close="close"
  )
    .crop-avatar-modal
      .crop-avatar-modal__back-to-profile
        MobileBackTo(
          @click.native="close"
        ) Вернуться в поиск
      form.crop-avatar-modal__modal(@submit.prevent="submit")
        .crop-avatar-modal__header Фотогрфия на вашей странице
        .crop-avatar-modal__description Выбранная область будет показываться на Вашей странице.
        .crop-avatar-modal__cropper
          Cropper(
            :src="avatar"
            :stencil-component="$options.components.CircleStencil"
            @change="avatarChange"
          )
        .crop-avatar-modal__footer
          .crop-avatar-modal__footer-submit
            Button(
              :submit="true"
              :loading="loading"
              min-height="40px"
            ) Сохранить
          .crop-avatar-modal__footer-cancel
            Button(
              :secondary="true"
              min-height="40px"
              @click.native="close"
            ) Отмена

</template>

<script>
import { Cropper, CircleStencil } from 'vue-advanced-cropper'
import AdjacentModalContainer from '../AdjacentModalContainer/AdjacentModalContainer.vue'
import MobileBackTo from '../../General/MobileBackTo/MobileBackTo.vue'
import ModalCloseSVG from '../../SVG/ModalCloseSVG.vue'
import Button from '../../Interactives/Controls/Button/Button.vue'
import { setUserAvatar } from '../../../plugins/api/user'

export default {
  components: {
    AdjacentModalContainer,
    MobileBackTo,
    ModalCloseSVG,
    Cropper,
    CircleStencil,
    Button,
  },
  props: {
    avatar: {
      required: true,
      type: String,
    },
    file: {
      required: true,
      type: null,
    },
  },
  data() {
    return {
      coordinates: {
        width: 0,
        height: 0,
        left: 0,
        top: 0,
      },
      image: null,
      loading: false,
    }
  },
  methods: {
    close() {
      this.$emit('close')
    },
    avatarChange({ coordinates, canvas }) {
      this.coordinates = coordinates
      this.image = canvas.toDataURL()
    },
    async submit() {
      if (this.loading) return
      this.loading = true
      const formData = new FormData()
      formData.append('image', this.file)
      formData.append('image_params[x]', this.coordinates.left)
      formData.append('image_params[y]', this.coordinates.top)
      formData.append('image_params[width]', this.coordinates.width)
      formData.append('image_params[height]', this.coordinates.height)
      const result = await setUserAvatar(formData)
      this.loading = false
      this.$emit('changeAvatar', result.data.avatar)
      this.close()
    },
  },
}
</script>

<style lang="stylus">
@import "crop-avatar-modal.styl"
</style>
