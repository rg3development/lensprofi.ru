<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

global $APPLICATION;

/*
 * B_ADMIN_SUBELEMENTS
 * if defined and equal 1 - working, another die
 * B_ADMIN_SUBELEMENTS_LIST - true/false
 * if not defined - die
 * if equal true - get list mode
 * 	include prolog and epilog
 * other - get simple html
 *
 * need variables
 * 		$strSubElementAjaxPath - path for ajax
 * 		$strSubIBlockType - iblock type
 * 		$arSubIBlockType - iblock type array
 * 		$intSubIBlockID - iblock ID
 * 		$strSubIBlockPerm - permission to iblock
 * 		$arSubIBlock	- array with info about iblock
 *		$boolSubWorkFlow - workflow and iblock in workflow
 *		$boolSubBizproc - business process and iblock in business
 *		$boolSubCatalog - catalog and iblock in catalog
 *		$arSubCatalog - info about catalog (with product_iblock_id iand sku_property_id info)
 *		$intSubPropValue - ID for filter
 *		$strSubTMP_ID - string identifier for link with new product ($intSubPropValue = 0, in edit form send -1)
 *
 *
 *created variables
 *		$arSubElements - array subelements for product with ID = 0
 */
if ((false == defined('B_ADMIN_SUBELEMENTS')) || (1 != B_ADMIN_SUBELEMENTS))
	return '';
if (false == defined('B_ADMIN_SUBELEMENTS_LIST'))
	return '';

$strSubElementAjaxPath = trim($strSubElementAjaxPath);
$strSubIBlockType = trim($strSubIBlockType);
$intSubIBlockID = intval($intSubIBlockID);
if ($intSubIBlockID <= 0)
	return;
$boolSubWorkFlow = ($boolSubWorkFlow === true ? true : false);
$boolSubBizproc = ($boolSubBizproc === true ? true : false);
$boolSubCatalog = ($boolSubCatalog === true ? true : false);

$intSubPropValue = intval($intSubPropValue);

$strSubTMP_ID = intval($strSubTMP_ID);

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/iblock/admin/iblock_element_admin.php");
IncludeModuleLangFile(__FILE__);

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/iblock/classes/general/subelement.php');

if (!(defined('BX_PUBLIC_MODE') && BX_PUBLIC_MODE == 1))
{
	$APPLICATION->SetAdditionalCSS('/bitrix/themes/.default/sub.css');
	$APPLICATION->AddHeadScript('/bitrix/js/iblock/subelement.js');
}
if (defined('BX_PUBLIC_MODE') && BX_PUBLIC_MODE == 1)
{
	?>
	<script type="text/javascript" bxrunfirst="yes" src="/bitrix/js/iblock/subelement.js"></script>
	<script type="text/javascript" bxrunfirst="yes">BX.loadCSS('/bitrix/themes/.default/sub-public.css');</script><?
}

$dbrFProps = CIBlockProperty::GetList(array("SORT"=>"ASC","NAME"=>"ASC"),array("IBLOCK_ID"=>$intSubIBlockID,"ACTIVE"=>"Y"));

$arProps = Array();
while($arProp = $dbrFProps->GetNext())
{
	$arProp["PROPERTY_USER_TYPE"] = (0 < strlen($arProp["USER_TYPE"]) ? CIBlockProperty::GetUserType($arProp["USER_TYPE"]) : array());
	$arProps[] = $arProp;
}

$sTableID = "tbl_iblock_sub_element_".md5($strSubIBlockType.".".$intSubIBlockID);
$oSort = new CAdminSubSorting($sTableID, "id", "asc",'by','order',$strSubElementAjaxPath);
$arHideFields = array('PROPERTY_'.$arCatalog['SKU_PROPERTY_ID']);
$lAdmin = new CAdminSubList($sTableID, $oSort,$strSubElementAjaxPath,$arHideFields);
//$lAdmin->bMultipart = true;

// only sku property filter
$arFilterFields = Array(
	"find_el_property_".$arCatalog['SKU_PROPERTY_ID'],
);

$find_section_section = -1;

//We have to handle current section in a special way
$section_id = intval($find_section_section);
$lAdmin->InitFilter($arFilterFields);
$find_section_section = $section_id;
//This is all parameters needed for proper navigation
//$sThisSectionUrl = '&type='.urlencode($strSubIBlockType).'&lang='.LANG.'&IBLOCK_ID='.$intSubIBlockID.'&find_section_section='.intval($find_section_section);
$sThisSectionUrl = '';

/*if (0 < $intSubPropValue)
{
	include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/admin/templates/iblock_subelement_action.php");
}
else
{
	include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/admin/templates/iblock_subzero_action.php");
}*/
// simple filter
$arFilter = Array(
	"IBLOCK_ID" => $intSubIBlockID,
);
if($boolSubBizproc && ($strSubIBlockPerm < "W"))
	$arFilter["CHECK_BP_PERMISSIONS"] = "read";

if (0 < $intSubPropValue)
	$arFilter["=PROPERTY_".$arSubCatalog['SKU_PROPERTY_ID']] = $intSubPropValue;
else
{
	$arFilter["=PROPERTY_".$arSubCatalog['SKU_PROPERTY_ID']] = $intSubPropValue;
}

