<template lang="pug">
  Table(
    :fields="fields"
    :data="data"
    :actions="actions"
    :_openCreateModal="openCreateModal"
  )
</template>

<script>

import { index } from '../../plugins/api/api'
import Table from '../../components/Table/Table'
import CreateModal from '../../components/Modals/Users/CreateModal/CreateModal'
const url = "users"

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
          key: 'last_name',
          label: 'Фамилия',
        },
        {
          key: 'first_name',
          label: 'Имя',
        },
        {
          key: 'patronymic',
          label: 'Отчество',
        },
        {
          key: 'email',
          label: 'Почта',
        },
        {
          key: 'phone',
          label: 'Номер телефона',
        },
        {
          key: 'birth_date',
          label: 'Дата рождения',
        },
        {
          key: 'actions',
          label: 'Действия'
        }
      ],
      actions:{
        show: true,
        edit: true,
        delete: false,
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
      item.birth_date = (new Date(item.birth_date)).toLocaleDateString();
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
        item.birth_date = (new Date(item.birth_date)).toLocaleDateString();
      })
      this.data = data.data.items;
    },
    openCreateModal() {
      this.setModal(this.createModal)
    },
  }
}
</script>

<style src="./users.styl" lang="stylus" scoped></style>
