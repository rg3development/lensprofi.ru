<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?/*<div class="bx-auth-profile">*/?>

<?
// echo '<pre>';
// print_R($arResult);
// echo '</pre>';

// echo '<pre>';
// print_R($_REQUEST);
// echo '</pre>';
?>


<?
//CHAK !!!!!!!!!!!
$GLOBALS['FRIENDS_DISCOUNT'] = $arResult['arUser']['UF_FRIENDS_COUNTER'];


// 
//обработка профиля
$prof_arr = $_REQUEST['prof'];
if(isset($prof_arr['enabled']))
{
	if($prof_arr['address']!='' and ($prof_arr['metro']!=0 or $prof_arr['city']!=''))
	{	
		//form fill complete
			$arr_prof_fields = array
			(
			   "NAME" => $prof_arr['address'],
			   "USER_ID" => $arResult['ID'],
			   "PERSON_TYPE_ID" => 1 // ?????? ФИЗИК
			);
			$USER_PROPS_ID = CSaleOrderUserProps::Add($arr_prof_fields);
			
			if(intval($USER_PROPS_ID>0))
			{
					if(isset($prof_arr['metro']) and $prof_arr['metro']!='')
					{
						$arFields = array(
						   "USER_PROPS_ID" => $USER_PROPS_ID,
						   "ORDER_PROPS_ID" => 7,
						   "NAME" => "Станция метро",
						   "VALUE" => $prof_arr['metro'],
						);
						CSaleOrderUserPropsValue::Add($arFields);
					};
					
					if(isset($prof_arr['city']) and $prof_arr['city']!='')
					{
						$arFields = array(
						   "USER_PROPS_ID" => $USER_PROPS_ID,
						   "ORDER_PROPS_ID" => 8,
						   "NAME" => "Город",
						   "VALUE" => $prof_arr['city'],
						);
						CSaleOrderUserPropsValue::Add($arFields);
					};
					
					if($prof_arr['address']!='')
					{
						$arFields = array(
						   "USER_PROPS_ID" => $USER_PROPS_ID,
						   "ORDER_PROPS_ID" => 9,
						   "NAME" => "Адрес доставки",
						   "VALUE" => $prof_arr['address'],
						);
						CSaleOrderUserPropsValue::Add($arFields);
					};
					
					if($prof_arr['city_or_not']!='')
					{
						$arFields = array(
						   "USER_PROPS_ID" => $USER_PROPS_ID,
						   "ORDER_PROPS_ID" => 10,
						   "NAME" => "выбор местоположения",
						   "VALUE" => $prof_arr['city_or_not'],
						);
						CSaleOrderUserPropsValue::Add($arFields);
					};
				
			}	
	}		
	else
	{
		
	};
};
//---------------------------------------------------------------------------------
?>

<?=ShowError($arResult["strProfileError"]);?>
<?
if ($arResult['DATA_SAVED'] == 'Y')
	echo ShowNote(GetMessage('PROFILE_DATA_SAVED'));
?>
<script type="text/javascript">
<!--
var opened_sections = [<?
$arResult["opened"] = $_COOKIE[$arResult["COOKIE_PREFIX"]."_user_profile_open"];
$arResult["opened"] = preg_replace("/[^a-z0-9_,]/i", "", $arResult["opened"]);
if (strlen($arResult["opened"]) > 0)
{
	echo "'".implode("', '", explode(",", $arResult["opened"]))."'";
}
else
{
	$arResult["opened"] = "reg";
	echo "'reg'";
}
?>];
//-->

var cookie_prefix = '<?=$arResult["COOKIE_PREFIX"]?>';
</script>

<form method="post" name="form1" action="<?=$arResult["FORM_TARGET"]?>?" enctype="multipart/form-data">
<?=$arResult["BX_SESSION_CHECK"]?>
<input type="hidden" name="lang" value="<?=LANG?>" />
<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
<input type="hidden" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD"))?>" />

<?/*
<div class="profile-link profile-user-div-link"><a title="<?=GetMessage("REG_SHOW_HIDE")?>" href="javascript:void(0)" OnClick="javascript: SectionClick('reg')"><?=GetMessage("REG_SHOW_HIDE")?></a></div>
<div class="profile-block-<?=strpos($arResult["opened"], "reg") === false ? "hidden" : "shown"?>" id="user_div_reg">
*/?>

