# developx.gcaptcha

Developx: модуль гугл каптчи 3.0

Установка:
1) Скачать и установить модуль из https://marketplace.1c-bitrix.ru/

2) Получить ключ гугл каптчи https://www.google.com/recaptcha/admin/create

3) Заполнить настройки на странице /bitrix/admin/settings.php?lang=ru&mid=developx.gcaptcha

4) На странице формы подключить компонент каптчи 
<? $APPLICATION->IncludeComponent("developx:gcaptcha", ".default", array(), false); ?>

5) В тег формы добавить класс .captchaFormJs
<form class="captchaFormJs"></form>

6) Перед добавлением данных формы добавить код проверку
if (CModule::IncludeModule('developx.gcaptcha')){
    $captchaObj = new Developx\Gcaptcha\Main();
    if ($captchaObj->checkCaptcha(){
        //проверка пройдена
    }
}