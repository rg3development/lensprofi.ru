<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");
?>

<?$APPLICATION->IncludeComponent("utlab:sale.order.ajax", "order1", array(
	"PAY_FROM_ACCOUNT" => "N",
	"COUNT_DELIVERY_TAX" => "N",
	"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
	"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
	"ALLOW_AUTO_REGISTER" => "Y",
	"SEND_NEW_USER_NOTIFY" => "Y",
	"DELIVERY_NO_AJAX" => "Y",
	"PROP_1" => array(
	),
	"PATH_TO_BASKET" => "/personal/basket.php",
	"PATH_TO_PERSONAL" => "/personal/index.php",
	"PATH_TO_PAYMENT" => "/personal/payment.php",
	"PATH_TO_AUTH" => "/auth/",
	"SET_TITLE" => "Y"
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>