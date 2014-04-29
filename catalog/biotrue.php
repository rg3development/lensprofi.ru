<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Широкий выбор линз для глаз Biotrue в интернет магазине LensProfi. Консультации специалиста по подбору и покупке контактных линз Biotrue. Оперативная доставка по Москве и России.");
$APPLICATION->SetPageProperty("keywords", "линзы biotrue купить контактные недорого москва продажа интернет магазин");
$APPLICATION->SetPageProperty("title", "Линзы Biotrue: купить контактные линзы Biotrue недорого в Москве, продажа контактных линз Biotrue в интернет магазине LensProfi.");
$APPLICATION->SetTitle("Title");
?> 
<div style="font-size: 13px;"> 
  <p><img src="/img/brandsgray/biotrue.jpg" title="Контактные линзы Biotrue" border="0" align="left" alt="Контактные линзы Biotrue"  /></p>
 
  <p>BioTrue новая линейка продуктов от Baush + Lomb. Инновационные однодневные контактные линзы и раствор идеально сочитающийся с ними.</p>
 
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
		"SECTION_ID" => "24",
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
		"CACHE_NOTES" => "",
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
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => ""
	)
);?>  
<div style="font-size: 12px;"><a href="http://lensprofi.ru/" >Интернет магазин контактных линз</a> &gt; <a href="http://lensprofi.ru/catalog/brands.php" >Все бренды</a> &gt; Biotrue</div>
 <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>