<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
$ARR_SOURCE = array();
foreach($_REQUEST['fields'] as $field)
{
	$ARR_SOURCE[$field['name']] = $field['value'];
};

// echo '<pre>';
// print_R($ARR_SOURCE);
// echo '</pre>';

// die();

if(isset($ARR_SOURCE['prod_id']) and $ARR_SOURCE['prod_id'])
{
	
	/*
	function getPropCodeList_f($iblock_id)
	{
		$arr_prop = array();
		$db_list = CIBlock::GetProperties($iblock_id, Array(), Array());
		while($one_prop = $db_list->GetNext())
		{
			$arr_prop[] = 'PROPERTY_'.$one_prop['CODE'];
		};
		return $arr_prop;
	};	
	
	$arr_product = array();
	$arr_order=array('SORT'=>'ASC');
	$arr_filter = Array('IBLOCK_ID'=>4, 'ID'=>$ARR_SOURCE['prod_id']);
	$arr_sel_fields = array('IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT'); 
	
	$arr_sel_fields = array_merge($arr_sel_fields, getPropCodeList_f(4));
	
	$rsElem = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_sel_fields);
	if($arr_elem = $rsElem->GetNext())
	{
		$arr_product = $arr_elem;
	};
	*/
	
	// echo '<pre>';
	// print_R($arr_product);
	// echo '</pre>';

	/*
	$DISCOUNT_PRICE = 0;
	$FULL_PRICE = 0;
	$db_res1 = CPrice::GetList(array(), array("PRODUCT_ID" => $arr_product['ID'], 'CATALOG_GROUP_ID'=>2)); // RETAIL PRICE
	while($one_pr = $db_res1->GetNext())
	{
		// echo '<pre>';
		// print_R($one_pr);
		// echo '</pre>';
		
		$from = $one_pr['QUANTITY_FROM'];
		$to = 10000000000;
		if($one_pr['QUANTITY_TO']!='') $to = $one_pr['QUANTITY_TO'];
		
		if($ARR_SOURCE['count_l']>=$from and $ARR_SOURCE['count_l']<=$to)
		{
			$DISCOUNT_PRICE = $one_pr['PRICE'];;
		};
		
		if($FULL_PRICE<$one_pr['PRICE']) $FULL_PRICE = $one_pr['PRICE'];
	};
	*/
	
	
	CModule::IncludeModule('catalog');
	
	if($ARR_SOURCE['count_l']>0)
	{	
		$arr_params = array();
		
		if(isset($ARR_SOURCE['radius_l']) and $ARR_SOURCE['radius_l']!='')
		{
			$arr_params[] = array(
									"NAME" => 'Радиус',    
									"CODE" => 'radius',
									"VALUE" => $ARR_SOURCE['radius_l']
								);  
		};
		
		if(isset($ARR_SOURCE['force_l']) and $ARR_SOURCE['force_l']!='')
		{
			$arr_params[] = array(
									"NAME" => 'Оптическая сила',    
									"CODE" => 'force',
									"VALUE" => $ARR_SOURCE['force_l']
								);  
		}
		
		//for toric
		if(isset($ARR_SOURCE['cyl_l']) and $ARR_SOURCE['cyl_l']!='')
		{
			$arr_params[] = array(
									"NAME" => 'Оптическая сила цилиндра',    
									"CODE" => 'cyl_force',
									"VALUE" => $ARR_SOURCE['cyl_l']
								);  
		}
		
		//for toric
		if(isset($ARR_SOURCE['axis_l']) and $ARR_SOURCE['axis_l']!='')
		{
			$arr_params[] = array(
									"NAME" => 'Ось',    
									"CODE" => 'axis',
									"VALUE" => $ARR_SOURCE['axis_l']
								);  
		}
		
		//for multifoc
		if(isset($ARR_SOURCE['addid_l']) and $ARR_SOURCE['addid_l']!='')
		{		
			$arr_params[] = array(
									"NAME" => 'Аддидация',    
									"CODE" => 'addid',
									"VALUE" => $ARR_SOURCE['addid_l']
								);
		}
		
		//for colors
		if(isset($ARR_SOURCE['color_l']) and $ARR_SOURCE['color_l']!='')
		{		
			$arr_params[] = array(
									"NAME" => 'Цвет',    
									"CODE" => 'color',
									"VALUE" => $ARR_SOURCE['color_l']
								);
		}
		
		$arr_params[] = array(
								"NAME" => 'Глаз',    
								"CODE" => 'eye',
								"VALUE" => 'Левый'
							);
		
		
		// echo '<pre>';
		//print_R($ARR_SOURCE['prod_id']);
		//print_R($ARR_SOURCE['count_l']);
		// print_R($arr_params);
		// echo '</pre>';
		
		//придется заменить этот лаконичный код
		$res = Add2BasketByProductID($ARR_SOURCE['prod_id'], $ARR_SOURCE['count_l'], $arr_params);
		basket_recounter();
		//на
		/*
		 $arFields = array(    
							"PRODUCT_ID" => 51,    
							"PRODUCT_PRICE_ID" => 0,    
							"PRICE" => 138.54,    
							"CURRENCY" => "RUB",    
							"WEIGHT" => 530,    
							"QUANTITY" => 1,    
							"LID" => LANG,    
							"DELAY" => "N",    
							"CAN_BUY" => "Y",    
							"NAME" => "Чемодан кожаный",    
							 "DETAIL_PAGE_URL" => "/".LANG."/detail.php?ID=51"  
							);  
		$arFields["PROPS"] = $arProps;  
		$res = CSaleBasket::Add($arFields);
		*/
		echo $res;
	};
	
	if($ARR_SOURCE['count_r']>0)
	{	
		$arr_params = array();
		
		if(isset($ARR_SOURCE['radius_r']) and $ARR_SOURCE['radius_r']!='')
		{
			$arr_params[] = array(
									"NAME" => 'Радиус',    
									"CODE" => 'radius',
									"VALUE" => $ARR_SOURCE['radius_r']
								);  
		};
		
		if(isset($ARR_SOURCE['force_r']) and $ARR_SOURCE['force_r']!='')
		{
			$arr_params[] = array(
									"NAME" => 'Оптическая сила',    
									"CODE" => 'force',
									"VALUE" => $ARR_SOURCE['force_r']
								);  
		};
		
		//for toric
		if(isset($ARR_SOURCE['cyl_r']) and $ARR_SOURCE['cyl_r']!='')
		{
			$arr_params[] = array(
									"NAME" => 'Оптическая сила цилиндра',    
									"CODE" => 'cyl_force',
									"VALUE" => $ARR_SOURCE['cyl_r']
								);  
		}
		
		//for toric
		if(isset($ARR_SOURCE['axis_r']) and $ARR_SOURCE['axis_r']!='')
		{
			$arr_params[] = array(
									"NAME" => 'Ось',    
									"CODE" => 'axis',
									"VALUE" => $ARR_SOURCE['axis_r']
								);  
		}
		
		//for multifoc
		if(isset($ARR_SOURCE['addid_r']) and $ARR_SOURCE['addid_r']!='')
		{		
			$arr_params[] = array(
									"NAME" => 'Аддидация',    
									"CODE" => 'addid',
									"VALUE" => $ARR_SOURCE['addid_r']
								);
		}
		
		//for colors
		if(isset($ARR_SOURCE['color_r']) and $ARR_SOURCE['color_r']!='')
		{		
			$arr_params[] = array(
									"NAME" => 'Цвет',    
									"CODE" => 'color',
									"VALUE" => $ARR_SOURCE['color_r']
								);
		}
		
		
		// echo '<pre>';
		// print_R($arr_params);
		// echo '</pre>';
		
		
		$res = Add2BasketByProductID($ARR_SOURCE['prod_id'], $ARR_SOURCE['count_r'], $arr_params);
		basket_recounter();
		//echo $res;
	};
	
	// for accessories
	if($ARR_SOURCE['count']>0)
	{
		$res = Add2BasketByProductID($ARR_SOURCE['prod_id'], $ARR_SOURCE['count'], $arr_params);
		basket_recounter();
	}
	
	/*
	if (CModule::IncludeModule("sale"))
	{
				$arFields = array(    
									"PRODUCT_ID" => $arr_product['ID'],    
									"PRODUCT_PRICE_ID" => rand(),    //дополнительный айдишник
									"PRICE" => $DISCOUNT_PRICE,    
									"CURRENCY" => "RUB",    
									"WEIGHT" => '',    
									"QUANTITY" => $ARR_SOURCE['count_l'],    
									"LID" => LANG,    
									"DELAY" => "N",    
									"CAN_BUY" => "Y",    
									"NAME" => kt::getSectionName($arr_product['IBLOCK_ID'],$arr_product['IBLOCK_SECTION_ID']).' '.$arr_product['NAME'],    
									"CALLBACK_FUNC" => "",    
									"MODULE" => "catalog",    
									"NOTES" => "",
									//"FUSER_ID" => $USER->GetID(),									
									"ORDER_CALLBACK_FUNC" => "",    
									"DETAIL_PAGE_URL" => ""  
									);  
				$arProps = array();
				
				// собираем свойства
				// if(isset($_REQUEST['dimension']))
				// {
					// $arProps[] = array( "NAME" => 'размеры',    
										// "VALUE" => $_REQUEST['dimension'] 
									// );  
				// };				
				
				//$arFields["PROPS"] = $arProps;  
				
				echo '<pre>';
				print_R($arFields);
				echo '</pre>';
				
				
				$res_id = CSaleBasket::Add($arFields);
				
				echo 'succs'.$res_id;
	};
	*/
	
};
?>