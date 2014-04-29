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

$bookNum = 3;

$k=0;

for ($i = 3; $i <= $data->sheets[$bookNum]['numRows']; $i++) 
{
	
	for($j=1; $j<=27; $j++)
	{
		if(trim($data->sheets[$bookNum]['cells'][$i][$j])!='')
		{	
			$arr_goods[$k][$j] = trim($data->sheets[$bookNum]['cells'][$i][$j]);
		};
		
	};		
	    
	$k++;
}


	
// echo '<pre>';
// print_R($arr_goods);
// echo '</pre>';

// die();

$ARR_REAL = array();
$real_counter = 0;

foreach($arr_goods as $arr_col)
{
	if(count($arr_col)<=2 and isset($arr_col[1]) and !is_numeric($arr_col[1])) // its a title/delimeter
	{
		continue;
	};
			
	//first 
	$real_counter++;
	
	//element themself	
	$ARR_REAL[$real_counter] = $arr_col;

	
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
		$PROP['USETIME'] = $ONE[4];
		$PROP['BRAND'] = $ONE[6];
		
		$pict_name = $_SERVER['DOCUMENT_ROOT'].'/pict/'.$ONE[7].'.jpg';
		//echo $pict_name;
		$arr_pict = CFile::MakeFileArray($pict_name);
		// echo '<pre>';
		// print_R($arr_pict);
		// echo '</pre>';
		$cert_name = $_SERVER['DOCUMENT_ROOT'].'/sert/'.$ONE[8].'.jpg';
		$PROP['CERT'] = CFile::MakeFileArray($cert_name);
		
		$PROP['QPERPACK'] = $ONE[9];
		
		$PROP['WEARING'] = $ONE[10];
		$PROP['DESINFECTION'] = $ONE[11];
		$PROP['HUMIDITY'] = $ONE[12];
		$PROP['OXYCOEF'] = $ONE[13];
		$PROP['UFDEF'] = $ONE[14];
		$PROP['MATERIAL'] = $ONE[15];
		$PROP['DIAMETER'] = $ONE[16];
		$PROP['THICKNESS'] = $ONE[17];
		$PROP['INVERSE'] = $ONE[18];
		$PROP['DESIGN'] = $ONE[19];
		//$PROP['OPACITY'] = $ONE[20];
		$PROP['FDAGROUP'] = $ONE[20];
		$PROP['MADEIN'] = $ONE[21];
		
		$BASE_PRICE = $ONE[22];
		$DETAIL = $ONE[23];
		$ANONS = $ONE[24];
		
		
		
		$PROP['RADIUS']  = array(
								'n1' => array(
												"VALUE" => $ONE[25],
												"DESCRIPTION" => $ONE[26]
											)
								);	
		
		$PROP['COLORS'] = $ONE[27];
		
		
		$arr_fields = Array(		
								"CREATED_BY"    => $USER->GetID(),
								"MODIFIED_BY"    => $USER->GetID(),  
								"IBLOCK_SECTION_ID" => 4, //COLORS
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
