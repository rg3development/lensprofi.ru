<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? //echo ShowError($arResult["ERROR_MESSAGE"]);
//echo GetMessage("STB_ORDER_PROMT"); ?>

<?/*
<div style="display: block">
	<div id="bskPopup">
		<div class="block788a">Товары в корзине<img src="/img/pic15c.gif" class="bskCloser" alt="" /></div>
		<div class="block788b">
		<? //print_r($arResult["ITEMS"]["AnDelCanBuy"]); ?>
			<div class="block368">
				<table class="wishlist q9">
					<tr>
						<td style="border: 0px; padding: 0;"><div style="height: 11px;"></div></td>
					</tr>
					<? $i = 0; foreach($arResult["ITEMS"]["AnDelCanBuy"] as $arBasketItems) { ?>
						<?
						$arr_props_by_code = array();
						foreach($arBasketItems['PROPS'] as $one_prop)
						{
							$arr_props_by_code[$one_prop['CODE']] = $one_prop;
						};
						?>
						<tr>
							<td style="border: none">
								<table class="positions" style="width: 100%">
									<tr>
										<td colspan="7">
											<div class="srvBlock1">
												<ul>
													<li><span></span></li>
													<li><a href="/catalog/<?=kt::getSectionID($arBasketItems['PRODUCT_ID']);?>/<?=$arBasketItems['PRODUCT_ID']?>/"><?=$arBasketItems["NAME"] ?></a></li>
													<li><div class="sm1">Количество:</div></li>
													<li><input type="text" class="w43 prod_quantity" name="QUANTITY_<?=$arBasketItems["ID"] ?>" value="<?=$arBasketItems["QUANTITY"]?>" /></li>
													<li><p class="price2"><?=$arBasketItems["PRICE_FORMATED"]?></p></li>
												</ul>
												<span class="remover"><img src="/img/pic15.gif" alt="" /></span>
												<input type="checkbox" name="DELETE_<?=$arBasketItems["ID"] ?>" id="DELETE_<?=$i?>" value="Y">
											</div>
										</td>
									</tr>
									<tr>
										<td rowspan="2"><img height="80px" src="<?=kt::getPreviewPicture($arBasketItems['PRODUCT_ID']);?>" alt="" /></td>
										<td>Глаз</td>					
										<td>Радиус</td>					
										<td>Оптическая сила</td>
									</tr>
									<tr>	
										<td><?=$arr_props_by_code['eye']['VALUE']?></td>
										<td><?=$arr_props_by_code['radius']['VALUE']?></td>
										<td><?=$arr_props_by_code['force']['VALUE']?></td>
									</tr>
								</table>
							</td>
						</tr>
					<? $i ++; } ?>
				</table>
			</div>
			
			<div class="reserveBlock">
				<table>
					<tr>
						<td width="400px;"><p>Товаров на сумму: <nobr><?=$arResult["allSum_FORMATED"]?></nobr></p></td>
						<td><input  id="continue" src="/img/bt216a.png" type="image" value="submit" name="BasketRefresh"></td> <?// кнопка не рефрешит, она закрывает?>
						<td><input id="order_basket" src="/img/bt218b.png" type="image" value="submit" name="BasketOrder" id="basketOrderButton2"></td>
					</tr>
				</table> 
			</div>
		</div>
		<div class="block788c">&nbsp;</div>
	</div>
</div>
*/?>

<style type="text/css">
	.vmiddle tr td
	{
		vertical-align: middle;
	}
	.wpad tr td.nopad
	{
		padding: 0px;
	}
