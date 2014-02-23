<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<ul>
<li><noindex><a href="#" rel="nofollow" id="filter1-t">Каталог</a></noindex></li>
<?if (!empty($arResult)):?>
	<? foreach($arResult as $arItem):
		if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) { continue; } ?>
		<?if($arItem["SELECTED"]):?>
			<li><noindex><a href="<?=$arItem["LINK"]?>" class="selected" rel="nofollow"><?=$arItem["TEXT"]?></a></noindex></li>
		<?else:?>
			<li><noindex><a href="<?=$arItem["LINK"]?>" rel="nofollow"><?=$arItem["TEXT"]?></a></noindex></li>
		<?endif?>
	<?endforeach?>
	<li>
		<select size="1" name="list1" name="">
			<option value="Список линз">Список линз</option>
			<? echo kt::listLenses(); ?>
		</select>
	</li>
	<div style="clear:both;"></div>
<?endif?>
</ul>