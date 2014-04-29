<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?

// echo '<pre>';
// print_R($_REQUEST);
// echo '</pre>';

//собираем массив для фильтрации
if(isset($_REQUEST['prod']))
{
	
	$cat_iblock = 4;
	
	// справочник 
	$arr_types = array();
	$arr_order= array('SORT'=>'ASC');
	$arr_filter = Array('IBLOCK_ID'=>$cat_iblock, 'GLOBAL_ACTIVE'=>'Y');  
	$arr_select=array("ID", "IBLOCK_ID", "CODE", "DESCRIPTION", "IBLOCK_SECTION_ID", "NAME", "ACTIVE", "PICTURE", "UF_*");
	$db_list = CIBlockSection::GetList($arr_order, $arr_filter, false, $arr_select);
	while($one_sect = $db_list->GetNext())
	{
		$arr_types[$one_sect['ID']] = $one_sect;
	};
		
	
	if($_REQUEST['prod']=='l') // linz
	{
		// not all types
		if(isset($_REQUEST['type']) and $_REQUEST['type']!='type0')
		{
			$arrFilter['SECTION_ID'] = str_replace('type', '', $_REQUEST['type']);
		}
		else
		{
			//chak, added 03/05
			$arrFilter['!SECTION_ID'] = 7;
		}
		
		// not all producers
		if(isset($_REQUEST['producer']) and $_REQUEST['producer']!='producer0') 
		{
			
			$arr_producers = array();
			
			$arr_order= array('id'=>'ASC');
			$arr_select=array('ID', 'IBLOCK_ID', 'CODE', 'IBLOCK_SECTION_ID', 'NAME', 'PROPERTY_PRODUCER', 'PROPERTY_BRAND', 'PROPERTY_USETIME');
			$arr_filter=array('IBLOCK_ID'=>$cat_iblock, 'ACTIVE'=>'Y', '!SECTION_ID'=>7);
			$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);
			$i=0;
			while($one=$res->GetNext())
			{
				/*
				if(!in_array(trim($one['PROPERTY_PRODUCER_VALUE']),$arr_producers) and $one['PROPERTY_PRODUCER_VALUE']!='')
				{
					$arr_producers[$one['ID']] = trim($one['PROPERTY_PRODUCER_VALUE']);
				}
				*/
				if(!in_array(trim($one['PROPERTY_BRAND_VALUE']),$arr_producers) and $one['PROPERTY_BRAND_VALUE']!='')
				{
					$arr_producers[$one['ID']] = trim($one['PROPERTY_BRAND_VALUE']);
				}
			};
			
			// echo '<pre>';
			// print_R($arr_producers);
			// echo '</pre>';
			
			
			$prod_id = str_replace('producer', '', $_REQUEST['producer']);
			if(isset($arr_producers[$prod_id]))
			{
				$arrFilter['PROPERTY']['BRAND'] = $arr_producers[$prod_id];;
			};
		}
		
		// not all periods
		if(isset($_REQUEST['period']) and $_REQUEST['period']!='time0') 
		{
			$arrFilter['PROPERTY']['USETIME'] = str_replace('time', '', $_REQUEST['period']);
		};
		
		
		// *** part 2
		// create breadcrumb-like chain
		$GLOBALS['PROD_CHAIN'] = array();
		if(isset($arrFilter['SECTION_ID'])) 
		{
			$GLOBALS['PROD_CHAIN'][]= $arr_types[$arrFilter['SECTION_ID']]['NAME'];
		}
		else
		{
			$GLOBALS['PROD_CHAIN'][]= 'Все типы';
		};
		
		if(isset($arrFilter['PROPERTY']['BRAND'])) 
		{
			$GLOBALS['PROD_CHAIN'][]= $arrFilter['PROPERTY']['BRAND'];
		}
		else
		{
			$GLOBALS['PROD_CHAIN'][]= 'Все бренды';
		};
		
		if(isset($arrFilter['PROPERTY']['USETIME']))
		{
			$GLOBALS['PROD_CHAIN'][]= get_period_str($arrFilter['PROPERTY']['USETIME']);
		}
		else
		{
			$GLOBALS['PROD_CHAIN'][]= 'Любой срок использования';
		};
	};
	
	if($_REQUEST['prod']=='a') // accessories
	{
		
		$arr_access_producers=array();
		$arr_access_types = array();

		$arr_order= array('SORT'=>'ASC');
		$arr_select=array('ID', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'NAME', 'PROPERTY_PRODUCER', 'PROPERTY_BRAND', 'PROPERTY_LENSTYPE');
		$arr_filter=array('IBLOCK_ID'=>$cat_iblock, 'ACTIVE'=>'Y', "SECTION_ID"=>7);
		$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);
		$i=0;
		while($one=$res->GetNext())
		{
			/*
			if(!in_array(trim($one['PROPERTY_PRODUCER_VALUE']), $arr_access_producers) and $one['PROPERTY_PRODUCER_VALUE']!='')
			{
				$arr_access_producers[$one['ID']] = trim($one['PROPERTY_PRODUCER_VALUE']);
			};
			*/
			if(!in_array(trim($one['PROPERTY_BRAND_VALUE']), $arr_access_producers) and $one['PROPERTY_BRAND_VALUE']!='')
			{
				$arr_access_producers[$one['ID']] = trim($one['PROPERTY_BRAND_VALUE']);
			};
			
			if(!in_array(trim($one['PROPERTY_LENSTYPE_VALUE']), $arr_access_types) and $one['PROPERTY_LENSTYPE_VALUE']!='')
			{
				$arr_access_types[$one['ID']] = trim($one['PROPERTY_LENSTYPE_VALUE']);
			};
		};
		
		//ALWAYS
		$arrFilter['SECTION_ID'] = 7; // HARDCODE
		
		// not all types
		if(isset($_REQUEST['access_type']) and $_REQUEST['access_type']!='access_type0')
		{
			$arrFilter['PROPERTY']['LENSTYPE'] = $arr_access_types[str_replace('access_type', '', $_REQUEST['access_type'])];
		}
		
		if(isset($_REQUEST['access_producer']) and $_REQUEST['access_producer']!='access_producer0') 
		{
			//$arrFilter['PROPERTY']['?PRODUCER'] = $arr_access_producers[str_replace('access_producer', '', $_REQUEST['access_producer'])];
			$arrFilter['PROPERTY']['BRAND'] = $arr_access_producers[str_replace('access_producer', '', $_REQUEST['access_producer'])];
		}
		
		// *** part 2
		// create breadcrumb-like chain
		$GLOBALS['PROD_CHAIN'] = array();
		if(isset($arrFilter['PROPERTY']['LENSTYPE']))
		{
			$GLOBALS['PROD_CHAIN'][]= $arrFilter['PROPERTY']['LENSTYPE'];
		}
		else
		{
			$GLOBALS['PROD_CHAIN'][]= 'Все типы';
		};
		
		if(isset($arrFilter['PROPERTY']['BRAND']))
		{
			$GLOBALS['PROD_CHAIN'][]= $arrFilter['PROPERTY']['BRAND'];
		}
		else
		{
			$GLOBALS['PROD_CHAIN'][]= 'Все бренды';
		};
			
	};
	

	$GLOBALS['arrFilter'] = $arrFilter;
	
	// echo '<pre>';
	// print_R($GLOBALS['arrFilter']);
	// echo '</pre>';
}

