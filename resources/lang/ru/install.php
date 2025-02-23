<?php

return [
    'install'               => 'Установка',
    'update'                => 'Обновление',
    'step1'                 => 'Шаг 1 - проверка требований',
    'step2_install'         => 'Шаг 2 - проверка статуса БД (установка)',
    'step3_install'         => 'Шаг 3 - выполнение миграций (установка)',
    'step4_install'         => 'Шаг 4 - заполнение БД (установка)',
    'step5_install'         => 'Шаг 5 - создание администратора (установка)',
    'step2_update'          => 'Шаг 2 - проверка статуса БД (обновление)',
    'install_completed'     => 'Установка завершена',
    'update_completed'      => 'Обновление завершено',
    'debug'                 => 'Если в процессе установки движка произойдет какая-либо ошибка, чтобы узнать причину ошибки включите вывод ошибок, измените значение APP_DEBUG на true',
    'env'                   => 'Для установки движка, вам необходимо прописать данные от БД в файл .env',
    'app_key'               => 'Не забудьте изменить значение APP_KEY, эти данные необходимы для хеширования cookies и паролей в сессиях',
    'requirements'          => 'Минимальная версия PHP необходимая для работы движка PHP :php и MySQL :mysql, MariaDB :maria или Postgres :pgsql',
    'check_requirements'    => 'Проверка требований',
    'ffmpeg'                => 'Для обработки видео желательно установить библиотеку FFmpeg',
    'chmod_rights'          => 'Права доступа',
    'chmod_views'           => 'Дополнительно можете выставить права на директории и файлы с шаблонами внутри resources/views - это необходимо для редактирования шаблонов сайта',
    'chmod'                 => 'Если какой-то пункт выделен красным, необходимо зайти по FTP и выставить CHMOD разрешающую запись',
    'errors'                => 'Некоторые настройки являются рекомендуемыми для полной совместимости, однако скрипт способен работать даже если рекомендуемые настройки не совпадают с текущими.',
    'continue'              => 'Вы можете продолжить установку движка!',
    'requirements_pass'     => 'Все модули и библиотеки присутствуют, настройки корректны, необходимые файлы и папки доступны для записи',
    'requirements_not_pass' => 'Данные предупреждения не являются критическими, но тем не менее для полноценной, стабильной и безопасной работы движка желательно их устранить',
    'continue_restrict'     => 'Вы можете продолжить установку скрипта, но нет никаких гарантий, что движок будет работать стабильно',
    'check_status'          => 'Проверить статус БД',
    'requirements_warning'  => 'У вас имеются предупреждения!',
    'requirements_failed'   => 'Имеются критические ошибки!',
    'requirements_url'      => 'Адрес сайта в файле env ":env_url" отличается от фактического ":current_url"!',
    'resolve_errors'        => 'Вы не сможете приступить к установке, пока не устраните критические ошибки',
    'migrations'            => 'Выполнить миграции',
    'seeds'                 => 'Заполнить БД',
    'create_admin'          => 'Создать администратора',
    'create_admin_info'     => 'Прежде чем перейти к администрированию вашего сайта, необходимо создать аккаунт администратора.',
    'create_admin_errors'   => 'Перед тем как нажимать кнопку Создать, убедитесь, что на предыдущей странице нет уведомлений об ошибках, иначе процесс не сможет быть завершен удачно',
    'delete_install'        => 'После окончания инсталляции необходимо удалить файл app/Http/Controllers/InstallController.php, пароль и остальные данные вы сможете поменять в своем профиле',
    'welcome'               => 'Добро пожаловать!',
    'text_message'          => 'Привет, :login! Поздравляем с успешной установкой нашего движка Rotor.
    Новые версии, апгрейды, а также множество других дополнений вы найдете на нашем сайте [url=https://visavi.net]visavi.net[/url]',
    'text_news'             => 'Добро пожаловать на демонстрационную страницу движка Rotor
    Rotor - функционально законченная система управления контентом с открытым кодом написанная на PHP. Она использует базу данных MySQL для хранения содержимого вашего сайта. Rotor является гибкой, мощной и интуитивно понятной системой с минимальными требованиями к хостингу, высоким уровнем защиты и является превосходным выбором для построения сайта любой степени сложности
    Главной особенностью Rotor является низкая нагрузка на системные ресурсы, даже при очень большой аудитории сайта нагрузка не сервер будет минимальной, и вы не будете испытывать каких-либо проблем с отображением информации.
    Движок Rotor вы можете скачать на официальном сайте [url=https://visavi.net]visavi.net[/url]',
    'success_install'       => 'Поздравляем, Rotor был успешно установлен!',
    'success_update'        => 'Поздравляем, Rotor был успешно обновлен!',
    'main_page'             => 'Перейти на главную страницу сайта',
];
