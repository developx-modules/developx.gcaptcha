<?
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

global $USER;
if ($USER->IsAdmin()):
    $moduleName = 'developx.gcaptcha';
    Loader::includeModule($moduleName);
    $moduleObj = Developx\Gcaptcha\Options::getInstance();

    Loc::loadMessages(__FILE__);

    $arOptions = $moduleObj->getOptions();

    if ($_POST['Update'] && check_bitrix_sessid()) {
        foreach ($arOptions as $code => $prop) {
            $moduleObj->setOption($code, $_POST[$code]);
        }
        $moduleObj->clearCache();
        LocalRedirect($_SERVER['REQUEST_URI']);
        die();
    }

    $aTabs = array(
        array("DIV" => "edit1", "TAB" => Loc::getMessage('DX_CPT_OPT_T1'), "ICON" => "main_user_edit", "TITLE" => Loc::getMessage('DX_CPT_OPT_T1')),
        array("DIV" => "edit2", "TAB" => Loc::getMessage('DX_CPT_OPT_T2'), "ICON" => "main_user_edit", "TITLE" => Loc::getMessage('DX_CPT_OPT_T2')),
    );
    $tabControl = new CAdminTabControl("tabControl", $aTabs);

    $tabControl->Begin(); ?>
    <form name="developx_filter_gcaptcha" method="POST"
          action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialcharsbx($mid) ?>&amp;lang=<? echo LANG ?>&amp;mid_menu=1">
        <?= bitrix_sessid_post(); ?>
        <? $tabControl->BeginNextTab(); ?>
        <tr class="heading">
            <td colspan="2"><?=Loc::getMessage('DX_CPT_OPT_T1')?></td>
        </tr>
        <a href="https://www.google.com/recaptcha/admin/create" target="_blank" rel="nofollow">�������� ��� ���� ������</a>
        <? foreach ($arOptions as $code => $props) {
            $moduleObj->showHtmlOption($code, Loc::getMessage('DX_CPT_OPT_' . $code));
        } ?>
        <? $tabControl->BeginNextTab(); ?>
        <tr class="heading">
            <td colspan="2"><?=Loc::getMessage('DX_CPT_OPT_T2')?></td>
        </tr>
        <tr class="heading">
            <td colspan="2">
                <textarea style="width: 100%;height: 500px;">
                    <?
                    $mainObj = new Developx\Gcaptcha\Main();
                    echo $mainObj->getLogData();
                    ?>
                </textarea>
            </td>
        </tr>
        <? $tabControl->Buttons(); ?>
        <input type="submit" name="Update" value="<?= GetMessage("MAIN_SAVE") ?>">
        <? $tabControl->End(); ?>
    </form>
<? endif; ?>