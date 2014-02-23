<?//Функция проверки на соответсивие минимальным системным требованиям
function CheckForReqSettings()
{
	$phpver = phpversion();
    $generalphpver = explode(".", $phpver);
    
    if ($generalphpver[0] < 5):
    	$phperrorprint = ";phperror";	
 	endif;
 	
    $msqlver = mysql_get_server_info();
    $msqlgenralver = explode(".",$msqlver);
    
    if ($msqlgenralver[0] < 4):
    	$msqlerrorprint = ";msqlerror";
   	endif;
   	if ($msqlgenralver[0] == 4 && $msqlgenralver[1] < 1):
    	$msqlerrorprint = ";msqlerror";
   	endif;
 	
	if (!function_exists('curl_init'))
    {
		$curlerrorprint = ";curlerror";	
    }
    
	if (isset($phperrorprint) || isset($msqlerrorprint) || isset($curlerrorprint))
	{
		$result = $phperrorprint.$msqlerrorprint.$curlerrorprint;
		return $result;	
	}
}?>

<?$result = CheckForReqSettings();
$resultt = explode(";", $result);

foreach ($resultt as $arIndex)
{
	if ($arIndex != "")
	{
		$includeerrors[] = $arIndex; 
	}
}

if (count($includeerrors) == 0)
{
	IncludeModuleLangFile(__FILE__);
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/rarus.sms4b/classes/mysql/sms4b.php");
}?>