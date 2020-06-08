<template>
  <div class="v-cloak animated fadeIn">
    <b-card v-show="!isLoading">
      <div slot="header">
        <strong>{{model.name}}. {{title}}</strong>
      </div>

      <form @submit.prevent="saveMethod">
        <transition-group name="list">
          <FormField
            v-for="field in model.form.fields"
            v-bind:key="field.key"
            v-if="field.visible"
            :data="field"
          />
        </transition-group>

        <div slot="footer">
          <b-button type="submit" size="sm" variant="primary" :disabled="isSaving">
            <i v-if="isSaving" class="fa fa-spinner fa-pulse"></i>
            <i v-else class="fa fa-save"></i>
            Сохранить
          </b-button>
        </div>
      </form>
    </b-card>

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
import moment from "moment";

export default {
  name: "Form",
  props: ["model"],
  notifications: {
    showToast({ type, title, message }) {
      return {
        type,
        title,
        message
      };
    }
  },
  data() {
    let id = this.$router.currentRoute.params.id;

    this.model.form.fields = this.model.form.fields.map(field => {
      if (field.visible == undefined) {
        field.visible = true;
      }
      return field;
    });

    return {
      isLoading: true,
      isSaving: false,
      id,
      title: !id ? "Создание" : "Редактирование"
    };
  },
  methods: {
    async saveMethod() {
      this.isSaving = true;

      let data = new Object();

      this.model.form.fields.forEach(v => {
        switch (v.type) {
          case "select":
            if (v.select.multiple) {
              data[v.key] = [];
              v.value.forEach(element => {
                data[v.key].push(parseInt(element.value));
              });
            } else {
              data[v.key] = v.value ? parseInt(v.value.value) : null;
            }
            break;

          case "number":
            data[v.key] = v.value ? parseInt(v.value) : null;
            break;

          case "checkbox":
            data[v.key] = Boolean(v.value);
            break;

          case "file":
            if (v.options.max_files > 1) {
              data[v.key] = [];
              v.value.forEach(f => {
                if (!f.storage) {
                  data[v.key] = f.id;
                }
              });
            } else {
              if (v.value.length > 0) {
                if (!v.value[0].storage) {
                  data[v.key] = v.value[0].id;
                }
              }
            }
            console.log("file data[v.key]", data[v.key]);
            break;

          case "subform":
            data[v.key] = v.value ? v.value : [];
            break;

          case "datetime":
            let datetime = moment.utc(String(v.value));
            data[v.key] = datetime.isValid()
              ? datetime.format("YYYY-MM-DD hh:mm")
              : null;
            break;

          case "date":
            let date = moment(String(v.value));
            data[v.key] = date.isValid() ? date.format("YYYY-MM-DD") : null;
            break;

          case "file":
            console.log("v", v);
            if (v.options.multiple) {
              data[v.key] = v.value;
            } else {
              data[v.key] = parseInt(v.value);
            }
            break;

          default:
            // text, textarea
            data[v.key] = v.value;
            break;
        }
      });

      console.log("fillable", data);

      try {
        let response;
        if (!this.id) {
          response = await this.model.methods.create(data);
        } else {
          response = await this.model.methods.update(this.id, data);
        }

        this.isSaving = false;

        if (response.data.status == true) {
          this.$router.push(`/${this.model.url}`);
        } else {
          // toast
          this.showToast({
            type: "warn",
            title: "Ошибка в заполнении формы",
            message: "Что-то пошло не так"
          });
        }
      } catch (error) {
        // toast
        this.showToast({
          type: "warn",
          title: "Какая-то серьезная ошибка",
          message: "Что-то пошло не так"
        });
        this.isSaving = false;
      }
    }
  },

  async created() {
    // Сбросить все запомненные значения
    await this.model.form.fields.forEach(async (v, i, a) => {
      switch (v.type) {
        case "text":
          v.value = "";
          break;

        case "select":
          if (v.select.multiple) {
            v.value = [];
          } else {
            v.value = null;
          }
          break;

        case "subform":
          v.value = [];
          break;

        case "file":
          v.value = [];
          break;

        default:
          v.value = "";
          break;
      }
    });

    if (this.id) {
      let { data } = await this.model.methods.find(
        this.$router.currentRoute.params.id
      );

      let model = data.data;

      console.log("object", model);

      await this.model.form.fields.forEach(async v => {
        switch (v.type) {
          case "text":
            v.value = model[v.key];
            break;

          case "select":
            if (v.select.multiple) {
              v.value = [];
              model[v.select.view.source].forEach(element => {
                v.value.push({
                  label:
                    typeof v.select.label == "function"
                      ? v.select.label(model[v.select.view.source])
                      : model[v.select.view.source][v.select.label].toString(),
                  value: element[v.select.value].toString()
                });
              });
            } else {
              v.value = {
                label:
                  typeof v.select.label == "function"
                    ? v.select.label(model[v.select.view.source])
                    : model[v.select.view.source][v.select.label].toString(),
                value: model[v.select.view.source][v.select.value].toString()
              };
            }
            break;

          case "file":
            v.value = [];
            for (const f of model[v.options.source]) {
              v.value.push({
                id: f.id,
                file: {
                  name: f[v.options.key]
                },
                uploaded: true,
                storage: true,
                status: f.status
              });
            }
            break;

          case "subform":
            v.value = [];
            // console.log("v.form.fields", v.form.fields);

            let sources = new Object();
            for (const f of v.form.fields) {
              switch (f.type) {
                case "select":
                  let response = await f.select.source();
                  sources[f.key] = response.data.data;
                  break;
              }
            }

            await model[v.key].forEach(async p => {
              let { pivot } = p;
              let row = new Object();
              row["value"] = pivot;
              await Object.keys(pivot).forEach(async key => {
                if (sources[key]) {
                  await sources[key].forEach(async sourceRow => {
                    for (const f of v.form.fields) {
                      if (f.type == "select" && f.key == key) {
                        if (sourceRow[f.select.value] == pivot[key]) {
                          row[key] = sourceRow[f.select.view.key];
                        }
                      }
                    }
                  });
                } else {
                  row[key] = key;
                }
              });
              v.value.push(row);
            });
            break;

          case "datetime":
            v.value = moment
              .utc(String(model[v.key]))
              .local()
              .toISOString();
            break;

          case "date":
            v.value = moment
              .utc(String(model[v.key]))
              .local()
              .toISOString();
            break;

          default:
            v.value = model[v.key];
            break;
        }
      });
    }
    this.isLoading = false;
  }
};
</script>

<style>
.list-item {
  display: inline-block;
}

.list-enter-active,
.list-leave-active {
  transition: all 0.5s;
}

.list-enter,
.list-leave-to {
  height: 0px;
  opacity: 0;
  transform: translateY(30px);
}
</style>

