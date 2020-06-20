<template lang="pug">
  .container
    b-tabs
      b-tab(
        title="Основная информация"
      )
        b-card
          .user-info
            .user-info__main-info
              b-card
                div
                  b {{ "ФИО: " }}
                  span {{ user.fio }}
                div(
                  v-if="user.email"
                )
                  b {{ "Электронная почта: " }}
                  span {{ user.email }}
                div(
                  v-if="user.phone"
                )
                  b {{ "Номер телефона: " }}
                  span {{ user.phone }}
                div(
                  v-if="user.roles.length > 0"
                )
                  b {{ "Должности: " }}
                  ul
                    li(
                      v-for="item in user.roles"
                    ) {{ item.name }}
            .user-info__classes
              b-card
                div(
                  v-if="user.classes_where_teacher.length > 0"
                )
                  b {{ "Учитель в классах: " }}
                  ul
                    li(
                      v-for="item in user.classes_where_teacher"
                    )
                      a(
                        :href="`/classes/${item.id}`"
                      ) {{ getClassLink(item) }}
                div(
                  v-if="user.classes_where_student.length > 0"
                )
                  b {{ "Учится в классах: " }}
                  ul
                    li(
                      v-for="item in user.classes_where_student"
                    )
                      a(
                        :href="`/classes/${item.id}`"
                      ) {{ getClassLink(item) }}

      b-tab(
        title="Дневник"
      )
        b-card
          div
            b {{ "Дата начала " }}
            b-form-datepicker(
              v-model="schedule.start_at"
            )
          div
            b {{ "Дата окончания " }}
            b-form-datepicker(
              v-model="schedule.end_at"
            )
          br
          b-button(
            variant="success"
            @click="loadSchedule"
          ) Обновить

        .table-responsive
          table.table.table-bordered
            thead
              tr
                th Дата
                th Время
                th Класс
                th Домашнее задание
                th Оценка
                th Комментарий
            tbody
              tr(
                v-for="item in schedule.options"
              )
                th {{ convertToDate(item.lesson_begin_at) }}
                td {{ convertToTime(item.lesson_begin_at) }}
                td
                  a(
                    :href="`/classes/${item.class_semester.c_lass.id}`"
                  ) {{ getClassLink(item.class_semester.c_lass) }}
                td {{ item.homework || "" }}
                td {{ item.student_progress ? item.student_progress.evaluation : "" }}
                td {{ item.student_progress ? item.student_progress.comment : "" }}


</template>

<script>
  /*(
  :rowspan="date.length"
  )*/
import { show, all } from '../../../plugins/api/api'
import { getSchedule } from '../../../plugins/api/user'
import { DateStringToLocalDateString, DateStringToLocalString, DateStringToLocalTimeString } from '../../../plugins/datetime_formater'
import moment from "moment";

export default {
  inject: ['setModal'],
  middleware: 'authenticated',
  components: {
  },
  data() {
    let weekStart = moment().startOf('week').toDate();
    let weekEnd = moment().endOf('week').toDate();
    return {
      schedule: {
        start_at: `${weekStart.getFullYear()}-${weekStart.getMonth()+1}-${weekStart.getDate()}`,
        end_at: `${weekEnd.getFullYear()}-${weekEnd.getMonth()+1}-${weekEnd.getDate()}`,
        options: [],
      }
    }
  },
  async asyncData(params) {
    const id = params.params.id;
    const [ userData ] = await Promise.all([
      show('users', id)
    ])
    userData.data['fio'] = `${userData.data.last_name} ${userData.data.first_name} ${userData.data.patronymic || ''}`;
    return {
      user_id: id,
      user: userData.data
    }
  },
  mounted() {
    this.loadSchedule();
  },
  methods: {
    convertToDate(date) {
      return DateStringToLocalDateString(date);
    },
    convertToTime(date) {
      return DateStringToLocalTimeString(date);
    },
    getClassLink(item) {
      return `${item.name ? item.name + ' - ' : ''}${item.specialization.name}`
        + `${item.education_begin_at ? ' - ' + this.convertToDate(item.education_begin_at) : ''}`
        + `${item.education_end_at ? ' - ' + this.convertToDate(item.education_end_at) : ''}`
    },
    async loadSchedule() {
      let [schedule] = await Promise.all([
        getSchedule(this.schedule.start_at, this.schedule.end_at, this.user_id)
      ])
      this.schedule.options = schedule.data;
    }
  }
}
</script>

<style src="./index.styl" lang="stylus" scoped></style>
