<h2 class="item1">Интеграция с Require.js</h2>

<h5>
    Скрипт имеет встроенную поддержку AMD экспорта для интеграции с Require.js. Весь процесс интеграции все также
    сводится к нескольким простым шагам.
</h5>

<h3>
    Скачайте скрипт из Git'a
</h3>

<p>
    Для начала необходимо скачать данный скрипт из нашего публичного репозитория:
    <a href="http://products.git.devoffice.com/coding-development/rd-instafeed.git">Кликабельно</a>
</p>



<h3>
    Добавьте необходимую разметку
</h3>

<p>
    HTML разметка по умолчанию для создания получения 1-го изображения с сервиса  выглядит следующим образом.
</p>

<code>
<pre>
&lt;!-- RD Instafeed --&gt;
&lt;section class="instafeed" data-instafeed-get="user" data-instafeed-user="1507096244"&gt;
  &lt;div data-instafeed-item&gt;
    &lt;img src="images/_blank.png" alt="" data-images-low_resolution-url="src" /&gt;
  &lt;/div&gt;
&lt;/section&gt;
&lt;!-- END RD Instafeed--&gt;
</pre>
</code>

<p>
    <strong>Обратите внимание:</strong> разметка внутри данного блока может быть произвольной, включая элементы сетки и т.д. Необходимо только наличие элемента с атрибутом data-instafeed-item.
</p>

<h3>
    Обновите конфигурацию require.js
</h3>

<p>
    Прежде всего вам нобходимо убедиться в правильности настройки конфигурации путей в require.js. Обязательно необходимо
    определить алиасы jquery и jquery.rd-instafeed. В большинстве случаев, данная конфигурация определяется в главном скрипте
    приложения, путь к которому определяется в дата атрибуте data-main при подключении require.js
</p>

<code>
<pre>
&lt;script data-main="js/main" src="js/require.js"&gt;&lt;/script&gt;
</pre>
</code>

<p>
    Сама конфигурация должна содержать следующие алиасы для путей
</p>

<code>
<pre>
requirejs.config({
  paths: {
    "jquery": "path/to/jquery"
    "jquery.rd-instafeed": "path/to/jquery.rd-instafeed"
  }
});
</pre>
</code>

<h3>
    Выполните инициализацию скрипта
</h3>

<p>
    Для инициализации скрипта достаточно воспользоваться следующим кодом.
</p>

<code>
<pre>
requirejs(["jquery", "jquery.rd-instafeed"], function($, instafeed) {
  var o = $(".rd-instafeed");
  o.RDInstafeed();
});
</pre>
</code>