</style>

		<? 
		$i = 0; 
		
		// echo '<pre>';
		// print_R($arResult["ITEMS"]["AnDelCanBuy"]);
		// echo '</pre>';
							
		foreach($arResult["ITEMS"]["AnDelCanBuy"] as $arBasketItems):
			
			// chak for all discounts
			//if($arBasketItems['QUANTITY']>1) // если товар 1 то сикдки за кол-во не может быть
			//{
				$arr_bask_elems[$arBasketItems['PRODUCT_ID']]['id'] = $arBasketItems['PRODUCT_ID'];
				$arr_bask_elems[$arBasketItems['PRODUCT_ID']]['q'] += $arBasketItems['QUANTITY'];
				
			//};
			
			$arr_props_by_code = array();
			foreach($arBasketItems['PROPS'] as $one_prop)
			{
				$arr_props_by_code[$one_prop['CODE']] = $one_prop;
			};
			?>
			<table class="wishlist noBg <?=($i!=0)?'noBg1':'';?> q2">
				<?if($i==0):?>
					<tr>
						<th colspan=2><div>Товары в корзине</div></th>
					</tr>
				<?endif;?>
				<tr>
					<td  colspan=2 class="bg17">
						<?/*
						<div class="srvBlock1" style="float:left;">
							<ul>
								<li><span></span></li>
								<?
								// element code for href
								$res = CIBlockElement::GetByID($arBasketItems['PRODUCT_ID']);
								if($ar_res = $res->GetNext()) {}; 
								?>
								<li><a href="/catalog/<?=$ar_res['CODE']?>/"><?=$arBasketItems["NAME"] ?></a></li>
								<?//<li><div class="sm1">Количество:</div></li>?>
								<?//<li><input type="text" class="w43 stat_basket_quantity" name="QUANTITY_<?=$arBasketItems["ID"]?>" value="<?=$arBasketItems["QUANTITY"]?>" /></li>?>
							</ul>			
						</div>
						
						<div class="priceAndRemove" style="width: auto">
							<span class="sm1" style="border-left: none">Количество:</span>
							<input type="text" class="w43 stat_basket_quantity" name="QUANTITY_<?=$arBasketItems["ID"]?>" value="<?=$arBasketItems["QUANTITY"]?>" /> |
							<div class="remover stat_basket_remover" delid="DELETE_<?=$arBasketItems["ID"]?>"><img src="/img/pic15.gif" alt="" /></div>
							<div class="priceR"><span class="price2"><?=$arBasketItems["PRICE_FORMATED"]?></span></div>
						</div>
						<div style="clear:both;"></div>
						*/?>
						<div>
							<table class="verst" style="width: 100%">
								<tr>
									<td style="padding: 0 0 0 30px">
										<?
										// element code for href
										$res = CIBlockElement::GetByID($arBasketItems['PRODUCT_ID']);
										if($ar_res = $res->GetNext()) {}; 
										?>
										<a href="/catalog/<?=$ar_res['CODE']?>/" style="font-size: 14px;padding-right: 7px;text-decoration: underline;white-space: nowrap;"><?=$arBasketItems["NAME"] ?></a>
									</td>
									<td style="text-align:right">
										<table style="margin: 0 0 0 auto" class="vmiddle wpad">
											<tr>
												<td class="nopad" style="padding: 0 9px 0 0">
													<span style="font-size: 11px; line-height: 12px;">Количество:</span>
												</td>
												<td class="nopad">
													<input type="text" class="w43 stat_basket_quantity" name="QUANTITY_<?=$arBasketItems["ID"]?>" value="<?=$arBasketItems["QUANTITY"]?>" />
												</td>
												<td>
												<td class="nopad" style="padding: 0 10px 0 3px">
													<div style="height: 19px; border-left: 1px solid black">&nbsp;</span>
												</td>
												<td class="nopad" style="padding: 0 10px 0 0">
													<span style="font-size: 11px; line-height: 12px;">Цена за упаковку:</span>
												</td>
												<td class="nopad" style="padding: 0 14px 0 0">
													<div class="priceR" style="padding-top: 0px"><span class="price2"><?=$arBasketItems["PRICE_FORMATED"]?></span></div>
												</td>
												<td class="nopad" style="padding: 0 13px 0 0">
													<div class="stat_basket_remover" style="cursor: pointer" delid="DELETE_<?=$arBasketItems["ID"]?>"><img src="/img/pic15.gif" alt="" /></div>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td  style="width:100px;">
						<a href="#"><img src="<?=kt::getPreviewPicture($arBasketItems['PRODUCT_ID']);?>" alt="" class="pic72" /></a>
					</td>
					<td class="cartList">
						<div class="cartListData">
						<div style="float:left;">
							 <table>
									<tr>
										<?if(isset($arr_props_by_code['eye']['VALUE']) and $arr_props_by_code['eye']['VALUE']!=''):?><td>Глаз</td><?endif;?>
										<?if(isset($arr_props_by_code['radius']['VALUE']) and $arr_props_by_code['radius']['VALUE']!=''):?><td>Радиус</td><?endif;?>
										<?if(isset($arr_props_by_code['force']['VALUE']) and $arr_props_by_code['force']['VALUE']!=''):?><td>Оптическая сила</td><?endif;?>
										<?if(isset($arr_props_by_code['addid']['VALUE']) and $arr_props_by_code['addid']['VALUE']!=''):?><td>Аддидация</td><?endif;?>
										<?if(isset($arr_props_by_code['color']['VALUE']) and $arr_props_by_code['color']['VALUE']!=''):?><td>Цвет</td><?endif;?>
										<?if(isset($arr_props_by_code['cyl_force']['VALUE']) and $arr_props_by_code['cyl_force']['VALUE']!=''):?><td>Оптическая сила цилиндра</td><?endif;?>
										<?if(isset($arr_props_by_code['axis']['VALUE']) and $arr_props_by_code['axis']['VALUE']!=''):?><td>Ось</td><?endif;?>
									</tr>
									<tr>	
										<?=(isset($arr_props_by_code['eye']['VALUE']) and $arr_props_by_code['eye']['VALUE']!='')?'<td>'.$arr_props_by_code['eye']['VALUE'].'</td>':''?>
										<?=(isset($arr_props_by_code['radius']['VALUE']) and $arr_props_by_code['radius']['VALUE']!='')?'<td>'.$arr_props_by_code['radius']['VALUE'].'</td>':''?>
										<?=(isset($arr_props_by_code['force']['VALUE']) and $arr_props_by_code['force']['VALUE']!='')?'<td>'.$arr_props_by_code['force']['VALUE'].'</td>':''?>
										<?=(isset($arr_props_by_code['addid']['VALUE']) and $arr_props_by_code['addid']['VALUE']!='')?'<td>'.$arr_props_by_code['addid']['VALUE'].'</td>':''?>
										<?=(isset($arr_props_by_code['color']['VALUE']) and $arr_props_by_code['color']['VALUE']!='')?'<td>'.$arr_props_by_code['color']['VALUE'].'</td>':''?>
										<?=(isset($arr_props_by_code['cyl_force']['VALUE']) and $arr_props_by_code['cyl_force']['VALUE']!='')?'<td>'.$arr_props_by_code['cyl_force']['VALUE'].'</td>':''?>
										<?=(isset($arr_props_by_code['axis']['VALUE']) and $arr_props_by_code['axis']['VALUE']!='')?'<td>'.$arr_props_by_code['axis']['VALUE'].'</td>':''?>
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
			<?$i++;?>
		<?endforeach;?>
		<?
						
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
		
		//echo '***'.$CHAK_DISCOUNT;
		
		if($CHAK_DISCOUNT > 0) $arResult['DISCOUNT_PRICE'] = $arResult['DISCOUNT_PRICE'] + $CHAK_DISCOUNT;
		$FULL_PRICE = $arResult['allSum'] + $arResult['DISCOUNT_PRICE'];
		?>
		<div class="performance">
			<table>
				<tr>
					<td style="border-left: 1px solid #cacbcb; padding-left: 10px">Товаров на сумму:</td>
					<td style="border-right: 1px solid #cacbcb; padding-right: 10px; text-align: right"><?=$FULL_PRICE?> руб.</td>
				</tr>
				<?if($arResult['DISCOUNT_PRICE']>0):?>
					<tr>
						<td style="border-left: 1px solid #cacbcb; padding-left: 10px">Скидка:</td>
						<td style="border-right: 1px solid #cacbcb; padding-right: 10px; text-align: right"><?=$arResult['DISCOUNT_PRICE']?> руб.</td>
					</tr>
				<?endif;?>
				<?/*
				<tr>
					<td>Скидка при покупке трех и более товаров:</td>
					<td>5%</td>
				</tr>
				<tr>
					<td>Скидка за рекомендацию другу:</td>
					<td>3%</td>
				</tr>
				*/?>
				<tr>
					<td style="border-left: 1px solid #cacbcb; padding-left: 10px"><p>Итого:</p></td>
					<td style="border-right: 1px solid #cacbcb; padding-right: 10px; text-align: right"><p><?=$arResult['allSum_FORMATED'];?></p></td>
				</tr>
				<tr>
					<td colspan="2" style="border-bottom: 1px solid #444; border-right: 1px solid #444;  border-left: 1px solid #444; border-top: 1px solid #cacbcb; background: #444; text-align: center; padding: 5px 3px 0 5px"><a href="#" onClick="show_filter();" class="continue_choose_product"><img src="/img/bt216a.png" alt="" /></a><a href="/personal/order.php"><img  style="margin: 0 auto 0" src="/img/bt218b.png" alt="" /></a></td>
				</tr>
			</table>
			<?/*<p class="note2">Доставка по Москве - бесплатно, по Московской области - 35 рублей за 1 км от МКАД.</p>*/?>
		</div>
		<?
		// echo '<pre>';
		// print_R($arResult);
		// echo '</pre>';
		?>