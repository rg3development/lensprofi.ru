<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

//ShowMessage($arParams["~AUTH_RESULT"]);

?>

<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<? if (strlen($arResult["BACKURL"]) > 0) { ?><input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><? } ?>
	<table>
		<tbody><tr>
			<td><span>Адрес электронной почты:</span><input type="text" class="w205"></td>
			<td><p class="q5" style="padding-top:30px">Введите адрес электронной почты, на который будет выслан пароль</p></td>
		</tr>
	</tbody></table>
	<div class="enterBt" id="pwd1"><input src="/img/bt148b.gif" type="image" name="send_account_info" value="submit" /></div>
	<p class="q7">Пароль успешно выслан на почту lavrentiev@yandex.ru </p>
</form>

<? /*
<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<? if (strlen($arResult["BACKURL"]) > 0) { ?><input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><? } ?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">
<table class="data-table bx-forgotpass-table">
	<tbody>
		<tr>
			<td><?=GetMessage("AUTH_LOGIN")?></td>
			<td><input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" />
			</td>
		</tr>
		<tr> 
			<td><?=GetMessage("AUTH_EMAIL")?></td>
			<td>
				<input type="text" name="USER_EMAIL" maxlength="255" />
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr> 
			<td colspan="2">
				<input type="submit" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" />
			</td>
		</tr>
	</tfoot>
</table>

</form>
*/ ?>
