import Role from './Role';
import ORM from '../api/ORM';
import moment from 'moment';
import ManagementCompany from './ManagementCompany';

let model_name = 'users';

export default {
  name: "Пользователи",
  url: "users",
  icon: "fa fa-language fa-lg",

  searchable: true,

  show: {
    add: true,
    edit: true,
    delete: true
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
        label: "ФИО",
      },
      {
        key: "email",
        label: "E-mail"
      },
      {
        key: "phone",
        label: "Телефон"
      },
      {
        key: "role",
        label: "Роль",
        callback: element => {
          return element.role && element.role.name ? `<span class="badge badge-primary">${element.role.name}</span>` : `<span class="badge badge-danger">Роль не установлена</span>`;
        }
      },
      {
        key: "created_at",
        label: "Дата регистрации",
        callback: element => {
          let datetime = moment(String(element.created_at));
          return datetime.isValid() ? datetime.format('DD.MM.YYYY hh:mm') : ''
        }
      }
    ],
  },
  form: {
    fields: [{
        label: 'ФИО',
        type: 'text',
        placeholder: 'Иванов Иван Иванович',
        key: 'name',
        value: '',
      },
      {
        label: 'E-mail',
        type: 'text',
        placeholder: 'почта@mail.ru',
        key: 'email',
        value: '',
      },
      {
        label: 'Телефон',
        type: 'text',
        placeholder: '+79000000000',
        key: 'phone',
        value: '',
      },
      {
        label: 'Логин',
        type: 'text',
        placeholder: 'Логин для авторизации в системе',
        key: 'username',
        value: '',
      },
      {
        label: 'Пароль для входа в систему',
        type: 'password',
        placeholder: '******',
        key: 'password',
        value: '',
      },
      {
        label: 'Подтверждение пароля',
        type: 'password',
        placeholder: '******',
        key: 'password_confirmation',
        value: '',
      },
      {
        label: 'Роль',
        type: 'select',
        select: {
          multiple: false,
          source: Role.methods.all,
          label: 'name',
          value: 'id',
          view: {
            source: 'role',
            key: 'name'
          }
        },
        placeholder: 'Роль пользователя',
        key: 'role_id',
        value: null,
      },
      {
        label: 'Управляющая компания',
        type: 'select',
        select: {
          multiple: false,
          source: ManagementCompany.methods.all,
          label: 'name',
          value: 'id',
          view: {
            source: 'management_company',
            key: 'name'
          }
        },
        placeholder: 'Управляющая компания',
        key: 'management_company_id',
        value: null,
      }
    ]
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
