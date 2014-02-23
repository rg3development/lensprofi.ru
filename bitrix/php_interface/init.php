<?php
//error_reporting(E_ERROR);
ini_set('display_errors', 1);

/*
function custom_mail($to, $subject, $message, $additional_headers, $additional_parameters)
{
	
	// if(preg_match('//u', $to))
	// {
		// $to = iconv("utf-8", "windows-1251", $to);
	// };
	
	// if(preg_match('//u', $subject))
	// {
		// $to = iconv("utf-8", "windows-1251", $subject);
	// };
	
	// if(preg_match('//u', $message))
	// {
		// $message = iconv("utf-8", "windows-1251", $message);
	// };
	
	
	echo $to.'<br />';
	echo $subject.'<br />';
	$subject = "test";
	echo $message.'<br />';
	echo $additional_headers.'<br />';
	echo $additional_parameters.'<br />';
	return mail($to, $subject, $message, $additional_headers, $additional_parameters);
	//die();
};	
*/

// для баннеров
$GLOBALS['FOOT_BAN'] = array();
CModule::IncludeModule('iblock');

$bann_iblock = 5;
	
$arr_order= array('SORT'=>'ASC');
$arr_select=array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_href', 'PROPERTY_pict');
$arr_filter=array('IBLOCK_ID'=>$bann_iblock, 'ACTIVE'=>'Y');
$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);
$i=0;
while($one=$res->GetNext())
{
	$GLOBALS['FOOT_BAN'][$i]=$one;
	$GLOBALS['FOOT_BAN'][$i]['showed']=false;
	$i++;
};

//-----

// AddEventHandler("sale", "OnBasketAdd", Array("basket_class", "OnBasketAddHandler"));
// AddEventHandler("sale", "OnBasketUpdate", Array("basket_class", "OnBasketUpdateHandler"));
// AddEventHandler("sale", "OnBasketDelete", Array("basket_class", "OnBasketDeleteHandler"));

function getPriceOnCount($PRODUCT_ID, $FULL_COUNT)
{
	$DISCOUNT_PRICE = 0;
	$db_res1 = CPrice::GetList(array(), array("PRODUCT_ID" =>$PRODUCT_ID, 'CATALOG_GROUP_ID'=>2)); // RETAIL PRICE
	while($one_pr = $db_res1->GetNext())
	{
					
		$from = $one_pr['QUANTITY_FROM'];
		$to = 10000000000;
		if($one_pr['QUANTITY_TO']!='') $to = $one_pr['QUANTITY_TO'];
		
		if($FULL_COUNT>=$from and $FULL_COUNT<=$to)
		{
			$DISCOUNT_PRICE = $one_pr['PRICE'];;
		};
	};
	
	return $DISCOUNT_PRICE;
}

function basket_recounter()
{
	//define('LOG_FILENAME', $_SERVER["DOCUMENT_ROOT"]."/log.txt");	
	//AddMessage2Log('we_update');
	
	$basket_items = array();
	$prod_quantity = array();
	//выбираем всю текущую корзину
	$dbBasketItems = CSaleBasket::GetList(array("ID" => "ASC"),array("ORDER_ID" => "NULL", "FUSER_ID"=> CSaleBasket::GetBasketUserID()),false,false,array());
	while($item=$dbBasketItems->GetNext())
	{
		$basket_items[$item['PRODUCT_ID']][] = $item; 
		$prod_quantity[$item['PRODUCT_ID']] += $item['QUANTITY'];
		/*
		if(isset($prod_quantity[$item['PRODUCT_ID']]))
		{
			$prod_quantity[$item['PRODUCT_ID']] += $item['QUANTITY'];
		}
		else
		{
			$prod_quantity[$item['PRODUCT_ID']] = 1;
		};
		*/
	};

	//AddMessage2Log(serialize($prod_quantity));

	
	foreach($basket_items as $prod_id=>$items_array)
	{
		$PR = getPriceOnCount($prod_id, $prod_quantity[$prod_id]);
		
		//AddMessage2Log($PR);
		
		foreach($items_array as $b_item)
		{	
			if(CSaleBasket::Update($b_item['ID'], array('PRICE'=>$PR)))
			{
				//AddMessage2Log($b_item['ID'].' '.$PR.' '.$b_item['QUANTITY']);
			};
		};
		//AddMessage2Log($prod_id.'**'.$prod_quantity[$prod_id]);
	};
}

