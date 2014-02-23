<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/iblock.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/prolog.php");
IncludeModuleLangFile(__FILE__);

CUtil::JSPostUnescape();
/*
 * this page only for actions and get info
 *
 */
define('B_ADMIN_SUBELEMENTS',1);
define('B_ADMIN_SUBELEMENTS_LIST',true);

$bBizproc = CModule::IncludeModule("bizproc");
$bWorkflow = CModule::IncludeModule("workflow");

CFile::DisableJSFunction(true);

global $APPLICATION;

$strSubTMP_ID = intval($_REQUEST['TMP_ID']);

$strSubIBlockType = trim($type);

$arSubIBlockType = CIBlockType::GetByIDLang($strSubIBlockType, LANG);
if(false === $arSubIBlockType)
	$APPLICATION->AuthForm(GetMessage("IBLOCK_BAD_BLOCK_TYPE_ID"));

$intSubIBlockID = IntVal($IBLOCK_ID);
$strSubIBlockPerm = "D";

$arSubIBlock = CIBlock::GetArrayByID($intSubIBlockID);
if($arSubIBlock)
{
	$strSubIBlockPerm = CIBlock::GetPermission($intSubIBlockID);
	if($bWorkflow && $arSubIBlock["WORKFLOW"] != "N"
		|| $bBizproc && $arSubIBlock["BIZPROC"] != "N")
		$bBadBlock=($strSubIBlockPerm<"U");
	else
		$bBadBlock=($strSubIBlockPerm<"W");
}
else
	$bBadBlock = true;

if($bBadBlock)
{
	$APPLICATION->SetTitle($arSubIBlockType["NAME"]);
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

	if($bBadBlock):
	?>
	<?echo ShowError(GetMessage("IBLOCK_BAD_IBLOCK"));?>
	<a href="iblock_admin.php?lang=<?echo LANG?>&amp;type=<?echo htmlspecialchars($strSubIBlockType)?>"><?echo GetMessage("IBLOCK_BACK_TO_ADMIN")?></a>
	<?
	endif;
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
	die();
}

$arSubIBlock["SITE_ID"] = array();
$rsSites = CIBlock::GetSite($intSubIBlockID);
while($arSite = $rsSites->Fetch())
	$arSubIBlock["SITE_ID"] = $arSite["LID"];

$boolSubWorkFlow = $bWorkflow && (CIBlock::GetArrayByID($intSubIBlockID, "WORKFLOW") != "N");
$boolSubBizproc = $bBizproc && (CIBlock::GetArrayByID($intSubIBlockID, "BIZPROC") != "N");

$boolSubCatalog = false;
$bCatalog = CModule::IncludeModule("catalog");
if($bCatalog)
{
	$rs = CCatalog::GetList(array(),array("IBLOCK_ID"=>$arSubIBlock["ID"]));
	if(!($arCatalog = $rs->Fetch()))
	{
		require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
		require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
		die();
	}
	else
	{
		if(!$USER->CanDoOperation('catalog_read') && !$USER->CanDoOperation('catalog_price'))
		{
			require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
			require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
			die();
		}
	}
	$boolSubCatalog = true;
}

$arSubCatalog = CCatalog::GetByID($arSubIBlock["ID"]);

$intSubPropValue = intval($_REQUEST['find_el_property_'.$arSubCatalog['SKU_PROPERTY_ID']]);
if (0 >= $intSubPropValue)
{
	if ('' == $strSubTMP_ID)
	{
		require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
		require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
		die();
	}
}

//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
$strSubElementAjaxPath = '/bitrix/admin/iblock_subelement_admin.php?WF=Y&IBLOCK_ID='.$intSubIBlockID.'&type='.urlencode($strSubIBlockType).'&lang='.LANGUAGE_ID.'&find_section_section=0&find_el_property_'.$arSubCatalog['SKU_PROPERTY_ID'].'='.$intSubPropValue.'&TMP_ID='.urlencode($strSubTMP_ID);
require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/iblock/admin/templates/iblock_subelement_list.php');

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_popup_admin.php");
//require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>