<table class="data1" >
	<tr>
		<td style="height:27px">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td><p>Имя:</p></td>
		<td><input type="text" name="NAME" maxlength="50" value="<?=$arResult["arUser"]["NAME"]?>" class="w196" /></td>
	</tr>
	<tr>
		<td><p>Фамилия:</p></td>
		<td><input type="text" name="LAST_NAME" maxlength="50" value="<?=$arResult["arUser"]["LAST_NAME"]?>" class="w196<?/*alert*/?>" /></td>
	</tr>
	<tr>
		<td><p>Телефон:</p></td>
		<td>
			<ul class="type1">
				<li>+7</li>
				<li><input type="text" name="UF_PHONE_CODE" value="<?=$arResult['arUser']['UF_PHONE_CODE']?>" class="w43" /></li>
				<li><input  type="text" name="PERSONAL_PHONE" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" class="w92" /></li>
				
			</ul>
			<? // datepicker?>
			<script type="text/javascript" src="/js/date.js"></script>
			<!--[if lt IE 7]><script type="text/javascript" src="/js/jquery.bgiframe.min.js"></script><![endif]-->
			<script type="text/javascript" src="/js/jquery.datePicker.js"></script>
			<link rel="stylesheet" type="text/css" media="screen" href="/css/datePicker.css" />
			<script type="text/javascript">
				$(document).ready(function() {
						<!-- datepicker this page scripts only!-->
						$('.date-pick').datePicker();
						$('.dp-choose-date').html('Изменить дату');
						<!-- datepicker -->
					});
			</script>
			<? //-----------?>
			<ul class="type2">
				<li><label for="sms">Напомнить о следующем заказе: </label></li>
				<li style="clear:left; margin-top:5px;"><input type="hidden" name="UF_SMS_REMINDER" value="0"><input type="checkbox" <?=($arResult["arUser"]["UF_SMS_REMINDER"]==1)?'checked="checked"':''?> name="UF_SMS_REMINDER" id="sms" value="1" /><label for="sms">Через SMS</label></li>
				<li style="position:relative; top:12px; left:10px"><input type="text" name="UF_DATE_REMIND[]" id="date1" class="w92 date-pick" value="<?if(isset($arResult['arUser']['UF_DATE_REMIND'][0])) echo $arResult['arUser']['UF_DATE_REMIND'][0]?>" /></li>
			</ul>
			
			<ul class="type2">
				<li><input type="hidden" name="UF_EMAIL_REMINDER" value="0"><input type="checkbox" <?=($arResult["arUser"]["UF_EMAIL_REMINDER"]==1)?'checked="checked"':''?> id="email" name="UF_EMAIL_REMINDER" value="1"  /><label for="email">Через e-mail</label></li>
			</ul>
			
		</td>
	</tr>
	<tr>
		<td><p>E-mail*:</p></td>
		<td><input type="text" name="EMAIL" maxlength="50" value="<? echo $arResult["arUser"]["EMAIL"]?>" class="w196" /></td>
	</tr>
	<tr>
		<td><p>Логин*:</p></td>
		<td><input type="text" name="LOGIN" maxlength="50" value="<? echo $arResult["arUser"]["LOGIN"]?>" class="w196" /></td>
	</tr>
	<tr>
		<td><p>Пол:</p></td>
		<td>
			<ul class="type3">
				<li><input type="radio" name="PERSONAL_GENDER" id="male" <?=$arResult["arUser"]["PERSONAL_GENDER"] == "M" ? " checked=\"checked\"" : ""?> value="M" /><label for="male">Мужской</label></li>
				<li><input type="radio" name="PERSONAL_GENDER" <?=$arResult["arUser"]["PERSONAL_GENDER"] == "F" ? " checked=\"checked\"" : ""?> id="female" value="F" /><label for="female">Женский</label></li>
			</ul>
				<?/*
				<td><select name="PERSONAL_GENDER">
				<option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
				<option value="M"<?=$arResult["arUser"]["PERSONAL_GENDER"] == "M" ? " SELECTED=\"SELECTED\"" : ""?>><?=GetMessage("USER_MALE")?></option>
				<option value="F"<?=$arResult["arUser"]["PERSONAL_GENDER"] == "F" ? " SELECTED=\"SELECTED\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
			</select></td>*/?>
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>
			<script type="text/javascript">
				$(document).ready(function(){
					$('#adr').click(function() {
							$('.newAdr').show();
							$("#prof_enabled").removeAttr("disabled");
							return false;	
					});
				});
			</script>
		<a href="#" class="bLink" id="adr" style="margin-bottom:10px">Добавить адрес</a>
		</td>
	</tr>
							 <tr id="deliver_profile_ajax_target">
									<td class="newAdr"><p>Город:</p></td>
									<td class="newAdr">
											<input type="hidden" id="prof_enabled" name="prof[enabled]" disabled="disabled" />
											<table style="margin-left:10px">
													<tr>
															<td rowspan="2">
																	<ul class="type3">
																			<li><input type="radio" class="city_or_not" name="prof[city_or_not]"  value="1" id="msk" checked="checked" /><label for="msk">Москва</label></li>
																			<li><input type="radio" class="city_or_not" name="prof[city_or_not]" value="2"  id="mskRegion" /><label for="mskRegion">Подмосковье</label></li>
																	</ul>
															</td>
															<td>
																<?
																$arr_vars = array();
																$db_vars = CSaleOrderPropsVariant::GetList(
																												array("SORT" => "ASC"),
																												array("ORDER_PROPS_ID" => 7)
																											);
																while ($vars = $db_vars->Fetch())
																{
																	$arr_vars[] = $vars; 
																};
																
																// echo '<pre>';
																// print_R($arr_vars);
																// echo '</pre>';
																?>
																<select size="1" name="prof[metro]" id="metro" class="w250">
																	<?foreach($arr_vars as $one_var):?>
																		<option value="<?=$one_var['VALUE']?>"><?=$one_var['NAME']?></option>
																	<?endforeach;?>
																</select>
															</td>
													</tr>
													<tr style="vertical-align: top">
															<td><input style="position: inherit; top: 0px" type="text" class="w196 rel10" id="city" name="prof[city]" value="Город" disabled="disabled" /></td>
													</tr>
											</table>
											<script type="text/javascript">
											
												$(document).ready(function(){
														$(".city_or_not").live("click", function(){
															if($(this).attr("value")==2)
															{
																$("#metro").attr("disabled", "disabled");
																$("#city").removeAttr("disabled");
															}
															else
															{
																$("#city").attr("disabled", "disabled");
																$("#metro").removeAttr("disabled");
															};
														});
												});
											</script>
									</td>
							</tr>
							<tr>
									<td class="newAdr"><p style="padding-top:17px">Адрес:</p></td>
									<td class="newAdr">
												<span>Напишите адрес доставки и при необходимости дополнительную информацию:</span><input name="prof[address]" value="" type="text" class="w450" /><br /><br />
									</td>
							</tr>
	<tr>
		<td><p style="padding-top:17px">Адрес по умолчанию:</p></td>
		<td><span>Выберите адрес, который будет основным адресом:</span>
			<?
			$db_sales = CSaleOrderUserProps::GetList(
				array("DATE_UPDATE" => "DESC"),
				array("USER_ID" => $arResult['ID'])
			);
			while($one_prof = $db_sales->GetNext())
			{
				$arr_profiles[$one_prof['ID']] = $one_prof;
 			};
			
			$prof_props = array();
			
			$db_propVals = CSaleOrderUserPropsValue::GetList(($b="SORT"), ($o="ASC"), Array('USER_ID'=>$arResult['ID'])); //"USER_PROPS_ID"=>$ID
			while ($arr_prop_vals = $db_propVals->Fetch())
			{
			   $prof_props[$arr_prop_vals['USER_PROPS_ID']][$arr_prop_vals['CODE']] = $arr_prop_vals;
			};
			
			// echo '<pre>';
			// var_dump($arr_profiles);
			// echo '</pre>';
			?>
			
			<select size="1" id="prof_select" class="w450" style="float:left" onChange="prof_change();">
				<?if(count($arr_profiles)>0):?>
					<?foreach($arr_profiles as $one_prof):?>
						<?
						$addr='';
						//$prof_props[$one_prof['ID']]['address']['VALUE']
						$addr = $one_prof['NAME'];
						if(mb_strlen($one_prof['NAME'])>20)
						{
							$addr = mb_substr($addr, 0, 20).'...';
						};
						?>
						<option value="<?=$one_prof['ID']?>"><?=$addr;?></option>
					<?endforeach;?>
				<?else:?>
					<option value=""></option>
				<?endif;?>
			</select>
			<script type="text/javascript">
				function prof_change()
				{
					$("#prof_select").load("/inc/ajax_prof_change.php", {prof_id:$("#prof_select option:selected").attr("value")}, function(resp){});
				};
				
				function del_profile()
				{
					$("#prof_select").load("/inc/ajax_prof_del.php", {prof_id:$("#prof_select option:selected").attr("value")}, function(resp){});
					return false;
				};
				
			</script>
			<a class="bLink" onClick="del_profile()" id="del_pr" style="cursor: pointer; float:left; position:relative; left:10px"> Удалить адрес</a></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td><a href="#" class="bLink" id="pwd" style="margin-bottom:10px">Смена пароля</a>
		</td>
	</tr>
	<?/*
	<tr>
		<td class="pwd"><p>Текущий пароль:</p></td>
		<td class="pwd"><input type="text" class="w196" /></td>
	</tr>
	*/?>
	<tr>
		<td class="pwd"><p>Пароль:</p></td>
		<td class="pwd"><input type="password" name="NEW_PASSWORD" value="" autocomplete="off" class="w196" /></td>
	</tr>
	<tr>
		<td class="pwd"><p>Повтор пароля:</p></td>
		<td class="pwd"><input type="password" name="NEW_PASSWORD_CONFIRM" value="" autocomplete="off" class="w196" style="margin-bottom:20px;" /></td>
	</tr>