//here we create param string to save filter to session
$arr_interest= array('prod', 'access_type', 'access_producer', 'type', 'producer', 'period');
$param_str = '?';
$FILTER_ARR = array();
foreach($_REQUEST as $param_name=>$param_value)
{
	if(in_array($param_name, $arr_interest))
	{
		$param_str .= $param_name.'='.$param_value.'&';
		$FILTER_ARR[$param_name] = $param_value;
	};
};
session_start();
$_SESSION['FILTER_HREF'] = $param_str;
$_SESSION['FILTER_ARR'] = $FILTER_ARR;

// echo '<pre>';
// print_R($_SESSION['FILTER_ARR']);
// echo '</pre>';
?>


<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
 		"PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
		"META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
		"META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
		"BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
		"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"FILTER_NAME" => 'arrFilter', //HARDCODE
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_FILTER" => $arParams["CACHE_FILTER"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SET_TITLE" => $arParams["SET_TITLE"],
		"SET_STATUS_404" => $arParams["SET_STATUS_404"],
		"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
		"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
		"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],

		"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE" => $arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
		"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],

		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

		"SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
		"SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "Y"
	),
	$component
);
?>

<?/*$APPLICATION->IncludeComponent(
	"bitrix:catalog.section.list",
	"",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"]
	),
	$component
);
*/?>

<?if($arParams["USE_COMPARE"]=="Y"):?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.compare.list",
	"",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"NAME" => $arParams["COMPARE_NAME"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"COMPARE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["compare"],
	),
	$component
);?>
<br />
<?endif?>


<?if($arParams["SHOW_TOP_ELEMENTS"]!="N"):?>
<hr />
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.top",
	"",
	Array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_SORT_FIELD" => $arParams["TOP_ELEMENT_SORT_FIELD"],
		"ELEMENT_SORT_ORDER" => $arParams["TOP_ELEMENT_SORT_ORDER"],
		"SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
		"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
		"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
		"DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
		"ELEMENT_COUNT" => $arParams["TOP_ELEMENT_COUNT"],
		"LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
		"PROPERTY_CODE" => $arParams["TOP_PROPERTY_CODE"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
		"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
		"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
		"PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"OFFERS_FIELD_CODE" => $arParams["TOP_OFFERS_FIELD_CODE"],
		"OFFERS_PROPERTY_CODE" => $arParams["TOP_OFFERS_PROPERTY_CODE"],
		"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
		"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
		"OFFERS_LIMIT" => $arParams["TOP_OFFERS_LIMIT"],
	),
$component
);?>
<?endif?>
