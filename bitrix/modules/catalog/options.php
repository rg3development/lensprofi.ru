<?
$module_id = "catalog";
//$CAT_RIGHT = $APPLICATION->GetGroupRight($module_id);
//if ($CAT_RIGHT >= "R") :

define('CATALOG_NEW_OFFERS_IBLOCK_NEED','-1');

if ($USER->CanDoOperation('catalog_read') || $USER->CanDoOperation('catalog_settings')) :

$bReadOnly = !$USER->CanDoOperation('catalog_settings');

global $MESS;
include(GetLangFileName($GLOBALS["DOCUMENT_ROOT"]."/bitrix/modules/main/lang/", "/options.php"));
include(GetLangFileName($GLOBALS["DOCUMENT_ROOT"]."/bitrix/modules/catalog/lang/", "/options.php"));

include_once($GLOBALS["DOCUMENT_ROOT"]."/bitrix/modules/catalog/include.php");

if ($ex = $APPLICATION->GetException())
{
	require($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");

	$strError = $ex->GetString();
	ShowError($strError);

	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
	die();
}

if ($REQUEST_METHOD=="GET" && strlen($RestoreDefaults)>0 && !$bReadOnly /*$CAT_RIGHT=="W"*/ && check_bitrix_sessid())
{
	if (!$USER->IsAdmin())
		$strValTmp = COption::GetOptionString("catalog", "avail_content_groups");

	COption::RemoveOption("catalog");
	$z = CGroup::GetList($v1="id",$v2="asc", array("ACTIVE" => "Y", "ADMIN" => "N"));
	while($zr = $z->Fetch())
		$APPLICATION->DelGroupRight($module_id, array($zr["ID"]));

	if (!$USER->IsAdmin())
		COption::SetOptionString("catalog", "avail_content_groups", $strValTmp);
}

$arAllOptions =
	array(
		array("export_default_path", GetMessage("CAT_EXPORT_DEFAULT_PATH"), "/bitrix/catalog_export/", array("text", 30)),
		array("default_catalog_1c", GetMessage("CAT_DEF_IBLOCK"), "", array("text", 30)),
		array("deactivate_1c_no_price", GetMessage("CAT_DEACT_NOPRICE"), "N", array("checkbox")),
		array("yandex_xml_period", GetMessage("CAT_YANDEX_XML_PERIOD"), "24", array("text", 5)),
	);

$strWarning = "";
$strOK = "";
if ($REQUEST_METHOD=="POST" && strlen($Update)>0 && !$bReadOnly /*$CAT_RIGHT=="W"*/ && check_bitrix_sessid())
{
	for ($i=0, $cnt = count($arAllOptions); $i < $cnt; $i++)
	{
		$name = $arAllOptions[$i][0];
		$val = $$name;
		if ($arAllOptions[$i][3][0]=="checkbox" && $val!="Y")
			$val = "N";
		if ($name == 'export_default_path')
		{
			if (substr($val, -1) != '/') $val .= '/';
			if (!file_exists($_SERVER['DOCUMENT_ROOT'].$val) || !is_dir($_SERVER['DOCUMENT_ROOT'].$val))
			{
				$strWarning .= GetMessage('CAT_PATH_ERR_EXPORT_FOLDER_BAD').'<br />';
				$val = '/bitrix/catalog_export/';
			}
		}
		COption::SetOptionString("catalog", $name, $val, $arAllOptions[$i][1]);
	}

	if ($default_outfile_action!="D" && $default_outfile_action!="H" && $default_outfile_action!="F")
	{
		$default_outfile_action = "D";
	}
	COption::SetOptionString("catalog", "default_outfile_action", $default_outfile_action, "");

	$strYandexAgent = '';
	$strYandexAgent = trim($_POST['yandex_agent_file']);
	if (!empty($strYandexAgent))
	{
		if (!file_exists($_SERVER['DOCUMENT_ROOT'].$strYandexAgent) || !is_file($_SERVER['DOCUMENT_ROOT'].$strYandexAgent))
		{
			$strWarning .= GetMessage('CAT_PATH_ERR_YANDEX_AGENT').'<br />';
			$strYandexAgent = '';
		}
	}
	COption::SetOptionString('catalog','yandex_agent_file', $strYandexAgent,GetMessage("CAT_AGENT_FILE"));

	$strAllowedProductFields = "";
	for ($i = 0; $i < count($allowed_product_fields); $i++)
	{
		$allowed_product_fields[$i] = Trim($allowed_product_fields[$i]);
		if (strlen($allowed_product_fields[$i])>0)
		{
			if (strlen($strAllowedProductFields)>0) $strAllowedProductFields .= ",";
			$strAllowedProductFields .= $allowed_product_fields[$i];
		}
	}
	COption::SetOptionString("catalog", "allowed_product_fields", $strAllowedProductFields);

	$strAllowedPriceFields = "";
	for ($i = 0; $i < count($allowed_price_fields); $i++)
	{
		$allowed_price_fields[$i] = Trim($allowed_price_fields[$i]);
		if (strlen($allowed_price_fields[$i]) > 0)
		{
			if (strlen($strAllowedPriceFields) > 0)
				$strAllowedPriceFields .= ",";
			$strAllowedPriceFields .= $allowed_price_fields[$i];
		}
	}
	COption::SetOptionString("catalog", "allowed_price_fields", $strAllowedPriceFields);

	COption::SetOptionString("catalog", "num_catalog_levels", IntVal($num_catalog_levels));

	$strAllowedGroupFields = "";
	for ($i = 0; $i < count($allowed_group_fields); $i++)
	{
		$allowed_group_fields[$i] = Trim($allowed_group_fields[$i]);
		if (strlen($allowed_group_fields[$i])>0)
		{
			if (strlen($strAllowedGroupFields)>0) $strAllowedGroupFields .= ",";
			$strAllowedGroupFields .= $allowed_group_fields[$i];
		}
	}
	COption::SetOptionString("catalog", "allowed_group_fields", $strAllowedGroupFields);

	$strAllowedCurrencies = "";
	for ($i = 0; $i < count($allowed_currencies); $i++)
	{
		$allowed_currencies[$i] = Trim($allowed_currencies[$i]);
		if (strlen($allowed_currencies[$i])>0)
		{
			if (strlen($strAllowedCurrencies)>0) $strAllowedCurrencies .= ",";
			$strAllowedCurrencies .= $allowed_currencies[$i];
		}
	}
	COption::SetOptionString("catalog", "allowed_currencies", $strAllowedCurrencies);

	if ($USER->IsAdmin())
	{
		$arOldAvailContentGroups = array();
		$oldAvailContentGroups = COption::GetOptionString("catalog", "avail_content_groups");
		if (strlen($oldAvailContentGroups) > 0)
			$arOldAvailContentGroups = explode(",", $oldAvailContentGroups);

		$availContentGroups = "";
		if (isset($AVAIL_CONTENT_GROUPS) && is_array($AVAIL_CONTENT_GROUPS))
		{
			for ($i = 0; $i < count($AVAIL_CONTENT_GROUPS); $i++)
			{
				$AVAIL_CONTENT_GROUPS[$i] = IntVal($AVAIL_CONTENT_GROUPS[$i]);
				if ($AVAIL_CONTENT_GROUPS[$i] > 0)
				{
					if (strlen($availContentGroups) > 0)
						$availContentGroups .= ",";

					$availContentGroups .= $AVAIL_CONTENT_GROUPS[$i];

					if (in_array($AVAIL_CONTENT_GROUPS[$i], $arOldAvailContentGroups))
					{
						$ind = array_search($AVAIL_CONTENT_GROUPS[$i], $arOldAvailContentGroups);
						unset($arOldAvailContentGroups[$ind]);
					}
				}
			}
		}

		foreach ($arOldAvailContentGroups as $key => $value)
			CCatalogProductGroups::DeleteByGroup($value);

		COption::SetOptionString("catalog", "avail_content_groups", $availContentGroups);
	}

	$strSaveProductWithoutPrice = (!empty($_REQUEST['save_product_without_price']) && $_REQUEST['save_product_without_price'] == 'Y' ? 'Y' : 'N');
	COption::SetOptionString('catalog','save_product_without_price',$strSaveProductWithoutPrice);

	$bNeedAgent = false;

	$boolFlag = true;
	$arCurrentIBlocks = array();
	$arNewIBlocksList = array();
	$rsIBlocks = CIBlock::GetList(array());
	while ($arOneIBlock = $rsIBlocks->Fetch())
	{
		// Current info
		$arIBlockItem = array();
		$arOneCatalog = CCatalog::GetByIDExt($arOneIBlock['ID']);
		$arIBlockSitesList = array();
		$rsIBlockSites = CIBlock::GetSite($arOneIBlock['ID']);
		while ($arIBlockSite = $rsIBlockSites->Fetch())
		{
			$arIBlockSitesList[] = htmlspecialchars($arIBlockSite['SITE_ID']);
		}

		$strInfo = '['.$arOneIBlock['IBLOCK_TYPE_ID'].'] '.htmlspecialchars($arOneIBlock['NAME']).' ('.implode(' ',$arIBlockSitesList).')';

		$arIBlockItem = array(
			'INFO' => $strInfo,
			'ID' => $arOneIBlock['ID'],
			'NAME' => $arOneIBlock['NAME'],
			'SITE_ID' => $arIBlockSitesList,
			'IBLOCK_TYPE_ID' => $arOneIBlock['IBLOCK_TYPE_ID'],
			'CATALOG' => 'N',
			'CATALOG_TYPE' => '',
			'PRODUCT_IBLOCK_ID' => 0,
			'SKU_PROPERTY_ID' => 0,
			'OFFERS_IBLOCK_ID' => 0,
			'OFFERS_PROPERTY_ID' => 0,
		);
		if (true == is_array($arOneCatalog))
		{
			$arIBlockItem['CATALOG'] = $arOneCatalog['CATALOG'];
			$arIBlockItem['CATALOG_TYPE'] = $arOneCatalog['CATALOG_TYPE'];
			$arIBlockItem['PRODUCT_IBLOCK_ID'] = $arOneCatalog['PRODUCT_IBLOCK_ID'];
			$arIBlockItem['SKU_PROPERTY_ID'] = $arOneCatalog['SKU_PROPERTY_ID'];
			$arIBlockItem['OFFERS_IBLOCK_ID'] = $arOneCatalog['OFFERS_IBLOCK_ID'];
			$arIBlockItem['OFFERS_PROPERTY_ID'] = $arOneCatalog['OFFERS_PROPERTY_ID'];
		}
		$arCurrentIBlocks[$arOneIBlock['ID']] = $arIBlockItem;
		// From form
		$is_cat = ((${"IS_CATALOG_".$arOneIBlock["ID"]}=="Y") ? "Y" : "N" );
		$is_cont = ((${"IS_CONTENT_".$arOneIBlock["ID"]}!="Y") ? "N" : "Y" );
		$yan_exp = ((${"YANDEX_EXPORT_".$arOneIBlock["ID"]}!="Y") ? "N" : "Y" );
		$cat_vat = intval(${"VAT_ID_".$arOneIBlock["ID"]});

		$offer_name = trim(${"OFFERS_NAME_".$arOneIBlock["ID"]});
		$offer_type = trim(${"OFFERS_TYPE_".$arOneIBlock["ID"]});
		$offer_new_type = '';
		$offer_new_type = trim(${"OFFERS_NEWTYPE_".$arOneIBlock["ID"]});
		$flag_new_type = ('Y' == ${'CREATE_OFFERS_TYPE_'.$arOneIBlock["ID"]} ? 'Y' : 'N');

		//$is_offers = ((${"IS_OFFERS_".$arOneIBlock["ID"]}!="Y") ? "N" : "Y" );

		$offers_iblock_id = intval(${"OFFERS_IBLOCK_ID_".$arOneIBlock["ID"]});

		$arNewIBlockItem = array(
			'ID' => $arOneIBlock['ID'],
			'CATALOG' => $is_cat,
			'SUBSCRIPTION' => $is_cont,
			'YANDEX_EXPORT' => $yan_exp,
			'VAT_ID' => $cat_vat,
			'OFFERS_IBLOCK_ID' => $offers_iblock_id,
			'OFFERS_NAME' => $offer_name,
			'OFFERS_TYPE' => $offer_type,
			'OFFERS_NEW_TYPE' => $offer_new_type,
			'CREATE_OFFERS_NEW_TYPE' => $flag_new_type,
			'NEED_IS_REQUIRED' => 'N',
			'NEED_UPDATE' => 'N',
			'NEED_LINK' => 'N',
			'OFFERS_PROP' => 0,
		);
		$arNewIBlocksList[$arOneIBlock['ID']] = $arNewIBlockItem;
	}

	// check for offers is catalog
	foreach ($arCurrentIBlocks as $intIBlockID => $arIBlockInfo)
	{
		if ((0 < $arIBlockInfo['PRODUCT_IBLOCK_ID']) && ('Y' != $arNewIBlocksList[$intIBlockID]['CATALOG']))
			$arNewIBlocksList[$intIBlockID]['CATALOG'] = 'Y';
	}
	// check for double using iblock and selfmade
	$arOffersIBlocks = array();
	foreach ($arNewIBlocksList as $intIBlockID => $arIBlockInfo)
	{
		if (0 < $arIBlockInfo['OFFERS_IBLOCK_ID'])
		{
			// double
			if (true == in_array($arIBlockInfo['OFFERS_IBLOCK_ID'],$arOffersIBlocks))
			{
				$boolFlag = false;
				$strWarning .= str_replace('#OFFER#',$arCurrentIBlocks[$arIBlockInfo['OFFERS_IBLOCK_ID']]['INFO'],GetMessage('CAT_IBLOCK_OFFERS_ERR_TOO_MANY_PRODUCT_IBLOCK')).'<br />';
			}
			else
			{
				$arOffersIBlocks[] = $arIBlockInfo['OFFERS_IBLOCK_ID'];
			}
			// selfmade
			if ($arIBlockInfo['OFFERS_IBLOCK_ID'] == $intIBlockID)
			{
				$boolFlag = false;
				$strWarning .= str_replace('#PRODUCT#',$arCurrentIBlocks[$intIBlockID]['INFO'],GetMessage('CAT_IBLOCK_OFFERS_ERR_SELF_MADE')).'<br />';
			}
		}
	}
	unset($arOffersIBlocks);
	// check for rights
	if (true == $boolFlag)
	{
		if (false == $USER->IsAdmin())
		{
			foreach ($arNewIBlocksList as $intIBlockID => $arIBlockInfo)
			{
				if (CATALOG_NEW_OFFERS_IBLOCK_NEED == $arIBlockInfo['OFFERS_IBLOCK_ID'])
				{
					$boolFlag = false;
					$strWarning .= str_replace('#PRODUCT#',$arCurrentIBlocks[$intIBlockID]['INFO'],GetMessage('CAT_IBLOCK_OFFERS_ERR_CANNOT_CREATE_IBLOCK')).'<br />';
				}
			}
		}
	}
	// check for offers next offers
	if (true == $boolFlag)
	{
		foreach ($arCurrentIBlocks as $intIBlockID => $arIBlockInfo)
		{
			if ((0 < $arIBlockInfo['PRODUCT_IBLOCK_ID']) && (0 != $arNewIBlocksList[$intIBlockID]['OFFERS_IBLOCK_ID']))
			{
				$boolFlag = false;
				$strWarning .= $strWarning .= str_replace('#PRODUCT#',$arIBlockInfo['INFO'],GetMessage('CAT_IBLOCK_OFFERS_ERR_PRODUCT_AND_OFFERS')).'<br />';
			}
		}
	}
	// check for product as offer
	if (true == $boolFlag)
	{
		foreach ($arNewIBlocksList as $intIBlockID => $arIBlockInfo)
		{
			if (0 < $arIBlockInfo['OFFERS_IBLOCK_ID'])
			{
				if (0 < $arCurrentIBlocks[$arIBlockInfo['OFFERS_IBLOCK_ID']]['OFFERS_IBLOCK_ID'])
				{
					$boolFlag = false;
					$strWarning .= str_replace('#PRODUCT#',$arCurrentIBlocks[$arIBlockInfo['OFFERS_IBLOCK_ID']]['INFO'],GetMessage('CAT_IBLOCK_OFFERS_ERR_PRODUCT_AND_OFFERS')).'<br />';
				}
			}
		}
	}
	if (true == $boolFlag)
	{
		foreach ($arNewIBlocksList as $intIBlockID => $arIBlockInfo)
		{
			if (0 < $arIBlockInfo['OFFERS_IBLOCK_ID'])
			{
				if (0 < $arNewIBlocksList[$arIBlockInfo['OFFERS_IBLOCK_ID']]['OFFERS_IBLOCK_ID'])
				{
					$boolFlag = false;
					$strWarning .= str_replace('#PRODUCT#',$arNewIBlocksList[$arIBlockInfo['OFFERS_IBLOCK_ID']]['INFO'],GetMessage('CAT_IBLOCK_OFFERS_ERR_PRODUCT_AND_OFFERS')).'<br />';
				}
			}
		}
	}
	if (true == $boolFlag)
	{
		foreach ($arNewIBlocksList as $intIBlockID => $arIBlockInfo)
		{
			if (0 < $arIBlockInfo['OFFERS_IBLOCK_ID'])
			{
				if (CATALOG_NEW_OFFERS_IBLOCK_NEED == $arNewIBlocksList[$arIBlockInfo['OFFERS_IBLOCK_ID']]['OFFERS_IBLOCK_ID'])
				{
					$boolFlag = false;
					$strWarning .= str_replace('#PRODUCT#',$arNewIBlocksList[$arIBlockInfo['OFFERS_IBLOCK_ID']]['INFO'],GetMessage('CAT_IBLOCK_OFFERS_ERR_PRODUCT_AND_OFFERS')).'<br />';
				}
			}
		}
	}

	// check name and new iblock_type
	if (true == $boolFlag)
	{
		foreach ($arNewIBlocksList as $intIBlockID => $arIBlockInfo)
		{
			if (CATALOG_NEW_OFFERS_IBLOCK_NEED == $arIBlockInfo['OFFERS_IBLOCK_ID'])
			{
				if ('' == trim($arIBlockInfo['OFFERS_NAME']))
				{
					$arNewIBlocksList[$intIBlockID]['OFFERS_NAME'] = str_replace('#PRODUCT#',$arCurrentIBlocks[$intIBlockID]['NAME'],GetMessage('CAT_IBLOCK_OFFERS_NAME_TEPLATE'));
				}
				if (('Y' == $arIBlockInfo['CREATE_OFFERS_NEW_TYPE']) && ('' == trim($arIBlockInfo['OFFERS_NEW_TYPE'])))
				{
 					$arNewIBlocksList[$intIBlockID]['CREATE_OFFERS_NEW_TYPE'] = 'N';
 					$arNewIBlocksList[$intIBlockID]['OFFERS_TYPE'] = $arCurrentIBlocks[$intIBlockID]['IBLOCK_TYPE_ID'];
				}
				if (('N' == $arIBlockInfo['CREATE_OFFERS_NEW_TYPE']) && ('' == trim($arIBlockInfo['OFFERS_TYPE'])))
				{
 					$arNewIBlocksList[$intIBlockID]['CREATE_OFFERS_NEW_TYPE'] = 'N';
 					$arNewIBlocksList[$intIBlockID]['OFFERS_TYPE'] = $arCurrentIBlocks[$intIBlockID]['IBLOCK_TYPE_ID'];
				}
			}
		}
	}
	// check for sites
	if (true == $boolFlag)
	{
		foreach ($arNewIBlocksList as $intIBlockID => $arIBlockInfo)
		{
			if (0 < $arIBlockInfo['OFFERS_IBLOCK_ID'])
			{
				$arDiffParent = array();
				$arDiffParent = array_diff($arCurrentIBlocks[$intIBlockID]['SITE_ID'],$arCurrentIBlocks[$arIBlockInfo['OFFERS_IBLOCK_ID']]['SITE_ID']);
				$arDiffOffer = array();
				$arDiffOffer = array_diff($arCurrentIBlocks[$arIBlockInfo['OFFERS_IBLOCK_ID']]['SITE_ID'],$arCurrentIBlocks[$intIBlockID]['SITE_ID']);
				if ((false == empty($arDiffParent)) || (false == empty($arDiffOffer)))
				{
					$boolFlag = false;
					$strWarning .= str_replace(array('#PRODUCT#','#OFFERS#'),array($arCurrentIBlocks[$intIBlockID]['INFO'],$arCurrentIBlocks[$arIBlockInfo['OFFERS_IBLOCK_ID']]['INFO']),GetMessage('CAT_IBLOCK_OFFERS_ERR_SITELIST_DEFFERENT')).'<br />';
				}
			}
		}
	}
	// check properties
	if (true == $boolFlag)
	{
		foreach ($arNewIBlocksList as $intIBlockID => $arIBlockInfo)
		{
			if (0 < $arIBlockInfo['OFFERS_IBLOCK_ID'])
			{
				// search properties
				$intCountProp = 0;
				$arLastProp = false;
				$rsProps = CIBlockProperty::GetList(array(),array('IBLOCK_ID' => $arIBlockInfo['OFFERS_IBLOCK_ID'],'PROPERTY_TYPE' => 'E','LINK_IBLOCK_ID' => $intIBlockID,'ACTIVE' => 'Y','USER_TYPE' => 'SKU'));
				if ($arProp = $rsProps->Fetch())
				{
					$intCountProp++;
					$arLastProp = $arProp;
					while ($arProp = $rsProps->Fetch())
					{
						if (false !== $arProp)
						{
							$arLastProp = $arProp;
							$intCountProp++;
						}
					}
				}
				if (1 < $intCountProp)
				{
					// too many links for catalog
					$boolFlag = false;
					$strWarning .= str_replace(array('#OFFER#','#PRODUCT#'),array($arCurrentIBlocks[$arIBlockInfo['OFFERS_IBLOCK_ID']]['INFO'],$arCurrentIBlocks[$intIBlockID]['INFO']),GetMessage('CAT_IBLOCK_OFFERS_ERR_TOO_MANY_LINKS')).'<br />';
				}
				elseif (1 == $intCountProp)
				{
					if ('Y' == $arLastProp['MULTIPLE'])
					{
						// link must single property
						$boolFlag = false;
						$strWarning .= str_replace(array('#OFFER#','#PRODUCT#'),array($arCurrentIBlocks[$arIBlockInfo['OFFERS_IBLOCK_ID']]['INFO'],$arCurrentIBlocks[$intIBlockID]['INFO']),GetMessage('CAT_IBLOCK_OFFERS_ERR_LINKS_MULTIPLE')).'<br />';
					}
					elseif (('SKU' != $arLastProp['USER_TYPE']) || ('CML2_LINK' != $arLastProp['XML_ID']))
					{
						// link must is updated
						$arNewIBlocksList[$intIBlockID]['NEED_UPDATE'] = 'Y';
						$arNewIBlocksList[$intIBlockID]['OFFERS_PROP'] = $arLastProp['ID'];
					}
					else
					{
						$arNewIBlocksList[$intIBlockID]['OFFERS_PROP'] = $arLastProp['ID'];
					}
				}
				elseif (0 == $intCountProp)
				{
					// create offers iblock
					$arNewIBlocksList[$intIBlockID]['NEED_IS_REQUIRED'] = 'N';
					$arNewIBlocksList[$intIBlockID]['NEED_UPDATE'] = 'Y';
					$arNewIBlocksList[$intIBlockID]['NEED_LINK'] = 'Y';
				}
			}
		}
	}
	// create iblock
	$arNewOffers = array();
	if (true == $boolFlag)
	{
		$DB->StartTransaction();
		foreach ($arNewIBlocksList as $intIBlockID => $arIBlockInfo)
		{
			if (CATALOG_NEW_OFFERS_IBLOCK_NEED == $arIBlockInfo['OFFERS_IBLOCK_ID'])
			{
				// need new offers
				$arResultNewCatalogItem = array();
				if ('Y' == $arIBlockInfo['CREATE_OFFERS_NEW_TYPE'])
				{
					$rsIBlockTypes = CIBlockType::GetByID($arIBlockInfo['OFFERS_NEW_TYPE']);
					if ($arIBlockType = $rsIBlockTypes->Fetch())
					{
						$arIBlockInfo['OFFERS_TYPE'] = $arIBlockInfo['OFFERS_NEW_TYPE'];
					}
					else
					{
						$arFields = array(
							'ID' => $arIBlockInfo['OFFERS_NEW_TYPE'],
							'SECTIONS' => 'N',
							'IN_RSS' => 'N',
							'SORT' => 500,
						);
						$rsLanguages = CLanguage::GetList($by="sort", $order="desc",array('ACTIVE' => 'Y'));
						while ($arLanguage = $rsLanguages->Fetch())
						{
							$arFields['LANG'][$arLanguage['LID']]['NAME'] = $arIBlockInfo['OFFERS_NEW_TYPE'];
						}
						$obIBlockType = new CIBlockType();
						$mxOffersType = $obIBlockType->Add($arFields);
						if (false == $mxOffersType)
						{
							$boolFlag = false;
							$strWarning .= str_replace(array('#PRODUCT#','#ERROR#'),array($arCurrentIBlocks[$intIBlockID]['INFO'],$obIBlockType->LAST_ERROR),GetMessage('CAT_IBLOCK_OFFERS_ERR_NEW_IBLOCK_TYPE_NOT_ADD')).'<br />';
						}
						else
						{
							$arIBlockInfo['OFFERS_TYPE'] = $arIBlockInfo['OFFERS_NEW_TYPE'];
						}
					}
				}
				if (true == $boolFlag)
				{
					$arParentRights = CIBlock::GetGroupPermissions($intIBlockID);
					foreach ($arParentRights as $keyRight => $valueRight)
					{
						if ('U' == $valueRight)
						{
							$arParentRights[$keyRight] = 'W';
						}
					}
					$arFields = array(
						'SITE_ID' => $arCurrentIBlocks[$intIBlockID]['SITE_ID'],
						'IBLOCK_TYPE_ID' => $arIBlockInfo['OFFERS_TYPE'],
						'NAME' => $arIBlockInfo['OFFERS_NAME'],
						'ACTIVE' => 'Y',
						'GROUP_ID' => $arParentRights,
						'WORKFLOW' => 'N',
						'BIZPROC' => 'N',
						"LIST_PAGE_URL" => '',
						"SECTION_PAGE_URL" => '',
						"DETAIL_PAGE_URL" => '#PRODUCT_URL#',
						"INDEX_SECTION" => "N",
					);
					$obIBlock = new CIBlock();
					$mxOffersID = $obIBlock->Add($arFields);
					if (false === $mxOffersID)
					{
						$boolFlag = false;
						$strWarning .= str_replace(array('#PRODUCT#','#ERR#'),array($arCurrentIBlocks[$intIBlockID]['INFO'],$obIBlock->LAST_ERROR),GetMessage('CAT_IBLOCK_OFFERS_ERR_IBLOCK_ADD')).'<br />';
					}
					else
					{
						$arResultNewCatalogItem = array(
							'INFO' => '['.$arFields['IBLOCK_TYPE_ID'].'] '.htmlspecialchars($arFields['NAME']).' ('.implode(' ',$arCurrentIBlocks[$intIBlockID]['SITE_ID']).')',
							'SITE_ID' => $arCurrentIBlocks[$intIBlockID]['SITE_ID'],
							'IBLOCK_TYPE_ID' => $arFields['IBLOCK_TYPE_ID'],
							'ID' => $mxOffersID,
							'NAME' => $arFields['NAME'],
							'CATALOG' => 'Y',
							'IS_CONTENT' => 'N',
							'YANDEX_EXPORT' => 'N',
							'VAT_ID' => 0,
							'PRODUCT_IBLOCK_ID' => $intIBlockID,
							'SKU_PROPERTY_ID' => 0,
							'NEED_IS_REQUIRED' => 'N',
							'NEED_UPDATE' => 'Y',
							'LINK_PROP' => false,
							'NEED_LINK' => 'Y',
						);
						$arFields = array(
							'IBLOCK_ID' => $mxOffersID,
							'NAME' => GetMessage('CAT_IBLOCK_OFFERS_TITLE_LINK_NAME'),
							'ACTIVE' => 'Y',
							'PROPERTY_TYPE' => 'E',
							'MULTIPLE' => 'N',
							'LINK_IBLOCK_ID' => $intIBlockID,
							'CODE' => 'CML2_LINK',
							'XML_ID' => 'CML2_LINK',
							"FILTRABLE" => "Y",
							'USER_TYPE' => 'SKU',
						);
						$obProp = new CIBlockProperty();
						$mxPropID = $obProp->Add($arFields);
						if (false === $mxPropID)
						{
							$boolFlag = false;
							$strWarning .= str_replace(array('#OFFERS#','#ERR#'),array($arResultNewCatalogItem['INFO'],$obProp->LAST_ERROR),GetMessage('CAT_IBLOCK_OFFERS_ERR_CANNOT_CREATE_LINK')).'<br />';
						}
						else
						{
							$arResultNewCatalogItem['SKU_PROPERTY_ID'] = $mxPropID;
							$arResultNewCatalogItem['NEED_IS_REQUIRED'] = 'N';
							$arResultNewCatalogItem['NEED_UPDATE'] = 'N';
							$arResultNewCatalogItem['NEED_LINK'] = 'N';
						}
					}
				}
				if (true ==$boolFlag)
				{
					$arNewOffers[$mxOffersID] = $arResultNewCatalogItem;
				}
				else
				{
					break;
				}
			}
		}
		if (false == $boolFlag)
		{
			$DB->Rollback();
		}
		else
		{
			$DB->Commit();
		}
	}
	// create properties
	if (true == $boolFlag)
	{
		$DB->StartTransaction();
		foreach ($arNewIBlocksList as $intIBlockID => $arIBlockInfo)
		{
			if (0 < $arIBlockInfo['OFFERS_IBLOCK_ID'])
			{
				if ('Y' == $arIBlockInfo['NEED_LINK'])
				{
					$arFields = array(
						'IBLOCK_ID' => $arIBlockInfo['OFFERS_IBLOCK_ID'],
						'NAME' => GetMessage('CAT_IBLOCK_OFFERS_TITLE_LINK_NAME'),
						'ACTIVE' => 'Y',
						'PROPERTY_TYPE' => 'E',
						'MULTIPLE' => 'N',
						'LINK_IBLOCK_ID' => $intIBlockID,
						'CODE' => 'CML2_LINK',
						'XML_ID' => 'CML2_LINK',
						"FILTRABLE" => "Y",
						'USER_TYPE' => 'SKU',
					);
					$obProp = new CIBlockProperty();
					$mxPropID = $obProp->Add($arFields);
					if (false == $mxPropID)
					{
						$boolFlag = false;
						$strWarning .= str_replace(array('#OFFERS#','#ERR#'),array($arCurrentIBlocks[$arIBlockInfo['OFFERS_IBLOCK_ID']]['INFO'],$obProp->LAST_ERROR),GetMessage('CAT_IBLOCK_OFFERS_ERR_CANNOT_CREATE_LINK')).'<br />';
					}
					else
					{
						$arNewIBlocksList[$intIBlockID]['OFFERS_PROP'] = $mxPropID;
						$arNewIBlocksList[$intIBlockID]['NEED_IS_REQUIRED'] = 'N';
						$arNewIBlocksList[$intIBlockID]['NEED_UPDATE'] = 'N';
						$arNewIBlocksList[$intIBlockID]['NEED_LINK'] = 'N';
					}
				}
				elseif (0 < $arIBlockInfo['OFFERS_PROP'])
				{
					if ('Y' == $arIBlockInfo['NEED_UPDATE'])
					{
						$arPropFields = array(
							'USER_TYPE' => 'SKU',
							'XML_ID' => 'CML2_LINK',
						);
						$obProp = new CIBlockProperty();
						$mxPropID = $obProp->Update($arIBlockInfo['OFFERS_PROP'],$arPropFields);
						if (!$mxPropID)
						{
							$boolFlag = false;
							$strWarning .= $strWarning .= str_replace(array('#OFFERS#','#ERR#'),array($arCurrentIBlocks[$arIBlockInfo['OFFERS_IBLOCK_ID']]['INFO'],$obProp->LAST_ERROR),GetMessage('CAT_IBLOCK_OFFERS_ERR_MODIFY_PROP_IS_REQ')).'<br />';
							break;
						}
					}
				}
			}
		}
		if (false == $boolFlag)
		{
			$DB->Rollback();
		}
		else
		{
			$DB->Commit();
		}
	}
	// reverse array
	if (true == $boolFlag)
	{
		foreach ($arNewIBlocksList as $intIBlockID => $arIBlockInfo)
		{
			$arCurrentIBlocks[$intIBlockID]['CATALOG'] = $arIBlockInfo['CATALOG'];
			$arCurrentIBlocks[$intIBlockID]['SUBSCRIPTION'] = $arIBlockInfo['SUBSCRIPTION'];
			$arCurrentIBlocks[$intIBlockID]['YANDEX_EXPORT'] = $arIBlockInfo['YANDEX_EXPORT'];
			$arCurrentIBlocks[$intIBlockID]['VAT_ID'] = $arIBlockInfo['VAT_ID'];
		}
		foreach ($arNewIBlocksList as $intIBlockID => $arIBlockInfo)
		{
			if (0 < $arIBlockInfo['OFFERS_IBLOCK_ID'])
			{
				$arCurrentIBlocks[$arIBlockInfo['OFFERS_IBLOCK_ID']]['CATALOG'] = 'Y';
				$arCurrentIBlocks[$arIBlockInfo['OFFERS_IBLOCK_ID']]['PRODUCT_IBLOCK_ID'] = $intIBlockID;
				$arCurrentIBlocks[$arIBlockInfo['OFFERS_IBLOCK_ID']]['SKU_PROPERTY_ID'] = $arIBlockInfo['OFFERS_PROP'];
			}
		}
	}
	// check old offers
	if (true == $boolFlag)
	{
		foreach ($arCurrentIBlocks as $intIBlockID => $arIBlockInfo)
		{
			if (0 < $arIBlockInfo['PRODUCT_IBLOCK_ID'])
			{
				if ($intIBlockID != $arNewIBlocksList[$arIBlockInfo['PRODUCT_IBLOCK_ID']]['OFFERS_IBLOCK_ID'])
				{
					$arCurrentIBlocks[$intIBlockID]['UNLINK'] = 'Y';
				}
			}
		}
	}
	// go exist iblock
	$boolCatalogUpdate = false;
	if (true == $boolFlag)
	{
		$DB->StartTransaction();
		$obCatalog = new CCatalog();
		foreach ($arCurrentIBlocks as $intIBlockID => $arIBlockInfo)
		{
			$boolAttr = true;
			if ((true == isset($arIBlockInfo['UNLINK'])) && ('Y' == $arIBlockInfo['UNLINK']))
			{
				$boolFlag = $obCatalog->UnLinkSKUIBlock($arIBlockInfo['PRODUCT_IBLOCK_ID']);
				if (true == $boolFlag)
				{
					$arIBlockInfo['PRODUCT_IBLOCK_ID'] = 0;
					$arIBlockInfo['SKU_PROPERTY_ID'] = 0;
					$boolCatalogUpdate = true;
				}
				else
				{
					$boolFlag = false;
					$ex = $APPLICATION->GetException();
					$strError = $ex->GetString();
					$strWarning .= str_replace(array('#PRODUCT#','#ERROR#'),array($arIBlockInfo['INFO'],$strError),GetMessage('CAT_IBLOCK_OFFERS_ERR_UNLINK_SKU')).'<br />';
				}
			}
			if (true == $boolFlag)
			{
				$ar_res1 = CCatalog::GetByID($intIBlockID);

				if (($arIBlockInfo['CATALOG']=="Y" || $arIBlockInfo['SUBSCRIPTION']=="Y" || 0 < $arIBlockInfo['PRODUCT_IBLOCK_ID']) && $ar_res1)
				{
					$boolAttr = $obCatalog->Update($intIBlockID, array('IBLOCK_ID' => $arIBlockInfo['ID'],"YANDEX_EXPORT" => $arIBlockInfo['YANDEX_EXPORT'], "SUBSCRIPTION" => $arIBlockInfo['SUBSCRIPTION'], "VAT_ID" => $arIBlockInfo['VAT_ID'], "PRODUCT_IBLOCK_ID" => $arIBlockInfo['PRODUCT_IBLOCK_ID'], 'SKU_PROPERTY_ID' => $arIBlockInfo['SKU_PROPERTY_ID']));
					if (false == $boolAttr)
					{
						$ex = $APPLICATION->GetException();
						$strError = $ex->GetString();
						$strWarning .= str_replace(array('#PRODUCT#','#ERROR#'),array($arIBlockInfo['INFO'],$strError),GetMessage('CAT_IBLOCK_OFFERS_ERR_CAT_UPDATE')).'<br />';
						$boolFlag = false;
					}
					else
					{
						if (($ar_res1['SUBSCRIPTION'] != $arIBlockInfo['SUBSCRIPTION']) || (intval($ar_res1['PRODUCT_IBLOCK_ID']) != intval($arIBlockInfo['PRODUCT_IBLOCK_ID'])) || ($ar_res1['YANDEX_EXPORT'] != $arIBlockInfo['YANDEX_EXPORT']) || ($ar_res1['VAT_ID'] != $arIBlockInfo['VAT_ID']))
						{
							$boolCatalogUpdate = true;
						}
						if ($arIBlockInfo['YANDEX_EXPORT']=="Y") $bNeedAgent = True;
					}
				}
				elseif ($arIBlockInfo['CATALOG']!="Y" && $arIBlockInfo['SUBSCRIPTION']!="Y" && 0 == $arIBlockInfo['PRODUCT_IBLOCK_ID'] && $ar_res1)
				{
					if (CCatalog::Delete($arIBlockInfo['ID'])===False)
					{
						$boolFlag = false;
						$strWarning .= GetMessage("CAT_DEL_CATALOG1").' '.$arIBlockInfo['INFO'].' '.GetMessage("CAT_DEL_CATALOG2").".<br />";
					}
					else
					{
						$boolCatalogUpdate = true;
					}
				}
				elseif ($arIBlockInfo['CATALOG']=="Y" || $arIBlockInfo['SUBSCRIPTION']=="Y" || 0 < $arIBlockInfo['PRODUCT_IBLOCK_ID'])
				{
					$boolAttr = $obCatalog->Add(array('IBLOCK_ID' => $arIBlockInfo['ID'], "YANDEX_EXPORT" => $arIBlockInfo['YANDEX_EXPORT'], "SUBSCRIPTION" => $arIBlockInfo['SUBSCRIPTION'], "VAT_ID" => $arIBlockInfo['VAT_ID'], "PRODUCT_IBLOCK_ID" => $arIBlockInfo['PRODUCT_IBLOCK_ID'], 'SKU_PROPERTY_ID' => $arIBlockInfo['SKU_PROPERTY_ID']));
					if (false == $boolAttr)
					{
						$ex = $APPLICATION->GetException();
						$strError = $ex->GetString();
						$strWarning .= str_replace(array('#PRODUCT#','#ERROR#'),array($arIBlockInfo['INFO'],$strError),GetMessage('CAT_IBLOCK_OFFERS_ERR_CAT_ADD')).'<br />';
						$boolFlag = false;
					}
					else
					{
						if ($arIBlockInfo['YANDEX_EXPORT']=="Y") $bNeedAgent = True;
						$boolCatalogUpdate = true;
					}
				}
			}
			if (false == $boolFlag)
				break;
		}
		if (false == $boolFlag)
		{
			$DB->Rollback();
		}
		else
		{
			$DB->Commit();
		}
	}
	if (true == $boolFlag)
	{
		if (false == empty($arNewOffers))
		{
			$DB->StartTransaction();
			foreach ($arNewOffers as $IntIBlockID => $arIBlockInfo)
			{
				$boolAttr = $obCatalog->Add(array('IBLOCK_ID' => $arIBlockInfo['ID'], "YANDEX_EXPORT" => $arIBlockInfo['YANDEX_EXPORT'], "SUBSCRIPTION" => $arIBlockInfo['SUBSCRIPTION'], "VAT_ID" => $arIBlockInfo['VAT_ID'], "PRODUCT_IBLOCK_ID" => $arIBlockInfo['PRODUCT_IBLOCK_ID'], 'SKU_PROPERTY_ID' => $arIBlockInfo['SKU_PROPERTY_ID']));
				if (false == $boolAttr)
				{
					$ex = $APPLICATION->GetException();
					$strError = $ex->GetString();
					$strWarning .= str_replace(array('#PRODUCT#','#ERROR#'),array($arIBlockInfo['INFO'],$strError),GetMessage('CAT_IBLOCK_OFFERS_ERR_CAT_ADD')).'<br />';
					$boolFlag = false;
					break;
				}
				else
				{
					if ($arIBlockInfo['YANDEX_EXPORT']=="Y") $bNeedAgent = True;
					$boolCatalogUpdate = true;
				}
			}
			if (false == $boolFlag)
			{
				$DB->Rollback();
			}
			else
			{
				$DB->Commit();
			}
		}
	}

	if ((true == $boolFlag) && (true == $boolCatalogUpdate))
	{
		$strOK .= GetMessage('CAT_IBLOCK_CATALOG_SUCCESSFULLY_UPDATE').'<br />';
	}

	CAgent::RemoveAgent("CCatalog::PreGenerateXML(\"yandex\");", "catalog");
	if ($bNeedAgent)
	{
		CAgent::AddAgent("CCatalog::PreGenerateXML(\"yandex\");", "catalog", "N", IntVal(COption::GetOptionString("catalog", "yandex_xml_period", "24"))*60*60, "", "Y");
	}
}

if ($REQUEST_METHOD=="POST" && strlen($pregenerate_discounts)>0 && !$bReadOnly /*$CAT_RIGHT=="W"*/  && check_bitrix_sessid())
{
	if (file_exists($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_FILE) && is_file($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_FILE))
		@unlink($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_FILE);
	if (file_exists($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_CPN_FILE) && is_file($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_CPN_FILE))
		@unlink($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_CPN_FILE);

	clearstatcache();

	if (file_exists($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_FILE) || file_exists($_SERVER["DOCUMENT_ROOT"].CATALOG_DISCOUNT_CPN_FILE))
	{
		$strWarning .= GetMessage("COP_CANT_DELETE").". ";
	}
	else
	{
		$dbDiscounts = CCatalogDiscount::GetList(
			array(),
			array(
				"ACTIVE" => "Y",
				"+<=ACTIVE_FROM" => Date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL"))),
				"+>=ACTIVE_TO" => Date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL"))),
			),
			false,
			false,
			array("ID")
		);
		while ($arDiscounts = $dbDiscounts->Fetch())
			CCatalogDiscount::GenerateDataFile($arDiscounts["ID"]);

		$strOK .= GetMessage("COP_REGEN_SUCCESS").". ";
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agent_start']) && strlen($_POST['agent_start']) > 0 && !$bReadOnly /*$CAT_RIGHT=="W"*/  && check_bitrix_sessid())
{
	CAgent::RemoveAgent("CCatalog::PreGenerateXML(\"yandex\");", "catalog");
	$intCount = CCatalog::GetList(array(),array('YANDEX_EXPORT' => 'Y'),array());
	if ($intCount > 0)
	{
		CAgent::AddAgent("CCatalog::PreGenerateXML(\"yandex\");", "catalog", "N", IntVal(COption::GetOptionString("catalog", "yandex_xml_period", "24"))*60*60, "", "Y");
		$strOK .= GetMessage('CAT_AGENT_ADD_SUCCESS').'. ';
	}
	else
	{
		$strWarning .= GetMessage('CAT_AGENT_ADD_NO_EXPORT').'. ';
	}
}

if(strlen($strWarning)>0)
	CAdminMessage::ShowMessage($strWarning);

if(strlen($strOK)>0)
	CAdminMessage::ShowNote($strOK);

$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("CO_TAB_1"), "ICON" => "catalog_settings", "TITLE" => GetMessage("CO_TAB_1_TITLE")),
	array("DIV" => "edit2", "TAB" => GetMessage("CO_TAB_2"), "ICON" => "catalog_settings", "TITLE" => GetMessage("CO_TAB_2_TITLE"))
);

if ($USER->IsAdmin())
{
	$aTabs[] = array("DIV" => "edit3", "TAB" => GetMessage("CO_TAB_3"), "ICON" => "catalog_settings", "TITLE" => GetMessage("CO_SALE_GROUPS"));
	$aTabs[] = array("DIV" => "edit4", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "catalog_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS"));
}

