<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?/*
<form method="GET" action="<?= $arResult["CURRENT_PAGE"] ?>" name="bfilter">
<table class="sale-personal-order-list-filter data-table">
	<tr>
		<th colspan="2"><?echo GetMessage("SPOL_T_F_FILTER")?></th>
	</tr>
	<tr>
		<td><?=GetMessage("SPOL_T_F_ID");?>:</td>
		<td><input type="text" name="filter_id" value="<?=htmlspecialchars($_REQUEST["filter_id"])?>" size="10"></td>
	</tr>
	<tr>
		<td><?=GetMessage("SPOL_T_F_DATE");?>:</td>
		<td><?$APPLICATION->IncludeComponent(
	"bitrix:main.calendar",
	"",
	Array(
		"SHOW_INPUT" => "Y", 
		"FORM_NAME" => "bfilter", 
		"INPUT_NAME" => "filter_date_from", 
		"INPUT_NAME_FINISH" => "filter_date_to", 
		"INPUT_VALUE" => $_REQUEST["filter_date_from"], 
		"INPUT_VALUE_FINISH" => $_REQUEST["filter_date_to"], 
		"SHOW_TIME" => "N" 
	)
);?></td>
	</tr>
	<tr>
		<td><?=GetMessage("SPOL_T_F_STATUS")?>:</td>
		<td><select name="filter_status">
				<option value=""><?=GetMessage("SPOL_T_F_ALL")?></option>
				<?
				foreach($arResult["INFO"]["STATUS"] as $val)
				{
					if ($val["ID"]!="F")
					{
						?><option value="<?echo $val["ID"]?>"<?if($_REQUEST["filter_status"]==$val["ID"]) echo " selected"?>>[<?=$val["ID"]?>] <?=$val["NAME"]?></option><?
					}
				}
				?>
		</select></td>
	</tr>
	<tr>
		<td><?=GetMessage("SPOL_T_F_PAYED")?>:</td>
		<td><select name="filter_payed">
				<option value=""><?echo GetMessage("SPOL_T_F_ALL")?></option>
				<option value="Y"<?if ($_REQUEST["filter_payed"]=="Y") echo " selected"?>><?=GetMessage("SPOL_T_YES")?></option>
				<option value="N"<?if ($_REQUEST["filter_payed"]=="N") echo " selected"?>><?=GetMessage("SPOL_T_NO")?></option>
		</select></td>
	</tr>
	<tr>
		<td><?=GetMessage("SPOL_T_F_CANCELED")?>:</td>
		<td>
			<select name="filter_canceled">
				<option value=""><?=GetMessage("SPOL_T_F_ALL")?></option>
				<option value="Y"<?if ($_REQUEST["filter_canceled"]=="Y") echo " selected"?>><?=GetMessage("SPOL_T_YES")?></option>
				<option value="N"<?if ($_REQUEST["filter_canceled"]=="N") echo " selected"?>><?=GetMessage("SPOL_T_NO")?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?=GetMessage("SPOL_T_F_HISTORY")?>:</td>
		<td>
			<select name="filter_history">
				<option value="N"<?if($_REQUEST["filter_history"]=="N") echo " selected"?>><?=GetMessage("SPOL_T_NO")?></option>
				<option value="Y"<?if($_REQUEST["filter_history"]=="Y") echo " selected"?>><?=GetMessage("SPOL_T_YES")?></option>
			</select>
		</td>
	</tr>
	<tr>
		<th colspan="2">
			<input type="submit" name="filter" value="<?=GetMessage("SPOL_T_F_SUBMIT")?>">&nbsp;&nbsp;
			<input type="submit" name="del_filter" value="<?=GetMessage("SPOL_T_F_DEL")?>">
		</th>
	</tr>
</table>
</form>
<br />
<?if(strlen($arResult["NAV_STRING"]) > 0):?>
	<p><?=$arResult["NAV_STRING"]?></p>
<?endif?>
<table class="sale-personal-order-list data-table">
	<tr>
		<th><?=GetMessage("SPOL_T_ID")?><br /><?=SortingEx("ID")?></th>
		<th><?=GetMessage("SPOL_T_PRICE")?><br /><?=SortingEx("PRICE")?></th>
		<th><?=GetMessage("SPOL_T_STATUS")?><br /><?=SortingEx("STATUS_ID")?></th>
		<th><?=GetMessage("SPOL_T_BASKET")?><br /></th>
		<th><?=GetMessage("SPOL_T_PAYED")?><br /><?=SortingEx("PAYED")?></th>
		<th><?=GetMessage("SPOL_T_CANCELED")?><br /><?=SortingEx("CANCELED")?></th>
		<th><?=GetMessage("SPOL_T_PAY_SYS")?><br /></th>
		<th><?=GetMessage("SPOL_T_ACTION")?></th>
	</tr>
	<?foreach($arResult["ORDERS"] as $val):?>
		<tr>
			<td><b><?=$val["ORDER"]["ID"]?></b><br /><?=GetMessage("SPOL_T_FROM")?> <?=$val["ORDER"]["DATE_INSERT_FORMAT"]?></td>
			<td><?=$val["ORDER"]["FORMATED_PRICE"]?></td>
			<td><?=$arResult["INFO"]["STATUS"][$val["ORDER"]["STATUS_ID"]]["NAME"]?><br /><?=$val["ORDER"]["DATE_STATUS"]?></td>
			<td><?
				$bNeedComa = False;
				foreach($val["BASKET_ITEMS"] as $vval)
				{
					?><li><?
					if (strlen($vval["DETAIL_PAGE_URL"])>0) 
						echo '<a href="'.$vval["DETAIL_PAGE_URL"].'">';
					echo $vval["NAME"];
					if (strlen($vval["DETAIL_PAGE_URL"])>0) 
						echo '</a>';
						echo ' - '.$vval["QUANTITY"].' '.GetMessage("STPOL_SHT");
					?></li><?
				}
			?></td>
			<td><?=(($val["ORDER"]["PAYED"]=="Y") ? GetMessage("SPOL_T_YES") : GetMessage("SPOL_T_NO"))?></td>
			<td><?=(($val["ORDER"]["CANCELED"]=="Y") ? GetMessage("SPOL_T_YES") : GetMessage("SPOL_T_NO"))?></td>
			<td>
				<?=$arResult["INFO"]["PAY_SYSTEM"][$val["ORDER"]["PAY_SYSTEM_ID"]]["NAME"]?> / 
				<?if (strpos($val["ORDER"]["DELIVERY_ID"], ":") === false):?>
					<?=$arResult["INFO"]["DELIVERY"][$val["ORDER"]["DELIVERY_ID"]]["NAME"]?>
				<?else:
					$arId = explode(":", $val["ORDER"]["DELIVERY_ID"]);
				?>
					<?=$arResult["INFO"]["DELIVERY_HANDLERS"][$arId[0]]["NAME"]?> (<?=$arResult["INFO"]["DELIVERY_HANDLERS"][$arId[0]]["PROFILES"][$arId[1]]["TITLE"]?>)
				<?endif?>
			</td>
			<td><a title="<?=GetMessage("SPOL_T_DETAIL_DESCR")?>" href="<?=$val["ORDER"]["URL_TO_DETAIL"]?>"><?=GetMessage("SPOL_T_DETAIL")?></a><br />
				<a title="<?=GetMessage("SPOL_T_COPY_ORDER_DESCR")?>" href="<?=$val["ORDER"]["URL_TO_COPY"]?>"><?=GetMessage("SPOL_T_COPY_ORDER")?></a><br />
				<?if($val["ORDER"]["CAN_CANCEL"] == "Y"):?>
					<a title="<?=GetMessage("SPOL_T_DELETE_DESCR")?>" href="<?=$val["ORDER"]["URL_TO_CANCEL"]?>"><?=GetMessage("SPOL_T_DELETE")?></a>
				<?endif;?>
			</td>
		</tr>
	<?endforeach;?>
</table>


<?if(strlen($arResult["NAV_STRING"]) > 0):?>
	<p><?=$arResult["NAV_STRING"]?></p>
<?endif?>
*/?>

