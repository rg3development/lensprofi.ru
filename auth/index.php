<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if (isset($_REQUEST["backurl"]) && strlen($_REQUEST["backurl"])>0) 
	LocalRedirect($backurl);

$APPLICATION->SetTitle("Авторизация");
?>
<p>Вы успешно авторизовались.<br />Через несколько секунд Вы будете автоматически перенеправлены на главную страницу сайта</p>
<script type="text/javascript">
	setTimeout('window.location="/"', 3000);
</script>
 
<p><a href="<?=SITE_DIR?>">Вернуться на главную страницу</a></p>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>