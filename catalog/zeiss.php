<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Широкий выбор линз для глаз Zeiss в интернет магазине LensProfi. Консультации специалиста по подбору и покупке контактных линз Zeiss. Оперативная доставка по Москве и России.");
$APPLICATION->SetPageProperty("keywords", "линзы zeiss купить контактные недорого москва продажа интернет магазин");
$APPLICATION->SetPageProperty("title", "Линзы Zeiss: купить контактные линзы Zeiss недорого в Москве, продажа контактных линз Zeiss в интернет магазине LensProfi.");
$APPLICATION->SetTitle("Title");
?> 
<div style="font-size: 13px;"> 
  <p><img src="/img/brandsgray/zeiss.jpg" title="Контактные линзы Zeiss" border="0" align="left" alt="Контактные линзы Zeiss"  /></p>
 
  <p>Контактные линзы и средства ухода за ними от известного бренда Carl Zeiss. Оцените преимущества высокотехнологичного бренда Zeiss. </p>
 
  <p> 
    <br />
   </p>
 
  <p> 
    <br />
   </p>
 
  <br />
 </div>
 <?$APPLICATION->IncludeComponent(
	"utlab:catalog.section",
	"landing_1",
	Array(
		"AJAX_MODE" => "N",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "4",
		"SECTION_ID" => "25",
		"SECTION_CODE" => "",
		"SECTION_USER_FIELDS" => array(),
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"FILTER_NAME" => "arrFilter",
		"INCLUDE_SUBSECTIONS" => "Y",
		"SHOW_ALL_WO_SECTION" => "N",
		"SECTION_URL" => "",
		"DETAIL_URL" => "",
		"BASKET_URL" => "/personal/basket.php",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"META_KEYWORDS" => "-",
		"META_DESCRIPTION" => "-",
		"BROWSER_TITLE" => "-",
		"ADD_SECTIONS_CHAIN" => "N",
		"DISPLAY_COMPARE" => "N",
		"SET_TITLE" => "Y",
		"SET_STATUS_404" => "N",
		"PAGE_ELEMENT_COUNT" => "30",
		"LINE_ELEMENT_COUNT" => "3",
		"PROPERTY_CODE" => array(),
		"PRICE_CODE" => array("RETAIL"),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_PROPERTIES" => array("TOHEAD"),
		"USE_PRODUCT_QUANTITY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "Y",
		"PAGER_TEMPLATE" => "",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "Y",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N"
	)
);?>  
<div style="font-size: 12px;"><a href="http://lensprofi.ru/" >Интернет магазин контактных линз</a> &gt; <a href="http://lensprofi.ru/catalog/brands.php"  >Все бренды</a> &gt; Zeiss</div>
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>