<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? //echo ShowError($arResult["ERROR_MESSAGE"]);
//echo GetMessage("STB_ORDER_PROMT"); ?>
<style type="text/css">
	.chak tr td
	{
		background: none;
	}
	
	.nopad, table.chak tr td.nopad
	{
		padding: 0;
	}
	
	div#for_me table#for_me2 tr td table.chak tr td, div#for_me table#for_me2 tr td
	{
		padding: 0 0 0 0!important;
	}
	
	div#for_me table.vmiddle tr td
	{
		vertical-align: middle;
	}
	
	table.positions td#first_td
	{
		padding-right: 0!important;
	}
</style>
<script>
 $(document).ready(function() {
       $('html, body').animate({scrollTop:0}, 'slow');
});
</script>
<div id="bskHolder" style="display: block">
	<div id="bskPopup">
		<div class="block788a">Товары в корзине<img src="/img/pic15c.gif" class="bskCloser" alt="" /></div>
		<div class="block788b">
		<? //print_r($arResult["ITEMS"]["AnDelCanBuy"]); ?>
			<div class="block368">
				<?
				// echo '<pre>';
				// print_R($arResult);
				// echo '</pre>';
				?>
				<table class="wishlist q9">
					<tr>
						<td style="border: 0px; padding: 0;"><div style="height: 11px;"></div></td>
					</tr>
					<? $i = 0; foreach($arResult["ITEMS"]["AnDelCanBuy"] as $arBasketItems) { ?>
						<?
						// chak for all discounts
						if($arBasketItems['QUANTITY']>1) // если товар 1 то сикдки за кол-во не может быть
						{
							$arr_bask_elems[$i]['id'] = $arBasketItems['PRODUCT_ID'];
							$arr_bask_elems[$i]['q'] = $arBasketItems['QUANTITY'];
						};
						// echo '<pre>';
						// print_R($arBasketItems);
						// echo '</pre>';
						?>
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
									<?
									// element code for href
									$res = CIBlockElement::GetByID($arBasketItems['PRODUCT_ID']);
									if($ar_res = $res->GetNext()) {}; 
									?>
									<tr>
										<td colspan="7" id="first_td">
											<?/*
											<div class="srvBlock1">
												<ul>
													<li>
														<span></span>
													</li>
													<li>
														<a href="/catalog/<?=$ar_res['CODE'];?>/"><?=$arBasketItems["NAME"] ?></a>
													</li>
													<li>
														<div class="sm1">Количество:</div>
													</li>
													<li>
														<input type="text" class="w43 prod_quantity" name="QUANTITY_<?=$arBasketItems["ID"] ?>" value="<?=$arBasketItems["QUANTITY"]?>" />
													</li>
													<li>
														<p class="price2"><?=$arBasketItems["PRICE_FORMATED"]?></p>
													</li>
												</ul>
												<span class="remover remover_in_float"><img src="/img/pic15.gif" alt="" /></span>
												<input type="checkbox" name="DELETE_<?=$arBasketItems["ID"] ?>" id="DELETE_<?=$i?>" value="Y">
											</div>
											*/?>
											
											<div id="for_me" style="padding: 4px 0">
												<table style="width: 100%" class="chak" id="for_me2">
													<tr>
														<td class="nopad" style="padding: 0 0 0 0">
															<div style="padding: 0 0 0 30px"><a href="/catalog/<?=$ar_res['CODE'];?>/" style="font-size: 14px;text-decoration: underline;white-space: nowrap;"><?=$arBasketItems["NAME"] ?></a></div>
														</td>
														<td class="nopad" style="text-align: right; padding: 0 0 0 0">
															<table class="chak vmiddle" style="margin: 0 0 0 auto">
																<tr>
																	<td>
																		<div style="font-size: 11px;line-height: 12px; padding: 0 13px 0 0">Количество:</div>
																	</td>
																	<td>
																		<div style="padding: 0 3px 0 0"><input type="text" class="w43 prod_quantity" name="QUANTITY_<?=$arBasketItems["ID"] ?>" value="<?=$arBasketItems["QUANTITY"]?>" /></div>
																	</td>
																	<td>
																		<div style="height: 19px; border-left: 1px solid black">&nbsp;</span>
																	</td>	
																	<td>
																		<div style="padding: 0 0 0 10px; font-size: 11px;line-height: 12px;">Цена за упаковку:</div>
																	</td>
																	<td>
																		<p style="padding: 0 0 0 9px" class="price2"><?=$arBasketItems["PRICE_FORMATED"]?></p>
																	</td>
																	<td>
																		<span class="remover_in_float"><img style="cursor: pointer; left: 15px" src="/img/pic15.gif" alt="" /></span>
																		<input type="checkbox" name="DELETE_<?=$arBasketItems["ID"] ?>" id="DELETE_<?=$i?>" value="Y">
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
										<td rowspan="2" style="width: 140px">
											<img height="80px" src="<?=kt::getPreviewPicture($arBasketItems['PRODUCT_ID']);?>" alt="" />
										</td>
										<?if(isset($arr_props_by_code['eye']['VALUE']) and $arr_props_by_code['eye']['VALUE']!=''):?>
											<td>Глаз</td>
										<?endif;?>
										<?if(isset($arr_props_by_code['radius']['VALUE']) and $arr_props_by_code['radius']['VALUE']!=''):?>
											<td>Радиус</td>
										<?endif;?>
										<?if(isset($arr_props_by_code['force']['VALUE']) and $arr_props_by_code['force']['VALUE']!=''):?>
											<td>Оптическая сила</td>
										<?endif;?>
										<?if(isset($arr_props_by_code['addid']['VALUE']) and $arr_props_by_code['addid']['VALUE']!=''):?>
											<td>Аддидация</td>
										<?endif;?>
										<?if(isset($arr_props_by_code['color']['VALUE']) and $arr_props_by_code['color']['VALUE']!=''):?>
											<td>Цвет</td>
										<?endif;?>
										<?if(isset($arr_props_by_code['cyl_force']['VALUE']) and $arr_props_by_code['cyl_force']['VALUE']!=''):?>		<td>Оптическая сила цилиндра</td>
										<?endif;?>
										<?if(isset($arr_props_by_code['axis']['VALUE']) and $arr_props_by_code['axis']['VALUE']!=''):?>
											<td>Ось</td>
										<?endif;?>
										<td>&nbsp;</td>
									</tr>
									<tr>	
										<?=(isset($arr_props_by_code['eye']['VALUE']) and $arr_props_by_code['eye']['VALUE']!='')?'<td>'.$arr_props_by_code['eye']['VALUE'].'</td>':''?>
										<?=(isset($arr_props_by_code['radius']['VALUE']) and $arr_props_by_code['radius']['VALUE']!='')?'<td>'.$arr_props_by_code['radius']['VALUE'].'</td>':''?>
										<?=(isset($arr_props_by_code['force']['VALUE']) and $arr_props_by_code['force']['VALUE']!='')?'<td>'.$arr_props_by_code['force']['VALUE'].'</td>':''?>
										<?=(isset($arr_props_by_code['addid']['VALUE']) and $arr_props_by_code['addid']['VALUE']!='')?'<td>'.$arr_props_by_code['addid']['VALUE'].'</td>':''?>
										<?=(isset($arr_props_by_code['color']['VALUE']) and $arr_props_by_code['color']['VALUE']!='')?'<td>'.$arr_props_by_code['color']['VALUE'].'</td>':''?>
										<?=(isset($arr_props_by_code['cyl_force']['VALUE']) and $arr_props_by_code['cyl_force']['VALUE']!='')?'<td>'.$arr_props_by_code['cyl_force']['VALUE'].'</td>':''?>
										<?=(isset($arr_props_by_code['axis']['VALUE']) and $arr_props_by_code['axis']['VALUE']!='')?'<td>'.$arr_props_by_code['axis']['VALUE'].'</td>':''?>
										<td>&nbsp;</td>
									</tr>
								</table>
							</td>
						</tr>
					<? $i ++; } ?>
						<?/*
						<tr>
							<td>
								<?
								echo '<pre>';
								print_R($arr_props_by_code);
								echo '</pre>';
								?>
							</td>
						</tr>
						*/?>
				</table>
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
						?>
			</div>
			
			<div class="reserveBlock">
				<table>
					<tr>
						<td width="400px;">
						<p>Товаров на сумму: <nobr>
						<?
						if($CHAK_DISCOUNT>0) $arResult['DISCOUNT_PRICE'] = $arResult['DISCOUNT_PRICE'] + $CHAK_DISCOUNT;
						?>
						<?if($arResult['DISCOUNT_PRICE']>0) 
						{ 
							$f_pr = $arResult['DISCOUNT_PRICE']+$arResult['allSum']; 
							echo '<s style="color: black">'.$f_pr.' руб</s>';
						}
						?> 
						<nobr><?=$arResult["allSum_FORMATED"]?></nobr></p></td>
						<td><input  id="continue" src="/img/bt216a.png" type="image" value="submit" name="BasketRefresh"></td> <?// кнопка не рефрешит, она закрывает?>
						<td><input id="order_basket" src="/img/bt218b.png" type="image" value="submit" name="BasketOrder" id="basketOrderButton2"></td>
					</tr>
				</table> 
			</div>
		</div>
		<div class="block788c">&nbsp;</div>
	</div>
</div>

