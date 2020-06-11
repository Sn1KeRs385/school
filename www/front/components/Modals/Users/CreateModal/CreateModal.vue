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
            .main-content__header.text-center {{'Фамилия'}}
          b-card-body
            b-form-input(
              v-model="data.last_name"
            )

        b-card.margin-top(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Имя'}}
          b-card-body
            b-form-input(
              v-model="data.first_name"
            )

        b-card.margin-top(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Отчество'}}
          b-card-body
            b-form-input(
              v-model="data.patronymic"
            )

        b-card.margin-top(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Эл. почта'}}
          b-card-body
            b-form-input(
              v-model="data.email"
              type="email"
            )

        b-card.margin-top(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Номер телефона'}}
          b-card-body
            b-form-input(
              type="tel"
              v-model="data.phone"
            )

        b-card.margin-top(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Дата рождения'}}
          b-card-body
            b-form-datepicker(
              v-model="data.birth_date"
            )

        b-card.margin-top(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Логин для входа'}}
          b-card-body
            b-form-input(
              v-model="data.login"
            )

        b-card.margin-top(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Пароль для входа'}}
          b-card-body
            b-form-input(
              v-model="data.password"
              type="password"
            )

        b-card.margin-top(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Подтверждение пароля'}}
          b-card-body
            b-form-input(
              v-model="data.confirm_password"
              type="password"
            )

        b-card.margin-top(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Роли'}}
          b-card-body
            .row
              .col-9
                b-select(
                  v-model="role_selected"
                  :options="roles"
                )
              .col-3
                b-button(
                  @click="addRole"
                  variant="primary"
                ) {{'Добавить'}}
            b-table(
              :items="data.roles"
              :fields="role_fields"
            )
              template(
                v-slot:cell(actions)="data"
              )
                b-button(
                  variant="danger"
                  @click="deleteRole(data)"
                )
                  b-icon-trash

        b-card.margin-top(
          v-if="isTeacher"
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Специализации'}}
          b-card-body
            .row
              .col-9
                b-select(
                  v-model="specialization_selected"
                  :options="specializations"
                )
              .col-3
                b-button(
                  @click="addSpecialization"
                  variant="primary"
                ) {{'Добавить'}}
            b-table(
              :items="data.specializations"
              :fields="specialization_fields"
            )
              template(
                v-slot:cell(actions)="data"
              )
                b-button(
                  variant="danger"
                  @click="deleteSpecialization(data)"
                )
                  b-icon-trash

        b-card.margin-top(
          v-if="isParent"
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-slot:header
          )
            .main-content__header.text-center {{'Настройка связей с учениками'}}
          b-card-body
            .row
              .col-9
                span.title {{'Выберите ученика'}}
                b-select(
                  v-model="student_selected"
                  :options="students"
                  placeholder="Выбор ученика"
                )
                span.title {{'Выберите тип связи'}}
                b-select(
                  v-model="relation_selected"
                  :options="relations"
                  placeholder="Выбор типа связт"
                )
              .col-3
                b-button(
                  @click="addRelation"
                  variant="primary"
                ) {{'Добавить'}}
            b-table(
              :items="data.relations"
              :fields="relation_fields"
            )
              template(
                v-slot:cell(actions)="data"
              )
                b-button(
                  variant="danger"
                  @click="deleteRelation(data)"
                )
                  b-icon-trash


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
import { save, all } from '../../../../plugins/api/api'
let url = 'users';

export default {
  components: {
    AdjacentModalContainer,
    ModalCloseSVG,
    Button
  },
  data() {
    this.$t.bind(this)
    return {
      data: {
        last_name: null,
        first_name: null,
        patronymic: null,
        email: null,
        phone: null,
        birth_date: null,
        login: null,
        password: null,
        confirm_password: null,
        roles: [],
        specializations: [],
        relations: [],
      },
      isTeacher: false,
      isParent: false,
      role_selected: null,
      roles: [],
      role_fields: [
        {
          key: 'name',
          label: 'Название'
        },
        {
          key: 'actions',
          label: 'Действия'
        }
      ],
      specialization_selected: null,
      specializations: [],
      specialization_fields: [
        {
          key: 'name',
          label: 'Название'
        },
        {
          key: 'actions',
          label: 'Действия'
        }
      ],
      relation_fields: [
        {
          key: 'student_name',
          label: 'Ученик'
        },
        {
          key: 'relation_name',
          label: 'Связь'
        },
        {
          key: 'actions',
          label: 'Действия'
        }
      ],
      student_selected: null,
      students: [],
      relation_selected: null,
      relations: [],
    }
  },
  async created() {
    const [ dataRoles, dataSpecializations, dataStudents, dataRelations ] = await Promise.all([
      all('roles'),
      all('specializations'),
      all('users', 'type=students'),
      all('relations'),
    ])
    this.roles = dataRoles.data.map(item => {
      return {
        text: item.name,
        value: item.id,
      }
    })
    this.specializations = dataSpecializations.data.map(item => {
      return {
        text: item.name,
        value: item.id,
      }
    })
    this.students = dataStudents.data.map(item => {
      return {
        text: `${item.last_name} ${item.first_name} ${item.patronymic || ''}`,
        value: item.id,
      }
    })
    this.relations = dataRelations.data.map(item => {
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
      await Promise.all([
        save(this.data, url),
      ]);
      this.$emit('reload')
      this.$emit('close')
    },
    addRole(){
      if(this.role_selected && this.data.roles.findIndex(role => role.id === this.role_selected) === -1){
        let role = this.roles.find(role => role.value === this.role_selected);
        if(role.text === "Учитель"){
          this.isTeacher = true;
        }
        if(role.text === "Родитель"){
          this.isParent = true;
        }
        this.data.roles.push({
          id: role.value,
          name: role.text,
        });
      }
      this.role_selected = null;
    },
    deleteRole(item){
      for(let i = 0; i < this.data.roles.length; i++){
        if(this.data.roles[i].id === item.item.id){
          if(this.data.roles[i].text === "Учитель"){
            this.isTeacher = false;
          }
          if(this.data.roles[i].text === "Родитель"){
            this.isParent = false;
          }
          this.data.roles.splice(i, 1);
          return;
        }
      }
    },
    addSpecialization(){
      if(this.specialization_selected && this.data.specializations.findIndex(item => item.id === this.specialization_selected) === -1){
        let item = this.specializations.find(item => item.value === this.specialization_selected);
        this.data.specializations.push({
          id: item.value,
          name: item.text,
        });
      }
      this.specialization_selected = null;
    },
    deleteSpecialization(item){
      for(let i = 0; i < this.data.specializations.length; i++){
        if(this.data.specializations[i].id === item.item.id){
          this.data.specializations.splice(i, 1);
          return;
        }
      }
    },
    addRelation(){
      if(this.student_selected
          && this.relation_selected
          && this.data.relations.findIndex(item => item.student_id === this.student_selected) === -1)
      {
        let student = this.students.find(item => item.value === this.student_selected);
        let relation = this.relations.find(item => item.value === this.relation_selected);
        this.data.relations.push({
            student_id: student.value,
            student_name: student.text,
            relation_id: relation.value,
            relation_name: relation.text,
        })
      }
      this.student_selected = null;
      this.relation_selected = null;
    },
    deleteRelation(item){
      for(let i = 0; i < this.data.relations.length; i++){
        if(this.data.relations[i].student_id === item.item.student_id){
          this.data.relations.splice(i, 1);
          return;
        }
      }
    },
  },
}
</script>

<style lang="stylus" scoped>
@import "create-modal.styl"
</style>
