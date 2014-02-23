<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;
$cp = $this->__component; // объект компонента

if (is_object($cp))
{
	$cp->arResult['MY_TITLE'] = 'Мое название';
	
	global $USER;
	
	if(intval($USER->GetID())>0)
	{
		$ARR_USER = array();
		$filter = array('ID'=>$USER->GetID());
		$rsUsers = CUser::GetList(($by="name"), ($order="asc"), $filter, array('SELECT'=>array('UF_*')));
		while($one_user = $rsUsers->GetNext())
		{
			$ARR_USER = $one_user;
		};
		
		// USER NAME
		if($arResult["ORDER_PROP"]["USER_PROPS_N"][2]['VALUE']=='') 
		{
			$cp->arResult["ORDER_PROP"]["USER_PROPS_N"][2]['VALUE'] = $ARR_USER['NAME']; 
		};
		
		// USER last_name
		if($arResult["ORDER_PROP"]["USER_PROPS_N"][1]['VALUE']=='') 
		{
			$cp->arResult["ORDER_PROP"]["USER_PROPS_N"][1]['VALUE'] = $ARR_USER['LAST_NAME']; 
		}
		else
		{
			$arr_temp = explode(" ", $arResult["ORDER_PROP"]["USER_PROPS_N"][1]['VALUE']);
			if(count($arr_temp)>=2)
			{
				$cp->arResult["ORDER_PROP"]["USER_PROPS_N"][1]['VALUE'] = $ARR_USER['LAST_NAME']; 
			};
		};
		
		
		//phone code		
		if($arResult["ORDER_PROP"]["USER_PROPS_N"][5]['VALUE']=='')
		{
			$cp->arResult["ORDER_PROP"]["USER_PROPS_N"][5]['VALUE'] =  $ARR_USER['UF_PHONE_CODE']; // code
			//$cp->arResult["ORDER_PROP"]['PRINT'][5]['VALUE'] = $ARR_USER['UF_PHONE_CODE'];  // NO NEED?????
		}
		
		//phone	
		if($arResult["ORDER_PROP"]["USER_PROPS_N"][3]['VALUE']=='')
		{
			$cp->arResult["ORDER_PROP"]["USER_PROPS_N"][3]['VALUE'] =  $ARR_USER['PERSONAL_PHONE']; // code
			//$cp->arResult["ORDER_PROP"]['PRINT'][5]['VALUE'] = $ARR_USER['UF_PHONE_CODE'];  // NO NEED?????
		}

	};
	
	// для напоминалки $arResult["ORDER_PROP"]["USER_PROPS_N"][11]['VALUE'] 
	// $arResult["ORDER_PROP"][PRINT][11]['VALUE'] 

};