class basket_class
{
	
	function OnBasketAddHandler($ID, $arr_fields)
	{
		basket_class::basket_recounter();
	}
	
	function OnBasketUpdateHandler($ID, $arr_fields)
	{
		basket_class::basket_recounter();
	}
	
	function OnBasketDeleteHandler($ID)
	{
		basket_class::basket_recounter();
	}
}

AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("quest_class", "OnAfterIBlockElementUpdateHandler"));
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("quest_class", "OnAfterIBlockElementAddHandler"));

class quest_class
{	
	// для уведомления об ответе на вопрос
	function OnAfterIBlockElementUpdateHandler(&$arFields)
	{
		if($arFields['IBLOCK_ID']==3 and $arFields['ACTIVE']=='Y')
		{
			if($arFields['DETAIL_TEXT']!='')
			{
				if(!defined('LOG_FILENAME'))
						{
							define('LOG_FILENAME', $_SERVER["DOCUMENT_ROOT"]."/log.txt");
						};
				
				$is_need = false;
				$db_props = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], array("sort" => "asc"), Array("CODE"=>"submit"));
				if($one = $db_props->GetNext())
				{
					//AddMessage2Log('****'.$one['VALUE']);
					if($one['VALUE']==2)
					{
						$is_need = true;
					};
				};
				
				if($is_need)
				{
							
						//$qid = $arFields['ID'];
						$db_props = CIBlockElement::GetProperty($arFields['IBLOCK_ID'], $arFields['ID'], array("sort" => "asc"), Array("CODE"=>"email"));
						if($one = $db_props->GetNext())
						{
							$mail_addr = $one['VALUE'];
						};

						$arFields1 = Array(
								"C_EMAIL" => $mail_addr,
								"C_NAME" => $arFields['NAME'],
							);

						if(intval(CEvent::Send('QUESTION_ANSWER', 's1', $arFields1, 'Y', 34))>0) 
						{
							//AddMessage2Log('send ok to '.$mail_addr.', site ='.SITE_ID,'iblock');
						};
				};
				
			};
		};
		return $arFields;
	}
	
	//для уведомления о вопросе 
	function OnAfterIBlockElementAddHandler(&$arFields)
	{
		if($arFields['IBLOCK_ID']==3)
		{
			
				if(!defined('LOG_FILENAME'))
				{
					define('LOG_FILENAME', $_SERVER["DOCUMENT_ROOT"]."/log.txt");
				};
															

				$arFields1 = Array(
						"C_NAME" => $arFields['NAME'],
						"C_TEXT" => $arFields['PREVIEW_TEXT'],
						"C_ID" => $arFields['ID']
				);

				if(intval(CEvent::Send('QUESTION_NEW', 's1', $arFields1, 'Y', 35))>0) 
				{
					//AddMessage2Log('send ok to '.$mail_addr.', site ='.SITE_ID,'iblock');
				};
				
		};
		return $arFields;
	}
};

/*
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", Array("fix_class", "OnBeforeIBlockElementAddHandler"));

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("fix_class", "OnBeforeIBlockElementUpdateHandler"));


class fix_class
{

    function OnBeforeIBlockElementAddHandler(&$arFields)
    { 
		$arFields['SEARCHABLE_CONTENT'] ='';
        return $arFields;   
    }
	

    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    { 
		$arFields['SEARCHABLE_CONTENT'] ='';
        return $arFields;   
    }
}
*/


// invite friend - substraction friends discount
AddEventHandler("sale", "OnOrderAdd", "FDiscountSub");

