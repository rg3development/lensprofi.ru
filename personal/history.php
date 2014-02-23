<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
?>
<div class="srv5"><a href="#"><span>Здесь вы можете оформить повторный заказ в 3 клика</span></a></div>
<div class="switcher1"> 	 
	<ul> 		 
		<li><a href="./index.php" >Личные данные</a></li>
		<li><span>История заказов</span></li>
	</ul>
</div>
 <?$APPLICATION->IncludeComponent("utlab:sale.personal.order", "order_history", array(
	"PROP_1" => array(
	),
	"SEF_MODE" => "N",
	"SEF_FOLDER" => "/personal/",
	"ORDERS_PER_PAGE" => "20",
	"PATH_TO_PAYMENT" => "payment.php",
	"PATH_TO_BASKET" => "basket.php",
	"SET_TITLE" => "N",
	"SAVE_IN_SESSION" => "Y",
	"NAV_TEMPLATE" => ""
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
