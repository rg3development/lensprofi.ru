<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?

// echo '<pre>';
// print_R($arResult["ORDER_PROP"]["USER_PROPS_N"][12]);
// echo '</pre>';

// echo '<pre>';
// print_R($arResult);
// echo '</pre>';
?>

<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script> <? //?>

<script type="text/javascript" src="/js/date.js"></script>
<!--[if lt IE 7]><script type="text/javascript" src="/js/jquery.bgiframe.min.js"></script><![endif]-->
<script type="text/javascript" src="/js/jquery.datePicker.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="/css/datePicker.css" />

<script type="text/javascript">
	$(document).ready(function() {
		<!-- datepicker this page scripts only!-->
		$('.date-pick').datePicker();
		/*$('.dp-choose-date').html('Изменить дату');*/
		<!-- datepicker -->
		
		
		// местоположение
		$('#ORDER_PROP_10_1').live("click", function() {
				$('.rel10').attr({'disabled':'disabled'});
				$("#ORDER_PROP_7").removeAttr("disabled");
			});
			
			$('#ORDER_PROP_10_2').live("click",function() {
				$('.rel10').attr({'disabled':''});
				$("#ORDER_PROP_7").attr("disabled", "disabled");
			});
		
	
		
		// оповещение
		/*
		$('.type2 input:checkbox').live("click", function() {
			alert("jop");
			if( $('#sms').attr('checked')== false && $('#email').attr('checked')== false ) {
				$('.type2').each(function(index) {
					$('li',this).css('display','none');		  
					$('li:first',this).css('display','block');		  
				});
			}	
		
			else {
				$('.type2 li').css('display','block');
			}
			return false;	
		});
		*/

	});
</script>
		





<div id="order_form_div">
<NOSCRIPT>
 <div class="errortext"><?=GetMessage("SOA_NO_JS")?></div>
