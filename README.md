# developx.gcaptcha

Developx: модуль ГуглКаптчи v3

Установка:

1) Скачать и установить модуль из https://marketplace.1c-bitrix.ru/developx.gcaptcha

2) Получить ключ гугл каптчи https://www.google.com/recaptcha/admin/create

3) Заполнить настройки на странице /bitrix/admin/settings.php?lang=ru&mid=developx.gcaptcha

4) На странице формы подключить компонент каптчи 
<pre>
$APPLICATION->IncludeComponent("developx:gcaptcha", ".default", array(), false);
</pre>

5) В тег формы добавить класс .captchaFormJs
<form class="captchaFormJs"></form>

6) Перед добавлением данных формы добавить код проверку
<pre>
if (CModule::IncludeModule('developx.gcaptcha')){
    $captchaObj = new Developx\Gcaptcha\Main();
    if ($captchaObj->checkCaptcha(){
        //проверка пройдена
    }
}
</pre>