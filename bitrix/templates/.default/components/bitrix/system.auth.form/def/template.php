<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($arResult["FORM_TYPE"] == "login") { ?>
<?if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
	//ShowMessage($arResult['ERROR_MESSAGE']);
?>
<form class="reg" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
	<?if($arResult["BACKURL"] <> ''):?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<?endif?>
	<?foreach ($arResult["POST"] as $key => $value):?>
		<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
	<?endforeach?>
		<input type="hidden" name="AUTH_FORM" value="Y" />
		<input type="hidden" name="TYPE" value="AUTH" />
	<table>
		<tbody><tr>
			<td><span>Адрес электронной почты:</span><input type="text" class="w205" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" /></td>
			<td>
				<?if($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
				{
				?>
					<span class="q5"><?=$arResult['ERROR_MESSAGE']['MESSAGE'];?></span>
				<?
				}
				?>
			</td>
		</tr>
		<tr>
			<td><span>Пароль:</span><input class="w205" type="password" name="USER_PASSWORD" maxlength="50" /></td>
			<td><a href="#" class="q4" id="fpass" style="position:relative; top:8px">Забыли пароль?</a></td>
		</tr>
		<tr>
			<td colspan="2"><input type="checkbox" id="rem"><label for="rem">Запомнить пароль</label></td>
		</tr>
	</tbody></table>
	<div class="enterBt"><input type="image" name="Login" value="submit" src="/img/bt148a.gif" /></div>
</form>
<? } ?>
<? /*
<form class="reg" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?if($arResult["BACKURL"] <> ''):?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
<?foreach ($arResult["POST"] as $key => $value):?>
	<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
<?endforeach?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="AUTH" />
	<table style="margin: 20px auto 30px;" align="center">
		<tr>
			<td colspan="2">
			Адрес электронной почты:<br />
			<input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" size="17" /></td>
		</tr>
		<tr>
			<td colspan="2">
			Пароль<br />
			<input type="password" name="USER_PASSWORD" maxlength="50" size="17" /></td>
		</tr>
<?if ($arResult["CAPTCHA_CODE"]):?>
		<tr>
			<td colspan="2">
			<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
			<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br /><br />
			<input type="text" name="captcha_word" maxlength="50" value="" /></td>
		</tr>
<?endif?>
		<tr>
			<td colspan="2"><input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" /></td>
		</tr>
	</table>
</form>

<? /*
//if($arResult["FORM_TYPE"] == "login")
else:
?>

<form action="<?=$arResult["AUTH_URL"]?>">
	<table width="95%">
		<tr>
			<td align="center">
				<?=$arResult["USER_NAME"]?><br />
				[<?=$arResult["USER_LOGIN"]?>]<br />
				<a href="<?=$arResult["PROFILE_URL"]?>" title="<?=GetMessage("AUTH_PROFILE")?>"><?=GetMessage("AUTH_PROFILE")?></a><br />
			</td>
		</tr>
		<tr>
			<td align="center">
			<?foreach ($arResult["GET"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endforeach?>
			<input type="hidden" name="logout" value="yes" />
			<input type="submit" name="logout_butt" value="<?=GetMessage("AUTH_LOGOUT_BUTTON")?>" />
			</td>
		</tr>
	</table>
</form>
<?endif?> */?>
