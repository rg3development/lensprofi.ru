<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*
------------------------------------------------------------------------------
	sms4b_module.config
	version 1.0.0
	Wizard for adjustment of sms4b module.
	Needed for convenient get of demo account.
------------------------------------------------------------------------------
*/

/*
	functions for correct viewing of forms elements
	@ShowCheckedSelectField - for select
	@ShowCheckedCheckboxField - for checkbox
*/
function ShowCheckedSelectField($arr, $arname, $checkedKey, $params = "")
{
	if (is_array($arr) && !empty($arr)){
		$res = "<select name = '$arname' $params >";
		foreach($arr as $key => $val)
		{
			$res .= "<option value = '$key' ";
			if (($key == $checkedKey) || ($val == $checkedKey))
			{
				$res .= " SELECTED ";
			} 
			$res .= "> $val </option>";
		}
		$res .= "</select>";
		return $res; 
	}
	else
	{ 
		return false;
	}
}

function ShowCheckedCheckboxField ($name, $val, $is_checked, $params = "")
{
	$res = "<input type = 'checkbox' name = '$name' value = $val";
	if (($is_checked == 'Y') || ($is_checked == 'y'))
		$res .= " checked ";
	if (strlen($params) > 0)
		$res .= $params;
	$res .= " >";  
	return $res;
}


//step 0 - simple info
class Step0 extends CWizardStep
{
	function InitStep()
	{
		if (IsModuleInstalled("rarus.sms4b")){
			$this->SetTitle(GetMessage("WW_STEP0_TITLE"));   
			$this->SetStepID("step0");
			$this->SetNextStep("step1");
			$this->SetCancelStep("cancel");
		}
		else
		{
			$this->SetTitle(GetMessage("WW_ERROR_TITLE"));
			$this->SetStepID("error");
			$this->SetCancelStep("cancel");
			$this->SetCancelCaption(GetMessage("WW_CLOSE"));
		}
		
	}

	function ShowStep()
	{
		if (IsModuleInstalled("rarus.sms4b"))
		{
			$this->content = GetMessage("WW_STEP0_DESCR");
		} 
		else
		{
			$this->content = GetMessage("WW_ERROR_DESCR"); 
		}
	}
}

// step 1 - here user can chose what he want to do: register account or to enter module options
class Step1 extends CWizardStep
{
	function InitStep()
	{
		$this->SetTitle(GetMessage("WW_STEP1_TITLE"));
		$this->SetNextStep("step2");
		$this->SetStepID("step1");
		$this->SetPrevStep("step0_1"); 
		$this->SetCancelStep("cancel");
	}

	function ShowStep()
	{
		$serv_login	= COption::GetOptionString("rarus.sms4b", "login");
	
		$arMode = array(0 => GetMessage("WW_STEP1_DEMO"),
						1 => GetMessage("WW_STEP1_ENTERING_OPTIONS"));

		$this->content = '<p><b>'.GetMessage("WW_STEP1_TEXT1").'</b></p><br />';
		$this->content .= '<table border = 0 cellspacing = "10%">';
		if (strlen($serv_login) > 0)
		{
			$this->content .= '<tr><td><input type = "radio" name = "modeId" id="register" value = "1" />  <label for="register">'.GetMessage("WW_STEP1_TEXT3").'</label></td></tr>';
			$this->content .= '<tr><td><input type = "radio" name = "modeId" id="options" value = "0" checked />  <label for="options">'.GetMessage("WW_STEP1_TEXT2").'</label></td></tr>';
		} 
		else
		{
			$this->content .= '<tr><td><input type = "radio" name = "modeId" id="register" value = "1" checked />  <label for="register">'.GetMessage("WW_STEP1_TEXT3").'</label></td></tr>';       	
			$this->content .= '<tr><td><input type = "radio" name = "modeId" id="options" value = "0" />  <label for="options">'.GetMessage("WW_STEP1_TEXT2").'</label></td></tr>';     
		}
		$this->content .= '</table>';
	}
	
	function OnPostForm ()
	{
		$wizard =& $this->GetWizard();

		if($wizard->IsNextButtonClick())
		{
			$mode = $_REQUEST["modeId"];
			if ($mode == 0) 
				$wizard->SetCurrentStep("step2_1");
			else 
				$wizard->SetCurrentStep("step2_2");
		}
	}
}


