@section('title', 'Функция user_position')

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/files/docs">Документация Rotor</a></li>
            <li class="breadcrumb-item active">Функция user_position</li>
        </ol>
    </nav>
@stop

Определение местонахождения по url (Удалено в версии 3.5.8)<br>

<pre class="prettyprint linenums">
<b>user_position</b>(
    string url
);
</pre><br>

<b>Параметры функции</b><br>

<b>url</b> - ссылка (без слэша в начале), которую нужно искать в таблице headers<br><br>

<div class="info"><b>Примечание</b><br>
Данные о местонахождении пользователя берутся из таблицы headers, если на сайте имеются страницы, которые отсутсвуют в таблице, то узнать местонахождение по ссылке не получится, будет выведен результат "Не определено"
</div><br>

<b>Примеры использования</b><br>
<?php
echo bbCode(check('[code]<?php
echo user_position(\'forum/index.php\'); // выведет - Форум
?>[/code]'));
?>
