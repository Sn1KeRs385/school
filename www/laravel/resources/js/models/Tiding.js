import ORM from '../api/ORM';

let model_name = 'tidings';

export default {
    name: "Новости",
    url: "tidings",
    icon: "fa fa-language fa-lg",

    searchable: true,

    show: {
        add: true,
        edit: true,
        delete: true
    },

    table: {

        sort: {},

        fields: [{
            key: "id",
            label: "#"
        }, {
            key: "name",
            label: "Название",
        }, {
            key: "published_at",
            label: "Дата публикации",
            callback: time => {
                var moment = require('moment');
                return moment(time.published_at).lang("ru").format('LL');
            }
        }, {
            key: "expired_at",
            label: "Дата перемещения в архив",
            callback: time => {
                var moment = require('moment');
                return moment(time.expired_at).lang("ru").format('LL');
            }
        }, {
            key: "author",
            label: "Автор",
        }
        ],
    },
    form: {
        fields: [{
            label: 'Название',
            type: 'text',
            placeholder: 'Название',
            key: 'name',
            value: '',
        }, {
            label: 'Содержание',
            type: 'texteditor',
            placeholder: 'Содержание',
            key: 'content',
            value: '',
        }, {
            label: 'Дата публикации',
            type: 'date',
            placeholder: '',
            key: 'published_at',
            value: '',
        }, {
            label: 'Дата перемещения в архив',
            type: 'date',
            placeholder: '',
            key: 'expired_at',
            value: '',
        }, {
            label: 'Изображения',
            type: 'file',
            options: {
                max_files: 20,
                accept: 'image/*',
                source: 'news_img',
                key: 'original_filename',
            },
            key: 'news_img_id',
        }, {
            label: 'Автор',
            type: 'text',
            placeholder: '',
            key: 'author',
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