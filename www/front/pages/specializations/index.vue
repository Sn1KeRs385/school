<template lang="pug">
  Table(
    :fields="fields"
    :data="data"
    :actions="actions"
    :currentPage="page"
    :lastPage="last_page"
    :changePage="changePage"
    :_deleteMethod="deleteMethod"
    :_openCreateModal="openCreateModal"
  )
</template>

<script>

import { index, del } from '../../plugins/api/api'
import Table from '../../components/Table/Table'
import CreateModal from '../../components/Modals/Specializations/CreateModal/CreateModal'
import { url } from '../../plugins/api/specialization'
let default_page = 1;
let default_per_page = 9;

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
        page: default_page,
        per_page: default_per_page
      }, url),
    ])
    return {
      data: data.data.items,
      page: default_page,
      per_page: default_per_page,
      last_page: data.data.meta.last_page,
    }
  },
  methods: {
    async loadIndex() {
      const [ data ] = await Promise.all([
        index({
          page: this.page,
          per_page: this.per_page,
        }, url),
      ])
      this.data = data.data.items;
      this.last_page = data.data.meta.last_page;
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
    changePage(page) {
      const saveCurrentPage = this.page;
      if(page < 1){
        this.page = 1
      } else if(page > this.last_page){
        this.page = this.last_page
      } else {
        this.page = page
      }

      if(this.page !== saveCurrentPage){
        this.loadIndex();
      }
    }
  }
}
</script>

<style src="./specializations.styl" lang="stylus" scoped></style>
