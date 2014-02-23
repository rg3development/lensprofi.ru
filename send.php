<?				  
define(ROOT_DIR, '/var/www/vhosts/lensprofi.ru/httpdocs');
$_SERVER['DOCUMENT_ROOT'] = ROOT_DIR;
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
define("LANG", "ru");
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

ini_set("max_execution_time","36000");
ini_set("memory_limit", "500M");
ini_set('display_errors', 1);

set_time_limit(0);

error_reporting(E_ALL ^ E_NOTICE);
?>

<?require($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");?>
<?CEvent::CheckEvents();?>
<?
if(!defined('LOG_FILENAME'))
{
	define('LOG_FILENAME', $_SERVER["DOCUMENT_ROOT"]."/log_send.txt");
};
//AddMessage2Log('success exec at '.date('d.m.Y H:i'),'iblock');
?>