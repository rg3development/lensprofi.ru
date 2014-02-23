<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->IncludeComponent("utlab:sale.basket.basket", "lenses", array(
	"COUNT_DISCOUNT_4_ALL_QUANTITY" => "Y",
	"COLUMNS_LIST" => array(
		0 => "NAME",
		2 => "PROPS",
		3 => "QUANTITY",
		4 => "DELETE",
	),
	//"COLUMNS_LIST" => array("NAME", "PROPS", "PRICE", "TYPE", "QUANTITY", "DELETE", "DELAY", "WEIGHT", "DISCOUNT"),
	"PATH_TO_ORDER" => "/personal/order.php",
	"HIDE_COUPON" => "Y",
	"QUANTITY_FLOAT" => "N",
	"PRICE_VAT_SHOW_VALUE" => "Y",
	"SET_TITLE" => "N"
	),
	false
);?>