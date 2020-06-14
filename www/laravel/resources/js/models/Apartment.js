import ORM from '../api/ORM';
import Entrance from './Entrance';

let model_name = 'apartments';

export default {
    name: "Квартиры",
    url: "apartments",
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
                key: "entrance.house.management_company",
                label: "УК",
                callback: (apartment) => {
                    return apartment.entrance.house.management_company.name;
                }
            },
            {
                key: "entrance.house",
                label: "Подъезд",
                callback: (apartment) => {
                    return [apartment.entrance.house.street, apartment.entrance.house.house + (apartment.entrance.house.housing || ''), 'подъезд №' + apartment.entrance.number].join(', ')
                }
            },
            {
                key: "number",
                label: "Номер квартиры",
            }
        ],
    },
    form: {
        fields: [{
                label: 'Подъезд',
                type: 'select',
                select: {
                    multiple: false,
                    source: Entrance.methods.all,
                    label: (element) => {
                        return [element.house.street, element.house.house + (element.house.housing || ''), 'подъезд №' + element.number].join(', ');
                    },
                    value: 'id',
                    view: {
                        source: 'entrance',
                        key: 'entrance'
                    }
                },
                placeholder: 'Подъезд',
                key: 'entrance_id',
                value: null,
            },
            {
                label: 'Номер квартиры',
                type: 'text',
                placeholder: 'Номер квартиры',
                key: 'number',
                value: '',
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