function FDiscountSub($ID,$arr_fields)
{
	$ORDER=CSaleOrder::GetByID($ID);
	$temp_user= new CUser;
	$rs_users = $temp_user->GetList(($by="personal_country"), ($order="desc"), Array('ID'=>$ORDER['USER_ID']),array('SELECT'=>Array('UF_*'))); 
	$arr_user=$rs_users->Fetch();
	$friends_counter = $arr_user['UF_FRIENDS_COUNTER'];
	if($friends_counter>0) 
	{
		$friends_counter = $friends_counter - 1;
		// write
		$user1 = new CUser;
		$fields = Array(
		  "UF_FRIENDS_COUNTER" => $friends_counter,
		);
		$user1->Update($arr_user['ID'], $fields);
		//если кончились скидки
		if($friends_counter==0)
		{
			$arGroups = CUser::GetUserGroup($arr_user['ID']);
			foreach($arGroups as $key=>$one_gr)
			{
				if($one_gr==5) unset($arGroups[$key]);
			};
			// this write to db
			CUser::SetUserGroup($arr_user['ID'], $arGroups);
			// this set groups in session
			global $USER;
			$USER->SetUserGroupArray($arGroups);
		}
	};
	// echo '<pre>';
	// print_R($ORDER);
	// echo '</pre>';
	// die();
}


// invite friend
AddEventHandler("sale", "OnSalePayOrder", "FriendDiscount");

function FriendDiscount($id,$val)
{
	if ($val=='Y')
	{
		$ORDER=CSaleOrder::GetByID($id);
		$temp_user= new CUser;
		$rs_users = $temp_user->GetList(($by="personal_country"), ($order="desc"), Array('ID'=>$ORDER['USER_ID']),array('SELECT'=>Array('UF_*'))); 
		$arr_user=$rs_users->Fetch();
		// email того, кто оплачивает
		$check_email = $arr_user['EMAIL'];
		
		CModule::IncludeModule('iblock');
		$arr_order= array('SORT'=>'ASC');
		$arr_select=array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_email');
		$arr_filter=array('IBLOCK_ID'=>$invite_iblock_id, 'ACTIVE'=>'N');
		$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);
		$i=0;
		while($one=$res->GetNext())
		{
			if($one['PROPERTY_EMAIL_VALUE']==$check_email) 
			{
					// отмечаем мыло как отработанное
					$elem = new CIBlockElement;
					$arr_fields = Array("ACTIVE" => "Y");			
					$up = $elem->Update($one['ID'], $arr_fields, false, false,false);
					// плюсуем "очки"
					$rsUser = CUser::GetList(($by="ID"), ($order="desc"), array("ID"=>$one['NAME']),array("SELECT"=>array("UF_*")));
					if($one_user = $rsUser->GetNext())
					{
						$friends_counter = $one_user['UF_FRIENDS_COUNTER'];
						if($friends_counter=='') $friends_counter = 0;
						$friends_counter++;
						// здесь плюсуем
						$user1 = new CUser;
						$fields = Array(
						  "UF_FRIENDS_COUNTER" => $friends_counter,
						);
						$user1->Update($one_user['ID'], $fields);
						
						//тут же код, который добавит в группу со скидкой
						$arGroups = CUser::GetUserGroup($one_user['ID']);
						$arGroups[] = 5;
						CUser::SetUserGroup($one_user['ID'], $arGroups);
						
						// echo '<pre>';
						// print_R($one_user);
						// echo '</pre>';
						// die();
					};
			};
		};
		// echo '<pre>';
		// print_R($arr_user);
		// echo '</pre>';
		// die();
	};
};



