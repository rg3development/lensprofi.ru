<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
// echo '<pre>';
// print_R($_REQUEST);
// echo '</pre>';

if(isset($_REQUEST['use_mail']) and $_REQUEST['use_mail'])
{
	global $USER;
	$arResult = $USER->SendPassword('', $_REQUEST['use_mail']);
	if($arResult["TYPE"] == "OK")
	{
		//echo 'OK';
		?>
		<p class="q7" style="display: block; margin-bottom: 10px">Письмо, содержащее ссылку для смены пароля, выслано на почту <?=$_REQUEST['use_mail']?> </p>
		<div style="padding: 0px 0 0 0; text-align: center">
			<img src="/img/butt_ok.png" class="pass_remind_closer" style="cursor: pointer; margin: 0 auto 0" alt="" />
		</div>
		<?
	}
};
?>

<?/*
*/?>