$tabControl = new CAdminTabControl("tabControl", $aTabs);
?>
<?
$tabControl->Begin();
?><form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($mid)?>&lang=<?=LANGUAGE_ID?>" name="ara">
<?echo bitrix_sessid_post()?><?
$tabControl->BeginNextTab();

	for($i=0; $i<count($arAllOptions); $i++):
		$Option = $arAllOptions[$i];
		$val = COption::GetOptionString("catalog", $Option[0], $Option[2]);
		$type = $Option[3];
		?>
		<tr>
			<td valign="top"><?	if($type[0]=="checkbox")
							echo "<label for=\"".htmlspecialchars($Option[0])."\">".$Option[1]."</label>";
						else
							echo $Option[1];?></td>
			<td valign="middle">
				<?
				if ($Option[0] == 'export_default_path')
				{
					CAdminFileDialog::ShowScript
					(
						array(
							"event" => "BtnClickExpPath",
							"arResultDest" => array("FORM_NAME" => "ara", "FORM_ELEMENT_NAME" => $Option[0]),
							"arPath" => array("PATH" => GetDirPath($val)),
							"select" => 'D',// F - file only, D - folder only
							"operation" => 'O',// O - open, S - save
							"showUploadTab" => false,
							"showAddToMenuTab" => false,
							"fileFilter" => '',
							"allowAllFiles" => true,
							"SaveConfig" => true,
						)
					);
					?><input type="text" name="<? echo htmlspecialchars($Option[0]); ?>" size="50" maxlength="255" value="<?echo htmlspecialchars($val); ?>">&nbsp;<input type="button" name="browseExpPath" value="..." onClick="BtnClickExpPath()"><?
				}
				else
				{
					if($type[0]=="checkbox"):?>
						<input type="checkbox" name="<?echo htmlspecialchars($Option[0])?>" id="<?echo htmlspecialchars($Option[0])?>" value="Y"<?if($val=="Y")echo" checked";?>>
					<?elseif($type[0]=="text"):?>
						<input type="text" size="<?echo $type[1]?>" maxlength="255" value="<?echo htmlspecialchars($val)?>" name="<?echo htmlspecialchars($Option[0])?>">
					<?elseif($type[0]=="textarea"):?>
						<textarea rows="<?echo $type[1]?>" cols="<?echo $type[2]?>" name="<?echo htmlspecialchars($Option[0])?>"><?echo htmlspecialchars($val)?></textarea>
					<?endif;
				}
				?>
			</td>
		</tr>
	<?endfor;?>
	<tr>
		<td valign="top"><?=GetMessage("CAT_DEF_OUTFILE")?></td>
		<td valign="middle">
				<?$default_outfile_action = COption::GetOptionString("catalog", "default_outfile_action", "D");?>
				<select name="default_outfile_action">
					<option value="D" <?if ($default_outfile_action=="D" || strlen($default_outfile_action)<=0) echo "selected" ?>><?echo GetMessage("CAT_DEF_OUTFILE_D") ?></option>
					<option value="H" <?if ($default_outfile_action=="H") echo "selected" ?>><?=GetMessage("CAT_DEF_OUTFILE_H")?></option>
					<option value="F" <?if ($default_outfile_action=="F") echo "selected" ?>><?=GetMessage("CAT_DEF_OUTFILE_F")?></option>
				</select>
		</td>
	</tr>
	<tr>
		<td>
		<?
		$yandex_agent_file = COption::GetOptionString('catalog','yandex_agent_file','');
		CAdminFileDialog::ShowScript
		(
			Array(
				"event" => "BtnClick",
				"arResultDest" => array("FORM_NAME" => "ara", "FORM_ELEMENT_NAME" => "yandex_agent_file"),
				"arPath" => array("PATH" => GetDirPath($yandex_agent_file)),
				"select" => 'F',// F - file only, D - folder only
				"operation" => 'O',// O - open, S - save
				"showUploadTab" => true,
				"showAddToMenuTab" => false,
				"fileFilter" => 'php',
				"allowAllFiles" => true,
				"SaveConfig" => true,
			)
		);
		?>
		<?echo GetMessage("CAT_AGENT_FILE")?></td>
		<td><input type="text" name="yandex_agent_file" size="50" maxlength="255" value="<?echo $yandex_agent_file?>">&nbsp;<input type="button" name="browse" value="..." onClick="BtnClick()"></td>
	</tr>
	<tr class="heading">
		<td colspan="2" valign="top" align="center"><?echo GetMessage("CO_PAR_IE_CSV") ?></td>
	</tr>
	<tr>
		<td valign="top"><?echo GetMessage("CO_PAR_DPP_CSV") ?></td>
		<td valign="top">
			<?
			$strVal = COption::GetOptionString("catalog", "allowed_product_fields", $defCatalogAvailProdFields.",".$defCatalogAvailPriceFields);
			$arVal = explode(",", $strVal);
			$arCatalogAvailProdFields_tmp = array_merge($arCatalogAvailProdFields, $arCatalogAvailPriceFields);
			?>
			<select name="allowed_product_fields[]" multiple size="5">
				<?for ($i = 0; $i < count($arCatalogAvailProdFields_tmp); $i++):?>
					<option value="<?echo $arCatalogAvailProdFields_tmp[$i]["value"] ?>"<?if (in_array($arCatalogAvailProdFields_tmp[$i]["value"], $arVal)) echo " selected";?>><?echo $arCatalogAvailProdFields_tmp[$i]["name"]; ?></option>
				<?endfor;?>
			</select>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<?= GetMessage("CO_AVAIL_PRICE_FIELDS") ?>
		</td>
		<td valign="top">
			<?
			$strVal = COption::GetOptionString("catalog", "allowed_price_fields", $defCatalogAvailValueFields);
			$arVal = explode(",", $strVal);
			?>
			<select name="allowed_price_fields[]" multiple size="5">
				<?for ($i = 0; $i < count($arCatalogAvailValueFields); $i++):?>
					<option value="<?echo $arCatalogAvailValueFields[$i]["value"] ?>"<?if (in_array($arCatalogAvailValueFields[$i]["value"], $arVal)) echo " selected";?>><?echo $arCatalogAvailValueFields[$i]["name"]; ?></option>
				<?endfor;?>
			</select>
		</td>
	</tr>
	<tr>
		<td valign="top"><?echo GetMessage("CAT_NUM_CATALOG_LEVELS");?></td>
		<td valign="top"><?
			$strVal = COption::GetOptionString("catalog", "num_catalog_levels", "3");
			?>
			<input type="text" size="5" maxlength="5" value="<?echo htmlspecialchars($strVal)?>" name="num_catalog_levels"></td>
	</tr>
	<tr>
		<td valign="top"><?echo GetMessage("CO_PAR_DPG_CSV") ?></td>
		<td valign="top"><?
			$strVal = COption::GetOptionString("catalog", "allowed_group_fields", $defCatalogAvailGroupFields);
			$arVal = explode(",", $strVal);
			?>
			<select name="allowed_group_fields[]" multiple size="5">
				<?for ($i = 0; $i < count($arCatalogAvailGroupFields); $i++):?>
					<option value="<?echo $arCatalogAvailGroupFields[$i]["value"] ?>"<?if (in_array($arCatalogAvailGroupFields[$i]["value"], $arVal)) echo " selected";?>><?echo $arCatalogAvailGroupFields[$i]["name"]; ?></option>
				<?endfor;?>
			</select></td>
	</tr>
	<tr>
		<td valign="top"><?echo GetMessage("CO_PAR_DV1_CSV")?></td>
		<td valign="top">
			<?
			$strVal = COption::GetOptionString("catalog", "allowed_currencies", $defCatalogAvailCurrencies);
			$arVal = explode(",", $strVal);

			$lcur = CCurrency::GetList(($by1="sort"), ($order1="asc"));
			?>
			<select name="allowed_currencies[]" multiple size="3">
				<?while ($lcur_res = $lcur->Fetch()):?>
					<option value="<?echo $lcur_res["CURRENCY"] ?>"<?if (in_array($lcur_res["CURRENCY"], $arVal)) echo " selected";?>><?echo $lcur_res["CURRENCY"] ?></option>
				<?endwhile;?>
			</select>
		</td>
	</tr>

