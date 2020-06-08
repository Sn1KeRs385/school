import ORM from '../api/ORM';
import House from './House';

let model_name = 'entrances';

export default {
    name: "Подъезды",
    url: "entrances",
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
                key: "house.management_company.name",
                label: "УК",
                callback: (entrance) => {
                    return entrance.house.management_company.name;
                }
            },
            {
                key: "house",
                label: "Дом",
                callback: (entrance) => {
                    return [entrance.house.street, entrance.house.house + (entrance.house.housing || '')].join(', ')
                }
            },
            {
                key: "number",
                label: "Номер подъезда",
            },
            {
                key: "storeys",
                label: "Этажность",
            }
        ],
    },
    form: {
        fields: [{
                label: 'Дом',
                type: 'select',
                select: {
                    multiple: false,
                    source: House.methods.all,
                    label: (element) => {
                        return [element.street, element.house + (element.housing || '')].join(', ');
                    },
                    value: 'id',
                    view: {
                        source: 'house',
                        key: 'house'
                    }
                },
                placeholder: 'Дом',
                key: 'house_id',
                value: null,
            }, {
                label: 'Номер подъезда',
                type: 'number',
                placeholder: 'Номер подъезда',
                key: 'number',
                value: 0,
            },
            {
                label: 'Этажность',
                type: 'number',
                placeholder: 'Этажность',
                key: 'storeys',
                value: 0,
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