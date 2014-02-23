<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?
// chak
// код для чистки поисковых подсказок при каждом выполненном поиске
$phrases = array();
$query_str = 'DELETE FROM `b_search_phrase` WHERE RESULT_COUNT=0'; 
$res = $DB->Query($query_str);
?>

	<?if(count($arResult["SEARCH"])>0) { ?>
		<ul class="simpleList1">
			<? foreach($arResult["SEARCH"] as $arItem) { ?>
				<?
				$arr_item = array();
				
				$arr_order= array('SORT'=>'ASC');
				$arr_select=array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_LENSTYPE', 'PROPERTY_PRODUCER', 'PROPERTY_USETIME');
				$arr_filter=array('IBLOCK_ID'=>4, 'ACTIVE'=>'Y', 'ID'=>$arItem['ITEM_ID']);
				$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);
				$i=0;
				if($one=$res->GetNext())
				{
					$arr_item = $one;
				};
				
				if($USER->IsAdmin())
				{
					// echo '<pre>';
					// print_R($arr_item);
					// echo '</pre>';
				};
				
				
				
				?>
				<li>
					<h3><a href="<?echo $arItem["URL"]?>"><?echo $arItem["TITLE_FORMATED"]?></a></h3>
					<p><?echo $arItem["BODY_FORMATED"]?></p>
					<table>
						<tr>
							<td colspan="2"><a href="<?echo $arItem["URL"]?>" class="urlLink">http://<?echo $_SERVER['HTTP_HOST']?><?echo $arItem["URL"]?></a></td>
						</tr>
						<tr>
							<td>Раздел:</td>
							<td>
								<?if(isset($_REQUEST['oft_cab']) and $_REQUEST['oft_cab']=='on'):?>
									Офтальмологический кабинет
								<?else:?>
									<ul class="smallCrumbs"><li>Контактные линзы</li><li><?=$arr_item['PROPERTY_LENSTYPE_VALUE']?></li><li><?=$arr_item['PROPERTY_PRODUCER_VALUE']?></li><li><?=$arr_item['PROPERTY_USETIME_VALUE'].' дней'?></li></ul>
								<?endif;?>
							</td>
						</tr>
					</table>
				</li>
			<? } ?>
		</ul>
	<? } ?>
