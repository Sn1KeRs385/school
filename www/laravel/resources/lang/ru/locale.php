<?php

return [
    'message' => 'ru',

    'errors' => [

        // WRONG_PASSWORD_EXCEPTION
        'wrong_password' => 'Неверный пароль.',

        // HAVENT_AUTH_HEADER_EXCEPTION
        'havent_auth_header' => 'Вы не передали авторизационный токен.',

        // BAD_TOKEN_EXCEPTION
        'bad_token' => 'Неверный access_token.',

        // NOTIFICATION_NOT_FOUND
        'notification_not_found' => 'Уведомление не найдено.',

        // NOTIFICATION_HAVE_OTHER_OWNER
        'notification_have_other_owner' => 'Уведомление принадлежит другому пользователю.',

        'sms_sending_failed' => 'Отправка смс не удалась.',
        'sms_incorrect' => 'Неверный смс код.',
        'sms_expired' => 'Смс код истёк.',

        'user_has_been_blocked' => 'Извините, пользователь заблокирован.',

        'vk_error' => 'Ошибка авторизации vk.com.',
        'fb_error' => 'Ошибка авторизации facebook.com.',

        // MUST_BE_VERIFIED_EXCEPTION
        'must_be_verified' => 'Вы не подтвердили свой аккаунт.',

        // MUST_BE_VERIFIED_AS_MENTOR_EXCEPTION
        'must_be_verified_as_mentor' => 'Вы должны быть подтверждены как наставник.',

        // MUST_BE_EXPERT_EXCEPTION
        'must_be_verified_as_expert' => 'Вы должны быть подтверждены как эксперт.',

        // TOKEN_EXPIRED
        'token_expired' => 'Ваш токен истек.',

        // HAVENT_PIN_EXCEPTION
        'havent_pin' => 'Вы не установили пин.',

        // ALREADY_CHILD_EXCEPTION
        'already_child' => 'Вы уже ребенок.',

        // WRONG_PIN_EXCEPTION
        'wrong_pin' => 'Неверный пин.',

        'user_not_found'=> 'Пользователь не зарегистрирован, либо введен неверный E-mail',

        'movie_not_found'=> 'Фильм не найден',

        // CONTROLLER_INVALID_PARAMETERS_EXCEPTION
        'meter_too_little' => ':id # строка содержит слишком мало переменных',
        'meter_dublicated' => ':modem_id # вы пытаетесь добавить 2 счетчика с одинаковыми ид модема',
        'meter_wrong_type' => ':id # :type неверный тип счетчика',
        'meter_exist' => 'счетчик с модемом :modem_id уже существует',
        'meter_wrong_time_send_data' => 'Сейчас нельзя передавать показания',
        'meter_not_found' => 'Счетчик не найден',

    ],

    'answers' =>[
        'meter_added' => 'счетчик с модемом :modem_id добавлен',

        'sms_registration' => 'Ваш код для входа :code',
    ]

];
