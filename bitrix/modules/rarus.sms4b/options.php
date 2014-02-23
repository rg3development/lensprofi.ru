<?//Функция проверки на соответсивие минимальным системным требованиям
function CheckForReqSett()
{
	IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/options.php");
	IncludeModuleLangFile(__FILE__);
	
	$phpver = phpversion();
	$generalphpver = explode(".", $phpver);

	if ($generalphpver[0] < 5):
		$phperrorprint = '<p><strong style="font-size: 12px; color:red;">'.GetMessage('PHPERROR').$phpver.GetMessage('PHPERROR2').'</strong></p>';
	endif;

	$msqlver = mysql_get_server_info();
	$msqlgenralver = explode(".",$msqlver);

	if ($msqlgenralver[0] < 4):
		$msqlerrorprint = '<p><strong style="font-size: 12px; color:red;">'.GetMessage('MSQLERROR').$msqlver.GetMessage('MSQLERROR2').'</strong></p>';
	endif;
	if ($msqlgenralver[0] == 4 && $msqlgenralver[1] < 1):
		$msqlerrorprint = '<p><strong style="font-size: 12px; color:red;">'.GetMessage('MSQLERROR').$msqlver.GetMessage('MSQLERROR2').'</strong></p>';       
	endif;

	if (!function_exists('curl_init'))
	{
		$curlerrorprint = '<p><strong style="font-size: 12px; color:red;">'.GetMessage('CURLERROR').'</strong></p>';	
	}
	
	$result = $phperrorprint.$msqlerrorprint.$curlerrorprint;
	
	return $result;
}
//!Функция проверки на соответсивие минимальным системным требованиям

$result = CheckForReqSett();

$resultsettings = explode("</strong>", $result);

foreach ($resultsettings as $arIndex)
{
	if ($arIndex != "")
	{
		$errors[] = $arIndex;
	}
}