// step 2-1 entering module options
class Step2_1 extends CWizardStep
{
	function InitStep()
	{
		$this->SetTitle(GetMessage("WW_STEP2_1_TITLE"));
		$this->SetNextStep("step3_1");
		$this->SetStepID("step2_1");
		$this->SetPrevStep("step1"); 
		$this->SetCancelStep("cancel");
	}

	function ShowStep()
	{
		$arsGmt = array(	4 => "(+4) ".GetMessage('MOSCOW'),
				3 => "(+3) ".GetMessage('KALININGRAD'),
				6 => "(+6) ".GetMessage('EKATA'),
				7 => "(+7) ".GetMessage('OMSK'),
				8 => "(+8) ".GetMessage('KEMEROVO'),
				9 => "(+9) ".GetMessage('IRKYTSK'),
				10 => "(+10) ".GetMessage('CHITA'),
				11 => "(+11) ".GetMessage('VLADIVOSTOK'),
				12 => "(+12) ".GetMessage('MAGA'),
		);
		
 
		$curr_host 			= COption::GetOptionString("rarus.sms4b", "host");  
		$curr_proxy_host 	= COption::GetOptionString("rarus.sms4b", "proxy_host");  
		$curr_proxy_port	= COption::GetOptionString("rarus.sms4b", "proxy_port");  
		$curr_proxy_use		= COption::GetOptionString("rarus.sms4b", "proxy_use");  
		$curr_login			= COption::GetOptionString("rarus.sms4b", "login");  
		$curr_password		= COption::GetOptionString("rarus.sms4b", "password");  
		$curr_gmt 			= COption::GetOptionString("rarus.sms4b", "gmt");
		$curr_sms_sym_count		= COption::GetOptionString("rarus.sms4b", "sms_sym_count");
		$curr_use_translit	= COption::GetOptionString("rarus.sms4b", "use_translit"); 
		
		$this->content = '<p><b>'.GetMessage("WW_STEP2_1_DESCR").': </b></p>';
		$this->content .= '<table cellspacing=2%>';
		$this->content .= '<tr><td>'.GetMessage("WW_STEP2_1_TEXT1").": </td><td><input type = 'text' name = 'host' size = '20' value = $curr_host></td></tr>";
		$this->content .= '<tr><td>'.GetMessage("WW_STEP2_1_TEXT4").': </td><td>'.ShowCheckedCheckboxField("proxy_use", "Y", $curr_proxy_use).'</td></tr>';
		$this->content .= '<tr><td>'.GetMessage("WW_STEP2_1_TEXT2").":</span> </td><td><input type = 'text' name = 'proxy_host'  size = '20' value = $curr_proxy_host></td></tr>";
		$this->content .= '<tr><td>'.GetMessage("WW_STEP2_1_TEXT3").":</span></td><td><input type = 'text' name = 'proxy_port'  size = '20' value = $curr_proxy_port></td></tr>";  
		$this->content .= '<tr><td>'.GetMessage("WW_STEP2_1_TEXT5").": </td><td><input type = 'text' name = 'login' size = '20' value = $curr_login></td></tr>"; 
		$this->content .= '<tr><td>'.GetMessage("WW_STEP2_1_TEXT6").": </td><td><input type = 'password' name = 'password' size = '20' value = $curr_password></td></tr>";       
		$this->content .= '<tr><td>'.GetMessage("WW_STEP2_1_TEXT7").': </td><td>'.ShowCheckedSelectField($arsGmt, "gmt", $curr_gmt, "style = 'width: 365px !important;'").'</td></tr>';
		$this->content .= '<tr><td>'.GetMessage("WW_STEP2_1_TEXT8").": </td><td><input type = 'text' name = 'sms_sym_count' size = '20' value = $curr_sms_sym_count><td></tr>";
		$this->content .= '<tr><td>'.GetMessage("WW_STEP2_1_TEXT9").': </td><td>'.ShowCheckedCheckboxField("use_translit", "Y", $curr_use_translit).'</td></tr>'; 
		$this->content .= '</table>';   
	}

	function OnPostForm()
	{
		$wizard = &$this->GetWizard();
		if ($wizard->IsNextButtonClick())
		{
			$wizard->SetVar("host", $_REQUEST['host']);
			$wizard->SetVar("proxy_host", $_REQUEST["proxy_host"]);
			$wizard->SetVar("proxy_port", $_REQUEST["proxy_port"]);
			$wizard->SetVar("proxy_use", $_REQUEST["proxy_use"]);
			$wizard->SetVar("login", $_REQUEST["login"]);
			$wizard->SetVar("password", $_REQUEST["password"]);
			$wizard->SetVar("gmt", $_REQUEST["gmt"]);
			$wizard->SetVar("sms_sym_count", $_REQUEST["sms_sym_count"]);
			$wizard->SetVar("use_translit", $_REQUEST["use_translit"]);
		}
	}
}


