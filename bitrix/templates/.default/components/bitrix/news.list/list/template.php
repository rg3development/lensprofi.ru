
<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<ul class="simpleList1">
	<? foreach($arResult["ITEMS"] as $arItem) { ?>
	<? $this -> AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this -> AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'))); ?>
		<li id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<h3><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></h3>
			<span class="date"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
			<p><?echo $arItem["PREVIEW_TEXT"];?></p><a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="furtherLink"><span>Подробнее</span>&nbsp;&raquo;</a>
		</li>
	<? } ?>
</ul>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
