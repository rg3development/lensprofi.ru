<div class="auth">
	<div class="bskContent">
		<?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.small", "top-basket", Array("PATH_TO_BASKET" => "/personal/basket.php", "PATH_TO_ORDER" => "/personal/order.php"), false);?>
	</div>	
	<? global $USER;
	if($USER -> IsAuthorized()) { ?>
		<? $APPLICATION -> IncludeFile("/inc/loged.php"); ?>
	<? } else { ?>
		<? $APPLICATION -> IncludeFile("/inc/private.php"); ?>
	<? } ?>
</div>