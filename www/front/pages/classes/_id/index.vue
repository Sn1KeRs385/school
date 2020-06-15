<template lang="pug">
  .container
    .top-content
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
        .top-content-member-info-slot
          b-card
            div
              b {{ "Учителей в классе: " }}
                span {{ Class.teachers_count }}
            b-button.openListButton(
              variant="warning"
              @click="openMembersModal('teachers')"
            ) Открыть список
        .top-content-member-info-slot
          b-card
            div
              b {{ "Учеников в классе: " }}
                span {{ Class.students_count }}
            b-button.openListButton(
              variant="warning"
              @click="openMembersModal('students')"
            ) Открыть список
    .row
      b-tabs
        b-tab(
          title="1 семестр"
        )
        b-tab(
          title="1 семестр"
        )
        b-tab(
          title="1 семестр"
        )
        b-tab(
          title="1 семестр"
        )
        b-tab(
          title="1 семестр"
        )
        b-tab(
          title="1 семестр"
        )
        b-tab(
          title="1 семестр"
        )



</template>

<script>

import { show } from '../../../plugins/api/api'
import { url } from '../../../plugins/api/class'
import MembersModal from '../../../components/Modals/Classes/MembersModal/MembersModal'

export default {
  inject: ['setModal'],
  middleware: 'authenticated',
  components: {
    MembersModal
  },
  data() {
    return {
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
    }
  },
  async asyncData(params) {
    const id = params.params.id;
    const [ classData ] = await Promise.all([
      show(url, id),
    ])
    return {
      id: id,
      Class: classData.data,
    }
  },
  methods: {
    getLocaleDate(date){
      return (new Date(date)).toLocaleDateString();
    },
    openMembersModal(type) {
      this.membersModal.props.type = type;
      this.membersModal.props.id = this.id;
      this.setModal(this.membersModal)
    },
    async reloadData(){
      const [ classData ] = await Promise.all([
        show(url, this.id),
      ])
      this.Class = classData.data;
    }
  }
}
</script>

<style src="./index.styl" lang="stylus" scoped></style>
