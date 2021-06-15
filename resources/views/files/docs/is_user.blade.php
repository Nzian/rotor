@section('title', 'Функция is_user')

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/files/docs">Документация Rotor</a></li>
            <li class="breadcrumb-item active">Функция is_user</li>
        </ol>
    </nav>
@stop

Функция проверят авторизацию пользователя на сайте, возвращает true если пользователь авторизован и false если не авторизован<br>

<pre class="prettyprint linenums">
<b>is_user</b>();
</pre><br>

<b>Параметры функции</b><br>
У данной функции нет параметров<br><br>

<b>Примеры использования</b><br>

<?php
echo bbCode(check('[code]<?php
if (getUser()) {
    echo \'Пользователь авторизирован\';
} else {
    echo \'Пользователь не авторизирован\';
}
?>[/code]'));
?>
