<?php show_title('Функция unlink_image'); ?>

Удаляет картинку, а также кэшированное изображение сохраненное в директории upload/thumbnail, с проверкой имеется ли картинка в данных директориях (Доступно с версии 2.6.0)<br /><br />

<pre class="d">
<b>unlink_image</b>(
	string dir,
	string image
);
</pre><br />

<b>Параметры функции</b><br />

<b>dir</b> - Директория с изображением<br />
<b>image</b> - Имя файла с изображением<br /><br />


<b>Примеры использования</b><br />

<?php
echo App::bbCode(check('[code]<?php
unlink_image("upload/photos/", "Vantuz.gif");
/* Удаляет файл Vantuz.gif сначала в директории upload/photos, а затем в upload/thumbnail */
?>[/code]'));
?>

<br />
<i class="fa fa-arrow-circle-left"></i> <a href="/files/docs">Вернуться</a><br />
