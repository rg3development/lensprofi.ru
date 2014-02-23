<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<style type="text/css">
	.new_life tr td
	{
		padding: 0 3px;
	}
</style>
<?
$page_url='';
$page_name ='';
$page_descr ='';

$arr_uri = explode('?', $_SERVER['REQUEST_URI']);
$uri_only = $arr_uri[0];
$page_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

$page_name =$arResult['NAME'];

$page_descr =$arResult["PREVIEW_TEXT"];
?>
<div style="padding: 0px 0 0px 0; text-align: right">
	<table class="verst new_life" style="margin: 0 0 0 auto">
		<tr>
			<td>
				<a href="http://vkontakte.ru/share.php?url=<?=$page_url?>&title=<?=$page_name?>&description=<?=$page_descr?>" target="_blank" rel="nofollow"><img src="/images/vkontakte.jpg" alt="" /></a>
			</td>
			<td>
				<a class="Tiptip" href="https://www.facebook.com/sharer.php?u=http://<?=SITE_SERVER_NAME?>:80<?=$_SERVER['REQUEST_URI']?>&t=<?=$page_name?>" target="_blank" rel="nofollow"><img  src="/images/facebook.jpg" alt="" /></a>
			</td>
			<td>
				<a class="Tiptip" href="http://twitter.com/share?&text=<?=$page_name?>&url=<?=$page_url?>" target="_blank" rel="nofollow"><img  src="/images/twitter.jpg" alt="" /></a>
			</td>
		</tr>
	</table>
</div>
<div class="news-detail">
	<?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
		<p><img class="detail_picture" border="0" src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["NAME"]?>"  title="<?=$arResult["NAME"]?>" /></p>
		
		
	<?endif?>
	<?if($arParams["DISPLAY_DATE"]!="N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
		<div><span class="news-date-time"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span></div>
	<?endif;?>
	<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
		<?/*<h3><?=$arResult["NAME"]?></h3>*/?>
	<?endif;?>
	<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<p><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
	<?endif;?>
	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
 	<?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?echo $arResult["DETAIL_TEXT"];?>
 	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
	<div style="clear:both"></div>
	<br />
	<?foreach($arResult["FIELDS"] as $code=>$value):?>
			<?=GetMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
			<br />
	<?endforeach;?>
	<?foreach($arResult["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>

		<?=$arProperty["NAME"]?>:&nbsp;
		<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
			<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
		<?else:?>
			<?=$arProperty["DISPLAY_VALUE"];?>
		<?endif?>
		<br />
	<?endforeach;?>
	<?
	if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
	{
		?>
		<div class="news-detail-share">
			<noindex>
			<?
			$APPLICATION->IncludeComponent("bitrix:main.share", "", array(
					"HANDLERS" => $arParams["SHARE_HANDLERS"],
					"PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
					"PAGE_TITLE" => $arResult["~NAME"],
					"SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
					"SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
					"HIDE" => $arParams["SHARE_HIDE"],
				),
				$component,
				array("HIDE_ICONS" => "Y")
			);
			?>
			</noindex>
		</div>
		<?
	}
	?>
</div>