// step 3-1 setting default sender
// if couldn't take account of user, we show him error message
// if everything ok, we are forming list of names for select
class Step3_1 extends CWizardStep
{
	function InitStep()
	{
		$this->SetTitle(GetMessage("WW_STEP3_1_TITLE"));
		$this->SetNextStep("step4_1");
		$this->SetStepID("step3_1");
		$this->SetPrevStep("step2_1"); 
		$this->SetCancelStep("cancel");
	}

	function ShowStep()  
	{
		//get wizard object
		$wizard = &$this->GetWizard();
		
		if ($wizard->IsNextButtonClick())
		{
			//delete parameters from DataBase
			//this is needed for refresh
			COption::RemoveOption("rarus.sms4b","host");
			COption::RemoveOption("rarus.sms4b","proxy_host");
			COption::RemoveOption("rarus.sms4b","proxy_port");
			COption::RemoveOption("rarus.sms4b","proxy_use");
			COption::RemoveOption("rarus.sms4b","login");
			COption::RemoveOption("rarus.sms4b","password");
			COption::RemoveOption("rarus.sms4b","gmt");
			COption::RemoveOption("rarus.sms4b","sms_sym_count");
			COption::RemoveOption("rarus.sms4b","use_translit");
			
			
			//now getting and setting new parameters
			$host = $wizard->GetVar("host");   
			$proxy_host = $wizard->GetVar("proxy_host");
			$proxy_port = $wizard->GetVar("proxy_port");
			$proxy_use = $wizard->GetVar("proxy_use");
			$login = $wizard->GetVar("login");
			$password = $wizard->GetVar("password");
			$gmt = $wizard->GetVar("gmt");
			$sms_sym_count = $wizard->GetVar("sms_sym_count"); 
			$use_translit = $wizard->GetVar("use_translit");
			
			COption::SetOptionString("rarus.sms4b", "host", $host);
			COption::SetOptionString("rarus.sms4b", "proxy_host", $proxy_host);
			COption::SetOptionString("rarus.sms4b", "proxy_port", $proxy_port);
			COption::SetOptionString("rarus.sms4b", "proxy_use", $proxy_use);
			COption::SetOptionString("rarus.sms4b", "login", $login);
			COption::SetOptionString("rarus.sms4b", "password", $password);
			COption::SetOptionString("rarus.sms4b", "gmt", $gmt);
			COption::SetOptionString("rarus.sms4b", "sms_sym_count", $sms_sym_count);
			COption::SetOptionString("rarus.sms4b", "use_translit", $use_translit);
			
			CModule::IncludeModule("rarus.sms4b");
			
			global $SMS4B;
			
			$arrDefSender = $SMS4B->GetSender();
			TrimArr($arrDefSender);
			
			if (count($arrDefSender) == 0 || !isset($arrDefSender))
			{
				$this->content .= "<p><span style='color:red'>".GetMessage('WW_STEP3_1_ERROR_TEXT1')."</span></p>";
				$this->content .= "<p><span style='color:green'>".GetMessage('WW_STEP3_1_NOTE_TEXT1')."</span></p>";
			}
			else
			{
				
				$curr_defsender = COption::GetOptionString("rarus.sms4b", "defsender");
				
				foreach($arrDefSender as $val)
					$arrDF[$val] = $val;
				
				$this->content = '<p>'.GetMessage("WW_STEP3_1_TEXT1").': '.ShowCheckedSelectField($arrDF, "defSender", $curr_defsender).'</p>'; 
				$this->content .= '<br><p style = "font-size: 85%;">'.GetMessage("WW_STEP3_1_TEXT2").'</p>';
			}
		} 
	}

	function OnPostForm()
	{
		$wizard = &$this->GetWizard();
		if ($wizard->IsNextButtonClick())
		{
			$wizard->SetVar("defSender", $_REQUEST['defSender']);
		}
	}

}