if ((true == defined('B_ADMIN_SUBELEMENTS_LIST')) && (true == B_ADMIN_SUBELEMENTS_LIST))
{
if($lAdmin->EditAction())
{
	if(is_array($_FILES['FIELDS']))
		CAllFile::ConvertFilesToPost($_FILES['FIELDS'], $_POST['FIELDS']);
	if(is_array($FIELDS_del))
		CAllFile::ConvertFilesToPost($FIELDS_del, $_POST['FIELDS'], "del");

	foreach($_POST['FIELDS'] as $ID=>$arFields)
	{
		if(!$lAdmin->IsUpdated($ID))
			continue;
		$ID = IntVal($ID);

	   	$arRes = CIBlockElement::GetByID($ID);
	   	$arRes = $arRes->Fetch();
	   	if(!$arRes)
	   		continue;

		$WF_ID = $ID;
		if($boolSubWorkFlow)
		{
			$WF_ID = CIBlockElement::WF_GetLast($ID);
			if($WF_ID!=$ID)
			{
				$rsData2 = CIBlockElement::GetByID($WF_ID);
				if($arRes = $rsData2->Fetch())
					$WF_ID = $arRes["ID"];
				else
					$WF_ID = $ID;
			}

			if($arRes["LOCK_STATUS"]=='red' && !($_REQUEST['action']=='unlock' && CWorkflow::IsAdmin()))
			{
				$lAdmin->AddUpdateError(GetMessage("IBEL_A_UPDERR1")." (ID:".$ID.")", $ID);
				continue;
			}
		}
		elseif ($boolSubBizproc)
		{
			if (CIBlockDocument::IsDocumentLocked($ID, ""))
			{
				$lAdmin->AddUpdateError(GetMessage("IBEL_A_UPDERR_LOCKED", array("#ID#" => $ID)), $TYPE.$ID);
				continue;
			}
		}

		$bPermissions = false;
		//delete and modify can:
		if($strSubIBlockPerm>='W') // only writers
			$bPermissions = true;
		elseif($boolSubWorkFlow)
		{
			// change is under workflow find status and its permissions
			$STATUS_PERMISSION = CIBlockElement::WF_GetStatusPermission($arRes["WF_STATUS_ID"]);
			if($STATUS_PERMISSION>=2)
				$bPermissions = true;

			// status change  - check permissions
			if(isset($arFields["WF_STATUS_ID"]))
			{
				if(CIBlockElement::WF_GetStatusPermission($arFields["WF_STATUS_ID"])<1)
				{
					$lAdmin->AddUpdateError(GetMessage("IBEL_A_UPDERR2")." (ID:".$ID.")", $ID);
					continue;
				}
			}
		}
		elseif ($boolSubBizproc)
		{
			$bCanWrite = CIBlockDocument::CanUserOperateDocument(
				CBPCanUserOperateOperation::WriteDocument,
				$USER->GetID(),
				$ID,
				array("IBlockId" => $intSubIBlockID, "IBlockPermission" => $strSubIBlockPerm, "UserGroups" => $USER->GetUserGroupArray())
			);
			if ($bCanWrite)
			{
				$bPermissions = true;
			}
		}

		if(!$bPermissions)
		{
			$lAdmin->AddUpdateError(GetMessage("IBEL_A_UPDERR3")." (ID:".$ID.")", $ID);
			continue;
		}

		if(!is_array($arFields["PROPERTY_VALUES"]))
			$arFields["PROPERTY_VALUES"] = Array();
		$bFieldProps = array();
		foreach($arFields as $k=>$v)
		{
			if(
				substr($k, 0, strlen("PROPERTY_")) == "PROPERTY_"
				&& $k != "PROPERTY_VALUES"
			)
			{
				$prop_id = substr($k, strlen("PROPERTY_"));
				$arFields["PROPERTY_VALUES"][$prop_id] = $v;
				unset($arFields[$k]);
				$bFieldProps[$prop_id]=true;
			}
		}
		if(count($bFieldProps) > 0)
		{
			//We have to read properties from database in order not to delete its values
			if(!$boolSubWorkFlow)
			{
				$dbPropV = CIBlockElement::GetProperty($intSubIBlockID, $ID, "sort", "asc", Array("ACTIVE"=>"Y"));
				while($arPropV = $dbPropV->Fetch())
				{
					if(!array_key_exists($arPropV["ID"], $bFieldProps) && $arPropV["PROPERTY_TYPE"] != "F")
					{
						if(!array_key_exists($arPropV["ID"], $arFields["PROPERTY_VALUES"]))
							$arFields["PROPERTY_VALUES"][$arPropV["ID"]] = array();

						$arFields["PROPERTY_VALUES"][$arPropV["ID"]][$arPropV["PROPERTY_VALUE_ID"]] = array(
							"VALUE" => $arPropV["VALUE"],
							"DESCRIPTION" => $arPropV["DESCRIPTION"],
						);
					}
				}
			}
		}
		else
		{
			//We will not update property values
			unset($arFields["PROPERTY_VALUES"]);
		}

		//All not displayed required fields from DB
		foreach($arSubIBlock["FIELDS"] as $FIELD_ID => $field)
		{
			if(
				$field["IS_REQUIRED"] === "Y"
				&& !array_key_exists($FIELD_ID, $arFields)
				&& $FIELD_ID !== "DETAIL_PICTURE"
				&& $FIELD_ID !== "PREVIEW_PICTURE"
			)
				$arFields[$FIELD_ID] = $arRes[$FIELD_ID];
		}
		if($arRes["IN_SECTIONS"] == "Y")
		{
			$arFields["IBLOCK_SECTION"] = array();
			$rsSections = CIBlockElement::GetElementGroups($arRes["ID"], true);
			while($arSection = $rsSections->Fetch())
				$arFields["IBLOCK_SECTION"][] = $arSection["ID"];
		}

		$arFields["MODIFIED_BY"]=$USER->GetID();
		$ib = new CIBlockElement;
		$DB->StartTransaction();

		if(!$ib->Update($ID, $arFields, true, true, true))
		{
			$lAdmin->AddUpdateError(GetMessage("IBEL_A_SAVE_ERROR", array("#ID#"=>$ID, "#ERROR_TEXT#"=>$ib->LAST_ERROR)), $ID);
			$DB->Rollback();
		}
		else
		{
			$DB->Commit();
		}

		if($boolSubCatalog)
		{
			if($USER->CanDoOperation('catalog_price'))
			{
				$CATALOG_QUANTITY = $arFields["CATALOG_QUANTITY"];
				$CATALOG_QUANTITY_TRACE = $arFields["CATALOG_QUANTITY_TRACE"];

				if(!CCatalogProduct::GetByID($ID))
				{
					$arCatalogQuantity = Array("ID" => $ID);
					if(strlen($CATALOG_QUANTITY) > 0)
						$arCatalogQuantity["QUANTITY"] = $CATALOG_QUANTITY;
					if(strlen($CATALOG_QUANTITY_TRACE) > 0)
						$arCatalogQuantity["QUANTITY_TRACE"] = ($CATALOG_QUANTITY_TRACE == "Y") ? "Y" : "N";
					CCatalogProduct::Add($arCatalogQuantity);
				}
				else
				{
					$arCatalogQuantity = Array();
					if(strlen($CATALOG_QUANTITY) > 0)
						$arCatalogQuantity["QUANTITY"] = $CATALOG_QUANTITY;
					if(strlen($CATALOG_QUANTITY_TRACE) > 0)
						$arCatalogQuantity["QUANTITY_TRACE"] = ($CATALOG_QUANTITY_TRACE == "Y") ? "Y" : "N";
					if(!empty($arCatalogQuantity))
						CCatalogProduct::Update($ID, $arCatalogQuantity);
				}
			}
		}
	}

	if($boolSubCatalog)
	{
		if($USER->CanDoOperation('catalog_price') && (isset($_POST["CATALOG_PRICE"]) || isset($_POST["CATALOG_CURRENCY"])))
		{
			$CATALOG_PRICE = $_POST["CATALOG_PRICE"];
			$CATALOG_CURRENCY = $_POST["CATALOG_CURRENCY"];
			$CATALOG_EXTRA = $_POST["CATALOG_EXTRA"];
			$CATALOG_PRICE_ID = $_POST["CATALOG_PRICE_ID"];
			$CATALOG_QUANTITY_FROM = $_POST["CATALOG_QUANTITY_FROM"];
			$CATALOG_QUANTITY_TO = $_POST["CATALOG_QUANTITY_TO"];
			$CATALOG_PRICE_old = $_POST["CATALOG_old_PRICE"];
			$CATALOG_CURRENCY_old = $_POST["CATALOG_old_CURRENCY"];

			$db_extras = CExtra::GetList(($by3="NAME"), ($order3="ASC"));
			while ($extras = $db_extras->Fetch())
				$arCatExtraUp[$extras["ID"]] = $extras["PERCENTAGE"];

			if(!CCatalogProduct::GetByID($ID))
				CCatalogProduct::Add(Array("ID" => $ID));

			foreach($CATALOG_PRICE as $elID => $arPrice)
			{
				//1 Find base price ID
				//2 If such a column is displayed then
				//	check if it is greater than 0
				//3 otherwise
				//	look up it's value in database and
				//	output an error if not found or found less or equal then zero
				$bError = false;
				$arBaseGroup = CCatalogGroup::GetBaseGroup();
				if (isset($arPrice[$arBaseGroup['ID']]))
				{
					if ($arPrice[$arBaseGroup['ID']] <= 0)
					{
						$bError = true;
						$lAdmin->AddUpdateError($elID.': '.GetMessage('IB_CAT_NO_BASE_PRICE'), $elID);
					}
				}
				else
				{
					$arBasePrice = CPrice::GetBasePrice(
						$elID,
						$CATALOG_QUANTITY_FROM[$elID][$arBaseGroup['ID']],
						$CATALOG_QUANTITY_FROM[$elID][$arBaseGroup['ID']]
					);

					if (!is_array($arBasePrice) || $arBasePrice['PRICE'] <= 0)
					{
						$bError = true;
						$lAdmin->AddGroupError($elID.': '.GetMessage('IB_CAT_NO_BASE_PRICE'), $elID);
					}
				}

				if($bError)
					continue;

				$arCurrency = $CATALOG_CURRENCY[$elID];

				$dbCatalogGroups = CCatalogGroup::GetList(
						array("SORT" => "ASC"),
						array("CAN_ACCESS" => "Y", "LID"=>LANGUAGE_ID)
					);
				while ($arCatalogGroup = $dbCatalogGroups->Fetch())
				{
					if(doubleval($arPrice[$arCatalogGroup["ID"]]) != doubleval($CATALOG_PRICE_old[$elID][$arCatalogGroup["ID"]])
						|| $arCurrency[$arCatalogGroup["ID"]] != $CATALOG_CURRENCY_old[$elID][$arCatalogGroup["ID"]])
					{
						if($arCatalogGroup["BASE"]=="Y") // if base price check extra for other prices
						{
							$arFields = Array(
								"PRODUCT_ID" => $elID,
								"CATALOG_GROUP_ID" => $arCatalogGroup["ID"],
								"PRICE" => DoubleVal($arPrice[$arCatalogGroup["ID"]]),
								"CURRENCY" => $arCurrency[$arCatalogGroup["ID"]],
								"QUANTITY_FROM" => $CATALOG_QUANTITY_FROM[$elID][$arCatalogGroup["ID"]],
								"QUANTITY_TO" => $CATALOG_QUANTITY_TO[$elID][$arCatalogGroup["ID"]],
							);
							if($arFields["PRICE"] <=0 )
							{
								CPrice::Delete($CATALOG_PRICE_ID[$elID][$arCatalogGroup["ID"]]);
							}
							elseif(IntVal($CATALOG_PRICE_ID[$elID][$arCatalogGroup["ID"]])>0)
							{
								CPrice::Update(IntVal($CATALOG_PRICE_ID[$elID][$arCatalogGroup["ID"]]), $arFields);
							}
							elseif($arFields["PRICE"] > 0)
							{
								CPrice::Add($arFields);
							}

							$arPrFilter = array(
						                "PRODUCT_ID" => $elID,
							);
							if(DoubleVal($arPrice[$arCatalogGroup["ID"]])>0)
							{
								$arPrFilter["!CATALOG_GROUP_ID"] = $arCatalogGroup["ID"];
								$arPrFilter["+QUANTITY_FROM"] = "1";
								$arPrFilter["!EXTRA_ID"] = false;
							}
							$db_res = CPrice::GetList(
								array(),
								$arPrFilter,
								false,
								false,
								Array("ID", "PRODUCT_ID", "CATALOG_GROUP_ID", "PRICE", "CURRENCY", "QUANTITY_FROM", "QUANTITY_TO", "EXTRA_ID")
							);
							while($ar_res = $db_res->Fetch())
							{
								$arFields = Array(
									"PRICE" => DoubleVal($arPrice[$arCatalogGroup["ID"]])*(1+$arCatExtraUp[$ar_res["EXTRA_ID"]]/100) ,
									"EXTRA_ID" => $ar_res["EXTRA_ID"],
									"CURRENCY" => $arCurrency[$arCatalogGroup["ID"]],
									"QUANTITY_FROM" => $ar_res["QUANTITY_FROM"],
									"QUANTITY_TO" => $ar_res["QUANTITY_TO"]
								);
								if($arFields["PRICE"] <= 0)
									CPrice::Delete($ar_res["ID"]);
								else
									CPrice::Update($ar_res["ID"], $arFields);
							}
						}
						elseif(!isset($CATALOG_EXTRA[$elID][$arCatalogGroup["ID"]]))
						{
							$arFields = Array(
								"PRODUCT_ID" => $elID,
								"CATALOG_GROUP_ID" => $arCatalogGroup["ID"],
								"PRICE" => DoubleVal($arPrice[$arCatalogGroup["ID"]]),
								"CURRENCY" => $arCurrency[$arCatalogGroup["ID"]],
								"QUANTITY_FROM" => $CATALOG_QUANTITY_FROM[$elID][$arCatalogGroup["ID"]],
								"QUANTITY_TO" => $CATALOG_QUANTITY_TO[$elID][$arCatalogGroup["ID"]]
							);
							if($arFields["PRICE"] <= 0)
								CPrice::Delete($CATALOG_PRICE_ID[$elID][$arCatalogGroup["ID"]]);
							elseif(IntVal($CATALOG_PRICE_ID[$elID][$arCatalogGroup["ID"]])>0)
								CPrice::Update(IntVal($CATALOG_PRICE_ID[$elID][$arCatalogGroup["ID"]]), $arFields);
							elseif($arFields["PRICE"] > 0)
								CPrice::Add($arFields);
						}
					}
				}
			}
		}
	}
}

if(($arID = $lAdmin->GroupAction()))
{
	if($_REQUEST['action_target']=='selected')
	{
		$rsData = CIBlockElement::GetList(Array($by=>$order), $arFilter);
		while($arRes = $rsData->Fetch())
			$arID[] = $arRes['ID'];
	}

	foreach($arID as $ID)
	{
		if(strlen($ID)<=0)
			continue;

	   	$ID = IntVal($ID);
	   	$arRes = CIBlockElement::GetByID($ID);
	   	$arRes = $arRes->Fetch();
	   	if(!$arRes)
	   		continue;

		$WF_ID = $ID;
		if($boolSubWorkFlow)
		{
			$WF_ID = CIBlockElement::WF_GetLast($ID);
			if($WF_ID!=$ID)
			{
				$rsData2 = CIBlockElement::GetByID($WF_ID);
				if($arRes = $rsData2->Fetch())
					$WF_ID = $arRes["ID"];
				else
					$WF_ID = $ID;
			}

			if($arRes["LOCK_STATUS"]=='red' && !($_REQUEST['action']=='unlock' && CWorkflow::IsAdmin()))
			{
				$lAdmin->AddGroupError(GetMessage("IBEL_A_UPDERR1")." (ID:".$ID.")", $ID);
				continue;
			}
		}
		elseif ($boolSubBizproc)
		{
			if (CIBlockDocument::IsDocumentLocked($ID, "") && !($_REQUEST['action']=='unlock' && CBPDocument::IsAdmin()))
			{
				$lAdmin->AddUpdateError(GetMessage("IBEL_A_UPDERR_LOCKED", array("#ID#" => $ID)), $TYPE.$ID);
				continue;
			}
		}

		$bPermissions = false;
		//delete and modify can:
		if($strSubIBlockPerm>='W') // only writers
			$bPermissions = true;
		elseif($boolSubWorkFlow)
		{
			//For delete action we have to check all statuses in element history
			$STATUS_PERMISSION = CIBlockElement::WF_GetStatusPermission($arRes["WF_STATUS_ID"], $_REQUEST['action']=="delete"? $ID: false);
			if($STATUS_PERMISSION>=2)
				$bPermissions = true;
		}
		elseif ($boolSubBizproc)
		{
			$bCanWrite = CIBlockDocument::CanUserOperateDocument(
				CBPCanUserOperateOperation::WriteDocument,
				$USER->GetID(),
				$ID,
				array("IBlockId" => $intSubIBlockID, "IBlockPermission" => $strSubIBlockPerm, "UserGroups" => $USER->GetUserGroupArray())
			);
			if ($bCanWrite)
				$bPermissions = true;
		}

		if(!$bPermissions)
		{
			$lAdmin->AddGroupError(GetMessage("IBEL_A_UPDERR3")." (ID:".$ID.")", $ID);
			continue;
		}

		switch($_REQUEST['action'])
		{
		case "delete":
			@set_time_limit(0);
			$DB->StartTransaction();
			$APPLICATION->ResetException();
			if(!CIBlockElement::Delete($ID))
			{
				$DB->Rollback();
				if($ex = $APPLICATION->GetException())
					$lAdmin->AddGroupError(GetMessage("IBLOCK_DELETE_ERROR")." [".$ex->GetString()."]", $ID);
				else
					$lAdmin->AddGroupError(GetMessage("IBLOCK_DELETE_ERROR"), $ID);
			}
			else
			{
				$DB->Commit();
			}
			break;
		case "activate":
		case "deactivate":
			$ob = new CIBlockElement();
			$arFields = Array("ACTIVE"=>($_REQUEST['action']=="activate"?"Y":"N"));
			if(!$ob->Update($ID, $arFields, true))
				$lAdmin->AddGroupError(GetMessage("IBEL_A_UPDERR").$ob->LAST_ERROR, $ID);
			break;
		case "lock":
			CIBlockElement::WF_Lock($ID);
			break;
		case "unlock":
			CIBlockElement::WF_UnLock($ID);
			break;
		}
	}
}
}
$CAdminCalendar_ShowScript = '';
if (true == B_ADMIN_SUBELEMENTS_LIST)
	$CAdminCalendar_ShowScript = CAdminCalendar::ShowScript();

