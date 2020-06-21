<template lang="pug">
  .container
    .top-content
      .top-content-container
        .top-content-main-info
          b-card
            div
              b {{ "Направление: " }}
              span {{ Class.specialization.name }}
            div(
              v-if="Class.name"
            )
              b {{ "Название класса: " }}
              span {{ Class.name}}
            div(
              v-if="Class.education_begin_at"
            )
              b {{ "Дата начала обучения: " }}
              span {{ getLocaleDate(Class.education_begin_at) }}
            div(
              v-if="Class.education_end_at"
            )
              b {{ "Дата окончания обучения: " }}
              span {{ getLocaleDate(Class.education_end_at) }}

        .top-content-member-info
          b-card.top-content-member-info-slot
            div
              b {{ "Учителей в классе: " }}
                span {{ Class.teachers_count }}
            b-button.openListButton(
              variant="warning"
              @click="openMembersModal('teachers')"
            ) Открыть список
          b-card.top-content-member-info-slot
            div
              b {{ "Учеников в классе: " }}
                span {{ Class.students_count }}
            b-button.openListButton(
              variant="warning"
              @click="openMembersModal('students')"
            ) Открыть список
    .journal
      b-card
        template(
          v-slot:header
        )
          .journal_header
            .journal-header-text
              h5
                b Электронный журнал

            .journal_header_buttons
              b-button.journal_header_buttons(
                v-if="$store.getters['auth/isAdmin']"
                variant="success"
                @click="openCreateSemesterModal"
              ) Добавить семестр

        b-tabs
          b-tab(
            v-for="(item, index) in semesters"
            :title="(index + 1) + ' семестр'"
            @click="changeTab(item)"
          )
            .semester
              b-card(
                :class="{ 'semester_info_full':  !$store.getters['auth/isAdmin'], 'semester_info': $store.getters['auth/isAdmin']}"
              )
                template(
                  v-slot:header
                ) Информация о семестре
                div(
                  v-if="item.schedule_type"
                )
                  b {{ "Тип расписания: " }}
                  span {{ item.schedule_type.name }}
                div(
                  v-if="item.semester_begin_at"
                )
                  b {{ "Дата начала семестра: " }}
                  span {{ getLocaleDate(item.semester_begin_at) }}
                div(
                  v-if="item.semester_end_at"
                )
                  b {{ "Дата окончания семестра: " }}
                  span {{ getLocaleDate(item.semester_end_at) }}
                div(
                  v-if="item.lesson_time"
                )
                  b {{ "Длительность урока: " }}
                  span {{ `${item.lesson_time} мин.`}}

              b-card.semester_buttons(
                v-if="$store.getters['auth/isAdmin']"
              )
                template(
                  v-slot:header
                ) Управление семестрами
                b-button.semester_buttons_button(
                  variant="warning"
                ) Редактировать семестр
                b-button.semester_buttons_button(
                  variant="danger"
                ) Удалить семестр

              b-card.schedule_choose-lesson-card
                template(
                  v-slot:header
                )
                  .schedule-header
                    .schedule-header-text Управление занятиями
                    .schedule-header-button
                      b-button(
                        variant="success"
                        @click="openCreateLessonModal(item.id, item.schedule_type_id)"
                        v-if="$store.getters['auth/isAdmin'] || $store.getters['auth/isTeacher']"
                      ) Создать занятие
                .schedule_choose-lesson-card_container
                  .schedule_choose-lesson-card_container_choose_box
                    label Выбор занятия
                    b-select(
                      v-model="lessonSelected"
                      :options="lessons"
                      value-field="id"
                      text-field="lesson_begin_at"
                      placeholder="Выбор занятия"
                      @change="reloadStudents(lessonSelected)"
                    )
                      template(
                        v-slot:text
                      )
                  .schedule_choose-lesson-card_container_control_buttons
                    b-button.schedule_choose-lesson-card_container_control_buttons_button(
                      variant="warning"
                      v-if="lessonSelected && ($store.getters['auth/isAdmin'] || $store.getters['auth/isTeacher'])"
                    ) Изменить дату и время
                    b-button.schedule_choose-lesson-card_container_control_buttons_button(
                      variant="danger"
                      v-if="lessonSelected && ($store.getters['auth/isAdmin'] || $store.getters['auth/isTeacher'])"
                    ) Отменить занятие

              b-tabs.progresses(
                v-if="lessonSelected"
              )
                b-tab(
                  title="Оценки"
                )
                  b-button.progresses_save-button(
                    variant="success"
                    @click="saveStudentsProgresses"
                    v-if="$store.getters['auth/isAdmin'] || $store.getters['auth/isTeacher']"
                  ) Сохранить
                  .table-responsive
                    b-table.progresses-table(
                      :items="students"
                      :fields="progressesFields"
                    )
                      template(
                        v-slot:cell(evaluation)="data"
                      )
                        b-input.progresses-table-evaluation_input(
                          v-model="data.item.evaluation"
                          :readonly="!$store.getters['auth/isAdmin'] && !$store.getters['auth/isTeacher']"
                        )
                      template(
                        v-slot:cell(comment)="data"
                      )
                        b-textarea.progresses-table-comment_input(
                          v-model="data.item.comment"
                          no-resize
                          :readonly="!$store.getters['auth/isAdmin'] && !$store.getters['auth/isTeacher']"
                        )
                      template(
                        v-slot:cell(notification)="data"
                      )
                        b-checkbox(
                          v-model="data.item.notification"
                          :value="true"
                          :unchecked-value="false"
                        )
                b-tab(
                  title="Домашнее задание"
                )
                  b-button.progresses_homework-area(
                    variant="success"
                    @click="saveHomework"
                    v-if="$store.getters['auth/isAdmin'] || $store.getters['auth/isTeacher']"
                  ) Сохранить
                  b-textarea.progresses(
                    v-model="homework"
                    no-resize
                    :readonly="!$store.getters['auth/isAdmin'] && !$store.getters['auth/isTeacher']"
                  )





