<h2 class="item1">Настройки скрипта</h2>

<h5>
    Скрипт поддерживает следующие опции для настройки
</h5>

<h3>
    Общие настройки
</h3>

<p>
    Общие настройки скрипта определяются в объекте options при инициализации.
</p>

<h5>accessToken</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>

<p>
    Маркер доступа для Instagram API.
</p>

<h5>clientId</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>

<p>
    <strong>Обязательная опция. Идентификатор клиента, полученный с Instagram </strong>
</p>

<h5>get</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>
<dl class="inline-term">
    <dt>Значение по-умолчанию</dt>
    <dd>user</dd>
</dl>

<p>
    Определяет, по какому признаку получать данные с Instagram. Доступно 5 вариантов:
</p>

<dl class="inline-term">
    <dt>popular</dt>
    <dd>изображения с популярных страниц;</dd>
</dl>
<dl class="inline-term">
    <dt>tagged</dt>
    <dd>изображения, которые соотвествуют определенному тегу. Используется с опцией <strong>tagName</strong></dd>
</dl>
<dl class="inline-term">
    <dt>location</dt>
    <dd>location - изображения, которые соотвествуют определенному местоположению. Используется с опцией <strong>locationId</strong>;</dd>
</dl>
<dl class="inline-term">
    <dt>user</dt>
    <dd>изображения, которые соотвествуют определенному пользователю. Используется с опцией <strong>userId</strong>;</dd>
</dl>
<dl class="inline-term">
    <dt>profile</dt>
    <dd>информация о пользователе. Используется с опцией <strong>userId</strong>.</dd>
</dl>

<h5>tagName</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>
<dl class="inline-term">
    <dt>Значение по-умолчанию</dt>
    <dd>awesome</dd>
</dl>

<p>
    Название тега для получения изображений. Используется с <strong>get: 'tagged'</strong>.
</p>

<h5>locationId</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>

<p>
    Уникальный идентификатор локации для получения изображений. Используется с <strong>get: 'location'</strong>
</p>

<h5>userId </h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>

<p>
    Уникальный идентификатор пользователя для получения изображений. Используется с <strong>get: 'user'</strong>
</p>

<h5>sortBy</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>
<dl class="inline-term">
    <dt>Значение по-умолчанию</dt>
    <dd>most-recent</dd>
</dl>

<p>
    Определяет сортировку изображаений по указанным параметрам. Доступны следующие значения:
</p>


<dl class="inline-term">
    <dt>none</dt>
    <dd>отсутсвует</dd>
</dl>
<dl class="inline-term">
    <dt>most-recent</dt>
    <dd>от новых к старым;</dd>
</dl>
<dl class="inline-term">
    <dt>least-recent</dt>
    <dd> от старых к новым;</dd>
</dl>
<dl class="inline-term">
    <dt>most-liked;</dt>
    <dd>количество лайков по убыванию;</dd>
</dl>
<dl class="inline-term">
    <dt>least-liked</dt>
    <dd>количество лайков по возрастанию;</dd>
</dl>
<dl class="inline-term">
    <dt>most-commented</dt>
    <dd>количество комментариев по убыванию;</dd>
</dl>
<dl class="inline-term">
    <dt>least-commented</dt>
    <dd>количество комментариев по возрастанию.</dd>
</dl>

<h5>showLog</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>
<dl class="inline-term">
    <dt>Значения</dt>
    <dd>true, false</dd>
</dl>

<p>
    Определяет, выводить полученные информацию с Instagram в консоль или нет (для формирования правильных data
    атрибутов).
</p>

<h5>dateFormat</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>


<p>
    Составной обьект для отображения даты добавления изображения. Состоит из следующих вложенных опций:
</p>


