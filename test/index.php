<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>

<?
CModule::IncludeModule("sale");

/*
$arr_orders = array();
$arFilter = array();
$db_sales = CSaleOrder::GetList(array("DATE_INSERT" => "ASC"), $arFilter);
while ($arr_order = $db_sales->Fetch())
{
   $arr_orders[$arr_order['ID']] = $arr_order;
};
*/

// echo '<pre>';
// print_R($arr_orders);
// echo '</pre>';

$order_props = array();

$db_vals = CSaleOrderPropsValue::GetList(array("SORT" => "ASC"),array());
while($one_prop = $db_vals->GetNext())
{
	if($one_prop['CODE']!='')
	{
		$order_props[$one_prop['ORDER_ID']][$one_prop['CODE']] = $one_prop['VALUE'];
	}
};

// echo '<pre>';
// print_R($order_props);
// echo '</pre>';
	


if(!CModule::IncludeModule('rarus.sms4b')) echo 'fallure';

$SMS = '';
$db_mess = CEventMessage::GetByID(25);
if($arr_mess = $db_mess->GetNext())
{
	// echo '<pre>';
	// print_R($arr_mess['MESSAGE']);
	// echo '</pre>';
}

$SMS = $arr_mess['MESSAGE'];

foreach($order_props as $one)
{
	if($one['reminder']== date('d.m.Y')) //
	{			
		//email part
		if(isset($one['remind_on_email']) and $one['remind_on_email']='Y')
		{	
			echo '<br /><br />';
			echo 'email'.'<br/>';
			echo $one['email'].'<br />';	
			
			$arFields1 = Array(
									"EMAIL_TO" => $one['email'],
								);
		
			if(intval(CEvent::Send('LINZ_REMINDER', SITE_ID, $arFields1))>0) echo 'ok <br/>';
			
		};
		
		// SMS part
		if(isset($one['remind_on_sms']) and $one['remind_on_sms']='Y')
		{
				$num_to = '8'.$one['code'].$one['phone'];
				
				if(strlen($one['phone'])>7) // for KOSTION
				{
					$num_to = '8'.$one['phone'];
				};
				
				
				if(strlen($num_to)==10) //STRICT !!!
				{
							
					echo '<br /><br />';
					echo 'sms <br />';
					echo $SMS.'<br />';
					echo $num_to.'<br />';
					
					$SMS4B = new Csms4b();
					//if($SMS4B->SendSMS($SMS, $num_to)) {echo 'ok <br />';}
				};
				//die($num_to);
	
		};
		
		
	};
};

// -------------------------------------- FOR USER PROFILE -----------------------
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
		echo '<pre>';
		print_R($user);
		echo '</pre>';
		
		//email part
		if($user['UF_EMAIL_REMINDER']=1)
		{	
			echo '<br /><br />';
			echo 'email'.'<br/>';
			echo $user['EMAIL'].'<br />';	
			
			$arFields1 = Array(
									"EMAIL_TO" => $user['EMAIL'],
								);
		
			if(intval(CEvent::Send('LINZ_REMINDER', SITE_ID, $arFields1))>0) echo 'ok <br/>';
			
		};
		
		// SMS part
		if($user['UF_SMS_REMINDER']=1)
		{
				$num_to = '8'.$user['UF_PHONE_CODE'].$user['PERSONAL_PHONE'];
				
				if(strlen($user['PERSONAL_PHONE'])>7) // for KOSTION
				{
					$num_to = '8'.$user['PERSONAL_PHONE'];
				};
				
				
				if(strlen($num_to)==10) //STRICT !!!
				{
							
					echo '<br /><br />';
					echo 'sms <br />';
					echo $SMS.'<br />';
					echo $num_to.'<br />';
					
					$SMS4B = new Csms4b();
					//if($SMS4B->SendSMS($SMS, $num_to)) {echo 'ok <br />';};//
				};
				//die($num_to);
	
		};
		
	};
	
};

?>