<?$tabControl->BeginNextTab();?>
<?
$strSaveProductWithoutPrice = COption::GetOptionString('catalog','save_product_without_price','N');
?>
<tr class="heading">
	<td colspan="2" valign="top" align="center"><? echo GetMessage("CAT_PRODUCT_CARD") ?></td>
</tr>
<tr>
	<td valign="top" width="50%"><label for="save_product_without_price_y"><? echo GetMessage("CAT_SAVE_PRODUCTS_WITHOUT_PRICE"); ?>:</label></td>
	<td valign="middle" width="50%"><input type="hidden" name="save_product_without_price" id="save_product_without_price_n" value="N"><input type="checkbox" name="save_product_without_price" id="save_product_without_price_y" value="Y"<?if($strSaveProductWithoutPrice == "Y")echo " checked";?>></td>
</tr>
<tr class="heading">
	<td colspan="2" valign="top" align="center"><?echo GetMessage("CAT_CATALOGS_LIST"); ?></td>
</tr>
<tr><td colspan="2">
<script type="text/javascript">
function ib_checkFldActivity(id, flag)
{
	if (flag == 0)
		if (!document.forms.ara['IS_CATALOG_' + id].checked)
			document.forms.ara['IS_CONTENT_' + id].checked = false;

	if (flag == 1)
		if (document.forms.ara['IS_CONTENT_' + id].checked)
			document.forms.ara['IS_CATALOG_' + id].checked = true;

	var bActive = document.forms.ara['IS_CATALOG_' + id].checked;
	document.forms.ara['YANDEX_EXPORT_' + id].disabled = !bActive;
	document.forms.ara['VAT_ID_' + id].disabled = !bActive;
}

