<template lang="pug">
  AdjacentModalContainer(@close="close")
    .create-modal__close(@click="close")
      ModalCloseSVG
    .create-modal__container
      .create-modal
        b-card(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Заголовок'}}
          b-card-body
            b-form-input(
              v-model="title"
            )

        b-card.margin-top.editor__card(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Текст объявления'}}
          b-card-body
            quill-editor.editor(
              v-model="text"
            )
        .row.margin-top
          b-button.col(
            variant="warning"
            @click="close"
          ) {{ $t('CRUD_Button.close_button') }}
          b-button.col(
            variant="success"
            @click="save"
          ) {{ $t('CRUD_Button.save_button') }}
        //Button(
          @click.native="close"
        //) {{ $t('CRUD_Button.close_form') }}

</template>

<script>
import AdjacentModalContainer from '../../AdjacentModalContainer/AdjacentModalContainer.vue'
import ModalCloseSVG from '../../../SVG/ModalCloseSVG.vue'
import Button from '../../../Interactives/Controls/Button/Button.vue'
import { saveNews } from '../../../../plugins/api/news'

export default {
  components: {
    AdjacentModalContainer,
    ModalCloseSVG,
    Button
  },
  props: {},
  data() {
    this.$t.bind(this)
    return {
      title: null,
      text: null,
    }
  },
  methods: {
    close() {
      this.$emit('close')
    },
    async save() {
      const [news] = await Promise.all([
        saveNews({
          title: this.title,
          text: this.text
        }),
      ]);
      this.$emit('reload')
      this.$emit('close')
    },
  },
}
</script>

<style lang="stylus" scoped>
@import "create-modal.styl"
</style>
