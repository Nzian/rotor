@extends('layout')

@section('title')
    Собственные страницы сайта
@stop

@section('content')

    <h1>Как создать свои страницы</h1>

    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item active">Как создать свои страницы</li>
        </ol>
    </nav>

    1. Перейдите в директорию /resources/views/files, эта директория автоматически генерирует страницы сайта<br>
    2. Создайте в ней директорию с произвольным латинским названием (к примеру library)<br>
    3. Положите в созданную директорию обычный файл с расширением .blade.php (к примеру index.blade.php)<br>
    4. Напишите любой текст на этой странице, это может быть как html код, так и php<br>
    5. Теперь попробуйте перейти на созданную станицу, введите в браузере <?= siteUrl() ?>/files/library<br>
    6. Если страница отобразилась, значит вы все сделали правильно<br>

    <div class="help-block">
        В одной директории может быть неограниченное число файлов, расширение указывать не нужно, только имя папки и имя файла через слеш, к примеру /library/simplepage, /library/index то же что и просто /library <br><br>

        Также можно указать заголовок страницы, который автоматически подставится в блок title, для этого нужно написать следующий код

    <pre class="prettyprint linenums">
    @@section('title')
        Новый заголовок страницы
    @@stop
    </pre>

        Дополнительно можно указать произвольные ключевые слова и описание заполнив переменные setting('keywords') и setting('description')

    <pre class="prettyprint linenums">
    @@section('keywords')
        Ключевые слова
    @@stop
    
    @@section('keywords')
        Описание страниц
    @@stop
    </pre>
    </div>

    Посмотрите пример страниц в виде <a href="/files/docs">документации Rotor</a><br>
@stop
