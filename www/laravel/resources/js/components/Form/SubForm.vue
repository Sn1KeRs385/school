<template>
    <b-card>

      <div slot="header">
        <strong>{{form.label}}</strong>
      </div>

      <form @submit.prevent="addMethod">
        
        <form-field v-for="field in form.fields"
            v-bind:key="field.key"
            :data="field">
        </form-field>
        
        <b-table
          :hover="true"
          :striped="true"
          :bordered="true"
          :small="true"
          :fixed="false"
          responsive="sm"
          :items="value"
          :fields="getFields"
          :current-page="1"
          :per-page="25"
        >

          <template slot="actions" slot-scope="i">
            <b-button @click="deleteMethod(i.item)" size="sm" variant="danger"><i class="fa fa-trash-o"></i></b-button>                
          </template>
          
        </b-table>

        <div slot="footer">
          <b-button type="submit" size="sm" variant="primary"><i class="fa fa-save"></i> Добавить</b-button>
        </div>
      </form>

    </b-card>
</template>
<script>
export default {
  name: "SubForm",
  props: ["form", "value"],
  computed: {
    getFields() {
      let fields = [...this.form.fields];
      fields.push({
        actions: "Управление"
      });
      return fields;
    }
  },
  methods: {
    deleteMethod(item) {
      if (!confirm("Вы действительно хотите удалить объект?")) {
        return;
      }
      var idx = this.value.indexOf(item);
      if (idx != -1) {
        return this.value.splice(idx, 1);
      }
    },
    addMethod() {
      let row = new Object();
      row["value"] = new Object();

      this.form.fields.forEach(v => {
        row[v.key] = v.value.label;
        row["value"][v.key] = v.value.value;

        v.value = null
        console.log('v', v)
      });

      console.log("row", row);
      this.value.push(row);

      console.log(this.value);

      let outs = [];

      this.value.forEach(v => {
        let out = new Object();
        Object.keys(v.value).forEach(property => {
          out[property] = v.value[property];
        });
        console.log("out", out);
        outs.push(out);
      });

      this.$emit("input", this.value);
      // this.$emit("input", outs);
    }
  }
};
</script>
