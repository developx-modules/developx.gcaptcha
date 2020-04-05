Модуль google каптчи reCAPTCHA v3 для 1С-Битрикс. Защитит вашу форму обратной связи от спама.
﻿
Новейшая разработка учитывает движение курсора мыши, а также использует множество других методов идентификации
реального пользователя, вроде набора текста в браузере. Точность определения – 99,98%.
Поддерживает настройку чувствительности и логирование неуспешных заявок.
﻿
Возможно подключение одновременно к нескольким формам на одной странице.

Установка:
1) Скачать и установить модуль из MarketPlace Битрикс https://marketplace.1c-bitrix.ru/developx.gcaptcha

2) Получить ключ каптчи на странице https://www.google.com/recaptcha/admin/create

3) Заполнить настройки на вашем сайте /bitrix/admin/settings.php?lang=ru&mid=developx.gcaptcha

4) Добавить в блок формы компонент каптчи

Пример: 
<pre>
    < form>
        //поля формы
        <?$APPLICATION->IncludeComponent("developx:gcaptcha", ".default", array(), false);?>
    < /form>
</pre>

5) Перед добавлением данных формы добавить код проверки

Пример: 
<pre>
if (CModule::IncludeModule('developx.gcaptcha')){
    $captchaObj = new Developx\Gcaptcha\Main();
    if ($captchaObj->checkCaptcha(){
        //проверка пройдена
    }
}
</pre>