<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поиск по сайту");


if(isset($_REQUEST['oft_cab']) and $_REQUEST['oft_cab']=='on')
{
	// search in QUESTION
		$APPLICATION->IncludeComponent("bitrix:search.page", "search-results", array(
			"RESTART" => "Y",
			"NO_WORD_LOGIC" => "Y",
			"CHECK_DATES" => "N",
			"USE_TITLE_RANK" => "Y",
			"DEFAULT_SORT" => "rank",
			"FILTER_NAME" => "",
			"arrFILTER" => array(
				0 => "iblock_cnt",
			),
			"arrFILTER_iblock_cnt" => array(
				0 => "3",
			),
			"SHOW_WHERE" => "N",
			"SHOW_WHEN" => "N",
			"PAGE_RESULT_COUNT" => "100000",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TITLE" => "Результаты поиска",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "",
			"USE_LANGUAGE_GUESS" => "N",
			"USE_SUGGEST" => "N",
			"SHOW_ITEM_TAGS" => "Y",
			"TAGS_INHERIT" => "Y",
			"SHOW_ITEM_DATE_CHANGE" => "Y",
			"SHOW_ORDER_BY" => "Y",
			"SHOW_TAGS_CLOUD" => "N",
			"AJAX_OPTION_ADDITIONAL" => ""
			),
			false
		);
}
else
{	
	// search in CAT
		$APPLICATION->IncludeComponent("bitrix:search.page", "search-results", array(
			"RESTART" => "Y",
			"NO_WORD_LOGIC" => "Y",
			"CHECK_DATES" => "N",
			"USE_TITLE_RANK" => "Y",
			"DEFAULT_SORT" => "rank",
			"FILTER_NAME" => "",
			"arrFILTER" => array(
				0 => "iblock_catalog",
			),
			"arrFILTER_iblock_catalog" => array(
				0 => "all",
			),
			"SHOW_WHERE" => "N",
			"SHOW_WHEN" => "N",
			"PAGE_RESULT_COUNT" => "100000",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "3600",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TITLE" => "Результаты поиска",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => "",
			"USE_LANGUAGE_GUESS" => "N",
			"USE_SUGGEST" => "N",
			"SHOW_ITEM_TAGS" => "Y",
			"TAGS_INHERIT" => "Y",
			"SHOW_ITEM_DATE_CHANGE" => "Y",
			"SHOW_ORDER_BY" => "Y",
			"SHOW_TAGS_CLOUD" => "N",
			"AJAX_OPTION_ADDITIONAL" => ""
			),
			false
		);
};

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
