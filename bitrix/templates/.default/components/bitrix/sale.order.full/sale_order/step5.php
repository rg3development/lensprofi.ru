<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<div class="block940">
	<div style="margin:10px 0;"><a href="/order/" class="srv2"><span>Вернуться к первому шагу</span></a></div>
	<form action="" method="post">
		<fieldset>
			<table class="data1 q3">
				<tr>
					<th colspan="2">Данные покупателя</th>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><p>Имя:<span>*</span></p></td>
					<td>
						<table>
							<tr>
								<td><input type="text" class="w196" /></td>
								<td><p class="alertError">Ошибка! не введено имя.</p></td>
							</tr>
						</table>
					</td>
				</tr>							
				<tr>
					<td><p>Фамилия:<span>*</span></p></td>
					<td>
						<table>
							<tr>
								<td><input type="text" class="w196" /></td>
								<td><p class="alertError">Ошибка! не введено имя.</p></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><p>Телефон:<span>*</span></p></td>
					<td>
						<ul class="type1">
							<li>+7</li>
							<li><input type="text" class="w43" /></li>
							<li><input type="text" class="w92" /></li>
							<li><p class="alertError">Ошибка! не введен номер телефона.</p></li>
						</ul>
					</td>
				</tr>
				<tr>
					<td><p>Дополнительная<br />информация:</p></td>
					<td><textarea rows="5" cols="40" class="w232" name="msg"></textarea></td>
				</tr>
				<tr>
					<td>&nbsp;</td>						
					<td>
						<ul class="type2">
							<li><label for="sms">Напомнить о следующем заказе: </label></li>
							<li style="clear:left; margin-top:5px;"><input type="checkbox" id="sms" /><label for="sms">Через SMS</label></li>
							<!--<li>Следущее оповещение:</li>-->
							<li style="position:relative; top:12px; left:10px"><input type="text" name="date1" id="date1" class="w92 date-pick" value="" /></li>
						</ul>
						
						<ul class="type2">
							<li><input type="checkbox" id="email" checked="checked" /><label for="email">Через e-mail</label></li>
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
					<td><p>Адрес:<span>*</span></p></td>
					<td>
						<table>
							<tr>
								<td><select size="1" name="subway" class="w250">
										<option value="adr1">adr1</option>
										<option value="adr2">adr2</option>
										<option value="adr3">adr3</option>
										<option value="adr4">adr4</option>
										<option value="Новый адрес">Новый адрес</option>
									</select>
								</td>
								<td><p class="alertError">Ошибка! не введен город.</p></td>
							</tr>
						</table><br />
						<table >
							<tr>
								<td rowspan="2">
									<ul class="type3">
										<li><input type="radio" name="position" id="msk" checked="checked" /><label for="msk">Москва</label></li>
										<li><input type="radio" name="position" id="mskRegion" /><label for="mskRegion">Подмосковье</label></li>
									</ul>
								</td>
								<td><select size="1" name="subway" class="w250"><option value="Выбрать станцию метро">Выбрать станцию метро</option></select></td>
							</tr>
							<tr>
								<td><input type="text" class="w196 rel10" value="Город" onfocus="if(this.value=='Город') this.value='';" onblur="if(this.value=='') this.value='Город';" disabled="disabled" /></td>
							</tr>
							<tr>
								<td colspan="2">
									<span>Напишите адрес доставки и при необходимости дополнительную информацию:</span><input type="text" class="w450" /><br /><br />
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
						<table class="data4">
							<tr>
								<th>&nbsp;</th>
								<th>Тип доставки</th>
								<th>Срок</th>
								<th>Время</th>
								<th>Цена</th>																														
							</tr>
							<tr>
								<td><input type="radio" name="del" id="d1" /></td>
								<td><label for="d1">Самовывоз</label></td>										
								<td>По договорённости</td>
								<td>10-19</td>										
								<td>Бесплатно</td>										
							</tr>
							<tr>
								<td><input type="radio" name="del" id="d2" /></td>
								<td><label for="d2">Доставка в субботу, воскресенье, праздничные дни</label></td>										
								<td>По согласованию</td>
								<td>По согласованию</td>										
								<td>300 руб.</td>										
							</tr>
							<tr>
								<td><input type="radio" name="del" id="d3" /></td>
								<td><label for="d3">Доставка утром до 10:00, вечером с 19:00, в рабочие дни, при заказе от 1000 руб.</label></td>										
								<td>0-1 дня</td>
								<td>Утром до 10-00 или вечером после 19:00</td>										
								<td>180 руб.</td>										
							</tr>
							<tr>
								<td><input type="radio" name="del" id="d4" /></td>
								<td><label for="d4">Экспресс-доставка по рабочим дням заказов до 1000 руб.</label></td>										
								<td>3 часа</td>
								<td>с 10:00 до 19:00</td>										
								<td>250 руб.</td>										
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</fieldset>
	</form>
