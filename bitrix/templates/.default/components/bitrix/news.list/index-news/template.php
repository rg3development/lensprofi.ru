<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<? $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'))); ?>
	<?/* <span><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span> */?>
	<p id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<a <?/*class="index-news-link"*/?> href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"];?></a> <?=(mb_strlen($arItem["PREVIEW_TEXT"])>45)?mb_substr($arItem["PREVIEW_TEXT"], 0, 26, "UTF-8").'...':$arItem["PREVIEW_TEXT"];?>
	</p>
	<?break;?>
<?endforeach;?>
