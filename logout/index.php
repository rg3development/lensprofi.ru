<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("�����");?>
<? if($USER -> isAuthorized()) { $USER -> Logout(); LocalRedirect('/index.php'); } else { LocalRedirect('/index.php'); } ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>            
