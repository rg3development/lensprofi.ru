<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

/*
$csv = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/book1.csv');

$arr_strings=preg_split("/[\n\r]+/s", $csv);

$arr_goods = array();

$i = 0;

foreach($arr_strings as $one_string)
{
	$k=0;
	$arr_cells = explode(';', $one_string);
	foreach($arr_cells as $cell){
		$arr_goods[$i][$k] = $cell;
		$k++;
	};
	
	$i++;
};

*/

require($_SERVER['DOCUMENT_ROOT'].'/reader.php');

$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('windows-1251');
$data->read($_SERVER['DOCUMENT_ROOT'].'/book1.xls');

$bookNum = 0;

$k=0;

for ($i = 3; $i <= $data->sheets[$bookNum]['numRows']; $i++) 
{
	
	for($j=1; $j<=28; $j++)
	{
		if(trim($data->sheets[$bookNum]['cells'][$i][$j])!='')
		{
			//$arr_goods[$k][$j] = preg_replace('/^[0-9A-Za-zа-€ј-я\$\%\@\,\.\-\_\&\#]/' , '', trim($data->sheets[$bookNum]['cells'][$i][$j]));
			
			//$arr_goods[$k][$j] = iconv('windows-1251', 'utf-8', trim($data->sheets[$bookNum]['cells'][$i][$j]));
			
			$arr_goods[$k][$j] = trim($data->sheets[$bookNum]['cells'][$i][$j]);
		};
		
	};		
	    
	$k++;
}


	
// echo '<pre>';
// print_R($arr_goods);
// echo '</pre>';


$ARR_REAL = array();
$real_counter = 0;

foreach($arr_goods as $arr_col)
{
	if(count($arr_col)<=2 and isset($arr_col[1]) and !is_numeric($arr_col[1])) // its a title/delimeter
	{
		continue;
	};
	
	if(count($arr_col)==2 OR count($arr_col)==3)
	{
		if(isset($arr_col[28]) AND $arr_col[28]!='')  $ARR_REAL[$real_counter][28] = $arr_col[28]; // количество
		
		//разобрать 26 и 27
		if(isset($arr_col[26]) and isset($arr_col[27]))
		{
			$arr_variants = explode(',', $arr_col[26]);
			$arr_variants_values = explode(',', $arr_col[27]);
			
			foreach($arr_variants as $one)
			{
				foreach($arr_variants_values as $one_var_value)
				{
					$ARR_REAL[$real_counter]['VARS'][$one][] = $one_var_value;
				};
			};
		}
		else
		{
			die('not exist 26 or 27');
		};
		
	}
	else
	{
		//first 
		$real_counter++;
		
		//element themself	
		$ARR_REAL[$real_counter] = $arr_col;
		
		//разобрать 26 и 27
		$arr_variants = explode(',', $arr_col[26]);
		$arr_variants_values = explode(',', $arr_col[27]);
		
		foreach($arr_variants as $one)
		{
			$ARR_REAL[$real_counter]['VARS'][$one] = $arr_variants_values;
		};

	};
	
};


	// echo '<pre>';
	// print_R($ARR_REAL);
	// echo '</pre>';

	// die();
	
	
	$cat_iblock_id = 4;
	
	$success_counter=0;
	
	foreach($ARR_REAL as $ONE)
	{
		
		$elem = new CIBlockElement;
		$PROP = array();
		
		$PROP['LENSTYPE'] = $ONE[3]; 
		$PROP['PRODUCER'] = $ONE[5];
		$PROP['BRAND'] = $ONE[6];
		$PROP['USETIME'] = $ONE[4];
		$PROP['QPERPACK'] = $ONE[9];
		
		//$PROP['ARTICUL'] = $ONE[];
		
		//$PROP['CERT'] = $ONE[];
		
		$PROP['DESINFECTION'] = $ONE[11];
		$PROP['WEARING'] = $ONE[10];
		$PROP['HUMIDITY'] = $ONE[12];
		$PROP['OXYCOEF'] = $ONE[13];
		$PROP['UFDEF'] = $ONE[14];
		$PROP['MATERIAL'] = $ONE[15];
		$PROP['DIAMETER'] = $ONE[16];
		$PROP['DESIGN'] = $ONE[19];
		$PROP['THICKNESS'] = $ONE[17];
		$PROP['INVERSE'] = $ONE[18];
		$PROP['OPACITY'] = $ONE[20];
		$PROP['FDAGROUP'] = $ONE[21];
		$PROP['MADEIN'] = $ONE[22];
		
		$BASE_PRICE = $ONE[23];
		$ANONS = $ONE[25];
		$DETAIL = $ONE[24];
		
		$arr_pairs = array();
		$i=0;
		foreach($ONE['VARS'] as $var_key=>$arr_one_var)
		{
			$key = 'n'.$i;
			$arr_pairs[$key] = array(
									"VALUE" => $var_key,
									"DESCRIPTION" => implode(',', $arr_one_var)
								);	
			$i++;
		};
		
		$PROP['RADIUS'] = $arr_pairs;
		
		
		// echo '<pre>';
		// print_R($PROP['RADIUS']);
		// echo '</pre>';
		
		
		/*
		$file_arr='';
		if($_FILES['child_photo']['error']=='0')
		{
			$file_arr=$_FILES['child_photo'];
		};
		*/
		
		//$pict_name = $_SERVER['DOCUMENT_ROOT'].'/pict/'.iconv('utf-8', 'windows-1251', $ONE[7]).'.jpg';
		$pict_name = $_SERVER['DOCUMENT_ROOT'].'/pict/'.$ONE[7].'.jpg';
		
		/*
		if(!file_exists($pict_name))
		{
			echo 'dddd';
		}
		*/
		
		
		// echo $pict_name.' <br />';
		 $arr_pict = CFile::MakeFileArray($pict_name);
		
		$cert_name = $_SERVER['DOCUMENT_ROOT'].'/sert/'.$ONE[8].'.jpg';
		$PROP['CERT'] = CFile::MakeFileArray($cert_name);
		
		// echo '<pre>';
		// print_R($arr_pict);
		// echo '</pre>';
		
		//echo $_SERVER['DOCUMENT_ROOT'].'/pict/'.$ONE[8].'.jpg';
		
		//$arr_sert = CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT'].'/pict/'.$ONE[8].'.jpg');
		
		
		// echo '<pre>';
		// print_R($arr_sert);
		// echo '</pre>';
		
		
		
		$arr_fields = Array(		
								"CREATED_BY"    => $USER->GetID(),
								"MODIFIED_BY"    => $USER->GetID(),  
								"IBLOCK_SECTION_ID" => 1,
								"IBLOCK_ID"      => $cat_iblock_id,  
								"PROPERTY_VALUES"=> $PROP,  
								"NAME" => $ONE[2],  
								"ACTIVE" => "Y",
								"DETAIL_PICTURE" => $arr_pict,
								"DETAIL_TEXT" => $DETAIL,
								"PREVIEW_TEXT" => $ANONS
							);			
		
		$new_id= $elem->Add($arr_fields, false, false, true);
		
		if(!$new_id)
		{
			echo "Error: ".$elem->LAST_ERROR;
		}
		else
		{
			$price_fields =array();
			
			$price_fields = Array(
								"PRODUCT_ID" => $new_id,
								"CATALOG_GROUP_ID" => 1,
								"PRICE" => $BASE_PRICE,
								"CURRENCY" => "RUB",
							);
			
			if($res = CPrice::Add($price_fields))
			{
				$success_counter++;
				//echo $res;
			};
		};
		
		
		//break;
		
	};

echo count($ARR_REAL).' = '.$success_counter;
	
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
