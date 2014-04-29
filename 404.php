<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Страница не найдена");?>
Запрашиваемая вами страница не существует. 
<br />
 Пожалуйста, проверте URL или попробуйте посетить следующие страницы: 
<br /><br />
<?$APPLICATION->IncludeComponent("bitrix:main.map", ".default", array(
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"SET_TITLE" => "Y",
	"LEVEL"	=>	"3",
	"COL_NUM"	=>	"1",
	"SHOW_DESCRIPTION" => "Y"
	),
	false
);
?>
 
<br />
 Если вы перешли на эту страницу по ссылке, расположенной на сайте, пожалуйста, сообщите по адресу: <a href="mailto:info@lensprofi.ru">info@lensprofi.ru</a>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>