<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

$cat_iblock = 4;

$arr_order= array('SORT'=>'ASC');
$arr_select=array();
$arr_filter=array('IBLOCK_ID'=>$cat_iblock);
$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);
$success=0;
$all_count = 0;
while($one=$res->GetNext())
{
	$el = new CCatalogProduct;
	$el_arr = Array("QUANTITY_TRACE"=> "N");
	if($el->Update($one['ID'], $el_arr))
	{
		$success++;
	};
	$all_count++;
};

echo $all_count.' = '.$success;


?>