function show_add_offers(id, obj)
{
	var value = obj.options[obj.selectedIndex].value;
	var add_form = document.getElementById('offers_add_info_'+id);
	if (undefined !== add_form)
	{
		if (<? echo CATALOG_NEW_OFFERS_IBLOCK_NEED; ?> == value)
		{
			add_form.style.display = 'block';
		}
		else
		{
			add_form.style.display = 'none';
		}
	}
}
function change_offers_ibtype(obj,ID)
{
	var value = obj.value;
	if ('Y' == value)
	{
		document.forms.ara['OFFERS_TYPE_' + ID].disabled = true;
		document.forms.ara['OFFERS_NEWTYPE_' + ID].disabled = false;
	}
	else if ('N' == value)
	{
		document.forms.ara['OFFERS_TYPE_' + ID].disabled = false;
		document.forms.ara['OFFERS_NEWTYPE_' + ID].disabled = true;
	}
}
</script>
<table width="100%" cellspacing="0" cellpadding="0" border="0" class="internal">
	<tr class="heading">
		<td valign="top"><?=GetMessage("CAT_IBLOCK_SELECT_NAME")?></td>
		<td valign="top"><?=GetMessage("CAT_IBLOCK_SELECT_CAT")?></td>
		<td valign="top"><?=GetMessage("CAT_IBLOCK_SELECT_OFFERS")?></td>
		<td valign="top"><?=GetMessage("CO_SALE_CONTENT") ?></td>
		<td valign="top"><?=GetMessage("CAT_IBLOCK_SELECT_YAND")?></td>
		<td valign="top"><?=GetMessage("CAT_IBLOCK_SELECT_VAT")?></td>
	</tr>
	<?
	$arVATRef = CatalogGetVATArray(array(), true);

	$arIBlockSitesList = array();

	$arIBlockFullInfo = array();

	$rsIBlocks = CIBlock::GetList(array('IBLOCK_TYPE' => 'ASC','NAME' => 'ASC'));
	while ($arIBlock = $rsIBlocks->Fetch())
	{
		if (false == array_key_exists($arIBlock['ID'],$arIBlockSitesList))
		{
			$arLIDList = array();
			$arWithLinks = array();
			$arWithoutLinks = array();
			$rsIBlockSites = CIBlock::GetSite($arIBlock['ID']);
			while ($arIBlockSite = $rsIBlockSites->Fetch())
			{
				$arLIDList[] = $arIBlockSite['LID'];
				$arWithLinks[] = '<a href="/bitrix/admin/site_edit.php?LID='.urlencode($arIBlockSite['LID']).'&lang='.LANGUAGE_ID.'" title="'.GetMessage("CO_SITE_ALT").'">'.htmlspecialchars($arIBlockSite["LID"]).'</a>';
				$arWithoutLinks[] = htmlspecialchars($arIBlockSite['LID']);
			}
			$arIBlockSitesList[$arIBlock['ID']] = array(
				'SITE_ID' => $arLIDList,
				'WITH_LINKS' => implode('&nbsp;',$arWithLinks),
				'WITHOUT_LINKS' => implode(' ',$arWithoutLinks),
			);
		}
		$arIBlockItem = array(
			'ID' => $arIBlock['ID'],
			'IBLOCK_TYPE_ID' => $arIBlock['IBLOCK_TYPE_ID'],
			'SITE_ID' => $arIBlockSitesList[$arIBlock['ID']]['SITE_ID'],
			'NAME' => htmlspecialchars($arIBlock['NAME']),
			'ACTIVE' => $arIBlock['ACTIVE'],
			'FULL_NAME' => '['.$arIBlock['IBLOCK_TYPE_ID'].'] '.htmlspecialchars($arIBlock['NAME']).' ('.$arIBlockSitesList[$arIBlock['ID']]['WITHOUT_LINKS'].')',
			'IS_CATALOG' => 'N',
			'IS_CONTENT' => 'N',
			'YANDEX_EXPORT' => 'N',
			'VAT_ID' => 0,
			'PRODUCT_IBLOCK_ID' => 0,
			'SKU_PROPERTY_ID' => 0,
			'OFFERS_IBLOCK_ID' => 0,
			'IS_OFFERS' => 'N',
			'OFFERS_PROPERTY_ID' => 0,
			'CATALOG_TYPE' => '',
		);
		$ar_res1 = CCatalog::GetByIDExt($arIBlock['ID']);
		if (true == is_array($ar_res1))
		{
			$arIBlockItem['IS_CATALOG'] = $ar_res1['CATALOG'];
			$arIBlockItem['IS_CONTENT'] = $ar_res1['SUBSCRIPTION'];
			$arIBlockItem['YANDEX_EXPORT'] = $ar_res1['YANDEX_EXPORT'];
			$arIBlockItem['VAT_ID'] = $ar_res1['VAT_ID'];
			$arIBlockItem['PRODUCT_IBLOCK_ID'] = $ar_res1['PRODUCT_IBLOCK_ID'];
			$arIBlockItem['SKU_PROPERTY_ID'] = $ar_res1['SKU_PROPERTY_ID'];
			$arIBlockItem['OFFERS_IBLOCK_ID'] = $ar_res1['OFFERS_IBLOCK_ID'];
			$arIBlockItem['OFFERS_PROPERTY_ID'] = $ar_res1['OFFERS_PROPERTY_ID'];
			if (0 < $ar_res1['PRODUCT_IBLOCK_ID'])
				$arIBlockItem['IS_OFFERS'] = 'Y';
			$arIBlockItem['CATALOG_TYPE'] = $ar_res1['CATALOG_TYPE'];
		}

		$arIBlockFullInfo[$arIBlock['ID']] = $arIBlockItem;
	}

	$arIBlockTypeIDList = array();
	$arIBlockTypeNameList = array();
	$rsIBlockTypes = CIBlockType::GetList(array("sort"=>"asc"), array("ACTIVE"=>"Y"));
	while ($arIBlockType = $rsIBlockTypes->Fetch())
	{
		if ($ar = CIBlockType::GetByIDLang($arIBlockType["ID"], LANGUAGE_ID, true))
		{
			$arIBlockTypeIDList[] = htmlspecialchars($arIBlockType["ID"]);
			$arIBlockTypeNameList[] = htmlspecialchars('['.$arIBlockType["ID"].'] '.$ar["~NAME"]);
		}
	}

	$arDoubleIBlockFullInfo = $arIBlockFullInfo;

	foreach ($arIBlockFullInfo as $res)
	{
		?>
		<tr>
			<td><?="[<a title='".GetMessage("CO_IB_TYPE_ALT")."' href='/bitrix/admin/iblock_admin.php?type=".urlencode($res["IBLOCK_TYPE_ID"])."&lang=".LANGUAGE_ID."'>".$res["IBLOCK_TYPE_ID"]."</a>] <a title='".GetMessage("CO_IB_ELEM_ALT")."' href='/bitrix/admin/iblock_element_admin.php?type=".urlencode($res["IBLOCK_TYPE_ID"])."&IBLOCK_ID=".$res["ID"]."&lang=".LANGUAGE_ID."&filter_section=-1&&filter=Y&set_filter=Y'>".$res["NAME"]."</a> (".$arIBlockSitesList[$res['ID']]['WITH_LINKS'].")"?>
			<input type="hidden" name="IS_OFFERS_<?php echo $res["ID"]; ?>" value="<?php echo $res['IS_OFFERS']; ?>" />
			</td>
			<td align="center"><input type="checkbox" name="IS_CATALOG_<?echo $res["ID"] ?>" onclick="ib_checkFldActivity('<?=$res['ID']?>', 0)" <?if ('Y' == $res['IS_CATALOG']) echo 'checked="checked"'?> <?php if ('Y' == $res['IS_OFFERS']) echo 'disabled="disabled"'; ?>value="Y" /></td>
			<td align="center"><select id="OFFERS_IBLOCK_ID_<?php echo $res["ID"]; ?>" name="OFFERS_IBLOCK_ID_<?php echo $res["ID"]; ?>" class="typeselect" size="1" <?php if ('Y' == $res['IS_OFFERS']) { ?>disabled="disabled"<?php } else { ?>onchange="show_add_offers(<?php echo $res["ID"]; ?>,this);"<?php } ?> style="width: 100%;">
			<option value="0" <?php echo (0 == $res['OFFERS_IBLOCK_ID'] ? 'selected' : '');?>><?php echo GetMessage('CAT_IBLOCK_OFFERS_EMPTY')?></option>
			<option value="<?php echo CATALOG_NEW_OFFERS_IBLOCK_NEED; ?>"><?php echo GetMessage('CAT_IBLOCK_OFFERS_NEW')?></option>
			<?php
			foreach ($arDoubleIBlockFullInfo as $value)
			{
				$boolAdd = true;
				if ($value['ID'] == $res['OFFERS_IBLOCK_ID'])
				{
					$boolAdd = true;
				}
				elseif (('N' == $value['ACTIVE']) || ('Y' == $value['IS_OFFERS']) || (0 < $value['OFFERS_IBLOCK_ID']) || ($res['ID'] == $value['ID']) || (0 < $value['PRODUCT_IBLOCK_ID']))
				{
					$boolAdd = false;
				}
				else
				{
					$arDiffParent = array();
					$arDiffParent = array_diff($value['SITE_ID'],$res['SITE_ID']);
					$arDiffOffer = array();
					$arDiffOffer = array_diff($res['SITE_ID'],$value['SITE_ID']);
					if ((false == empty($arDiffParent)) || (false == empty($arDiffOffer)))
					{
						$boolAdd = false;
					}
				}
				if (true == $boolAdd)
				{
					?><option value="<?php echo intval($value['ID']); ?>"<?php echo ($value['ID'] == $res['OFFERS_IBLOCK_ID'] ? ' selected' : ''); ?>><?php echo $value['FULL_NAME']; ?></option><?php
				}
			}
			?>
			</select>
			<div id="offers_add_info_<?php echo $res["ID"]; ?>" style="display: none; width: 98%; maring: 0 1%;"><table class="internal" style="width: 100%;"><tbody>
				<tr><td style="text-align: right; width: 25%;"><?php echo GetMessage('CAT_IBLOCK_OFFERS_TITLE'); ?>:</td><td style="text-align: left; width: 75%;"><input type="text" name="OFFERS_NAME_<?php echo $res["ID"]; ?>" value="" style="width: 98%; margin: 0 1%;" /></td></tr>
				<tr><td style="text-alogn: left; width: 100%;" colspan="2"><input type="radio" value="N" id="CREATE_OFFERS_TYPE_N_<?php echo $res['ID']; ?>" name="CREATE_OFFERS_TYPE_<?php echo $res['ID']; ?>" checked="checked" onclick="change_offers_ibtype(this,<?php echo $res['ID']?>);"><label for="CREATE_OFFERS_TYPE_N_<?php echo $res['ID']; ?>"><?php echo GetMessage('CAT_IBLOCK_OFFERS_OLD_IBTYPE');?></label></td></tr>
				<tr><td style="text-align: right; width: 25%;"><?php echo GetMessage('CAT_IBLOCK_OFFERS_TYPE'); ?>:</td><td style="text-align: left; width: 75%;"><?php echo SelectBoxFromArray('OFFERS_TYPE_'.$res["ID"],array('REFERENCE' => $arIBlockTypeNameList,'REFERENCE_ID' => $arIBlockTypeIDList),'','','style="width: 98%;  margin: 0 1%;"'); ?></td></tr>
				<tr><td style="text-alogn: left; width: 100%;" colspan="2"><input type="radio" value="Y" id="CREATE_OFFERS_TYPE_Y_<?php echo $res['ID']; ?>" name="CREATE_OFFERS_TYPE_<?php echo $res['ID']; ?>" onclick="change_offers_ibtype(this,<?php echo $res['ID']?>);"><label for="CREATE_OFFERS_TYPE_Y_<?php echo $res['ID']; ?>"><?php echo GetMessage('CAT_IBLOCK_OFFERS_NEW_IBTYPE');?></label></td></tr>
				<tr><td style="text-align: right; width: 25%;"><?php echo GetMessage('CAT_IBLOCK_OFFERS_NEWTYPE'); ?>:</td><td style="text-align: left; width: 75%;"><input type="text" name="OFFERS_NEWTYPE_<?php echo $res["ID"]; ?>" value="" style="width: 98%; margin: 0 1%;" disabled="disabled" /></td></tr>
			</tbody></table></div></td>
			<td align="center"><input type="checkbox" name="IS_CONTENT_<?echo $res["ID"] ?>" onclick="ib_checkFldActivity('<?=$res['ID']?>', 1)" <?if ('Y' == $res["IS_CONTENT"]) echo "checked"?> value="Y" /></td>
			<td align="center"><input type="checkbox" name="YANDEX_EXPORT_<?echo $res["ID"] ?>" <?if ('N' == $res['IS_CATALOG']) echo 'disabled="disabled"';?> <?if ('Y' == $res["YANDEX_EXPORT"]) echo "checked"?> value="Y" /></td>
			<td align="center"><?=SelectBoxFromArray('VAT_ID_'.$res['ID'], $arVATRef, $res['VAT_ID'], '', ('N' == $res['IS_CATALOG'] ? 'disabled="disabled"' : ''))?></td>
		</tr>
		<?
	}
	?>
