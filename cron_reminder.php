<?		

//define(ROOT_DIR, '/var/www/vhosts/lensprofi.ru/httpdocs');
$DOCUMENT_ROOT = '/var/www/vhosts/lensprofi.ru/httpdocs';
//$_SERVER['DOCUMENT_ROOT'] = '/var/www/vhosts/lensprofi.ru/httpdocs';
//$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
define("LANG", "ru");
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);

ini_set("max_execution_time","36000");
ini_set("memory_limit", "500M");
ini_set('display_errors', 1);

ini_set('php_value mbstring.func_overload', 2);
ini_set('mbstring.internal_encoding', 'UTF-8');

set_time_limit(0);

error_reporting(E_ALL ^ E_NOTICE);

?>
<?require($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_before.php");?>

<?
CModule::IncludeModule("sale");

// -------------------------------------- FOR USER PROFILE -----------------------

if(!CModule::IncludeModule('rarus.sms4b')) echo 'fallure';

define('SITE_ID', 's1');

$SMS = '';
$db_mess = CEventMessage::GetByID(25);
if($arr_mess = $db_mess->GetNext())
{
}

$SMS = $arr_mess['MESSAGE'];


// DEFINE LOG
if(!defined('LOG_FILENAME'))
{
	define('LOG_FILENAME', $DOCUMENT_ROOT."/log_reminder.txt");
};

$SEND_COUNTER = 0;
$SUCCESS_COUNTER = 0;

$ARR_USERS = array();
$filter = array();
$rsUsers = CUser::GetList(($by="name"), ($order="asc"), $filter, array('SELECT'=>array('UF_*')));
while($one_user = $rsUsers->GetNext())
{
	$ARR_USERS[] = $one_user;
};


foreach($ARR_USERS as $user)
{
	if(isset($user['UF_DATE_REMIND'][0]) and $user['UF_DATE_REMIND'][0]==date('d.m.Y'))
	{		
		//email part
		if($user['UF_EMAIL_REMINDER']=1)
		{				
			$arFields1 = Array(
									"EMAIL_TO" => $user['EMAIL'],
								);
			
			
			if(intval(CEvent::Send('LINZ_REMINDER', 's1', $arFields1))>0) 
			{
				//echo 'ok <br/>';
			};
		};
		
		
		
		// SMS part
		if($user['UF_SMS_REMINDER']=1)
		{
				
				$num_to = '8'.$user['UF_PHONE_CODE'].$user['PERSONAL_PHONE'];
				
				if(strlen($user['PERSONAL_PHONE'])>7) // for KOSTION
				{
					$num_to = '8'.$user['PERSONAL_PHONE'];
				};
				
				if(strlen($num_to)==11) //STRICT !!!
				{
					
					$SEND_COUNTER++;
					
					$SMS4B = new Csms4b();
					//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
					if($SMS4B->SendSMS($SMS, $num_to)) 
					{
						$SUCCESS_COUNTER++;
					};
					AddMessage2Log('***'.$SMS4B->LastError);
				};	
		};
		
	};
	
};

AddMessage2Log(date('d.m.Y H:i').' try SMS send='.$SEND_COUNTER.', success='.$SUCCESS_COUNTER.', SMS='.$SMS.', site='.SITE_ID, 'iblock');
?>