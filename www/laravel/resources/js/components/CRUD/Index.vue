<template>
    <div class="animated fadeIn">
        <b-row v-show="!isLoading">
            <b-col sm="6" class="mb-6">
                <b-button class="search_element" v-if="model.show.add" @click="go_add" variant="success">Добавить</b-button>
            </b-col>
            <b-col v-if="model.searchable" sm="6" class="mb-3">
                <b-input class="search_element" v-model="searchWatcher" placeholder="Поиск по имени/названию..."></b-input>
            </b-col>

            <b-col  sm="12">
                <b-card :header="model.title">
                    <b-table :sortBy="model.table.sort.sortBy || 'id'" class="index_table" :sortDesc="model.table.sort.sortDesc || false" :hover="true" :striped="true" :bordered="true" :small="true" :fixed="false" responsive="sm" :items="response.items" :fields="model.table.fields" :current-page="1" :per-page="25">

                        <template v-for="field in model.table.fields" :slot="field.key" slot-scope="data">

                            <div v-if="field.callback" v-html="field.callback(data.item)" :key="field.key" />

                            <div v-else-if="field.key == 'action'" :key="field.key">
                              <b-button v-if="model.show.show" @click="go_show(data.item.id)" size="sm" variant="info"><i class="fa fa-search"></i></b-button>
                              <b-button v-if="model.show.edit" @click="go_edit(data.item.id)" size="sm" variant="warning"><i class="fa fa-pencil"></i></b-button>
                              <b-button v-if="model.show.delete" @click="deleteMethod(data.item.id)" size="sm" variant="danger"><i class="fa fa-trash-o"></i></b-button>
                            </div>

                            <div v-else :key="field.key"> {{data.item[field.key]}}</div>

                        </template>

                    </b-table>

                    <nav>
                        <b-pagination :total-rows="response.meta.total" :per-page="response.meta.per_page" v-model="response.meta.current_page" @input="paginate" prev-text="<" next-text=">"  hide-goto-end-buttons/>
                    </nav>
                </b-card>
            </b-col>
        </b-row>
         <div v-show="isLoading"> 
          <p class="d-flex align-items-center">
            <div class="ball-triangle-path ml-5 mt-5 mr-3">
              <div></div>
              <div></div>
              <div></div>
            </div>
          </p>
        </div>
    </div>
</template>

<script>
import bTable from "bootstrap-vue/es/components/table/table";
import bButton from "bootstrap-vue/es/components/button/button";

export default {
  name: "Index",
  props: ["model"],
  data() {
    this.model.table.fields.push({
      key: "action",
      label: "Действия"
    });

    return {
      selected_page: 1,
      searchWatcher: '',
      isLoading: true,
      search_timer: 0,
      response: {
        items: [],
        meta: {
          current_page: 1,
          last_page: 0,
          per_page: 25,
          total: 0
        }
      }
    };
  },
  methods: {
    // Подгружает таблицу
    async paginate(page = 1, searchString = "") {
      let { current_page, per_page } = this.response.meta;

      if (this.$router.currentRoute.query.page !== current_page){
        let pageroute = current_page>1 ? '?page='+current_page : '';
        this.$router.replace(`/${this.model.url}${pageroute}`);
      }

      this.selected_page = page || 1;

      current_page = this.selected_page

      console.log("this.selected_page", this.selected_page);
      console.log("current_page", current_page);
      console.log("per_page", per_page);

      this.isLoading = true;
      let { data } = await this.model.methods.paginate(
        current_page,
        per_page,
        searchString //searchWatcher input text
      );
      this.isLoading = false;

      if (!data.status) {
        console.error("cant get index");
        return;
      }
      this.response = data.data;
    },

    // Редиректит на создание
    async go_add() {
      this.$router.push(`/${this.model.url}/add`);
    },

    // Редиректит на изменение
    async go_edit(id) {
      this.$router.push(`/${this.model.url}/${id}`);
    },

    // Редиректит на просмотр
    async go_show(id) {
      this.$router.push(`/${this.model.url}/${id}/show`);
    },

    // Удаляет
    async deleteMethod(id) {
      if (confirm("Вы уверены что хотите удалить объект?")) {
        let { data } = await this.model.methods.delete(id);

        if (data.status) {
          for (let i = 0; i < this.response.items.length; i++) {
            if (this.response.items[i].id == id) {
              this.response.items.splice(i, 1);
              break;
            }
          }
        }
      }
    }
  },
  async mounted() {
    this.items = [];
    this.selected_page = this.$router.currentRoute.query.page || 1
    await this.paginate(this.selected_page);
    this.isLoading = false;
  },
  created() {
    // Запилить сортируемые данные
    this.model.table.fields.forEach(element => {
      element.sortable = true;

      if (element.key == 'id'){
        element.class = "field_id"
      }
    });
  },
  watch: {
    searchWatcher: function(){
      clearTimeout(this.search_timer)
      this.search_timer = setTimeout(async () => {
        this.isLoading = true;
        await this.paginate(this.selected_page, this.searchWatcher)
        this.isLoading = false;
      }, 1000)
    },
  },
  components: {
    bTable
  }
};
</script>
<style>
.search_element{
  margin-bottom: 15px;
}
.field_id {
  width: 50px;
  text-align: center;
}
.index_table {
  font-size: 1rem;
}
.index_table button{
  margin-right: 10px;
  padding: 0.5rem 0.75rem;
}
</style>
