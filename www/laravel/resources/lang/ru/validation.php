<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
     */

    // 'time_of_work_array' => 'Поле «:attribute» должно быть json массивом из 7 элементов.',
    // 'time_of_work_el' => 'Поле «:attribute» - поврежденный элемент массива.',

    'accepted' => 'Вы должны принять «:attribute».',
    'active_url' => 'Поле «:attribute» содержит недействительный URL.',
    'after' => 'В поле «:attribute» должна быть дата после :date.',
    'after_or_equal' => 'В поле «:attribute» должна быть дата после или равняться :date.',
    'alpha' => 'Поле «:attribute» может содержать только буквы.',
    'alpha_dash' => 'Поле «:attribute» может содержать только буквы, цифры и дефис.',
    'alpha_num' => 'Поле «:attribute» может содержать только буквы и цифры.',
    'array' => 'Поле «:attribute» должно быть массивом.',
    'before' => 'В поле «:attribute» должна быть дата до :date.',
    'before_or_equal' => 'В поле «:attribute» должна быть дата до или равняться :date.',
    'between' => [
        'numeric' => 'Поле «:attribute» должно быть между :min и :max.',
        'file' => 'Размер файла в поле «:attribute» должен быть между :min и :max Килобайт(а).',
        'string' => 'Количество символов в поле «:attribute» должно быть между :min и :max.',
        'array' => 'Количество элементов в поле «:attribute» должно быть между :min и :max.',
    ],
    'boolean' => 'Поле «:attribute» должно иметь значение логического типа.', // калька 'истина' или 'ложь' звучала бы слишком неестественно
    'confirmed' => 'Поле «:attribute» не совпадает с подтверждением.',
    'date' => 'Поле «:attribute» не является датой.',
    'date_format' => 'Поле «:attribute» не соответствует формату :format.',
    'different' => 'Поля «:attribute» и :other должны различаться.',
    'digits' => 'Длина цифрового поля «:attribute» должна быть :digits.',
    'digits_between' => 'Длина цифрового поля «:attribute» должна быть между :min и :max.',
    'dimensions' => 'Поле «:attribute» имеет недопустимые размеры изображения.',
    'distinct' => 'Поле «:attribute» содержит повторяющееся значение.',
    'email' => 'Поле «:attribute» должно быть действительным электронным адресом.',
    'exists' => 'Выбранное значение для «:attribute» некорректно.',
    'file' => 'Поле «:attribute» должно быть файлом.',
    'filled' => 'Поле «:attribute» обязательно для заполнения.',
    'image' => 'Поле «:attribute» должно быть изображением.',
    'in' => 'Выбранное значение для «:attribute» ошибочно.',
    'in_array' => 'Поле «:attribute» не существует в :other.',
    'integer' => 'Поле «:attribute» должно быть целым числом.',
    'ip' => 'Поле «:attribute» должно быть действительным IP-адресом.',
    'json' => 'Поле «:attribute» должно быть JSON строкой.',
    'max' => [
        'numeric' => 'Поле «:attribute» не может быть более :max.',
        'file' => 'Размер файла в поле «:attribute» не может быть более :max Килобайт(а).',
        'string' => 'Количество символов в поле «:attribute» не может превышать :max.',
        'array' => 'Количество элементов в поле «:attribute» не может превышать :max.',
    ],
    'mimes' => 'Поле «:attribute» должно быть файлом одного из следующих типов: :values.',
    'mimetypes' => 'Поле «:attribute» должно быть файлом одного из следующих типов: :values.',
    'min' => [
        'numeric' => 'Поле «:attribute» должно быть не менее :min.',
        'file' => 'Размер файла в поле «:attribute» должен быть не менее :min Килобайт(а).',
        'string' => 'Количество символов в поле «:attribute» должно быть не менее :min.',
        'array' => 'Количество элементов в поле «:attribute» должно быть не менее :min.',
    ],
    'not_in' => 'Выбранное значение для «:attribute» ошибочно.',
    'numeric' => 'Поле «:attribute» должно быть числом.',
    'present' => 'Поле «:attribute» должно присутствовать.',
    'regex' => 'Поле «:attribute» имеет ошибочный формат.',
    'required' => 'Поле «:attribute» обязательно для заполнения.',
    'required_if' => 'Поле «:attribute» обязательно для заполнения, когда :other равно :value.',
    'required_unless' => 'Поле «:attribute» обязательно для заполнения, когда :other не равно :values.',
    'required_with' => 'Поле «:attribute» обязательно для заполнения, когда :values указано.',
    'required_with_all' => 'Поле «:attribute» обязательно для заполнения, когда :values указано.',
    'required_without' => 'Поле «:attribute» обязательно для заполнения, когда :values не указано.',
    'required_without_all' => 'Поле «:attribute» обязательно для заполнения, когда ни одно из :values не указано.',
    'same' => 'Значение «:attribute» должно совпадать с :other.',
    'size' => [
        'numeric' => 'Поле «:attribute» должно быть равным :size.',
        'file' => 'Размер файла в поле «:attribute» должен быть равен :size Килобайт(а).',
        'string' => 'Количество символов в поле «:attribute» должно быть равным :size.',
        'array' => 'Количество элементов в поле «:attribute» должно быть равным :size.',
    ],
    'string' => 'Поле «:attribute» должно быть строкой.',
    'timezone' => 'Поле «:attribute» должно быть действительным часовым поясом.',
    'unique' => 'Такое значение поля «:attribute» уже существует.',
    'uploaded' => 'Загрузка поля «:attribute» не удалась.',
    'url' => 'Поле «:attribute» имеет ошибочный формат.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
     */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
        'description' => [
            //    'required' => 'Поле «Описание» обязательно для заполнения.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
     */

    'attributes' => [
        'description' => 'Описание',
        'contact_type' => 'Тип связи',
        'text' => 'Текст',
        'name' => 'Название',
        'user_id' => 'Пользователь',
        'order_id' => 'Заказ',
        'isClosed' => 'Статус',
        'days_for_close_order' => 'Дней до закрытия заказа',
        'private' => 'Приватность',
        'category_id' => 'Категория',
        'subcategory_id' => 'Подкатегория',
        'email' => 'Email',
        'photo' => 'Фото',
        'id_cms_privileges' => 'Права доступа',
        'password' => 'Пароль',
        'city_kladr_id' => 'Город',
        'region_kladr' => 'Кадастровые номера',
        'region_kladr_id' => 'Кадастровый номер',
        'service_categories' => 'Категории',

        //для API
        'phone' => 'Телефон',
        'sms' => 'SMS',
        'type' => 'Тип',
        'q' => 'Запрос',
        'image_id' => 'Изображение',
        'image' => 'Изображение',
        'region_ids' => 'Регионы',
        'subcategory_ids' => 'Подкатегории',
        'addresses' => 'Адреса',
        'addresses.*' => 'Адрес',
        'truck' => 'Техника',
        'image_ids' => 'Изображения',
    ],

];