</table>
<div class="block51">
	<a href="/"><img src="/img/bt126a.png" alt="" /></a>
	<input type="image" src="/img/bt218a.png" />
</div>

<?/*
<table class="data1">
	<tbody>
	<?
	if($arResult["ID"]>0)
	{
	?>
		<?
		if (strlen($arResult["arUser"]["TIMESTAMP_X"])>0)
		{
		?>
		<tr>
			<td><?=GetMessage('LAST_UPDATE')?></td>
			<td><?=$arResult["arUser"]["TIMESTAMP_X"]?></td>
		</tr>
		<?
		}
		?>
		<?
		if (strlen($arResult["arUser"]["LAST_LOGIN"])>0)
		{
		?>
		<tr>
			<td><?=GetMessage('LAST_LOGIN')?></td>
			<td><?=$arResult["arUser"]["LAST_LOGIN"]?></td>
		</tr>
		<?
		}
		?>
	<?
	}
	?>
	<tr>
		<td><?=GetMessage('NAME')?></td>
		<td><input type="text" name="NAME" maxlength="50" value="<?=$arResult["arUser"]["NAME"]?>" /></td>
	</tr>
	<tr>
		<td><?=GetMessage('LAST_NAME')?></td>
		<td><input type="text" name="LAST_NAME" maxlength="50" value="<?=$arResult["arUser"]["LAST_NAME"]?>" /></td>
	</tr>
	<tr>
		<td><?=GetMessage('SECOND_NAME')?></font></td>
		<td><input type="text" name="SECOND_NAME" maxlength="50" value="<?=$arResult["arUser"]["SECOND_NAME"]?>" /></td>
	</tr>
	<tr>
		<td><?=GetMessage('EMAIL')?><span class="starrequired">*</span></td>
		<td><input type="text" name="EMAIL" maxlength="50" value="<? echo $arResult["arUser"]["EMAIL"]?>" /></td>
	</tr>
	<tr>
		<td><?=GetMessage('LOGIN')?><span class="starrequired">*</span></td>
		<td><input type="text" name="LOGIN" maxlength="50" value="<? echo $arResult["arUser"]["LOGIN"]?>" /></td>
	</tr>
	<tr>
		<td><?=GetMessage('NEW_PASSWORD_REQ')?></td>
		<td><input type="password" name="NEW_PASSWORD" maxlength="50" value="" autocomplete="off" class="bx-auth-input" />
<?if($arResult["SECURE_AUTH"]):?>
				<span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
					<div class="bx-auth-secure-icon"></div>
				</span>
				<noscript>
				<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				</span>
				</noscript>
<script type="text/javascript">
document.getElementById('bx_auth_secure').style.display = 'inline-block';
</script>
<?endif?>
		</td>
	</tr>
	<tr>
		<td><?=GetMessage('NEW_PASSWORD_CONFIRM')?></td>
		<td><input type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off" /></td>
	</tr>
<?if($arResult["TIME_ZONE_ENABLED"] == true):?>
	<tr>
		<td colspan="2" class="profile-header"><?echo GetMessage("main_profile_time_zones")?></td>
	</tr>
	<tr>
		<td><?echo GetMessage("main_profile_time_zones_auto")?></td>
		<td>
			<select name="AUTO_TIME_ZONE" onchange="this.form.TIME_ZONE.disabled=(this.value != 'N')">
				<option value=""><?echo GetMessage("main_profile_time_zones_auto_def")?></option>
				<option value="Y"<?=($arResult["arUser"]["AUTO_TIME_ZONE"] == "Y"? ' SELECTED="SELECTED"' : '')?>><?echo GetMessage("main_profile_time_zones_auto_yes")?></option>
				<option value="N"<?=($arResult["arUser"]["AUTO_TIME_ZONE"] == "N"? ' SELECTED="SELECTED"' : '')?>><?echo GetMessage("main_profile_time_zones_auto_no")?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?echo GetMessage("main_profile_time_zones_zones")?></td>
		<td>
			<select name="TIME_ZONE"<?if($arResult["arUser"]["AUTO_TIME_ZONE"] <> "N") echo ' disabled="disabled"'?>>
<?foreach($arResult["TIME_ZONE_LIST"] as $tz=>$tz_name):?>
				<option value="<?=htmlspecialchars($tz)?>"<?=($arResult["arUser"]["TIME_ZONE"] == $tz? ' SELECTED="SELECTED"' : '')?>><?=htmlspecialchars($tz_name)?></option>
<?endforeach?>
			</select>
		</td>
	</tr>
<?endif?>
	
</table>
*/?>
<?/*</div>*/?>