</NOSCRIPT>
<?
if(!$USER->IsAuthorized() && $arParams["ALLOW_AUTO_REGISTER"] == "N")
{
	/*
	if(!empty($arResult["ERROR"]))
	{
		foreach($arResult["ERROR"] as $v)
			echo ShowError($v);
	}
	elseif(!empty($arResult["OK_MESSAGE"]))
	{
		foreach($arResult["OK_MESSAGE"] as $v)
			echo "<p class='sof-ok'>".$v."</p>";
	}

	include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/auth.php");
	*/
}
else
{
	if($arResult["USER_VALS"]["CONFIRM_ORDER"] == "Y")
	{
		if(strlen($arResult["REDIRECT_URL"]) > 0)
		{
			?>
			<script>
			<!--
			//top.location.replace = '<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
			window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';
			//setInterval("window.top.location.href='<?=CUtil::JSEscape($arResult["REDIRECT_URL"])?>';",2000);
			//-->
			</script>
			<?
			die();
		}
		else
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/confirm.php");
	}
	else
	{
		$FORM_NAME = 'ORDERFORM_'.RandString(5);
		if(!empty($arResult["ERROR"]) && $arResult["USER_VALS"]["FINAL_STEP"] == "Y")
		{
			foreach($arResult["ERROR"] as $v)
				//CHAK убираем стандартный вывод ошибок!!!
				//echo ShowError($v);
			?>
			<script>
			$(document).ready(function(){
				//alert("zz");
				top.location.hash = '';
				top.location.hash = '#order_form';
			});
			</script>
			<?
		}
		?>
		
		<script>
		<!--
		function submitForm(val)
		{
			if(val != 'Y') 
				document.getElementById('confirmorder').value = 'N';
			
			var orderForm = document.getElementById('ORDER_FORM_ID_NEW');
			
			jsAjaxUtil.InsertFormDataToNode(orderForm, 'order_form_div', true);
			orderForm.submit();
			return true;
		}
		function SetContact(profileId)
		{
			document.getElementById("profile_change").value = "Y";
			submitForm();
		}
		//-->
		</script>

		<div style="display:none;">
			<div id="order_form_id">
				
				<? // ----------------- verstka -------------?>
				<div style="margin:10px 0;"><a href="/personal/basket.php" class="srv2"><span>Вернуться в корзину</span></a></div>
				<table class="data1 q3">
					<tr>
						<th colspan="2">Данные покупателя</th>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
					</tr>
					
					<tr>
						<td><p><?=$arResult["ORDER_PROP"]["USER_PROPS_N"][2]["NAME"]?>:<span><?=($arResult["ORDER_PROP"]["USER_PROPS_N"][2]["REQUIED_FORMATED"]=='Y')?'*':'';?></span></p></td>
						<td>
							<table>
								<tr>
									<td><input type="text" id="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][2]["FIELD_NAME"]?>" name="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][2]["FIELD_NAME"]?>" value="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][2]["VALUE"]?>" class="w196" /></td>
									<?if(isset($arResult['ARR_ERROR_PROP'][2])):?>
										<td><p class="alertError">Ошибка! не введено имя.</p></td>
									<?endif;?>
								</tr>
							</table>
						</td>
					</tr>							
					<tr>
						<td><p><?=$arResult["ORDER_PROP"]["USER_PROPS_N"][1]["NAME"]?>:<span><?=($arResult["ORDER_PROP"]["USER_PROPS_N"][1]["REQUIED_FORMATED"]=='Y')?'*':'';?></span></p></td>
						<td>
							<table>
								<tr>
									<td><input id="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][1]["FIELD_NAME"]?>" name="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][1]["FIELD_NAME"]?>" type="text" class="w196" value="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][1]["VALUE"]?>" /></td>
									<?if(isset($arResult['ARR_ERROR_PROP'][1])):?>
										<td><p class="alertError">Ошибка! не введена фамилия.</p></td>
									<?endif;?>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><p><?=$arResult["ORDER_PROP"]["USER_PROPS_N"][4]["NAME"]?>:<span><?=($arResult["ORDER_PROP"]["USER_PROPS_N"][4]["REQUIED_FORMATED"]=='Y')?'*':'';?></span></p></td>
						<td>
							<table>
								<tr>
									<td><input type="text" name="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][4]["FIELD_NAME"]?>" id="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][4]["FIELD_NAME"]?>" value="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][4]["VALUE"]?>" class="w196" /></td>
									<?if(isset($arResult['ARR_ERROR_PROP'][4])):?>
										<td><p class="alertError">Ошибка! не введен e-mail.</p></td>
									<?endif;?>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td><p><?=$arResult["ORDER_PROP"]["USER_PROPS_N"][3]["NAME"]?>:<span><?=($arResult["ORDER_PROP"]["USER_PROPS_N"][3]["REQUIED_FORMATED"]=='Y')?'*':'';?></span></p></td></span></p></td>
						<td>
							
							<ul class="type1">
								<li>+7</li>
								<li><input type="text" name="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][5]["FIELD_NAME"]?>" id="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][5]["FIELD_NAME"]?>" value="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][5]["VALUE"]?>" class="w43" /></li>
								<li><input type="text" name="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][3]["FIELD_NAME"]?>" id="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][3]["FIELD_NAME"]?>" value="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][3]["VALUE"]?>" class="w92" /></li>
								<?if(isset($arResult['ARR_ERROR_PROP'][3]) or isset($arResult['ARR_ERROR_PROP'][5])):?>
									<li><p class="alertError">Ошибка! не введен номер телефона.</p></li>
								<?endif;?>
							</ul>
						</td>
					</tr>
					<tr>
						<td><p>Дополнительная<br />информация:</p></td>
						<td><textarea rows="5" cols="40" class="w232" name="ORDER_DESCRIPTION"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea></td>
					</tr>
				
					<tr>
						<td>&nbsp;</td>						
						<td>
							<?
							// TIME

							$products = array();
							$QUANTITY = array();
							
							$max_usetime_item_id = 0;
							$max_usetime = 0;
							
							foreach($arResult['BASKET_ITEMS'] as $item)
							{
								$QUANTITY[$item['PRODUCT_ID']] = $item['QUANTITY'];
									
								$arr_order= array('SORT'=>'ASC');
								$arr_select=array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_USETIME', 'PROPERTY_QPERPACK');
								$arr_filter=array('IBLOCK_ID'=>4, 'ID'=>$item['PRODUCT_ID']);
								$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);
								$i=0;
								if($prod_arr=$res->GetNext())
								{
									// echo '<pre>';
									// print_R($prod_arr);
									// echo '</pre>';
									
									$products[$prod_arr['ID']] = $prod_arr;
									
									if($prod_arr['PROPERTY_USETIME_VALUE']>$max_usetime)
									{
										$max_usetime = $prod_arr['PROPERTY_USETIME_VALUE'];
										$max_usetime_item_id = $prod_arr['ID'];
									};
								};
							}
							
							$all_count = $products[$max_usetime_item_id]['PROPERTY_QPERPACK_VALUE'] * $QUANTITY[$max_usetime_item_id];
							
							$per_eye = ceil($all_count/2);
							
							$days = $per_eye * $products[$max_usetime_item_id]['PROPERTY_USETIME_VALUE'];
							
							$date = (date("d.m.Y", date('U')+(86400*$days)));
							//--------------------------------------------------
							?>
							<script type="text/javascript">
								$(document).ready(function(){
									
									// its for start
									<?// заполнялась но не установлено?>
									<?if(isset($_REQUEST['ORDER_PROP_2']) and !isset($arResult["ORDER_PROP"]["USER_PROPS_N"][12]["CHECKED"])):?>
									$("#date1").attr("readonly", "readonly");
									$(".dp-choose-date").css("display", "none");
									<?endif;?>
									
									// checkbox click handler
									$("#ORDER_PROP_13, #ORDER_PROP_12").live("click", function(){
										if($("#ORDER_PROP_13:checked").length<=0 && $("#ORDER_PROP_12:checked").length<=0)
										{
											$("#date1").attr("readonly", "readonly");
											$(".dp-choose-date").css("display", "none");
										}
										else
										{
											$("#date1").removeAttr("readonly");
											$(".dp-choose-date").css("display", "inline");
										};
									});
									
									$("#date1").attr("value", "<?=$date?>");
								});
							</script>
							<?
							$def_sms_checked = false;
							if(!isset($_REQUEST['ORDER_PROP_2'])) // if form was filled
							{
								$def_sms_checked = true;
							};
							?>
							<ul class="type2">
								<li><label for="sms">Напомнить о следующем заказе: </label></li>
								<li style="clear:left; margin-top:5px;"><input type="hidden" name="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][12]["FIELD_NAME"]?>" value="" /><input type="checkbox" id="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][12]["FIELD_NAME"]?>" name="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][12]["FIELD_NAME"]?>" <?if ($arResult["ORDER_PROP"]["USER_PROPS_N"][12]["CHECKED"]=="Y" or $def_sms_checked) echo 'checked="checked"';?> value="Y" /><label for="sms">Через SMS</label></li>
								<!--<li>Следущее оповещение:</li>-->
								<li style="position:relative; top:12px; left:10px"><input type="text" name="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][11]["FIELD_NAME"]?>" value="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][11]["VALUE"]?>" id="date1" class="w92 date-pick" /></li>
							</ul>
							<ul class="type2">
								<li><input type="hidden" name="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][13]["FIELD_NAME"]?>" value="" /><input type="checkbox" id="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][13]["FIELD_NAME"]?>" <?if ($arResult["ORDER_PROP"]["USER_PROPS_N"][13]["CHECKED"]=="Y") echo 'checked="checked"';?> name="<?=$arResult["ORDER_PROP"]["USER_PROPS_N"][13]["FIELD_NAME"]?>" value="Y" /><label for="email">Через e-mail</label></li>
								<!--<li>Следущее оповещение:</li>-->
							</ul>
						</td>
					</tr>
					<tr>
						<th colspan="2">Адрес доставки</th>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
					<tr>
						<?
						$towns = array("Агеево","Александров","Алексин","Аленино","Андреевское","Анищево","Апрелевка","Бабынино","Бавлены","Бакшеево","Балабаново","Балакирево","Балашиха","Барсуки","Барыбино","Барягино","Батюшково","Белоозерский","Белоомуг","Белые Столбы","Белый","Белый Городок","Берендеево","Богородское","Бол Гридино","Бол Михайловское","Бол Поляны","Боровск","Бородино","Борщево","Бронницы","Быково","Введенское","Венев","Вербилки","Верея","Видное","Виленка","Внуково","Волоколамск","Воротынск","Воскресенск","Востряково","Выкопанка","Высокиничи","Высоковск","Высокое","Гаврилов Посад","Голицино","Голицыно","Головково","Горелки","Горка","Городищи","Городня","Гурьево","д.Жилино-Горки","Давыдово","Деденево","Демчино","Дзержинский","Дмитров","Дмитровский Погост","Дмитровское","Долгопрудный","Домодедово","Дорохово","Дрезна","Дубна","Дугна","Дьяконово","Егорьев","Егорьевск","Емельямово","Ермолино","Железнодорожный","Желябужский","Жилево","Жуковский","Завидово","Заокский","Запрудня","Зарайск","Захарово","Звенигород","Зеленоград","Зубово","Ивакино","Иванисово","Ивантеевка","Иваньково","Износки","Изоплит","Икша","Ильинское","Ильинское-Хованское","им Цюрупы","Истра","Итларь","Калязин","Каменское","Карабаново","Караваево","Кашира","Керва","Кимры","Киржач","Климовск","Клин","Клишино","Коломна","Колонтаево","Кольчугино","Колюбакино","Комсомольск","Конаково","Кондрово","Конобеево","Констатиново","Корекозеьо","Королев","Костерево","Котельники","Красмоармейск","Красная Гора","Красногорск","Краснозаводск","Краснознаменск","Красный Октябрь","Красный Ткач","Кресты","Кубинка","Кудрино","Кудринская","Кузяево","Купавна","Купанское","Куплиям","Куровское","Куровской","Лакинск","Ленинский","Ликино-Дулево","Лобня","Лосино-Петровский","Лотошино","Луховицы","Лыткарино","Львовский","Люберцы","Любой","Макарово","Малаховка","Малинки","Малино","Малоярославец","Медное","Медынь","Мещовск","Михайлов","Михнево","Мишеронский","МКАД","Можайск","Монино","Мордвес","Москва","Мошки","Муханово","Мытищи","Мятлево","Нагорье","Наро-Фоминск","Нахабино","Некрасовский","Ненашево","Никиткино","Никитское","Никольское","Новогиреево","Новогурский","Новое","Новозавидовский","Новомосковск","Новопетровское","Новоселки","Новостройка","Ногинск","Обнинск","Обухово","Одинки","Одинцово","Ожерелье","Озеры","Октябрьский","Орехово-Зуево","Орудьево","Орша","Осташево","п.Воровского","п.Кузнецы","п.Саперное","п.Светлый","Павловский Посад","Перемышль","Пески","Песочемский","Петрищево","Петровский","Петровское","Петушки","Поварово","Подольск","Подхожее","Покров","Покровка","Покровское","Поповка","Поречье","Починки","Правдинский","Привокзальный","Пролетарский","Протвино","Протвино","Пушкино","Пущино","Пятовский","Радовицкий","Раки","Раменское","Рассудово","Ревякино","Редкино","Реутов","Реутово","Решетниково","Рогачево","Романцево","Рошаль","Руза","Румянцева","Рыбное","Рязановский","Салтыковка","Северный","Селятино","Семеновское","Сергиев Посад","Сергиевское","Серебряные Пруды","Середа","Середниково","Серпухов","с-з Фрязевский","с-з Электросталь","Сима","Собинка","Солнечногорск","Солотча","Софрино","Спас-Клепики","Старожилово","Старьево","Степанцево","Столбовая","Стрелецкие Высоты","Стремилово","Струнино","Ступино","Суховерково","Сходня","Сычево","Талдом","Таруса","Темпы","Теряево","Тимоховский","Тишнево","Товарково","Томилино","Троицк","Туголесский Бор","Туменское","Тургиново","Тучково","Тырново","Уваровка","Узуново","Уршельский","Федоровка","Федорцово","Федякино","Ферзиково","Фосфоритный","Фрязево","Фрязимо","Фрязино","Фряново","Ханино","Химки","Хлебниково","Хорлово","Хотьково","Храпуново","Черкутино","Черновцы","Черноголовка","Черусти","Чехов","Чисмена","Шатура","Шатурторф","Шаховская","Щелково","Щербинка","Электрогорск","Электросталь","Электроугли","Юбилейный","Юрьев-Польский","Юхнов","Ясногорск","Яхрома");
						?>
						<td><p>Адрес:<span>*</span></p></td>
						<td>
							<?/*
							<select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)">
								<option value="0"><?=GetMessage("SOA_TEMPL_PROP_NEW_PROFILE")?></option>
								<?
								foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
								{
									?>
									<option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
									<?
								}
								?>
							</select>
							*/?>
							<table>
								<tr>
									<td><select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)" size="1" name="subway" class="w250">
											<option value="0">Новый адрес...</option>
											<?
											foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
											{
												?>
												<option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
												<?
											}
											?>
										</select>
									</td>
									<td><?/*<p class="alertError">Ошибка! не введен город.</p>*/?></td>
								</tr>
							</table><br />
							<table >
								<tr>
									<td rowspan="2">
										<ul class="type3">
											<?
											// значение по-умолчанию для незарегенного пользователя
											$arProperties = $arResult["ORDER_PROP"]["USER_PROPS_Y"][10];
											
											// echo '<pre>';
											// print_R($arProperties);
											// echo '</pre>';
											
											$checked_count = 0;
											foreach($arProperties["VARIANTS"] as $var)
											{
												if(isset($var['CHECKED']) and $var['CHECKED']=='Y') $checked_count++;
											}
											
											if($checked_count==0) $arProperties['VARIANTS'][0]['CHECKED'] = 'Y';
											// END OF
											
											foreach($arProperties["VARIANTS"] as $arVariants)
											{
												?>
												<li><input type="radio" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>" value="<?=$arVariants["VALUE"]?>"<?if($arVariants["CHECKED"] == "Y") echo " checked";?>> <label for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"><?=$arVariants["NAME"]?></label></li>
												<?
											}
											?>
										</ul>
											<?/*
											<li><input type="radio" name="<?=$arProperties["FIELD_NAME"]?>" id="msk" checked="checked" /><label for="msk">Москва</label></li>
											<li><input type="radio" name="position" id="mskRegion" /><label for="mskRegion">Подмосковье</label></li>										
											*/?>
									</td>
									<td>
											<? 
											$arProperties = $arResult["ORDER_PROP"]["USER_PROPS_Y"][7];
											?>
											<select class="w250" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
												<?/*<option value="Выбрать станцию метро">Выбрать станцию метро</option>*/?>
												<?
												foreach($arProperties["VARIANTS"] as $arVariants)
												{
													?>
													<option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
													<?
												}
												?>
											</select></td>
								</tr>
								<tr>
									<td style="vertical-align: top"><input style="position: inherit; top: 0px" id="<?=$arResult["ORDER_PROP"]["USER_PROPS_Y"][8]["FIELD_NAME"]?>" name="<?=$arResult["ORDER_PROP"]["USER_PROPS_Y"][8]["FIELD_NAME"]?>" <?if($arResult["ORDER_PROP"]["USER_PROPS_Y"][10]['VARIANTS'][0]['CHECKED'] = 'Y') echo 'disabled="disabled"';?>  type="text" class="w196 rel10" value="<?=($arResult["ORDER_PROP"]["USER_PROPS_Y"][8]["VALUE"]=='')?'Город':$arResult["ORDER_PROP"]["USER_PROPS_Y"][8]["VALUE"]?>" onfocus="if(this.value=='Город') this.value='';" onblur="if(this.value=='') this.value='Город';" /></td>
								</tr>
								<tr>
									<td colspan="2">
										<span>Напишите адрес доставки и при необходимости дополнительную информацию:</span><input id="<?=$arResult["ORDER_PROP"]["USER_PROPS_Y"][9]["FIELD_NAME"]?>" name="<?=$arResult["ORDER_PROP"]["USER_PROPS_Y"][9]["FIELD_NAME"]?>" value="<?=$arResult["ORDER_PROP"]["USER_PROPS_Y"][9]["VALUE"]?>" type="text" class="w450" /><?if(isset($arResult['ARR_ERROR_PROP'][9])):?><p class="alertError">Ошибка! не введен адрес</p><?endif;?><br /><br />
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<th colspan="2">Способ доставки</th>
					</tr>
					<tr>
						<td colspan="2">
							<h5>Выберите подходящий тип доставки и нажмите кнопку подтверждения.</h5>
							<?
							// echo '<pre>';
							// print_R($arResult["DELIVERY"]);
							// echo '</pre>';
							?>
							<table class="data4" style="width: 894px">
								<tr>
									<th>&nbsp;</th>
									<th>Тип доставки</th>
									<th>Срок</th>
									<th>Время</th>
									<th>Цена</th>																														
								</tr>
									<?
									foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery)
									{
										if ($delivery_id !== 0 && intval($delivery_id) <= 0)
										{
											?>
											
												<?
												foreach ($arDelivery["PROFILES"] as $profile_id => $arProfile)
												{
												?>
													<tr>
														<td><input id="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>" type="radio" name="<?=$arProfile["FIELD_NAME"]?>" value="<?=$delivery_id.":".$profile_id;?>" <?/*id="d1"*/?>  onClick="submitForm();" /></td>
														<td><label for="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>"><?=$arProfile["TITLE"]?></label></td>										
														<td>xxxxx</td>
														<td>xxxx</td>										
														<td>xxxx</td>										
													</tr>
												<?
												}; // endforeach
												?>
											
										<?
										}
										else
										{
											$arr_params = explode('|',$arDelivery['DESCRIPTION']);
											$period = $arr_params[0];
											$time = $arr_params[1];
											?>
											<tr>
												<td><input id="ID_DELIVERY_ID_<?= $arDelivery["ID"]?>" type="radio" name="<?=$arDelivery["FIELD_NAME"];?>" value="<?=$arDelivery["ID"];?>" <?//id="d1"?> <?if ($arDelivery["CHECKED"]=="Y") echo " checked";?>  onClick="submitForm();" /></td>
												<td><label for="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>"><?=$arDelivery["NAME"]?></label></td>										
												<td><?=$period;?></td>
												<td><?=$time?></td>										
												<td><?=($arDelivery["PRICE"]>0)?$arDelivery["PRICE_FORMATED"]:'бесплатно';?></td>										
											</tr>
											<?
											
										};?>
									<?
									};
									?>
							</table>
						</td>
					</tr>
				</table>
			<div class="performance">
				<?//print_R($arResult);?>
				
				<table style="width: 440px">
					<?
					
					foreach($arResult['BASKET_ITEMS'] as $b_item)
					{
						//if($b_item['QUANTITY']>1) // если товар 1 то сикдки за кол-во не может быть
						//{
							//$arr_bask_elems[$i]['id'] = $b_item['PRODUCT_ID'];
							//$arr_bask_elems[$i]['q'] = $b_item['QUANTITY'];
							//$i++;//kostion
							
							$arr_bask_elems[$b_item['PRODUCT_ID']]['id'] = $b_item['PRODUCT_ID'];
							$arr_bask_elems[$b_item['PRODUCT_ID']]['q'] += $b_item['QUANTITY'];
						//};
					};
					
					
						// chak CURL 
							// echo '<pre>';
							// print_R($arr_bask_elems);
							// echo '</pre>';
						  $ch = curl_init();
						  curl_setopt($ch, CURLOPT_URL, 'http://'.$_SERVER['HTTP_HOST'].'/inc/ajax_curl_price.php');
						  //curl_setopt($ch, CURLOPT_URL, 'http://192.168.5.38/test.php');
						  curl_setopt($ch, CURLOPT_PORT, 80);
						  //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: multipart/form-data'));
						  //curl_setopt($ch, CURLOPT_HEADER, false);
						  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // для того чтобы рез пришел не в буфер браузера
						  curl_setopt($ch, CURLOPT_POST, true);
						  //Здесь передаются значения переменных
						  curl_setopt($ch, CURLOPT_POSTFIELDS, array('elems' => serialize($arr_bask_elems)));
						  $j_resp = curl_exec($ch);
						  
						  $err     = curl_errno($ch);
						  $errmsg  = curl_error($ch);
						  $header  = curl_getinfo($ch);
						  
						  curl_close($ch);
						  
						  if(intval($j_resp)>=0)
						  {
							$CHAK_DISCOUNT = $j_resp;
						  }
						  else
						  {
							$CHAK_DISCOUNT = 0;
						  };
						  
							// print_R($err);
							// print_R($errmsg);
							// print_R($header);					
							// print_R($j_resp);
						
					?>
					<? if($CHAK_DISCOUNT>0) $arResult['DISCOUNT_PRICE'] = $arResult['DISCOUNT_PRICE'] + $CHAK_DISCOUNT;?>
					<?if($arResult['DISCOUNT_PRICE']>0) $arResult['ORDER_PRICE'] = $arResult['ORDER_PRICE'] + $arResult['DISCOUNT_PRICE'];?>
					<tr>
						<td style="border-left: 1px solid #cacbcb; padding-left: 10px">Товаров на сумму:</td>
						<td style="border-right: 1px solid #cacbcb; padding-right: 10px; text-align: right"><?=$arResult['ORDER_PRICE'].' руб.'?></td>
					</tr>
					<?if($arResult['DISCOUNT_PRICE']>0):?>
						<tr>
							<td style="border-left: 1px solid #cacbcb; padding-left: 10px">Скидка:</td>
						<td style="border-right: 1px solid #cacbcb; padding-right: 10px; text-align: right"><?=$arResult['DISCOUNT_PRICE'].' руб';?></td>
					</tr>
					<?endif;?>
					<?if($arResult['DELIVERY_PRICE']>0):?>
						<tr>
							<td style="border-left: 1px solid #cacbcb; padding-left: 10px">Доставка:</td>
						<td style="border-right: 1px solid #cacbcb; padding-right: 10px; text-align: right"><?=$arResult['DELIVERY_PRICE_FORMATED'];?></td>
					</tr>
					<?endif;?>
					<tr>
						<td style="border-left: 1px solid #cacbcb; padding-left: 10px"><p>Итого:</p></td>
						<td style="border-right: 1px solid #cacbcb; padding-right: 10px; text-align: right"><p><?=$arResult['ORDER_TOTAL_PRICE_FORMATED']?></p></td>
					</tr>
					<tr>
						<td colspan="2" style="border-bottom: 1px solid #444; border-right: 1px solid #444;  border-left: 1px solid #444; border-top: 1px solid #cacbcb; background: #444; text-align: center; padding: 5px 3px 0 5px"><?/*<a href="#"><img src="/img/bt216b.png" alt="" /></a>*/?><input type="image" style="margin: 0 auto 0" src="/img/bt218b.png" name="submitbutton" onClick="submitForm('Y');" value="<?=GetMessage("SOA_TEMPL_BUTTON")?>"></td>
					</tr>
				</table>
			</div>
			
			<? // its need ?>
			<input type="hidden" name="PERSON_TYPE" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>">
			<input type="hidden" name="PERSON_TYPE_OLD" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>">
			
			<? // ------------------- END OF verstka -------------------------------  ?>	
				<?
				/*
				if(count($arResult["PERSON_TYPE"]) > 1)
				{
					?>
					
					<b><?=GetMessage("SOA_TEMPL_PERSON_TYPE")?></b>
					<table class="sale_order_full_table">
					<tr>
					<td>
					<?
					foreach($arResult["PERSON_TYPE"] as $v)
					{
						?><input type="radio" id="PERSON_TYPE_<?= $v["ID"] ?>" name="PERSON_TYPE" value="<?= $v["ID"] ?>"<?if ($v["CHECKED"]=="Y") echo " checked=\"checked\"";?> onClick="submitForm()"> <label for="PERSON_TYPE_<?= $v["ID"] ?>"><?= $v["NAME"] ?></label><br /><?
					}
					?>
					<input type="hidden" name="PERSON_TYPE_OLD" value="<?=$arResult["USER_VALS"]["PERSON_TYPE_ID"]?>">
					</td></tr></table>
					<br /><br />
					<?
				}
				else
				{
					if(IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"]) > 0)
					{
						?>
						<input type="hidden" name="PERSON_TYPE" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>">
						<input type="hidden" name="PERSON_TYPE_OLD" value="<?=IntVal($arResult["USER_VALS"]["PERSON_TYPE_ID"])?>">
						<?
					}
					else
					{
						foreach($arResult["PERSON_TYPE"] as $v)
						{
							?>
							<input type="hidden" id="PERSON_TYPE" name="PERSON_TYPE" value="<?=$v["ID"]?>">11
							<input type="hidden" name="PERSON_TYPE_OLD" value="<?=$v["ID"]?>">
							<?
						}
					}
				}
				*/