</table>
</td></tr>
<?
if ($USER->IsAdmin())
{
$tabControl->BeginNextTab();

		$strVal = COption::GetOptionString("catalog", "avail_content_groups");
		$arVal = explode(",", $strVal);

		$dbUserGroups = CGroup::GetList(($b="c_sort"), ($o="asc"), array("ANONYMOUS" => "N"));
		while ($arUserGroups = $dbUserGroups->Fetch())
		{
			$arUserGroups["ID"] = IntVal($arUserGroups["ID"]);
			if ($arUserGroups["ID"] == 2)
				continue;
			?>
			<tr>
				<td width="50%"><label for="user_group_<?=$arUserGroups["ID"]?>"><?= htmlspecialcharsEx($arUserGroups["NAME"])?></label> [<a href="group_edit.php?ID=<?=$arUserGroups["ID"]?>&lang=<?=LANGUAGE_ID?>" title="<?=GetMessage("CO_USER_GROUP_ALT")?>"><?=$arUserGroups["ID"]?></a>]:</td>
				<td width="50%"><input type="checkbox" id="user_group_<?=$arUserGroups["ID"]?>" name="AVAIL_CONTENT_GROUPS[]"<?if (in_array($arUserGroups["ID"], $arVal)) echo " checked"?> value="<?= $arUserGroups["ID"] ?>"></td>
			</tr>
			<?
		}
?>
<?$tabControl->BeginNextTab();?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights2.php");?>
<?
}
?>
<?$tabControl->Buttons();?>
<script language="JavaScript">
function RestoreDefaults()
{
	if (confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>'))
		window.location = "<?echo $APPLICATION->GetCurPage()?>?RestoreDefaults=Y&lang=<?echo LANG?>&mid=<?echo urlencode($mid)?>&<?echo bitrix_sessid_get()?>";
}
</script>
<input type="submit" <?if ($bReadOnly /*$CAT_RIGHT<"W"*/) echo "disabled" ?> name="Update" value="<?echo GetMessage("MAIN_SAVE")?>">
<input type="hidden" name="Update" value="Y">
<input type="reset" name="reset" value="<?echo GetMessage("MAIN_RESET")?>">
<input type="button" <?if ($bReadOnly /*CAT_RIGHT<"W"*/) echo "disabled" ?> title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="RestoreDefaults();" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
<?$tabControl->End();?>
</form>



<h2><?= GetMessage("COP_SYS_ROU") ?></h2>
<?
$aTabs = Array();
$aTabs = array(
	array("DIV" => "fedit1", "TAB" => GetMessage("COP_TAB2_TAB_NEW"), "ICON" => "catalog_settings", "TITLE" => GetMessage("COP_TAB2_TAB_TITLE_NEW")),
	array("DIV" => "fedit2", "TAB" => GetMessage("COP_TAB2_AGENT"), "ICON" => "catalog_settings", "TITLE" => GetMessage("COP_TAB2_AGENT_TITLE")),
);
$tabControl = new CAdminTabControl("tabControl2", $aTabs, true, true);

$tabControl->Begin();
$tabControl->BeginNextTab();
	?>
	<tr>
		<td align="left" colspan="2">
			<form name="preg_form" method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($mid)?>&lang=<?=LANGUAGE_ID?>">
			<?echo bitrix_sessid_post()?>
			<input type="button" <?if ($bReadOnly /*$CAT_RIGHT < "W"*/) echo "disabled" ?> name="preg_but" value="<? echo htmlspecialchars(GetMessage("COP_PREG_BUT_NEW")); ?>" onclick="javascript: PregenerateDiscounts();">
			<input type="hidden" name="pregenerate_discounts" value="Y">
			<input type="hidden" name="lang" value="<?=LANGUAGE_ID?>">
			<script type="text/javascript">
			<!--
			function PregenerateDiscounts()
			{
				if (confirm('<? echo htmlspecialchars(GetMessage("COP_PREG_CONFIRM_NEW")); ?>'))
					document.preg_form.submit();
			}
			//-->
			</script>
			</form>
		</td>
	</tr>
	<?
$tabControl->BeginNextTab();
?><tr><td align="left" colspan="2">
<?
$arAgentInfo = false;
$rsAgents = CAgent::GetList(array(),array('MODULE_ID' => 'catalog','NAME' => "CCatalog::PreGenerateXML(\"yandex\");"));
if ($arAgent = $rsAgents->Fetch())
{
	$arAgentInfo = $arAgent;
}
if (!is_array($arAgentInfo) || empty($arAgentInfo))
{
	// NO
	?><form name="agent_form" method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($mid)?>&lang=<?=LANGUAGE_ID?>">
	<?echo bitrix_sessid_post()?>
	<input type="submit" name="agent_start" value="<? echo GetMessage('CAT_AGENT_START') ?>" <?if ($bReadOnly) echo "disabled" ?>>
	</form><?
}
else
{
	// YES
	echo GetMessage('CAT_AGENT_ACTIVE').':&nbsp;'.($arAgentInfo['ACTIVE'] == 'Y' ? GetMessage("MAIN_YES") : GetMessage("MAIN_NO")).'<br>';
	if ($arAgentInfo['LAST_EXEC'])
	{
		echo GetMessage('CAT_AGENT_LAST_EXEC').':&nbsp;'.($arAgentInfo['LAST_EXEC'] ? $arAgentInfo['LAST_EXEC'] : '').'<br>';
		echo GetMessage('CAT_AGENT_NEXT_EXEC').':&nbsp;'.($arAgentInfo['NEXT_EXEC'] ? $arAgentInfo['NEXT_EXEC'] : '').'<br>';
	}
	else
	{
		echo GetMessage('CAT_AGENT_WAIT_START').'<br>';
	}
}
?><br><?
$strYandexFile = str_replace('//','/',COption::GetOptionString("catalog", "export_default_path", "/upload/").'/yandex.php');
if (file_exists($_SERVER['DOCUMENT_ROOT'].$strYandexFile))
{
	echo str_replace('#FILE#', '<a href="'.$strYandexFile.'">'.$strYandexFile.'</a>',GetMessage('CAT_AGENT_FILEPATH')).'<br>';
}
else
{
	echo GetMessage('CAT_AGENT_FILE_ABSENT').'<br>';
}
?><br><?
echo GetMessage('CAT_AGENT_EVENT_LOG').':&nbsp;';

?><a href="/bitrix/admin/event_log.php?lang=<? echo LANGUAGE_ID; ?>&set_filter=Y<? echo CCatalogEvent::GetYandexAgentFilter(); ?>"><? echo GetMessage('CAT_AGENT_EVENT_LOG_SHOW_ERROR')?></a><?
?>
</td></tr>
<?
$tabControl->End();
?>
<?endif;?>