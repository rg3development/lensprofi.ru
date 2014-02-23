<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if ($arResult["READY"]=="Y" || $arResult["DELAY"]=="Y" || $arResult["NOTAVAIL"]=="Y") { ?>
	в корзине: <noindex><a href="/personal/basket.php" id="enterBsk" rel="nofollow"><? echo kt::noun(count($arResult["ITEMS"]), array('товар', 'товара', 'товаров')); ?></a></noindex>
<? } else { ?>
	в корзине: <noindex><a href="#" id="enterBsk" rel="nofollow">нет товаров</a></noindex>
<? } ?>
