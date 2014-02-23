<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
?> 
<div class="switcher1"> 	 
	<ul> 		 
		<li><span>Личные данные</span></li> 
		<li><a href="./history.php" >История заказов</a></li>
	</ul>
</div>
<?$APPLICATION->IncludeComponent("bitrix:main.profile", "lk", array(
	"AJAX_MODE" => "N",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"SET_TITLE" => "N",
	"USER_PROPERTY" => array(
		0 => "UF_PHONE_CODE",
		1 => "UF_FRIENDS_COUNTER",
		2 => "UF_DATE_REMIND",
		3 => "UF_SMS_REMINDER",
		4 => "UF_EMAIL_REMINDER",
	),
	"SEND_INFO" => "N",
	"CHECK_RIGHTS" => "N",
	"USER_PROPERTY_NAME" => "",
	"AJAX_OPTION_ADDITIONAL" => ""
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
