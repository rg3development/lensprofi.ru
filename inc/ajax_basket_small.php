<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.small", "top-basket", Array("PATH_TO_BASKET" => "/personal/basket.php", "PATH_TO_ORDER" => "/personal/order.php"), false);?>