<div class="inner">
    <h5>seconds</h5>
    <dl class="inline-term">
        <dt>Тип</dt>
        <dd>String</dd>
    </dl>
    <dl class="inline-term">
        <dt>Значение по-умолчанию</dt>
        <dd>less than a minute ago</dd>
    </dl>

    <p>
        Текст, выводимый вместо даты, если изображение добавлено меньше минуты назад.
    </p>

    <h5>minute</h5>
    <dl class="inline-term">
        <dt>Тип</dt>
        <dd>String</dd>
    </dl>
    <dl class="inline-term">
        <dt>Значение по-умолчанию</dt>
        <dd>about a minute ago</dd>
    </dl>

    <p>
        Текст, выводимый вместо даты, если изображение добавлено минуту назад.
    </p>

    <h5>minutes</h5>
    <dl class="inline-term">
        <dt>Тип</dt>
        <dd>String</dd>
    </dl>
    <dl class="inline-term">
        <dt>Значение по-умолчанию</dt>
        <dd> minutes ago</dd>
    </dl>

    <p>
        Текст, выводимый возле количества минут, с момента добавления твита (5 minutes ago).
    </p>

    <h5>hour</h5>
    <dl class="inline-term">
        <dt>Тип</dt>
        <dd>String</dd>
    </dl>
    <dl class="inline-term">
        <dt>Значение по-умолчанию</dt>
        <dd>about an hour ago</dd>
    </dl>

    <p>
        Текст, выводимый вместо даты, если изображение добавлено час назад.
    </p>


    <h5>hours</h5>
    <dl class="inline-term">
        <dt>Тип</dt>
        <dd>String</dd>
    </dl>
    <dl class="inline-term">
        <dt>Значение по-умолчанию</dt>
        <dd> hours ago</dd>
    </dl>

    <p>
        Текст, выводимый возле количества часов, с момента добавления твита (5 hours ago).
    </p>

    <h5>day</h5>
    <dl class="inline-term">
        <dt>Тип</dt>
        <dd>String</dd>
    </dl>
    <dl class="inline-term">
        <dt>Значение по-умолчанию</dt>
        <dd>1 day ago</dd>
    </dl>

    <p>
        Текст, выводимый вместо даты, если изображение было добавлено вчера.
    </p>

    <h5>days</h5>
    <dl class="inline-term">
        <dt>Тип</dt>
        <dd>String</dd>
    </dl>
    <dl class="inline-term">
        <dt>Значение по-умолчанию</dt>
        <dd>%b/%d/%Y</dd>
    </dl>

    <p>
        Формат вывод даты. Дата будет отображаться в данном формате, по истечении 2-х дней после добавления.
    </p>
</div>

<h5>before</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>function</dd>
</dl>

<p>
    Функция, которая будет выполнена до получения данных с Istagram
</p>

<h5>after</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>function</dd>
</dl>

<p>
    Функция, которая будет выполнена после выполнения скрипта.
</p>


<h3>
    Настройки с помощью data атрибутов
</h3>

<p>
    Скрипт также поддерживает дополнительную настройку  в HTML разметке с помощью data-атрибут API.
</p>

<h5>data-instafeed-clientid</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>

<p>
    <strong>Обязательная опция.</strong> Идентификатор клиента, полученный с Instagram.
</p>

<h5>data-instafeed-get</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>
<dl class="inline-term">
    <dt>Значение по-умолчанию</dt>
    <dd>user</dd>
</dl>

<p>
    Определяет, по какому признаку получать данные с Instagram. Доступно 5 вариантов:
</p>

<h5>data-instafeed-user</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>

<p>
    Уникальный идентификатор пользователя для получения изображений. Используется с <strong>get: 'user'</strong>
</p>


<h5>data-instafeed-tagname</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>
<dl class="inline-term">
    <dt>Значение по-умолчанию</dt>
    <dd>awesome</dd>
</dl>

<p>
    Название тега для получения изображений. Используется с <strong>get: 'tagged'</strong>.
</p>

<h5>data-instafeed-location</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>

<p>
    Уникальный идентификатор локации для получения изображений. Используется с <strong>get: 'location'</strong>
</p>

<h5>data-show</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>

<p>
    Уникальный идентификатор локации для получения изображений. Используется с <strong>get: 'location'</strong>
</p>




<h5>data-instafeed-sort</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>
<dl class="inline-term">
    <dt>Значение по-умолчанию</dt>
    <dd>most-recent</dd>
</dl>

<p>
    Определяет сортировку изображаений по указанным параметрам.
</p>

<h5>data-instafeed-date-format</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>
<dl class="inline-term">
    <dt>Значение по-умолчанию</dt>
    <dd>%b/%d/%Y</dd>
</dl>

<p>
    Формат вывод даты. Дата будет отображаться в данном формате, по истечении 2-х дней после добавления.
</p>

<h5>data-instafeed-showlog</h5>
<dl class="inline-term">
    <dt>Тип</dt>
    <dd>String</dd>
</dl>
<dl class="inline-term">
    <dt>Значения</dt>
    <dd>true, false</dd>
</dl>

<p>
    Определяет, выводить полученные информацию с Instagram в консоль или нет (для формирования правильных data
    атрибутов).
</p>

