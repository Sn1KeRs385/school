<template lang="pug">
  .container
    b-button(
      v-if="_openCreateModal"
      variant="success"
      @click="_openCreateModal"
    ) {{ $t('CRUD_Button.create_button') }}
    .main-content
      b-table(
        :items="data"
        :fields="fields"
      )
        template(
          v-slot:cell(actions)="data"
        )
          b-button(
            v-if="actions.show"
            variant="success"
          )
            b-icon-eye
          b-button(
            v-if="actions.edit"
            variant="warning"
          )
            b-icon-pencil
          b-button(
            v-if="actions.delete && _deleteMethod"
            @click="_deleteMethod(data.item)"
            variant="danger"
          )
            b-icon-trash
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
    _deleteMethod: {
      type: Function,

    },
  },
}
</script>

<style lang="stylus">
@import "table.styl"
</style>
