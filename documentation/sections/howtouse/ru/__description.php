<h2 class="item1">Как использовать</h2>

<h5>
    Внедрение скрипта на страницу сводится к нескольким простым шагам.
</h5>

<p>
    <strong>Обратите внимание:</strong> предложенный вариант инициализации может отличаться в зависимости от продукта,
    в котором он внедряется. Информация предоставленная ниже лишь отображает принципы работы со скриптом.
</p>

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
    HTML разметка по умолчанию для создания получения одного изображения с сервиса  выглядит следующим образом.
</p>

<code>
<pre>
&lt;!-- RD Instafeed --&gt;
&lt;section class="instafeed" data-instafeed-clientid="44f19408f04040bd85214315861a84a1" data-instafeed-get="user" data-instafeed-user="1507096244"&gt;
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
    Подключите скрипт на странице
</h3>

<p>
    Вам необходимо скопировать скрипт в папку /js вашего проекта и выполнить его подключение на странице. Для это можно
    использовать следующий участок кода:
</p>

<code>
<pre>
&lt;script src="js/jquery.rd-instafeed.min.js"&gt;&lt;/script&gt;
</pre>
</code>


<h3>
    Выполните инициализацию скрипта
</h3>

<p>
    Вам необходимо выполнить инициализацию скрипта для элементов по целевому селектору, с помощью следующего участка кода
</p>

<code>
<pre>
&lt;script&gt;
  $(document).ready(function () {
    o.RDInstafeed({}); // Additional options
  });
&lt;/script&gt;
</pre>
</code>


<h3>
    HTML разметка элемента инстаграма
</h3>

<p>
    Получить данные о изображении возможно только внутри блока с атрибутом data-instafeed-item.
    Для получения данных необходимо дописать следующий атрибут: <br/>
    <span style="display: block; text-align:center;">data-(путь к данным)="target"</span> <br/>
    где target - HTML атрибут, в который будут записаны данные. Если в target указать значение “text”, данные будут выведены
    внутрь тега обычным текстом. В target можно записать несколько значений, определив их через запятую.
    Instagram API предоставляет большое количество данных о элементе, которые мы можем получить с помощью data-атрибутов.
    Включив опцию скрипта data-showlog="true", в консоль будет выведено массив обьектов инстаграма, откуда вы и сможете
    получить "путь к данным" для вывода необходимого элемента. Например, обьект полученный с инстаграмма содержит обьект
    images, который в свою очередь содержит 3 возможных разрешения картинки(low_resolution, standard_resolution, thumbnail),
    каждая из которых имеет 3 параметра(height, width, url). Теперь для получения картинки необходимо добавить data аттрибут,
    учитывая всю пройденную вложенность, разделяя каждый вложенный элемент знаком "-": data-images-low_resolution-url
</p>

<code>
<pre>
&lt;div data-instafeed-item&gt;
    &lt;img src="images/_blank.png" alt="" data-images-low_resolution-url="src" /&gt;
&lt;/div&gt;
</pre>
</code>

<h4>Список атрибутов для часто используемых элементов:</h4>
<ul class="marked-list">
    <li>
        <dl class="inline-term">
            <dt>data-images-low_resolution-url</dt>
            <dd>ссылка на изображение с разрешением 320х320</dd>
        </dl>
    </li>
    <li>
        <dl class="inline-term">
            <dt>data-images-standard_resolution-url</dt>
            <dd>ссылка на изображение с разрешением 640х640</dd>
        </dl>
    </li>
    <li>
        <dl class="inline-term">
            <dt>data-images-thumbnail-url</dt>
            <dd>ссылка на изображение с разрешением 150x150</dd>
        </dl>
    </li>
    <li>
        <dl class="inline-term">
            <dt>data-created_time</dt>
            <dd>дата добавления</dd>
        </dl>
    </li>
    <li>
        <dl class="inline-term">
            <dt>data-tags_full</dt>
            <dd>список тегов разделенных пробелом</dd>
        </dl>
    </li>
    <li>
        <dl class="inline-term">
            <dt>data-link</dt>
            <dd>ссылка на пост в инстаграме</dd>
        </dl>
    </li>
    <li>
        <dl class="inline-term">
            <dt>data-caption-text</dt>
            <dd>описание изображения</dd>
        </dl>
    </li>
    <li>
        <dl class="inline-term">
            <dt>data-likes-count</dt>
            <dd>количество лайков</dd>
        </dl>
    </li>
    <li>
        <dl class="inline-term">
            <dt>data-comments-count</dt>
            <dd>количество комментариев</dd>
        </dl>
    </li>
</ul>

<h3>
    HTML разметка для получения дополнительной информации
</h3>

<p>
    Для вывода дополнительной информации о комментарии, лайке или локации необходимо добавить блок с data аттрибутом
    data-instafeed-comment, data-instafeed-like, data-instafeed-location соответсвенно, и уже внутри данных блоков
    выводить требуемую информацию. Например, для вывода текста комментария необходима следующая разметка:
</p>


<code>
<pre>
&lt;div data-instafeed-item&gt;
    &lt;div data-instafeed-comment&gt;
        &lt;div data-comments-data-text="text"&gt;&lt;/div&gt;
    &lt;/div&gt;
&lt;/div&gt;
</pre>
</code>



