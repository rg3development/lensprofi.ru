<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("basket");
?>
<?
echo '<pre>';
print_R($_POST);
echo '</pre>';
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket",
	"",
	Array(
		"PATH_TO_ORDER" => "/personal/order.php",
		"HIDE_COUPON" => "N",
		"COLUMNS_LIST" => array("NAME", "PROPS", "PRICE", "TYPE", "QUANTITY", "DELETE", "DELAY", "WEIGHT", "DISCOUNT"),
		"QUANTITY_FLOAT" => "N",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "Y",
		"SET_TITLE" => "Y"
	),
false
);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>