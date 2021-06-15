@section('title', 'Функция date_fixed')

@section('breadcrumb')
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><a href="/files/docs">Документация Rotor</a></li>
            <li class="breadcrumb-item active">Функция date_fixed</li>
        </ol>
    </nav>
@stop

Конвертирует время timestamp с учетом часового пояса конкретного пользователя в нормальное представление<br>
Если указан формат по умолчанию, то вместо текущей даты будет написано Сегодня или Вчера, если дата вчерашняя<br><br>

<pre class="prettyprint linenums">
<b>date_fixed</b>(
    int timestamp,
    string format = "d.m.y / H:i"
);
</pre><br>

<b>Параметры функции</b><br>

<b>timestamp</b> - Время в Unix-формате. Если переданы неверные данные, то подставится текущее время сайта (SITETIME)<br>
<b>format</b> - Формат времени или даты. Необязательный параметр, по умолчанию - d.m.y / H:i<br><br>

<b>Примеры использования</b><br>

<pre class="prettyprint linenums">
echo dateFixed(time()); /* Сегодня / 22:00 */
echo dateFixed(time(), "j F Y / H:i"); /* 13 Ноября 2011 / 22:00 */

$mktime = mktime(0, 0, 0, 7, 1, 2010);
echo dateFixed($mktime); /* 01.07.10 / 00:00 */
</pre>