$arHeader = Array();
$arHeader[] = array("id"=>"NAME", "content"=>GetMessage("IBLOCK_FIELD_NAME"), "sort"=>"name", "default"=>true);

$arHeader[] = array("id"=>"ACTIVE", "content"=>GetMessage("IBLOCK_FIELD_ACTIVE"), "sort"=>"active", "default"=>true, "align"=>"center");
$arHeader[] = array("id"=>"DATE_ACTIVE_FROM", "content"=>GetMessage("IBEL_A_ACTFROM"), "sort"=>"date_active_from");
$arHeader[] = array("id"=>"DATE_ACTIVE_TO", "content"=>GetMessage("IBEL_A_ACTTO"), "sort"=>"date_active_to");
$arHeader[] = array("id"=>"SORT", "content"=>GetMessage("IBLOCK_FIELD_SORT"), "sort"=>"sort", "default"=>true, "align"=>"right");
$arHeader[] = array("id"=>"TIMESTAMP_X", "content"=>GetMessage("IBLOCK_FIELD_TIMESTAMP_X"), "sort"=>"timestamp_x");
$arHeader[] = array("id"=>"USER_NAME", "content"=>GetMessage("IBLOCK_FIELD_USER_NAME"), "sort"=>"modified_by");
$arHeader[] = array("id"=>"DATE_CREATE", "content"=>GetMessage("IBLOCK_EL_ADMIN_DCREATE"), "sort"=>"created");
$arHeader[] = array("id"=>"CREATED_USER_NAME", "content"=>GetMessage("IBLOCK_EL_ADMIN_WCREATE2"), "sort"=>"created_by");

$arHeader[] = array("id"=>"CODE", "content"=>GetMessage("IBEL_A_CODE"), "sort"=>"code");
$arHeader[] = array("id"=>"EXTERNAL_ID", "content"=>GetMessage("IBEL_A_EXTERNAL_ID"), "sort"=>"external_id");
$arHeader[] = array("id"=>"TAGS", "content"=>GetMessage("IBEL_A_TAGS"), "sort"=>"tags");

if($boolSubWorkFlow)
{
	$arHeader[] = array("id"=>"WF_STATUS_ID", "content"=>GetMessage("IBLOCK_FIELD_STATUS"), "sort"=>"status", "default"=>true);
	$arHeader[] = array("id"=>"WF_NEW", "content"=>GetMessage("IBEL_A_EXTERNAL_WFNEW"), "sort"=>"");
	$arHeader[] = array("id"=>"LOCK_STATUS", "content"=>GetMessage("IBEL_A_EXTERNAL_LOCK"), "default"=>true);
	$arHeader[] = array("id"=>"LOCKED_USER_NAME", "content"=>GetMessage("IBEL_A_EXTERNAL_LOCK_BY"));
	$arHeader[] = array("id"=>"WF_DATE_LOCK", "content"=>GetMessage("IBEL_A_EXTERNAL_LOCK_WHEN"));
	$arHeader[] = array("id"=>"WF_COMMENTS", "content"=>GetMessage("IBEL_A_EXTERNAL_COM"));
}

$arHeader[] = array("id"=>"ID", "content"=>'ID', "sort"=>"id", "default"=>true, "align"=>"right");
$arHeader[] = array("id"=>"SHOW_COUNTER", "content"=>GetMessage("IBEL_A_EXTERNAL_SHOWS"), "sort"=>"show_counter", "align"=>"right");
$arHeader[] = array("id"=>"SHOW_COUNTER_START", "content"=>GetMessage("IBEL_A_EXTERNAL_SHOW_F"), "sort"=>"show_counter_start", "align"=>"right");
$arHeader[] = array("id"=>"PREVIEW_PICTURE", "content"=>GetMessage("IBEL_A_EXTERNAL_PREV_PIC"), "align"=>"right");
$arHeader[] = array("id"=>"PREVIEW_TEXT", "content"=>GetMessage("IBEL_A_EXTERNAL_PREV_TEXT"));
$arHeader[] = array("id"=>"DETAIL_PICTURE", "content"=>GetMessage("IBEL_A_EXTERNAL_DET_PIC"), "align"=>"center");
$arHeader[] = array("id"=>"DETAIL_TEXT", "content"=>GetMessage("IBEL_A_EXTERNAL_DET_TEXT"));


for($i=0; $i<count($arProps); $i++)
{
	$arFProps = $arProps[$i];
	$arHeader[] = array("id"=>"PROPERTY_".$arFProps['ID'], "content"=>$arFProps['NAME'], "align"=>($arFProps["PROPERTY_TYPE"]=='N'?"right":"left"), "sort" => ($arFProps["MULTIPLE"]!='Y'? "PROPERTY_".$arFProps['ID'] : ""));
}

$arWFStatus = Array();
if($boolSubWorkFlow)
{
	$rsWF = CWorkflowStatus::GetDropDownList("Y");
	while($arWF = $rsWF->GetNext())
		$arWFStatus[$arWF["~REFERENCE_ID"]] = $arWF["~REFERENCE"];
}

if($boolSubCatalog)
{
	if($USER->CanDoOperation('catalog_read') || $USER->CanDoOperation('catalog_price'))
	{
		$arCatGroup = Array();
		$arBaseGroup = CCatalogGroup::GetBaseGroup();
		$dbCatalogGroups = CCatalogGroup::GetList(
				array("SORT" => "ASC"),
				array("CAN_ACCESS" => "Y", "LID"=>LANGUAGE_ID)
			);
		while ($arCatalogGroup = $dbCatalogGroups->Fetch())
		{
			$arHeader[] = array(
				"id" => "CATALOG_GROUP_".$arCatalogGroup["ID"],
				"content" => htmlspecialcharsex(!empty($arCatalogGroup["NAME_LANG"]) ? $arCatalogGroup["NAME_LANG"] : $arCatalogGroup["NAME"]),
				"align" => "right",
				"sort" => "CATALOG_PRICE_".$arCatalogGroup["ID"],
				"default" => ($arBaseGroup['ID'] == $arCatalogGroup["ID"] ? true : false),
			);
			$arCatGroup[$arCatalogGroup["ID"]] = $arCatalogGroup;
		}
		$arCatExtra = Array();

		$db_extras = CExtra::GetList(($by3="NAME"), ($order3="ASC"));
		while ($extras = $db_extras->Fetch())
			$arCatExtra[] = $extras;
		$arHeader[] = array(
			"id" => "CATALOG_QUANTITY",
			"content" => GetMessage("IBEL_CATALOG_QUANTITY"),
			"align" => "right",
			"sort" => "CATALOG_QUANTITY",
		);
		$arHeader[] = array(
			"id" => "CATALOG_QUANTITY_TRACE",
			"content" => GetMessage("IBEL_CATALOG_QUANTITY_TRACE"),
			"align" => "right",
		);
	}
}

if ($boolSubBizproc)
{
	$arWorkflowTemplates = CBPDocument::GetWorkflowTemplatesForDocumentType(array("iblock", "CIBlockDocument", "iblock_".$intSubIBlockID));
	foreach ($arWorkflowTemplates as $arTemplate)
	{
		$arHeader[] = array(
			"id" => "WF_".$arTemplate["ID"],
			"content" => $arTemplate["NAME"],
		);
	}
	$arHeader[] = array(
		"id" => "BIZPROC",
		"content" => GetMessage("IBEL_A_BP_H"),
	);
	$arHeader[] = array(
		"id" => "BP_PUBLISHED",
		"content" => GetMessage("IBLOCK_FIELD_BP_PUBLISHED"),
		"sort" => "status",
		"default" => true,
	);
}

$lAdmin->AddHeaders($arHeader);

$arSelectedFields = $lAdmin->GetVisibleHeaderColumns();

$arSelectedProps = Array();
foreach($arProps as $i => $arProperty)
{
	$k = array_search("PROPERTY_".$arProperty['ID'], $arSelectedFields);
	if($k!==false)
	{
		$arSelectedProps[] = $arProperty;
		if($arProperty["PROPERTY_TYPE"] == "L")
		{
			$arSelect[$arProperty['ID']] = Array();
			$rs = CIBlockProperty::GetPropertyEnum($arProperty['ID']);
			while($ar = $rs->GetNext())
				$arSelect[$arProperty['ID']][$ar["ID"]] = $ar["VALUE"];
		}
		elseif($arProperty["PROPERTY_TYPE"] == "G")
		{
			$arSelect[$arProperty['ID']] = Array();
			$rs = CIBlockSection::GetTreeList(Array("IBLOCK_ID"=>$arProperty["LINK_IBLOCK_ID"]));
			while($ar = $rs->GetNext())
				$arSelect[$arProperty['ID']][$ar["ID"]] = str_repeat(" . ", $ar["DEPTH_LEVEL"]).$ar["NAME"];
		}
		unset($arSelectedFields[$k]);
	}
}

