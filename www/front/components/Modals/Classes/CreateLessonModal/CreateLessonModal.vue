<template lang="pug">
  AdjacentModalContainer(@close="close")
    .create-modal__close(@click="close")
      ModalCloseSVG
    .create-modal__container
      .create-modal
        h4.center Создание урока
        b-card(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          ) Дата и время урока
          b-card-body
            label Дата урока
            b-form-datepicker(
              v-model="date"
            )
            label Время урока
            b-form-timepicker(
              v-model="time"
            )
            div(
              v-if="schedule_type_id === 1"
            )
              br
              b-form-checkbox(
                v-model="auto_fill"
                :value="true"
                :unchecked-value="false"
              ) Заполнить расписание автоматически
                p
                  small Если установить галочку, автоматически создадутся уроки в этот день недели на протяжении всего семестра

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
import { save } from '../../../../plugins/api/api'

export default {
  components: {
    AdjacentModalContainer,
    ModalCloseSVG
  },
  props: {
    semester_id: [Number, String],
    schedule_type_id: [Number, String],
  },
  data() {
    this.$t.bind(this)
    return {
      date: null,
      time: null,
      auto_fill: false,
    }
  },
  methods: {
    close() {
      this.$emit('close')
    },
    async save() {
      let data = {
        class_semester_id: this.semester_id,
        lesson_begin_at: `${this.date} ${this.time}`,
        auto_fill: this.auto_fill
      }
      const [item] = await Promise.all([
        save(data, 'class_lessons'),
      ]);
      this.$emit('reload')
      this.$emit('close')
    },
  },
}
</script>

<style lang="stylus" scoped>
@import "create-lesson-modal.styl"
</style>
