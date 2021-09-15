<?php

use Bitrix\Main\Config\Option;

/**
 * @var string $REQUEST_METHOD
 * @var string $Update
 */

defined('B_PROLOG_INCLUDED') or die;

define('ADMIN_MODULE_NAME', 'saa.pict_optimize');

$module_id = ADMIN_MODULE_NAME;
$install_status = CModule::IncludeModuleEx($module_id);

$RIGHT = $APPLICATION->GetGroupRight($module_id);
$RIGHT_W = ($RIGHT >= 'W');
$RIGHT_R = ($RIGHT >= 'R');

$errors = [];

if ($RIGHT_R) {
    if ($REQUEST_METHOD == 'POST'&& $RIGHT_W && check_bitrix_sessid()) {
		# сохраняем
		Option::set($module_id, 'enabled', $_POST['enabled']?$_POST['enabled']:'0');
        Option::set($module_id, 'logging', $_POST['logging']?$_POST['logging']:'0');

		$ex = $APPLICATION->GetException();
		if ($ex) {
			CAdminMessage::ShowOldStyleError($ex->GetString());
		} else {
			CAdminMessage::ShowNote('Настройки сохранены');
		}

    }


    $currentOptions = Option::getForModule($module_id);
	$enabled = $currentOptions['enabled'] == 1;
    $logging = $currentOptions['logging'] == 1;

    $aTabs = [
        ['DIV' => 'edit1', 'TAB' => 'Настройки', 'ICON' => '', 'TITLE' => 'Настройки'],
        ['DIV' => 'edit3', 'TAB' => 'Доступ', 'ICON' => '', 'TITLE' => 'Доступ'],
    ];

    $tabControl = new CAdminTabControl('tabControl', $aTabs);
    $tabControl->Begin();
    ?>

	<form method="post" action="<? echo $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialchars($mid) ?>&lang=<?= LANGUAGE_ID ?>">
        <?= bitrix_sessid_post() ?>
        	<? $tabControl->BeginNextTab(); ?>

			<tr>
				<td><label for="is_enabled">Включено:</label></td>
				<td>
					<input type="checkbox" id="is_enabled" name="enabled" value="1"<?= $enabled ? ' checked' : '' ?>>
				</td>
			</tr>
            <tr>
                <td><label for="is_logging">Логирование:</label></td>
                <td>
                    <input type="checkbox" id="is_logging" name="logging" value="1"<?= $logging ? ' checked' : '' ?>>
                </td>
            </tr>
			<tr>
				<td>
                <?php
                $config = \Saa\Pictoptimizer\ModuleControl::getCurrentConfiguration();
                if(!empty($config['optimizer'])){
                    echo '<b>текущие оптимизаторы:</b>';
                    foreach($config['optimizer'] as $type=>$toolName){
                        echo '<p>'.$type.': '.$toolName.'</p>';
                    }
                }
                ?>
				<input type="submit" class="adm-btn-save" name="save" value="Сохранить">
				</td>
                <td></td>
			</tr>
        <? $tabControl->End(); ?>
	</form>
    <?php
}