</div>
<div class="performance">
	<table>
		<tr>
			<td>Товаров на сумму:</td>
			<td>1100 руб.</td>
		</tr>
		<tr>
			<td>Скидка при покупке трех и более товаров:</td>
			<td>5%</td>
		</tr>
		<tr>
			<td>Скидка за рекомендацию другу:</td>
			<td>3%</td>
		</tr>
		<tr>
			<td><p>Итого:</p></td>
			<td><p>989 руб.</p></td>
		</tr>
		<tr>
			<td colspan="2"><a href="#"><img src="/img/bt216b.png" alt="" /></a><a href="#"><img src="/img/bt218b.png" alt="" /></a></td>
		</tr>
	</table>
</div>

<?
/*
<table border="0" cellspacing="0" cellpadding="5">
<tr>
	<td valign="top" width="60%" align="right">
		<input type="submit" name="contButton" value="<?= GetMessage("SALE_CONFIRM")?>">
	</td>
	<td valign="top" width="5%" rowspan="3">&nbsp;</td>
	<td valign="top" width="35%" rowspan="3">
		<?echo GetMessage("STOF_CORRECT_PROMT_NOTE")?><br /><br />
		<?echo GetMessage("STOF_CONFIRM_NOTE")?><br /><br />
		<?echo GetMessage("STOF_CORRECT_ADDRESS_NOTE")?><br /><br />
		<?echo GetMessage("STOF_PRIVATE_NOTES")?>
	</td>
</tr>
<tr>
	<td valign="top" width="60%">
		<?
		if(!empty($arResult["ORDER_PROPS_PRINT"]))
		{
			?>
			<b><?echo GetMessage("STOF_ORDER_PARAMS")?></b><br /><br />
			<table class="sale_order_full_table">
				<?
				foreach($arResult["ORDER_PROPS_PRINT"] as $arProperties)
				{
					if ($arProperties["SHOW_GROUP_NAME"] == "Y")
					{
						?>
						<tr>
							<td colspan="2" align="center"><b><?= $arProperties["GROUP_NAME"] ?></b></td>
						</tr>
						<?
					}
					if(strLen($arProperties["VALUE_FORMATED"])>0)
					{
						?>
						<tr>
							<td width="50%" align="right" valign="top">
								<?= $arProperties["NAME"] ?>:
							</td>
							<td width="50%"><?=$arProperties["VALUE_FORMATED"]?></td>
						</tr>
						<?
					}
				}
				?>
			</table>
			<?
		}
		?>
		<br /><br />
		<b><?echo GetMessage("STOF_PAY_DELIV")?></b><br /><br />

		<table class="sale_order_full_table">
			<tr>
				<td width="50%" align="right"><?= GetMessage("SALE_DELIV_SUBTITLE")?>:</td>
				<td width="50%">
					<?
					//echo "<pre>"; print_r($arResult); echo "</pre>";
					if (is_array($arResult["DELIVERY"]))
					{
						echo $arResult["DELIVERY"]["NAME"];
						if (is_array($arResult["DELIVERY_ID"]))
						{
							echo " (".$arResult["DELIVERY"]["PROFILES"][$arResult["DELIVERY_PROFILE"]]["TITLE"].")";
						}
					}
					elseif ($arResult["DELIVERY"]=="ERROR")
					{
						echo ShowError(GetMessage("SALE_ERROR_DELIVERY"));
					}
					else
					{
						echo GetMessage("SALE_NO_DELIVERY");
					}
					?>
				</td>
			</tr>
			<?if(is_array($arResult["PAY_SYSTEM"]) || $arResult["PAY_SYSTEM"]=="ERROR" || $arResult["PAYED_FROM_ACCOUNT"] == "Y")
			{
				?>
				<tr>
					<td width="50%" align="right"><?= GetMessage("SALE_PAY_SUBTITLE")?>:</td>
					<td width="50%">
						<?
						if (is_array($arResult["PAY_SYSTEM"]))
						{
							echo $arResult["PAY_SYSTEM"]["PSA_NAME"];
						}
						elseif ($arResult["PAY_SYSTEM"]=="ERROR")
						{
							echo ShowError(GetMessage("SALE_ERROR_PAY_SYS"));
						}
						elseif($arResult["PAYED_FROM_ACCOUNT"] != "Y")
						{
							echo GetMessage("STOF_NOT_SET");
						}
						if($arResult["PAYED_FROM_ACCOUNT"] == "Y")
							echo " (".GetMessage("STOF_PAYED_FROM_ACCOUNT").")";
						?>				
					</td>
				</tr>
				<?
			}
			?>
		</table>

		<br /><br />
		<b><?= GetMessage("SALE_ORDER_CONTENT")?></b><br /><br />

		<table class="sale_order_full data-table">
			<tr>
				<th><?echo GetMessage("SALE_CONTENT_NAME")?></th>
				<th><?echo GetMessage("SALE_CONTENT_PROPS")?></th>
				<th><?echo GetMessage("SALE_CONTENT_PRICETYPE")?></th>
				<th><?echo GetMessage("SALE_CONTENT_DISCOUNT")?></th>
				<th><?echo GetMessage("SALE_CONTENT_WEIGHT")?></th>
				<th><?echo GetMessage("SALE_CONTENT_QUANTITY")?></th>
				<th><?echo GetMessage("SALE_CONTENT_PRICE")?></th>
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
				<td align="right"><b><?=GetMessage("SALE_CONTENT_WEIGHT_TOTAL")?>:</b></td>
				<td align="right" colspan="6"><?=$arResult["ORDER_WEIGHT_FORMATED"]?></td>
			</tr>
			<tr>
				<td align="right"><b><?=GetMessage("SALE_CONTENT_PR_PRICE")?>:</b></td>
				<td align="right" colspan="6"><?=$arResult["ORDER_PRICE_FORMATED"]?></td>
			</tr>
			<?
			if (doubleval($arResult["DISCOUNT_PRICE_FORMATED"]) > 0)
			{
				?>
				<tr>
					<td align="right"><b><?echo GetMessage("SALE_CONTENT_DISCOUNT")?>:</b></td>
					<td align="right" colspan="6"><?echo $arResult["DISCOUNT_PRICE_FORMATED"]?>
						<?if (strLen($arResult["DISCOUNT_PERCENT_FORMATED"])>0):?>
							(<?echo $arResult["DISCOUNT_PERCENT_FORMATED"];?>)
						<?endif;?>
					</td>
				</tr>
				<?
			}
			if (doubleval($arResult["VAT_PRICE"]) > 0)
			{
				?>
				<tr>
					<td align="right">
						<b><?echo GetMessage("SALE_CONTENT_VAT")?>:</b>
					</td>
					<td align="right" colspan="6"><?=$arResult["VAT_PRICE_FORMATED"]?></td>
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
						<b><?echo GetMessage("SALE_CONTENT_DELIVERY")?>:</b>
					</td>
					<td align="right" colspan="6"><?=$arResult["DELIVERY_PRICE_FORMATED"]?></td>
				</tr>
				<?
			}
			?>
			<tr>
				<td align="right"><b><?= GetMessage("SALE_CONTENT_ITOG")?>:</b></td>
				<td align="right" colspan="6"><b><?=$arResult["ORDER_TOTAL_PRICE_FORMATED"]?></b>
				</td>
			</tr>
			<?
			if (doubleval($arResult["PAYED_FROM_ACCOUNT_FORMATED"]) > 0)
			{
				?>
				<tr>
					<td align="right"><b><?echo GetMessage("STOF_PAY_FROM_ACCOUNT1")?></b></td>
					<td align="right" colspan="6"><?=$arResult["PAYED_FROM_ACCOUNT_FORMATED"]?></td>
				</tr>
				<?
			}
			?>
		</table>

		<br /><br />
		<b><?= GetMessage("SALE_ADDIT_INFO")?></b><br /><br />

		<table class="sale_order_full_table">
			<tr>
				<td width="50%" align="right" valign="top">
					<?= GetMessage("SALE_ADDIT_INFO_PROMT")?>
				</td>
				<td width="50%" valign="top">
					<textarea rows="4" cols="40" name="ORDER_DESCRIPTION"><?=$arResult["ORDER_DESCRIPTION"]?></textarea>
				</td>
			</tr>
		</table>
	</td>
</tr>
<tr>
	<td valign="top" width="60%" align="right">
		<?if(!($arResult["SKIP_FIRST_STEP"] == "Y" && $arResult["SKIP_SECOND_STEP"] == "Y" && $arResult["SKIP_THIRD_STEP"] == "Y" && $arResult["SKIP_FORTH_STEP"] == "Y"))
		{
			?>
			<input type="submit" name="backButton" value="&lt;&lt; <?echo GetMessage("SALE_BACK_BUTTON")?>">
			<?
		}
		?>
		<input type="submit" name="contButton" value="<?= GetMessage("SALE_CONFIRM")?>">
	</td>
</tr>
</table>
*/?>
<?
// echo '<pre>';
// print_R($arResult);
// echo '</pre>';
?>