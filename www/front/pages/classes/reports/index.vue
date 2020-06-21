<template lang="pug">
  .container
    b-tabs
      b-tab(
        title="Список классов"
      )
        b-card
          b-card
            p Отчет по классам, которые не завершили обучения на выбранную дату
            div
            b Дата
            b-form-datepicker(
              v-model="classReport.date"
            )
            br
            b-button(
              variant="success"
              @click="loadClassReport"
            ) Показать
            br
            b-card(
              style="margin-top: 40px;"
              v-for="class_ in classReport.data"
            )
              .table-responsive
                .center-text Класс
                  b {{class_.name}}
                .center-text Список учащихся
                table.table.table-bordered
                  thead
                    tr
                      th #
                      th ФИО
                      th Год рождения
                  tbody
                    tr(
                      v-for="(student, index) in class_.students"
                    )
                      th {{ index + 1 }}
                      th {{ `${student.last_name} ${student.first_name} ${student.patronymic || ''}` }}
                      th {{ student.birth_date ? getDateLocale(student.birth_date) : '' }}

              .center-text Список преподавателей
                table.table.table-bordered
                  thead
                    tr
                      th #
                      th ФИО
                  tbody
                    tr(
                      v-for="(teacher, index) in class_.teachers"
                    )
                      th {{ index + 1 }}
                      th {{ `${teacher.last_name} ${teacher.first_name} ${teacher.patronymic || ''}` }}


</template>

<script>
import { show, all, update } from '../../../plugins/api/api'
import { getClassReport } from '../../../plugins/api/class'
import { DateStringToLocalDateString, DateStringToLocalString } from '../../../plugins/datetime_formater'

export default {
  middleware: 'authenticated',
  data() {
    return {
      classReport: {
        date: null,
        data: [],
      }
    }
  },
  methods: {
    getDateLocale(date){
      return DateStringToLocalDateString(date);
    },
    async loadClassReport() {
      let [report] = await Promise.all([
        getClassReport(this.classReport.date)
      ])
      this.classReport.data = report.data;
    },
  }
}
</script>

<style src="./index.styl" lang="stylus" scoped></style>