</template>

<script>
import { show, all, update } from '../../../plugins/api/api'
import { url, getStudentsWithProgress, saveStudentsProgress } from '../../../plugins/api/class'
import MembersModal from '../../../components/Modals/Classes/MembersModal/MembersModal'
import CreateSemesterModal from '../../../components/Modals/Classes/CreateSemesterModal/CreateSemesterModal'
import CreateLessonModal from '../../../components/Modals/Classes/CreateLessonModal/CreateLessonModal'
import { DateStringToLocalDateString, DateStringToLocalString } from '../../../plugins/datetime_formater'

export default {
  inject: ['setModal'],
  middleware: 'authenticated',
  components: {
    MembersModal,
    CreateSemesterModal,
    CreateLessonModal
  },
  data() {
    let progressFields = [
      {
        key: "full_name",
        label: "ФИО"
      },
      {
        key: "evaluation",
        label: "Оценка"
      },
    ];
    if(this.$store.getters['auth/isAdmin'] || this.$store.getters['auth/isTeacher'] || this.$store.getters['auth/isTeacher']){
      progressFields.push(
        {
          key: "comment",
          label: "Замечания"
        }
      )
    }
    if(this.$store.getters['auth/isAdmin'] || this.$store.getters['auth/isTeacher']){
      progressFields.push(
        {
          key: "notification",
          label: "Уведомить"
        }
      )
    }
    return {
      lessonSelected: null,
      membersModal: {
        component: MembersModal,
        props:{
          type: null,
          id: null,
        },
        events: {
          close: this.setModal,
          reload: async () => {
            await this.reloadData();
          }
        },
      },
      createSemesterModal: {
        component: CreateSemesterModal,
        props:{
          id: null,
        },
        events: {
          close: this.setModal,
          reload: async () => {
            await this.reloadSemester();
          }
        },
      },
      createLessonModal: {
        component: CreateLessonModal,
        props:{
          semester_id: null,
          schedule_type_id: 2,
        },
        events: {
          close: this.setModal,
          reload: async () => {
            await this.reloadLessons(this.createLessonModal.props.semester_id);
          }
        },
      },
      progressesFields: progressFields,
    }
  },
  async asyncData(params) {
    const id = params.params.id;
    const [ classData, semesterData ] = await Promise.all([
      show(url, id),
      all('class_semesters', `class_id=${id}`),
    ])
    let lessonData = [];
    let studentsData = [];
    lessonData['data'] = [];
    studentsData['data'] = [];
    if(semesterData.data.length > 0) {
      [ lessonData ] = await Promise.all([
        all('class_lessons', `semester_id=${semesterData.data[0].id}`),
      ])
      if(lessonData.data.length > 0){
        [ studentsData ] = await Promise.all([
          getStudentsWithProgress(lessonData.data[0].id),
        ])
      }
    }
    console.log(studentsData);
    return {
      id: id,
      Class: classData.data,
      semesters: semesterData.data,
      lessons: lessonData.data.map(item => {
        return {
          id: item.id,
          lesson_begin_at: DateStringToLocalString(item.lesson_begin_at)
        }
      }),
      students: studentsData.data.map(item => {
        return {
          full_name: `${item.last_name} ${item.first_name.substring(0, 1)}. ${item.patronymic ? (item.patronymic.substring(0, 1) + '.') : ''}`,
          user_id: item.id,
          class_lesson_id: item.progress.class_lesson_id,
          evaluation: item.progress.evaluation,
          comment: item.progress.comment,
          notification: false,
        }
      }),
      homework: lessonData.data[0].homework,
    }
  },
  mounted() {
    if(this.lessons.length > 0){
      this.lessonSelected = this.lessons[0].id;
    }
  },
  methods: {
    changeTab(semester){
      this.reloadLessons(semester.id)
    },
    getLocaleDate(date){
      return DateStringToLocalDateString(date);
    },
    openMembersModal(type) {
      this.membersModal.props.type = type;
      this.membersModal.props.id = this.id;
      this.setModal(this.membersModal)
    },
    openCreateSemesterModal() {
      this.createSemesterModal.props.id = this.id;
      this.setModal(this.createSemesterModal)
    },
    openCreateLessonModal(semester_id, schedule_type_id) {
      this.createLessonModal.props.semester_id = semester_id;
      this.createLessonModal.props.schedule_type_id = schedule_type_id;
      this.setModal(this.createLessonModal)
    },
    async reloadData(){
      const [ classData ] = await Promise.all([
        show(url, this.id),
      ])
      this.Class = classData.data;
    },
    async reloadSemester(){
      const [ semesterData ] = await Promise.all([
        all('class_semesters', `class_id=${this.id}`),
      ])
      this.semesters = semesterData.data;
      if(this.semesters.length > 0){
        await this.reloadLessons(this.semesters[0].id);
      }
    },
    async reloadLessons(semester_id){
      this.lessonSelected = null;
      this.lessons = [];
      const [ lessonData ] = await Promise.all([
        all('class_lessons', `semester_id=${semester_id}`),
      ])
      this.lessons = lessonData.data.map(item => {
        return {
          id: item.id,
          lesson_begin_at: DateStringToLocalString(item.lesson_begin_at),
          homework: item.homework,
        }
      })
      if(this.lessons.length > 0){
        this.lessonSelected = this.lessons[0].id;
        await this.reloadStudents(this.lessons[0].id);
        await this.reloadHomework(this.lessons[0].id);
      }
    },
    async reloadStudents(lesson_id){
      await this.reloadHomework(lesson_id);
      this.students = [];
      const [ studentsData ] = await Promise.all([
        getStudentsWithProgress(lesson_id),
      ])
      this.students = studentsData.data.map(item => {
        return {
          full_name: `${item.last_name} ${item.first_name.substring(0, 1)}. ${item.patronymic ? (item.patronymic.substring(0, 1) + '.') : ''}`,
          user_id: item.id,
          class_lesson_id: item.progress.class_lesson_id,
          evaluation: item.progress.evaluation,
          comment: item.progress.comment,
          notification: false,
        }
      })
    },
    async reloadHomework(lesson_id){
      const [ lessonData ] = await Promise.all([
        show('class_lessons', lesson_id),
      ])
      this.homework = lessonData.data.homework;
    },
    saveStudentsProgresses(){
      saveStudentsProgress({data: this.students});
    },
    async saveHomework(){
      await Promise.all([
        update({homework: this.homework}, 'class_lessons', this.lessonSelected),
      ])
    }
  }
}
</script>

<style src="./index.styl" lang="stylus" scoped></style>
