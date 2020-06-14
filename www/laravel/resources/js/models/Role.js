import ORM from '../api/ORM';

let model_name = 'roles';

export default {
  name: "Роли",
  url: "roles",
  icon: "fa fa-language fa-lg",

  searchable: false,

  show: {
    add: false,
    edit: true,
    delete: false
  },

  table: {
    
    sort: {

    },

    fields: [{
        key: "id",
        label: "#"
      },
      {
        key: "name",
        label: "Название",
      }
    ],
  },
  form: {
    fields: [{
      label: 'Название',
      type: 'text',
      placeholder: 'Название роли',
      key: 'name',
      value: '',
    }]
  },
  methods: {
    async all() {
      return await ORM.all(model_name);
    },
    async paginate(page, per_page, q) {
      return await ORM.paginate(model_name, page, per_page, q);
    },
    async find(id) {
      return await ORM.find(model_name, id);
    },
    async create(data) {
      return await ORM.create(model_name, data);
    },
    async update(id, data) {
      return await ORM.update(model_name, id, data);
    },
    async delete(id) {
      return await ORM.delete(model_name, id);
    }
  }
}
