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

4) Добавить компонент каптчи в шапку или подвал сайта.

Пример: 
<pre>
    < ?$APPLICATION->IncludeComponent("developx:gcaptcha", ".default", array(), false);?>
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

6) Для форм регистрации, восстановления пароля нужно добавить обработчик в файл init.php

AddEventHandler("main", "OnBeforeUserRegister", "checkCaptchaV3");
AddEventHandler("main", "OnBeforeUserSendPassword", "checkCaptchaV3");

function checkCaptchaV3()
{
    if (CModule::IncludeModule('developx.gcaptcha')){
        $captchaObj = new Developx\Gcaptcha\Main();
        if ($captchaObj->checkCaptcha()){

        } else {
            $GLOBALS['APPLICATION']->ThrowException('Ошибка проверка каптчи');
            return false;
        }
    }
}

Изменения в версии 2.0

1) Упрощена установка модуля. Теперь не нужно добавлять ее в каждую форму. А достаточно добавить ее один раз шапку или подвал сайта
2) Добавлена поддержка композита
3) Переделан запрос к серверу google на curl
4) Отключена проверка для авторизированных пользователей
5) Отключена повторная проверка. Если проверка была пройдена успешно, то в рамках сессии каптча будет отключена
6) Поддержка обычных (не ajax) форм
7) Добавлен счетчик успешных / не успешных проверок
8) В логи добавлена информация о ip, с которых поступают запросы
