<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?// if (!empty($arResult)) {
	//$APPLICATION->SetTitle($arResult['PROPERTY_51']);
//} ?>
<div class="block730b" style="padding-top: 0px">
	<div class="block220">
		<?
		session_start();
		$param_str = '';
		if(isset($_SESSION['FILTER_HREF'])) $param_str = $_SESSION['FILTER_HREF'];
		unset($_SESSION['FILTER_HREF']);
		?>
		<?/* OLD STYLE
		<a href="/catalog/<?=$param_str ?>" class="srv2" style="line-height: 22px"><span>Вернуться к списку товаров</span></a>
		*/?>
		<a href="<?=($arResult['IBLOCK_SECTION_ID']!=7)?"javascript:show_filter_panel1();":"javascript:show_filter_panel2();"?>" class="srv2" style="line-height: 22px"><span>Вернуться к списку товаров</span></a>
		<!--выводим превью-->
		<? 
		$arr_watermark = Array(
									Array( 'name' => 'watermark',
									  'position' => 'mc',
									  'size'=>'real',
									  //'coefficient' => '0.8',
									  'type'=>'image',
									  'alpha_level'=>'100',
									  'file'=>$_SERVER['DOCUMENT_ROOT'].'/product_full.png', 
									  ),
								);
		
		$to_width = 449;
		$to_height = 800;
		if($arResult['DETAIL_PICTURE']['WIDTH']<$to_width or $arResult['DETAIL_PICTURE']['HEIGHT']<$to_height)
		{
			$to_height = $arResult['DETAIL_PICTURE']['HEIGHT']-1;
			$to_width = $arResult['DETAIL_PICTURE']['WIDTH']-1;
		};
		$image_resize_det = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"]["ID"], array('width' => $to_width, 'height' => $to_height), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arr_watermark);
		?>
		<a href="<?=$image_resize_det['src']?>" class="lb" rel="prettyPhoto">
			<?if(is_array($arResult["PREVIEW_PICTURE"]) || is_array($arResult["DETAIL_PICTURE"])):?>
				<?
					$arFilter_watermark = Array(
									Array( 'name' => 'watermark',
									  'position' => 'mc',
									  'size'=>'real',
									  //'coefficient'    => '0.8',
									  'type'=>'image',
									  'alpha_level'=>'100',
									  'file'=>$_SERVER['DOCUMENT_ROOT'].'/product preview.png', 
									  ),
									);
					$to_width = 217;
					$to_height = 217;
					if($arResult['DETAIL_PICTURE']['WIDTH']<$to_width or $arResult['DETAIL_PICTURE']['HEIGHT']<$to_height)
					{
						$to_height = $arResult['DETAIL_PICTURE']['HEIGHT']-1;
						$to_width = $arResult['DETAIL_PICTURE']['WIDTH']-1;
					};
					$image_resize_prev = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"]["ID"], array('width' => $to_width, 'height' => $to_height), BX_RESIZE_IMAGE_PROPORTIONAL,  false, $arFilter_watermark);
				?>
				<img style="width: 218px" class="prevSm" src="<?=$image_resize_prev['src']?>" <?/*alt="<?=$arResult["NAME"]?>"*/?> <?/*title="<?=$arResult["NAME"]?>"*/?> />
			<?endif;?>
		</a>
		<!--выводим цену-->
		<!--вывод без диапазона-->
		<?foreach($arResult["PRICES"] as $code=>$arPrice):?>
			<?if($arPrice["CAN_ACCESS"]):?>s
				<?if($arPrice["DISCOUNT_VALUE"] < $arPrice["VALUE"]):?>
					<s><?=$arPrice["PRINT_VALUE"]?></s> <span class="price1"><?=$arPrice["PRINT_DISCOUNT_VALUE"]?></span>
				<?else:?><span class="price1"><?=$arPrice["PRINT_VALUE"]?></span><?endif;?>
			<?endif;?>
		<?endforeach;?>
	
		<!--вывод c диапазоном-->
		<?if(is_array($arResult["PRICE_MATRIX"])):?>
			<?foreach ($arResult["PRICE_MATRIX"]["ROWS"] as $ind => $arQuantity) { ?>
				<?foreach($arResult["PRICE_MATRIX"]["COLS"] as $typeID => $arType) { ?>
					<? if(in_array($arType['NAME'], $arParams['PRICE_CODE']) && $arQuantity["QUANTITY_TO"] == 1 ) { ?>
						<span class="price4"><?=FormatCurrency($arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"], $arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"]);?></span>
					<? } ?>
				<? } ?>
			<? } ?>
			<table class="data3" style="width: 220px">
				<tr>
					<?if(count($arResult["PRICE_MATRIX"]["ROWS"]) >= 1 && ($arResult["PRICE_MATRIX"]["ROWS"][0]["QUANTITY_FROM"] > 0 || $arResult["PRICE_MATRIX"]["ROWS"][0]["QUANTITY_TO"] > 0)):?>
						<th>количество</th>
					<?endif;?>
					<?foreach($arResult["PRICE_MATRIX"]["COLS"] as $typeID => $arType):?>
						<? if(in_array($arType['NAME'], $arParams['PRICE_CODE'])) { ?>
							<th>цена за упаковку</th>
						<? } ?>
					<?endforeach?>
				</tr>
				<? $hack_counter=0;?>
				<?foreach ($arResult["PRICE_MATRIX"]["ROWS"] as $ind => $arQuantity):?>
				<?
				if($hack_counter==0)
				{
					$hack_counter++;
					continue;
				};
				?>
				<tr>
					<?if(count($arResult["PRICE_MATRIX"]["ROWS"]) > 1 || count($arResult["PRICE_MATRIX"]["ROWS"]) == 1 && ($arResult["PRICE_MATRIX"]["ROWS"][0]["QUANTITY_FROM"] > 0 || $arResult["PRICE_MATRIX"]["ROWS"][0]["QUANTITY_TO"] > 0)):?>
						<td><?php
							if (IntVal($arQuantity["QUANTITY_FROM"]) > 0 && IntVal($arQuantity["QUANTITY_TO"]) > 0 && IntVal($arQuantity["QUANTITY_FROM"]) != IntVal($arQuantity["QUANTITY_TO"]))
								echo 'от '.$arQuantity["QUANTITY_FROM"].' до '.$arQuantity["QUANTITY_TO"]; 
							elseif (IntVal($arQuantity["QUANTITY_FROM"]) > 0 && IntVal($arQuantity["QUANTITY_TO"]) > 0 && IntVal($arQuantity["QUANTITY_FROM"]) == IntVal($arQuantity["QUANTITY_TO"]))
								echo kt::noun($arQuantity["QUANTITY_FROM"], array('упаковка', 'упаковки', 'упаковок'));
							elseif (IntVal($arQuantity["QUANTITY_FROM"]) > 0)
								echo $arQuantity["QUANTITY_FROM"].' и более';
							elseif (IntVal($arQuantity["QUANTITY_TO"]) > 0)
								echo str_replace("#TO#", $arQuantity["QUANTITY_TO"], GetMessage("CATALOG_QUANTITY_TO"));
						?></td>
					<?endif;?>
					<?foreach($arResult["PRICE_MATRIX"]["COLS"] as $typeID => $arType):?>
						<? if(in_array($arType['NAME'], $arParams['PRICE_CODE'])) { ?>
						<td>
							<?if($arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["DISCOUNT_PRICE"] < $arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"])
								echo '<s>'.FormatCurrency($arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"], $arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"]).'</s> <span class="catalog-price">'.FormatCurrency($arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["DISCOUNT_PRICE"], $arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"])."</span>";
							else
								echo FormatCurrency($arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["PRICE"], $arResult["PRICE_MATRIX"]["MATRIX"][$typeID][$ind]["CURRENCY"]);
							?>
						<? } ?>
						</td>
					<?endforeach?>
				</tr>
				<?endforeach?>
				<tr>
					<td colspan="2"><span class="note1">Покупая больше, вы платите меньше</span></td>
				</tr>
				</table>
				<?if($arParams["PRICE_VAT_SHOW_VALUE"]):?>
					<?if($arParams["PRICE_VAT_INCLUDE"]):?>
						<small><?=GetMessage('CATALOG_VAT_INCLUDED')?></small>
					<?else:?>
						<small><?=GetMessage('CATALOG_VAT_NOT_INCLUDED')?></small>
					<?endif?>
				<?endif;?>
			<?endif?>
	
		<!--выводим свойства-->
		<?
		$arr_first_block = array('PRODUCER', 'USETIME', 'WEARING', 'QPERPACK', 'HUMIDITY', 'OXYCOEF');
		$arr_second_block = array('LENSTYPE', 'MATERIAL', 'DESIGN', 'UFDEF', 'DIAMETER', 'THICKNESS', 'DESINFECTION', 'INVERSE', 'OPACITY', 'FDAGROUP', 'MADEIN', 'BRAND');
		?>
		<?if($arResult['IBLOCK_SECTION_ID']!=7):?>
			<div class="brd218" style="border-bottom: 0;">
				<table class="data2">
					<?foreach($arr_first_block as $prop_code):?>
						<?if($arResult['PROPERTIES'][$prop_code]['VALUE']!=''):?>
							<tr>
								<td><?=$arResult['PROPERTIES'][$prop_code]['NAME']?>:</td>
								<td><?=($prop_code=='USETIME')?get_period_str($arResult['PROPERTIES'][$prop_code]['VALUE']):$arResult['PROPERTIES'][$prop_code]['VALUE']?></td>						
							</tr>
						<?endif;?>
					<?endforeach;?>
				</table>
			</div>      			
			<div class="brd218 tdHide" style="border-top:0; display: none">
				<table class="data2">
					<?foreach($arr_second_block as $prop_code):?>
						<?if($arResult['PROPERTIES'][$prop_code]['VALUE']!=''):?>
							<tr>
								<td><?=$arResult['PROPERTIES'][$prop_code]['NAME']?>:</td>
								<td><?=$arResult['PROPERTIES'][$prop_code]['VALUE']?></td>						
							</tr>
						<?endif;?>
					<?endforeach;?>
				</table>
			</div>
			<div class="srv3 t2">&nbsp;</div>
		<?endif;?>
		<?if($arResult['PROPERTIES']['CERT']['VALUE']>0):?>
				<?
				$arFilter_watermark = Array(
									Array( 'name' => 'watermark',
									  'position' => 'mc',
									  'size'=>'real',
									  //'coefficient'    => '0.8',
									  'type'=>'image',
									  'alpha_level'=>'100',
									  'file'=>$_SERVER['DOCUMENT_ROOT'].'/cert_water.png', 
									  ),
									);
				$to_width = 600;
				$to_height = 700;
				
				$arr_img_size = getimagesize($_SERVER['DOCUMENT_ROOT'].CFile::GetPath($arResult['PROPERTIES']['CERT']['VALUE']));
				
				if($arr_img_size[0]<$to_width or $arr_img_size[1]<$to_height)
					{
						$to_width = $arr_img_size[0]-1;
						$to_height = $arr_img_size[1]-1;
					};
				$image_resize_big = CFile::ResizeImageGet($arResult['PROPERTIES']['CERT']['VALUE'], array('width' => $to_width, 'height' => $to_height), BX_RESIZE_IMAGE_PROPORTIONAL, false, $arFilter_watermark);
				?>
				<p class="srv4"><a href="<?=$image_resize_big['src']?>" rel="prettyPhoto" class="lb1">Сертификат на продукт</a></p>
		<?endif;?>
		<? if (!empty($arResult['PROPERTIES']['CANONIC_PAGE']['VALUE'])) : ?>
		<? $APPLICATION->AddHeadString('<link rel="canonical" href="'.$arResult['PROPERTIES']['CANONIC_PAGE']['VALUE'].'" />', true); ?>
		<!--<p class="srv4"><a href="<?=$arResult['PROPERTIES']['CANONIC_PAGE']['VALUE']?>"><?=$arResult['PROPERTIES']['NAME_CANONIC_PAGE']['VALUE']?></a></p>-->
		<? endif; ?>
		<div style="padding: 0 0 0 0">
			<?
				$page_url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
				$page_name =$arResult['NAME'];
				$page_descr =$arResult["PREVIEW_TEXT"];
			?>
			<table class="verst new_life" style="margin: 0 0 0 auto">
				<tr>
					<td style='width:30px'>
						<a href="http://vkontakte.ru/share.php?url=<?=$page_url?>&title=<?=$page_name?>&description=<?=$page_descr?>" target="_blank" rel="nofollow"><img src="/images/vkontakte.jpg" alt="" /></a>
					</td>
					<td style='width:30px'>
						<a class="Tiptip" href="https://www.facebook.com/sharer.php?u=<?=$page_url?>&t=<?=$page_name?>" target="_blank" rel="nofollow"><img  src="/images/facebook.jpg" alt="" /></a>
					</td>
					<td>
						<a class="Tiptip" href="http://twitter.com/share?&text=<?=$page_name?>&url=<?=$page_url?>" target="_blank" rel="nofollow"><img  src="/images/twitter.jpg" alt="" /></a>
					</td>
				</tr>
			</table>
			<?/*<table class="">
				<tr>
					<td style="vertical-align: middle">
						<div style="width: 50px; overflow: hidden">
							<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="" send="false" layout="button_count" width="90" show_faces="false" font="lucida grande"></fb:like>
						</div>
					</td>
					<td style="vertical-align: middle">
						<div style="width: 80px; overflow: hidden">
														
							<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?49"></script>

							<script type="text/javascript">
							  VK.init({apiId: 2979345, onlyWidgets: true});
							</script>

							<!-- Put this div tag to the place, where the Like block will be -->
							<div id="vk_like"></div>
							<script type="text/javascript">
							VK.Widgets.Like("vk_like", {type: "mini", height: 18});
							</script>
													
							<div id="vk_like"></div>
							<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?<?=rand();?>"></script>
							<script type="text/javascript">
							  VK.init({apiId: 2979345, onlyWidgets: true});
							  VK.Widgets.Like("vk_like", {type: "mini"});
							</script>
							
						</div>
					</td>
					<td style="vertical-align: middle">
						<div style="padding: 5px 0 0 0">
							<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-lang="ru">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
						</div>
					</td>
				</tr>
			</table>*/?>
		</div>
	</div>
	<form id="prop_form" name="prop_form" method="post">
	<div class="block490">
		<?
		if(count($arResult["LINKED_ELEMENTS"]>0)) $GLOBALS['LEMS'] = $arResult["LINKED_ELEMENTS"];
		?>
		<?if($arResult['IBLOCK_SECTION_ID']!=7):?>
			<? // ДЛЯ ЛИНЗ ?>
			<!--<h1 style="font-size: 18px;"><?=kt::getSectionName($arResult['IBLOCK_ID'], $arResult['IBLOCK_SECTION_ID'])?><br /><?=!empty($arResult['PROPERTY_51']) ? $arResult['PROPERTY_51'] : $arResult['NAME']?></h1>-->
			<h1 style="font-size: 18px;"><?=kt::getSectionName($arResult['IBLOCK_ID'], $arResult['IBLOCK_SECTION_ID'])?><br /><?=$arResult['NAME']?></h1>
		<?else:?>
			<?if($arResult['PROPERTIES']['LENSTYPE']['VALUE']!=''):?>
				<!--<h1 style="font-size: 18px;"><?=$arResult['PROPERTIES']['LENSTYPE']['VALUE']?><br /><?=!empty($arResult['PROPERTY_51']) ? $arResult['PROPERTY_51'] : $arResult['NAME']?></h1>-->
					<h1 style="font-size: 18px;"><?=$arResult['PROPERTIES']['LENSTYPE']['VALUE']?><br /><?=$arResult['NAME']?></h1>
			<?else:?>
				<? // просто "средство ухода"?>
				<!--<h1 style="font-size: 18px;"><?=kt::getSectionName($arResult['IBLOCK_ID'], $arResult['IBLOCK_SECTION_ID'])?><br /><?=!empty($arResult['PROPERTY_51']) ? $arResult['PROPERTY_51'] : $arResult['NAME']?></h1>-->
				<h1 style="font-size: 18px;"><?=kt::getSectionName($arResult['IBLOCK_ID'], $arResult['IBLOCK_SECTION_ID'])?><br /><?=$arResult['NAME']?></h1>
			<?endif;?>
		<?endif;?>
		<?if($arResult['IBLOCK_SECTION_ID']==7):?>
			<table class="analytic">
				<tr>
					<td colspan="2" style="text-align: left; padding-top: 14px">
						<table style="margin: 0 auto 0 0">
							<tr>
								<td style="width: 50px">Количество:</td>
								<td><select id="quantity_l" size="1" class="w60" name="count">
										<?for($i=1; $i<=5; $i++):?>
											<option value="<?=$i?>"><?=$i?></option>
										<?endfor;?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
		<?else: // не аксессуары?>
			<table class="analytic" style="margin-top: 20px;">
				<tr id="toric_ajax_target">
					<? $arr_eyes = array('l', 'r');?>
					<?foreach($arr_eyes as $eye):?>
					<td>
						<h3><?echo ($eye=='l')?'Левый':'Правый';?> глаз</h3>
						<? // ниже везде кроме name будут ПЕРЕПУТАНЫ СИЛА и РАДИУС?>
						<table>
							<tr>
								<td>Радиус кривизны (BC):</td>
								<td>
									<select onChange="force_change_<?=$eye?>();" id="force_select_<?=$eye?>"  size="1" class="w60" name="radius_<?=$eye?>">
										<?
										$arr_force = $arResult['PROPERTIES']['RADIUS']['DESCRIPTION'];
										$arr_raduis_string = $arResult['PROPERTIES']['RADIUS']['VALUE'];
										foreach($arr_force as $force):
										?>
											<option value="<?=$force?>"><?=$force?></option>
										<?endforeach;?>
									</select>
								</td>
							</tr>
							<tr>
								<td>Оптическая сила (SPH):</td>
								<td>
									<? $i=0;?>
									<?foreach($arr_raduis_string as $radius_string):?>
										<select id="radius_select_<?=$eye?><?=$i?>" size="1" class="all_radius_<?=$eye?> w60" name="force_<?=$eye?>" style="display:<?=($i==0)?'block':'none';?>" <?=($i!=0)?'disabled="disabled"':'';?>>
											<? 
											$arr_radius = explode(',', $radius_string);
											//**
											$first_n = -100;
											foreach($arr_radius as $one)
											{
												if($one>$first_n and $one<=0)
												{
													$first_n = $one;
												};
											};
											//echo $first_n;
											//**
											foreach($arr_radius as $radius):
											?>
												<option <?if($radius==$first_n) echo 'selected="selected"';?> value="<?=$radius?>"><?=$radius?></option>
											<?endforeach;?>
										</select>
										<?$i++;?>
									<?endforeach;?>
									<script type="text/javascript">
										$(document).ready(function(){
											/*
											var arr_temp = new Array();
											$.each($(".all_radius_l option"), function(){
												
											});
											*/
										});
										
										function force_change_<?=$eye?>()
										{
											 var sel_idx = $("#force_select_<?=$eye?> option").index($("#force_select_<?=$eye?> option:selected"));
			
											//alert(sel_idx);
											$(".all_radius_<?=$eye?>").attr("disabled", "disabled");
											$(".all_radius_<?=$eye?>").css("display", "none");
											 
											$("#radius_select_<?=$eye?>"+sel_idx).css("display", "block");
											$("#radius_select_<?=$eye?>"+sel_idx).removeAttr("disabled"); 
										};
									</script>
								</td>
							</tr>
							<?if($arResult['IBLOCK_SECTION_ID']==3):?>
								<tr>
									<td>Аддидация (ADD):</td>
									<td>
										<select size="1" class="w110" name="addid_<?=$eye?>">
											<?
											$arr_addid = explode(',', $arResult['PROPERTIES']['ADDIDATION']['VALUE']);
											foreach($arr_addid as $addid):
											?>
												<option value="<?=$addid?>"><?=$addid?></option>
											<?endforeach;?>
										</select>
									</td>
								</tr>
							<?endif;?>
							<?if(in_array($arResult['IBLOCK_SECTION_ID'], array(4,5,6))):?>
								<tr>
									<td>Цвет:</td>
									<td>
										<select size="1" class="w110" name="color_<?=$eye?>">
											<?
											$arr_colors = explode(',', $arResult['PROPERTIES']['COLORS']['VALUE']);
											foreach($arr_colors as $color):
											?>
											<option value="<?=$color?>"><?=$color?></option>
											<?endforeach;?>
										</select>
									</td>
								</tr>
							<?endif;?>
							<tr>
								<td>Количество упаковок:</td>
								<td>
									<select id="quantity_<?=$eye?>" size="1" class="w60" name="count_<?=$eye?>">
										<?for($i=0; $i<=5; $i++):?>
											<option <?if($eye=='l' and $i==1):?>selected="selected"<?endif;?> value="<?=$i?>"><?=$i?></option>
										<?endfor;?>
									</select>
								</td>
							</tr>
						</table>
					</td>
					<?endforeach;?>
				</tr>
		<?endif;?>
				<tr>
					<td colspan="2" id="price_ajax_block">
						<?/*
						<ul>
							<li><span>Товаров на сумму: <span>1520 руб.</span></span></li>
							<li><p>Со скидкой: 1300 руб.</p></li>
							<li><a href="<?=$arResult["ADD_URL"]?>"><img src="/img/bt151a.png" alt="добавить в корзину" /></a></li>
						</ul>
						*/?>
					</td>
				</tr>
			</table>
	</div>
		<input type="hidden" name="prod_id" value="<?=$arResult['ID']?>" />
	</form>
	<br/><br /><br /><br />
	<table>
	<tr><td>
    <?=$arResult['DETAIL_TEXT']?>
	</td></tr>
	</table>
    <br/><br />
</div>
<?/*<div id="test_div"></div>*/?>
<script type="text/javascript">
	
	<? // for TORIC linz?>
	<?if($arResult['IBLOCK_SECTION_ID']==2):?>
		function character_change()
		{
			$("#toric_ajax_target").load("/inc/ajax_toric_analyse.php", $("#prop_form").serialize(), function(){
			});
		};
		
		$(document).ready(function(){
			$("#toric_ajax_target").load("/inc/ajax_toric_analyse.php", {toric_id:<?=$arResult['ID']?>}, function(){
			});
		});
	<?endif;?>
	<? //---------------- ?>
	 
	function ajax_basket_add()
	{
		//$("#prop_form").serialize();
		$.post("/inc/ajax_basket_add.php", {fields:$("#prop_form").serializeArray()}, function(resp){
			
			update_basket_string();
			// CHAK тут добавили автовсплытие корзины после добавления
			load_basket();
		},
		"html");
	};
	
	function ajax_buy_with_add(x)
	{
		//$("#prop_form").serialize();
		$.post("/inc/ajax_basket_add.php", {fields:$("#prop_form"+x).serializeArray()}, function(resp){
			
			update_basket_string();
			// CHAK тут добавили автовсплытие корзины после добавления
			load_basket();
		},
		"html");
	};
	
	function get_price()
	{
		var calc_quantity = parseInt($("#quantity_l option:selected").attr("value")) // сработает для аксессуаров
		if($("#quantity_r").length>0) calc_quantity = calc_quantity + parseInt($("#quantity_r option:selected").attr("value")); //добавит для линз - для правого глаза
		//alert(calc_quantity);
		$.post("/inc/ajax_price.php", {prod_id:<?=$arResult['ID']?>,quantity:calc_quantity}, function(resp){
			$("#price_ajax_block").html(resp);
		},
		"html");
	};
	
	$(document).ready(function(){
			// for BIG IMAGES
			
			//$('.lb').lightBox(); /* detail picture*/
			//$('.lb1').lightBox(); /* sertificate */
		
			$("a[rel^='prettyPhoto']").prettyPhoto(
			{
				 theme: "light_square",
				 social_tools: ""
			}
			);
				
			//fixing FONT SIZES
			$("div.block490 font[size='1']").css("font-size", "10px");
			$("div.block490 font[size='2']").css("font-size", "13px");
			$("div.block490 font[size='3']").css("font-size", "16px");
			
			// for price
			$("#quantity_l").live("change", get_price);
			$("#quantity_r").live("change", get_price);
			get_price();
		});
</script>

<?// here was ban and button block?>
<div style="clear:both"></div>
<?
// echo '<pre>';
// print_R($arResult);
// echo '</pre>';

// echo '<pre>';
// print_R($arResult['PROPERTIES']);
// echo '</pre>';
?>