<template lang="pug">
  AdjacentModalContainer(@close="close")
    .create-modal__close(@click="close")
      ModalCloseSVG
    .create-modal__container
      .create-modal
        h4.center Добавление семестра
        b-card(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          ) Тип расписания
          b-card-body
            b-select(
              v-model="data.schedule_type_id"
              :options="schedule_types"
              value-field="id"
              text-field="name"
            )
        b-card(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          ) Длительность урока (в минутах)
          b-card-body
            b-form-input(
              v-model="data.lesson_time"
              type="number"
            )
        b-card(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          ) Дата начала семестра
          b-card-body
            b-form-datepicker(
              v-model="data.semester_begin_at"
            )
        b-card(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          ) Дата окончания семестра
          b-card-body
            b-form-datepicker(
              v-model="data.semester_end_at"
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
import { all, save } from '../../../../plugins/api/api'

export default {
  components: {
    AdjacentModalContainer,
    ModalCloseSVG
  },
  props: {
    id: [Number, String],
  },
  data() {
    this.$t.bind(this)
    return {
      data:{
        schedule_type_id: null,
        lesson_time: null,
        semester_begin_at: null,
        semester_end_at: null,
      },
      schedule_type: null,
      schedule_types: [],
    }
  },
  async created() {
    const [ dataSheduleType, dataTable ] = await Promise.all([
      all('schedule_types'),
    ])
    this.schedule_types = dataSheduleType.data;
  },
  methods: {
    close() {
      this.$emit('close')
    },
    async save() {
      this.data['class_id'] = this.id;
      const [item] = await Promise.all([
        save(this.data, 'class_semesters'),
      ]);
      this.$emit('reload')
      this.$emit('close')
    },
  },
}
</script>

<style lang="stylus" scoped>
@import "create-semester-modal.styl"
</style>