/*  --------------------   PROPS BLOCK -----------------------------------  */
/*
function PrintPropsForm($arSource=Array())
{
	if (!empty($arSource))
	{
		?>

		<?
		foreach($arSource as $arProperties)
		{
			if($arProperties["SHOW_GROUP_NAME"] == "Y")
			{
				?>
				<tr>
					<td colspan="2">
						<b><?= $arProperties["GROUP_NAME"] ?></b>
					</td>
				</tr>
				<?
			}
			?>
			<tr>
				<td align="right" valign="top">
					<?= $arProperties["NAME"] ?>:<?
					if($arProperties["REQUIED_FORMATED"]=="Y")
					{
						?><span class="sof-req">*</span><?
					}
					?>
				</td>
				<td>
					<?
					if($arProperties["TYPE"] == "CHECKBOX")
					{
						?>
						
						<input type="hidden" name="<?=$arProperties["FIELD_NAME"]?>" value="">
						<input type="checkbox" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" value="Y"<?if ($arProperties["CHECKED"]=="Y") echo " checked";?>>
						<?
					}
					elseif($arProperties["TYPE"] == "TEXT")
					{
						?>
						<input type="text" maxlength="250" size="<?=$arProperties["SIZE1"]?>" value="<?=$arProperties["VALUE"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>">
						<?
					}
					elseif($arProperties["TYPE"] == "SELECT")
					{
						?>
						<select name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
						<?
						foreach($arProperties["VARIANTS"] as $arVariants)
						{
							?>
							<option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
							<?
						}
						?>
						</select>
						<?
					}
					elseif ($arProperties["TYPE"] == "MULTISELECT")
					{
						?>
						<select multiple name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>" size="<?=$arProperties["SIZE1"]?>">
						<?
						foreach($arProperties["VARIANTS"] as $arVariants)
						{
							?>
							<option value="<?=$arVariants["VALUE"]?>"<?if ($arVariants["SELECTED"] == "Y") echo " selected";?>><?=$arVariants["NAME"]?></option>
							<?
						}
						?>
						</select>
						<?
					}
					elseif ($arProperties["TYPE"] == "TEXTAREA")
					{
						?>
						<textarea rows="<?=$arProperties["SIZE2"]?>" cols="<?=$arProperties["SIZE1"]?>" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>"><?=$arProperties["VALUE"]?></textarea>
						<?
					}
					elseif ($arProperties["TYPE"] == "LOCATION")
					{
						$value = 0;
						foreach ($arProperties["VARIANTS"] as $arVariant) 
						{
							if ($arVariant["SELECTED"] == "Y") 
							{
								$value = $arVariant["ID"]; 
								break;
							}
						}
				
						$GLOBALS["APPLICATION"]->IncludeComponent(
							'bitrix:sale.ajax.locations', 
							'', 
							array(
								"AJAX_CALL" => "N", 
								"COUNTRY_INPUT_NAME" => "COUNTRY_".$arProperties["FIELD_NAME"],
								"CITY_INPUT_NAME" => $arProperties["FIELD_NAME"],
								"CITY_OUT_LOCATION" => "Y",
								"LOCATION_VALUE" => $value,
								"ONCITYCHANGE" => ($arProperties["IS_LOCATION"] == "Y" || $arProperties["IS_LOCATION4TAX"] == "Y") ? "submitForm()" : "",
							),
							null,
							array('HIDE_ICONS' => 'Y')
						);
					
					}
					elseif ($arProperties["TYPE"] == "RADIO")
					{
						
						
						// echo '<pre>';
						// print_R($arProperties);
						// echo '</pre>';
						
						foreach($arProperties["VARIANTS"] as $arVariants)
						{
							?>
							<input type="radio" name="<?=$arProperties["FIELD_NAME"]?>" id="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>" value="<?=$arVariants["VALUE"]?>"<?if($arVariants["CHECKED"] == "Y") echo " checked";?>> <label for="<?=$arProperties["FIELD_NAME"]?>_<?=$arVariants["VALUE"]?>"><?=$arVariants["NAME"]?></label><br />
							<?
						}
					}

					if (strlen($arProperties["DESCRIPTION"]) > 0)
					{
						?><br /><small><?echo $arProperties["DESCRIPTION"] ?></small><?
					}
					?>
					
				</td>
			</tr>
			<?
		}
		?>
		<?
		return true;
	}
	return false;
}
?>
<b><?=GetMessage("SOA_TEMPL_PROP_INFO")?></b><br />
<table class="sale_order_full_table">
<tr><td>
<?
if(!empty($arResult["ORDER_PROP"]["USER_PROFILES"]))
{
	?>
	<?=GetMessage("SOA_TEMPL_PROP_CHOOSE")?><br />
	<select name="PROFILE_ID" id="ID_PROFILE_ID" onChange="SetContact(this.value)">
		<option value="0"><?=GetMessage("SOA_TEMPL_PROP_NEW_PROFILE")?></option>
		<?
		foreach($arResult["ORDER_PROP"]["USER_PROFILES"] as $arUserProfiles)
		{
			?>
			<option value="<?= $arUserProfiles["ID"] ?>"<?if ($arUserProfiles["CHECKED"]=="Y") echo " selected";?>><?=$arUserProfiles["NAME"]?></option>
			<?
		}
		?>
	</select>
	<br />
	<br />
	<?
}
?>
<div style="display:none;">
<?
	$APPLICATION->IncludeComponent(
		'bitrix:sale.ajax.locations', 
		'', 
		array(
			"AJAX_CALL" => "N", 
			"COUNTRY_INPUT_NAME" => "COUNTRY_tmp",
			"CITY_INPUT_NAME" => "tmp",
			"CITY_OUT_LOCATION" => "Y",
			"LOCATION_VALUE" => "",
			"ONCITYCHANGE" => "",
		),
		null,
		array('HIDE_ICONS' => 'Y')
	);
?>
</div>
<table class="sale_order_full_table_no_border">
<?

// echo '<pre>';
// print_R($arResult["ORDER_PROP"]["USER_PROPS_N"]);
// echo '</pre>';

PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_N"]);
PrintPropsForm($arResult["ORDER_PROP"]["USER_PROPS_Y"]);
?>
</table>
</td></tr></table>
<br /><br />

*/?>
		