// step 4-1 setting options for module events
class Step4_1 extends CWizardStep
{
	function InitStep()
	{
		$this->SetTitle(GetMessage("WW_STEP4_1_TITLE"));
		$this->SetStepID("step4_1");
		$this->SetPrevStep("step2_1"); 
		$this->SetFinishStep("install1");
		$this->SetCancelStep("cancel");
	}

	function ShowStep()  
	{
		$arEvents = array (
			"event_subscribe_confirm",
			"event_sale_status_changed",
			"event_sale_new_order",
			"event_sale_order_paid", 
			"event_sale_order_delivery",
			"event_sale_order_cancel", 
		);
		
		foreach ($arEvents as $event_name)
		{
			$arEventsVal[$event_name] = COption::GetOptionString("rarus.sms4b", $event_name);  
		}

		$this->content = '<p><b>'.GetMessage("WW_STEP4_1_TEXT1").':</b></p> <table>'; 
		$n = 1;
		foreach ($arEventsVal as $ename => $eval)
		{
			$this->content .= '<tr><td>'.GetMessage("WW_STEP4_1_EVENT$n").'</td><td>'.ShowCheckedCheckboxField("events[]", $ename, $eval).'</td></tr>';	
			$n++;
		}
		$this->content .= '</table>';  
	}

	function OnPostForm()
	{
		$wizard = &$this->GetWizard();
		if ($wizard->IsFinishButtonClick())
		{
			foreach($_REQUEST["events"] as $ename => $eval)
			{
				$wizard->SetVar("events[$eval]", 'Y');  
			}
		}
	}

}

// step 2-2 forming iframe from sms4b.ru with register form
class Step2_2 extends CWizardStep
{
	function InitStep()
	{
		$this->SetTitle(GetMessage("WW_STEP2_2_TITLE"));
		//$this->SetFinishStep("install2");
		$this->SetStepID("step2_2");
		$this->SetPrevStep("step1"); 
		$this->SetCancelStep("cancel");
		$this->SetCancelCaption("Выход из мастера");
	}

	function ShowStep()  
	{
		$this->content = '<div><iframe frameborder = "no" width="550px" height = "300px" src="http://www.sms4b.ru/regform_notemp.php">Ваш браузер не поддерживает плавающие фреймы</iframe></div>'; 
	}
}


// Install1 - saving some options (events and default sender)
class Install1 extends CWizardStep
{
	function InitStep()
	{
		$this->SetTitle(GetMessage("WW_INSTALL1_TITLE"));
		$this->SetStepID("install1");
		$this->SetCancelCaption(GetMessage("WW_CLOSE"));
		$this->SetCancelStep("final");
	}

	function ShowStep()  
	{	
		$wizard = &$this->GetWizard();
		//setting module options - default sender   
		$defsender = $wizard->GetVar("defSender");
		COption::SetOptionString("rarus.sms4b","defsender",$defsender);
		
		//setting module options - events  
		$arEvents = array (
			"event_sale_status_changed",
			"event_sale_recurring_cancel",
			"event_sale_order_paid", 
			"event_sale_order_delivery",
			"event_sale_order_cancel", 
			"event_sale_new_order"
		);
		
		$events = $wizard->GetVar("events");  
		
		foreach ($arEvents as $ename)
		{
			if(array_key_exists ($ename, $events) && $events[$ename] == 'Y')
			{
				COption::SetOptionString("rarus.sms4b", $ename, 'Y');
			} 
			else
			{ 
				COption::SetOptionString("rarus.sms4b", $ename, '');
			}
		}
		
		$this->content = GetMessage("WW_Install1_TEXT1");       

	}

}

// install2 - step with ready-state installation
class Install2 extends CWizardStep
{
	function InitStep()
	{
		$this->SetTitle(GetMessage("WW_INSTALL2_TITLE"));
		$this->SetStepID("install2");
		$this->SetCancelCaption(GetMessage("WW_CLOSE"));
		$this->SetCancelStep("final");
	}

	function ShowStep()  
	{	
		$this->content = GetMessage("WW_INSTALL2_TEXT1");  
	}  
}

// installation cancellation
class CancelStep extends CWizardStep
{
	function InitStep()
	{
		$this->SetTitle(GetMessage("WW_CANCEL_TITLE"));
		$this->SetStepID("cancel");
		$this->SetCancelStep("cancel");
		$this->SetCancelCaption(GetMessage("WW_CLOSE"));
	}

	function ShowStep()
	{
		$this->content .= GetMessage("WW_CANCEL_DESCR");
	}
}
?>
