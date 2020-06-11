<template lang="pug">
  Table(
    :fields="fields"
    :data="data"
    :actions="actions"
    :_deleteMethod="deleteMethod"
    :_openCreateModal="openCreateModal"
  )
</template>

<script>

import { index, del } from '../../plugins/api/api'
import Table from '../../components/Table/Table'
import CreateModal from '../../components/Modals/Classes/CreateModal/CreateModal'
const url = "classes"

export default {
  inject: ['setModal'],
  components: { Table },
  data() {
    return {
      createModal: {
        component: CreateModal,
        events: {
          close: this.setModal,
          reload: async () => {
            await this.loadIndex();
          }
        },
      },
      fields: [
        {
          key: 'specialization.name',
          label: 'Направление',
        },
        {
          key: 'education_begin_at',
          label: 'Дата начала обучения',
        },
        {
          key: 'education_end_at',
          label: 'Дата окончания обучения',
        },
        {
          key: 'actions',
          label: 'Действия'
        }
      ],
      actions:{
        show: false,
        edit: true,
        delete: true,
      }
    }
  },
  async asyncData() {
    const [ data ] = await Promise.all([
      index({
        page: 1,
        records: 20
      }, url),
    ])
    data.data.items.forEach(item => {
      item.education_begin_at = (new Date(item.education_begin_at)).toLocaleDateString();
      item.education_end_at = (new Date(item.education_end_at)).toLocaleDateString();
    })
    return {
      data: data.data.items,
    }
  },
  methods: {
    async loadIndex() {
      const [ data ] = await Promise.all([
        index({
          page: 1,
          records: 20
        }, url),
      ])
      data.data.items.forEach(item => {
        item.education_begin_at = (new Date(item.education_begin_at)).toLocaleDateString();
        item.education_end_at = (new Date(item.education_end_at)).toLocaleDateString();
      })
      this.data = data.data.items;
    },
    openCreateModal() {
      this.setModal(this.createModal)
    },
    async deleteMethod(item) {
      await Promise.all([
        del(item.id, url),
      ])
      await this.loadIndex();
    },
  }
}
</script>

<style src="./classes.styl" lang="stylus" scoped></style>