if(!in_array("ID", $arSelectedFields))
	$arSelectedFields[] = "ID";
if(!in_array("CREATED_BY", $arSelectedFields))
	$arSelectedFields[] = "CREATED_BY";

$arSelectedFields[] = "LANG_DIR";
$arSelectedFields[] = "LID";
$arSelectedFields[] = "WF_PARENT_ELEMENT_ID";

if(in_array("LOCKED_USER_NAME", $arSelectedFields))
	$arSelectedFields[] = "WF_LOCKED_BY";
if(in_array("USER_NAME", $arSelectedFields))
	$arSelectedFields[] = "MODIFIED_BY";
if(in_array("CREATED_USER_NAME", $arSelectedFields))
	$arSelectedFields[] = "CREATED_BY";
if(in_array("PREVIEW_TEXT", $arSelectedFields))
	$arSelectedFields[] = "PREVIEW_TEXT_TYPE";
if(in_array("DETAIL_TEXT", $arSelectedFields))
	$arSelectedFields[] = "DETAIL_TEXT_TYPE";

$arSelectedFields[] = "LOCK_STATUS";
$arSelectedFields[] = "WF_NEW";
$arSelectedFields[] = "WF_STATUS_ID";
$arSelectedFields[] = "DETAIL_PAGE_URL";
$arSelectedFields[] = "SITE_ID";
$arSelectedFields[] = "CODE";
$arSelectedFields[] = "EXTERNAL_ID";

$arSelectedFieldsMap = array();
foreach($arSelectedFields as $field)
	$arSelectedFieldsMap[$field] = true;


if(is_array($arCatGroup))
{
	foreach($arCatGroup as $CatalogGroups)
	{
		if(in_array("CATALOG_GROUP_".$CatalogGroups["ID"], $arSelectedFields))
		{
			$arFilter["CATALOG_SHOP_QUANTITY_".$CatalogGroups["ID"]] = 1;
		}
	}
}

