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
            .main-content__header.text-center {{'Специальность'}}
          b-card-body
            b-select(
              v-model="data.specialization_id"
              :options="specializations"
              placeholder="Выбор направления обучения"
            )

        b-card(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Название'}}
          b-card-body
            b-input(
              v-model="data.name"
              placeholder="Название класса"
            )

        b-card.margin-top(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Дата начала обучения'}}
          b-card-body
            b-form-datepicker(
              v-model="data.education_begin_at"
            )

        b-card.margin-top(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Дата окончания обучения'}}
          b-card-body
            b-form-datepicker(
              v-model="data.education_end_at"
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

</template>

<script>
import AdjacentModalContainer from '../../AdjacentModalContainer/AdjacentModalContainer.vue'
import ModalCloseSVG from '../../../SVG/ModalCloseSVG.vue'
import Button from '../../../Interactives/Controls/Button/Button.vue'
import { all, save } from '../../../../plugins/api/api'
const url = "classes"

export default {
  components: {
    AdjacentModalContainer,
    ModalCloseSVG,
    Button
  },
  props: {
  },
  data() {
    this.$t.bind(this)
    return {
      data: {
        specialization_id: null,
        name: null,
        education_begin_at: null,
        education_end_at: null,
      },
      specializations: [],
    }
  },  async created() {
    const [ dataSpecializations ] = await Promise.all([
      all('specializations'),
    ])
    this.specializations = dataSpecializations.data.map(item => {
      return {
        text: item.name,
        value: item.id,
      }
    })
  },
  methods: {
    close() {
      this.$emit('close')
    },
    async save() {
      const [item] = await Promise.all([
        save(this.data, url),
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
