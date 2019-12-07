<?php

return [
    'rules' => 'Общие правила для пользователей сайта %SITENAME%',

    'settings' => [
        'language'       => 'ru',
        'currency'       => 'руб',
        'guest_user'     => 'Гость',
        'deleted_user'   => 'Удаленный аккаунт',
        'default_status' => 'Дух',
        'description'    => 'Краткое описание вашего сайта',
        'logos'          => 'Сайт на движке Rotor',
        'moneyname'      => 'монета,монеты,монет',
        'scorename'      => 'балл,балла,баллов',
    ],

    'statuses' => [
        'novice'       => 'Новичок',
        'local'        => 'Местный',
        'advanced'     => 'Продвинутый',
        'experienced'  => 'Бывалый',
        'specialist'   => 'Специалист',
        'expert'       => 'Знаток',
        'master'       => 'Мастер',
        'professional' => 'Профессионал',
        'guru'         => 'Гуру',
        'legend'       => 'Легенда',
    ],

    'notices' => [
        'register_name' => 'Приветствие при регистрации в приват',
        'register_text' => 'Добро пожаловать, %username%!
Теперь Вы полноправный пользователь сайта, сохраните ваш логин и пароль в надежном месте, они пригодятся вам для входа на наш сайт.
Перед посещением сайта рекомендуем вам ознакомиться с [url=/rules]правилами сайта[/url], это поможет Вам избежать неприятных ситуаций.
Желаем приятно провести время.
С уважением, администрация сайта!',

        'down_upload_name' => 'Уведомление о загрузке файла',
        'down_upload_text' => 'Уведомеление о загрузке файла.
Новый файл [b][url=%url%]%title%[/url][/b] требует подтверждения на публикацию!',

        'down_publish_name' => 'Уведомление о публикации файла',
        'down_publish_text' => 'Уведомеление о публикации файла.
Ваш файл [b][url=%url%]%title%[/url][/b] успешно прошел проверку и добавлен в загрузки',

        'down_unpublish_name' => 'Уведомление о снятии с публикации',
        'down_unpublish_text' => 'Уведомление о снятии с публикации.
Ваш файл [b][url=%url%]%title%[/url][/b] снят с публикации из загрузок',

        'down_change_name' => 'Уведомление об изменении файла',
        'down_change_text' => 'Уведомление об изменении файла.
Ваш файл [b][url=%url%]%title%[/url][/b] был отредактирован модератором, возможно от вас потребуются дополнительные исправления!',

        'notify_name' => 'Упоминание пользователя',
        'notify_text' => 'Пользователь @%login% упомянул вас на странице [b][url=%url%]%title%[/url][/b]
Текст сообщения: %text%',

        'invite_name' => 'Отправка пригласительных ключей',
        'invite_text' => 'Поздравляем! Вы получили пригласительные ключи
Ваши ключи: %key%
С помощью этих ключей вы можете пригласить ваших друзей на наш сайт!',

        'contact_name' => 'Добавление в контакт-лист',
        'contact_text' => 'Пользователь @%login% добавил вас в свой контакт-лист!',

        'ignore_name' => 'Добавление в игнор-лист',
        'ignore_text' => 'Пользователь @%login% добавил вас в свой игнор-лист!',

        'transfer_name' => 'Перевод денег',
        'transfer_text' => 'Пользователь @%login% перечислил вам %money% 
Комментарий: %comment%',

        'rating_name' => 'Изменение репутации',
        'rating_text' => 'Пользователь @%login% поставил вам %vote%! (Ваш рейтинг: %rating%)
Комментарий: %comment%',

        'surprise_name' => 'Новогодний сюрприз',
        'surprise_text' => 'Поздравляем с новым %year% годом!
В качестве сюрприза вы получаете:
%point% 
%money%
%rating% репутации
Ура!!!',

        'explain_name' => 'Объяснение нарушения',
        'explain_text' => 'Объяснение нарушения: %message%',
    ],
];
