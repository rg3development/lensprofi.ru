<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule('iblock');
CModule::IncludeModule('catalog');

CModule::IncludeModule('sale');

$DISCOUNT_PRICE = 0;
$FULL_PRICE = 0;

function getBasketItemsCountbyId($id)
{
	$dbBasketItems = CSaleBasket::GetList(
        array("ID" => "ASC"),
        array("PRODUCT_ID" => $id, "ORDER_ID" => "NULL", "FUSER_ID"=> CSaleBasket::GetBasketUserID()),
        false,
        false,
        array()
    );
	$counter = 0;
	while($item=$dbBasketItems->GetNext())
	{	
		if($item['PRODUCT_ID']==$id) $counter += $item['QUANTITY'];
	};
	
	return $counter;
};

// для подбора цены возьмем плюсанем товары из корзины
$FULL_COUNT = $_REQUEST['quantity'] + getBasketItemsCountbyId($_REQUEST['prod_id']);

/*
	$dbBasketItems = CSaleBasket::GetList(
        array("ID" => "ASC"),
        array( "ORDER_ID" => "NULL", "FUSER_ID"=> CSaleBasket::GetBasketUserID()),
        false,
        false,
        array()
    );
	$counter = 0;
	while($item=$dbBasketItems->GetNext())
	{	
		// if($item['PRODUCT_ID']==$_REQUEST['prod_id']) 
		// {
			echo '<pre>';
			print_R($item);
			echo '</pre>';
		//}
	};
*/

$db_res1 = CPrice::GetList(array(), array("PRODUCT_ID" => $_REQUEST['prod_id'], 'CATALOG_GROUP_ID'=>2)); // RETAIL PRICE
while($one_pr = $db_res1->GetNext())
{
	// echo '<pre>';
	// print_R($one_pr);
	// echo '</pre>';
	
	$from = $one_pr['QUANTITY_FROM'];
	$to = 10000000000;
	if($one_pr['QUANTITY_TO']!='') $to = $one_pr['QUANTITY_TO'];
	
	if($FULL_COUNT>=$from and $FULL_COUNT<=$to)
	{
		$DISCOUNT_PRICE = $one_pr['PRICE'];;
	};
	
	if($FULL_PRICE<$one_pr['PRICE']) $FULL_PRICE = $one_pr['PRICE'];
};


$DISCOUNT_PRICE = $DISCOUNT_PRICE*$_REQUEST['quantity'];
$FULL_PRICE = $FULL_PRICE*$_REQUEST['quantity'];


?>

<?if($FULL_PRICE!=$DISCOUNT_PRICE):?>
	<ul>
		<li style="font-size: 11px"><span style="padding-top: 3px">Товаров на сумму: <span><?=$FULL_PRICE;?> руб.</span></span></li>
		<li><p style="font-size: 14px">Со скидкой: <?=$DISCOUNT_PRICE;?> руб.</p></li>
		<?if($DISCOUNT_PRICE!=0):?><li><a href="javascript:ajax_basket_add();"><img src="/img/bt151a.png" alt="добавить в корзину" /></a></li><?endif;?>
	</ul>
<?else:?>
	<ul>
		
		<li></li>
		<li><p style="font-size: 14px">Товаров на сумму: <?=$DISCOUNT_PRICE;?> руб.</p></li>
		<?if($DISCOUNT_PRICE!=0):?><li><a href="javascript:ajax_basket_add();"><img src="/img/bt151a.png" alt="добавить в корзину" /></a></li><?endif;?>
	</ul>
<?endif;?>
