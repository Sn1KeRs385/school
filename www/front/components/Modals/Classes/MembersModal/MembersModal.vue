<template lang="pug">
  AdjacentModalContainer(@close="close")
    .create-modal__close(@click="close")
      ModalCloseSVG
    .create-modal__container
      .create-modal
        h4.center {{getType}}
        b-card(
          bg-variant="light"
          text-variant="black"
        )
          template(
            v-if="$store.getters['auth/isAdmin']"
            v-slot:header
          )
            .row
              .col-8
                b-select(
                  v-model="selected"
                  :options="users"
                  placeholder="Добавить"
                )
              .col-4
                b-button(
                  variant="success"
                  @click="addItem"
                ) Добавить
          b-card-body
            b-table(
              :items="table"
              :fields="fields"
            )
              template(
                v-slot:cell(action)="data"
              )
                b-button(
                  @click="deleteItem(data)"
                  variant="danger"
                )
                  b-icon-trash

              template(
                v-slot:cell(text)="data"
              )
                a(
                  href="#"
                ) {{data.item.text}}


        .row.margin-top
          b-button.col(
            variant="warning"
            @click="close"
          ) {{ $t('CRUD_Button.close_button') }}
          b-button.col(
            v-if="$store.getters['auth/isAdmin']"
            variant="success"
            @click="save"
          ) {{ $t('CRUD_Button.save_button') }}

</template>

<script>
import AdjacentModalContainer from '../../AdjacentModalContainer/AdjacentModalContainer.vue'
import ModalCloseSVG from '../../../SVG/ModalCloseSVG.vue'
import { all } from '../../../../plugins/api/api'
import { getMembers, setMembers } from '../../../../plugins/api/class'

export default {
  components: {
    AdjacentModalContainer,
    ModalCloseSVG
  },
  props: {
    id: [Number, String],
    type: String,
  },
  data() {
    this.$t.bind(this)
    let fields = [
      {
        key: "text",
        label: "ФИО"
      },
    ];
    if(this.$store.getters['auth/isAdmin']){
      fields.push(
        {
          key: "action",
          label: "Действия"
        }
      );
    }
    return {
      selected: null,
      users: [],
      table: [],
      fields: fields,
    }
  },
  computed: {
    getType() {
      return this.type === "students" ? "Список учеников" : "Список учителей"
    }
  },
  async created() {
    const [ dataUsers, dataTable ] = await Promise.all([
      all('users', `type=${this.type}`),
      getMembers(this.id, this.type),
    ])
    this.users = dataUsers.data.map(item => {
      return {
        text: `${item.last_name} ${item.first_name} ${item.patronymic || ''}`,
        value: item.id,
      }
    })
    this.table = dataTable.data.map(item => {
      return {
        text: `${item.last_name} ${item.first_name} ${item.patronymic || ''}`,
        value: item.id,
      }
    })
  },
  methods: {
    close() {
      this.$emit('close')
    },
    async save() {
      const [item] = await Promise.all([
        setMembers(this.id, this.type, this.table),
      ]);
      this.$emit('reload')
      this.$emit('close')
    },
    addItem(){
      if(this.selected && this.table.findIndex(item => item.value === this.selected) === -1) {
        const item = this.users.find(item => item.value === this.selected);
        this.table.push(item);
      }
      this.selected = null;
    },
    deleteItem(item) {
      this.table.splice(item.index, 1);
    }
  },
}
</script>

<style lang="stylus" scoped>
@import "members-modal.styl"
</style>
