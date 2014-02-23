<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');
CModule::IncludeModule('catalog');

?>
<?

$arr_order= array('ID'=>'ASC');
$arr_select=array();
$arr_filter=array('IBLOCK_ID'=>4);
$db_res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);

$i=0;

while($one = $db_res->GetNext()) 
{	
	$PROD_ID = $one['ID'];
	
	//echo $PROD_ID.'<br />';
	
	$db_res1 = CPrice::GetList(array(), array("PRODUCT_ID" => $PROD_ID)); // for base price
	while($one_pr = $db_res1->GetNext()) // массив ценовых предложений
	{
		if($one_pr['PRICE']!='' and $one_pr['PRICE']!=0 and $one_pr['CATALOG_GROUP_ID']==1)  $PRICE = $one_pr['PRICE']; //its BASE price
		CPrice::Delete($one_pr['ID']);
	};
	
		// CREATE BASE 1
		$arFields = Array(
			"PRODUCT_ID" => $PROD_ID,
			"PRICE" => $PRICE,
			"CURRENCY" => "RUB",
			"CATALOG_GROUP_ID" => 1,
			"QUANTITY_FROM" => 1,
			"QUANTITY_TO" => 1
		);

		$obPrice = new CPrice();
		$res = $obPrice->Add($arFields);


		// CREATE BASE 2
		$arFields = Array(
			"PRODUCT_ID" => $PROD_ID,
			"PRICE" => $PRICE,
			"CURRENCY" => "RUB",
			"CATALOG_GROUP_ID" => 1,
			"QUANTITY_FROM" => 2,
			"QUANTITY_TO" => 2
		);

		$obPrice = new CPrice();
		$res = $obPrice->Add($arFields);


		// CREATE BASE 3
		$arFields = Array(
			"PRODUCT_ID" => $PROD_ID,
			"PRICE" => $PRICE,
			"CURRENCY" => "RUB",
			"CATALOG_GROUP_ID" => 1,
			"QUANTITY_FROM" => 3,
			"QUANTITY_TO" => 3
		);

		$obPrice = new CPrice();
		$res = $obPrice->Add($arFields);
	

		// CREATE BASE 4
		$arFields = Array(
			"PRODUCT_ID" => $PROD_ID,
			"PRICE" => $PRICE,
			"CURRENCY" => "RUB",
			"CATALOG_GROUP_ID" => 1,
			"QUANTITY_FROM" => 4,
		);

		$obPrice = new CPrice();
		$res = $obPrice->Add($arFields);
	

		// CREATE RETAIL 1
		$arFields = Array(
			"PRODUCT_ID" => $PROD_ID,
			"PRICE" => $PRICE,
			"CURRENCY" => "RUB",
			"CATALOG_GROUP_ID" => 2,
			"EXTRA_ID" => 2, // 1 pack
			"QUANTITY_FROM" => 1,
			"QUANTITY_TO" => 1
		);

		$obPrice = new CPrice();
		$res = $obPrice->Add($arFields);
	

		// CREATE RETAIL 2
		$arFields = Array(
			"PRODUCT_ID" => $PROD_ID,
			"PRICE" => $PRICE,
			"CURRENCY" => "RUB",
			"CATALOG_GROUP_ID" => 2,
			"EXTRA_ID" => 1, //2 pack
			"QUANTITY_FROM" => 2,
			"QUANTITY_TO" => 2
		);

		$obPrice = new CPrice();
		$res = $obPrice->Add($arFields);
	

		// CREATE RETAIL 3
		$arFields = Array(
			"PRODUCT_ID" => $PROD_ID,
			"PRICE" => $PRICE,
			"CURRENCY" => "RUB",
			"CATALOG_GROUP_ID" => 2,
			"EXTRA_ID" => 3, //3 pack
			"QUANTITY_FROM" => 3,
			"QUANTITY_TO" => 3
		);

		$obPrice = new CPrice();
		$res = $obPrice->Add($arFields);

		
		// CREATE RETAIL 4
		$arFields = Array(
			"PRODUCT_ID" => $PROD_ID,
			"PRICE" => $PRICE,
			"CURRENCY" => "RUB",
			"CATALOG_GROUP_ID" => 2,
			"EXTRA_ID" => 4, //4+ pack
			"QUANTITY_FROM" => 4
		);

		$obPrice = new CPrice();
		$res = $obPrice->Add($arFields);
	

		$i++;
		//if($i==3) break;
};

echo $i;

?>