if (count($errors) == 0)
{
	$module_id = "rarus.sms4b";
	IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/options.php");
	IncludeModuleLangFile(__FILE__);

	$SMS_RIGHT = $APPLICATION->GetGroupRight($module_id);

	if ($SMS_RIGHT >="R"):
	$gmt = array(	4 => "(+4) ".GetMessage('MOSCOW'),
				3 => "(+3) ".GetMessage('KALININGRAD'),
				6 => "(+6) ".GetMessage('EKATA'),
				7 => "(+7) ".GetMessage('OMSK'),
				8 => "(+8) ".GetMessage('KEMEROVO'),
				9 => "(+9) ".GetMessage('IRKYTSK'),
				10 => "(+10) ".GetMessage('CHITA'),
				11 => "(+11) ".GetMessage('VLADIVOSTOK'),
				12 => "(+12) ".GetMessage('MAGA'),
	);

	CModule::IncludeModule("rarus.sms4b");

	global $SMS4B;
 
	$arrDefSender = $SMS4B->GetSender();
		
	foreach($arrDefSender as $val)
	{
		$arrDF[addslashes(htmlspecialchars_decode($val))] = addslashes(htmlspecialchars_decode($val));
	}

	$arAllOptions = array(
		array("host", GetMessage("opt_host"), "https://sms4b.ru", array("text", 35)),
		array("proxy_host", GetMessage("opt_proxy_host"), "", array("text", 35)),
		array("proxy_port", GetMessage("opt_proxy_port"), "", array("text", 35)),
		array("proxy_use", GetMessage("opt_proxy_use"),"n", array("checkbox", "y")),
		array("login", GetMessage("opt_login"), "", array("text",35)),
		array("password", GetMessage("opt_password"), "", array("text",35)),
		array("gmt", GetMessage("opt_gmt"), 3, array("selectbox", $gmt)),
		array("defsender", GetMessage("opt_defsender"), 3, array("selectbox", $arrDF)),
		array("sms_sym_count",GetMessage("sms_sym_count"),"", array("text",35)), 
		array("use_translit", GetMessage("use_translit"),"y", array("checkbox", "y")),
	);

	if (CModule::IncludeModule("subscribe"))
	{
		$arAllOptions_events[] = array("event_subscribe_confirm", getmessage("opt_subscribe_confirm"),"Y", array("checkbox", "y"));
	}

	if (CModule::IncludeModule("sale"))
	{
		$arAllOptions_events[] = array("event_sale_status_changed", getmessage("opt_sale_status_changed"),"n", array("checkbox", "y"));
		$arAllOptions_events[] = array("event_sale_order_paid", getmessage("opt_order_paid"),"n", array("checkbox", "y"));
		$arAllOptions_events[] = array("event_sale_order_delivery", getmessage("opt_order_delivery"),"n", array("checkbox", "y"));
		$arAllOptions_events[] = array("event_sale_order_cancel", getmessage("opt_order_cancel"),"n", array("checkbox", "y"));
		$arAllOptions_events[] = array("event_sale_new_order", getmessage("opt_new_order"),"n", array("checkbox", "y"));
	}

	if (CModule::IncludeModule("support"))
		$arAllOptions_events[] = array("event_ticket_new_for_techsupport", getmessage("opt_ticket_new_for_techsupport"),"n", array("checkbox", "y"));

	if (CModule::IncludeModule("intranet"))
	{
		$arAllOptions_events[] = array("event_corp_add_calendar", getmessage("opt_corp_add_calendar"),"n", array("checkbox", "y"));
		$arAllOptions_events[] = array("event_corp_update_calendar", getmessage("opt_corp_update_calendar"),"n", array("checkbox", "y"));
		#$arAllOptions_events[] = array("event_corp_reminder_calendar", getmessage("opt_corp_reminder_calendar"),"n", array("checkbox", "y"));
	}

	//настройки для публичной компоненты отправки
	$arPublicOptions = array( 
		'defsenderPublic' => array("defsenderPublic", GetMessage("def_public_name"), 3, array("selectbox", $arrDF)),
	);


	$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("SMS4B_TAB_PARAM"), "ICON" => "sms4b_settings", "TITLE" => GetMessage("SMS4B_TAB_TITLE_PARAM")),
		array("DIV" => "edit2", "TAB" => GetMessage("SMS4B_TAB_EVENTS"), "ICON" => "sms4b_settings", "TITLE" => GetMessage("SMS4B_TAB_TITLE_EVENTS")),
		array("DIV" => "edit3", "TAB" => GetMessage("SMS4B_TAB_TITLE_PUBLIC_SEND"), "ICON" => "sms4b_settings", "TITLE" => GetMessage("SMS4B_TAB_TITLE_PUBLIC_SEND")),
		array("DIV" => "edit4", "TAB" => GetMessage("SMS4B_TAB_RIGHTS"), "ICON" => "sms4b_settings", "TITLE" => GetMessage("SMS4B_TAB_TITLE_RIGHTS"))
	);

	$tabControl = new CAdminTabControl("tabControl", $aTabs);

	if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults)>0 && $SMS_RIGHT >= "W" && check_bitrix_sessid())
	{
		if(strlen($RestoreDefaults)>0)
		{
			COption::RemoveOption("rarus.sms4b");
			$APPLICATION->DelGroupRight("rarus.sms4b");
		}
		else
		{	
			COption::RemoveOption("rarus.sms4b", "sid");
			
			foreach($arAllOptions as $arOption)
			{
				$name=$arOption[0];
				$val=$_REQUEST[$name];
				if($arOption[2][0]=="checkbox" && $val!="Y")
					$val="N";
				COption::SetOptionString("rarus.sms4b", $name, $val, $arOption[1]);
			}
	 
			foreach($arAllOptions_events as $arOption)
			{
				$name=$arOption[0];
				$val=$_REQUEST[$name];
				if($arOption[2][0]=="checkbox" && $val!="Y")
					$val="N";
				COption::SetOptionString("rarus.sms4b", $name, $val, $arOption[1]);
			}
			
			foreach($arPublicOptions as $arOption)
			{
				$name=$arOption[0];
				$val=$_REQUEST[$name];
				if($arOption[2][0]=="checkbox" && $val!="Y")
					$val="N";
				COption::SetOptionString("rarus.sms4b", $name, $val, $arOption[1]);
			}
		}
	}

	$tabControl->Begin();?>
	<form method="post" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>">
	<?
		/*echo "<pre>";print_r($arrDefSender);echo "</pre>";*/
			
		if($SMS4B->getLogin() <> '' && $SMS4B->getPassword() <> '')
		{
			if(count($arrDefSender) > 0 && $arrDefSender[0] <> '')
			{
				ShowNote(GetMessage('success_connect'));
			}
			else
			{
				ShowError(GetMessage('none_connect'));?>
				<?=GetMessage('registry_information');
			}
		}
		else
			ShowError(GetMessage('no_log_and_pass'));

	$tabControl->BeginNextTab();
	foreach($arAllOptions as $arOption):
		__AdmSettingsDrawRow("rarus.sms4b", $arOption);
	endforeach;

	$tabControl->BeginNextTab();
	foreach($arAllOptions_events as $arOption):
		__AdmSettingsDrawRow("rarus.sms4b", $arOption);
	endforeach;

	$tabControl->BeginNextTab();
	foreach($arPublicOptions as $key => $arOption):
		__AdmSettingsDrawRow("rarus.sms4b", $arOption);
	endforeach;

	$tabControl->BeginNextTab();?>
	<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>
	<?
	if($REQUEST_METHOD=="POST" && strlen($Update.$Apply.$RestoreDefaults)>0 && check_bitrix_sessid()) 
	{
		if(strlen($Update)>0 && strlen($_REQUEST["back_url_settings"])>0)
			LocalRedirect($_REQUEST["back_url_settings"]);
		else
			LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());	
	}
	?>

	<?$tabControl->Buttons();?>
		<input <?if ($SMS_RIGHT<"W") echo "disabled" ?> type="submit" name="Update" value="<?=GetMessage("MAIN_SAVE")?>" title="<?=GetMessage("MAIN_OPT_SAVE_TITLE")?>">
		<input <?if ($SMS_RIGHT<"W") echo "disabled" ?> type="submit" name="Apply" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
		<?if(strlen($_REQUEST["back_url_settings"])>0):?>
			<input type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?=htmlspecialchars(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
			<input type="hidden" name="back_url_settings" value="<?=htmlspecialchars($_REQUEST["back_url_settings"])?>">
		<?endif?>
		<input <?if ($SMS_RIGHT<"W") echo "disabled" ?> type="submit" name="RestoreDefaults" title="<?=GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="confirm('<?=AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?=GetMessage("MAIN_RESTORE_DEFAULTS")?>">
		<?=bitrix_sessid_post();?>
	<?$tabControl->End();?>
	</form>
	<?else:?>
		<?=CAdminMessage::ShowMessage(GetMessage('NO_RIGHTS_FOR_VIEWING'));?>
	<?endif;
}
elseif (count($errors) > 0)
{
	foreach ($resultsettings as $arIndex)
	{
		if ($arIndex != "")
		{
			echo "<pre>";print_r($arIndex);echo "</pre>";
		}
	}
}?>