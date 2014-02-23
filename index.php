<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Интернет магазин контактных линз, купить линзы для глаз недорого, продажа линз в Москве.");
$APPLICATION->SetPageProperty("tags", "Интернет магазин контактных линз LensProfi.ru");
$APPLICATION->SetPageProperty("keywords", "интернет магазин контактные линзы доставка по Москве консультация офтальмолога онлайн");
$APPLICATION->SetPageProperty("description", "Широкий выбор популярных контактных линз для глаз в интернет магазине ЛинзПрофи. Регулярные скидки и подарки покупателям, доставка линз в день заказа и многое другое. Доставка заказов осуществляется как по Москве так и по всей России.");
$APPLICATION->SetTitle("Internet shop");
error_reporting(E_ERROR);
ini_set('display_errors', 'On');
$APPLICATION -> SetTitle("");?> 
<div class="block619"> 	<img src="img/pic310.jpg" class="pic310" title="ЛинзПрофи интернет магазин контактных линз" alt="ЛинзПрофи" width="619" height="310"  /> 
  <div class="block250"> 		 
    <h2>Профессиональная забота о Вашем зрении</h2>
   		 
    <ul> 			 
      <li>Гарантия качества</li>
     
      <li>Консультация врача онлайн</li>
     			 
      <li>Доставка точно в срок до 23.00</li>
     
      <li>Напоминание купить новые линзы</li>
     		</ul>
   		<a href="#" class="bt181" id="filter1" ><img src="img/bt192a.png"  /></a> 		<a href="#" class="bt181" id="filter2" ><img src="img/bt192b.png"  /></a>				 	</div>
 </div>
 
<div class="block340"> 	 
  <div class="block339" style="padding-top: 4px; "> 		<? $APPLICATION -> IncludeFile("/inc/poll.php"); ?> 	</div>
 	 
  <table> 		 
    <tbody> 
      <tr> 			<td> 				 
          <h3>Статьи<a href="/articles/" >все</a></h3>
         				<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"index-articles",
	Array(
		"IBLOCK_TYPE" => "cnt",
		"IBLOCK_ID" => "2",
		"NEWS_COUNT" => "1",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(),
		"PROPERTY_CODE" => array(),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_OPTION_ADDITIONAL" => ""
	)
);?> 	 			</td> 			<td> 				 
          <h3>Новости<a href="/news/" >все</a></h3>
         				<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"index-news",
	Array(
		"IBLOCK_TYPE" => "cnt",
		"IBLOCK_ID" => "1",
		"NEWS_COUNT" => "1",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_ORDER1" => "DESC",
		"SORT_BY2" => "SORT",
		"SORT_ORDER2" => "ASC",
		"FILTER_NAME" => "",
		"FIELD_CODE" => array(),
		"PROPERTY_CODE" => array(),
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"SET_TITLE" => "N",
		"SET_STATUS_404" => "N",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"ADD_SECTIONS_CHAIN" => "N",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "Новости",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"AJAX_OPTION_ADDITIONAL" => ""
	)
);?> 			</td> 		</tr>
     	</tbody>
   </table>
 </div>
<div class="pos-rel"> 
  <div class="second-line"> <?
        $rs = CIBlockElement::GetList(array("SORT" => "ASC"), array("IBLOCK_ID" => 8));
        while($ar = $rs->GetNext()) {
         print_r($ar['DETAIL_TEXT']);
        }
?> </div>
<div class="clean"></div>
 </div><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>