//We need javascript not in excel mode
if(($_REQUEST["mode"]=='list' || $_REQUEST["mode"]=='frame') && isset($arCatGroup))
{
	?><script language="JavaScript">
		top.arCatalogShowedGroups = new Array();
	<?
	if(is_array($arCatGroup))
	{
		$i=0;
		foreach($arCatGroup as $CatalogGroups)
		{
			if(in_array("CATALOG_GROUP_".$CatalogGroups["ID"], $arSelectedFields))
			{
				echo "top.arCatalogShowedGroups[".$i."]=".$CatalogGroups["ID"].";\n";
				$i++;
			}
		}
	}
	?>
		top.arExtra = new Array();
		top.arCatalogGroups = new Array();
		top.BaseIndex = "";
		<?
		$i=0;
		foreach($arCatExtra as $CatExtra)
		{
			echo "top.arExtra[".$CatExtra["ID"]."]=".$CatExtra["PERCENTAGE"].";\n";
			$i++;
		}
		$i=0;
		foreach($arCatGroup as $CatGroup)
		{
			if($CatGroup["BASE"]!="Y")
			{
				echo "top.arCatalogGroups[".$i."]=".$CatGroup["ID"].";\n";
				$i++;
			}
			else
				echo "top.BaseIndex=".$CatGroup["ID"].";\n";
		}
		?>
		top.ChangeBasePrice = function(id)
		{
			for(var i = 0, cnt = top.arCatalogShowedGroups.length; i < cnt; i++)
			{
				var pr = top.document.getElementById("CATALOG_PRICE["+id+"]"+"["+top.arCatalogShowedGroups[i]+"]");
				if(pr.disabled)
				{
					var price = top.document.getElementById("CATALOG_PRICE["+id+"]"+"["+top.BaseIndex+"]").value;
					if(price > 0)
					{
						var extraId = document.getElementById("CATALOG_EXTRA["+id+"]"+"["+top.arCatalogShowedGroups[i]+"]").value;
						var esum = parseFloat(price) * (1 + top.arExtra[extraId] / 100);
						var eps = 1.00/Math.pow(10, 6);
						esum = Math.round((esum+eps)*100)/100;
					}
					else
						var esum = "";

					pr.value = esum;
				}
			}
		}

		top.ChangeBaseCurrency = function(id)
		{
			var currency = top.document.getElementById("CATALOG_CURRENCY["+id+"]["+top.BaseIndex+"]");
			for(var i = 0, cnt = top.arCatalogShowedGroups.length; i < cnt; i++)
			{
				var pr = top.document.getElementById("CATALOG_CURRENCY["+id+"]["+top.arCatalogShowedGroups[i]+"]");
				if(pr.disabled)
				{
					pr.selectedIndex = currency.selectedIndex;
				}
			}
		}
	</script>
	<?
}
if (!((false == B_ADMIN_SUBELEMENTS_LIST) && (true == $bCopy)))
{

$wf_status_id = "";
/*if($boolSubWorkFlow && (strpos($find_el_status_id, "-") !== false))
{
	$ar = explode("-", $find_el_status_id);
	$wf_status_id = $ar[1];
}

if($wf_status_id)
{
	$rsData = CIBlockElement::GetList(
		Array($by=>$order),
		$arFilter,
		false,
		false,
		$arSelectedFields
	);
	while($arElement = $rsData->Fetch())
	{
		if($wf_status_id!==false)
		{
			$LAST_ID = CIBlockElement::WF_GetLast($arElement['ID']);
			if($LAST_ID!=$arElement['ID'])
			{
				$rsData2 = CIBlockElement::GetList(
						Array(),
						Array(
							"ID"=>$LAST_ID,
							"SHOW_HISTORY"=>"Y"
							),
						false,
						Array("nTopCount"=>1),
						array("ID","WF_STATUS_ID")
					);
				if($arRes = $rsData2->Fetch())
				{
					if($arRes["WF_STATUS_ID"]!=$wf_status_id)
						continue;
				}
			}
			else
				continue;
		}
		$arResult[]=$arElement;
	}
	$rsData = new CDBResult();
	$rsData->InitFromArray($arResult);
	$rsData = new CAdminResult($rsData, $sTableID);
}
else
{
	$rsData = CIBlockElement::GetList(
		Array($by=>$order),
		$arFilter,
		false,
		//Array("nPageSize"=>CAdminResult::GetNavSize($sTableID)),
		false,
		$arSelectedFields
	);
	$rsData->SetTableID($sTableID);
	$wf_status_id = false;
} */

$rsData = CIBlockElement::GetList(
	Array($by=>$order),
	$arFilter,
	false,
	//Array("nPageSize"=>CAdminResult::GetNavSize($sTableID)),
	false,
	$arSelectedFields
);
$rsData->SetTableID($sTableID);
$wf_status_id = false;

//$rsData->NavStart();
//$lAdmin->NavText($rsData->GetNavPrint(htmlspecialchars($arSubIBlock["ELEMENTS_NAME"])));

function GetElementName($ID)
{
	$ID = IntVal($ID);
	static $cache = array();
	if(!array_key_exists($ID, $cache))
	{
		$rsElement = CIBlockElement::GetList(Array(), Array("ID"=>$ID, "SHOW_HISTORY"=>"Y"), false, false, array("ID","IBLOCK_ID","NAME"));
		$cache[$ID] = $rsElement->GetNext();
	}
	return $cache[$ID];
}
function GetIBlockTypeID($intSubIBlockID)
{
	$intSubIBlockID = IntVal($intSubIBlockID);
	static $cache = array();
	if(!array_key_exists($intSubIBlockID, $cache))
	{
		$rsIBlock = CIBlock::GetByID($intSubIBlockID);
		if(!($cache[$ID] = $rsIBlock->GetNext()))
			$cache[$ID] = array("IBLOCK_TYPE_ID"=>"");
	}
	return $cache[$ID]["IBLOCK_TYPE_ID"];
}

$boolOldOffers = false;
while($arRes = $rsData->NavNext(true, "f_"))
{
	$arRes_orig = $arRes;
	// in workflow mode show latest changes
 	if($boolSubWorkFlow)
	{
		$LAST_ID = CIBlockElement::WF_GetLast($arRes['ID']);
		if($LAST_ID!=$arRes['ID'])
		{
			$rsData2 = CIBlockElement::GetList(
					Array(),
					Array(
						"ID"=>$LAST_ID,
						"SHOW_HISTORY"=>"Y"
						),
					false,
					Array("nTopCount"=>1),
					$arSelectedFields
				);
			if(isset($arCatGroup))
			{
				$arRes_tmp = Array();
				foreach($arRes as $vv => $vval)
				{
					if(substr($vv, 0, 8) == "CATALOG_")
						$arRes_tmp[$vv] = $arRes[$vv];
				}
			}

			$arRes = $rsData2->NavNext(true, "f_");
			if(isset($arCatGroup))
				$arRes = array_merge($arRes, $arRes_tmp);

			$f_ID = $arRes_orig["ID"];
		}
		$lockStatus = $arRes_orig['LOCK_STATUS'];
	}
	elseif($boolSubBizproc)
	{
		$lockStatus = CIBlockDocument::IsDocumentLocked($f_ID, "") ? "red" : "green";
	}
	else
	{
		$lockStatus = "";
	}

	$edit_url = '/bitrix/admin/iblock_subelement_edit.php?WF=Y&type='.urlencode($strSubIBlockType).'&IBLOCK_ID='.$intSubIBlockID.'&lang='.LANGUAGE_ID.'&PRODUCT_ID='.$ID.'&ID='.$arRes_orig['ID'].'&TMP_ID='.$strSubTMP_ID.$sThisSectionUrl;
	$row =& $lAdmin->AddRow($f_ID, $arRes, $edit_url, GetMessage("IB_SE_L_EDIT_ELEMENT"), true);

	$row->AddViewField("ID", $f_ID);
	$row->AddCheckField("ACTIVE");
	$row->AddInputField("NAME", Array('size'=>'35'));
	$row->AddViewField("NAME", '<div class="iblock_menu_icon_elements"></div>'.$f_NAME);
	$row->AddInputField("SORT", Array('size'=>'3'));
	$row->AddInputField("CODE");
	$row->AddInputField("EXTERNAL_ID");
	if(CModule::IncludeModule('search'))
	{
		$row->AddViewField("TAGS", $f_TAGS);
		$row->AddEditField("TAGS", InputTags("FIELDS[".$f_ID."][TAGS]", $arRes["TAGS"], $arSubIBlock["SITE_ID"]));
	}
	else
	{
		$row->AddInputField("TAGS");
	}
	$row->AddCalendarField("DATE_ACTIVE_FROM");
	$row->AddCalendarField("DATE_ACTIVE_TO");

	if($f_LOCKED_USER_NAME)
		$row->AddViewField("LOCKED_USER_NAME", $f_LOCKED_USER_NAME);
	if($f_USER_NAME)
		$row->AddViewField("USER_NAME", $f_USER_NAME);
	$row->AddViewField("CREATED_USER_NAME", $f_CREATED_USER_NAME);

	if($arWFStatus)
	{
		$row->AddSelectField("WF_STATUS_ID", $arWFStatus);
		if($arRes_orig['WF_NEW']=='Y' || $arRes['WF_STATUS_ID']=='1')
			$row->AddViewField("WF_STATUS_ID", htmlspecialcharsex($arWFStatus[$arRes['WF_STATUS_ID']]));
		else
			$row->AddViewField("WF_STATUS_ID", htmlspecialcharsex($arWFStatus[$arRes['WF_STATUS_ID']]).' / '.htmlspecialcharsex($arWFStatus[$arRes_orig['WF_STATUS_ID']]));
	}

	if(array_key_exists("PREVIEW_PICTURE", $arSelectedFieldsMap))
	{
		$row->AddViewField("PREVIEW_PICTURE", CFile::ShowFile($arRes['PREVIEW_PICTURE'], 100000, 50, 50, true));
	}
	if(array_key_exists("PREVIEW_TEXT", $arSelectedFieldsMap))
	{
		$row->AddViewField("PREVIEW_TEXT", ($arRes["PREVIEW_TEXT_TYPE"]=="text" ? htmlspecialcharsex($arRes["PREVIEW_TEXT"]) : HTMLToTxt($arRes["PREVIEW_TEXT"])));
		$sHTML = '<input type="radio" name="FIELDS['.$f_ID.'][PREVIEW_TEXT_TYPE]" value="text" id="'.$f_ID.'PREVIEWtext"';
		if($arRes["PREVIEW_TEXT_TYPE"]!="html")
			$sHTML .= ' checked';
		$sHTML .= '><label for="'.$f_ID.'PREVIEWtext">text</label> /';
		$sHTML .= '<input type="radio" name="FIELDS['.$f_ID.'][PREVIEW_TEXT_TYPE]" value="html" id="'.$f_ID.'PREVIEWhtml"';
		if($arRes["PREVIEW_TEXT_TYPE"]=="html")
			$sHTML .= ' checked';
		$sHTML .= '><label for="'.$f_ID.'PREVIEWhtml">html</label><br>';
		$sHTML .= '<textarea rows="10" cols="50" name="FIELDS['.$f_ID.'][PREVIEW_TEXT]">'.htmlspecialcharsex($arRes["PREVIEW_TEXT"]).'</textarea>';
		$row->AddEditField("PREVIEW_TEXT", $sHTML);
	}
	if(array_key_exists("DETAIL_PICTURE", $arSelectedFieldsMap))
	{
		$row->AddViewField("DETAIL_PICTURE", CFile::ShowFile($arRes['DETAIL_PICTURE'], 100000, 50, 50, true));
	}
	if(array_key_exists("DETAIL_TEXT", $arSelectedFieldsMap))
	{
		$row->AddViewField("DETAIL_TEXT", ($arRes["DETAIL_TEXT_TYPE"]=="text" ? htmlspecialcharsex($arRes["DETAIL_TEXT"]) : HTMLToTxt($arRes["DETAIL_TEXT"])));
		$sHTML = '<input type="radio" name="FIELDS['.$f_ID.'][DETAIL_TEXT_TYPE]" value="text" id="'.$f_ID.'DETAILtext"';
		if($arRes["DETAIL_TEXT_TYPE"]!="html")
			$sHTML .= ' checked';
		$sHTML .= '><label for="'.$f_ID.'DETAILtext">text</label> /';
		$sHTML .= '<input type="radio" name="FIELDS['.$f_ID.'][DETAIL_TEXT_TYPE]" value="html" id="'.$f_ID.'DETAILhtml"';
		if($arRes["DETAIL_TEXT_TYPE"]=="html")
			$sHTML .= ' checked';
		$sHTML .= '><label for="'.$f_ID.'DETAILhtml">html</label><br>';

		$sHTML .= '<textarea rows="10" cols="50" name="FIELDS['.$f_ID.'][DETAIL_TEXT]">'.htmlspecialcharsex($arRes["DETAIL_TEXT"]).'</textarea>';
		$row->AddEditField("DETAIL_TEXT", $sHTML);
	}
	if($boolSubWorkFlow || $boolSubBizproc)
	{
		$lamp = "/bitrix/images/workflow/".$lockStatus.".gif";
		if($lockStatus=="green")
			$lamp_alt = GetMessage("IBLOCK_GREEN_ALT");
		elseif($lockStatus=="yellow")
			$lamp_alt = GetMessage("IBLOCK_YELLOW_ALT");
		else
			$lamp_alt = GetMessage("IBLOCK_RED_ALT");


		if($lockStatus=='red' && $arRes_orig['LOCKED_USER_NAME']!='')
			$row->AddViewField("LOCK_STATUS", '<table cellpadding="0" cellspacing="0" border="0"><tr><td><img hspace="4" src="'.$lamp.'" alt="'.htmlspecialchars($lamp_alt).'" title="'.htmlspecialchars($lamp_alt).'" /></td><td>'.$arRes_orig['LOCKED_USER_NAME'].$unlock.'</td></tr></table>');
		else
			$row->AddViewField("LOCK_STATUS", '<img src="'.$lamp.'" hspace="4" alt="'.htmlspecialchars($lamp_alt).'" title="'.htmlspecialchars($lamp_alt).'" />');
	}

	if($boolSubBizproc)
		$row->AddCheckField("BP_PUBLISHED", false);

	$arProperties = array();
	if(count($arSelectedProps) > 0)
	{
		$rsProperties = CIBlockElement::GetProperty($intSubIBlockID, $arRes["ID"]);
		while($ar = $rsProperties->GetNext())
		{
			if(!array_key_exists($ar["ID"], $arProperties))
				$arProperties[$ar["ID"]] = array();
			$arProperties[$ar["ID"]][$ar["PROPERTY_VALUE_ID"]] = $ar;
		}
	}

	foreach($arSelectedProps as $aProp)
	{
		$arViewHTML = array();
		$arEditHTML = array();
		if(strlen($aProp["USER_TYPE"])>0)
			$arUserType = CIBlockProperty::GetUserType($aProp["USER_TYPE"]);
		else
			$arUserType = array();
		$max_file_size_show=100000;

		$last_property_id = false;
		foreach($arProperties[$aProp["ID"]] as $prop_id => $prop)
		{
			$prop['PROPERTY_VALUE_ID'] = intval($prop['PROPERTY_VALUE_ID']);
			$VALUE_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].']['.$prop['PROPERTY_VALUE_ID'].'][VALUE]';
			$DESCR_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].']['.$prop['PROPERTY_VALUE_ID'].'][DESCRIPTION]';
			//View part
			if(array_key_exists("GetAdminListViewHTML", $arUserType))
			{
				$arViewHTML[] = call_user_func_array($arUserType["GetAdminListViewHTML"],
					array(
						$prop,
						array(
							"VALUE" => $prop["~VALUE"],
							"DESCRIPTION" => $prop["~DESCRIPTION"]
						),
						array(
							"VALUE" => $VALUE_NAME,
							"DESCRIPTION" => $DESCR_NAME,
							"MODE"=>"iblock_element_admin",
							"FORM_NAME"=>"form_".$sTableID,
						),
					));
			}
			elseif($prop['PROPERTY_TYPE']=='N')
				$arViewHTML[] = $prop["VALUE"];
			elseif($prop['PROPERTY_TYPE']=='S')
				$arViewHTML[] = $prop["VALUE"];
			elseif($prop['PROPERTY_TYPE']=='L')
				$arViewHTML[] = $prop["VALUE_ENUM"];
			elseif($prop['PROPERTY_TYPE']=='F')
			{
				$arViewHTML[] = CFile::ShowFile($prop["VALUE"], 100000, 50, 50, true);
			}
			elseif($prop['PROPERTY_TYPE']=='G')
			{
				if(intval($prop["VALUE"])>0)
				{
					$rsSection = CIBlockSection::GetList(Array(), Array("ID" => $prop["VALUE"]));
					if($arSection = $rsSection->GetNext())
					{
						$arViewHTML[] = $arSection['NAME'].
						' ['.$arSection['ID'].']';
					}
				}
			}
			elseif($prop['PROPERTY_TYPE']=='E')
			{
				if($t = GetElementName($prop["VALUE"]))
				{
					$arViewHTML[] = $t['NAME'].
					' ['.$t['ID'].']';
				}
			}
			//Edit Part
			$bUserMultiple = $prop["MULTIPLE"] == "Y" &&  array_key_exists("GetPropertyFieldHtmlMulty", $arUserType);
			if($bUserMultiple)
			{
				if($last_property_id != $prop["ID"])
				{
					$VALUE_NAME = 'FIELDS['.$f_TYPE.$f_ID.'][PROPERTY_'.$prop['ID'].']';
					$arEditHTML[] = call_user_func_array($arUserType["GetPropertyFieldHtmlMulty"], array(
						$prop,
						$arProperties[$prop["ID"]],
						array(
							"VALUE" => $VALUE_NAME,
							"MODE"=>"iblock_element_admin",
							"FORM_NAME"=>"form_".$sTableID,
						)
					));
				}
			}
			elseif(array_key_exists("GetPropertyFieldHtml", $arUserType))
			{
				$arEditHTML[] = call_user_func_array($arUserType["GetPropertyFieldHtml"],
					array(
						$prop,
						array(
							"VALUE" => $prop["VALUE"],
							"DESCRIPTION" => $prop["DESCRIPTION"],
						),
						array(
							"VALUE" => $VALUE_NAME,
							"DESCRIPTION" => $DESCR_NAME,
							"MODE"=>"iblock_element_admin",
							"FORM_NAME"=>"form_".$sTableID,
						),
					));
			}
			elseif($prop['PROPERTY_TYPE']=='N' || $prop['PROPERTY_TYPE']=='S')
			{
				if($prop["ROW_COUNT"] > 1)
					$html = '<textarea name="'.$VALUE_NAME.'" cols="'.$prop["COL_COUNT"].'" rows="'.$prop["ROW_COUNT"].'">'.$prop["VALUE"].'</textarea>';
				else
					$html = '<input type="text" name="'.$VALUE_NAME.'" value="'.$prop["VALUE"].'" size="'.$prop["COL_COUNT"].'">';
				if($prop["WITH_DESCRIPTION"] == "Y")
					$html .= ' <span title="'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_DESC").'">'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_DESC_1").
						'<input type="text" name="'.$DESCR_NAME.'" value="'.$prop["DESCRIPTION"].'" size="18"></span>';
				$arEditHTML[] = $html;
			}
			elseif($prop['PROPERTY_TYPE']=='L' && ($last_property_id!=$prop["ID"]))
			{
				$VALUE_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].'][]';
				$arValues = array();
				foreach($arProperties[$prop["ID"]] as $g_prop)
				{
					$g_prop = intval($g_prop["VALUE"]);
					if($g_prop > 0)
						$arValues[$g_prop] = $g_prop;
				}
				if($prop['LIST_TYPE']=='C')
				{
					if($prop['MULTIPLE'] == "Y" || count($arSelect[$prop['ID']]) == 1)
					{
						$html = '<input type="hidden" name="'.$VALUE_NAME.'" value="">';
						foreach($arSelect[$prop['ID']] as $value => $display)
						{
							$html .= '<input type="checkbox" name="'.$VALUE_NAME.'" id="'.$prop["PROPERTY_VALUE_ID"]."_".$value.'" value="'.$value.'"';
							if(array_key_exists($value, $arValues))
								$html .= ' checked';
							$html .= '>&nbsp;<label for="'.$prop["PROPERTY_VALUE_ID"]."_".$value.'">'.$display.'</label><br>';
						}
					}
					else
					{
						$html = '<input type="radio" name="'.$VALUE_NAME.'" id="'.$prop["PROPERTY_VALUE_ID"].'_none" value=""';
						if(count($arValues) < 1)
							$html .= ' checked';
						$html .= '>&nbsp;<label for="'.$prop["PROPERTY_VALUE_ID"].'_none">'.GetMessage("IBLOCK_ELEMENT_EDIT_NOT_SET").'</label><br>';
						foreach($arSelect[$prop['ID']] as $value => $display)
						{
							$html .= '<input type="radio" name="'.$VALUE_NAME.'" id="'.$prop["PROPERTY_VALUE_ID"]."_".$value.'" value="'.$value.'"';
							if(array_key_exists($value, $arValues))
								$html .= ' checked';
							$html .= '>&nbsp;<label for="'.$prop["PROPERTY_VALUE_ID"]."_".$value.'">'.$display.'</label><br>';
						}
					}
				}
				else
				{
					$html = '<select name="'.$VALUE_NAME.'" size="'.$prop["MULTIPLE_CNT"].'" '.($prop["MULTIPLE"]=="Y"?"multiple":"").'>';
					$html .= '<option value=""'.(count($arValues) < 1? ' selected': '').'>'.GetMessage("IBLOCK_ELEMENT_EDIT_NOT_SET").'</option>';
					foreach($arSelect[$prop['ID']] as $value => $display)
					{
						$html .= '<option value="'.$value.'"';
						if(array_key_exists($value, $arValues))
							$html .= ' selected';
						$html .= '>'.$display.'</option>'."\n";
					}
					$html .= "</select>\n";
				}
				$arEditHTML[] = $html;
			}
			elseif($prop['PROPERTY_TYPE']=='F')
			{
				//$html = CFile::InputFile($VALUE_NAME, $prop["COL_COUNT"], $prop["VALUE"], false, 0, "").
				$html = ''.
					"<br>".
					CFile::ShowFile($prop["VALUE"], $max_file_size_show, 400, 400, true).
					"<br>";
				if($prop["WITH_DESCRIPTION"]=="Y")
					$html .= ' <span title="'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_DESC").'">'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_DESC_1").'<input type="text" name="'.$DESCR_NAME.'" value="'.$prop["DESCRIPTION"].'" size="18"></span>';
				else
					$html .= '<input type="hidden" name="'.$DESCR_NAME.'" value="'.$prop["DESCRIPTION"].'">';
				$html = '';
				$arEditHTML[] = $html;
			}
			elseif(($prop['PROPERTY_TYPE']=='G') && ($last_property_id!=$prop["ID"]))
			{
				$VALUE_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].'][]';
				$arValues = array();
				foreach($arProperties[$prop["ID"]] as $g_prop)
				{
					$g_prop = intval($g_prop["VALUE"]);
					if($g_prop > 0)
						$arValues[$g_prop] = $g_prop;
				}
				$html = '<select name="'.$VALUE_NAME.'" size="'.$prop["MULTIPLE_CNT"].'" '.($prop["MULTIPLE"]=="Y"?"multiple":"").'>';
				$html .= '<option value=""'.(count($arValues) < 1? ' selected': '').'>'.GetMessage("IBLOCK_ELEMENT_EDIT_NOT_SET").'</option>';
				foreach($arSelect[$prop['ID']] as $value => $display)
				{
					$html .= '<option value="'.$value.'"';
					if(array_key_exists($value, $arValues))
						$html .= ' selected';
					$html .= '>'.$display.'</option>'."\n";
				}
				$html .= "</select>\n";
				$arEditHTML[] = $html;
			}
			elseif($prop['PROPERTY_TYPE']=='E')
			{
				$VALUE_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].']['.$prop['PROPERTY_VALUE_ID'].']';
				if($t = GetElementName($prop["VALUE"]))
				{
					$arEditHTML[] = '<input type="text" name="'.$VALUE_NAME.'" id="'.$VALUE_NAME.'" value="'.$prop["VALUE"].'" size="5">'.
					'<input type="button" value="..." onClick="jsUtils.OpenWindow(\'iblock_element_search.php?lang='.LANG.'&amp;IBLOCK_ID='.$prop["LINK_IBLOCK_ID"].'&amp;n='.urlencode($VALUE_NAME).'\', 600, 500);">'.
					'&nbsp;<span id="sp_'.$VALUE_NAME.'" >'.$t['NAME'].'</span>';
				}
				else
				{
					$arEditHTML[] = '<input type="text" name="'.$VALUE_NAME.'" id="'.$VALUE_NAME.'" value="" size="5">'.
					'<input type="button" value="..." onClick="jsUtils.OpenWindow(\'iblock_element_search.php?lang='.LANG.'&amp;IBLOCK_ID='.$prop["LINK_IBLOCK_ID"].'&amp;n='.urlencode($VALUE_NAME).'\', 600, 500);">'.
					'&nbsp;<span id="sp_'.$VALUE_NAME.'" ></span>';
				}
			}
			$last_property_id = $prop['ID'];
		}
		$table_id = md5($f_ID.':'.$aProp['ID']);
		if($aProp["MULTIPLE"] == "Y")
		{
			$VALUE_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].'][n0][VALUE]';
			$DESCR_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].'][n0][DESCRIPTION]';
			if(array_key_exists("GetPropertyFieldHtmlMulty", $arUserType))
			{
			}
			elseif(array_key_exists("GetPropertyFieldHtml", $arUserType))
			{
				$arEditHTML[] = call_user_func_array($arUserType["GetPropertyFieldHtml"],
					array(
						$prop,
						array(
							"VALUE" => "",
							"DESCRIPTION" => "",
						),
						array(
							"VALUE" => $VALUE_NAME,
							"DESCRIPTION" => $DESCR_NAME,
							"MODE"=>"iblock_element_admin",
							"FORM_NAME"=>"form_".$sTableID,
						),
					));
			}
			elseif($prop['PROPERTY_TYPE']=='N' || $prop['PROPERTY_TYPE']=='S')
			{
				if($prop["ROW_COUNT"] > 1)
					$html = '<textarea name="'.$VALUE_NAME.'" cols="'.$prop["COL_COUNT"].'" rows="'.$prop["ROW_COUNT"].'"></textarea>';
				else
					$html = '<input type="text" name="'.$VALUE_NAME.'" value="" size="'.$prop["COL_COUNT"].'">';
				if($prop["WITH_DESCRIPTION"] == "Y")
					$html .= ' <span title="'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_DESC").'">'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_DESC_1").'<input type="text" name="'.$DESCR_NAME.'" value="" size="18"></span>';
				$arEditHTML[] = $html;
			}
			elseif($prop['PROPERTY_TYPE']=='F')
			{
/*				$html = CFile::InputFile($VALUE_NAME, $prop["COL_COUNT"], "", false, 0, "").
					"<br>".
					CFile::ShowFile("", $max_file_size_show, 400, 400, true).
					"<br>";
				if($prop["WITH_DESCRIPTION"]=="Y")
					$html .= ' <span title="'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_DESC").'">'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_DESC_1").'<input type="text" name="'.$DESCR_NAME.'" value="" size="18"></span>';
				else
					$html .= '<input type="hidden" name="'.$DESCR_NAME.'" value="'.$prop["DESCRIPTION"].'">'; */
				$html = CFile::ShowFile("", $max_file_size_show, 400, 400, true).
					"<br>";
				$arEditHTML[] = $html;
			}
			elseif($prop['PROPERTY_TYPE']=='E')
			{
				$VALUE_NAME = 'FIELDS['.$f_ID.'][PROPERTY_'.$prop['ID'].'][n0]';
				$arEditHTML[] = '<input type="text" name="'.$VALUE_NAME.'" id="'.$VALUE_NAME.'" value="" size="5">'.
					'<input type="button" value="..." onClick="jsUtils.OpenWindow(\'iblock_element_search.php?lang='.LANG.'&amp;IBLOCK_ID='.$prop["LINK_IBLOCK_ID"].'&amp;n='.urlencode($VALUE_NAME).'\', 600, 500);">'.
					'&nbsp;<span id="sp_'.$VALUE_NAME.'" ></span>';
			}

			if($prop["PROPERTY_TYPE"]!=="G" && $prop["PROPERTY_TYPE"]!=="L" && !$bUserMultiple)
				$arEditHTML[] = '<input type="button" value="'.GetMessage("IBLOCK_ELEMENT_EDIT_PROP_ADD").'" onClick="addNewRow(\'tb'.$table_id.'\')">';
		}
		if(count($arViewHTML) > 0)
			$row->AddViewField("PROPERTY_".$aProp['ID'], implode(" / ", $arViewHTML)."&nbsp;");
		if(count($arEditHTML) > 0)
			$row->AddEditField("PROPERTY_".$aProp['ID'], '<table id="tb'.$table_id.'" border=0 cellpadding=0 cellspacing=0><tr><td nowrap>'.implode("</td></tr><tr><td nowrap>", $arEditHTML).'</td></tr></table>');
	}

	$row->AddInputField("CATALOG_QUANTITY");
	$row->AddCheckField("CATALOG_QUANTITY_TRACE");
	if(isset($arCatGroup))
	{
		foreach($arCatGroup as $CatGroup)
		{
			$price = "";
			$sHTML = "";
			$selectCur = "";
			if(CModule::IncludeModule("currency"))
			{
				$price = CurrencyFormat($arRes["CATALOG_PRICE_".$CatGroup["ID"]],$arRes["CATALOG_CURRENCY_".$CatGroup["ID"]]);
				if($USER->CanDoOperation('catalog_price'))
				{
					$db_curr = CCurrency::GetList(($by1="sort"), ($order1="asc"));
					$selectCur = '<select name="CATALOG_CURRENCY['.$arRes["ID"].']['.$CatGroup["ID"].']" id="CATALOG_CURRENCY['.$f_ID.']['.$CatGroup["ID"].']"';
					if(IntVal($arRes["CATALOG_EXTRA_ID_".$CatGroup["ID"]])>0)
						$selectCur .= ' disabled="disabled" readonly="readonly"';
					if($CatGroup["BASE"]=="Y")
						$selectCur .= ' OnChange="ChangeBaseCurrency('.$f_ID.')"';
					$selectCur .= '>';
					while ($curr = $db_curr->Fetch())
					{
						$selectCur .= '<option value="'.htmlspecialcharsex($curr["CURRENCY"]).'"';
						if($curr["CURRENCY"]==$arRes["CATALOG_CURRENCY_".$CatGroup["ID"]])
							$selectCur .= ' selected';
						$selectCur .= '>'.htmlspecialcharsex($curr["CURRENCY"]).'</option>';
					}
					$selectCur .= '</select>';
				}
			}
			else
				$price = $arRes["CATALOG_PRICE_".$CatGroup["ID"]]." ".$arRes["CATALOG_CURRENCY_".$CatGroup["ID"]];

				$row->AddViewField("CATALOG_GROUP_".$CatGroup["ID"], $price);
			if($USER->CanDoOperation('catalog_price'))
			{
				$sHTML = '<input type="text" size="5" id="CATALOG_PRICE['.$f_ID.']['.$CatGroup["ID"].']" name="CATALOG_PRICE['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_PRICE_".$CatGroup["ID"]].'"';
				if($CatGroup["BASE"]=="Y")
					$sHTML .= ' OnChange="ChangeBasePrice('.$f_ID.')"';
				if(IntVal($arRes["CATALOG_EXTRA_ID_".$CatGroup["ID"]])>0)
					$sHTML .= ' disabled readonly';
				$sHTML .= '> '.$selectCur;
				if(IntVal($arRes["CATALOG_EXTRA_ID_".$CatGroup["ID"]])>0)
					$sHTML .= '<input type="hidden" id="CATALOG_EXTRA['.$f_ID.']['.$CatGroup["ID"].']" name="CATALOG_EXTRA['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_EXTRA_ID_".$CatGroup["ID"]].'">';

				$sHTML .= '<input type="hidden" name="CATALOG_old_PRICE['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_PRICE_".$CatGroup["ID"]].'">';
				$sHTML .= '<input type="hidden" name="CATALOG_old_CURRENCY['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_CURRENCY_".$CatGroup["ID"]].'">';
				$sHTML .= '<input type="hidden" name="CATALOG_PRICE_ID['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_PRICE_ID_".$CatGroup["ID"]].'">';
				$sHTML .= '<input type="hidden" name="CATALOG_QUANTITY_FROM['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_QUANTITY_FROM_".$CatGroup["ID"]].'">';
				$sHTML .= '<input type="hidden" name="CATALOG_QUANTITY_TO['.$f_ID.']['.$CatGroup["ID"].']" value="'.$arRes["CATALOG_QUANTITY_TO_".$CatGroup["ID"]].'">';

				$row->AddEditField("CATALOG_GROUP_".$CatGroup["ID"], $sHTML);
			}
		}
	}

	if ($boolSubBizproc)
	{
		$arDocumentStates = CBPDocument::GetDocumentStates(
			array("iblock", "CIBlockDocument", "iblock_".$intSubIBlockID),
			array("iblock", "CIBlockDocument", $f_ID)
		);

		$arRes["CURENT_USER_GROUPS"] = $GLOBALS["USER"]->GetUserGroupArray();
		if ($arRes["CREATED_BY"] == $GLOBALS["USER"]->GetID())
			$arRes["CURENT_USER_GROUPS"][] = "Author";

		$arStr = array();
		$arStr1 = array();
		foreach ($arDocumentStates as $kk => $vv)
		{
			$canViewWorkflow = CIBlockDocument::CanUserOperateDocument(
				CBPCanUserOperateOperation::ViewWorkflow,
				$GLOBALS["USER"]->GetID(),
				$f_ID,
				array("IBlockPermission" => $strSubIBlockPerm, "AllUserGroups" => $arRes["CURENT_USER_GROUPS"], "DocumentStates" => $arDocumentStates, "WorkflowId" => $kk)
			);
			if (!$canViewWorkflow)
				continue;

			$arStr1[$vv["TEMPLATE_ID"]] = $vv["TEMPLATE_NAME"];
			$arStr[$vv["TEMPLATE_ID"]] .= "<a href=\"bizproc_log.php?ID=".$kk."\">".(strlen($vv["STATE_TITLE"]) > 0 ? $vv["STATE_TITLE"] : $vv["STATE_NAME"])."</a><br />";

			if (strlen($vv["ID"]) > 0)
			{
				$arTasks = CBPDocument::GetUserTasksForWorkflow($USER->GetID(), $vv["ID"]);
				foreach ($arTasks as $arTask)
				{
					$arStr[$vv["TEMPLATE_ID"]] .= GetMessage("IBEL_A_BP_TASK").":<br /><a href=\"bizproc_task.php?id=".$arTask["ID"]."\" title=\"".$arTask["DESCRIPTION"]."\">".$arTask["NAME"]."</a><br /><br />";
				}
			}
		}

		$str = "";
		foreach ($arStr as $k => $v)
		{
			$row->AddViewField("WF_".$k, $v);
			$str .= "<b>".(strlen($arStr1[$k]) > 0 ? $arStr1[$k] : GetMessage("IBEL_A_BP_PROC"))."</b>:<br />".$v."<br />";
		}

		$row->AddViewField("BIZPROC", $str);
	}

	$arActions = Array();


	if($boolSubWorkFlow)
	{
		$STATUS_PERMISSION = 2;
		if($arRes["WF_STATUS_ID"]>1)
			$STATUS_PERMISSION = CIBlockElement::WF_GetStatusPermission($arRes["WF_STATUS_ID"]);

		$arUnLock = Array(
				"ICON"=>"unlock",
				"TEXT"=>GetMessage("IBEL_A_UNLOCK"),
				"TITLE"=>GetMessage("IBLOCK_UNLOCK_ALT"),
				"ACTION"=>"if(confirm('".GetMessage("IBLOCK_UNLOCK_CONFIRM")."')) ".$lAdmin->ActionDoGroup($arRes_orig['ID'], "unlock", $sThisSectionUrl)
			);

		if($arRes_orig['LOCK_STATUS']=="red" && CWorkflow::IsAdmin())
		{
			$arActions[] = $arUnLock;
		}
		elseif($STATUS_PERMISSION>=2 || $strSubIBlockPerm>="W")
		{
			if($arRes_orig['LOCK_STATUS']=="yellow")
			{
				$arActions[] = $arUnLock;
				$arActions[] = array("SEPARATOR"=>true);
			}

			if($arRes_orig['WF_NEW']=="Y") // not published, under workflow
			{
				$arActions[] = array(
					"ICON"=>"edit",
					"TEXT"=>GetMessage("IBEL_A_CHANGE"),
					"DEFAULT" => true,
					//"ACTION"=>"/bitrix/admin/iblock_subelement_edit.php?WF=Y&type=".$strSubIBlockType."&IBLOCK_ID=".$intSubIBlockID."&lang=".LANGUAGE_ID."&PRODUCT_ID=".$ID."&ID=".$arRes_orig['ID'].$sThisSectionUrl,
					"ACTION"=>"javascript:(new BX.CAdminDialog({
			    		'content_url': '/bitrix/admin/iblock_subelement_edit.php?WF=Y&type=".CUtil::JSEscape(htmlspecialchars($strSubIBlockType))."&IBLOCK_ID=".$intSubIBlockID."&lang=".LANGUAGE_ID."&PRODUCT_ID=".$ID."&ID=".$arRes_orig['ID']."&TMP_ID=".urlencode($strSubTMP_ID).$sThisSectionUrl."',
			    		'content_post': '&bxpublic=Y',
						'draggable': true,
						'resizable': true,
						'buttons': [BX.CAdminDialog.btnSave, BX.CAdminDialog.btnCancel]
						})).Show();",
				);

				$arActions[] = array(
					"ICON"=>"copy",
					"TEXT"=>GetMessage("IBEL_A_COPY_ELEMENT"),
					"ACTION"=>"javascript:(new BX.CAdminDialog({
			    		'content_url': '/bitrix/admin/iblock_subelement_edit.php?WF=Y&type=".CUtil::JSEscape(htmlspecialchars($strSubIBlockType))."&IBLOCK_ID=".$intSubIBlockID."&lang=".LANGUAGE_ID."&PRODUCT_ID=".$ID."&ID=".$arRes_orig['ID']."&TMP_ID=".urlencode($strSubTMP_ID)."&action=copy".$sThisSectionUrl."',
			    		'content_post': '&bxpublic=Y',
						'draggable': true,
						'resizable': true,
						'buttons': [BX.CAdminDialog.btnSave, BX.CAdminDialog.btnCancel]
						})).Show();",
				);


				$arActions[] = array("SEPARATOR"=>true);
		$arActions[] = array(
			"ICON"=>"delete",
			"TEXT"=>GetMessage('MAIN_DELETE'),
			"TITLE"=>GetMessage("IBLOCK_DELETE_ALT"),
			"ACTION"=>"if(confirm('".GetMessage('IBLOCK_CONFIRM_DEL_MESSAGE')."')) ".$lAdmin->ActionDoGroup($arRes_orig['ID'], "delete", $sThisSectionUrl)
		);
			}
			elseif($arRes["WF_STATUS_ID"]>1) // published but changed
			{
				$arActions[] = array(
					"ICON"=>"edit",
					"TEXT"=>GetMessage("IBEL_A_CHANGE"),
					"DEFAULT" => true,
					//"ACTION"=>"/bitrix/admin/iblock_subelement_edit.php?WF=Y&type=".$strSubIBlockType."&IBLOCK_ID=".$intSubIBlockID."&lang=".LANGUAGE_ID."&PRODUCT_ID=".$ID."&ID=".$arRes_orig['ID'].$sThisSectionUrl,
					"ACTION"=>"javascript:(new BX.CAdminDialog({
			    		'content_url': '/bitrix/admin/iblock_subelement_edit.php?WF=Y&type=".CUtil::JSEscape(htmlspecialchars($strSubIBlockType))."&IBLOCK_ID=".$intSubIBlockID."&lang=".LANGUAGE_ID."&PRODUCT_ID=".$ID."&ID=".$arRes_orig['ID']."&TMP_ID=".urlencode($strSubTMP_ID).$sThisSectionUrl."',
			    		'content_post': '&bxpublic=Y',
						'draggable': true,
						'resizable': true,
						'buttons': [BX.CAdminDialog.btnSave, BX.CAdminDialog.btnCancel]
						})).Show();",
				);

				$arActions[] = array(
					"ICON"=>"copy",
					"TEXT"=>GetMessage("IBEL_A_COPY_ELEMENT"),
					"ACTION"=>"javascript:(new BX.CAdminDialog({
			    		'content_url': '/bitrix/admin/iblock_subelement_edit.php?WF=Y&type=".CUtil::JSEscape(htmlspecialchars($strSubIBlockType))."&IBLOCK_ID=".$intSubIBlockID."&lang=".LANGUAGE_ID."&PRODUCT_ID=".$ID."&ID=".$arRes_orig['ID']."&TMP_ID=".urlencode($strSubTMP_ID)."&action=copy".$sThisSectionUrl."',
			    		'content_post': '&bxpublic=Y',
						'draggable': true,
						'resizable': true,
						'buttons': [BX.CAdminDialog.btnSave, BX.CAdminDialog.btnCancel]
						})).Show();",
				);

				if($strSubIBlockPerm>="W")
				{
					$arActions[] = array("SEPARATOR"=>true);

		$arActions[] = array(
			"ICON"=>"delete",
			"TEXT"=>GetMessage('MAIN_DELETE'),
			"TITLE"=>GetMessage("IBLOCK_DELETE_ALT"),
			"ACTION"=>"if(confirm('".GetMessage('IBLOCK_CONFIRM_DEL_MESSAGE')."')) ".$lAdmin->ActionDoGroup($arRes_orig['ID'], "delete", $sThisSectionUrl)
		);
				}
			}
			else //published
			{
				$arActions[] = array(
					"ICON"=>"edit",
					"TEXT"=>GetMessage("IBEL_A_CHANGE"),
					"DEFAULT" => true,
					//"ACTION"=>"/bitrix/admin/iblock_subelement_edit.php?WF=Y&type=".$strSubIBlockType."&IBLOCK_ID=".$intSubIBlockID."&lang=".LANGUAGE_ID."&PRODUCT_ID=".$ID."&ID=".$arRes_orig['ID'].$sThisSectionUrl,
					"ACTION"=>"javascript:(new BX.CAdminDialog({
			    		'content_url': '/bitrix/admin/iblock_subelement_edit.php?WF=Y&type=".CUtil::JSEscape(htmlspecialchars($strSubIBlockType))."&IBLOCK_ID=".$intSubIBlockID."&lang=".LANGUAGE_ID."&PRODUCT_ID=".$ID."&ID=".$arRes_orig['ID']."&TMP_ID=".urlencode($strSubTMP_ID).$sThisSectionUrl."',
			    		'content_post': '&bxpublic=Y',
						'draggable': true,
						'resizable': true,
						'buttons': [BX.CAdminDialog.btnSave, BX.CAdminDialog.btnCancel]
						})).Show();",
				);

				$arActions[] = array(
					"ICON"=>"copy",
					"TEXT"=>GetMessage("IBEL_A_COPY_ELEMENT"),
					"ACTION"=>"javascript:(new BX.CAdminDialog({
			    		'content_url': '/bitrix/admin/iblock_subelement_edit.php?WF=Y&type=".CUtil::JSEscape(htmlspecialchars($strSubIBlockType))."&IBLOCK_ID=".$intSubIBlockID."&lang=".LANGUAGE_ID."&PRODUCT_ID=".$ID."&ID=".$arRes_orig['ID']."&TMP_ID=".urlencode($strSubTMP_ID)."&action=copy".$sThisSectionUrl."',
			    		'content_post': '&bxpublic=Y',
						'draggable': true,
						'resizable': true,
						'buttons': [BX.CAdminDialog.btnSave, BX.CAdminDialog.btnCancel]
						})).Show();",
				);

				$arActions[] = array("SEPARATOR"=>true);
		$arActions[] = array(
			"ICON"=>"delete",
			"TEXT"=>GetMessage('MAIN_DELETE'),
			"TITLE"=>GetMessage("IBLOCK_DELETE_ALT"),
			"ACTION"=>"if(confirm('".GetMessage('IBLOCK_CONFIRM_DEL_MESSAGE')."')) ".$lAdmin->ActionDoGroup($arRes_orig['ID'], "delete", $sThisSectionUrl)
		);
			}
		} //if($STATUS_PERMISSION>=2)
	}
	elseif($boolSubBizproc)
	{

	}
	else
	{
		$arActions[] = array(
			"ICON"=>"edit",
			"TEXT"=>GetMessage("IBEL_A_CHANGE"),
			//"ACTION"=>"/bitrix/admin/iblock_subelement_edit.php?WF=Y&type=".$strSubIBlockType."&IBLOCK_ID=".$intSubIBlockID."&lang=".LANGUAGE_ID."&PRODUCT_ID=".$ID."&ID=".$arRes_orig['ID'].$sThisSectionUrl,
			"ACTION"=>"javascript:(new BX.CAdminDialog({
			    'content_url': '/bitrix/admin/iblock_subelement_edit.php?WF=Y&type=".CUtil::JSEscape(htmlspecialchars($strSubIBlockType))."&IBLOCK_ID=".$intSubIBlockID."&lang=".LANGUAGE_ID."&PRODUCT_ID=".$ID."&ID=".$arRes_orig['ID']."&TMP_ID=".urlencode($strSubTMP_ID).$sThisSectionUrl."',
			    'content_post': '&bxpublic=Y',
				'draggable': true,
				'resizable': true,
				'buttons': [BX.CAdminDialog.btnSave, BX.CAdminDialog.btnCancel]
				})).Show();",
		);

		$arActions[] = array(
			"ICON"=>"copy",
			"TEXT"=>GetMessage("IBEL_A_COPY_ELEMENT"),
			//"ACTION"=>$lAdmin->ActionRedirect('iblock_element_edit.php?ID='.$arRes_orig['ID'].$sThisSectionUrl."&action=copy")
			"ACTION"=>"javascript:(new BX.CAdminDialog({
			    'content_url': '/bitrix/admin/iblock_subelement_edit.php?WF=Y&type=".CUtil::JSEscape(htmlspecialchars($strSubIBlockType))."&IBLOCK_ID=".$intSubIBlockID."&lang=".LANGUAGE_ID."&PRODUCT_ID=".$ID."&ID=".$arRes_orig['ID']."&TMP_ID=".urlencode($strSubTMP_ID)."&action=copy".$sThisSectionUrl."',
			    'content_post': '&bxpublic=Y',
				'draggable': true,
				'resizable': true,
				'buttons': [BX.CAdminDialog.btnSave, BX.CAdminDialog.btnCancel]
				})).Show();",
		);

		$arActions[] = array("SEPARATOR"=>true);
		$arActions[] = array(
			"ICON"=>"delete",
			"TEXT"=>GetMessage('MAIN_DELETE'),
			"TITLE"=>GetMessage("IBLOCK_DELETE_ALT"),
			"ACTION"=>"if(confirm('".GetMessage('IBLOCK_CONFIRM_DEL_MESSAGE')."')) ".$lAdmin->ActionDoGroup($arRes_orig['ID'], "delete", $sThisSectionUrl)
		);
	}

	$row->AddActions($arActions);
}

