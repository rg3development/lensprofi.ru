<?
define("STOP_STATISTICS", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER;

$arResult = array(
	'STATUS' => 'OK',
	'MESSAGE' => '',
	'RESULT' => '',
);
$boolFlag = true;

IncludeModuleLangFile(__FILE__);
if (true == $boolFlag)
{
	if (!$USER->IsAuthorized())
	{
		$arResult['STATUS'] = 'ERROR';
		$arResult['MESSAGE'] = GetMessage('BT_CAT_TOOLS_GEN_CPN_ERR_AUTH');
		$boolFlag = false;
	}
}

if (true == $boolFlag)
{
	if (!check_bitrix_sessid())
	{
		$arResult['STATUS'] = 'ERROR';
		$arResult['MESSAGE'] = GetMessage('BT_CAT_TOOLS_GEN_CPN_ERR_SESSION');
		$boolFlag = false;
	}
}
if (true == $boolFlag)
{
	if (!$USER->CanDoOperation('catalog_discount'))
	{
		$arResult['STATUS'] = 'ERROR';
		$arResult['MESSAGE'] = GetMessage('BT_CAT_TOOLS_GEN_CPN_ERR_RIGHTS');
		$boolFlag = false;
	}
}

if (true == $boolFlag)
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/include.php");
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/catalog/prolog.php");

	$arResult['RESULT'] = CatalogGenerateCoupon();
}

echo CUtil::PhpToJSObject($arResult);
?>