<?
$month = array(
				'01'=>'января',
				'02'=>'февраля',
				'03'=>'марта',
				'04'=>'апреля',
				'05'=>'мая',
				'06'=>'июня',
				'07'=>'июля',
				'08'=>'августа',
				'09'=>'сентября',
				'10'=>'октября',
				'11'=>'ноября',
				'12'=>'декабря',
				);
?>

<script type="text/javascript">
	function copy_order(ord_id)
	{
		var ids = "";
		$.each($("span.order"+ord_id+":visible"), function(){
			ids=ids+$(this).attr("prodid")+"x";
		});
		var href = "?ID="+ord_id+"&COPY_ORDER=Y&ids="+ids;
		window.location = href;
	};
</script>

<?foreach($arResult['ORDERS'] as $ord_key=>$order):?>
			<div>
			<table class="wishlist noBg <?=($ord_key!=0)?'CartTableMargin':''?>" style="width: 100%">
				<tr>
					<th colspan=2><div><?=$DB->FormatDate($order['ORDER']['DATE_INSERT'], 'DD.MM.YYYY HH:MI:SS', 'DD').' '.$month[$DB->FormatDate($order['ORDER']['DATE_INSERT'], 'DD.MM.YYYY HH:MI:SS', 'MM')].', '.$DB->FormatDate($order['ORDER']['DATE_INSERT'], 'DD.MM.YYYY HH:MI:SS', 'YYYY');?>
					<a href="<?=$order['ORDER']['URL_TO_COPY']?>">Повторить заказ</a>
					<a href="javascript:copy_order(<?=$order['ORDER']['ID']?>);">Повторить заказ</a></div></th>
				</tr>
				<?foreach($order['BASKET_ITEMS'] as $key=>$item):?>
					<?
					$pict ='';
					$re = CIBlockElement::GetByID($item['PRODUCT_ID']);
					if($elem=$re->GetNext())
					{
						$pict = CFile::GetPath($elem['PREVIEW_PICTURE']);
					};
					
					/*
					$db_order = CSaleOrderPropsValue::GetOrderProps($order['ORDER']['ID']);
					$list1 = array();
					while($one_l=$db_order->GetNext())
					{
						$list1[] = $one_l;
					};
					
					echo '<pre>';
					print_R($list1);
					echo '</pre>';
					*/
					
					$db_res = CSaleBasket::GetPropsList(
													array(
															"SORT" => "ASC",
															"NAME" => "ASC"
														),
													array("BASKET_ID" => $item['ID'])
												);
					$list1 = array();
					
					while ($ar_res = $db_res->Fetch())
					{
						if($ar_res['NAME']!='Catalog XML_ID' and $ar_res['NAME']!='Product XML_ID')
						{
							$list1[] = $ar_res;
							
						};
					};
					// echo '<pre>';
					// print_R($item);
					// echo '</pre>';
					?>
					<?if($key==0):?>
								<tr>
									<td  colspan=2 class="bg17">
										<div class="srvBlock1" style="float:left;">
											<ul>
												<li><span></span></li>
												<li><a href="<?=$item['DETAIL_PAGE_URL']?>"><?=$item['NAME']?></a></li>
											</ul>
										</div>
										
										<div style="position:relative; height:20px; padding-bottom:10px; float:right; width:20px;">
											<span class="switchOff"><img src="/img/pic12b.gif" alt="" /></span>
											<span prodid="<?=$item['ID']?>" class="order<?=$order['ORDER']['ID']?> switchOn"><img src="/img/pic12a.gif" alt="" /></span>
										</div>
										<div style="clear:both;"></div>
									</td>
								</tr>
								<tr>
									<td  style="width:100px;">
										<a href="<?=$item['DETAIL_PAGE_URL']?>"><img src="<?=$pict?>" alt="" class="pic72" /></a>
									</td>
									<td class="cartList">
										<div class="cartListData">
										<div style="float:left;">
											
											 <table>
													<tr>
														<?foreach($list1 as $param1):?>
														 <td><?=$param1['NAME']?></td>
														<?endforeach;?>
														<td>Количество</td>
													</tr>
													<tr>
														<?foreach($list1 as $param1):?>
														 <td class="bottomTableForIe"><?=$param1['VALUE']?></td>
														 <?endforeach;?>
														 <td><?=$item['QUANTITY']?></td>
													</tr>
											</table>
											
												<?/*
											   <table>
													<tr>
														 <td>Праметр 1</td>
														 <td>Длинное название параметра 2</td>
														 <td>Параметр3</td>
														 <td>Количество</td>
													</tr>
													<tr>
														 <td class="bottomTableForIe">Значение 1</td>
														 <td class="bottomTableForIe">Знач 2</td>
														 <td class="bottomTableForIe">3</td>
														 <td class="bottomTableForIe">1</td>
													</tr>
													</table>
												*/?>
										</div>
										<div style="float:right;">
											<table>
											<tr>
												 <td style="text-align:right;">Актуальная цена</td>
											</tr>
											<tr>
												 <td  class="bottomTableForIe" style="text-align:right;"><span class="price2"><?=$item['PRICE']?> руб.</span></td>
											</tr>
											</table>
										</div>
										</div>
										<div style="position:relative;">
											<div class="dottedLine1 bg17"></div>
											<div class="dottedLine2"></div>
										</div>
									</td>
								</tr>
							</table>
					<?else:?>
							<table class="wishlist noBg noBg1" style="width: 100%">
								<tr>
									<td  colspan=2 class="bg17">
										<div class="srvBlock1" style="float:left;">
											<ul>
												<li><span></span></li>
												<li><a href="<?=$item['DETAIL_PAGE_URL']?>"><?=$item['NAME']?></a></li>
											</ul>
										</div>
										
										<div style="position:relative; height:20px; padding-bottom:10px; float:right; width:20px;">
											<span class="switchOff"><img src="/img/pic12b.gif" alt="" /></span>
											<span prodid="<?=$item['ID']?>" class="order<?=$order['ORDER']['ID']?> switchOn"><img src="/img/pic12a.gif" alt="" /></span>
										</div>
										<div style="clear:both;"></div>
									</td>
								</tr>
								<tr>
									<td  style="width:100px;">
										<a href="<?=$item['DETAIL_PAGE_URL']?>"><img src="<?=$pict?>" alt="" class="pic72" /></a>
									</td>
									<td class="cartList">
										<div class="cartListData">
										<div style="float:left;">
											
											<table>
													<tr>
														<?foreach($list1 as $param1):?>
														 <td><?=$param1['NAME']?></td>
														<?endforeach;?>
														<td>Количество</td>
													</tr>
													<tr>
														<?foreach($list1 as $param1):?>
														 <td class="bottomTableForIe"><?=$param1['VALUE']?></td>
														 <?endforeach;?>
														 <td><?=$item['QUANTITY']?></td>
													</tr>
											</table>
											
												<?/*
											   <table>
												<tr>
													 <td>Праметр 1</td>
													 <td>Длинное название параметра 2</td>
													 <td>Параметр3</td>
													 <td>Количество</td>
												</tr>
												<tr>
													 <td class="bottomTableForIe">Значение 1</td>
													 <td class="bottomTableForIe">Знач 2</td>
													 <td class="bottomTableForIe">3</td>
													 <td class="bottomTableForIe">1</td>
												</tr>
												</table>
												*/?>
										</div>
										<div style="float:right;">
											<table>
											<tr>
												 <td style="text-align:right;">Актуальная цена</td>
											</tr>
											<tr>
												 <td  class="bottomTableForIe" style="text-align:right;"><span class="price2"><?=$item['PRICE']?> руб.</span></td>
											</tr>
											</table>
										</div>
										</div>
										<div style="position:relative;">
											<div class="dottedLine1 bg17"></div>
											<div class="dottedLine2"></div>
										</div>
									</td>
								</tr>
							</table>
					<?endif;?>
				<?endforeach;?>
		</div>
<?endforeach;?>

<?
// echo '<pre>';
// print_R($arResult);
// echo '</pre>';
?>