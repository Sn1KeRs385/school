<template lang="pug">
  .container
    .row
      .col-3
        b-button(
          v-if="_openCreateModal && $store.getters['auth/isAdmin']"
          variant="success"
          @click="_openCreateModal"
        ) {{ $t('CRUD_Button.create_button') }}
        b-button(
          style="margin-left: 20px;"
          v-if="reportsLink && $store.getters['auth/isAdmin']"
          variant="warning"
          @click="redirectToReports"
        ) Отчеты
      .col-9
        b-input(
          placeholder="Поиск"
        )
    .main-content
      .table-responsive
        b-table(
          :items="data"
          :fields="fields"
        )
          template(
            v-slot:cell(actions)="data"
          )
            b-button(
              v-if="actions.show && _showMethod"
              @click="_showMethod(data.item)"
              variant="success"
            )
              b-icon-eye
            b-button(
              v-if="actions.edit && $store.getters['auth/isAdmin']"
              variant="warning"
            )
              b-icon-pencil
            b-button(
              v-if="actions.delete && _deleteMethod && $store.getters['auth/isAdmin']"
              @click="_deleteMethod(data.item)"
              variant="danger"
            )
              b-icon-trash
    .row.justify-content-center
      .col-4
        b-button(
          variant="dark"
          @click="changePageButton(false)"
        )
          b-icon-arrow-left-circle
        span {{` ${currentPage}/${lastPage} `}}
        b-button(
          variant="dark"
          @click="changePageButton(true)"
        )
          b-icon-arrow-right-circle
</template>

<script>

export default {
  props: {
    fields: {
      required: true,
      type: Array,
    },
    data: {
      required: true,
      type: Array,
    },
    actions: {
      required: true,
      type: Object,
    },
    _openCreateModal: {
      type: Function,
    },
    _showMethod: {
      type: Function,

    },
    _deleteMethod: {
      type: Function,

    },
    currentPage: {
      type: Number,
      default: 0,
    },
    lastPage: {
      type: Number,
      default: 0,
    },
    changePage: {
      type: Function,
      required: true,
    },
    reportsLink: {
      type: String
    }
  },
  methods:{
    changePageButton(next){
      if(next){
        this.changePage(this.currentPage + 1);
      } else {
        this.changePage(this.currentPage - 1);
      }
    },
    redirectToReports(){
      if(this.reportsLink) {
        this.$router.push(this.reportsLink)
      }
    }
  }
}
</script>

<style lang="stylus">
@import "table.styl"
</style>