$lAdmin->AddFooter(
	array(
		array("title"=>GetMessage("MAIN_ADMIN_LIST_SELECTED"), "value"=>$rsData->SelectedRowsCount()),
		array("counter"=>true, "title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value"=>"0"),
	)
);

$arActions = array(
	"delete" => GetMessage("MAIN_ADMIN_LIST_DELETE"),
	"activate" => GetMessage("MAIN_ADMIN_LIST_ACTIVATE"),
	"deactivate" => GetMessage("MAIN_ADMIN_LIST_DEACTIVATE"),
);

if($boolSubWorkFlow)
{
	$arActions["unlock"] = GetMessage("IBEL_A_UNLOCK_ACTION");
	$arActions["lock"] = GetMessage("IBEL_A_LOCK_ACTION");
}
elseif($boolSubBizproc)
{
	$arActions["unlock"] = GetMessage("IBEL_A_UNLOCK_ACTION");
}
$lAdmin->AddGroupActionTable($arActions,array('disable_action_target' => true));
?><script "text/javascript">
function CheckProductName(id)
{
	if (!id)
		return false;
	var obj = BX(id);
	if (!obj)
		return false;
	var obFormElement = BX.findParent(obj,{tag: 'form'});
	if (!obFormElement)
		return false;
	if ((obFormElement.elements['NAME']) && (0 < obFormElement.elements['NAME'].value.length))
		return BX.util.htmlspecialchars(obFormElement.elements['NAME'].value);
	else
		return false;

}
function ShowNewOffer(id)
{
	var mxProductName = CheckProductName(id);
	if (!mxProductName)
		alert('<? echo CUtil::JSEscape(GetMessage('IB_SE_L_ENTER_PRODUCT_NAME')); ?>');
	else
	{
		(new BX.CAdminDialog({
		    'content_url': '/bitrix/admin/iblock_subelement_edit.php?WF=Y&type=<? echo CUtil::JSEscape(htmlspecialchars($strSubIBlockType)); ?>&IBLOCK_ID=<? echo $intSubIBlockID; ?>&lang=<? echo LANGUAGE_ID; ?>&PRODUCT_ID=<? echo $intSubPropValue; ?>&ID=0&TMP_ID=<? echo urlencode($strSubTMP_ID).$sThisSectionUrl; ?>',
		    'content_post': '&bxpublic=Y&PRODUCT_NAME='+BX.util.urlencode(mxProductName),
			'draggable': true,
			'resizable': true,
			'buttons': [BX.CAdminDialog.btnSave, BX.CAdminDialog.btnCancel]
			})).Show();
	}
}
</script><?
$aContext = array(
	array(
		"ICON"=>"btn_sub_new",
		"TEXT"=>htmlspecialcharsex('' != trim($arSubIBlock["ELEMENT_ADD"]) ? $arSubIBlock["ELEMENT_ADD"] : GetMessage('IB_SE_L_ADD_NEW_ELEMENT')),
		"LINK"=>"javascript:ShowNewOffer('btn_sub_new')",
		"TITLE"=>GetMessage("IB_SE_L_ADD_NEW_ELEMENT_DESCR")
	),
	array(
		"ICON"=>"btn_sub_refresh",
		"TEXT"=>htmlspecialcharsex(GetMessage('IB_SE_L_REFRESH_ELEMENTS')),
		"LINK" => "javascript:".$lAdmin->ActionAjaxReload($lAdmin->GetListUrl(true)),
		"TITLE"=>GetMessage("IB_SE_L_REFRESH_ELEMENTS_DESCR"),
	),
);
if (!defined('BX_PUBLIC_MODE') || BX_PUBLIC_MODE != 1)
	$lAdmin->AddAdminContextMenu($aContext);
else
	$lAdmin->AddAdminContextMenu($aContext,false,false);

$lAdmin->CheckListMode();

if (true == B_ADMIN_SUBELEMENTS_LIST)
{
//	$APPLICATION->SetTitle($arSubIBlock["NAME"].": ".$arSubIBlock["ELEMENTS_NAME"]);
//	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_popup_admin.php");

	echo $CAdminCalendar_ShowScript;
}

$lAdmin->DisplayList(B_ADMIN_SUBELEMENTS_LIST);
}
else
{
	ShowMessage(GetMessage('IB_SE_L_SHOW_PRICES_AFTER_COPY'));
}
?>