<?/*  -------------------------- DELIVERY BLOCK ---------------------------------- */
/*
if(!empty($arResult["DELIVERY"]))
{
	?>
	<b><?=GetMessage("SOA_TEMPL_DELIVERY")?></b>
	<table class="sale_order_full_table">
		<?
		foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery)
		{
			if ($delivery_id !== 0 && intval($delivery_id) <= 0)
			{
				?>
				<tr>
					<td colspan="2">
						<b><?=$arDelivery["TITLE"]?></b><?if (strlen($arDelivery["DESCRIPTION"]) > 0):?><br />
						<?=nl2br($arDelivery["DESCRIPTION"])?><br /><?endif;?>
						<table border="0" cellspacing="0" cellpadding="3">
						<?
						foreach ($arDelivery["PROFILES"] as $profile_id => $arProfile)
						{
							?>
							<tr>
								<td width="20" nowrap="nowrap">&nbsp;</td>
								<td width="0%" valign="top"><input type="radio" id="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>" name="<?=$arProfile["FIELD_NAME"]?>" value="<?=$delivery_id.":".$profile_id;?>" <?=$arProfile["CHECKED"] == "Y" ? "checked=\"checked\"" : "";?> onClick="submitForm();" /></td>
								<td width="50%" valign="top">
									<label for="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>">
										<small><b><?=$arProfile["TITLE"]?></b><?if (strlen($arProfile["DESCRIPTION"]) > 0):?><br />
										<?=nl2br($arProfile["DESCRIPTION"])?><?endif;?></small>
									</label>
								</td>
								<td width="50%" valign="top" align="right">
								<?
									$APPLICATION->IncludeComponent('bitrix:sale.ajax.delivery.calculator', '', array(
										"NO_AJAX" => $arParams["DELIVERY_NO_AJAX"],
										"DELIVERY" => $delivery_id,
										"PROFILE" => $profile_id,
										"ORDER_WEIGHT" => $arResult["ORDER_WEIGHT"],
										"ORDER_PRICE" => $arResult["ORDER_PRICE"],
										"LOCATION_TO" => $arResult["USER_VALS"]["DELIVERY_LOCATION"],
										"LOCATION_ZIP" => $arResult["USER_VALS"]["DELIVERY_LOCATION_ZIP"],
										"CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
									), null, array('HIDE_ICONS' => 'Y'));
								?>
								
								</td>
							</tr>
							<?
						} // endforeach
						?>
						</table>
					</td>
				</tr>
				<?
			}	
			else
			{
				?>
				<tr>
					<td valign="top" width="0%">
						<input type="radio" id="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>" name="<?=$arDelivery["FIELD_NAME"]?>" value="<?= $arDelivery["ID"] ?>"<?if ($arDelivery["CHECKED"]=="Y") echo " checked";?> onclick="submitForm();">
					</td>
					<td valign="top" width="100%">
						<label for="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>">
						<b><?= $arDelivery["NAME"] ?></b><br />
						<?
						if (strlen($arDelivery["PERIOD_TEXT"])>0)
						{
							echo $arDelivery["PERIOD_TEXT"];
							?><br /><?
						}
						?>
						<?=GetMessage("SALE_DELIV_PRICE");?> <?=$arDelivery["PRICE_FORMATED"]?><br />
						<?
						if (strlen($arDelivery["DESCRIPTION"])>0)
						{
							?>
							<?=$arDelivery["DESCRIPTION"]?><br />
							<?
						}
						?>
						</label>
					</td>
				</tr>
				<?
			}
		}
		?>
	</table>
	<br /><br />
	<?
}
*/
?>

