@section('title', 'Функция last_page')

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/files/docs">Документация Rotor</a></li>
            <li class="breadcrumb-item active">Функция last_page</li>
        </ol>
    </nav>
@stop

Функция вычисляет номер последней страницы на основе определенного вывода количества сообщений на странице<br>
К примеру если у пользователя выводится 15 сообщений на страницу в форуме, а всего сообщений 32, то последняя страница будет иметь номер 3,
а если выводится 10 сообщений на страницу, то номер 4
<br><br>

<pre class="prettyprint linenums">
<b>last_page</b>(
  int total
  int posts
);
</pre><br>

<b>Параметры функции</b><br>

<b>total</b> - Общее количество сообщений<br>
<b>posts</b> - Число вывода сообщений у пользователя на страницу
<br><br>

<b>Примеры использования</b><br>
<?php
echo bbCode(check('[code]<?php
if ($start >= $total) {
  $start = last_page($total, $config[\'bookpost\']);
}
// Если текущая страница больше чем всего страниц, то переменная start будет иметь значение последней страницы
?>[/code]'));
?>
