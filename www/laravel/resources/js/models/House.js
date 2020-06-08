import ORM from '../api/ORM';
import ManagementCompany from './ManagementCompany';

let model_name = 'houses';

export default {
    name: "Дома",
    url: "houses",
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
                key: "management_company.name",
                label: "УК",
                callback: (house) => {
                    return house.management_company.name;
                }
            },
            {
                key: "street",
                label: "Улица",
            },
            {
                key: "house",
                label: "Дом",
            },
            {
                key: "housing",
                label: "Корпус",
            }
        ],
    },
    form: {
        fields: [{
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
        }, {
            label: 'Улица',
            type: 'text',
            placeholder: 'Улица',
            key: 'street',
            value: '',
        }, {
            label: 'Номер дома',
            type: 'text',
            placeholder: 'Номер дома',
            key: 'house',
            value: '',
        }, {
            label: 'Корпус',
            type: 'text',
            placeholder: 'Корпус',
            key: 'housing',
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