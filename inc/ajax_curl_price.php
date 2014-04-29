<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');
CModule::IncludeModule('catalog');


if(isset($_POST['elems']))
{
	$arr_elems = unserialize($_POST['elems']);
	
	$FULL_DISCOUNT = 0;
	
	foreach($arr_elems as $item)
	{
		$DISCOUNT_PRICE = 0;
		$FULL_PRICE = 0;
		$db_res1 = CPrice::GetList(array(), array("PRODUCT_ID" => $item['id'], 'CATALOG_GROUP_ID'=>2)); // RETAIL PRICE
		while($one_pr = $db_res1->GetNext())
		{
			// echo '<pre>';
			// print_R($one_pr);
			// echo '</pre>';
			
			$from = $one_pr['QUANTITY_FROM'];
			$to = 10000000000;
			if($one_pr['QUANTITY_TO']!='') $to = $one_pr['QUANTITY_TO'];
			
			if($item['q']>=$from and $item['q']<=$to)
			{
				$DISCOUNT_PRICE = $one_pr['PRICE'];
			};
			
			if($FULL_PRICE<$one_pr['PRICE']) $FULL_PRICE = $one_pr['PRICE'];
		};


		$DISCOUNT_PRICE = $DISCOUNT_PRICE*$item['q'];
		$FULL_PRICE = $FULL_PRICE*$item['q'];
		
		if($FULL_PRICE>$DISCOUNT_PRICE)
		{
			$FULL_DISCOUNT = $FULL_DISCOUNT + ($FULL_PRICE-$DISCOUNT_PRICE);
		};
		
	};
	
	echo $FULL_DISCOUNT;
};
?>