<?/*
<div class="profile-link profile-user-div-link"><a title="<?=GetMessage("USER_SHOW_HIDE")?>" href="javascript:void(0)" OnClick="javascript: SectionClick('personal')"><?=GetMessage("USER_PERSONAL_INFO")?></a></div>
<div id="user_div_personal" class="profile-block-<?=strpos($arResult["opened"], "personal") === false ? "hidden" : "shown"?>">
<table class="data-table profile-table">
	<thead>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?=GetMessage('USER_PROFESSION')?></td>
			<td><input type="text" name="PERSONAL_PROFESSION" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_PROFESSION"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_WWW')?></td>
			<td><input type="text" name="PERSONAL_WWW" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_WWW"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_ICQ')?></td>
			<td><input type="text" name="PERSONAL_ICQ" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_ICQ"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_GENDER')?></td>
			<td><select name="PERSONAL_GENDER">
				<option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
				<option value="M"<?=$arResult["arUser"]["PERSONAL_GENDER"] == "M" ? " SELECTED=\"SELECTED\"" : ""?>><?=GetMessage("USER_MALE")?></option>
				<option value="F"<?=$arResult["arUser"]["PERSONAL_GENDER"] == "F" ? " SELECTED=\"SELECTED\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
			</select></td>
		</tr>
		<tr>
			<td><?=GetMessage("USER_BIRTHDAY_DT")?> (<?=$arResult["DATE_FORMAT"]?>):</td>
			<td><?
			$APPLICATION->IncludeComponent(
				'bitrix:main.calendar',
				'',
				array(
					'SHOW_INPUT' => 'Y',
					'FORM_NAME' => 'form1',
					'INPUT_NAME' => 'PERSONAL_BIRTHDAY',
					'INPUT_VALUE' => $arResult["arUser"]["PERSONAL_BIRTHDAY"],
					'SHOW_TIME' => 'N'
				),
				null,
				array('HIDE_ICONS' => 'Y')
			);

			//=CalendarDate("PERSONAL_BIRTHDAY", $arResult["arUser"]["PERSONAL_BIRTHDAY"], "form1", "15")
			?></td>
		</tr>
		<tr>
			<td><?=GetMessage("USER_PHOTO")?></td>
			<td>
			<?=$arResult["arUser"]["PERSONAL_PHOTO_INPUT"]?>
			<?
			if (strlen($arResult["arUser"]["PERSONAL_PHOTO"])>0)
			{
			?>
			<br />
				<?=$arResult["arUser"]["PERSONAL_PHOTO_HTML"]?>
			<?
			}
			?></td>
		<tr>
			<td colspan="2" class="profile-header"><?=GetMessage("USER_PHONES")?></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_PHONE')?></td>
			<td><input type="text" name="PERSONAL_PHONE" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_FAX')?></td>
			<td><input type="text" name="PERSONAL_FAX" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_FAX"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_MOBILE')?></td>
			<td><input type="text" name="PERSONAL_MOBILE" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_MOBILE"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_PAGER')?></td>
			<td><input type="text" name="PERSONAL_PAGER" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_PAGER"]?>" /></td>
		</tr>
		<tr>
			<td colspan="2" class="profile-header"><?=GetMessage("USER_POST_ADDRESS")?></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_COUNTRY')?></td>
			<td><?=$arResult["COUNTRY_SELECT"]?></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_STATE')?></td>
			<td><input type="text" name="PERSONAL_STATE" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_STATE"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_CITY')?></td>
			<td><input type="text" name="PERSONAL_CITY" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_CITY"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_ZIP')?></td>
			<td><input type="text" name="PERSONAL_ZIP" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_ZIP"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage("USER_STREET")?></td>
			<td><textarea cols="30" rows="5" name="PERSONAL_STREET"><?=$arResult["arUser"]["PERSONAL_STREET"]?></textarea></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_MAILBOX')?></td>
			<td><input type="text" name="PERSONAL_MAILBOX" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_MAILBOX"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage("USER_NOTES")?></td>
			<td><textarea cols="30" rows="5" name="PERSONAL_NOTES"><?=$arResult["arUser"]["PERSONAL_NOTES"]?></textarea></td>
		</tr>
	</tbody>
</table>
</div>

<div class="profile-link profile-user-div-link"><a title="<?=GetMessage("USER_SHOW_HIDE")?>" href="javascript:void(0)" OnClick="javascript: SectionClick('work')"><?=GetMessage("USER_WORK_INFO")?></a></div>
<div id="user_div_work" class="profile-block-<?=strpos($arResult["opened"], "work") === false ? "hidden" : "shown"?>">
<table class="data-table profile-table">
	<thead>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?=GetMessage('USER_COMPANY')?></td>
			<td><input type="text" name="WORK_COMPANY" maxlength="255" value="<?=$arResult["arUser"]["WORK_COMPANY"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_WWW')?></td>
			<td><input type="text" name="WORK_WWW" maxlength="255" value="<?=$arResult["arUser"]["WORK_WWW"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_DEPARTMENT')?></td>
			<td><input type="text" name="WORK_DEPARTMENT" maxlength="255" value="<?=$arResult["arUser"]["WORK_DEPARTMENT"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_POSITION')?></td>
			<td><input type="text" name="WORK_POSITION" maxlength="255" value="<?=$arResult["arUser"]["WORK_POSITION"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage("USER_WORK_PROFILE")?></td>
			<td><textarea cols="30" rows="5" name="WORK_PROFILE"><?=$arResult["arUser"]["WORK_PROFILE"]?></textarea></td>
		</tr>
		<tr>
			<td><?=GetMessage("USER_LOGO")?></td>
			<td>
			<?=$arResult["arUser"]["WORK_LOGO_INPUT"]?>
			<?
			if (strlen($arResult["arUser"]["WORK_LOGO"])>0)
			{
			?>
				<br /><?=$arResult["arUser"]["WORK_LOGO_HTML"]?>
			<?
			}
			?></td>
		</tr>
		<tr>
			<td colspan="2" class="profile-header"><?=GetMessage("USER_PHONES")?></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_PHONE')?></td>
			<td><input type="text" name="WORK_PHONE" maxlength="255" value="<?=$arResult["arUser"]["WORK_PHONE"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_FAX')?></font></td>
			<td><input type="text" name="WORK_FAX" maxlength="255" value="<?=$arResult["arUser"]["WORK_FAX"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_PAGER')?></font></td>
			<td><input type="text" name="WORK_PAGER" maxlength="255" value="<?=$arResult["arUser"]["WORK_PAGER"]?>" /></td>
		</tr>
		<tr>
			<td colspan="2" class="profile-header"><?=GetMessage("USER_POST_ADDRESS")?></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_COUNTRY')?></td>
			<td><?=$arResult["COUNTRY_SELECT_WORK"]?></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_STATE')?></td>
			<td><input type="text" name="WORK_STATE" maxlength="255" value="<?=$arResult["arUser"]["WORK_STATE"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_CITY')?></td>
			<td><input type="text" name="WORK_CITY" maxlength="255" value="<?=$arResult["arUser"]["WORK_CITY"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_ZIP')?></td>
			<td><input type="text" name="WORK_ZIP" maxlength="255" value="<?=$arResult["arUser"]["WORK_ZIP"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage("USER_STREET")?></td>
			<td><textarea cols="30" rows="5" name="WORK_STREET"><?=$arResult["arUser"]["WORK_STREET"]?></textarea></td>
		</tr>
		<tr>
			<td><?=GetMessage('USER_MAILBOX')?></td>
			<td><input type="text" name="WORK_MAILBOX" maxlength="255" value="<?=$arResult["arUser"]["WORK_MAILBOX"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage("USER_NOTES")?></td>
			<td><textarea cols="30" rows="5" name="WORK_NOTES"><?=$arResult["arUser"]["WORK_NOTES"]?></textarea></td>
		</tr>
	</tbody>
</table>
</div>
	<?
	if ($arResult["INCLUDE_FORUM"] == "Y")
	{
	?>

<div class="profile-link profile-user-div-link"><a title="<?=GetMessage("USER_SHOW_HIDE")?>" href="javascript:void(0)" OnClick="javascript: SectionClick('forum')"><?=GetMessage("forum_INFO")?></a></div>
<div id="user_div_forum" class="profile-block-<?=strpos($arResult["opened"], "forum") === false ? "hidden" : "shown"?>">
<table class="data-table profile-table">
	<thead>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?=GetMessage("forum_SHOW_NAME")?></td>
			<td><input type="checkbox" name="forum_SHOW_NAME" value="Y" <?if ($arResult["arForumUser"]["SHOW_NAME"]=="Y") echo "checked=\"checked\"";?> /></td>
		</tr>
		<tr>
			<td><?=GetMessage('forum_DESCRIPTION')?></td>
			<td><input type="text" name="forum_DESCRIPTION" maxlength="255" value="<?=$arResult["arForumUser"]["DESCRIPTION"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('forum_INTERESTS')?></td>
			<td><textarea cols="30" rows="5" name="forum_INTERESTS"><?=$arResult["arForumUser"]["INTERESTS"]; ?></textarea></td>
		</tr>
		<tr>
			<td><?=GetMessage("forum_SIGNATURE")?></td>
			<td><textarea cols="30" rows="5" name="forum_SIGNATURE"><?=$arResult["arForumUser"]["SIGNATURE"]; ?></textarea></td>
		</tr>
		<tr>
			<td><?=GetMessage("forum_AVATAR")?></td>
			<td><?=$arResult["arForumUser"]["AVATAR_INPUT"]?>
			<?
			if (strlen($arResult["arForumUser"]["AVATAR"])>0)
			{
			?>
				<br /><?=$arResult["arForumUser"]["AVATAR_HTML"]?>
			<?
			}
			?></td>
		</tr>
	</tbody>
</table>
</div>

	<?
	}
	?>
	<?
	if ($arResult["INCLUDE_BLOG"] == "Y")
	{
	?>
<div class="profile-link profile-user-div-link"><a title="<?=GetMessage("USER_SHOW_HIDE")?>" href="javascript:void(0)" OnClick="javascript: SectionClick('blog')"><?=GetMessage("blog_INFO")?></a></div>
<div id="user_div_blog" class="profile-block-<?=strpos($arResult["opened"], "blog") === false ? "hidden" : "shown"?>">
<table class="data-table profile-table">
	<thead>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?=GetMessage('blog_ALIAS')?></td>
			<td><input class="typeinput" type="text" name="blog_ALIAS" maxlength="255" value="<?=$arResult["arBlogUser"]["ALIAS"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('blog_DESCRIPTION')?></td>
			<td><input class="typeinput" type="text" name="blog_DESCRIPTION" maxlength="255" value="<?=$arResult["arBlogUser"]["DESCRIPTION"]?>" /></td>
		</tr>
		<tr>
			<td><?=GetMessage('blog_INTERESTS')?></td>
			<td><textarea cols="30" rows="5" class="typearea" name="blog_INTERESTS"><?echo $arResult["arBlogUser"]["INTERESTS"]; ?></textarea></td>
		</tr>
		<tr>
			<td><?=GetMessage("blog_AVATAR")?></td>
			<td><?=$arResult["arBlogUser"]["AVATAR_INPUT"]?>
			<?
			if (strlen($arResult["arBlogUser"]["AVATAR"])>0)
			{
			?>
				<br /><?=$arResult["arBlogUser"]["AVATAR_HTML"]?>
			<?
			}
			?></td>
		</tr>
	</tbody>
</table>
</div>
	<?
	}
	?>
	<?if ($arResult["INCLUDE_LEARNING"] == "Y"):?>
	<div class="profile-link profile-user-div-link"><a title="<?=GetMessage("USER_SHOW_HIDE")?>" href="javascript:void(0)" OnClick="javascript: SectionClick('learning')"><?=GetMessage("learning_INFO")?></a></div>
	<div id="user_div_learning" class="profile-block-<?=strpos($arResult["opened"], "learning") === false ? "hidden" : "shown"?>">
	<table class="data-table profile-table">
		<thead>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?=GetMessage("learning_PUBLIC_PROFILE");?>:</td>
				<td><input type="checkbox" name="student_PUBLIC_PROFILE" value="Y" <?if ($arResult["arStudent"]["PUBLIC_PROFILE"]=="Y") echo "checked=\"checked\"";?> /></td>
			</tr>
			<tr>
				<td><?=GetMessage("learning_RESUME");?>:</td>
				<td><textarea cols="30" rows="5" name="student_RESUME"><?=$arResult["arStudent"]["RESUME"]; ?></textarea></td>
			</tr>

			<tr>
				<td><?=GetMessage("learning_TRANSCRIPT");?>:</td>
				<td><?=$arResult["arStudent"]["TRANSCRIPT"];?>-<?=$arResult["ID"]?></td>
			</tr>
		</tbody>
	</table>
	</div>
	<?endif;?>
	<?if($arResult["IS_ADMIN"]):?>
	<div class="profile-link profile-user-div-link"><a title="<?=GetMessage("USER_SHOW_HIDE")?>" href="javascript:void(0)" OnClick="javascript: SectionClick('admin')"><?=GetMessage("USER_ADMIN_NOTES")?></a></div>
	<div id="user_div_admin" class="profile-block-<?=strpos($arResult["opened"], "admin") === false ? "hidden" : "shown"?>">
	<table class="data-table profile-table">
		<thead>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?=GetMessage("USER_ADMIN_NOTES")?>:</td>
				<td><textarea cols="30" rows="5" name="ADMIN_NOTES"><?=$arResult["arUser"]["ADMIN_NOTES"]?></textarea></td>
			</tr>
		</tbody>
	</table>
	</div>
	<?endif;?>
*/?>
	<?/*
	<?// ********************* User properties ***************************************************?>
	<?if(true)://($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
	<div class="profile-link profile-user-div-link"><a title="<?=GetMessage("USER_SHOW_HIDE")?>" href="javascript:void(0)" OnClick="javascript: SectionClick('user_properties')"><?=strLen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></a></div>
	<div id="user_div_user_properties" class="profile-block-<?=strpos($arResult["opened"], "user_properties") === false ? "hidden" : "shown"?>">
	<table class="data-table profile-table">
		<thead>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
		</thead>
		<tbody>
		<?$first = true;?>
		<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
		<tr><td class="field-name">
			<?if ($arUserField["MANDATORY"]=="Y"):?>
				<span class="starrequired">*</span>
			<?endif;?>
			<?=$arUserField["EDIT_FORM_LABEL"]?>:</td><td class="field-value">
				<?$APPLICATION->IncludeComponent(
					"bitrix:system.field.edit",
					$arUserField["USER_TYPE"]["USER_TYPE_ID"],
					array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField), null, array("HIDE_ICONS"=>"Y"));?></td></tr>
		<?endforeach;?>
		</tbody>
	</table>
	</div>
	<?endif;?>
	<?// ******************** /User properties ***************************************************?>
	<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
	<p><input type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD"))?>">&nbsp;&nbsp;<input type="reset" value="<?=GetMessage('MAIN_RESET');?>"></p>
	*/?>
</form>

<?/*
</div>
*/?>