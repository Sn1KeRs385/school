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
import CreateModal from '../../components/Modals/Specializations/CreateModal/CreateModal'
const url = "specializations"

export default {
  inject: ['setModal'],
  middleware: 'authenticated',
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
          key: 'name',
          label: 'Название',
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

<style src="./specializations.styl" lang="stylus" scoped></style>
