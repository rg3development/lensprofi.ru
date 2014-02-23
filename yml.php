<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
CModule::IncludeModule('iblock');
$cat_iblock = 4;

$SITE_URL = 'http://lensprofi.ru';

$arr_sect=array();
$arr_order= array('SORT'=>'ASC');
$arr_filter = Array('IBLOCK_ID'=>$cat_iblock, 'GLOBAL_ACTIVE'=>'Y');  
$arr_select=array();
$db_list = CIBlockSection::GetList($arr_order, $arr_filter, false, $arr_select);
while($one_sect = $db_list->GetNext())
{
	$arr_sect[$one_sect['ID']]=$one_sect;
};

$arr_elems = array();
$arr_order= array('SORT'=>'ASC');
$arr_select=array();
$arr_filter=array('IBLOCK_ID'=>$cat_iblock, 'ACTIVE'=>'Y');
$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);
$i=0;
while($one=$res->GetNext())
{
	$arr_elems[]= $one;
};

$PRICE = array();
CModule::IncludeModule('catalog');
$db_res1 = CPrice::GetList(array(), array());
while($one_pr = $db_res1->GetNext())
{
	// echo '<pre>';
	// print_R($one_pr);
	// echo '</pre>';
	if($one_pr['PRICE']!='' and $one_pr['PRICE']!=0 and $one_pr['CATALOG_GROUP_ID']==1 and $one_pr['QUANTITY_FROM']==1 and $one_pr['QUANTITY_TO']==1)  $PRICE[$one_pr['PRODUCT_ID']] = $one_pr['PRICE']; //its BASE price
};

// echo '<pre>';
// print_R($PRICE);
// echo '</pre>';

/*
require_once($_SERVER['DOCUMENT_ROOT'].'/YMarket/YMarket.class.php');
$market = new YMarket('lensprofy', 'lensprofy', $SITE_URL);
$market->add(new Currency('RUR', 1));
*/

header('Content-type:application/xml');
$str ='<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="'.date('Y-m-d H:i').'"><shop><name>lensprofy</name><company>lensprofy</company><url>http://lensprofi.ru</url><currencies><currency id="RUR" rate="1"/></currencies><categories>';

foreach($arr_sect as $cat)
{
	$str.= '<category id="'.$cat['ID'].'" >'.$cat['NAME'].'</category>';
};

$str .='</categories><offers>';

foreach($arr_elems as $elem)
{
	$name = $elem['NAME'];
	/*
	if($elem['IBLOCK_SECTION_ID']!=7) $name = $arr_sect[$elem['IBLOCK_SECTION_ID']]['NAME'].' '.$elem['NAME'];
	*/
	$vendor = '';
	if($elem['IBLOCK_SECTION_ID']!=7) $vendor = $arr_sect[$elem['IBLOCK_SECTION_ID']]['NAME'];
	if(isset($PRICE[$elem['ID']]))
	{
		$PR = $PRICE[$elem['ID']];
	}
	else
	{
		die('one or more price not found');
	};
	;
$str .= '
	<offer id="'.$elem['ID'].'" available="true">
		<url>'.$SITE_URL.'/catalog/'.$elem['CODE'].'/'.'</url>
		<price>'.$PR.'</price>
		<currencyId>RUR</currencyId>
		<categoryId>'.$elem['IBLOCK_SECTION_ID'].'</categoryId>
		<picture>'.$SITE_URL.CFile::GetPath($elem['PREVIEW_PICTURE']).'</picture>
		<name><![CDATA['.trim(strip_tags($name)).']]></name>
		<description><![CDATA['.trim(strip_tags($elem['PREVIEW_TEXT'])).']]></description>
	</offer>';
};

$str .='</offers>
</shop>
</yml_catalog>';

echo $str;
?>