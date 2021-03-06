Модуль google каптчи reCAPTCHA v3 для 1С-Битрикс. Защитит вашу форму обратной связи от спама.
﻿
Новейшая разработка учитывает движение курсора мыши, а также использует множество других методов идентификации
реального пользователя, вроде набора текста в браузере. Точность определения – 99,98%.
Поддерживает настройку чувствительности и логирование неуспешных заявок.
﻿
Возможно подключение одновременно к нескольким формам на одной странице.

<h2>Установка:</h2>
1) Скачать и установить модуль из MarketPlace Битрикс https://marketplace.1c-bitrix.ru/developx.gcaptcha

2) Получить ключ каптчи на странице https://www.google.com/recaptcha/admin/create

3) Заполнить настройки на вашем сайте /bitrix/admin/settings.php?lang=ru&mid=developx.gcaptcha

4) Добавить на сайт компонент каптчи

Пример: 
```
<? $APPLICATION->IncludeComponent("developx:gcaptcha", ".default", array(), false); ?>
```

5) Перед добавлением данных формы добавить код проверки

Пример: 
```php
if (CModule::IncludeModule('developx.gcaptcha')){
    $captchaObj = new Developx\Gcaptcha\Main();
    if ($captchaObj->checkCaptcha(){
        //проверка пройдена
    }
}
```


6) Для форм регистрации, восстановления пароля нужно добавить обработчик в файл init.php

Пример: 
```php
AddEventHandler("main", "OnBeforeUserRegister", "checkCaptchaV3"); //для формы регистрации
AddEventHandler("main", "OnBeforeUserSendPassword", "checkCaptchaV3"); //для восставновления пароля

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
```

<h2>Логирование</h2>

В случае, если проверка не будет пройдена (сервер гугл вернет score меньше, чем заданная чувствительность), в лог добавится запись о не пройденной проверке (при отмеченной опции "Логировать ошибки каптчи").
В этой записи будет время ошибки, а также 2 массива:
- 1й - это $_REQUEST, для понимания того, какую информацию пытались отправить
- 2й - ответ сервера гугл, в котором есть score


<h2>Score (чувствительность каптчи)</h2>

Score может быть в пределах от 0.0 до 1.0, где:
- 0.0 означает, что это вероятнее всего робот
- 1.0 будет означать, что это скорее всего человек

Score рекомендуется устанавливать = 0.5

Страница модуля: https://developx.ru/bitrix-modules/gcaptcha/

Изменения в версии 2.0

1) Упрощена установка модуля. Теперь не нужно добавлять ее в каждую форму. А достаточно добавить ее один раз шапку или подвал сайта
2) Добавлена поддержка композита
3) Переделан запрос к серверу google на curl
4) Отключена проверка для авторизированных пользователей
5) Отключена повторная проверка. Если проверка была пройдена успешно, то в рамках сессии каптча будет отключена
6) Поддержка обычных (не ajax) форм
7) Добавлен счетчик успешных / не успешных проверок
8) В логи добавлена информация о ip, с которых поступают запросы