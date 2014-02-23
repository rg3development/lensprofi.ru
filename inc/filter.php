
<div align="center"> 
  <br />
 </div>
 
<!--noindex-->
<div style="text-align: left;"> 
<!--фильтр товаров-->

 <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.filter",
	"lens-filter",
	Array(
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "4",
		"FILTER_NAME" => "arrFilter",
		"FIELD_CODE" => array(),
		"PROPERTY_CODE" => array(),
		"PRICE_CODE" => array("RETAIL"),
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CACHE_NOTES" => "",
		"CACHE_GROUPS" => "N",
		"LIST_HEIGHT" => "10",
		"TEXT_WIDTH" => "20",
		"NUMBER_WIDTH" => "5",
		"SAVE_IN_SESSION" => "Y"
	)
);?> 
  <br />
 </div>
<!--/noindex-->
