<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
CModule::IncludeModule("iblock");

$opros_iblock = 6;


// echo '<pre>';
// print_R($_REQUEST);
// echo '</pre>';

if(isset($_REQUEST['id']))
{
	$arr_elem = array();

	$arr_order= array('SORT'=>'ASC');
	$arr_select=array('ID', 'IBLOCK_ID', 'NAME', 'PROPERTY_ip', 'PROPERTY_question', 'PROPERTY_ip');
	$arr_filter=array('IBLOCK_ID'=>$opros_iblock, 'ACTIVE'=>'Y', 'ID'=>$_REQUEST['id']);
	$res = CIBlockElement::GetList($arr_order, $arr_filter, false, false, $arr_select);

	if($one=$res->GetNext())
	{
		$arr_elem[] = $one;
	};

	$arr_elem = $arr_elem[0];
	
	// echo '<pre>';
	// print_R($arr_elem);
	// echo '</pre>';
	
	
	//готовим массив результатов опроса
	$arr_prop_val = array();
		
	$i=0;
	foreach($arr_elem['PROPERTY_QUESTION_VALUE'] as $one_val)
	{
		$key = 'n'.$i;
		$descr = 0;
		if($arr_elem['PROPERTY_QUESTION_DESCRIPTION'][$i]!='') $descr = $arr_elem['PROPERTY_QUESTION_DESCRIPTION'][$i];
		
		$arr_prop_val[$key] = array(
								"VALUE" => $one_val,
								"DESCRIPTION" => $descr
							);	
		$i++;
	};
	
	// защита )))
	$arr_ip = array();
	if($arr_elem['PROPERTY_IP_VALUE']!='')
	{
		$arr_ip = unserialize($arr_elem['PROPERTY_IP_VALUE']);
	};
	$arr_ip[] = $_SERVER['REMOTE_ADDR'];
	
	$ser_arr = serialize($arr_ip);
	
	// echo '<pre>';
	// print_R($arr_ip);
	// echo '</pre>';
	
	// процесс голосования - здесь
	if(isset($_REQUEST['answer']))
	{
		//плюсуем голос!!
		$arr_prop_val['n'.$_REQUEST['answer']]['DESCRIPTION'] = $arr_prop_val['n'.$_REQUEST['answer']]['DESCRIPTION'] + 1;
		
		if(CIBlockElement::SetPropertyValueCode($_REQUEST['id'], 'question', $arr_prop_val)) 
		{
			//echo 'good';
		};
		
		//пишем IP
		if(CIBlockElement::SetPropertyValueCode($_REQUEST['id'], 'ip', $ser_arr)) 
		{
			//echo 'good';
		};
	};
	
	
	// считаем всего голосов
	$all_count = 0;
	foreach($arr_prop_val as $one)
	{
		$all_count = $all_count + $one['DESCRIPTION'];
	};
	
	// результат - массив в процентах	
	$arr_diagr = array();
	
	$i=0;
	$all_summ = 0;
	foreach($arr_prop_val as $key=>$one)
	{
		$i++;
		if($i!=count($arr_prop_val))
		{
			$arr_diagr[$key] = round(($one['DESCRIPTION']/$all_count)*100);
			$all_summ = $all_summ + $arr_diagr[$key];
		}
		else
		{
			$arr_diagr[$key] = 100 - $all_summ; // чтобы четко
		};
		
	};
	
	arsort($arr_diagr);
	reset($arr_diagr);
	
	// echo '<pre>';
	// print_R($arr_diagr);
	// echo '</pre>';
	
	$W = 280; //px
	
	$i=0;
	foreach($arr_diagr as $key=>$percent):
		?>
		<div style="padding: 0px 0 0px 0">
			<?=$arr_prop_val[$key]['VALUE'].' - '.$percent.'% '.'('.$arr_prop_val[$key]['DESCRIPTION'].')';?>
		</div>
		<div style="padding: 2px 0 2px 0">
			<div style="background:<?=($i==0)?'#D72A18':'#B5B5B5';?>; height: 10px; width: <?=round(280*$percent/100)?>px"></div>
		</div>
		<?
		$i++;
	endforeach;
};
?>