<?
/*  ---------------------------------------------------------- PAYSYSTEM BLOCK ---------------------------------------------------------- */
/*
?>
<b><?=GetMessage("SOA_TEMPL_PAY_SYSTEM")?></b>
<table class="sale_order_full_table">
	<?
	if ($arResult["PAY_FROM_ACCOUNT"]=="Y")
	{
		?>
		<tr>
		<td colspan="2">
		<input type="hidden" name="PAY_CURRENT_ACCOUNT" value="N">
		<input type="checkbox" name="PAY_CURRENT_ACCOUNT" id="PAY_CURRENT_ACCOUNT" value="Y"<?if($arResult["USER_VALS"]["PAY_CURRENT_ACCOUNT"]=="Y") echo " checked=\"checked\"";?> onChange="submitForm()"> <label for="PAY_CURRENT_ACCOUNT"><b><?=GetMessage("SOA_TEMPL_PAY_ACCOUNT")?></b></label><br />
		<?=GetMessage("SOA_TEMPL_PAY_ACCOUNT1")?> <b><?=$arResult["CURRENT_BUDGET_FORMATED"]?></b>, <?=GetMessage("SOA_TEMPL_PAY_ACCOUNT2")?>
		<br /><br />
		</td></tr>
		<?
	}
	?>
	<?
	foreach($arResult["PAY_SYSTEM"] as $arPaySystem)
	{
		if(count($arResult["PAY_SYSTEM"]) == 1)
		{
			?>
			<tr>
			<td colspan="2">
			<input type="hidden" name="PAY_SYSTEM_ID" value="<?=$arPaySystem["ID"]?>">
			<b><?=$arPaySystem["NAME"];?></b>
			<?
			if (strlen($arPaySystem["DESCRIPTION"])>0)
			{
				?>
				<?=$arPaySystem["DESCRIPTION"]?>
				<br />
				<?
			}
			?>
			</td>
			</tr>
			<?
		}
		else
		{
			?>
			<tr>
				<td valign="top" width="0%">
					<input type="radio" id="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>" name="PAY_SYSTEM_ID" value="<?= $arPaySystem["ID"] ?>"<?if ($arPaySystem["CHECKED"]=="Y") echo " checked=\"checked\"";?>>
				</td>
				<td valign="top" width="100%">
					<label for="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>">
					<b><?= $arPaySystem["PSA_NAME"] ?></b><br />
					<?
					if (strlen($arPaySystem["DESCRIPTION"])>0)
					{
						?>
						<?=$arPaySystem["DESCRIPTION"]?>
						<br />
						<?
					}
					?>
					</label>
					
				</td>
			</tr>
			<?
		}
	}
	?>
</table>

<?*/ /*  -------------------------------------------------------- SUMMARY BLOCK -------------------------------------------  */?>
<?/*
<b><?=GetMessage("SOA_TEMPL_SUM_TITLE")?></b><br />

<table class="sale_order_full data-table">
	<tr>
		<th><?=GetMessage("SOA_TEMPL_SUM_NAME")?></th>
		<th><?=GetMessage("SOA_TEMPL_SUM_PROPS")?></th>
		<th><?=GetMessage("SOA_TEMPL_SUM_PRICE_TYPE")?></th>
		<th><?=GetMessage("SOA_TEMPL_SUM_DISCOUNT")?></th>
		<th><?=GetMessage("SOA_TEMPL_SUM_WEIGHT")?></th>
		<th><?=GetMessage("SOA_TEMPL_SUM_QUANTITY")?></th>
		<th><?=GetMessage("SOA_TEMPL_SUM_PRICE")?></th>
	</tr>
	<?
	foreach($arResult["BASKET_ITEMS"] as $arBasketItems)
	{
		?>
		<tr>
			<td><?=$arBasketItems["NAME"]?></td>
			<td>
				<?
				foreach($arBasketItems["PROPS"] as $val)
				{
					echo $val["NAME"].": ".$val["VALUE"]."<br />";
				}
				?>
			</td>
			<td><?=$arBasketItems["NOTES"]?></td>
			<td><?=$arBasketItems["DISCOUNT_PRICE_PERCENT_FORMATED"]?></td>
			<td><?=$arBasketItems["WEIGHT_FORMATED"]?></td>
			<td><?=$arBasketItems["QUANTITY"]?></td>
			<td align="right"><?=$arBasketItems["PRICE_FORMATED"]?></td>
		</tr>
		<?
	}
	?>
	<tr>
		<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_WEIGHT_SUM")?></b></td>
		<td align="right" colspan="6"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></td>
	</tr>
	<tr>
		<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_SUMMARY")?></b></td>
		<td align="right" colspan="6"><?=$arResult["ORDER_PRICE_FORMATED"]?></td>
	</tr>
	<?
	if (doubleval($arResult["DISCOUNT_PRICE"]) > 0)
	{
		?>
		<tr>
			<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_DISCOUNT")?><?if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"])>0):?> (<?echo $arResult["DISCOUNT_PERCENT_FORMATED"];?>)<?endif;?>:</b></td>
			<td align="right" colspan="6"><?echo $arResult["DISCOUNT_PRICE_FORMATED"]?>
			</td>
		</tr>
		<?
	}

	if (doubleval($arResult["VAT_SUM_FORMATED"]) > 0)
	{
		?>
		<tr>
			<td align="right">
				<b><?=GetMessage("SOA_TEMPL_SUM_VAT")?></b>
			</td>
			<td align="right" colspan="6"><?=$arResult["VAT_SUM_FORMATED"]?></td>
		</tr>
		<?
	}

	if(!empty($arResult["arTaxList"]))
	{
		foreach($arResult["arTaxList"] as $val)
		{
			?>
			<tr>
				<td align="right"><?=$val["NAME"]?> <?=$val["VALUE_FORMATED"]?>:</td>
				<td align="right" colspan="6"><?=$val["VALUE_MONEY_FORMATED"]?></td>
			</tr>
			<?
		}
	}
	if (doubleval($arResult["DELIVERY_PRICE"]) > 0)
	{
		?>
		<tr>
			<td align="right">
				<b><?=GetMessage("SOA_TEMPL_SUM_DELIVERY")?></b>
			</td>
			<td align="right" colspan="6"><?=$arResult["DELIVERY_PRICE_FORMATED"]?></td>
		</tr>
		<?
	}
	?>
	<tr>
		<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_IT")?></b></td>
		<td align="right" colspan="6"><b><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></b>
		</td>
	</tr>
	<?
	if (strlen($arResult["PAYED_FROM_ACCOUNT_FORMATED"]) > 0)
	{
		?>
		<tr>
			<td align="right"><b><?=GetMessage("SOA_TEMPL_SUM_PAYED")?></b></td>
			<td align="right" colspan="6"><?=$arResult["PAYED_FROM_ACCOUNT_FORMATED"]?></td>
		</tr>
		<?
	}
	?>
</table>
*/?>
<?/*
<br /><br />
<b><?=GetMessage("SOA_TEMPL_SUM_ADIT_INFO")?></b><br /><br />
<table class="sale_order_full_table">
	<tr>
		<td width="50%" align="left" valign="top"><?=GetMessage("SOA_TEMPL_SUM_COMMENTS")?><br />
			<textarea rows="4" cols="40" name="ORDER_DESCRIPTION"><?=$arResult["USER_VALS"]["ORDER_DESCRIPTION"]?></textarea>
		</td>
	</tr>
</table>
*/?>


				

				
				<input type="hidden" name="confirmorder" id="confirmorder" value="Y">
				<input type="hidden" name="profile_change" id="profile_change" value="N">
				<br /><br />
				<div align="right">
				<?/*
				<input type="button" name="submitbutton" onClick="submitForm('Y');" value="<?=GetMessage("SOA_TEMPL_BUTTON")?>">
				*/?>
				</div>
			</div>
		</div>
		
		<div id="form_new"></div>
		<script>
		<!--
		var newform = document.createElement("FORM");
		newform.method = "POST";
		newform.action = "";
		newform.name = "<?=$FORM_NAME?>";
		newform.id = "ORDER_FORM_ID_NEW";
		var im = document.getElementById('order_form_id');
		document.getElementById("form_new").appendChild(newform);
		newform.appendChild(im);
		//-->
		</script>
		
		<?
	}
}
?>
</div>
