<template>
  <b-row>
    <b-col sm="12">
      <b-form-group>
        
        <label :for="data.key">{{data.label}}</label>     

        <v-select v-if="data.type == 'select'"
          v-model="data.value"          
          :id="data.key"
          :options="options"
          :multiple="data.select.multiple"
          :searchable="true"
          :placeholder="data.placeholder">
        </v-select>
        
        <b-form-textarea v-else-if="data.type == 'textarea'"
          v-model="data.value"
          :id="data.key"
          :textarea="true"
          :rows="9"
          :placeholder="data.placeholder">
        </b-form-textarea>
        
        <switches v-else-if="data.type == 'checkbox'"
          v-model="data.value"
          :value="data.value"
          theme="bootstrap"
          color="success"
          style="margin-left: 15px;">
        </switches>

        <datetime v-else-if="data.type == 'date' || data.type == 'datetime'"
          v-model="data.value"
          :type="data.type"
          :id="data.key"
          :placeholder="data.placeholder"
          :phrases="{ok: 'OK', cancel: 'Отменить'}"
          input-class="form-control">
        </datetime>
        
        <!-- @input="data.value = $event;"
          :value="data.value" -->
        <file-uploader v-else-if="data.type == 'file'"
          v-model="data.value"
          :placeholder="data.placeholder"
          :id="data.key"
          :accept="data.options.accept"
          :max_files="data.options.max_files"
          :source="data.options.source">
        </file-uploader>


      <!-- @input="data.values = $event;"
          :value="data.value" -->
        <sub-form v-else-if="data.type == 'subform'"
          :form="data.form"
          v-model="data.value">
        </sub-form>
          
        <!-- text, number -->
        <b-form-input v-else 
          v-model="data.value"
          :readonly="data.readonly"
          :id="data.key"
          :type="data.type"
          :placeholder="data.placeholder">
        </b-form-input>
        
      </b-form-group>
    </b-col>
  </b-row>
</template>

<script>

import Switches from "vue-switches";

import vSelect from "vue-select";

import { Datetime } from "vue-datetime";
import "vue-datetime/dist/vue-datetime.css";

import fileUploader from "./FileUploader";
import subForm from "./SubForm";

export default {
  name: "FormField",
  props: ["name", "data"],
  data() {
    return {
      options: []
    };
  },
  async mounted() {
    switch (this.data.type) {
      case "select":
        this.options = [];
        let { data } = await this.data.select.source();
        await data.data.forEach(async element => {
          this.options.push({
            label: typeof(this.data.select.label) == 'function' ? this.data.select.label(element) : element[this.data.select.label].toString(),
            value: element[this.data.select.value].toString()
          });
        });
        break;
    }
  },
  components: {
    vSelect,
    Datetime,
    Switches,
    fileUploader,
    subForm
  }
};
</script>
