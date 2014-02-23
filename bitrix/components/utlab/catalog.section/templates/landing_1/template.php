<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="catalog-section">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<div class="landing-page-1">
	<table cellpadding="0" cellspacing="0" border="0">
		<tbody>
			<? foreach($arResult["ITEMS"] as $cell=>$arElement): ?>
			<?
			$this->AddEditAction($arElement['ID'], $arElement['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arElement['ID'], $arElement['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));
			?>
			<?if($cell%$arParams["LINE_ELEMENT_COUNT"] == 0):?>
			<tr>
			<?endif;?>
				<td valign="top">
					<table cellpadding="0" cellspacing="2" border="0" class="landing-table-1">
						<tbody>
							<tr>
								<td valign="top">
									<p class="landing-price-catalogue">
										<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" class="landing-page-1-title"><?=$arElement["NAME"]?></a>
									</p>
								</td>
							</tr>
							<tr>
								<td valign="top" class="landing_1-img">
									<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img border="0" src="<?=$arElement["PREVIEW_PICTURE"]["SRC"]?>" width="<?=$arElement["PREVIEW_PICTURE"]["WIDTH"]?>" height="<?=$arElement["PREVIEW_PICTURE"]["HEIGHT"]?>" alt="<?=$arElement["NAME"]?>" title="<?=$arElement["NAME"]?>" /></a>
								</td>
							</tr>
							<tr>
								<td valign="top">
									<p class="landing-price-catalogue">
									<?if($arElement["PRICES"]["RETAIL"]["CAN_ACCESS"] = "Y"):?>
										<?if($arElement["PRICES"]["RETAIL"]["DISCOUNT_VALUE"] < $arElement["PRICES"]["RETAIL"]["VALUE"]):?>
											<s><?=$arPrice["PRINT_VALUE"]?></s> <span class="catalog-price"><?=$arElement["PRICES"]["RETAIL"]["PRINT_DISCOUNT_VALUE"]?></span>
										<?else:?>
											<span class="catalog-price"><?=$arElement["PRICES"]["RETAIL"]["PRINT_VALUE"]?></span>
										<?endif?>
									<?endif;?>
									<div class="landing_1-button-submit">
										<noindex class="landing_1-buy-button-block">
											<a href="<?=$arElement["DETAIL_PAGE_URL"]?>" rel="nofollow" class="button"><img src="/img/buy-button.png"></a>
										</noindex>
									</div>
									</p>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			<?$cell++;
			if($cell%$arParams["LINE_ELEMENT_COUNT"] == 0):?>
				</tr>
			<?endif?>
			<? endforeach; ?>
		</tbody>
	</table>
</div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
