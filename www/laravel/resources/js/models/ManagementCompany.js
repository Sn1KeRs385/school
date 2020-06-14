import ORM from '../api/ORM';

let model_name = 'management_companies';

export default {
    name: "Управляющие компании",
    url: "management_companies",
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
                label: "Название УК",
            },
            {
                key: "email",
                label: "Email",
            }
        ],
    },
    form: {
        fields: [{
            label: 'Название УК',
            type: 'text',
            placeholder: 'Название УК',
            key: 'name',
            value: '',
        }, {
            label: 'Email',
            type: 'text',
            placeholder: 'Email',
            key: 'email',
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