/*
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "AddWatermarkToImg");
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "AddWatermarkToImg");

function AddWatermarkToImg(&$arFields)
{
	// echo '<pre>';
	// print_R($arFields['DETAIL_PICTURE']);
	// print_R($arFields['PREVIEW_PICTURE']);
	// echo '</pre>';
	// die();
   
		  $arFilter_WM = Array(
			 array("name" => "watermark", 
			 "position" => "mc", 
			 "size"=>"real", 
			 "alpha_level"=>"99",
			 "file"=>$_SERVER['DOCUMENT_ROOT']."/water_goods.png")
		  );   
      
		
		 $v = CFile::SaveFile(CFile::MakeFileArray($arFields['DETAIL_PICTURE']['tmp_name']), "abc");
		   //тупак битрикса
		   $arSizeORIG = getimagesize($_SERVER['DOCUMENT_ROOT'].CFile::GetPath($v));
		   $widthBIG =  intval($arSizeORIG[0])-1;
		   $heightBIG =  intval($arSizeORIG[0])-1;
		   //конец тупака битрикса
		   $v = CFile::ResizeImageGet($v, Array('width' => $widthBIG, 'height' => $heightBIG), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilter_WM);
		   $arFields['DETAIL_PICTURE'] = CFile::MakeFileArray($v["src"]);  
		
		
		  $tmp = Array();
		  foreach($arFields['PROPERTY_VALUES'] as $key => $value)
			 foreach($value as $key2 => $value2)
				if($value2["tmp_name"]){
				   $v = CFile::SaveFile(CFile::MakeFileArray($value2["tmp_name"]), "abc");
				   //тупак битрикса
				   $arSizeORIG = getimagesize($_SERVER['DOCUMENT_ROOT'].CFile::GetPath($v));
				   $widthBIG =  intval($arSizeORIG[0])-1;
				   $heightBIG =  intval($arSizeORIG[0])-1;
				   //конец тупака битрикса
				   $v = CFile::ResizeImageGet($v, Array('width' => $widthBIG, 'height' => $heightBIG), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilter_WM);
				   $arFields['PROPERTY_VALUES'][$key][$key2] = CFile::MakeFileArray($v["src"]);               
		
   
}
*/



CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");

function get_period_str($days)
{
	$period_str ="";
	if($days>=0 and $days <=6)
	{
		$period_str = kt::noun($days, array('день', 'дня', 'дней'));
	};
	if($days==7)
	{
		$period_str = 'неделя';
	};
	if($days>=8 and $days <=13)
	{
		$period_str = kt::noun($days, array('день', 'дня', 'дней'));
	};
	if($days==14)
	{
		$period_str = '2 недели';
	};
	if($days>=15 and $days <=27)
	{
		$period_str = kt::noun($days, array('день', 'дня', 'дней'));
	};
	if($days>=28 and $days <=31)
	{
		$period_str = 'месяц';
	};
	if($days>=32 and $days <=45)
	{
		$pp = floor($days/7);
		$period_str = kt::noun($pp, array('неделя', 'недели', 'недель'));
	};
	if($days>=56 and $days <=62)
	{
		$pp = floor($days/30);
		$period_str = kt::noun($pp, array('месяц', 'месяца', 'месяцев'));
	};
	if($days>=63)
	{
		//FUCK!
		$pp = floor($days/30);
		$period_str = kt::noun($pp, array('месяц', 'месяца', 'месяцев'));
	};
	
	return $period_str;
};

class kt {
	
	public function noun($number, $titles) {
		$cases = array (2, 0, 1, 1, 1, 2);
		return $number." ".$titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
	}
	
	public function getSectionID($elid) {
		$res = CIBlockElement::GetById($elid);
		$row = $res -> GetNext();
		return $row['IBLOCK_SECTION_ID'];	
	}
	
	public function listLenses() {
		$html = '';
		$res = CIblockElement::GetList(array('sort'=>'asc'), array("IBLOCK_ID" => 4, "ACTIVE"=>"Y")); //"PROPERTY_TOHEAD_VALUE" => "Да"
		while($row = $res -> GetNext()) {
			$sid = kt::getSectionID($row['ID']);
			$html .= "<option value=\"{$row['ID']}\" sid=\"{$row['CODE']}\">{$row['NAME']}</option>";
		}
		return $html;
	}
	
	public function getSectionName($ibid, $id) {
		$res = CIBlockSection::GetList(array(), array('IBLOCK_ID' => $ibid, 'ID' => $id));
		$row = $res -> GetNext();
		return $row['NAME'];
	}
	
	public function getPreviewPicture($id) {
		$res = CIBlockElement::GetById($id);
		$row = $res -> GetNext();
		return CFile::GetPath($row["PREVIEW_PICTURE"]);
	}
	
	
	
	
}
?>
